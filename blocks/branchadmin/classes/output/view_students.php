<?php

require_once('../../../config.php');

class view_students implements renderable, templatable{

    /*var $userid = null;

    public function __construct($id) {                                                                                        
        $this->userid = $id;                                                                                                
    }*/
    
        /*
    gets users who belong to the same center as the current user
    */
    private function get_students_by_center(){
        global $CFG,$USER;
        if ($USER->id==null){
            return null;
        }
        profile_load_data($USER);
        $sql = 'select * from {user} as u join {user_info_data} as ud where ud.data like ?';
        $users_by_center = $DB->get_records_sql($sql,array($USER->profile_field_center));
        return $users_by_center;
    }

    /**                                                                                                                             
     * Export this data so it can be used as the context for a mustache template.                                                   
     *                                                                                                                              
     * @return stdClass                                                                                                             
     */                                                                                                                             
    public function export_for_template(renderer_base $output) {                                                                    
        //get all the students with the same center
        global $USER, $DB;
        $data = $this->get_students_by_center();
        return $data;                                                                                                               
    }
}