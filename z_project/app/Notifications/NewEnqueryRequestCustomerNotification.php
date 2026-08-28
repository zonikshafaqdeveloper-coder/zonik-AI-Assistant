<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEnqueryRequestCustomerNotification extends Notification
{
    use Queueable;

    private $enquiryId;
    private $message; // Custom message

    /**
     * Create a new notification instance.
     *
     * @param int $enquiryId
     * @param string|null $message
     * @return void
     */
    public function __construct($enquiryId, $message = null)
    {
        $this->enquiryId = $enquiryId;
        $this->message = $message ?? 'New Enquiry Submitted.';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->message)
                    ->action('View Enquiry', url('/enquiries/' . $this->enquiryId))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data' => $this->message,
            'tag' => 'Customer',
            'url' => url('/enquiries/' . $this->enquiryId . '?notification_id=' . $this->id),
            'enquiry_id' => $this->enquiryId,
            'admin_read' => false, // Default for all notifications
        ];
    }
}
