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
 	// my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true, 
                     'course-view-social' => false,
                     'mod' => true, 
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return true;}

    public function cron() {
            mtrace( "Hey, my cron script is running" );
             
  echo "Test Message";
                  
                      return true;
    }
}