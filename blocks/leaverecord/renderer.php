<?php

require_once('locallib.php');

class block_leaverecord_renderer extends plugin_renderer_base {
 
    public function render_view_apply_leaves($page){
        $data = array();
        $data['view_leaves'] = $page->export_for_template($this);
        return $this->render_from_template('block_leaverecord/view_leaves', $data);
    }
    public function render_view_attendance_master($page){
        $data = array();
        $data['attendance_data'] = $page->export_for_template($this);
        //echo "<pre>"; print_r($data);
        return $this->render_from_template('block_leaverecord/attendance_master', $data);
    }
    public function render_view_faculty_timetable($page){
	$data = array();
	$data['faculty_timetable'] = $page->export_for_template($this);
	return $this->render_from_template('block_leaverecord/faculty_timetable', $data);
    }
    public function render_faculty_register($page){
        $data = array();
        $data['faculty_register'] = $page->export_for_template($this);
        return $this->render_from_template('block_leaverecord/faculty_register', $data);
    }
}
// View Leaves //
class view_apply_leaves implements renderable, templatable {
    var $email = null;
    var $from_date = null;
    var $to_date = null;
   
    public function __construct($email, $from_date,$to_date){
        $this->email = $email;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }
    private function view_leaves(){
        global $USER, $DB;
        $header = array(
            'Authorization: Token 390aa78baf9ef435ec92427e1fea9323133f5ce9'
        );
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_URL,"http://192.168.1.19:8000/leaves/faculty_leaves/?user=$this->email&from=$this->from_date&to=$this->to_date");
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        $response=curl_exec($curl);
        $data=json_decode($response);
        $count= count($data);
        for ($i=0; $i<$count; $i++){
               $leave_type = $data[$i]->leave_type;
               $status = $data[$i]->status;
            if($leave_type == "F"){
                $data[$i]->leave_type = "Full Day";
            }
            switch ($status) {
                case "0":
                        $data[$i]->status = "Pending Approval";
                        break;
                case "1":
                        $data[$i]->status = "Approved";
                        break;   
                default:
                $data[$i]->status = "Rejected";  
            }
           
        }
        return $data;
            
       
     
}
    public function export_for_template(renderer_base $output){
        $data = $this->view_leaves();
        return $data;
    }
}
// View Leaves //


class view_attendance_master implements renderable, templatable {
    var $attendance_master = null;

    public function __construct($attendance_master){
        $this->attendance_master = $attendance_master;
    }
    private function get_attendance_data(){
  
        return $this->attendance_master;
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_attendance_data();
        return $data;
    }

}

	
// View Faculty Timetable //

class faculty_register implements renderable, templatable {
    private function get_ftimetable(){
      
        }

    public function export_for_template(renderer_base $output){
        $data = $this->get_ftimetable();
        return $data;
    }
}
