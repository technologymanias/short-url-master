@extends('layouts.app')

@section('content')

<h2 style="color:#2980b9;">Client Admin Dashboard</h2>

<!-- SHORT URLS -->
<div class="card">

    <div class="card-header">

    <div>
        <b>Generated Short URLs</b>
        <a href="{{ route('short-urls.create') }}" class="btn">Generate</a>
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
            <th>User</th>
            <th>Created</th>
        </tr>

        @forelse($shortUrls ?? [] as $url)
        <tr>
            <td>{{ url('/s/'.$url->short_code) }}</td>
            <td>{{ $url->original_url }}</td>
            <td>{{ $url->clicks }}</td>
            <td>{{ $url->user->name }}</td>
            <td>{{ $url->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="5">No Data</td></tr>
        @endforelse
    </table>

</div>

<!-- TEAM MEMBERS -->
<div class="card">

    <!-- HEADER -->
    <div class="card-header">
        <b>Generated Short URLs</b>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>Short URL</th>
            <th>Long URL</th>
            <th>Hits</th>
            <th>User</th>
            <th>Created</th>
        </tr>

        @foreach($shortUrls as $url)
        <tr>
            <td>{{ url('/s/'.$url->short_code) }}</td>
            <td>{{ $url->original_url }}</td>
            <td>{{ $url->clicks }}</td>
            <td>{{ $url->user->name ?? '-' }}</td>
            <td>{{ $url->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
    </table>

    <!-- ✅ ADD FOOTER HERE (JUST AFTER TABLE) -->
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