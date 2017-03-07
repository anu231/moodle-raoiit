<?php

require_once('../../config.php');
require_once('feedback_form.php');
require_once('lib.php');

$id = optional_param('id', 0, PARAM_INT);
$r = optional_param('r', 0, PARAM_INT);     // raobooklet instanceid
$link = optional_param('link', 0, PARAM_INT); // Display download link?

$fb = optional_param('fb', 0, PARAM_INT); // Feedback?
$fb_comment = optional_param("comment", "", PARAM_TEXT);
$fb_rating = optional_param("rating", "", PARAM_INT);

// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('raobooklet', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $raobooklet = $DB->get_record('raobooklet', array('id'=>$cm->instance), '*', MUST_EXIST);
} else if ($r) {
    $raobooklet = $DB->get_record('raobooklet', array('id'=>$r), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$raobooklet->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('raobooklet', $raobooklet->id, $course->id, false, MUST_EXIST);
} else {
    error('Your must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);



// Get the file 
$context = context_module::instance($cm->id);
$filearea = "booklets";
$itemid = $raobooklet->year;
$filepath = '/';
$filename = $raobooklet->filename;

$fs = get_file_storage();
$file = $fs->get_file($context->id, 'mod_raobooklet', $filearea, $itemid, $filepath, $filename);

// Attach instanceid to raobooklet instance.
$raobooklet->instance = $context->id;
$raobooklet->back_link = $id;

// Create and set the file download link if allowed
if ($link = 1) {
    // $url = "$CFG->wwwroot/pluginfile.php/$context->id/mod_raobooklet/booklets/$raobooklet->year/$raobooklet->filename"; // This is for reference. It's adviced ot use the below method instead.
    $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    $raobooklet->link = $url; // Set the download link
}

// FEEDBACK HANDLING

// Display feedback form
if ( $fb != 1 && $DB->record_exists('raobooklet_feedback', array('userid'=> $USER->id)) ){
    // Get last feedback by user
    $feedback = $DB->get_record('raobooklet_feedback', array('userid'=> $USER->id), "*", MUST_EXIST);
    $feedback->updatelink = "view.php?id=$id&fb=1";
} else {
    $fbform = new mod_raobooklet_feedback_form("view.php?id=$id&fb=1");
}

// Save/update the feedback record
if ($fb == 1) {
    if( $fb_comment != 0 or $fb_rating != 0 ) {
        $result = raobooklet_add_or_update_feedback($id, $fb_rating, $fb_comment, $USER->id);
        if ( $result ) {
            redirect("view.php?id=$id&thank=1");

        } else {
            redirect("view.php?id=$id");
        }
    }
}


// Page setup
$PAGE->set_url('/mod/raobooklet/view.php', array('id' => $cm->id, 'fb' => $fb));
$PAGE->set_title(format_string("Booklet: ".$raobooklet->name));
$PAGE->set_heading(format_string("Booklet: ".$raobooklet->name));

// Render output
$output = $PAGE->get_renderer('mod_raobooklet');
echo $output->header();
echo $output->raobooklet($raobooklet);

if ( isset($fbform) ) {
    $fbform->display();
} else {
    echo $output->raobooklet_feedback($feedback);
}

echo $output->footer();