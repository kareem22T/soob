<?php

namespace App\Observers;

use App\Models\Offer;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;

class OfferObserver
{
    use NotificationTrait;

    /**
     * Handle the Offer "updated" event.
     *
     * @param  \App\Models\Offer  $offer
     * @return void
     */
    public function updated(Offer $offer)
    {
        // Check if the status was updated
        if ($offer->isDirty('status')) {
            // Get the admin who made the change
            $admin = Auth::guard('admin')->user();

            // Generate the notification message based on the new status
            $message = $this->getNotificationMessage($offer->status, $offer->title, $admin->name);

            // Prepare notification data
            $title = "تم تحديث حالة العرض"; // A general title for the notification
            $body = $message;

            $referenceId = $offer->id;
            $receiverType = 'employee';
            $receiverId = $offer->company_id;

            // Send notification
            $this->sendNotification($title, $body, $offer->status, $referenceId, $receiverType, $receiverId);
        }
    }

    /**
     * Generate the notification message based on the offer's status.
     *
     * @param string $status
     * @param string $offerTitle
     * @param string $adminName
     * @return string
     */
    private function getNotificationMessage(string $status, string $offerTitle, string $adminName): string
    {
        switch ($status) {
            case 'Pending':
                return "تم تعطيل العرض '{$offerTitle}' بواسطة المسؤول '{$adminName}'.";
            case 'Approved':
                return "تمت الموافقة على العرض '{$offerTitle}' بواسطة المسؤول '{$adminName}'.";
            case 'Rejected':
                return "تم رفض العرض '{$offerTitle}' بواسطة المسؤول '{$adminName}'.";
            default:
                return "تم تحديث حالة العرض '{$offerTitle}' إلى '{$status}' بواسطة المسؤول '{$adminName}'.";
        }
    }
}
