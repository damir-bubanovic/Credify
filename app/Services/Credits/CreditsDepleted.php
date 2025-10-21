<?php

namespace App\Services\Credits;
use Illuminate\Foundation\Events\Dispatchable;

class CreditsDepleted
{

    use Dispatchable;

    /**
     * Create a new class instance.
     */
    public function __construct(public string $tenantId, public int $balance)
    {
        // 
    }
}
