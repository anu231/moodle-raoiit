<?php
defined('MOODLE_INTERNAL') || die();
require_once('duration_form.php');

class block_attendance extends block_base {

   public function init(){
	  $this->title = get_string('pluginname', 'block_attendance');
	}
	public function get_content(){
        global $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
    
        $this->content = new stdClass;
        $attendance_view_url = new moodle_url('/blocks/attendance/view_biometric.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        $this->content->text = html_writer::link($attendance_view_url,"View Biometric Records");;
        return $this->content;
    }


    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return true;}             
                  
}