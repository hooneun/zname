@extends('layouts.app')

@section('head')
    @auth
    @endauth
@endsection

@section('content')
    <main id="card-content">
        <form method="post" action="{{ route('card.register') }}">
            @csrf
            <div id="zname_total_wrapper">
                <div id="profile_section">
                    <div id="rep_img">
                        <input id="main_pic" type="file" name="main_image" onchange="readURL(this);"/>
                        <img onclick="click_to_change('main_pic')" src="{{ asset('images/card/main_img.png') }}">
                    </div>
                    <div id="main_card_section">
                        <div id="main_left_section">
                            <input id="main_photo" type="file" name="main_profile" onchange="readURL(this);"/>
                            <img onclick="click_to_change('main_photo')"
                                 src="{{ asset('images/card/myprofilephoto.png') }}">
                        </div>
                        <div id="main_right_section">
                            <input id="rep_name" type="text" placeholder="이름 (필수, 최대15자)" name="name" required
                                   minlength="2" maxlength="15" size="16">
                            <div class="speficif_spec">
                                <div class="spec_section">
                                    <div class="img_wrapper"><img src="{{ asset('images/card/myjob.png') }}"></div>
                                    <input id="rep_job" type="text" placeholder="직업 (필수, 최대20자)" name="job" required
                                           minlength="1" maxlength="20" size="16">
                                </div>
                                <div class="spec_section">
                                    <div class="img_wrapper"><img src="{{ asset('images/card/myaddress.png') }}"></div>
                                    <input id="rep_address" type="text" placeholder="주소 (필수, 최대20자)" name="address"
                                           required minlength="1" maxlength="20" size="16">
                                </div>
                                <div class="spec_section">
                                    <div class="img_wrapper"><img src="{{ asset('images/card/myphone.png') }}"></div>
                                    <input id="rep_contact" type="tel" placeholder="연락처 (필수, 예: 010-0000-0000)"
                                           pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" name="phone" required minlength="2"
                                           maxlength="20" size="16">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="main_sub_section">
                        <div class="speficif_spec">
                            <div class="spec_section">
                                <div class="img_wrapper"><img src="{{ asset('images/card/mymessage.png') }}"></div>
                                <input id="today_comment" type="text" placeholder="오늘의 한마디 (선택, 최대 30자)" name="message"
                                       required minlength="2" maxlength="30" size="16">
                            </div>
                            <div class="spec_section">
                                <div class="img_wrapper"><img src="{{ asset('images/card/myemail.png') }}"></div>
                                <input id="rep_email" type="eamil" placeholder="이메일 (선택)" name="email" required
                                       minlength="2" maxlength="20" size="16">
                            </div>
                            <div class="spec_section">
                                <div class="img_wrapper"><img src="{{ asset('images/card/mycafe.png') }}"></div>
                                <input id="rep_cafe" type="text" placeholder="카페 또는 블로그 (선택)" name="cafe" required
                                       minlength="2" maxlength="20" size="16">
                            </div>
                        </div>
                    </div>
                    <div id="social_link">
                        <input id="facebook_link" type="url" placeholder="https://페이스북링크.com" name="facebook"
                               pattern="https://.*" minlength="2" maxlength="20" size="16">
                        <a href="">
                            <img src="{{ asset('images/card/socialfacebookicon.png') }}">
                        </a>
                        <input id="twitter_link" type="url" placeholder="https://트위터링크.com" name="twitter" minlength="2"
                               maxlength="20" size="16">
                        <a href="">
                            <img src="{{ asset('images/card/socialtwittericon.png') }}">
                        </a>
                        <input id="instagram_link" type="url" placeholder="https://인스타그램링크.com" name="instagram"
                               minlength="2" maxlength="20" size="16">
                        <a href="">
                            <img src="{{ asset('images/card/socialinstagramicon.png') }}">
                        </a>
                        <input id="band_link" type="url" placeholder="https://네이버밴드링크.com" name="band" minlength="2"
                               maxlength="20" size="16">
                        <a href="">
                            <img src="{{ asset('images/card/socialbandicon.png') }}">
                        </a>
                        <input id="kakao_link" type="url" placeholder="https://카카오톡링크.com" name="kakao" minlength="2"
                               maxlength="20" size="16">
                        <a href="">
                            <img src="{{ asset('images/card/socialkakaoicon.png') }}">
                        </a>
                    </div>
                    <div id="video_section">
                        <div id="video_wrapper">
                            <img src="{{ asset('images/card/myyoutube.png') }}">
                        </div>
                    </div>
                    <div id="total_ad_section">
                        <div>
                            <input id="ad_sec1" type="file" name="ad_image_top" onchange="readURL(this);"/>
                            <img onclick="click_to_change('ad_sec1')" src="{{ asset('images/card/adsection1.png') }}">
                            <textarea id="ad_contetn1" onkeyup="auto_grow(this)" name="ad_content_top" rows="4"
                                      cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요."></textarea>
                        </div>
                        <div>
                            <input id="ad_sec2" type="file" name="ad_image_middle" onchange="readURL(this);"/>
                            <img onclick="click_to_change('ad_sec2')" src="{{ asset('images/card/adsection2.png') }}">
                            <textarea id="ad_contetn2" onkeyup="auto_grow(this)" name="ad_content_middle" rows="4"
                                      cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요."></textarea>
                        </div>
                        <div>
                            <input id="ad_sec3" type="file" name="ad_image_bottom" onchange="readURL(this);"/>
                            <img onclick="click_to_change('ad_sec3')" src="{{ asset('images/card/adsection3.png') }}">
                            <textarea id="ad_contetn3" onkeyup="auto_grow(this)" name="ad_content_bottom" rows="4"
                                      cols="50" placeholder="필요한 안내,홍보,광고문구를 넣어주세요."></textarea>
                        </div>
                    </div>
                    @if ($type === 'register')
                        <div>
                            <button class="btn btn-primary">등록</button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </main>
@endsection