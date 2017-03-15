<?php

require_once('../../config.php');
require_once('forms.php');
require_once('lib.php');
require_once('renderer.php');

// Params
$bookletid = optional_param('bookletid', 0, PARAM_INT); // TODO UNUSED
$fileid = optional_param('fileid', 0, PARAM_INT); // File id for new form
$shouldUpdate = optional_param('update', 0, PARAM_INT);

// Variables
$booklet; // stdClass Booklet for injecting in the form
$mform; // booklet edit_from
$shouldDisplayForm = false; // Don't display form until $bookletid verification

// TODO Capability checks
require_login();

global $DB, $PAGE;

// PAGE SETUP
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Booklet: Edit');
$PAGE->set_pagelayout('admin');
$PAGE->set_url('/mod/rao_booklet/edit.php');

// Get the record
if( $bookletid != 0 ){
    // Get $booklet if valid
    $booklet = $DB->get_record('raobooklet_info', array('bookletid'=>$bookletid));
    if($booklet) $shouldDisplayForm = true;
} else if ($fileid != 0){
    // Create new booklet_info
    $file = $DB->get_record('files', array('id'=>$fileid), '*', MUST_EXIST);
    if($file->component == 'mod_raobooklet'){
        // Find booklet record else create a blank one
        if( $booklet = $DB->get_record('raobooklet_info', array('bookletid'=>$fileid))){
            $shouldUpdate = true;
            $shouldDisplayForm = true;
        } else {
            $booklet = new stdClass;
            $booklet->bookletid = $file->id;
            $booklet->name = $file->filename;
            $shouldDisplayForm = true;            
        }
    }
}

// Form setup
$mform = new mod_raobooklet_edit_form();
if(isset($booklet) && $shouldDisplayForm) $mform->set_data($booklet); // Inject data into form;

// Page Rendering
$output = $PAGE->get_renderer('mod_raobooklet');
echo $output->header();
echo $output->booklet_selector();
if(isset($booklet->id))   echo $output->heading("Updating existing bookletinfo for file id: ".$fileid);
else echo $output->heading("Creating new bookletinfo for file id: ".$fileid);


if($data = $mform->get_data()){
    if(isset($booklet->id)) $data->id = $booklet->id; // Insert id for updating existing record
    raobooklet_edit_info($data, $mform);
} else if($mform->is_cancelled()) {
    // Display just the template
    echo $output->heading('redirecting cuz is_cancelled');
    // TODO redirect here
} else if ( $shouldDisplayForm ){
    $mform->display();
} else {
    // TODO redirect here
    echo $output->heading('Please select a booklet');
}

echo $output->footer();