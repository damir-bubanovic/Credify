<h1>Billing</h1>

@if(session('status'))
  <p>{{ session('status') }}</p>
@endif

@if($state['subscribed'])
  <p>Plan: {{ $state['plan'] ?? 'active' }}</p>
  <a href="{{ route('tenant.billing.portal') }}">Manage subscription</a>
@else
  <p>No active subscription.</p>

  <form method="POST" action="{{ route('tenant.billing.checkout', $priceBasic) }}">
    @csrf
    <button type="submit">Subscribe Basic</button>
  </form>

  <form method="POST" action="{{ route('tenant.billing.checkout', $pricePro) }}">
    @csrf
    <button type="submit">Subscribe Pro</button>
  </form>
@endif

<a href="{{ route('dashboard') }}">Back</a>
