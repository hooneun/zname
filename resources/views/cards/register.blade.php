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
    <meta property="og:image" content="{{ $card->main_profile_url . '?' . \Illuminate\Support\Str::random(40)}}">
@endsection
@endif

@section('content')
    @if ($type === 'view')
        @auth
        <a href="{{ route('cards.edit', ['id' => $card->id], false) }}" class="make_my_name"><span>명함</span><span>수정하기</span></a>
        @endauth
        @guest
            <a href="{{ route('home') }}" class="make_my_name"><span>나도</span><span>만들기</span></a>
        @endguest
        <div id="updowntoggle">
            <div id="goup" onclick="document.body.scrollIntoView()"><i class="fas fa-chevron-up"></i></div>
            <div id="godown" onclick="document.body.scrollIntoView(false)"><i class="fas fa-chevron-down"></i></div>
        </div>
    @endif
    <main id="card-content">
        <form method="post" id="js-register-form" action="{{ $type === 'register' ? route('cards.register') : route('cards.update', ['id' => $card->id], false) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="init" value="{{ $init }}">
            <div id="zname_total_wrapper">
                @if ($type === 'register' || $type === 'edit')
                <div id="card_title">
                    <input id="card_type" type="text" name="title" value="{{ !empty($card->title) ? $card->title : '' }}" placeholder="명함이름을 입력해주세요. (필수)" />
                </div>
                @endif
                <div id="profile_section">
                    <div id="rep_img">
                        @if ($type === 'view')
                            @if ($init)
                                <img src="{{ asset($card->main_image) }}">
                            @else
                                <img src="{{ asset($card->main_image_url) }}">
                            @endif
                        @elseif ($type === 'register' || $type === 'edit')
                            @if ($init)
                                <input id="main_pic" type="file" name="main_image" value="{{ old('main_image') }}" onchange="readURL(this);"/>
                                <img onclick="click_to_change('main_pic')" src="{{ asset('images/card/main_img.png') }}">
                            @else
                                <input id="main_pic" type="file" name="main_image" value="{{ !empty($card->main_image_url) ? $card->main_image_url : old('main_image') }}" onchange="readURL(this);"/>
                                <img onclick="click_to_change('main_pic')" src="{{ !empty($card->main_image_url) ? asset($card->main_image_url) : asset('images/card/main_img.png') }}">
                            @endif

                        @endif
                    </div>
                    <div id="main_card_section">
                        <div id="main_left_section">
                            @if ($type === 'view')
                                @if ($init)
                                    <img src="{{ asset($card->main_profile) }}">
                                @else
                                <img src="{{ asset($card->main_profile_url) }}">
                                @endif
                            @elseif ($type === 'register' || $type === 'edit')
                                @if ($init)
                                    <input id="main_photo" type="file" name="main_profile" value="{{ 'images/card/myprofilephoto.png' }}" onchange="readURL(this);"/>
                                    <img onclick="click_to_change('main_photo')" src="{{ asset('images/card/myprofilephoto.png') }}">
                                @else
                                    <input id="main_photo" type="file" name="main_profile" value="{{ !empty($card->main_profile_url) ? asset($card->main_profile_url) : old('main_profile') }}" onchange="readURL(this);"/>
                                    <img onclick="click_to_change('main_photo')" src="{{ !empty($card->main_profile_url) ? asset($card->main_profile_url) : asset('images/card/myprofilephoto.png') }}">
                                @endif
                            @endif
                        </div>
                        <div id="main_right_section">
                            <input id="rep_name" type="text" placeholder="이름 (필수, 최대15자)" name="name" value="{{ !empty($card->name) ? $card->name : old('name') }}" required minlength="2" maxlength="15" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                            <div class="speficif_spec">
                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fab fa-black-tie"></i></div>
                                    <input id="rep_job" type="text" placeholder="직업 (필수, 최소2자이상 최대15자이하)" name="job" value="{{ !empty($card->job) ? $card->job : old('job') }}" required minlength="1" maxlength="20" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                </div>

                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fas fa-mobile-alt"></i></div>
                                    @if ($type === 'view')
                                        <a href="tel:{{ $card->phone }}">{{ preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $card->phone) }}</a>
                                    @else
                                        <input id="rep_contact" type="tel" placeholder="연락처 (필수, - 없이 숫자만) " pattern="[0-9]{3}[0-9]{4}[0-9]{4}" name="phone" value="{{ !empty($card->phone) ? $card->phone : old('phone') }}" required minlength="2" maxlength="20" size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                    @endif
                                </div>

                                <div class="spec_section">
                                    <div class="img_wrapper"><i class="fas fa-envelope"></i></div>
                                    @if ($type === 'view')
                                    <a href="mailto:{{ $card->email }}">{{ $card->email }}</a>
                                    @elseif ($type === 'register' || $type === 'edit')
                                    <input id="rep_email" type="eamil" placeholder="이메일 (필수)" name="email" value="{{ !empty($card->email) ? $card->email : old('email') }}" minlength="2" maxlength="50" required size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="main_sub_section">
                        <div class="speficif_spec">
                            @if ($type === 'view' && !blank($card->email) || $type === 'register' || $type === 'edit')
                            <div class="spec_section">
                                <div class="img_wrapper"><i class="fas fa-comments"></i></div>
                                <input id="today_comment" type="text" placeholder="오늘의 한마디 (필수, 최소5자이상 최대 25자이하)" name="message" value="{{ !empty($card->message) ? $card->message : old('message') }}" minlength="2" maxlength="30" required size="16" {{ $type === 'view' ? 'disabled' : '' }}>
                            </div>
                            @endif
                            @if ($type === 'view' && !blank($card->cafe) || $type === 'register' || $type === 'edit')
                            <div class="spec_section">
                                <div class="img_wrapper"><i class="fas fa-mug-hot"></i></div>
                                @if ($type === 'register' || $type === 'edit')
                                <input id="rep_cafe" type="text" placeholder="카페 또는 블로그 (선택)" name="cafe" value="{{ !empty($card->cafe) ? $card->cafe : old('cafe') }}" minlength="2" ize="16" {{ $type === 'view' ? 'disabled' : '' }}>
                                @else
                                    <a class="w-100 text-black" href="{{ $card->cafe }}" target="_blank">{{ $card->cafe }}</a>
                                @endif
                            </div>
                            @endif
                                @if ($type === 'view' && !blank($card->address))
                                <div id="mapOpen" class="spec_section">
                                    <div class="img_wrapper align_address"><i class="fas fa-map-marker-alt"></i></div>
                                    <textarea id="rep_address" type="text" placeholder="주소 (선택)" name="address" required minlength="1" size="16" disabled>{{ !empty($card->address) ? trim($card->address) : old('address') }}</textarea>
                                </div>
                                <div>
                                    <div id="map" class="d-none"></div>
                                </div>
                                @endif
                                @if ($type === 'register' || $type === 'edit')
                                    <div class="spec_section">
                                        <div class="img_wrapper align_address"><i class="fas fa-map-marker-alt"></i></div>
                                        <textarea id="rep_address" onkeyup="auto_grow(this)" onclick="openAddress()" type="text" placeholder="주소 (선택)" name="address"  required minlength="1" size="16">{{ !empty($card->address) ? trim($card->address) : old('address') }}</textarea>
                                    </div>
                                @endif
                        </div>
                    </div>
                    <div id="social_link">
                        <div id="button_wrapper">
                            @if ($type === 'view')
                                <a id="facebook_url" href="{{ !empty($card->facebook) ? $card->facebook : 'javascript:alert("' . $card->name . '님의 페이스북은 준비중입니다.' . '")' }}" {{ !empty($card->facebook) ? 'target="_blank"' : '' }}>
                                    <img src="{{ asset('images/card/socialfacebookicon.svg') }}">
                                </a>
                            @elseif ($type === 'register' || $type === 'edit')
                            <a id="facebook_url" href="{{ !empty($card->facebook) ? $card->facebook : 'javascript:void(0)' }}" {{ !empty($card->facebook) ? 'target="_blank"' : '' }}>
                                <img src="{{ asset('images/card/socialfacebookicon.svg') }}" onclick="changeFB();">
                            </a>
                            @endif


                            @if ($type === 'view')
                                    <a id="twitter_url" href="{{ !empty($card->twitter) ? $card->twitter : 'javascript:alert("' . $card->name . '님의 트위터는 준비중입니다.' . '")' }}" {{ !empty($card->twitter) ? 'target="_blank"' : '' }}>
                                        <img src="{{ asset('images/card/socialtwittericon.svg') }}" onclick="changeTW();">
                                    </a>
                            @elseif ($type === 'register' || $type == 'edit')
                            <a id="twitter_url" href="{{ !empty($card->twitter) ? $card->twitter : 'javascript:void(0)' }}" {{ !empty($card->twitter) ? 'target="_blank"' : '' }}>
                                <img src="{{ asset('images/card/socialtwittericon.svg') }}" onclick="changeTW();">
                            </a>
                            @endif


                            @if ($type === 'view')
                                <a id="instagram_url" href="{{ !empty($card->instagram) ? $card->instagram : 'javascript:alert("' . $card->name . '님의 인스타그램은 준비중입니다.' . '")'}}" {{ !empty($card->instagram) ? 'target="_blank"' : '' }}>
                                    <img src="{{ asset('images/card/socialinstagramicon.svg') }}" onclick="changeIN();">
                                </a>
                            @elseif ($type === 'register' || $type === 'edit')
                            <a id="instagram_url" href="{{ !empty($card->instagram) ? $card->instagram : 'javascript:void(0)' }}" {{ !empty($card->instagram) ? 'target="_blank"' : '' }}>
                                <img src="{{ asset('images/card/socialinstagramicon.svg') }}" onclick="changeIN();">
                            </a>
                            @endif


                            @if ($type === 'view')
                                <a id="band_url" href="{{ !empty($card->band) ? $card->band : 'javascript:alert("' . $card->name . '님의 밴드는 준비중입니다.' . '")' }}" {{ !empty($card->band) ? 'target="_blank"' : '' }}>
                                    <img src="{{ asset('images/card/socialbandicon.svg') }}" onclick="changeBA();">
                                </a>
                            @elseif ($type === 'register' || $type === 'edit')
                            <a id="band_url" href="{{ !empty($card->band) ? $card->band : 'javascript:void(0)' }}" {{ !empty($card->band) ? 'target="_blank"' : '' }}>
                                <img src="{{ asset('images/card/socialbandicon.svg') }}" onclick="changeBA();">
                            </a>
                            @endif


                            @if ($type === 'view')
                            <a id="kakao_url" href="javascript:sendLink()">
                                <img src="{{ asset('images/card/kakatoshare.png') }}">
                            </a>
                            @else
                            {{--<a id="band_url" href="{{ !empty($card->band) ? $card->band : 'javascript:void(0)' }}" {{ !empty($card->band) ? 'target="_blank"' : '' }}>--}}
                                <img src="{{ asset('images/sns_icons/kakao.png') }}" onclick="alert('자동설정되었습니다.');">
                            {{--</a>--}}
                            @endif
                        </div>
                        @if ($type === 'register' || $type === 'edit')
                            <div id="add_link_wrapper">
                                <div id="facebook_addlink">
                                    <input id="facebook_link" type="url" placeholder="예) https://페이스북링크.com" name="facebook" value="{{ !empty($card->facebook) ? $card->facebook : '' }}" pattern="https://.*" minlength="2" size="16">
                                    <button id="complete_facebook" type="button" onclick="completeFB();">완료</button>
                                </div>
                                <div id="twitter_addlink">
                                    <input id="twitter_link" type="url" placeholder="예) https://트위터링크.com" name="twitter" value="{{ !empty($card->twitter) ? $card->twitter : '' }}" minlength="2" size="16">
                                    <button id="complete_twitter" type="button" onclick="completeTW();">완료</button>
                                </div>
                                <div id="instagram_addlink">
                                    <input id="instagram_link" type="url" placeholder="예) https://인스타그램링크.com" name="instagram" value="{{ !empty($card->instagram) ? $card->instagram : '' }}" minlength="2" size="16">
                                    <button id="complete_instagram" type="button" onclick="completeIN();">완료</button>
                                </div>
                                <div id="band_addlink">
                                    <input id="band_link" type="url" placeholder="예) https://네이버밴드링크.com" name="band" value="{{ !empty($card->band) ? $card->band : '' }}" minlength="2" size="16">
                                    <button id="complete_band" type="button" onclick="completeBA();">완료</button>
                                </div>
                                <div id="kakao_addlink">
                                    <input id="kakao_link" type="url" placeholder="예) https://카카오스토리링크.com" name="kakao" value="{{ !empty($card->kakao) ? $card->kakao : '' }}" minlength="2" size="16">
                                    <button id="complete_kakao" type="button" onclick="completeCA();">완료</button>
                                </div>
                            </div>
                            @if ($type === 'register')
                            <div class="admin_only social_control">상단 버튼을 눌러 링크를 변경해주세요.(관리자만 보입니다.)</div>
                            @endif
                        @endif
                    </div>
                    @if ($type === 'register' || $type === 'edit')
                        <div id="video_section">
                            <div id="video_iframe_section">
                                <iframe class="{{ !empty($card->youtube) ? 'd-block' : '' }}" width="560" height="315" frameborder="0" src="{{ !empty($card->youtube) ? $card->youtube : '' }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                </iframe>
                            </div>
                            <div id="video_wrapper" class="{{ !empty($card->youtube) ? 'hide' : '' }}">
                                <img id="lets_change_video" src="{{ asset('images/card/myyoutube.png') }}" onclick="changeTag()";>
                            </div>
                            <div id="add_video_wrapper" class="{{ !empty($card->youtube) ? 'show' : '' }} zmt-10">
                                <textarea id="add_youtube" name="youtube" tyle="text" placeholder="유튜브 공유하기 링크를 넣어주세요.">{{ !empty($card->youtube) ? $card->youtube : '' }}</textarea>
                                <button id="complete_addyoutube" type="button" onclick="completeAddvideo()">완료</button>
                            </div>
                        </div>
                    @endif
                    @if ($type === 'view' && !blank($card->youtube))
                    <div id="video_section">
                        <div id="video_iframe_section">
                            <iframe class="d-block" width="560" height="315" frameborder="0" src="{{ !empty($card->youtube) ? $card->youtube : '' }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    @endif
                    <div id="total_ad_section">
                        <div>
                            @if ($type === 'view')
                                @if (!empty($card->ad_image_top))
                                    @if ($init)
                                        <img src="{{ asset($card->ad_image_top) }}">
                                    @else
                                        <img src="{{ asset($card->ad_image_top_url) }}">
                                    @endif
                                @endif
                                @if (!empty($card->ad_content_top))
                                <div class="wit-spc">{{ $card->ad_content_top }}</div>
                                @endif
                            @elseif ($type === 'register' || $type === 'edit')
                                @if ($init)
                                    <input id="ad_sec1" type="file" name="ad_image_top" onchange="readURL(this);" value=""/>
                                    <img onclick="click_to_change('ad_sec1')" src="{{ asset('images/card/adsection1.png') }}">
                                    <textarea id="ad_contetn1" onkeyup="auto_grow(this)" name="ad_content_top" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요."></textarea>
                                @else
                                    <input id="ad_sec1" type="file" name="ad_image_top" onchange="readURL(this);" value="{{ !empty($card->ad_image_top_url) ? asset($card->ad_image_top_url) : '' }}"/>
                                    <img onclick="click_to_change('ad_sec1')" src="{{ !empty($card->ad_image_top_url) ? asset($card->ad_image_top_url) : asset('images/card/adsection1.png') }}">
                                    <textarea id="ad_contetn1" onkeyup="auto_grow(this)" name="ad_content_top" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요." {{ $type === 'view' ? 'disabled' : '' }}>{{ !empty($card->ad_content_top) ? $card->ad_content_top : '' }}</textarea>
                                @endif
                            @endif
                        </div>
                        <div>
                            @if ($type === 'view')
                                @if (!empty($card->ad_image_middle_url))
                                <img src="{{ asset($card->ad_image_middle_url) }}">
                                @endif
                                @if (!empty($card->ad_content_middle ))
                                <div class="wit-spc">{{ $card->ad_content_middle }}</div>
                                @endif
                            @elseif ($type === 'register' || $type === 'edit')
                            <input id="ad_sec2" type="file" name="ad_image_middle" onchange="readURL(this);" value="{{ !empty($card->ad_image_middle_url) ? asset($card->ad_image_middle_url) : '' }}"/>
                            <img onclick="click_to_change('ad_sec2')" src="{{ !empty($card->ad_image_middle_url) ? asset($card->ad_image_middle_url) : asset('images/card/adsection2.png') }}">
                            <textarea id="ad_contetn2" onkeyup="auto_grow(this)" name="ad_content_middle" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요." {{ $type === 'view' ? 'disabled' : '' }}>{{ !empty($card->ad_content_middle) ? $card->ad_content_middle : '' }}</textarea>
                            @endif
                        </div>
                        <div>
                            @if ($type === 'view' && !blank($card->ad_image_bottom_url))
                                @if (!empty($card->ad_image_bottom_url))
                                <img src="{{ asset($card->ad_image_bottom_url) }}">
                                @endif
                                @if (!empty($card->ad_content_bottom ))
                                <div class="wit-spc">{{ $card->ad_content_bottom }}</div>
                                @endif
                            @elseif ($type === 'register' || $type === 'edit')
                            <input id="ad_sec3" type="file" name="ad_image_bottom" onchange="readURL(this);" value="{{ !empty($card->ad_image_bottom_url) ? asset($card->ad_image_bottom_url) : '' }}"/>
                            <img onclick="click_to_change('ad_sec3')" src="{{ !empty($card->ad_image_bottom_url) ? asset($card->ad_image_bottom_url) : asset('images/card/adsection3.png') }}">
                            <textarea id="ad_contetn3" onkeyup="auto_grow(this)" name="ad_content_bottom" rows="4" cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요." {{ $type === 'view' ? 'disabled' : '' }}>{{ !empty($card->ad_content_bottom) ? $card->ad_content_bottom : '' }}</textarea>
                            @endif
                        </div>
                    </div>
                    @if ($type === 'register' || $type === 'edit')
                        <div id="complete_edit">
                            <button type="button" class="card_register_btn" id="js-register-btn">
                                @if ($type === 'register')
                                    명함 만들기
                                @else
                                    명함 수정하기
                                @endif
                            </button>
                        </div>
                    @endif
                    @section('script')
                        <script>
                            @if($type === 'view')
                            const MAP_OPEN_BTN = document.getElementById('mapOpen');

                            MAP_OPEN_BTN.onclick = function () {
                                location.href = '{{ url('/cards/map', ['id' => $card->id], false) }}'
                            }
                            @endif
                            @if ($type === 'register' || $type === 'edit')
                            const REGISTER_BTN = document.getElementById('js-register-btn');
                            const REGISTER_FORM = document.getElementById('js-register-form');

                            REGISTER_BTN.onclick = function () {
                                const formData = new FormData(REGISTER_FORM);
                                const url = document.getElementById('js-register-form').action;

                                loadingOn();

                                window.axios.post(url, formData)
                                    .then(function (response) {
                                        loadingOff();
                                        @if ($type === 'register')
                                        alert('ZNAME 생성이 완료되었습니다.');
                                        @else
                                        alert('ZNAME 수정이 완료되었습니다.');
                                        @endif

                                            location.href = '/cards/' + response.data.id;
                                    })
                                    .catch(function (errors) {
                                        const error = errors.response.data.errors;
                                        loadingOff();
                                        let errMsg = '';
                                        for (err in error) {
                                            if (errMsg !== '') {
                                                errMsg += '\n';
                                            }
                                            errMsg += error[err][0];
                                        }
                                        alert(errMsg);
                                    });
                            };
                            @endif
                            @if ($type === 'view')

                            Kakao.init('07bf2ece600b81f0ccafbe73335246ad');
                            function sendLink() {
                                Kakao.Link.sendDefault({
                                    objectType: 'feed',
                                    content: {
                                        title: '{{ $card->name }}',
                                        description: '{{ $card->message }}',
                                        imageUrl: '{{ asset($card->main_profile_url) }}',
                                        link: {
                                            mobileWebUrl: '{{ url('/card/' . $card->phone)  }}',
                                            webUrl: '{{ url('/card/' . $card->phone) }}'
                                        }
                                    },
                                    buttons: [
                                        {
                                            title: '자세히 보기',
                                            link: {
                                                mobileWebUrl: '{{ url('/card/' . $card->phone)  }}',
                                                webUrl: '{{ url('/card/' . $card->phone) }}'
                                            }
                                        }
                                    ]
                                });
                            }
                            @endif
                        </script>
                    @endsection
                </div>
            </div>
        </form>
    </main>
    <script src="{{ asset('js/lib.js') }}"></script>
@endsection
