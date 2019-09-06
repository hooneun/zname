@extends('layouts.app')

@section('content')
{{--<img src="{{ asset('images/loader.png') }}" id="js-startImg" class="position-absolute fixed-top img-fluid w-100">--}}
<div id="js-startImg" class="loader_background h-100 w-100 position-absolute fixed-top">
    <img src="{{ asset('images/loader.png') }}">
</div>
<div class="container">
    @auth
        <div class="row">
            <div class="pl-2">명함 리스트</div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">제목</th>
                    <th scope="col">생성 시간</th>
                    <th scope="col">-</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cardList as $list)
                    <tr>
                        <td>{{ $list->id }}</td>
                        <td>{{ $list->title }}</td>
                        <td>{{ $list->created_at }}</td>
                        <td>
                            <a class="btn btn-info mb-1" href="{{ route('cards.edit', ['id' => $list->details->id], false) }}">수정</a>
                            <br>
                            <a class="btn btn-success mb-1" href="{{ route('cards.view', ['id' => $list->details->id], false) }}">보기</a>
                            <br>
                            <a class="btn btn-danger mb-1" href="{{ route('cards.delete', ['id' => $list->id], false) }}">삭제</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">ZNAME을 생성해주세요.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col text-right">
                @isset($message)
                    <span class="text-danger">{{ $message }}</span>
                @endisset
                <a href="{{ route('cards.register') }}" class="btn btn-primary">ZNAME 생성</a>
            </div>
        </div>
    @else
        <div class="row mt-5">
            <div class="col text-center about"><h2>{{ __('About') }}</h2></div>
        </div>
        <hr class="main_hr"/>
        <div class="row">
            <div class="col">
                <img src="{{ asset('images/main/main.png') }}" class="img-fluid w-100">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="main_p p-4">{{ config('app.name', 'Laravel') }}는 비즈니스, 프리랜서, 각종 모임 및 동호회 등을 위한 온라인 명함 플랫폼입니다. 하나의 계정으로 여러종류의 명함을 만들 수 있으며 언제 어디서든 다양하게 사용할 수
                    있습니다. 지금바로 나만의 명함을 만들어보세요.</p>
            </div>
        </div>
        <div class="row mt-2 mb-5">
            <div class="col text-center">
                <a href="{{ route('login') }}" class="btn btn-light">{{ __('Login') }}</a>
            </div>
            <div class="col text-center">
                <a href="{{ route('register') }}" class="btn btn-dark">{{ __('Register') }}</a>
            </div>
        </div>
    @endauth
</div>
@endsection

@section('script')
    <script>
        const START_IMG = document.getElementById('js-startImg');

        @if (config('app.env') === 'production')
        setTimeout(function () {
            START_IMG.classList.add('fade');
            setTimeout(function () {
                START_IMG.style.display = 'none';
            }, 2000)
        }, 1000);
        @else
            START_IMG.style.display = 'none';
        @endif
    </script>
@endsection