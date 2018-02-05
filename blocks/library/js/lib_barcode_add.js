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
});

$('#id_book').on('change', function(){
    console.log($(this).val());
    $.getJSON({
        method: 'get',
        url: 'fetch_book_info_id.php',
        data: 'id='+$(this).val(),
        success: function(resp){
            book_id=resp.bookid;
            volume=resp.volume;
            publisher=resp.publisher;
            author=resp.author;
            price=resp.price;
            var main_region = document.getElementById('region-main');
            var para = document.getElementById('new_book_info');
            var created = false;
            if (para==undefined){
                created = true;
                para = document.createElement("p");
                para.setAttribute('id','new_book_info');
            }

            var p_ihtml = "Book Name : "+resp.bookid+
            "<br>Volume : "+resp.volume+
            "<br>Publisher : "+resp.publisher+
            "<br>Author : "+resp.author+
            "<br><b>Please verify above book info correct or not</b>";0
            para.innerHTML = p_ihtml;
            if (created){
                main_region.appendChild(para);
            }
         }
         //success function end
    });
})

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