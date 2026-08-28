<?php

namespace App\Notifications;

use App\Models\AdminNotification; // Import your custom model
use Illuminate\Notifications\Notification;

class AdminVerificationNotification extends Notification
{
    protected $outletName;
    protected $message;

    public function __construct($outletName, $message)
    {
        $this->outletName = $outletName;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];  // Use the database channel for storing notifications
    }

    public function toArray($notifiable)
    {
        return [
            'data' => $this->message,
            'outlet_name' => $this->outletName,
        ];
    }

    /**
     * Override to use the custom notification model.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function getDatabaseNotificationModel($notifiable)
    {
        return AdminNotification::class;  // Tell Laravel to use the custom AdminNotification model
    }
}
