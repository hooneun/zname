@extends('layouts.app')

@section('content')
<img src="{{ asset('images/loader.png') }}" id="js-startImg" class="position-absolute fixed-top img-fluid h-100">
<div class="container">
    @auth
        <div class="row">
            <div>{{ __('Card List') }}</div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{ __('Card Name') }}</th>
                    <th scope="col">{{ __('Created At') }}</th>
                    <th scope="col">{{ __('View') }}</th>
                </tr>
                </thead>
                <tbody>
                @empty($cardList)
                    <tr>
                        <td colspan="4" class="text-center">{{ __('Not Registered Card') }}</td>
                    </tr>
                @else
                @endempty
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col text-right">
                <a href="{{ route('cards.register') }}" class="btn btn-primary">{{ __('Register Card') }}</a>
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

        @if (env('production'))
        {{--@if (1)--}}
        setTimeout(function () {
            START_IMG.classList.add('fade');
            START_IMG.style.display = 'none';
        }, 2000);
        @else
            START_IMG.style.display = 'none';
        @endif
    </script>
@endsection