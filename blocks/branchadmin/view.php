<?php
 
require_once('../../config.php');
require_once('simplehtml_form.php');
 
global $DB;
 
// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
 
 
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_branchadmin', $courseid);
}
 
require_login($course);
$PAGE->set_url('/blocks/branchadmin/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_branchadmin'));
$simplehtml = new simplehtml_form();
echo $OUTPUT->header();
$simplehtml->display();
echo $OUTPUT->footer();
$simplehtml->display();
?>