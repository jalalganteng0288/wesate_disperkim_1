<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $complaint;

    /**
     * Create a new notification instance.
     *
     * @param Complaint $complaint
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        // Notifikasi akan dikirim melalui database dan email
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Pengaduan Diperbarui')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Status pengaduan Anda telah diperbarui.')
            ->line('Judul Pengaduan: ' . $this->complaint->title)
            ->line('Status Baru: ' . $this->complaint->status)
            ->action('Lihat Pengaduan', url('/complaints/' . $this->complaint->id))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification (for database).
     *
     * @param mixed $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'title' => $this->complaint->title,
            'status' => $this->complaint->status,
            'message' => 'Status pengaduan Anda telah diperbarui menjadi: ' . $this->complaint->status,
        ];
    }
}
