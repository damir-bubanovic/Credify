<?php

namespace App\Listeners;

use App\Events\Credits\CreditsDepleted;
use App\Events\Credits\CreditsLow;
use App\Models\Tenant;
use App\Notifications\Tenant\LowCreditsNotification;
use App\Notifications\Tenant\CreditsDepletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class NotifyLowCredits implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CreditsLow|CreditsDepleted $event): void
    {
        $tenant = Tenant::find($event->tenantId);
        if (! $tenant) {
            return;
        }

        // Throttle: avoid repeated mails within the gap window
        $suffix = $event instanceof CreditsLow ? 'low' : 'depleted';
        $key = "credits.notify.{$suffix}:{$event->tenantId}";

        if (! Cache::add($key, 1, now()->addSeconds(config('credits.low_event_gap_seconds', 3600)))) {
            return;
        }

        if ($event instanceof CreditsLow) {
            $tenant->notify(new LowCreditsNotification($event->balance, $event->threshold));
        } else {
            $tenant->notify(new CreditsDepletedNotification($event->balance));
        }
    }
}
