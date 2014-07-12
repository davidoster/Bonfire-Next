//= require ../application/assets/js/jquery-2.1.1.js
//= require ../application/assets/js/bootstrap320/bootstrap.js

$(document).ready(function() {

    // Add 'Back to Top' links on all h2s
    $('.page h2').after('<a href="#top" class="top-btn">&uarr; Back to Top</a>');

    // Toggle our TOC display
    $('#toc-btn').click(function(e){
        $('.toc').slideToggle('fast');
        $('.toc-wrapper').toggleClass('inner-shadow');
    });
});
