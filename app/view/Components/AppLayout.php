<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        /** @var \App\Models\User|null $user */ // <-- TAMBAHKAN HINT INI
        $user = Auth::user();
        
        $unreadNotifications = $user ? $user->unreadNotifications()->take(5)->get() : collect();
        $notificationCount = $user ? $user->unreadNotifications()->count() : 0; // Seharusnya $notificationCount, bukan $unreadNotificationsCount

        return view('layouts.app', [
            'unreadNotifications' => $unreadNotifications,
            'unreadNotificationsCount' => $notificationCount, // Sesuaikan nama variabel yang dikirim ke Blade
        ]);
    }
}