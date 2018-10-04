<?php

require_once('../../config.php');
require_once('forms.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);
$fb = optional_param('fb', 0, PARAM_INT); // Save feedback if 1
$fb_comment = optional_param("comment", "", PARAM_TEXT);
$fb_rating = optional_param("rating", "", PARAM_INT);
$thank = optional_param("thank", 0, PARAM_INT); // Display thank you message if 1
// Variables
global $DB, $PAGE;
$showFbForm = FALSE;

require_login();
// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('raobooklet', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $raobooklet = $DB->get_record('raobooklet', array('id'=>$cm->instance), '*', MUST_EXIST);
} else {
    error('Your must specify a course_module ID or an instance ID');
}
require_login($course, true, $cm);


$fbform = new mod_raobooklet_feedback_form("view.php?id=$id&fb=1");

if ($DB->record_exists('raobooklet_feedback', array('userid'=> $USER->id)) ){
    // Get last feedback by user
    $feedback = $DB->get_record('raobooklet_feedback', array('userid'=> $USER->id), "*", MUST_EXIST);
    $feedback->updatelink = "view.php?id=$id&fb=1";
    $feedback->back_link = $id;
} else {
    $showFbForm = TRUE;
}

// Save/update the feedback record
if ($fb == 1) {
    if( $new_feedback = $fbform->get_data() ) {
        $result = raobooklet_add_or_update_feedback($raobooklet->bookletid, $new_feedback);
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
    echo $output->booklet_info($raobooklet->bookletid, $instanceid = $id);
}
if ( $showFbForm == TRUE ) {
    $fbform->display();
} else {
    if($thank == 1) echo '<h4 class="text-primary">Thank you for the feedback!</h4>';
    echo $output->booklet_feedback($feedback);
}

echo $output->footer();