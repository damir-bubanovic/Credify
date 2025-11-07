<x-layout>
    <h1 class="text-2xl font-semibold mb-2">Tenant {{ $tenant->id }}</h1>
    <p>Status: <strong>{{ $tenant->status ?? 'active' }}</strong></p>
    <p>Domains: {{ $tenant->domains->pluck('domain')->join(', ') }}</p>

    @if($subscriptions->count())
        <h2 class="text-xl font-semibold mt-4 mb-2">Subscriptions</h2>
        <ul class="list-disc ml-6">
            @foreach($subscriptions as $s)
                <li>{{ $s->name }} — {{ $s->status }} — {{ $s->stripe_price }} × {{ $s->quantity }}</li>
            @endforeach
        </ul>
    @endif
</x-layout>
