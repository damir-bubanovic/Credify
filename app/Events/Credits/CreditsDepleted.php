<?php

namespace App\Events\Credits;

use Illuminate\Foundation\Events\Dispatchable;

class CreditsDepleted
{
    use Dispatchable;

    public function __construct(public string $tenantId, public int $balance,)
    {
        
    }
}
