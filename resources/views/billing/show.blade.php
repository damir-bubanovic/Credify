<h1>Billing</h1>
<form method="POST" action="{{ route('billing.checkout','basic') }}">
  @csrf <button type="submit">Subscribe Basic</button>
</form>
<form method="POST" action="{{ route('billing.checkout','pro') }}">
  @csrf <button type="submit">Subscribe Pro</button>
</form>
<a href="{{ route('billing.portal') }}">Manage Billing</a>
