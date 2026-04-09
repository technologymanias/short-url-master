<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with('role')->latest()->get();
        return view('invitations.index', compact('invitations'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['SuperAdmin'])->get();
        return view('invitations.create', compact('roles'));
    }

    public function store(Request $request)
{
    $user = auth()->user();

    if ($user->role->name === 'SuperAdmin') {
        $request->merge([
            'role_id' => Role::where('name', 'Client')->value('id')
        ]);
    }

    $request->validate([
        'email' => 'required|email|unique:users,email|unique:invitations,email',
        'role_id' => 'required|exists:roles,id',
    ]);

    if ($user->role->name === 'Client Admin') {
        $role = Role::find($request->role_id);

        if (!in_array($role->name, ['Client Member', 'Client Admin'])) {
            return back()->withErrors([
                'role_id' => 'You can only invite team members or client admins'
            ]);
        }
    }

    $token = Str::random(64);

    Invitation::create([
        'email' => $request->email,
        'role_id' => $request->role_id,
        'token' => $token,
        'expires_at' => Carbon::now()->addDays(7),
    ]);

    $link = url("/accept-invitation/{$token}");

    Mail::raw("Click to set your password: {$link}", function ($message) use ($request) {
        $message->to($request->email)->subject('Invitation to join');
    });

    return redirect()->route('invitations.create')
        ->with('success', 'Invitation sent successfully!');
}

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        return view('invitations.accept', compact('invitation', 'token'));
    }

    public function register(Request $request, $token)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => bcrypt($request->password),
            'role_id' => $invitation->role_id,
            'company_id' => 1,
        ]);

        $invitation->delete();

        auth()->login($user);

        return redirect('/dashboard');
    }
}