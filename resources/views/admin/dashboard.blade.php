{{-- resources/views/admin/dashboard.blade.php --}}
<h1 class="mb-2">Admin Dashboard</h1>

@if(session('status'))
    <div style="padding:.5rem 1rem;border:1px solid #cbd5e1;background:#f1f5f9;margin-bottom:1rem;">
        {{ session('status') }}
    </div>
@endif

<ul style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem;padding:0;list-style:none;margin:0 0 1rem 0;">
    <li style="border:1px solid #e5e7eb;border-radius:.5rem;padding:.75rem;">
        <div style="font-size:.85rem;color:#6b7280;">Total tenants</div>
        <div style="font-weight:600;font-size:1.25rem;">{{ $cards['tenants'] }}</div>
    </li>
    <li style="border:1px solid #e5e7eb;border-radius:.5rem;padding:.75rem;">
        <div style="font-size:.85rem;color:#6b7280;">Subscribed</div>
        <div style="font-weight:600;font-size:1.25rem;">{{ $cards['subscribed'] }}</div>
    </li>
    <li style="border:1px solid #e5e7eb;border-radius:.5rem;padding:.75rem;">
        <div style="font-size:.85rem;color:#6b7280;">Total credits</div>
        <div style="font-weight:600;font-size:1.25rem;">{{ number_format((int) $cards['credits_sum']) }}</div>
    </li>
</ul>

<h3 class="mt-4 mb-2">Recent tenants</h3>
<div style="overflow:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">ID</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Primary domain</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Created</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Plan</th>
            <th style="text-align:right;border-bottom:1px solid #e5e7eb;padding:.5rem;">Credits</th>
        </tr>
    </thead>
    <tbody>
    @forelse($recent as $t)
        @php
            $domain = optional($t->domains->first())->domain;
        @endphp
        <tr>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                <a href="{{ route('admin.tenants.show', $t->id) }}" style="color:#2563eb;text-decoration:none;">
                    {{ $t->id }}
                </a>
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                {{ $domain ?: '—' }}
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                {{ $t->created_at }}
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                {{ $t->plan ?? '—' }}
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;text-align:right;">
                {{ number_format((int) $t->credit_balance) }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="padding:.75rem;text-align:center;color:#6b7280;">No tenants yet.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

<h3 class="mt-6 mb-2">Latest credit transactions</h3>
<div style="overflow:auto;">
<table style="width:100%;border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Tenant</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Type</th>
            <th style="text-align:right;border-bottom:1px solid #e5e7eb;padding:.5rem;">Amount</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">Reason</th>
            <th style="text-align:left;border-bottom:1px solid #e5e7eb;padding:.5rem;">At</th>
        </tr>
    </thead>
    <tbody>
    @forelse($ledger as $row)
        @php
            $sign = $row->type === 'spend' ? '-' : '+';
        @endphp
        <tr>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                <a href="{{ route('admin.tenants.show', $row->tenant_id) }}" style="color:#2563eb;text-decoration:none;">
                    {{ $row->tenant_id }}
                </a>
            </td>
            <td style="padding:.25rem .5rem;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:.8rem;padding:.15rem .4rem;border:1px solid #e5e7eb;border-radius:.25rem;background:#f8fafc;">
                    {{ $row->type }}
                </span>
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;text-align:right;">
                {{ $sign }}{{ number_format((int) $row->amount) }}
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                {{ $row->reason ?? '—' }}
            </td>
            <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                {{ $row->created_at }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="padding:.75rem;text-align:center;color:#6b7280;">No transactions found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>
