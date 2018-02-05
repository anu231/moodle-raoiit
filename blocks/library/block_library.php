<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->root_dir.'blocks/library/locallib.php');
class block_library extends block_list {
    public function init() {
        $this->title = get_string('pluginname', 'block_library');
    }
    public function get_content() {
     global $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->items = array();
        //$course_active = get_config('library','manager_course');
        if (is_siteadmin()){
            $books_add_url = new moodle_url('/blocks/library/add_barcode.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($books_add_url,"Add Barcode");
            $books_add_url = new moodle_url('/blocks/library/add_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($books_add_url,"Add Books");
            $total_fine_url = new moodle_url('/blocks/library/view_total_fine.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($total_fine_url,"Pending Fine yet not submitted");
            $view_all_rao_branch_fine = new moodle_url('/blocks/library/view_all_branch_fine.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($view_all_rao_branch_fine,"All Submitted(HO) Rao Branch Fine");
            
        }
        if (is_branch_admin()){
            $library_issue_url = new moodle_url('/blocks/library/library_issue.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($library_issue_url,"Issue book to student");

            //$pay_fine_url = new moodle_url('/blocks/library/student_fine.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            //$this->content->items[] = html_writer::link($pay_fine_url,"Click to pay book fine");

            $books_fine_url = new moodle_url('/blocks/library/pending_fine.php');//, array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($books_fine_url,"Pending Books Fine");

            $paid_fine_url = new moodle_url('/blocks/library/paid_fine.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($paid_fine_url,"All Paid Fine");

            $books_lost_url = new moodle_url('/blocks/library/lost_update.php');//, array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($books_lost_url,"All lost Books");
        }

        $books_available_url = new moodle_url('/blocks/library/available_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        $this->content->items[] = html_writer::link($books_available_url,"Available Books");
        
        //$books_issued_url = new moodle_url('/blocks/library/issued_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        //$this->content->items[] = html_writer::link($books_issued_url,"Issued Books");
        
        return $this->content;
        

}
     public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return false;}         
}