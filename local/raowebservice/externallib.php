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
               array('username' => new external_value(PARAM_TEXT, 'User name list', VALUE_REQUIRED))
        );
    }

public static function raouser_profile_update($username) {
    global $USER, $DB ,$CFG ;

    $params = self::validate_parameters(self::raouser_profile_update_parameters(), array('username' => $username));

    $user_data = $DB->get_record('user',array('username' => $username),$strictness=IGNORE_MULTIPLE);

    $array_pop=array_pop($user_data);
  
    $user_id = $user_data[0]->id;
    
    $new_user = new stdClass();
    $new_user->id= $user_id;
    $new_user->firstname = $user_data[0]->firstname;
    $new_user->fieldid = 2;
    $new_user->data=$data;

profile_load_data($new_user);


//  /*
if ($fields = $DB->get_records('user_info_field',array('id' => $user_id))) 


{
  foreach ($fields as $field) {
  require_once($CFG->dirroot.'/user/profile/field/'.$field->datatype.'/field.class.php');
  $newfield = 'profile_field_'.$field->datatype;

  $formfield = new $newfield($field->id, $new_user->id);

  $formfield->edit_save_data($new_user);

 }}
//  /*
$update_user=$DB->update_record('user', $new_user);
profile_save_data($update_user);
      return json_encode((array)$update_user);
    }
public static function raouser_profile_update_returns() {
          return new external_value(PARAM_RAW, 'username');
    }
}