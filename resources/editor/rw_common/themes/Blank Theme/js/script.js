
function ready(fn){if(document.readyState!='loading'){fn();}else if(document.addEventListener){document.addEventListener('DOMContentLoaded',fn);}else{document.attachEvent('onreadystatechange',function(){if(document.readyState!='loading')
fn();});}}
ready(function(){var mobileNavToggle=document.getElementById("blank-theme-show-nav");mobileNavToggle.onclick=function(){mobileNavToggle.parentNode.classList.toggle('blank-theme-show-nav');};});