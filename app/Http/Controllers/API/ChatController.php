<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\RequestOffers;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCustomeRequests;
use Carbon\Carbon;
use Pusher\Pusher;

class ChatController extends Controller
{
    // Endpoint to send a message by employee
    public function sendMessageByEmployee(Request $request)
    {
        $validated = $request->validate([
            'msg' => 'required|string',
            'msg_type' => 'required|string',
            'msg_reference_id' => 'nullable|integer',
            'receiver_id' => 'required|integer'
        ]);

        $chat = Chat::firstOrCreate([
            'user_id' => $validated['receiver_id'],
            'employee_id' => $request->user()->id
        ]);

        $message = Message::create([
            'msg' => $validated['msg'],
            'sender_id' => $request->user()->id,
            'sender_type' => 'employee',
            'msg_type' => $validated['msg_type'],
            'msg_reference_id' => $validated['msg_reference_id'],
            'chat_id' => $chat->id
        ]);

        $chat->touch(); // Update "updated_at" timestamp

        $this->broadcastMessage($chat->id, 'user', $validated['receiver_id'], $message);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 200);
    }

    // Endpoint to send a message by user
    public function sendMessageByUser(Request $request)
    {
        $validated = $request->validate([
            'msg' => 'required|string',
            'msg_type' => 'required|string',
            'msg_reference_id' => 'nullable|integer',
            'receiver_id' => 'required|integer'
        ]);

        $chat = Chat::firstOrCreate([
            'user_id' => $request->user()->id,
            'employee_id' => $validated['receiver_id']
        ]);

        $message = Message::create([
            'msg' => $validated['msg'],
            'sender_id' => $request->user()->id,
            'sender_type' => 'user',
            'msg_type' => $validated['msg_type'],
            'msg_reference_id' => $validated['msg_reference_id'],
            'chat_id' => $chat->id
        ]);

        $chat->touch(); // Update "updated_at" timestamp

        $this->broadcastMessage($chat->id, 'employee', $validated['receiver_id'], $message);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 200);
    }


    public function getChats(Request $request)
    {
        $userId = $request->user()->id; // Get the authenticated user's ID
        $type = $request->type; // Type can be 'user' or 'employee'

        $chats = Chat::with(['user', 'employee'])
            ->withCount([
                'messages as unseen_by_user' => function ($query) use ($userId) {
                    $query->where('sender_type', 'employee')->where('seen', false);
                },
                'messages as unseen_by_employee' => function ($query) use ($userId) {
                    $query->where('sender_type', 'user')->where('seen', false);
                }
            ])
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1); // Fetch the latest message for each chat
            }])
            ->when($type === 'user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($type === 'employee', function ($query) use ($userId) {
                $query->where('employee_id', $userId);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Format latest_msg_date as "Today", "Yesterday", or a simple date
        $chats->each(function ($chat) {
            $latestMessage = $chat->messages->first();
            $chat->latest_msg = $latestMessage ? $latestMessage->msg : null;

            if ($latestMessage) {
                $date = Carbon::parse($latestMessage->created_at);

                if ($date->isToday()) {
                    $chat->latest_msg_date = 'اليوم';
                } elseif ($date->isYesterday()) {
                    $chat->latest_msg_date = 'أمس';
                } else {
                    $chat->latest_msg_date = $date->format('j M'); // Example: 5 May
                }
            } else {
                $chat->latest_msg_date = null;
            }

            unset($chat->messages); // Optionally, remove messages from the output
        });

        return response()->json([
            'success' => true,
            'message' => 'Chats retrieved successfully',
            'data' => $chats
        ], 200);
    }
        // Add an offer to a custom request
    public function addOffer(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:user_custome_requests,id',
            'offer_details' => 'required|string',
            'offer_price' => 'required|numeric'
        ]);

        $customRequest = UserCustomeRequests::findOrFail($validated['request_id']);

        $offer = RequestOffers::create([
            'request_id' => $validated['request_id'],
            'offer_details' => $validated['offer_details'],
            'offer_price' => $validated['offer_price']
        ]);

        $chat = Chat::firstOrCreate([
            'user_id' => $customRequest->user_id,
            'employee_id' => $request->user()->id
        ]);

        $message = Message::create([
            'msg' => 'New offer added',
            'sender_id' => $request->user()->id,
            'sender_type' => 'employee',
            'msg_type' => 'offer',
            'msg_reference_id' => $offer->id,
            'chat_id' => $chat->id
        ]);

        $chat->touch(); // Update "updated_at" timestamp

        $this->broadcastMessage($chat->id, 'user', $customRequest->user_id, $message);

        return response()->json([
            'success' => true,
            'message' => 'Offer added successfully',
            'data' => [
                'offer' => $offer,
                'message' => $message
            ]
        ], 200);
    }

    // Get chat messages and mark unseen messages as seen
    public function getChatMessages(Request $request, $chatId)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:user,employee'
        ]);

        $userId = $request->user()->id;
        $chat = Chat::findOrFail($chatId);

        if (($validated['type'] === 'user' && $chat->user_id !== $userId) ||
            ($validated['type'] === 'employee' && $chat->employee_id !== $userId)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to chat messages'
            ], 403);
        }

        // Retrieve messages
        $messages = Message::where('chat_id', $chatId)->orderBy('created_at', 'asc')->get();

        // Mark unseen messages as seen
        Message::where('chat_id', $chatId)
            ->where('seen', false)
            ->where('sender_type', $validated['type'] === 'user' ? 'employee' : 'user')
            ->update(['seen' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Chat messages retrieved successfully',
            'data' => $messages
        ], 200);
    }

    // Broadcast message using Pusher
    private function broadcastMessage($chatId, $receiverType, $receiverId, $message)
    {
        $pusher = new Pusher('85d8aefb7b8d34dc9f17', '6bbcf1310effc32ad569', '1907839', ['cluster' => 'eu']);

        $pusher->trigger(
            "chat_{$receiverType}_{$chatId}",
            "new-message",
            [
                'type' => $receiverType,
                'id' => $receiverId,
                'message' => $message
            ]
        );
    }
}
