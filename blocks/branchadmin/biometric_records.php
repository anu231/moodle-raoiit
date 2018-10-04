<?php
require_once('../../config.php');
require_once('info_form.php');
require_once('../attendance/locallib.php');
require_once('locallib.php');
//require_once('fetch_book_info.php');
$PAGE->set_url('/blocks/branchadmin/biometric_records.php');

    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Biometric Records');
    $PAGE->set_heading('Biometric Records');
    echo $OUTPUT->header();
    $heading="Biometric Records";
    echo $OUTPUT->heading($heading);
    $mform = new student_attendance_form();
    if ($data = $mform->get_data()){
        $start_date = date('Y-m-d', $data->startdate);
        $end_date = date('Y-m-d', $data->enddate);
        global $CFG,$USER,$DB;
        $center_id = get_user_center($USER->id);
        
        $user = new stdClass();
        $user->id = $USER->id;
        $sql = 'select * from {user} as u join {user_info_data} as ud ON u.id = ud.userid where ud.data like ?';
        $users_by_center = $DB->get_records_sql($sql,array($center_id));
        
        $user_list = array(); 
       
        foreach ($users_by_center as $user){
            $temp = array();
              
            $temp['username'] = $user->username;
             
            $user_list[] = $temp;
            $rcount = count($user_list);
            for($i=0;$i<$rcount;$i++){
                $roll_number = $user_list[$i]['username'];
                $result = get_attendance_records($roll_number,$start_date,$end_date);
            }
          
        }
       
     
        print_r($result);
           

    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();


?>