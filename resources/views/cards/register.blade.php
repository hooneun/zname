@extends('layouts.app')

@section('head')
    @auth
    @endauth
@endsection

@if ($type === 'view')
@section('og')
    <meta property="og:title" content="{{ $card->name }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ $card->message }}">
    <meta property="og:image" content="{{ $card->main_profile }}">
@endsection
@endif

@section('content')
    @if ($type === 'view')
        <a href="{{ route('home') }}" class="make_my_name"><span>나도</span><span>만들기</span></a>
        <div id="updowntoggle">
            <div id="goup" onclick="document.body.scrollIntoView()"><i class="fas fa-chevron-up"></i></div>
            <div id="godown" onclick="document.body.scrollIntoView(false)"><i class="fas fa-chevron-down"></i></div>
        </div>
    @endif
    <main id="card-content">
        <form method="post" id="js-register-form" action="{{ $type === 'register' ? route('cards.register') : route('cards.update', ['id' => $card->id], false) }}" enctype="multipart/form-data">
            @csrf
            <div id="zname_total_wrapper">
                @if ($type === 'register' || $type === 'edit')
                <div id="card_title">
                    <input id="card_type" type="text" name="title" value="{{ !empty($card->title) ? $card->title : '' }}" placeholder="(사용하실 명함이름을 임력해주세요 (관리자만 보입니다).)" />
                </div>
                @endif
                <div id="profile_section">
                    <div id="rep_img">
                        @if ($type === 'view')
                            <img src="{{ asset($card->main_image) }}">
                        @elseif ($type === 'register' || $type === 'edit')
                            <input id="main_pic" type="file" name="main_image" value="{{ !empty($card->main_image) ? $card->main_image : old('main_image') }}" onchange="readURL(this);"/>
                            <img onclick="click_to_change('main_pic')" src="{{ !empty($card->main_image) ? asset($card->main_image) : asset('images/card/main_img.svg') }}">
                        @endif
                    </div>
                    <div id="main_card_section">
                        <div id="main_left_section">
                            @if ($type === 'view')
                                <img src="{{ asset($card->main_profile) }}">
                            @elseif ($type === 'register' || $type === 'edit')
                                <input id="main_photo" type="file" name="main_profile" value="{{ !empty($card->main_profile) ? asset($card->main_profile) : old('main_profile') }}" onchange="readURL(this);"/>
                                <img onclick="click_to_change('main_photo')" src="{{ !empty($card->main_profile) ? asset($card->main_profile) : asset('images/card/myprofilephoto.svg') }}">
                            @endif
                        </div>
                        <div id="main_right_section">
                            <input id="rep_name" type="text" placeholder="이름 (필수, 최대15자)" name="name" value="{{ !empty($card->name) ? $card->name : old('name') }}" required minlength="2" maxlength="15" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                            <div class="speficif_spec">
                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fab fa-black-tie"></i></div>
                                    <input id="rep_job" type="text" placeholder="직업 (필수, 최대15자)" name="job" value="{{ !empty($card->job) ? $card->job : old('job') }}" required minlength="1" maxlength="20" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                </div>
                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fas fa-map-marker-alt"></i></div>
                                    <input id="rep_address" onclick="openAddress()" type="text" placeholder="주소 (필수, 최대15자)" name="address" value="{{ !empty($card->address) ? $card->address : old('address') }}"  required minlength="1" maxlength="15" size="16">
                                </div>
                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fas fa-mobile-alt"></i></div>
                                    <input id="rep_contact" type="tel" placeholder="연락처 (필수, 예: 010-0000-0000)" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" name="phone" value="{{ !empty($card->phone) ? $card->phone : old('phone') }}" required minlength="2" maxlength="20" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="main_sub_section">
                        <div class="speficif_spec">
                            @if ($type === 'view' && !blank($card->email) || $type === 'register' || $type === 'edit')
                            <div class="spec_section">
                                <div class="img_wrapper"><i class="fas fa-comments"></i></div>
                                <input id="today_comment" type="text" placeholder="오늘의 한마디 (선택, 최대 25자)" name="message" value="{{ !empty($card->message) ? $card->message : old('message') }}" minlength="2" maxlength="30" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                            </div>
                            @endif
                            @if ($type === 'view' && !blank($card->email) || $type === 'register' || $type === 'edit')
                            <div class="spec_section">
                                <div class="img_wrapper"><i class="fas fa-envelope"></i></div>
                                <input id="rep_email" type="eamil" placeholder="이메일 (선택)" name="email" value="{{ !empty($card->email) ? $card->email : old('email') }}" minlength="2" maxlength="50" size="16">
                            </div>
                            @endif
                            @if ($type === 'view' && !blank($card->cafe) || $type === 'register' || $type === 'edit')
                            <div class="spec_section">
                                <div class="img_wrapper"><i class="fas fa-mug-hot"></i></div>
                                <input id="rep_cafe" type="text" placeholder="카페 또는 블로그 (선택)" name="cafe" value="{{ !empty($card->cafe) ? $card->cafe : old('cafe') }}" minlength="2" ize="16">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div id="social_link">
                        <div id="button_wrapper">
                            @if ($type === 'view' && isset($card->facebook) || $type === 'register' || $type === 'edit')
                            <a id="facebook_url" href="{{ !empty($card->facebook) ? $card->facebook : 'javascript:void(0)' }}">
                                <img src="{{ asset('images/card/socialfacebookicon.svg') }}" onclick="changeFB();">
                            </a>
                            @endif
                            @if ($type === 'view' && isset($card->twitter) || $type === 'register' || $type == 'edit')
                            <a id="twitter_url" href="{{ !empty($card->twitter) ? $card->twitter : 'javascript:void(0)' }}">
                                <img src="{{ asset('images/card/socialtwittericon.svg') }}" onclick="changeTW();">
                            </a>
                            @endif
                            @if ($type === 'view' && !blank($card->instagram) || $type === 'register' || $type === 'edit')
                            <a id="instagram_url" href="{{ !empty($card->instagram) ? $card->instagram : 'javascript:void(0)' }}">
                                <img src="{{ asset('images/card/socialinstagramicon.svg') }}" onclick="changeIN();">
                            </a>
                            @endif
                            @if ($type === 'view' && !blank($card->band) || $type === 'register' || $type === 'edit')
                            <a id="band_url" href="{{ !empty($card->band) ? $card->band : 'javascript:void(0)' }}">
                                <img src="{{ asset('images/card/socialbandicon.svg') }}" onclick="changeBA();">
                            </a>
                            @endif
                            @if ($type === 'view' && !blank($card->kakao) || $type === 'register' || $type === 'edit')
                            <a id="kakao_url" href="{{ !empty($card->kakao) ? $card->kakao : '#' }}">
                                <img src="{{ asset('images/card/socialkakaostory.svg') }}" onclick="changeKA();">
                            </a>
                            @endif
                        </div>
                        @if ($type === 'register' || $type === 'view')
                            <div id="add_link_wrapper">
                                <div id="facebook_addlink">
                                    <input id="facebook_link" type="url" placeholder="예) https://페이스북링크.com" name="facebook" pattern="https://.*" minlength="2" maxlength="20" size="16">
                                    <button id="complete_facebook" type="button" onclick="completeFB();">완료</button>
                                </div>
                                <div id="twitter_addlink">
                                    <input id="twitter_link" type="url" placeholder="예) https://트위터링크.com" name="twitter" minlength="2" maxlength="20" size="16">
                                    <button id="complete_twitter" type="button" onclick="completeTW();">완료</button>
                                </div>
                                <div id="instagram_addlink">
                                    <input id="instagram_link" type="url" placeholder="예) https://인스타그램링크.com" name="instagram" minlength="2" maxlength="20" size="16">
                                    <button id="complete_instagram" type="button" onclick="completeIN();">완료</button>
                                </div>
                                <div id="band_addlink">
                                    <input id="band_link" type="url" placeholder="예) https://네이버밴드링크.com" name="band" minlength="2" maxlength="20" size="16">
                                    <button id="complete_band" type="button" onclick="completeBA();">완료</button>
                                </div>
                                <div id="kakao_addlink">
                                    <input id="kakao_link" type="url" placeholder="예) https://카카오스토리링크.com" name="kakao" minlength="2" maxlength="20" size="16">
                                    <button id="complete_kakao" type="button" onclick="completeCA();">완료</button>
                                </div>
                            </div>
                            @if ($type === 'register')
                            <div class="admin_only social_control">상단 버튼을 눌러 링크를 변경해주세요.(관리자만 보입니다.)</div>
                            @endif
                        @endif
                    </div>
                    @if ($type === 'view' && !blank($card->youtube) || $type === 'register' || $type === 'edit')
                    <div id="video_section">
                        <div id="video_wrapper">
                            <img id="lets_change_video" src="{{ asset('images/card/myyoutube.svg') }}" onclick="changeTag()";>
                        </div>
                        <div id="add_video_wrapper">
                            <textarea id="add_youtube" name="youtube" tyle="text" placeholder="유튜브 공유하기 링크를 넣어주세요."></textarea>
                            <button id="complete_addyoutube" type="button" onclick="completeAddvideo()">완료</button>
                        </div>
                        <div id="video_iframe_section">
                            <iframe width="560" height="315" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif
                    <div id="total_ad_section">
                        <div>
                            @if ($type === 'view' && !blank($card->ad_image_top))
                            <img src="{{ asset($card->ad_image_top) }}">
                            <div>{{ $card->ad_content_top }}</div>
                            @elseif ($type === 'register' || $type === 'edit')
                            <input id="ad_sec1" type="file" name="ad_image_top" onchange="readURL(this);" value="{{ !empty($card->ad_image_top) ? asset($card->ad_image_top) : '' }}"/>
                            <img onclick="click_to_change('ad_sec1')" src="{{ !empty($card->ad_image_top) ? asset($card->ad_image_top) : asset('images/card/adsection1.png') }}">
                            <textarea id="ad_contetn1" onkeyup="auto_grow(this)" name="ad_content_top" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요.">{{ !empty($card->ad_content_top) ? $card->ad_content_top : '' }}</textarea>
                            @endif
                        </div>
                        <div>
                            @if ($type === 'view' && !blank($card->ad_image_middle))
                            <img src="{{ asset($card->ad_image_middle) }}">
                            <div>{{ $card->ad_content_middle }}</div>
                            @elseif ($type === 'register' || $type === 'edit')
                            <input id="ad_sec2" type="file" name="ad_image_middle" onchange="readURL(this);" value="{{ !empty($card->ad_image_middle) ? asset($card->ad_image_middle) : '' }}"/>
                            <img onclick="click_to_change('ad_sec2')" src="{{ !empty($card->ad_image_middle) ? asset($card->ad_image_middle) : asset('images/card/adsection2.png') }}">
                            <textarea id="ad_contetn2" onkeyup="auto_grow(this)" name="ad_content_middle" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요.">{{ !empty($card->ad_content_middle) ? $card->ad_content_middle : '' }}</textarea>
                            @endif
                        </div>
                        <div>
                            @if ($type === 'view' && !blank($card->ad_image_bottom))
                                <img src="{{ asset($card->ad_image_bottom) }}">
                                <div>{{ $card->ad_content_bottom }}</div>
                            @elseif ($type === 'register' || $type === 'edit')
                            <input id="ad_sec3" type="file" name="ad_image_bottom" onchange="readURL(this);" value="{{ !empty($card->ad_image_bottom) ? asset($card->ad_image_bottom) : '' }}"/>
                            <img onclick="click_to_change('ad_sec3')" src="{{ !empty($card->ad_image_bottom) ? asset($card->ad_image_bottom) : asset('images/card/adsection3.png') }}">
                            <textarea id="ad_contetn3" onkeyup="auto_grow(this)" name="ad_content_bottom" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요.">{{ !empty($card->ad_content_bottom) ? $card->ad_content_bottom : '' }}</textarea>
                            @endif
                        </div>
                    </div>
                    @if ($type === 'register' || $type === 'edit')
                        <div>
                            <button type="button" class="card_register_btn" id="js-register-btn">명함 만들기</button>
                        </div>
                        @section('script')
                        <script>
                            const REGISTER_BTN = document.getElementById('js-register-btn');
                            const REGISTER_FORM = document.getElementById('js-register-form');

                            REGISTER_BTN.onclick = function () {
                                const formData = new FormData(REGISTER_FORM);
                                const url = document.getElementById('js-register-form').action;

                                window.axios.post(url, formData)
                                    .then(function (response) {
                                        alert('ZNAME 생성이 완료되었습니다.');
                                    })
                                    .catch(function (errors) {
                                        const error = errors.response.data.errors;
                                        alert(error[Object.keys(error)[0]][0]);
                                    });
                            };
                        </script>
                        @endsection
                    @endif
                </div>
            </div>
        </form>
    </main>
    <script src="{{ asset('js/lib.js') }}"></script>
@endsection
