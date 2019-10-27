@extends('layouts.app')

@section('content')
    {{--<div id="js-startImg" class="loader_background h-100 w-100 position-absolute fixed-top">--}}
        {{--<img src="{{ asset('images/loader.svg') }}">--}}
    {{--</div>--}}
    <div class="container">
        @auth
            <div class="row">
                <div class="pl-3">내 명함 리스트</div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">제목</th>
                        <th scope="col" class="w-50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cardList as $list)
                        <tr>
                            <td>{{ $list->id }}</td>
                            <td>{{ $list->title }}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-dark mb-1" onclick="linkCopy(this)"
                                        value="{{ url('card', ['id' => $list->details->phone], false) }}">복사
                                </button>
                                <a class="btn btn-dark mb-1"
                                   href="{{ route('cards.edit', ['id' => $list->details->id], false) }}">수정</a>
                                <a class="btn btn-dark mb-1"
                                   href="{{ route('cards.view', ['id' => $list->details->id], false) }}">보기</a>
                                <button type="button" class="btn btn-danger mb-1" id="js-card_delete">삭제</button>
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
                    <a href="{{ route('cards.register') }}" class="btn btn-dark">명함 만들기</a>
                </div>
            </div>
            @else
                <div class="row">
                    <div class="col">
                        <img src="{{ asset('images/main/main.png') }}" class="img-fluid w-100">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="main_p p-4">ZNAME은 비즈니스, 프리랜서, 각종 모임 및 동호회 등을 위한 온라인 명함
                            플랫폼입니다. 하나의 계정으로 여러종류의 명함을 만들 수 있으며 언제 어디서든 다양하게 사용할 수
                            있습니다. 지금바로 나만의 명함을 만들어보세요.</p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <a href="/cards/10" class="btn btn-primary btn-lg btn-block font-16">샘플 보기</a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <a href="/cards/guide" class="btn btn-warning btn-lg btn-block font-16">이용가이드</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-light btn-lg btn-block font-16">{{ __('Login') }}</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <a href="{{ route('agreement') }}" class="btn btn-primary btn-lg btn-dark btn-lg btn-block font-16">{{ __('Register') }}</a>
                    </div>
                </div>
            @endauth
    </div>
@endsection

@section('script')
    <script>
        {{--const START_IMG = document.getElementById('js-startImg');--}}

        {{--@if (config('app.env') === 'production')--}}
        {{--if (!localStorage.getItem('START_LOGO')) {--}}
            {{--localStorage.setItem('START_LOGO', true);--}}
            {{--setTimeout(function () {--}}
                {{--START_IMG.classList.add('fade');--}}
                {{--setTimeout(function () {--}}
                    {{--START_IMG.style.display = 'none';--}}
                {{--}, 2000)--}}
            {{--}, 1000);--}}
        {{--} else {--}}
            {{--START_IMG.style.display = 'none';--}}
        {{--}--}}
        {{--@else--}}
            {{--START_IMG.style.display = 'none';--}}
        {{--@endif--}}

        function linkCopy (obj) {
            Copy(obj.value);

            alert('복사되었습니다.');
        }

        @if (!empty($list->id))
            document.querySelector('#js-card_delete').addEventListener("click", function () {
                if (confirm('정말 삭제하시겠습니까?')) {
                    location.href ="{{ route('cards.delete', ['id' => $list->id], false) }}";
                }
            });
        @endif

    </script>
@endsection