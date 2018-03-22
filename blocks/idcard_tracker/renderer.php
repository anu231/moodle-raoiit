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
    public function render_view_single_idcard($page){
        $data = array();
        $data['view_single_idcard'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_single_idcard', $data);
    }
    public function render_view_multiple_idcards($page){
        $data = array();
        $data['view_multiple_idcards'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_multiple_idcards', $data);
    }
    public function render_view_sent_idcards($page){
        $data = array();
        $data['view_sent_idcards'] = $page->export_for_template($this);
        return $this->render_from_template('block_idcard_tracker/view_sent_idcards', $data);
    }
}

class view_pending_idcards implements renderable, templatable {
    
    var $page = null;
    var $perpage = null;
   
    public function __construct($page, $perpage){
        $this->page = $page;
        $this->perpage = $perpage;
    }
   

    private function get_all_idcards(){
        global $USER, $DB,$PAGE;
       /*$idcards = $DB->get_records('student_idcard_submit', array('idcard_status'=>0),'','*',
       ($this->page)*$this->perpage, $this->perpage);*/
       $idcards = $DB->get_records('student_idcard_submit', array('idcard_status'=>0),'id','id,student_username',($this->page)*$this->perpage, $this->perpage);
       $idcards_array = array();
        foreach($idcards as $idcard){
            $user = $DB->get_record('user', array('username'=>$idcard->student_username));
            $user_picture = new user_picture($user);
            $user_picture->size = 400;
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
    var $idcard_id = null;

    public function __construct($idcard_id){
        $this->idcard_id = $idcard_id;
    }
    private function get_student_idcard(){
        global $USER, $DB,$PAGE;
        $student_idcard = $DB->get_record('student_idcard_submit', array('id'=>$this->idcard_id,'idcard_status'=>0));
        $user = $DB->get_record('user', array('username'=>$student_idcard->student_username));
        $user_picture = new user_picture($user);
        $user_picture->size = 300;
        $student_idcard->src = $user_picture->get_url($PAGE);
        return $student_idcard;
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_student_idcard();
        return $data;
    }
}

class view_single_idcard implements renderable, templatable {

    var $single_id = null;
    
    public function __construct($single_id){
        $this->single_id = $single_id;
    }

    private function single_idcard(){
       global $USER, $DB,$PAGE;
       $idcard = $DB->get_record('student_idcard_submit', array('student_username'=>$this->single_id,'idcard_status'=>1));
       $user = $DB->get_record('user', array('username'=>$idcard->student_username));
       $user_picture = new user_picture($user);
       $user_picture->size = 300;
       $idcard->src = $user_picture->get_url($PAGE);
       //$idcard->idcard_status=2;
       //$DB->update_record('student_idcard_submit', $idcard);
       return $idcard;
}
    public function export_for_template(renderer_base $output){
        $data = $this->single_idcard();
        return $data;
    }
}

// for multiple branch //

class view_multiple_idcards implements renderable, templatable {

    var $multiple_id = null;
    
    public function __construct($multiple_id){
        $this->multiple_id = $multiple_id;
    }

    private function multiple_idcard(){
       global $USER, $DB,$PAGE;
       $idcards_data = $DB->get_records('student_idcard_submit', array('branch'=>$this->multiple_id,'idcard_status'=>1));
       $idcards_array = array();
       foreach($idcards_data as $idcard){
           $user = $DB->get_record('user', array('username'=>$idcard->student_username));
           $idcard_new = $idcard->id;
           $user_picture = new user_picture($user);
           $user_picture->size = 200;
           $idcard->src = $user_picture->get_url($PAGE);
           $idcards_array[] = $idcard;
           $idcard->idcard_status=2;
           $DB->update_record('student_idcard_submit', $idcard);
       }
   
    return $idcards_array;
}
    public function export_for_template(renderer_base $output){
        $data = $this->multiple_idcard();
        return $data;
    }
}

class view_sent_idcards implements renderable, templatable {

    var $view_id = null;
    
    public function __construct($view_id){
        $this->view_id = $view_id;
    }

    private function view_idcards(){
       global $USER, $DB,$PAGE;
       $idcards_data = $DB->get_records('student_idcard_submit', array('branch'=>$this->view_id,'idcard_status'=>2));
       $rowcount     = $DB->count_records('student_idcard_submit', array('branch'=>$this->view_id,'idcard_status'=>2));
       $idcards_array = array();
       foreach($idcards_data as $idcard){
           $user = $DB->get_record('user', array('username'=>$idcard->student_username));
           $idcard_new = $idcard->id;
           $user_picture = new user_picture($user);
           $user_picture->size = 200;
           $idcard->src = $user_picture->get_url($PAGE);
           $idcard->idcard_count = $rowcount;
           $idcards_array[] = $idcard;
       }
        
    return $idcards_array;

}
private function get_total_count(){
    global $USER, $DB;
    $result = $DB->get_records_sql("SELECT count(idcard.id) AS Total,branchadmin.name AS branchname FROM {student_idcard_submit} AS idcard JOIN {branchadmin_centre_info} AS branchadmin ON idcard.branch = branchadmin.id WHERE branch=".$this->view_id." AND idcard_status = 2");

    $record_array = array();
    foreach($result as $value){
        $record_array[] = $value;
    }
    return $record_array;
}
    public function export_for_template(renderer_base $output){
        $record1 = $this->view_idcards();
        $record2 = $this->get_total_count();
        $data = array_merge($record1,$record2);
        return $data;
    }
}