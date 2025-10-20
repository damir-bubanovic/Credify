<h1>New Campaign</h1>

@if($errors->any())
    <ul>
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('tenant.campaigns.store') }}">
    @csrf
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Status:</label>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="active">Active</option>
        <option value="paused">Paused</option>
    </select>

    <button type="submit">Create</button>
</form>

<a href="{{ route('tenant.campaigns.index') }}">Back</a>
