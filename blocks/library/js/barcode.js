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
     // $.getJSON({
        $.getJSON({
        method: 'post',
        url: 'fetch_book_info.php',
        data: 'barcode='+barcode,
        success: function(resp){
            $book_id=resp.bookid;
            $volume=resp.volume;
            $publisher=resp.publisher;
            $author=resp.author;
            $price=resp.price;
            alert("book Name : "+$book_id+"\nvolume : "+$volume+"\npublisher : "+$publisher+"\nauthor : "+$author+"\nprice : "+$price+"\nPlease verify above book info correct or not");
           return false;
            //document.getElementById("book_id").innerHTML = $book_id;
         }
         //success function end
    });
   

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