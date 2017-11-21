var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();
var field_selector = '#id_book_barcode';
$(field_selector).focus();
$(field_selector).scannerDetection(function(barcode,qty){
    console.log(barcode);
    //fetch info for this book
    //via ajax
});


$(field_selector).on("input", function() {
    delay(function(){
        if ($(field_selector).val().length < 8) {
            $(field_selector).val("");
        }
    }, 20 );
});
$(document).ready(function(){
    //$('#barcode_value').hide();

});