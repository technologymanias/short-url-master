@extends('layouts.app')

@section('content')

<h2>All Short URLs</h2>

<div class="card">

    <!-- HEADER -->
    <div class="card-header">
        <h3>Short URLs</h3>

        <div class="actions">
            @if(in_array(auth()->user()->role->name, ['Client Admin', 'Client Member']))
                <a href="{{ route('short-urls.create') }}" class="btn">Generate</a>
            @endif

            <select>
                <option>This Month</option>
                <option>Last Month</option>
                <option>Last Week</option>
                <option>Today</option>
            </select>

            <a href="{{ route('short-urls.download') }}" class="btn">Download</a>
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>Short URL</th>
            <th>Long URL</th>
            <th>Hits</th>
            <th>Created</th>
        </tr>

        @forelse($shortUrls as $url)
        <tr>
            <td>
                <a href="{{ url('/s/'.$url->short_code) }}" target="_blank">
                    {{ url('/s/'.$url->short_code) }}
                </a>
            </td>
            <td>{{ $url->original_url }}</td>
            <td>{{ $url->clicks }}</td>
            <td>{{ $url->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No data found</td>
        </tr>
        @endforelse
    </table>

    <!-- FOOTER -->
    <div class="footer-bar">
    <div>
        Showing {{ $shortUrls->count() }} of {{ $shortUrls->total() }}
    </div>

    <div style="display:flex; gap:10px;">
        {{ $shortUrls->links() }}
    </div>
</div>

</div>

@endsection