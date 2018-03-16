<?php
require_once('../../config.php');
require_once('idcard_form.php');
require_once('../branchadmin/locallib.php');
require_once('../library/locallib.php');
require_once("$CFG->libdir/gdlib.php");
$PAGE->set_url('/blocks/idcard_tracker/add_idcard.php');

if (is_branch_admin())
{ 
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Add New ID Card in Edumate');
    $PAGE->set_heading('Add New ID Card in Edumate');
    echo $OUTPUT->header();
    global $DB,$CFG,$USER,$COURSE; 
    
    $heading="Add New ID Card in Edumate";
    echo $OUTPUT->heading($heading);
    $mform = new add_idcard_form();
    if ($data = $mform->get_data()){
        $student = $DB->get_record('user',array('username'=>$data->student_username));   
        profile_load_data($student);
        $context = context_user::instance($student->id, MUST_EXIST);
        $user = core_user::get_user($USER->id, 'id, picture', MUST_EXIST);
        //$idcard_record = new stdClass(); 
        //$idcard_record->student_username = $data->student_username;
        //$idcard_record->profile_pic = $CFG->id_card_image.$data->profile_pic;
        //$idcard_record->issue_date = date('Y-m-d');
        //$idcard_record->branch = get_user_center();
        //$idcard_record->idcard_status = $data->idcard_status;
        //$idcard_record->id = $DB->insert_record('student_idcard', $idcard_record, $returnid=true);
        $content = $mform->get_file_content('profile_pic');
        $name = $data->student_username.'-'.$mform->get_new_filename('profile_pic');
        // check image file is exist //
        if (file_exists($CFG->id_card_image.$data->student_username)) {
            //echo $CFG->id_card_image.$data->student_username;
            echo "Image file is already exists. System successfully replaced image file";
            unlink($CFG->id_card_image.$data->student_username);
            $success = $mform->save_file('profile_pic',$CFG->id_card_image.$data->student_username);
            //$newpicture = (int)process_new_icon($context, 'user', 'icon', 0,$CFG->id_card_image.$data->student_username);
            //$DB->set_field('user', 'picture', $newpicture, array('id'=>$student->id));
            redirect(new moodle_url('view_profile.php?student_username='.$data->student_username));
        }
        else{
            //echo $CFG->id_card_image.$data->student_username;
            $success = $mform->save_file('profile_pic',$CFG->id_card_image.$data->student_username);
            //$newpicture = (int)process_new_icon($context, 'user', 'icon', 0,$CFG->id_card_image.$data->student_username);
            redirect(new moodle_url('view_profile.php?student_username='.$data->student_username));
        }
       // check image file is exist //
    } else {
        $mform->display();
    }
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
    echo "<h5>You are not Authorised Person to add id card info</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
}
echo $OUTPUT->footer();
?>