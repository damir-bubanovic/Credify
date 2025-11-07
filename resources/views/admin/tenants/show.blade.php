<x-app-layout>
    <h1 class="mb-2">Tenant {{ $tenant->id }}</h1>

    @if(session('status'))
        <div style="padding:.5rem 1rem;border:1px solid #cbd5e1;background:#f1f5f9;margin-bottom:1rem;">
            {{ session('status') }}
        </div>
    @endif

    <p><strong>Status:</strong> {{ $tenant->status ?? 'active' }}</p>
    <p><strong>Domains:</strong> {{ $tenant->domains->pluck('domain')->join(', ') ?: '—' }}</p>
    <p><strong>Credits:</strong> {{ number_format((int) $tenant->credit_balance) }}</p>
    <p><strong>Plan:</strong> {{ $tenant->plan ?? '—' }}</p>

    <div style="margin:1rem 0;">
        <a href="{{ route('admin.tenants.index') }}" style="color:#2563eb;">← Back to tenants</a>
    </div>

    @if($subscriptions->count())
        <h2 class="mt-4 mb-2">Subscriptions</h2>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Name</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Status</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Price</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Qty</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Created</th>
                </tr>
            </thead>
            <tbody>
            @foreach($subscriptions as $s)
                <tr>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">{{ $s->name }}</td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">{{ $s->stripe_status }}</td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">{{ $s->stripe_price }}</td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">{{ $s->quantity }}</td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">{{ $s->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</x-app-layout>
