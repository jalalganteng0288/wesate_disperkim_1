<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\News;

class NewsObserver
{
    public function created(News $news): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'news.created',
            'resource_type' => 'News',
            'resource_id' => $news->id,
            'changes' => $news->toJson(),
        ]);
    }

    public function updated(News $news): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'news.updated',
            'resource_type' => 'News',
            'resource_id' => $news->id,
            'changes' => json_encode($news->getChanges()),
        ]);
    }

    public function deleted(News $news): void
    {
        AuditLog::create([
            'actor_id' => auth()->id() ?? 1,
            'action' => 'news.deleted',
            'resource_type' => 'News',
            'resource_id' => $news->id,
        ]);
    }
}