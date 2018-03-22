<?php
// Standard GPL and phpdocs
 
require_once('../../config.php');
require_once('locallib.php');
require_once("$CFG->dirroot/blocks/library/locallib.php");
require_once("{$CFG->libdir}/raolib.php");

//require_once($CFG->dirroot.'/lib/raolib.php');
//use plugin_renderer_base;  
//require_once('classes/output/view_students.php');
 
class block_branchadmin_renderer extends plugin_renderer_base {
    /**                                                                                                                             
     * Defer to template.                                                                                                           
     *                                                                                                                              
     * @param index_page $page                                                                                                      
     *                                                                                                                              
     * @return string html for the page                                                                                             
     */                                                                                                                             
    public function render_view_students($page) {                                                                                      
        //$data = $page->export_for_template($this);
        $data = array();
        $data['user_list'] = $page->export_for_template($this);

        return $this->render_from_template('block_branchadmin/student_list', $data);                                                         
    }        
             
     public function render_view_student($page) {                                                                                      
        //$data = $page->export_for_template($this);
        $data = $page->export_for_template($this);
        //$data['user_list'] = $page->export_for_template($this);
        return $this->render_from_template('block_branchadmin/student', $data);
    }  

    public function render_view_student_performance($page){
        $data = $page->export_for_template($this);
        return $this->render_from_template('block_branchadmin/student_performance', $data);
    }

    public function render_todays_birthday($page){
        $data = array();
        $data['students_birthdays'] = $page->export_for_template($this);
        return $this->render_from_template('block_branchadmin/todays_birthdays', $data);

    }
    
}



class view_students implements renderable, templatable{

    /*var $userid = null;

    public function __construct($id) {                                                                                        
        $this->userid = $id;                                                                                                
    }*/
    
        /*
    gets users who belong to the same center as the current user
    */
    private function get_students_by_center(){
        global $CFG,$USER,$DB;
        if ($USER->id==null){
            return null;
        }
        $user = new stdClass();
        $user->id = $USER->id;
        profile_load_data($user);
        $sql = 'select * from {user} as u join {user_info_data} as ud ON u.id = ud.userid where ud.data like ?';
        $users_by_center = $DB->get_records_sql($sql,array($user->profile_field_center));
      
        $user_list = array(); 
      
        foreach ($users_by_center as $user){
            $temp = array();
              
            $temp['username'] = $user->username;

            $temp['email'] = $user->email;
            $user_list[] = $temp;
        }
        return $user_list;
    }

    /**                                                                                                                             
     * Export this data so it can be used as the context for a mustache template.                                                   
     *                                                                                                                              
     * @return stdClass                                                                                                             
     */                                                                                                                             
    public function export_for_template(renderer_base $output) {                                                                    
        //get all the students with the same center
        $data = $this->get_students_by_center();
        return $data;                                                                                                               
    }
}

class view_student implements renderable, templatable{

    var $userid = null;

    public function __construct($id) {                                                                                        
        $this->username = $id;                                                                                                
    }
    
        /*
    gets users who belong to the same center as the current user
    */
    private function get_student_info(){
        global $DB, $CFG;
        if ($this->username==null){
            return null;
        }
        //check whether the logged in user and requested user belong to the same centre
        $user_center = get_user_center();
        $user = $DB->get_record('user',array('username'=>$this->username));
        if (!$user){
            return null;
        }
        $req_user_center = get_user_center($user->id);
        if ($user_center==$req_user_center){
            profile_load_data($user);
            //profile_display_fields($user->id);
            $user->profile_field_batch = get_batch_name($user->profile_field_batch);
            $user->profile_field_center = get_center_name($user->profile_field_center);
            $user->performance_link = $CFG->wwwroot.'/blocks/branchadmin/student_performance.php?userid='.$user->username;
            return $user;
        } else {
            $msg = new stdClass();
            $msg->error_message = 'Requested Student does not belong to the centre assigned to you';
            return $msg;
        }
    }

    /**                                                                                                                             
     * Export this data so it can be used as the context for a mustache template.                                                   
     *                                                                                                                              
     * @return stdClass                                                                                                             
     */                                                                                                                             
    public function export_for_template(renderer_base $output) {                                                                    
        //get all the students with the same center
        $data = $this->get_student_info();
        return $data;                                                                                                               
    }
}


class view_student_performance implements renderable, templatable{

    var $userid = null;

    public function __construct($id) {                                                                                        
        $this->username = $id;                                                                                                
    }
    
        /*
    gets users who belong to the same center as the current user
    */
    private function get_student_performance(){
        global $DB, $CFG;
        if ($this->username==null){
            return null;
        }
        //check whether the logged in user and requested user belong to the same centre
        $user_center = get_user_center();
        $user = $DB->get_record('user',array('username'=>$this->username));
        if (!$user){
            return null;
        }
        $req_user_center = get_user_center($user->id);
        if ($user_center==$req_user_center){
            $spr = get_student_full_report($user->username);
            return $spr;
        } else {
            $msg = new stdClass();
            $msg->error_message = 'Requested Student does not belong to the centre assigned to you';
            return $msg;
        }
    }

    /**                                                                                                                             
     * Export this data so it can be used as the context for a mustache template.                                                   
     *                                                                                                                              
     * @return stdClass                                                                                                             
     */                                                                                                                             
    public function export_for_template(renderer_base $output) {                                                                    
        //get all the students with the same center
        $data = $this->get_student_performance();
        return array('spr'=>$data);                                                                                                               
    }
}




class todays_birthday implements renderable, templatable {
    private function get_student_birthdays(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        $current_month = date('m');
        $current_date = date('d');
        //center not set yet //
        $sql = <<<SQL
        select userdata.userid, userdata.fieldid,userdata.data,user.username,user.firstname,user.lastname,user.email
        from {user_info_data} as userdata join {user} as user
        on userdata.userid = user.id
        where userdata.fieldid = ? AND MONTH(userdata.data) = $current_month AND DAY(userdata.data) = $current_date AND user.suspended = 0
SQL;
        $get_records = $DB->get_records_sql($sql,array(1));
        $result = array();
        foreach($get_records as $entry){
            $result[] = $entry;
        }
        //var_dump($result);
        return $result;
     }

    public function export_for_template(renderer_base $output){
        $data = $this->get_student_birthdays();
        return $data;
    }
}
