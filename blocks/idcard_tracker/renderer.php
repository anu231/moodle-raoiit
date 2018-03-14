<?php
defined('MOODLE_INTERNAL') || die;
require_once('../../config.php');
require_once('../branchadmin/locallib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->libdir.'/filelib.php');
//require_once('locallib.php');

class block_idcard_tracker_renderer extends plugin_renderer_base {
    
    public function render_view_pending_idcards($page){
        $data = array();
        $data['view_idcards'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_pending_idcards', $data);
    }
    public function render_view_student_idcard($page){
        $data = array();
        $data['student_idcard'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_student_idcard', $data);
    }
}

class view_pending_idcards implements renderable, templatable {
    
    private function get_all_idcards(){
        global $USER, $DB,$PAGE;
       //$center_id = get_user_center($USER->id);
       // center not assigned yet
       $idcards = $DB->get_records('student_idcard_submit', array('idcard_status'=>0));
       $idcards_array = array();
        foreach($idcards as $idcard){
            $user = $DB->get_record('user', array('username'=>$idcard->student_username));
            $user_picture = new user_picture($user);
            $user_picture->size = 200;
            $idcard->src = $user_picture->get_url($PAGE);
            $idcards_array[] = $idcard;
        }
        return $idcards_array;
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_all_idcards();
        return $data;
    }
}

class view_student_idcard implements renderable, templatable {
    
    private function get_student_idcard(){
        global $USER, $DB;
      
        //$id = optional_param('idcard_id', array(),PARAM_INT);
       
        if (isset($id)) {
            $idcards = $DB->get_record('student_idcard_submit', array('id'=>$id));
            return $idcards;
        }
        
        else {
            $new_id = array('1','2','3','4');
            
            foreach($new_id as $value){

                for($i=0; $i<count($new_id);$i++){
                    $idcards[] = $DB->get_record('student_idcard_submit', array('id'=>$new_id[$i]));
                
                }
                return $idcards;
            }

        }
       
       
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_student_idcard();
        return $data;
    }
}