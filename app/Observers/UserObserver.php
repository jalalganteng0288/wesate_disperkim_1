<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'user.created',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'changes' => $user->toJson(),
        ]);
    }

    public function updated(User $user): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'user.updated',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'changes' => json_encode($user->getChanges()),
        ]);
    }

    public function deleted(User $user): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'user.deleted',
            'resource_type' => 'User',
            'resource_id' => $user->id,
        ]);
    }
}