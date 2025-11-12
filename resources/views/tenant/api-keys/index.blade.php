<h1>API Keys</h1>

@if (session('status'))
    <p>{{ session('status') }}</p>
@endif

@if ($keys->isEmpty())
    <p>No API keys yet.</p>
@else
    <table border="1" cellpadding="4" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Key</th>
                <th>Last used</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keys as $key)
                <tr>
                    <td>{{ $key->name ?? '-' }}</td>
                    <td><code>{{ $key->key }}</code></td>
                    <td>{{ $key->last_used_at ? $key->last_used_at->diffForHumans() : 'never' }}</td>
                    <td>{{ $key->created_at->diffForHumans() }}</td>
                    <td>
                        <form method="POST" action="{{ route('tenant.api-keys.destroy', $key) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<h2>Create new API key</h2>

<form method="POST" action="{{ route('tenant.api-keys.store') }}">
    @csrf
    <div>
        <label for="name">Name (optional)</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">Generate key</button>
</form>
