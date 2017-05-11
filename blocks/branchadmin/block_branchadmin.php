<?php
defined('MOODLE_INTERNAL') || die();

class block_branchadmin extends block_base {

   public function init(){
	  $this->title = get_string('pluginname', 'block_branchadmin');
	}
	public function get_content(){
require_once($CFG->libdir . '/pagelib.php');
global $PAGE;

		if ($this->content !== null) {
      return $this->content;
    }
 
    $this->content         =  new stdClass;
    
    $this->content->text   = 'Link 1<br/>Link 2<br/>Link 3<br/>';
    $this->content->footer = 'This is a Footer of Branch Administrator';
$this->page->require->requiresjs('/blocks/mynewblock/javascript/myscript.js.');


   
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