<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" user-scalable="no" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes">
    @yield('og')

    @if (Route::currentRouteName() !== 'card.view')
    <meta property="og:image" content="{{ asset('images/ogimage.png') }}">
    <meta property="og:description" content="ZNAME은 온라인 명함 플랫폼입니다.">
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZNAME</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="https://kit.fontawesome.com/3d520b6228.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=07bf2ece600b81f0ccafbe73335246ad&libraries=services"></script>
    <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
    @yield('head')
</head>
<body>
<div id="loading" class="loading">Loading&#8230;</div>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" style="height: 16px;">
            </a>
            @auth
                @if (\Request::route()->getName() !== 'home')
                    <span onclick="history.back()"><i class="fas fa-arrow-left"></i></span>
                @else
                    <a href="{{ route('logout') }}" class="btn btn-light"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit()">로그아웃</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endif
            @endauth
            @guest
                @if (\Request::route()->getName() !== 'home')
                <span onclick="history.back()"><i class="fas fa-arrow-left"></i></span>
                @endif
            @endguest
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    @yield('script')
</div>
</body>
</html>
