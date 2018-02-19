function get_schedule_list(batch_id){
    var day = $('#id_date_year').val()+'-'+$('#id_date_month').val()+'-'+$('#id_date_day').val();
    //var day = new Date($('#id_date_year').val(), $('#id_date_month').val(), $('#id_date_day').val());//$('#id_date_day').val()+'-'+$('#id_date_month').val()+'-'+$('#id_date_year').val();
    //day = day.getDate()+'-'+day.getMonth()+'-'+day.getFullYear();
    console.log(day);
    //alert(batch_id);
    $.ajax({
        type:'get',
        url:'fetch_batch_info.php',
        data:'batch_id='+batch_id+'&date='+day,
        
        success:function(res)
        {   
            console.log(res);
            var batch_record = JSON.parse(res);
            if (batch_record.lectures.length == 0){
                alert('No lectures exist for this batch in the specified date');
                $('#id_schedule_id').empty();
                return;
            }
            $.each(batch_record.lectures[0].items, function (i, item) {
                var text = new Array(item.starttime, item.endtime, item.subject, item.teacher, item.topicname, item.notes);
                $('#id_schedule_id').append($('<option>', { 
                    value: item.sid,
                    text : text.join(' - ') 
                }));
            });
            set_student_list(batch_record.students);
        }
    });
}

function set_student_list(students){
    var container = $('#multi_select_student');
    if (container.length == 0){
        container = $('<div />', {id:'multi_select_student'}).appendTo('#fitem_id_select_roll_numbers');
    } else {
        container = container[0];
        $(container).html('');
    }
    $.each(students, function(i, item){
        $('<input />', {type:'checkbox', class:'roll_select', id:'mid-'+item.id, value:item.id}).appendTo(container);
        $('<label />', {for:'mid-'+item.id, text:item.name}).appendTo(container);
        $('<br>').appendTo(container);
    })
}

$('#id_batch').change(function(){
    var batch_id = $(this).val();
    get_schedule_list(batch_id);
});

$(document).ready(function(){
    //$("#id_roll_numbers").prop('disabled', true);
    get_schedule_list($('#id_batch').val());
})

function finalize_students(){
    var student_list = Array();
    $.each($('.roll_select'), function(i, item){
        if (item.checked){
            console.log(item.value);
            student_list.push(item.value);
        }
    });
    console.log(student_list.join(','));
    $('#id_roll_numbers')[0].value = student_list.join(',');
}