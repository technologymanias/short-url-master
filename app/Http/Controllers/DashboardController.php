<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $roleName = $user->role->name;

    if ($roleName === 'SuperAdmin') {

        $clients = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'Client Admin');
        })->paginate(10);

        $shortUrls = \App\Models\ShortUrl::latest()->paginate(10);

        return view('dashboard.super', compact('clients', 'shortUrls'));
    }

    if ($roleName === 'Client Admin') {

        $shortUrls = \App\Models\ShortUrl::where('user_id', $user->id)
            ->latest()->paginate(10);

        return view('dashboard.client', compact('shortUrls'));
    }

    if ($roleName === 'Client Member') {

        $users = \App\Models\User::where('company_id', $user->company_id)
            ->latest()->paginate(10);

        $shortUrls = \App\Models\ShortUrl::whereHas('user', function ($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })->latest()->paginate(10);

        return view('dashboard.member', compact('users', 'shortUrls'));
    }

    abort(403);
}
    public function clients()
{
    $clients = \App\Models\User::whereHas('role', function ($q) {
        $q->where('name', 'Client Admin');
    })->latest()->paginate(10);

    return view('clients.index', compact('clients'));
}
}