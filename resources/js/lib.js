//이미지 바꾸는 구간
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            input.nextElementSibling.setAttribute('src',e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function click_to_change(id) {
    document.getElementById(id).click();
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

//동영상 바꾸는 구간
const vidWrapper = document.getElementById("add_video_wrapper")
const vidImg = document.getElementById("video_wrapper")
const vidIframe = document.getElementById("video_iframe_section")
const youtubeEmbed = document.getElementById("add_youtube")
const changedURL = document.querySelector("#video_iframe_section iframe")

function changeTag() {
    if (vidWrapper.classList.contains("show")) {
        vidWrapper.classList.remove("show")
    } else {
        vidWrapper.classList.add("show")
    }

    if (vidImg.classList.contains("hide")) {
        vidImg.classList.remove("hide")
    } else {
        vidImg.classList.add("hide")
    }
}

function completeAddvideo() {
    let sliced = youtubeEmbed.value.match(/([^/]*)$/);
    changedURL.src = "//www.youtube.com/embed/" + sliced[1]
    vidWrapper.classList.remove("show")
    vidIframe.classList.add("show")
}

//소셜미디어 링크넣는구간
const fbLink = document.getElementById("facebook_addlink")
const twLink = document.getElementById("twitter_addlink")
const inLink = document.getElementById("instagram_addlink")
const baLink = document.getElementById("band_addlink")
const kaLink = document.getElementById("kakao_addlink")
const fbATag = document.getElementById("facebook_url")
const twATag = document.getElementById("twitter_url")
const inATag = document.getElementById("instagram_url")
const baATag = document.getElementById("band_url")
const kaATag = document.getElementById("kakao_url")

//페이스북
function changeFB() {
    if (fbLink.classList.contains("show")) {
        fbLink.classList.remove("show")
    } else {
        fbATag.href = "javascript:void(0)"
        fbATag.target = ""
        fbLink.classList.add("show")
        twLink.classList.remove("show")
        inLink.classList.remove("show")
        baLink.classList.remove("show")
        kaLink.classList.remove("show")
    }
}
function completeFB() {
    let slicedFB = document.getElementById("facebook_link").value;
    fbATag.href = slicedFB
    fbATag.target = "_blank"
    fbLink.classList.remove("show")
}

//트위터
function changeTW() {
    if (twLink.classList.contains("show")) {
        twLink.classList.remove("show")
    } else {
        twATag.href = "javascript:void(0)"
        twATag.target = ""
        fbLink.classList.remove("show")
        twLink.classList.add("show")
        inLink.classList.remove("show")
        baLink.classList.remove("show")
        kaLink.classList.remove("show")
    }
}
function completeTW() {
    let slicedTW = document.getElementById("twitter_link").value;
    twATag.href = slicedTW
    twATag.target = "_blank"
    twLink.classList.remove("show")
}

//인스타그램
function changeIN() {
    if (inLink.classList.contains("show")) {
        inLink.classList.remove("show")
    } else {
        inATag.href = "javascript:void(0)"
        inATag.target = ""
        fbLink.classList.remove("show")
        twLink.classList.remove("show")
        inLink.classList.add("show")
        baLink.classList.remove("show")
        kaLink.classList.remove("show")
    }
}
function completeIN() {
    let slicedIN = document.getElementById("instagram_link").value;
    inATag.href = slicedIN
    inATag.target = "_blank"
    inLink.classList.remove("show")
}

//네이버밴드
function changeBA() {
    if (baLink.classList.contains("show")) {
        baLink.classList.remove("show")
    } else {
        baATag.href = "javascript:void(0)"
        baATag.target = ""
        fbLink.classList.remove("show")
        twLink.classList.remove("show")
        inLink.classList.remove("show")
        baLink.classList.add("show")
        kaLink.classList.remove("show")
    }
}
function completeBA() {
    let slicedBA = document.getElementById("band_link").value;
    baATag.href = slicedBA
    baATag.target = "_blank"
    baLink.classList.remove("show")
}

//카카오스토리
function changeKA() {
    if (kaLink.classList.contains("show")) {
        kaLink.classList.remove("show")
    } else {
        kaATag.href = "javascript:void(0)"
        kaATag.target = ""
        fbLink.classList.remove("show")
        twLink.classList.remove("show")
        inLink.classList.remove("show")
        baLink.classList.remove("show")
        kaLink.classList.add("show")
    }
}
function completeCA() {
    let slicedCA = document.getElementById("kakao_link").value;
    kaATag.href = slicedCA
    kaATag.target = "_blank"
    kaLink.classList.remove("show")
}

//다음주소 호출
function openAddress() {
    new daum.Postcode({
        oncomplete: function(data) {
            document.getElementById("rep_address").setAttribute("value", data.address);
        }
    }).open();
}