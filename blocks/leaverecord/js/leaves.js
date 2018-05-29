$("#id_leave_from_day").hide();
$("#id_leave_from_month").hide();
$("#id_leave_from_year").hide();

$("#id_leave_to_day").hide();
$("#id_leave_to_month").hide();
$("#id_leave_to_year").hide();
$("#fitem_id_date").show();
$("#fitem_id_leave_from").hide();
$("#fitem_id_leave_to").hide();


$(".fgrouplabel").hide();
$("input[name=leave_status]").click(function(){
	
var id = $(this).attr("id");

	if(id=='id_leave_status_3'){
		 $("#id_leave_from_day").show();
		 $("#id_leave_from_month").show();	
		 $("#id_leave_from_year").show();
		 $(".visibleifjs").show();
		 $(".fgrouplabel").show();
		 $("#id_leave_to_day").show();
		 $("#id_leave_to_month").show();
                 $("#id_leave_to_year").show();
		$("#id_date_day").hide();
		$("#id_date_month").hide();
		$("#id_date_year").hide();
		$("#fitem_id_date").hide();
		$("#fitem_id_leave_from").show();
		$("#fitem_id_leave_to").show();
	}
	else{
		$("#id_leave_from_day").hide();
		$("#id_leave_from_month").hide();
		$("#id_leave_from_year").hide();
		$(".visibleifjs").hide();
		$(".fgrouplabel").hide();
		$("#id_leave_to_day").hide();
		$("#id_leave_to_month").hide();
		$("#id_leave_to_year").hide();
		$("#id_date_day").show();
		$("#id_date_month").show();
		$("#id_date_year").show();
		$("#fitem_id_date").show();

	}
	
});

