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
        if (is_branch_admin()){
            $idcard_add_url = new moodle_url('/blocks/idcard_tracker/add_idcard.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($idcard_add_url,"Add Student Id Card");
            $view_idcards = new moodle_url('/blocks/idcard_tracker/view_pending_idcards.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($view_idcards,"All Pending Id cards");
            $single_idcard = new moodle_url('/blocks/idcard_tracker/single_idcard.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($single_idcard,"Print Perticular Student ID Card");
            $multiple_idcards = new moodle_url('/blocks/idcard_tracker/multiple_idcards.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($multiple_idcards,"Print Branchwise ID Cards");
            $sent_idcards = new moodle_url('/blocks/idcard_tracker/sent_idcard.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($sent_idcards,"Print ID Cards Count");
        }
        return $this->content;
}
     public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return false;}         
}