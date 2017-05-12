<?php

require_once('../../config.php');


/*
gets users who belong to the same center as the current user
*/
function get_students_by_center(){
    global $CFG,$USER;
    if ($USER->id==null){
        return null;
    }
    profile_load_data($USER);
    $sql = 'select * from {user} as u join {user_info_data} as ud where ud.data like ?';
    $users_by_center = $DB->get_records_sql($sql,array($USER->profile_field_center));
    return $users_by_center;
}