<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/clients', [DashboardController::class, 'clients'])->name('clients.index');

Route::get('/team', function () {
    $users = \App\Models\User::where('company_id', auth()->user()->company_id)->paginate(10);
    return view('team.index', compact('users'));
})->name('team.index')->middleware('auth');

Route::get('/invitations/create', [InvitationController::class, 'create'])
    ->name('invitations.create')
    ->middleware('auth');

Route::get('/short-urls-download', [ShortUrlController::class, 'download'])
    ->name('short-urls.download')
    ->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('invitations', InvitationController::class)->except(['show', 'edit', 'update', 'destroy']);
});
Route::get('/accept-invitation/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
Route::post('/accept-invitation/{token}', [InvitationController::class, 'register'])->name('invitations.register');

Route::middleware(['auth'])->group(function () {
    Route::resource('short-urls', ShortUrlController::class)->except(['show', 'edit', 'update', 'destroy']);
});
Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])->name('shorten.redirect');

require __DIR__.'/auth.php';