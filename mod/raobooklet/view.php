<?php
/*
    This is booklet activity page. Booklet or rao booklet is module in moodle which showing information about the perticular booklet. This page showing booket name, booklet feedback form and read button to view perticular booklet.
*/
require_once('../../config.php');
require_once('forms.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT); // booklet id
$fb = optional_param('fb', 0, PARAM_INT); // Save feedback if 1
$fb_comment = optional_param("comment", "", PARAM_TEXT); // feedback comment optional
$fb_rating = optional_param("rating", "", PARAM_INT); // feedback rating
$thank = optional_param("thank", 0, PARAM_INT); // Display thank you message if 1
// Variables
global $DB, $PAGE;
$showFbForm = FALSE;

require_login(); // user login require
// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('raobooklet', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST); // get course of perticular booklet
    $raobooklet = $DB->get_record('raobooklet', array('id'=>$cm->instance), '*', MUST_EXIST); // get booklet records from table
} else {
    error('Your must specify a course_module ID or an instance ID');
}
require_login($course, true, $cm);


$fbform = new mod_raobooklet_feedback_form("view.php?id=$id&fb=1"); // creating feedback form object and id passes to it

if ($DB->record_exists('raobooklet_feedback', array('userid'=> $USER->id)) ){ // if feedback id is exist 
    // Get last feedback by user
    $feedback = $DB->get_record('raobooklet_feedback', array('userid'=> $USER->id), "*", MUST_EXIST); // get record from perticular id
    $feedback->updatelink = "view.php?id=$id&fb=1"; 
    $feedback->back_link = $id;
} else {
    $showFbForm = TRUE;
}


if ($fb == 1) { // Save/update the feedback record
    if( $new_feedback = $fbform->get_data() ) { // get data from feedback form
        $result = raobooklet_add_or_update_feedback($raobooklet->bookletid, $new_feedback); // booklet add or edit with current booklet id and feedback
        if ( $result ) {
            redirect("view.php?id=$id&thank=1");
        } else {
            redirect("view.php?id=$id");
        }
    } else if (isset($feedback)) {
        $fbform->set_data($feedback);
    }
    $showFbForm = TRUE;
}


$PAGE->set_title(format_string("Booklet: "));
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/mod/raobooklet/view.php', array('id' => $id, 'fb' => $fb));
$PAGE->set_heading(format_string("Booklet: "));

$output = $PAGE->get_renderer('raobooklet');

echo $output->header();
if($raobooklet){
    // echo var_dump($raobooklet);
    echo $output->booklet_info($raobooklet->bookletid, $instanceid = $id);// showing output fromn bookelt info
}
if ( $showFbForm == TRUE ) {
    $fbform->display();
} else {
    if($thank == 1) echo '<h4 class="text-primary">Thank you for the feedback!</h4>'; // after given feedback thank you message
    echo $output->booklet_feedback($feedback);
}

echo $output->footer();