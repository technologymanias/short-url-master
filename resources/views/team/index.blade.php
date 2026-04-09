@extends('layouts.app')

@section('content')

<h2>Team Members</h2>

<div class="card">

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Total URLs</th>
        <th>Total Hits</th>
    </tr>

    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role->name ?? '-' }}</td>
        <td>{{ \App\Models\ShortUrl::where('user_id', $user->id)->count() }}</td>
        <td>{{ \App\Models\ShortUrl::where('user_id', $user->id)->sum('clicks') }}</td>
    </tr>
    @endforeach

</table>

<div class="footer-bar">
    <div>
        Showing {{ $users->count() }} of {{ $users->total() }}
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>

</div>

@endsection