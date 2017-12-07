<?php

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot.'/user/profile/lib.php');
//require_once('/locallib.php');
require_once("$CFG->dirroot/local/raowebservice/locallib.php");
require_once("$CFG->dirroot/enrol/externallib.php");
///////////////////////////// Rao Web Services /////////////////////////////////////

class local_raowebservice_external extends external_api {

 
    
/////////////////////////////////// Rao Web Services - User Info/////////////////////////////////////////

    public static function user_info_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function user_info() {
        global $USER;
        return json_encode($USER);
        
        }
   
    
    public static function user_info_returns() {
          return new external_value(PARAM_TEXT, 'Get User Information');
    }

    /////////////////////////////////// Rao Web Services - Course Information /////////////////////////////////////////

    public static function get_course_info_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_course_info() {
        
        global $USER, $DB;
        $userid = $USER->id;

        $course_info = core_enrol_external::get_users_courses($userid);
        
        $courseid  = $course_info[0]['id'];
        echo $courseid;

        $course_info = json_encode($course_info);
        
        return $course_info;

        }
   
    
    public static function get_course_info_returns() {
          return new external_value(PARAM_TEXT, 'Get Course Information');
    }

    /////////////////////////////////// Rao Web Services - Paper List /////////////////////////////////////////

    public static function get_user_paper_list_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_user_paper_list() {
        
        global $USER, $DB;
        $userid = $USER->id;

        $course_info = core_enrol_external::get_users_courses($userid);
        $courseid  = $course_info[0]['id'];

        //echo $courseid;
        $paper_list = get_paper_list($courseid);
        return json_encode($paper_list);

        }
   
    
    public static function get_user_paper_list_returns() {
          return new external_value(PARAM_TEXT, 'Get user Course paper list');
    }


    /////////////////////////////////// Rao Web Services - User-grievance /////////////////////////////////////////

    public static function get_user_grievance_list_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_user_grievance_list() {
        
        $grievance_list = get_grievance_list();
        return json_encode($grievance_list);
    }
   
    
    public static function get_user_grievance_list_returns() {
          return new external_value(PARAM_TEXT, 'Get User-grievance list');
    }


    //////////////////// Rao Web Services - User-grievance Responses ///////////////////////////

    public static function get_user_grievance_response_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_user_grievance_response() {
        
        $grievance_list = get_grievance_list();
        
        $greivance_id = array();
        $greivance_response = array();
        foreach ($grievance_list as $key => $value) {
            $greivance_id[] = $grievance_list[$key]->id;
        }
        
        foreach ($greivance_id as $key => $value) {
            $greivance_response[] = get_greivance_response($value);
        }
        
        return json_encode($greivance_response);
    }
   
    
    public static function get_user_grievance_response_returns() {
          return new external_value(PARAM_TEXT, 'Get User-grievance Responses');
    }

    
}


