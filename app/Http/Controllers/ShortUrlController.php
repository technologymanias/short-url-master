<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $roleName = $user->role->name;

    if ($roleName === 'SuperAdmin') {
        $shortUrls = ShortUrl::latest()->paginate(10);
    } elseif ($roleName === 'Admin') {
        $shortUrls = ShortUrl::latest()->paginate(10);
    } elseif ($roleName === 'Member') {
        $shortUrls = ShortUrl::where('user_id', '!=', $user->id)
            ->latest()
            ->paginate(10);
    } else {
      
        $shortUrls = ShortUrl::latest()->paginate(10);
    }

    return view('short-urls.index', compact('shortUrls'));
}

    public function create()
    {
        $user = auth()->user();
        $roleName = $user->role->name;
    
        if (!in_array($roleName, ['Client Admin', 'Client Member'])) {
            abort(403, 'Only Client Admin and Client Member can create short URLs.');
        }
        return view('short-urls.create');
    }

    public function download()
{
    $urls = \App\Models\ShortUrl::all();

    $csv = "Short URL,Long URL,Clicks,Created\n";

    foreach ($urls as $u) {
        $csv .= url('/s/'.$u->short_code).",".
                $u->original_url.",".
                $u->clicks.",".
                $u->created_at."\n";
    }

    return response($csv)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="urls.csv"');
}

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role->name, ['Client Admin', 'Client Member'])) {
            abort(403);
        }

        $request->validate([
            'original_url' => 'required|url',
        ]);

        $shortCode = Str::random(6);
        while (ShortUrl::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(6);
        }

        ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'user_id' => $user->id,
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created.');
    }

    public function redirect($code)
    {
       
        if (!auth()->check()) {
            return redirect('login');
        }

        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();
        $shortUrl->increment('clicks');
        return redirect($shortUrl->original_url);
    }
}