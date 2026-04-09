@extends('layouts.app')

@section('content')

<h2 style="color:#d4ac0d;">Super Admin Dashboard</h2>

<div class="card">

    <div class="card-header">
        <div>
            <b>Clients</b>
        </div>

        <div class="actions">
            <a href="{{ route('invitations.create') }}" class="btn">Invite</a>
        </div>
    </div>

    <table>
        <tr>
            <th>Client</th>
            <th>Users</th>
            <th>Total URLs</th>
            <th>Total Hits</th>
        </tr>

        @foreach($clients as $client)
        <tr>
            <td>{{ $client->name }}</td>
            <td>{{ \App\Models\User::where('company_id', $client->company_id)->count() }}</td>
            <td>{{ \App\Models\ShortUrl::where('user_id', $client->id)->count() }}</td>
            <td>{{ \App\Models\ShortUrl::where('user_id', $client->id)->sum('clicks') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="footer-bar">
        <div>
            Showing {{ $clients->count() }} of {{ $clients->total() }}
        </div>

        <div style="display:flex; gap:10px;">
            <a href="{{ route('clients.index') }}" class="btn">View All</a>
            {{ $clients->links() }}
        </div>
    </div>

</div>

<div class="card">

    <div class="card-header">

        <div>
            <b>Generated Short URLs</b>
        </div>

        <div class="actions">
            <select>
                <option>This Month</option>
                <option>Last Month</option>
                <option>Last Week</option>
                <option>Today</option>
            </select>

            <a href="{{ route('short-urls.download') }}" class="btn">Download</a>
        </div>

    </div>

    <table>
        <tr>
            <th>Short URL</th>
            <th>Long URL</th>
            <th>Hits</th>
            <th>Company</th>
            <th>Created</th>
        </tr>

        @forelse($shortUrls as $url)
        <tr>
            <td>{{ url('/s/'.$url->short_code) }}</td>
            <td>{{ $url->original_url }}</td>
            <td>{{ $url->clicks }}</td>
            <td>Default Company</td>
            <td>{{ $url->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No Data</td>
        </tr>
        @endforelse
    </table>

    <div class="footer-bar">
        <div>
            Showing {{ $shortUrls->count() }} of {{ $shortUrls->total() }}
        </div>

        <div style="display:flex; gap:10px;">
            <a href="{{ route('short-urls.index') }}" class="btn">View All</a>
            {{ $shortUrls->links() }}
        </div>
    </div>

</div>

@endsection