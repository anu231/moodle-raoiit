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
            book_id=resp.bookid;
            volume=resp.volume;
            publisher=resp.publisher;
            author=resp.author;
            price=resp.price;
            var main_region = document.getElementById('region-main');
            var book_barcode = document.getElementById('id_book_barcode').value;
            var lenghth_barcode = book_barcode.length;
           // alert(lenghth_barcode);
            if(lenghth_barcode==13){
                var para = document.createElement("p");
                //var node = document.createTextNode(
                var p_ihtml = "Book Name : "+resp.bookid+
                "<br>Volume : "+resp.volume+
                "<br>Publisher : "+resp.publisher+
                "<br>Author : "+resp.author+
                "<br><b>Please verify above book info correct or not</b>";0
                //para.appendChild(node);
                para.innerHTML = p_ihtml;
                main_region.appendChild(para);
                //alert("book Name : "+$book_id+"\nvolume : "+$volume+"\npublisher : "+$publisher+"\nauthor : "+$author+"\nprice : "+$price+"\nPlease verify above book info correct or not");
                return false;
                //document.getElementById("book_id").innerHTML = $book_id;
            }
            else {
                alert ("Book is already scanned");
                location.reload(true);
                return false;
             
            }
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