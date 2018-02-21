<?php
defined('MOODLE_INTERNAL') || die();

class block_branchadmin extends block_list {

   public function init(){
	  $this->title = get_string('pluginname', 'block_branchadmin');
	}
	public function get_content(){
       
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content =  new stdClass;
        $this->content->items = array();
        $student_view_url = new moodle_url('/blocks/branchadmin/view_students.php', array('blockid' => $this->instance->id));
        $student_attendance = new moodle_url('/blocks/branchadmin/mark_attendance.php', array('blockid' => $this->instance->id));
        $student_birthday = new moodle_url('/blocks/branchadmin/todays_birthday.php', array('blockid' => $this->instance->id));
        $this->content->items[0] = html_writer::link($student_view_url,"View Branch Students");
        $this->content->items[1] = html_writer::link($sms_url,"Send SMS to students");
        $this->content->items[2] = html_writer::link($student_attendance,"Mark Student Absent");
        $this->content->items[3] = html_writer::link($student_birthday,"Today's Birthday at Center");
        return $this->content;
    }


    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return false;}             
                  
}