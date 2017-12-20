<?php
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');

function get_student_dlp_info($username){
    global $DB;
    $info_records = $DB->get_records_sql('select fid.id as id, fid.data as data, fid.fieldid as fieldid, fid.userid as userid from {user_info_data} as fid join {user_info_field} as fi join {user} as u on fid.userid=u.id and fid.fieldid=fi.id where u.username=? and fi.shortname in (?,?,?)',array($username,'videoaccess','bookletaccess','testaccess'));
    $ret_records = array();
    foreach($info_records as $rec){
        $ret_records[$rec->fieldid] = $rec;
    }
    return $ret_records;
}

$params = cli_get_params(array(
    'file' => false
));
echo $params[0]['file'].PHP_EOL;
$filename = $params[0]['file'];
$field_map = array(
    'username'=>1,
    'videoaccess'=>7,
    'testaccess'=>8,
    'bookletaccess'=>9
);
//get user list from csv file
//get id codes for videoaccess, testaccess and bookletaccess
global $DB;
$id_records = $DB->get_records_sql('select id, shortname from {user_info_field} where shortname in (?,?,?)', array('videoaccess', 'testaccess', 'bookletaccess'));
$field_id_map = array();
foreach($id_records as $rec){
    $field_id_map[$rec->shortname] = $rec->id;
}
$field_to_csv_map = array();
foreach(array_slice($field_map,1) as $key=>$value){
    $field_to_csv_map[$field_id_map[$key]] = $value;
}
print_r($field_to_csv_map);
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        //print_r($data);
        //get this student's dlp related info frm user fields
        //check if user exists
        $user = $DB->get_record('user',array('username'=>$data[$field_map['username']]));
        if ($user == null){
            echo 'User Not Found -'.$data[$field_map['username']].PHP_EOL;
            continue;
        }
        $student_info = get_student_dlp_info($data[$field_map['username']]);
        #print_r($student_info);
        echo $user->username.PHP_EOL;
        foreach($field_to_csv_map as $key=>$value){
            //check if this entry exists in student info
            #print_r($student_info[$key]);
            if (array_key_exists($key, $student_info)){
                if ($data[$value]!=$student_info[$key]->data){
                    $new_obj = new stdClass();
                    $new_obj->id = $student_info[$key]->id;
                    $new_obj->fieldid = $key;
                    $new_obj->userid = $student_info[$key]->userid;
                    $new_obj->data = $data[$value];
                    $DB->update_record('user_info_data',$new_obj);
                    echo 'updating -'.$key.PHP_EOL;
                }
            } else{
                //create new object
                $new_obj = new stdClass();
                $new_obj->fieldid = $key;
                $new_obj->userid = $user->id;
                $new_obj->data = $data[$value];
                $DB->insert_record('user_info_data',$new_obj);
                echo 'inserting -'.$key.PHP_EOL;
            }
        }
    }
    fclose($handle);
}