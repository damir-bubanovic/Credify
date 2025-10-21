<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Services\CreditService;

class CampaignController extends Controller
{
    // fallback if config/credits.php missing or key absent
    private const CREATE_COST_FALLBACK = 10;

    public function index()
    {
        return view('tenant.campaigns.index', [
            'campaigns' => Campaign::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return view('tenant.campaigns.create');
    }

    public function store(StoreCampaignRequest $request, CreditService $credits)
    {
        // determine cost from config, fallback to constant
        $cost = (int) config('credits.costs.campaign.create', self::CREATE_COST_FALLBACK);

        // build idempotency key for ledger safety
        $idempotencyKey = $request->header('Idempotency-Key') ?: Str::uuid()->toString();

        // optional metadata for ledger
        $meta = [
            'ip'   => $request->ip(),
            'ua'   => substr((string) $request->userAgent(), 0, 255),
            'path' => $request->path(),
        ];

        // spend credits in central context
        $ok = tenancy()->central(function () use ($credits, $cost, $meta, $idempotencyKey) {
            // Prefer spend signature: spend(Tenant $tenant, int $amount, string $reason, array $meta = [], ?string $idempotencyKey = null)
            // If your CreditService lacks $meta/$idempotencyKey, add them. For now we pass them.
            return $credits->spend(tenant(), $cost, 'campaign.create', $meta, $idempotencyKey);
        });

        if (! $ok) {
            return back()
                ->withErrors(['credits' => 'Insufficient credits'])
                ->withInput();
        }

        $campaign = Campaign::create($request->validated());

        return redirect()
            ->route('tenant.campaigns.show', $campaign)
            ->with('status', 'Created');
    }

    public function show(Campaign $campaign)
    {
        return view('tenant.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        return view('tenant.campaigns.edit', compact('campaign'));
    }

    public function update(StoreCampaignRequest $request, Campaign $campaign)
    {
        $campaign->update($request->validated());

        return redirect()
            ->route('tenant.campaigns.show', $campaign)
            ->with('status', 'Updated');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()
            ->route('tenant.campaigns.index')
            ->with('status', 'Deleted');
    }
}
