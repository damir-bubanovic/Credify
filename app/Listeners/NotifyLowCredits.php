<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Credits\CreditsLow;
use App\Models\CreditBalance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Tenant\LowCreditsNotification;

class NotifyLowCredits
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreditsLow $event): void
    {
        $key = "credits.low:{$event->tenantId}";
        if (Cache::add($key, 1, now()->addSeconds(config('credits.low_event_gap_seconds', 3600)))) {
            // notify tenant owners
            Notification::route('mail', "owner@{$event->tenantId}")
                ->notify(new LowCreditsNotification($event->balance, $event->threshold));
        }
    }
}
