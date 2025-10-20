<h1>Edit Campaign</h1>

@if($errors->any())
    <ul>
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('tenant.campaigns.update', $campaign) }}">
    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required>

    <label>Status:</label>
    <select name="status">
        <option value="draft" @selected($campaign->status === 'draft')>Draft</option>
        <option value="active" @selected($campaign->status === 'active')>Active</option>
        <option value="paused" @selected($campaign->status === 'paused')>Paused</option>
    </select>

    <button type="submit">Update</button>
</form>

<a href="{{ route('tenant.campaigns.index') }}">Back</a>
