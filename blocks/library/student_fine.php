<?php
require_once('../../config.php');
require_once('books_form.php');
$PAGE->set_url('/blocks/library/student_fine.php');
$context = context_course::instance($CFG->branchadmin_courseid);
if(is_enrolled($context, $USER->id, '', true)){
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('View Student Library Fine');
    $PAGE->set_heading('View Student Fine');
    echo $OUTPUT->header();
    $heading="View Student Fine";
    echo $OUTPUT->heading($heading);
    $mform = new student_fine_form();
    echo "<br>"; 
    if ($data = $mform->get_data()){
        $student_username = $data->student_username;
        redirect(new moodle_url('view_student_fine.php?student_username='.$student_username));
    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();
}
else
{
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
GLOBAL $USER;
$firstname=$USER->firstname;
$lastname= $USER->lastname;
 $fullname=$firstname." ".$lastname;
echo "<h5>Dear, $fullname </h5>";
echo "<br>";
echo "<h5>You are not Authorised Person to access this page</h5>";
echo $OUTPUT->continue_button($CFG->wwwroot);

echo $OUTPUT->footer();
}
?>