<?php
//does a select sync of only the branch and center details only
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once('../../timetable/locallib.php');
require_once('../locallib.php');
global $DB;
//get all the users in moodle
$users = $DB->get_records('user',array('auth'=>'db'));
$link = connect_analysis_db();
$center_id = 57;
$batch_id = 58;

function update_record($fieldid, $userid, $new_data){
    global $DB;
    $record = $DB->get_records('user_info_data',array('fieldid'=>$fieldid,'userid'=>$userid));
    if (count($record) == 0){
        //need to add this entry
        $rec = new stdClass();
        $rec->fieldid = $fieldid;
        $rec->userid = $userid;
        $rec->data = $new_data;
        $DB->insert_record('user_info_data',$rec);
        return True;
    } else {
        //get the key of the 1st entry
        $rec = array_values($record)[0];
        if ($rec->data != $new_data){
            //value updated in analysis
            $rec->data = $new_data;
            $DB->update_record('user_info_data',$rec);
            return True;
        } else{
            return False;
        }
    }
    return False;
}

$branch_info = load_field_records_edumate('branchadmin_centre_info');
$batch_info = load_field_records_edumate('ttbatches');

foreach($users as $user){
    $data = get_user_center_batch_analysis($user->username, $link);
    $change = False;
    if ($data == False){
        echo 'NO USER DATA ON ANALYSIS:'.$user->username.PHP_EOL;
        continue;
    }
    if ($data['centre']!=0){
        //update the center value if needed
        if (update_record($center_id, $user->id, $data['centre'])){
            echo $user->username.':'.$data['centre'].PHP_EOL;
            $change = True;
        }
    }
    if ($data['ttbatchid'] != 0){
        if (update_record($batch_id, $user->id, $data['ttbatchid'])){
            echo $user->username.':'.$data['ttbatchid'].PHP_EOL;
            $change = True;
        }
    }
    if (!$change){
        echo $user->username.PHP_EOL;
    }
}
close_analysis_db($link);