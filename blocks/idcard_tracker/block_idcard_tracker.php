<?php
defined('MOODLE_INTERNAL') || die();
class block_idcard_tracker extends block_list {
    public function init() {
        $this->title = get_string('pluginname', 'block_idcard_tracker');
    }

    public function get_content() {
     global $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->items = array();
        if (is_siteadmin()){
            $idcard_add_url = new moodle_url('/blocks/idcard_tracker/add_idcard.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($idcard_add_url,"Add Student Id Card");
            $view_idcards = new moodle_url('/blocks/idcard_tracker/view_idcards.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($view_idcards,"All Id cards");
        }
        return $this->content;
}
     public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return false;}         
}