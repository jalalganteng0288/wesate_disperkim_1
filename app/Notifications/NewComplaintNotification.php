<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComplaintNotification extends Notification
{
    use Queueable;

    public $pengaduan;

    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via(object $notifiable): array
    {
        // Kita akan menyimpan notifikasi di database
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->pengaduan->id,
            'title' => $this->pengaduan->judul,
            'user_name' => $this->pengaduan->user->name ?? 'Warga',
        ];
    }
}