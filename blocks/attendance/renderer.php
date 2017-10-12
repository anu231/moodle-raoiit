<?php
// Standard GPL and phpdocs
 
defined('MOODLE_INTERNAL') || die;                                                                                                  
require_once('../../config.php');
require_once('locallib.php');
//use renderable;                                                                                                                     
//use renderer_base;                                                                                                                  
//use templatable;                                                                                                                    
//use stdClass;   

class block_attendance_renderer extends plugin_renderer_base {

    //public function render_biometric_records($)
    public function render_biometric_page($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_attendance/biometric_page', $data);
    }
}

class biometric_page implements renderable, templatable {
    var $records = null;
    public function __construct($records){
        $this->records = $records;
    }
    public function export_for_template(renderer_base $output){
        $data = new stdClass();
        $data->records = $this->records;
        return $data;
    }
}