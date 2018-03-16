<?php
require_once('../../config.php');
require_once('idcard_form.php');
//require_once('../../user/profile/lib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/blocks/library/locallib.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once($CFG->libdir.'/filelib.php');
require_once("$CFG->libdir/gdlib.php");
$PAGE->set_url('/blocks/idcard_tracker/view_profile.php');
if (is_branch_admin())
{   global $USER,$COURSE;
    $PAGE->set_title('Add New ID Card in Edumate');
    $PAGE->set_heading('Add New ID Card in Edumate');
    $PAGE->set_pagelayout('standard');

    echo $OUTPUT->header();
    global $USER,$DB,$CFG,$COURSE;
    $heading="Student ID Card Information";
    echo $OUTPUT->heading($heading);
   // $courseid = required_param('courseid',PARAM_INT);
   // $context= get_context_instance(CONTEXT_COURSE, $courseid);
   // echo $context->id;
    $student_username = required_param('student_username',PARAM_INT);
    $user = $DB->get_record('user',array('username'=>$student_username));
    profile_load_data($user);
    //$user_picture = new user_picture($user);
    //$user_picture->size = true;
    $src = $CFG->wwwroot.'/blocks/idcard_tracker/id_img_serve.php?username='.$user->username;
    //$user_picture->get_url($PAGE);
    //var_dump($src);

    // load profile data //
    $idcard_valid="07-JUNE ".$user->profile_field_targetyear;
    $student_idcard = $DB->get_record('student_idcard_submit', array("student_username"=>$student_username));
   
    if ($student_idcard != null)
    {
        echo html_writer::div('ID Card Already Submitted');
        echo $OUTPUT->continue_button($CFG->wwwroot);
        //TODO:ABHISHEK
        //SHOW THE ID CARD
        // UPDATE QUERY //
        /*$mform = new view_profile_form (null, array('student_username'=>$student_username,'profile_pic'=>$src,'student_fullname'=>$user->firstname." ".$user->lastname,'student_course'=>$user->profile_field_coursetypename['text'],'student_targetyear'=>$user->profile_field_xiiyear,
        'idcard_valid'=>$idcard_valid,'student_mobile_number'=>$user->profile_field_studentmobile,
        'branch'=>$user->profile_field_center));
        
        if ($data = $mform->get_data())
        {
            $new_data = new stdClass();
            $new_data->id = $student_idcard->id;
            $new_data->student_fullname = $data->student_fullname;
            //$new_data->profile_pic =$CFG->id_card_image.$student_username;
            // $CFG->id_card_image.$student_username
            $result = $DB->update_record('student_idcard_submit', $new_data);
            echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/idcard_tracker/view_idcards.php');
            // UPDATE QUERY //
        }
        else{
            $mform->display();
        }
        */
    }
    else
    {
        $mform = new view_profile_form (null, array('student_username'=>$student_username,'profile_pic'=>$src,'student_fullname'=>$user->firstname." ".$user->lastname,'student_course'=>$user->profile_field_coursetypename['text'],'student_targetyear'=>$user->profile_field_xiiyear,'student_mobile_number'=>$user->profile_field_studentmobile,
        'idcard_valid'=>$idcard_valid,
        'branch'=>get_center_name($user->profile_field_center)));
        
       if ($data = $mform->get_data()){
            $student_idcard_submit = new stdClass();  
            $student_idcard_submit->student_username = $data->student_username;
            $student_idcard_submit->profile_pic = $src;
            $student_idcard_submit->student_fullname = $data->student_fullname;
            $student_idcard_submit->branch = $data->branch;
            $student_idcard_submit->student_course = $data->student_course;
            $student_idcard_submit->student_targetyear = $data->student_targetyear;
            $student_idcard_submit->idcard_valid = "07-JUNE ".$user->profile_field_xiiyear;
            $student_idcard_submit->student_mobile_number = $data->student_mobile_number;
            $student_idcard_submit->idcard_status = 0;
            $DB->insert_record('student_idcard_submit', $student_idcard_submit);
            //save the image as user image
            $context = context_user::instance($user->id, MUST_EXIST);
            $newpicture = (int)process_new_icon($context, 'user', 'icon', 0,$CFG->id_card_image.$user->username);
            $DB->set_field('user', 'picture', $newpicture, array('id'=>$user->id));
            echo html_writer::div('ID Card Successfully Submitted');
            echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/idcard_tracker/add_idcard.php');
        } 
        else{
            $mform->display();
        }
    echo $OUTPUT->footer();
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
    echo "<h5>You are not Authorised Person to Submit ID Cards</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
    echo $OUTPUT->footer();
}
?>