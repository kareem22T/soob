<?php

namespace App\Observers;

use App\Models\Company;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;

class CompanyObserver
{
    use NotificationTrait;

    public function updated(Company $company)
    {
        // Check if the status was updated
        if ($company->isDirty('is_approved')) {
            // Get the admin who made the change
            $admin = Auth::guard('admin')->user();

            if ($company->is_approved) {
                // Generate the notification message based on the new status
                $message = 'تم الموافقة على حسابك من قبل المسؤولين';

                // Prepare notification data
                $title = "اشعار نجاح"; // A general title for the notification
                $body = $message;

                $receiverType = 'employee';
                $receiverId = $company->id;

                // Send notification
                $this->sendNotification($title, $body, 'Approved', 0, $receiverType, $receiverId, 'approved');
            }
        }
    }

}
