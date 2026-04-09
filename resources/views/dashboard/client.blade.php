@extends('layouts.app')

@section('content')

<h2>Client Dashboard</h2>

@php
    $shortUrls = \App\Models\ShortUrl::where('user_id', auth()->id())
                    ->latest()
                    ->paginate(2);

    $users = \App\Models\User::where('company_id', auth()->user()->company_id)
                    ->paginate(2);
@endphp

<!-- ================= SHORT URL SECTION ================= -->
<div class="card">

    <div class="card-header">
        <h3>Generated Short URLs</h3>

        <div class="actions">
            <a href="{{ route('short-urls.create') }}" class="btn">Generate</a>

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
            <td colspan="4">No data</td>
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

<!-- ================= TEAM MEMBERS ================= -->
<div class="card">

    <div class="card-header">
        <h3>Team Members</h3>

        <div class="actions">
            <a href="{{ route('invitations.create') }}" class="btn">Invite</a>
        </div>
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Total URLs</th>
            <th>Total Hits</th>
        </tr>

        @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role->name ?? '-' }}</td>
            <td>{{ \App\Models\ShortUrl::where('user_id', $user->id)->count() }}</td>
            <td>{{ \App\Models\ShortUrl::where('user_id', $user->id)->sum('clicks') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No users found</td>
        </tr>
        @endforelse
    </table>

   <div class="footer-bar">

    <div>
        Showing {{ $users->count() }} of {{ $users->total() }}
    </div>

    <div>
        <a href="{{ route('team.index') }}" class="btn">View All</a>
    </div>

</div>

</div>

@endsection