<h1>Tenant Dashboard</h1>

<p>Credits: {{ $credit }}</p>
<ul>
  <li>Total campaigns: {{ $totals['campaigns'] }}</li>
  <li>Active: {{ $totals['active'] }}</li>
</ul>

<h3>Last 30 days</h3>
<table>
  <tr><th>Date</th><th>New campaigns</th></tr>
  @foreach($daily as $date => $count)
    <tr><td>{{ $date }}</td><td>{{ $count }}</td></tr>
  @endforeach
</table>

<a href="{{ route('tenant.campaigns.index') }}">Manage campaigns</a>
