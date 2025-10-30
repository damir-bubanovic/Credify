{{-- resources/views/tenant/credits/index.blade.php --}}
<x-app-layout>
    <h1 class="text-xl font-semibold">Credits</h1>
    <div class="mt-2">Balance: <strong>{{ $balance->balance }}</strong></div>

    <form class="mt-4" method="post" action="{{ route('tenant.credits.settings') }}">
        @csrf
        <label>Low threshold <input name="low_threshold" type="number" value="{{ $balance->low_threshold }}"></label>
        <label>Auto top-up <input name="auto_topup_enabled" type="checkbox" value="1" @checked($balance->auto_topup_enabled)></label>
        <label>Top-up amount <input name="topup_amount" type="number" value="{{ $balance->topup_amount }}"></label>
        <label>Stripe price id <input name="stripe_price_id" value="{{ $balance->stripe_price_id }}"></label>
        <button type="submit">Save</button>
    </form>

    <h2 class="mt-6 font-semibold">Ledger</h2>
    <table class="mt-2 w-full">
        <thead><tr><th>Date</th><th>Reason</th><th>Δ</th><th>Balance</th></tr></thead>
        <tbody>
            @foreach($ledger as $row)
                <tr>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->reason }}</td>
                    <td>{{ $row->delta }}</td>
                    <td>{{ $row->balance_after }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $ledger->links() }}
</x-app-layout>
