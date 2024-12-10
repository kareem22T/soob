<?php

namespace App\Traits;

use App\Models\Notification;
use Pusher\Pusher;

trait NotificationTrait
{
    /**
     * Send a notification and broadcast it using Pusher
     *
     * @param string $title
     * @param string $body
     * @param int|null $referenceId
     * @param string $receiverType (e.g., 'user', 'employee')
     * @param int $receiverId
     */
    public function sendNotification(string $title, string $body,  string $status, ?int $referenceId, string $receiverType, int $receiverId)
    {
        // Create the notification payload
        $notification = [
            'title' => $title,
            'body' => $body,
            'status' => $status,
            'reference_id' => $referenceId,
            'receiver_type' => $receiverType,
            'receiver_id' => $receiverId,
            'event_type' => 'notification',
        ];

        Notification::create(
            $notification
        );

        // Broadcast the notification using Pusher
        $this->broadcastNotification($receiverType, $receiverId, $notification);
    }

    /**
     * Broadcast a notification using Pusher
     *
     * @param string $receiverType
     * @param int $receiverId
     * @param array $notification
     */
    private function broadcastNotification(string $receiverType, int $receiverId, array $notification)
    {
        $pusher = new Pusher('85d8aefb7b8d34dc9f17', '6bbcf1310effc32ad569', '1907839', ['cluster' => 'eu']);

        $pusher->trigger(
            "event_{$receiverType}_{$receiverId}",
            "new-notification",
            $notification
        );
    }
}
