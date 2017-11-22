$(document).ready(function() {
    $.browser.chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());

    if ($.browser.chrome === false) {
        // $('#js-replaceable').prepend(
        //     '<div class="error-summary">'+
        //     '<h1 class="heading-medium error-summary-heading">You are using an unsupported browser</h1>'+
        //     '<p>To use HOCS please quit and reload the website using Google Chrome.</p>' +
        //     '</div>'
        // );
    }
});