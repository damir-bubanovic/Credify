<h1>Campaigns</h1>

<a href="{{ route('tenant.campaigns.create') }}">Create new</a>

@if(session('status'))
    <p>{{ session('status') }}</p>
@endif

<ul>
@foreach($campaigns as $c)
    <li>
        <a href="{{ route('tenant.campaigns.show', $c) }}">{{ $c->name }}</a>
        <form action="{{ route('tenant.campaigns.destroy', $c) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </li>
@endforeach
</ul>

{{ $campaigns->links() }}
