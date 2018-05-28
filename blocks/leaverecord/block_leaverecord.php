<?php
require_once('locallib.php');
class block_leaverecord extends block_list {
    public function init() {
        $this->title = get_string('leaverecord', 'block_leaverecord');
    }

    public function get_content() {
        global $CFG, $PAGE;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content =  new stdClass;
        $this->content->items = array();
        $apply_leave_url = new moodle_url('/blocks/leaverecord/apply_leave.php', array('blockid' => $this->instance->id));
        $view_leaves = new moodle_url('/blocks/leaverecord/view_leaves.php', array('blockid' => $this->instance->id));
        $this->content->items[0] = html_writer::link($apply_leave_url,"Apply for Leave");
        $this->content->items[1] = html_writer::link($view_leaves,"View Leaves");

        return $this->content;
    } 
}
?>
