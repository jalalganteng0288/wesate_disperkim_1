<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Complaint;

class ComplaintObserver
{
    /**
     * Handle the Complaint "created" event.
     */
    public function created(Complaint $complaint): void
    {
        AuditLog::create([
            'actor_id' => 1, // Nanti diganti dengan auth()->id()
            'action' => 'complaint.created',
            'resource_type' => 'Complaint',
            'resource_id' => $complaint->id,
            'changes' => $complaint->toJson(), // Simpan seluruh data baru sebagai JSON
        ]);
    }

    /**
     * Handle the Complaint "updated" event.
     */
    public function updated(Complaint $complaint): void
    {
        AuditLog::create([
            'actor_id' => 1, // Nanti diganti dengan auth()->id()
            'action' => 'complaint.updated',
            'resource_type' => 'Complaint',
            'resource_id' => $complaint->id,
            'changes' => json_encode($complaint->getChanges()), // Hanya simpan kolom yang berubah
        ]);
    }

    /**
     * Handle the Complaint "deleted" event.
     */
    public function deleted(Complaint $complaint): void
    {
        AuditLog::create([
            'actor_id' => 1, // Nanti diganti dengan auth()->id()
            'action' => 'complaint.deleted',
            'resource_type' => 'Complaint',
            'resource_id' => $complaint->id,
        ]);
    }
}