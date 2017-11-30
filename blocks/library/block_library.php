<?php
defined('MOODLE_INTERNAL') || die();
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
        if (is_siteadmin()){
            $books_add_url = new moodle_url('/blocks/library/add_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
            $this->content->items[] = html_writer::link($books_add_url,"Add Books");
        }
        $books_issued_url = new moodle_url('/blocks/library/issued_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        $this->content->items[] = html_writer::link($books_issued_url,"Issued Books");
        $books_available_url = new moodle_url('/blocks/library/available_books.php');//, array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        $this->content->items[] = html_writer::link($books_available_url,"Available Books");
        
        return $this->content;

}
     public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return false;}         
}