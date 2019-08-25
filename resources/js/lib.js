function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            input.nextElementSibling.setAttribute('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function click_to_change(id) {
    document.getElementById(id).click();
}