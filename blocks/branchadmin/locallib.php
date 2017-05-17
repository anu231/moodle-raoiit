<?php

require_once('../../config.php');

/*
gets users who belong to the same center as the current user
*/
function get_user_center(){
    global $USER;
    if ($USER->id==null){
        return '';
    }
    $user = new stdClass();
    $user->id = $USER->id;
    profile_load_data($user);
    return $user->profile_field_center;
}

function get_center_obj($centre_name){
    global $DB;
    $center = $DB->get_records_sql('select * from {branchadmin_centre_info} where name like ?',array($centre_name));
    if (count($center)>=0){
        return $center[key($center)];
    } else {
        return false;
    }
}

function get_batches_for_user($center){
    //gets the names of all batches with the current user
    //initialize curl handle
    //get centre object
    global $DB;
    $centre_obj = get_center_obj($center);
    if (!$centre_obj){
        return false;
    }
    $batches = $DB->get_records('branchadmin_ttbatches',array('centreid'=>$centre_obj->id));
    $batch_list = array();
    foreach($batches as $batch){
        array_push($batch_list,$batch->name);
    }
<<<<<<< HEAD

    profile_load_data($USER);
    $sql = 'select * from {user} as u join {user_info_data} as ud where ud.data like ?';
    $users_by_center = $DB->get_records_sql($sql,array($USER->profile_field_center));
    return $users_by_center;
}
=======
    return $batch_list;
}
>>>>>>> 4e821aedb13279117d290e66d42e2a0f394357f0
