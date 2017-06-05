<?php

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot.'/user/profile/lib.php');
////////////////////////////////////////////////// Rao Web Services ////////////////////////////////////////////////////////////////

class local_raowebservice_external extends external_api {

 
    public static function list_user_parameters() {
        return new external_function_parameters(
               array('courseid' => new external_value(PARAM_INT, 'grievance categories name list', VALUE_REQUIRED))
        );
    }

    public static function list_user($courseid) {
     global $USER, $DB;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::list_user_parameters(),
                  array('courseid' => $courseid));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
       

       $grievance_name = $DB->get_records('grievance_entries',array());
       return json_encode((array)$grievance_name) ;

    }
   
    
    public static function list_user_returns() {
          return new external_value(PARAM_TEXT, 'List of grievance categories availbale in the specified course');
    }

}

////////////////////////////////////////////////// User Profile Web Services ////////////////////////////////////////////////////////////////
class local_raowebservice_profile extends external_api {

public static function raouser_profile_update_parameters() {
        return new external_function_parameters(
               array('username' => new external_value(PARAM_TEXT, 'User name whose info is to be modified', VALUE_REQUIRED),
                     'field' => new external_value(PARAM_TEXT, 'Field name whose info is going to be modified', VALUE_REQUIRED),
                     'value' => new external_value(PARAM_TEXT, 'New Value of the field', VALUE_REQUIRED),
               )
        );
    }

public static function raouser_profile_update($username, $field, $value) {
    global $USER, $DB ,$CFG ;

    $params = self::validate_parameters(self::raouser_profile_update_parameters(), array('username' => $username,'field'=>$field,'value'=>$value));

    $user_data = $DB->get_record('user',array('username' => $username),'id',$strictness=IGNORE_MULTIPLE);

    $new_user = new stdClass();
    $new_user->id= $user_data->id;

    profile_load_data($new_user);
    $new_user->$field = $value;
    profile_save_data($new_user);
    return json_encode((array)$new_user);
}
public static function raouser_profile_update_returns() {
          return new external_value(PARAM_RAW, 'userdata');
    }
}