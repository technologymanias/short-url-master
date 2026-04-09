<!DOCTYPE html>
<html>
<head>
    <title>URL Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial;
            background:#f5f6fa;
            margin:0;
        }

        .navbar {
            background:#fff;
            padding:12px 20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-bottom:1px solid #ddd;
        }

        .navbar a {
            margin-left:15px;
            text-decoration:none;
            color:#333;
        }

        .container {
            padding:20px;
        }

        .card {
            background:#fff;
            padding:15px;
            border:1px solid #ddd;
            margin-bottom:20px;
            border-radius:6px;
        }

        .card-header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
        }

        .actions {
            display:flex;
            gap:10px;
            align-items:center;
            margin-left:auto; /* 🔥 FIX RIGHT ALIGN */
        }

        .btn {
            height:34px;
            padding:0 14px;
            border:1px solid #333;
            background:#eee;
            cursor:pointer;
            display:flex;
            align-items:center;
            border-radius:4px;
            text-decoration:none;
            color:#000;
        }

        .btn:hover {
            background:#ddd;
        }

        select {
            height:34px;
            padding:0 8px;
            border-radius:4px;
        }

        input {
            height:34px;
            padding:0 8px;
            border:1px solid #ccc;
            border-radius:4px;
        }

        table {
            width:100%;
            border-collapse: collapse;
            margin-top:10px;
            background:#fff;
        }

        th {
            background:#f1f1f1;
        }

        th, td {
            border:1px solid #ccc;
            padding:8px;
            text-align:left;
        }

        .footer-bar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-top:12px;
        }

        /* Pagination fix */
        .pagination {
            display:flex;
            list-style:none;
            gap:5px;
            padding:0;
        }

        .pagination li {
            display:inline-block;
        }

        .pagination a, .pagination span {
            padding:6px 10px;
            border:1px solid #ccc;
            text-decoration:none;
            color:#333;
        }

        .pagination .active span {
            background:#333;
            color:#fff;
        }

    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <!-- LEFT SIDE -->
    <div style="display:flex; align-items:center; gap:10px;">
        
        <a href="/dashboard" style="
            padding:6px 18px;
            border:1px solid #333;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
            color:#000;
            background:#f1f1f1;
        ">
            { URL }
        </a>

        <a href="/dashboard">Dashboard</a>

    </div>

    <!-- RIGHT SIDE -->
    <div>
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn">Logout →</button>
        </form>
        @endauth
    </div>

</div>

<!-- CONTENT -->
<div class="container">

    <!-- ✅ SUCCESS MESSAGE -->
    @if(session('success'))
        <div style="background:#d4edda; padding:10px; margin-bottom:10px; border:1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <!-- ❌ ERROR MESSAGE -->
    @if($errors->any())
        <div style="background:#f8d7da; padding:10px; margin-bottom:10px; border:1px solid #f5c6cb;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>