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
    
        $this->content         =  new stdClass;
        $this->content->items = array();
        $student_view_url = new moodle_url('/blocks/branchadmin/view_students.php', array('blockid' => $this->instance->id));
        $this->content->items[0] = html_writer::link($student_view_url,"View Branch Students");
        return $this->content;
    }


    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return true;}             
                  
}
