<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\NewComplaintNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class ComplaintObserver
{
    /**
     * Handle the Pengaduan "created" event.
     */
    public function created(Pengaduan $pengaduan): void
    {
        AuditLog::create([
            'actor_id' => Auth::id(), // Menggunakan Auth::id() yang lebih aman
            'action' => 'complaint.created',
            'resource_type' => 'Pengaduan',
            'resource_id' => $pengaduan->id,
            'changes' => $pengaduan->toJson(),
        ]);

        $adminsAndOperators = User::role(['Super Admin', 'Operator'])->get();

        if ($adminsAndOperators->isNotEmpty()) {
            Notification::send($adminsAndOperators, new NewComplaintNotification($pengaduan));
        }
    }

    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        AuditLog::create([
            'actor_id' => Auth::id(),
            'action' => 'complaint.updated',
            'resource_type' => 'Pengaduan',
            'resource_id' => $pengaduan->id,
            'changes' => json_encode($pengaduan->getChanges()),
        ]);
    }

    /**
     * Handle the Pengaduan "deleted" event.
     */
    public function deleted(Pengaduan $pengaduan): void
    {
        AuditLog::create([
            'actor_id' => Auth::id(),
            'action' => 'complaint.deleted',
            'resource_type' => 'Pengaduan',
            'resource_id' => $pengaduan->id,
        ]);
    }
}