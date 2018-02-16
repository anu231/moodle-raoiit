$('#id_batch').change(function(){
    var batch_id = $(this).val();
    //alert(batch_id);
    $.ajax({
        type:'post',
        url:'fetch_batch_info.php',
        data:'batch_id='+batch_id,
        
        success:function(res)
        {   
            var batch_record = JSON.parse(res);
            //alert(batch_record.bookid);
            document.getElementById("id_scheduled_id").value=batch_record.bookid;
            document.getElementById("id_tpoic_id").value=batch_record.name;
        }
    });
});