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
        $apply_od = new moodle_url('/blocks/leaverecord/apply_od.php', array('blockid' => $this->instance->id));
        $view_leaves_form = new moodle_url('/blocks/leaverecord/view_leave_form.php', array('blockid' => $this->instance->id));
        $attendance_master = new moodle_url('/blocks/leaverecord/attendance_master_form.php', array('blockid' => $this->instance->id));
        $faculty_register = new moodle_url('/blocks/leaverecord/faculty_register.php', array('blockid' => $this->instance->id));
        $faculty_timetable = new moodle_url('/blocks/leaverecord/faculty_timetable.php', array('blockid' => $this->instance->id));
        $this->content->items[0] = html_writer::link($apply_leave_url,"Apply for Leave");
        $this->content->items[1] = html_writer::link($apply_od,"Apply for OD");
        $this->content->items[2] = html_writer::link($view_leaves_form,"View Leaves");
        $this->content->items[3] = html_writer::link($attendance_master,"Attendance Master");
        $this->content->items[4] = html_writer::link($faculty_register,"Faculty Register");
        $this->content->items[5] = html_writer::link($faculty_timetable,"Faculty Timetable");
        return $this->content;
    } 
}
?>
