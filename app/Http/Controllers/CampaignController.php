<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Services\CreditService;

class CampaignController extends Controller
{

    // cost per campaign create
    private const CREATE_COST = 10;

    public function index()
    {
        return view('tenant.campaigns.index', ['campaigns' => Campaign::latest()->paginate(10)]);
    }

    public function create()
    {
        return view('tenant.campaigns.create');
    }

    public function store(StoreCampaignRequest $request, CreditService $credits)
    {
        // spend credits from central context
        $ok = tenancy()->central(function () use ($credits) {
            return $credits->spend(tenant(), self::CREATE_COST, 'campaign.create');
        });

        if (! $ok) {
            return back()->withErrors(['credits' => 'Insufficient credits.']);
        }

        $campaign = Campaign::create($request->validated());
        return redirect()->route('tenant.campaigns.show', $campaign)->with('status', 'Created');
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
        return redirect()->route('tenant.campaigns.show', $campaign)->with('status', 'Updated');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('tenant.campaigns.index')->with('status', 'Deleted');
    }

}
