<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\Credits\CreditsLow;
use App\Events\Credits\CreditsDepleted;
use App\Listeners\NotifyLowCredits;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreditsLow::class => [NotifyLowCredits::class],
        CreditsDepleted::class => [NotifyLowCredits::class],
    ];
}
