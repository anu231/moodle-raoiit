<?php

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once("$CFG->dirroot/local/raowebservice/locallib.php");
require_once("$CFG->dirroot/blocks/readytohelp/locallib.php");
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
    //NOT DONE
    public static function grievance_metadata_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function grievance_metadata() {        
        $deps = get_grievance_departments();
        $cats = get_grievance_categories();
        $metadata = array('departments'=>array(),'categories'=>array());
        foreach($deps as $k=>$v){
            array_push($metadata['departments'], array('id'=>$k, 'name'=>$v));
        }
        foreach($cats as $k=>$v){
            array_push($metadata['categories'], array('id'=>$k, 'name'=>$v));
        }
        return $metadata;
    }
   
    
    public static function grievance_metadata_returns() {
          return new external_single_structure(
              array(
                'departments' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Department ID'),
                            'name' => new external_value(PARAM_TEXT, 'Department Name')
                        )
                    )
                ),
                'categories' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Department ID'),
                            'name' => new external_value(PARAM_TEXT, 'Department Name')
                        )
                    )
                )
              )
          );
    }
    //DONE
    public static function get_user_grievance_list_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_user_grievance_list() {        
        $grievance_list = get_grievance_list();
        $deps = get_grievance_departments();
        $cats = get_grievance_categories();
        $grievances = array();
        foreach($grievance_list as $g){
            $dep_names = array();
            foreach(explode(',',$g->department) as $d){
                array_push($dep_names,$deps[$d]);
            }
            array_push($grievances, array(
                'id' => $g->id,
                'username' => $g->username,
                'created' => $g->timecreated,
                'category' => $cats[$g->category],
                'department' => implode(',',$dep_names),
                'subject' => $g->subject,
                'description' => $g->description,
                'status' => $g->status
            ));
        }
        return $grievances;
    }
   
    
    public static function get_user_grievance_list_returns() {
          return new external_multiple_structure(
              new external_single_structure(
                  array(
                      'id' => new external_value(PARAM_INT,'Grievance id'),
                      'username' => new external_value(PARAM_TEXT, 'Username'),
                      'created' => new external_value(PARAM_TEXT, 'Creation Time'),
                      'category' => new external_value(PARAM_TEXT,'Grievance Category'),
                      'department' => new external_value(PARAM_TEXT,'Departments'),
                      'subject' => new external_value(PARAM_TEXT,'Subject'),
                      'description' => new external_value(PARAM_TEXT,'Description'),
                      'status' => new external_value(PARAM_TEXT,'Status')
                  )
              )
          );
    }
    //DONE
    public static function user_grievance_post_parameters() {
        return new external_function_parameters(
            array(
                'category' => new external_value(PARAM_INT, 'Category'),
                'department' => new external_value(PARAM_INT, 'Department'),
                'subject' => new external_value(PARAM_TEXT, 'Subject'),
                'description' => new external_value(PARAM_TEXT, 'Description')
            )
        );
    }

    public static function user_grievance_post($category, $department, $subject, $description) {        
        $params = self::validate_parameters(self::user_grievance_post_parameters(),
                array('category' => $category, 'department' => $department, 'subject'=> $subject, 'description' => $description));
        global $USER, $DB, $CFG;
        $g_db = new stdClass();
        $g_db->category = $category;
        $g_db->department = $department;
        $g_db->subject = $subject;
        $g_db->description = $description;
        $g_db->username = $USER->username;
        $g_db->timecreated = time();
        $g_db->status = 'open';
        $grievance_id = $DB->insert_record('grievance_entries', $g_db,true);
        $grievance = array(
            'category' => $category,
            'department' => $department,
            'subject' => $subject,
            'description' => $description,
            'username' => $USER->username,
            'timecreated' => time(),
            'status' => 'open',
            'id' => $grievance_id
            );
        send_grievance_notification_admin($grievance_id);
        return array($grievance);
    }
   
    
    public static function user_grievance_post_returns() {
          return new external_multiple_structure(
              new external_single_structure(
                  array(
                      'id' => new external_value(PARAM_INT,'Grievance id'),
                      'username' => new external_value(PARAM_TEXT, 'Username'),
                      'timecreated' => new external_value(PARAM_TEXT, 'Creation Time'),
                      'category' => new external_value(PARAM_TEXT,'Grievance Category'),
                      'department' => new external_value(PARAM_TEXT,'Departments'),
                      'subject' => new external_value(PARAM_TEXT,'Subject'),
                      'description' => new external_value(PARAM_TEXT,'Description'),
                      'status' => new external_value(PARAM_TEXT,'Status')
                  )
              )
          );
    }


    //////////////////// Rao Web Services - User-grievance Responses ///////////////////////////
    //DONE
    public static function get_user_grievance_response_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT,'Grievance ID')
            )
        );
    }

    public static function get_user_grievance_response($id) {
        global $DB;
        // Validate parameters.
        $params = self::validate_parameters(self::get_user_grievance_response_parameters(),
                array('id' => $id));
        $resp = get_grievance_responses($params['id']);
        $responses = array();
        foreach($resp as $r){
            array_push($responses, array(
                'id' => $r->id,
                'time' => $r->timecreated,
                'body' => $r->body
            ));
        }
        return $responses;
    }
   
    
    public static function get_user_grievance_response_returns() {
          return new external_multiple_structure(
              new external_single_structure(
                  array(
                      'id' => new external_value(PARAM_INT,'Response id'),
                      'time' => new external_value(PARAM_TEXT, 'time'),
                      'body' => new external_value(PARAM_TEXT, 'Reply')
                  )
              )
          );
    }
    //DONE
    public static function post_user_grievance_reply_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT,'Grievance ID'),
                'body' => new external_value(PARAM_TEXT,'Reply')
            )
        );
    }

    public static function post_user_grievance_reply($id, $body) {
        global $DB;
        // Validate parameters.
        $params = self::validate_parameters(self::post_user_grievance_reply_parameters(),
                array('id' => $id, 'body' => $body));
        $resp = post_grievance_reply($id, $body, -1, 1);
        $ret = array();
        if ($resp == NULL){
            $ret['detail'] = 'fail';
            $ret['resp'] = array();
        } else{
            $ret['detail'] = 'success';
            $ret['resp'] = array(array(
                'id'=>$resp->id,
                'time'=>$resp->timecreated,
                'body'=>$resp->body)
            );
        }
        return $ret;
    }
   
    
    public static function post_user_grievance_reply_returns() {
          return new external_single_structure(
              array(
                  'detail' => new external_value(PARAM_TEXT,'status'),
                  'resp' => new external_multiple_structure(
                        new external_single_structure(
                            array(
                            'id' => new external_value(PARAM_INT,'Response id'),
                            'time' => new external_value(PARAM_TEXT, 'time'),
                            'body' => new external_value(PARAM_TEXT, 'Reply')
                        ),'created reply',VALUE_OPTIONAL)
                  )
              )
          );
    }
    ///////////////////////////////////ATTENDANCE//////////////////////////////////
    public static function biometric_attendance_parameters() {
        return new external_function_parameters(
            array(
                'sdate' => new external_value(PARAM_TEXT,'Start Date'),
                'edate' => new external_value(PARAM_TEXT,'End Date')
            )
        );
    }

    public static function biometric_attendance($sdate, $edate) {
        global $CFG;
        // Validate parameters.
        $params = self::validate_parameters(self::biometric_attendance_parameters(),
                array('sdate' => $id, 'edate' => $body));
        require_once("$CFG->dirroot/blocks/attendance/locallib.php");
        $bio_data = get_attendance_records($sdate, $edate, 'self');
        $ret = array();
        foreach($bio_data as $d){
            array_push($ret, array('time'=>$d['logtime'], 'node'=>$d['nodename']));
        }
        return $ret;
    }
   
    
    public static function biometric_attendance_returns() {
          return new external_multiple_structure(
              new external_single_structure(
                  array(
                    'time' => new external_value(PARAM_TEXT, 'time'),
                    'node' => new external_value(PARAM_TEXT, 'node')
                  )
              )
          );
    }
    ///////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////Get Student Timetable///////////////////////////////////////////
    public static function get_user_timetable_parameters(){
        return new external_function_parameters(array());
    }

    public static function get_user_timetable(){
        global $CFG;
        require_once("$CFG->dirroot/blocks/timetable/locallib.php");
        return get_week_timetable();
    }

    public static function get_user_timetable_returns(){
        return new external_function_parameters(
            array(
                new external_single_structure(
                    array(
                        //'day' => new external_value(PARAM_TEXT, 'day'),
                        'items' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'sid' => new external_value(PARAM_INT, 'schedule id'),
                                    'fancydate' => new external_value(PARAM_TEXT, 'Fancy Date'),
                                    'starttime'  => new external_value(PARAM_TEXT, 'start time'),
                                    'endtime'  => new external_value(PARAM_TEXT, 'end time'),
                                    'istest'  => new external_value(PARAM_INT, 'Is Test'),
                                    'teacher'  => new external_value(PARAM_TEXT, 'Teacher'),
                                    'subject'  => new external_value(PARAM_TEXT, 'Subject'),
                                    'topicname'  => new external_value(PARAM_TEXT, 'Topic'),
                                    'notes'  => new external_value(PARAM_TEXT, 'Notes'),
                                    'cancelclass' => new external_value(PARAM_TEXT, 'Cancelled'),
                                    'batch' => new external_value(PARAM_TEXT, 'Batch')
                                )
                            )
                        )
                    )
                )
            )
        );
    }

    public static function topic_completion_report_parameters(){
        return new external_function_parameters(array());
    }

    public static function topic_completion_report(){
        global $CFG, $USER;
        require_once("$CFG->dirroot/blocks/timetable/locallib.php");
        return get_completed_topics($USER->username);
    }

    public static function topic_completion_report_returns(){
        return new external_multiple_structure(
                new external_single_structure(
                    array(
                        'subject' => new external_value(PARAM_TEXT, 'subject'),
                        'items' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'name'=> new external_value(PARAM_TEXT, 'Topic')
                                )
                            )
                        )
                    )
                )
            );
    }

    public static function ptm_records_parameters(){
        return new external_function_parameters(array());
    }

    public static function ptm_records(){
        global $CFG, $USER;
        require_once("$CFG->dirroot/blocks/timetable/locallib.php");
        return get_ptm_records($USER->username);
    }

    public static function ptm_records_returns(){
        return new external_multiple_structure(
                new external_single_structure(
                    array(
                        'date' => new external_value(PARAM_TEXT, 'Date'),
                        'event' => new external_value(PARAM_TEXT, 'Event')
                    )
                )
            );
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////BOOKLET///////////////////////////////////////////
    public static function booklet_info_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT,' Booklet ID')
            )
        );
    }

    public static function booklet_info($id){
        global $CFG;
        require_once("$CFG->dirroot/mod/raobooklet/locallib.php");
        $params = self::validate_parameters(self::booklet_info_parameters(),
                array('id' => $id));
        $binfo = get_booklet_info();
        $t = time();
        $binfo['access_token'] = md5($CFG->custom_salt.$id.$t);
        $binfo['timestamp'] = $t;
        $binfo['id'] = $id;
        return $binfo;
    }

    public static function booklet_info_returns(){
        return new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Booklet ID'),
                        'status' => new external_value(PARAM_TEXT, 'Status'),
                        'page_cnt' => new external_value(PARAM_INT, 'Page Count'),
                        'access_token' => new external_value(PARAM_TEXT, 'Access Token'),
                        'timestamp' => new external_value(PARAM_INT, 'Timestamp'),
                    )
                );
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    
    //////////////////////////////////////////////LIBRARY/////////////////////////////////////////////////////
    public static function library_books_available_parameters(){
        return new external_function_parameters(
            array()
        );
    }

    public static function library_books_available(){
        global $CFG;
        require_once("$CFG->dirroot/blocks/library/locallib.php");
        $books = get_centers_book();
        $response = array();
        foreach($books as $book){
            array_push($response, array(
                'id' => $book->id,
                'bookid' => $book->bookid,
                'name' => $book->name
            ));
        }
        return $response;
    }

    public static function library_books_available_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'bookid' => new external_value(PARAM_TEXT, 'Book ID'),
                        'name' => new external_value(PARAM_TEXT, 'Name')
                    )
            )
        );
    }
    public static function library_books_history_parameters(){
        return new external_function_parameters(
            array()
        );
    }

    public static function library_books_history(){
        global $CFG;
        require_once("$CFG->dirroot/blocks/library/locallib.php");
        $books = get_issue_history();
        $response = array();
        foreach($books as $book){
            array_push($response, array(
                'id' => $book->id,
                'bookid' => $book->bookid,
                'name' => $book->name,
                'subject' => $book->subject,
                'issue_date' => $book->issue_date,
                'return_date' => $book->return_date,
            ));
        }
        return $response;
    }

    public static function library_books_history_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'bookid' => new external_value(PARAM_TEXT, 'Book ID'),
                        'name' => new external_value(PARAM_TEXT, 'Name'),
                        'subject' => new external_value(PARAM_TEXT, 'Subject'),
                        'issue_date' => new external_value(PARAM_TEXT, 'issue date'),
                        'return_date' => new external_value(PARAM_TEXT, 'return date')
                    )
            )
        );
    }
    public static function library_books_issued_parameters(){
        return new external_function_parameters(
            array()
        );
    }

    public static function library_books_issued(){
        global $CFG;
        require_once("$CFG->dirroot/blocks/library/locallib.php");
        $books = get_currently_issued_books();
        $response = array();
        foreach($books as $book){
            array_push($response, array(
                'id' => $book->id,
                'bookid' => $book->bookid,
                'name' => $book->name,
                'subject' => $book->subject,
                'issue_date' => $book->issue_date,
                'return_date' => $book->return_date,
            ));
        }
        return $response;
    }

    public static function library_books_issued_returns(){
        return new external_multiple_structure(
            new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'bookid' => new external_value(PARAM_TEXT, 'Book ID'),
                        'name' => new external_value(PARAM_TEXT, 'Name'),
                        'subject' => new external_value(PARAM_TEXT, 'Subject'),
                        'issue_date' => new external_value(PARAM_TEXT, 'issue date'),
                        'return_date' => new external_value(PARAM_TEXT, 'return date')
                    )
            )
        );
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function get_testing_token_parameters(){
        return new external_function_parameters(
            array()
        );
    }

    public static function get_testing_token(){
        global $CFG;
        require_once("$CFG->dirroot/mod/paper/locallib.php");
        $response = array();
        $token = get_testing_portal_token();
        if ($token == -1){
            $response['status'] = 'Failed';
        } else {
            $response['status'] = 'Success';
        }
        $response['token'] = $token;
        return $response;
    }

    public static function get_testing_token_returns(){
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Status'),
                'token' => new external_value(PARAM_TEXT, 'Token')
            )
        );
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function get_akamai_video_url_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT,' Video ID')
            )
        );
    }

    public static function get_akamai_video_url($id){
        global $CFG;
        require_once("$CFG->dirroot/mod/raovideo/locallib.php");
        $params = self::validate_parameters(self::get_akamai_video_url_parameters(),
                array('id' => $id));
        $response = array('url'=>get_video_akamai($id));
        return $response;
    }

    public static function get_akamai_video_url_returns(){
        return new external_single_structure(
            array(
                'url' => new external_value(PARAM_TEXT, 'URL')
            )
        );
    }
}


