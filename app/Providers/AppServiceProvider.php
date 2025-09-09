<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// --- TAMBAHKAN DI BAWAH INI ---
use App\Models\Complaint;
use App\Observers\ComplaintObserver;
use App\Models\News;
use App\Observers\NewsObserver;
use App\Models\User;
use App\Observers\UserObserver;
// --- AKHIR BAGIAN TAMBAHAN ---

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // --- TAMBAHKAN DI BAWAH INI ---
        Complaint::observe(ComplaintObserver::class);
        News::observe(NewsObserver::class);
        User::observe(UserObserver::class);
        // --- AKHIR BAGIAN TAMBAHAN ---
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}