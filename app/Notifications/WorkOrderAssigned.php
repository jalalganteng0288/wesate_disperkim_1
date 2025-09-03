<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class WorkOrderAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public WorkOrder $workOrder;

    public function __construct(WorkOrder $workOrder)
    {
        $this->workOrder = $workOrder;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $complaint = $this->workOrder->pengaduan;
        // PERBAIKAN: Gunakan Auth::user()->name untuk mendapatkan nama admin yang sedang login
        $assigner = Auth::user()->name; 
        $googleMapsUrl = '#'; // Default URL jika tidak ada koordinat
        if(isset($complaint->latitude) && isset($complaint->longitude)) {
             $googleMapsUrl = 'https://www.google.com/maps?q=${complaint->latitude},'.$complaint->longitude;
        }

        return (new MailMessage)
                    ->subject('Penugasan Baru: ' . $this->workOrder->title)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Anda telah menerima Surat Perintah Kerja (Work Order) baru dari ' . $assigner . '.')
                    ->line('**Judul Tugas:** ' . $this->workOrder->title)
                    ->line('**Terkait Pengaduan:** ' . ($complaint->judul ?? 'Tidak spesifik'))
                    ->line('**Instruksi:** ' . ($this->workOrder->description ?: 'Tidak ada instruksi tambahan.'))
                    ->line('**Batas Waktu:** ' . ($this->workOrder->due_date ? \Carbon\Carbon::parse($this->workOrder->due_date)->format('d F Y') : 'Tidak ditentukan.'))
                    ->action('Lihat Lokasi di Peta', $googleMapsUrl)
                    ->line('Mohon untuk segera ditindaklanjuti. Terima kasih atas perhatian dan kerja sama Anda.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'work_order_id' => $this->workOrder->id,
            'title' => $this->workOrder->title,
            'message' => 'Anda mendapatkan tugas baru: ' . $this->workOrder->title,
        ];
    }
}