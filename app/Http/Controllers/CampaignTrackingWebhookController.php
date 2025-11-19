<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignTrackingWebhookController extends Controller
{
    public function __invoke(Request $request, Campaign $campaign, CreditService $credits)
    {
        $data = $request->validate([
            'event' => ['required', 'in:impression,click,conversion'],
            'count' => ['nullable', 'integer', 'min:1'],
            'meta'  => ['nullable', 'array'],
        ]);

        $count = $data['count'] ?? 1;
        $meta  = $data['meta'] ?? [];

        $tenant = tenant();

        // per-event credit cost for running a campaign
        $perEventCost = (int) config('credits.costs.campaign.run', 5);
        $totalCost    = $perEventCost * $count;

        // idempotency key (same as your /v1/run endpoint)
        $idempotencyKey = $request->header('Idempotency-Key') ?: Str::uuid()->toString();

        $meta = array_merge($meta, [
            'campaign_id' => $campaign->getKey(),
            'event'       => $data['event'],
            'count'       => $count,
        ]);

        // Charge credits in the central DB
        // NOTE: This matches how you're already calling spend(...) elsewhere:
        // spend(Tenant $tenant, int $amount, ?string $reason, array $meta = [], ?string $idempotencyKey = null)
        $ok = $credits->spend($tenant, $totalCost, 'campaign.run', $meta, $idempotencyKey);

        if (! $ok) {
            return response()->json(['error' => 'insufficient_credits'], 402);
        }

        // Update campaign stats in the tenant DB
        $field = match ($data['event']) {
            'impression' => 'impressions',
            'click'      => 'clicks',
            'conversion' => 'conversions',
        };

        $campaign->increment($field, $count);
        $campaign->increment('spend', $totalCost);

        return response()->json([
            'ok'       => true,
            'campaign' => $campaign->fresh(),
        ]);
    }
}
