@extends('layouts.app')

@section('content')

<h2>All Clients</h2>

<div class="card">

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Created</th>
    </tr>

    @forelse($clients as $client)
    <tr>
        <td>{{ $client->name }}</td>
        <td>{{ $client->email }}</td>
        <td>{{ $client->created_at->format('d M Y') }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">No Clients</td>
    </tr>
    @endforelse
</table>

<div class="footer-bar">
    <div>
        Showing {{ $clients->count() }} of {{ $clients->total() }}
    </div>

    <div>
        {{ $clients->links() }}
    </div>
</div>

</div>

@endsection