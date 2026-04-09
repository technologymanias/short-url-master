@extends('layouts.app')

@section('content')

@php
    $roleName = auth()->user()->role->name;

    $clientAdminId = \App\Models\Role::where('name','Client Admin')->value('id');
    $clientMemberId = \App\Models\Role::where('name','Client Member')->value('id');
@endphp

<h3>
    @if($roleName === 'SuperAdmin')
        Invite New Client
    @else
        Invite New Team Member
    @endif
</h3>

<div class="card">

<form method="POST" action="{{ route('invitations.store') }}">
    @csrf

    <div style="display:flex; gap:10px; margin-bottom:10px; align-items:center;">
    
        <input name="name" placeholder="Name" required>
    
        <input name="email" placeholder="ex. sample@example.com" required>

        <select name="role_id" required>
            @if($roleName === 'SuperAdmin')
                <option value="{{ $clientAdminId }}" selected>
                    Client Admin
                </option>
            @else
                <option value="{{ $clientMemberId }}" selected>
                    Team Member
                </option>
                <option value="{{ $clientAdminId }}">
                    Client Admin
                </option>
            @endif
        </select>

    </div>

    <button class="btn">Send Invitation</button>

</form>

</div>

@endsection