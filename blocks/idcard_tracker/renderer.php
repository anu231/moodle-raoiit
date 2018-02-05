<?php
defined('MOODLE_INTERNAL') || die;
require_once('../../config.php');
require_once('../branchadmin/locallib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->libdir.'/filelib.php');
//require_once('locallib.php');

class block_idcard_tracker_renderer extends plugin_renderer_base {
    
    public function render_view_idcards($page){
        $data = array();
        $data['view_idcards'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_idcards', $data);
    }
    public function render_view_student_idcard($page){
        $data = array();
        $data['student_idcard'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_student_idcard', $data);
    }
}

class view_idcards implements renderable, templatable {
    
    private function get_all_idcards(){
        global $USER, $DB,$PAGE;
       //$center_id = get_user_center($USER->id);
       // center not assigned yet
       $idcards = $DB->get_records('student_idcard_submit', array('idcard_status'=>1));
       $idcards_array = array();
        foreach($idcards as $idcard){
            $idcards_array[] = $idcard;
          $count_id = count($idcard->student_username);
            for ($i=0;$i<$count_id;$i++){
                $user = $DB->get_record('user',array('username'=>$idcard->student_username));
                profile_load_data($user);
                $user_picture = new user_picture($user);
                $user_picture->size = true;
                $src = $user_picture->get_url($PAGE);
               // echo "<img src='$src' />";
            }
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
      
        $id = required_param('idcard_id',PARAM_INT);
        if (!empty($id)) {
                echo "Id Found";
        }
        else {
            echo "Multiple id found";
        }
        // $new_id = required_param('idcard_id',PARAM_INT);
        $idcards = $DB->get_record('student_idcard_submit', array('id'=>$id));
        return $idcards;
       
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_student_idcard();
        return $data;
    }
}
