<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" user-scalable="no" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') . ' 관리자 페이지' }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>

    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand text-white" href="{{ route('admin.home') }}">ADMIN</a>
        {{--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">--}}
            {{--<span class="navbar-toggler-icon"></span>--}}
        {{--</button>--}}
        {{--<div class="navbar-nav collapse" id="navbarAdmin">--}}
            {{--<ul class="navbar-nav mr-auto">--}}
                {{--<li class="nav-item">사용자</li>--}}
                {{--<li class="nav-item">결제</li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    @yield('script')
</div>
</body>
</html>