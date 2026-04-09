@extends('layouts.app')

@section('content')

<div class="card">
    <h3>Generate Short URL</h3>

    <form method="POST" action="{{ route('short-urls.store') }}">
        @csrf

        <input type="text" name="original_url" style="width:100%; padding:8px;"
               placeholder="Enter Long URL">

        <br><br>

        <button class="btn">Generate</button>
    </form>
</div>

@endsection