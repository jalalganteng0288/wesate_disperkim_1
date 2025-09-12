<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- Ini perbaikannya

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $user = Auth::user();
        $unreadNotifications = $user ? $user->unreadNotifications()->take(5)->get() : collect();
        $notificationCount = $user ? $user->unreadNotifications()->count() : 0;

        return view('layouts.app', [
            'unreadNotifications' => $unreadNotifications,
            'notificationCount' => $notificationCount,
        ]);
    }
}