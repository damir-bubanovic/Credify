<h1>{{ $campaign->name }}</h1>
<p>Status: {{ $campaign->status }}</p>
<p>Spend: {{ $campaign->spend }}</p>

<a href="{{ route('tenant.campaigns.edit', $campaign) }}">Edit</a>
<a href="{{ route('tenant.campaigns.index') }}">Back</a>
