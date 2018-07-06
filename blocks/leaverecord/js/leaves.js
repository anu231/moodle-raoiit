$("#id_leave_from_day").hide();
$("#id_leave_from_month").hide();
$("#id_leave_from_year").hide();

$("#id_leave_to_day").hide();
$("#id_leave_to_month").hide();
$("#id_leave_to_year").hide();
$("#fitem_id_date").show();
$("#fitem_id_leave_from").hide();
$("#fitem_id_leave_to").hide();

$("#fitem_id_od_start_from").hide();
$("#fitem_id_od_end_to").hide();
$("#fitem_id_od_date").hide();
$(".fgrouplabel").hide();

$("#fitem_id_hw_content").hide();
$("#fitem_id_break_duration").hide();

$("input[name=leave_status]").click(function(){
	
var id = $(this).attr("id");

	if(id=='id_leave_status_md'){
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
		$("#fitem_id_odstart_time").hide();
		$("#fitem_id_odend_time").hide();
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
		$("#fitem_id_odstart_time").show();
		$("#fitem_id_odend_time").show();
	}
	
});

// OD //


$("input[name=od_status]").click(function(){
	
	var id = $(this).attr("id");
	
		if(id=='id_od_status_3'){
			$("#fitem_id_od_start_from").show();
			$("#fitem_id_od_end_to").show();
			$("#id_odstarttime").hide();
			$("#id_odendtime").hide();
			$("#fitem_id_od_date").show();
			    
		}
		else{
			$("#fitem_id_od_start_from").hide();
			$("#fitem_id_od_end_to").hide();
			$("#id_odstarttime").show();
			$("#id_odendtime").show();
			$("#fitem_id_od_date").hide();
		}
		
	});

	$("input[name=lecture_cancelled]").click(function(){
		var id = $(this).attr("id");
		alert(id);
		if(id=='id_lecture_cancelled_1'){
			$("#fitem_id_topic").hide();
			$("#fitem_id_lec_no").hide();
			$("#fitem_id_mark_topic_completion").hide();
			$("#id_hw_section").hide();
			$("#id_br_section").hide();
			$("#id_note_section").hide();
		}
		else{
			$("#fitem_id_topic").show();
			$("#fitem_id_lec_no").show();
			$("#fitem_id_mark_topic_completion").show();
			$("#id_hw_section").show();
			$("#id_br_section").show();
			$("#id_note_section").show();
		}
		
	});

	// Homework Submission //

	$("input[name=hw_status]").click(function(){
		var id = $(this).attr("id");
		if(id=='id_hw_status_1'){
			$("#fitem_id_hw_content").show();
		}
		else{
			$("#fitem_id_hw_content").hide();
		}
	});

	$("input[name=break_given]").click(function(){
		var id = $(this).attr("id");
		if(id=='id_break_given_1'){
			$("#fitem_id_break_duration").show();
		}
		else{
			$("#fitem_id_break_duration").hide();
		}
	});

	