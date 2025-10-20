<h1>Admin Dashboard</h1>

<ul>
  <li>Total tenants: {{ $cards['tenants'] }}</li>
  <li>Subscribed: {{ $cards['subscribed'] }}</li>
  <li>Total credits: {{ $cards['credits_sum'] }}</li>
</ul>

<h3>Recent tenants</h3>
<table>
  <tr><th>ID</th><th>Created</th><th>Plan</th><th>Credits</th></tr>
  @foreach($recent as $t)
    <tr>
      <td>{{ $t->id }}</td>
      <td>{{ $t->created_at }}</td>
      <td>{{ $t->plan ?? '-' }}</td>
      <td>{{ $t->credit_balance }}</td>
    </tr>
  @endforeach
</table>

<h3>Latest credit transactions</h3>
<table>
  <tr><th>Tenant</th><th>Type</th><th>Amount</th><th>Reason</th><th>At</th></tr>
  @foreach($ledger as $row)
    <tr>
      <td>{{ $row->tenant_id }}</td>
      <td>{{ $row->type }}</td>
      <td>{{ $row->amount }}</td>
      <td>{{ $row->reason }}</td>
      <td>{{ $row->created_at }}</td>
    </tr>
  @endforeach
</table>
