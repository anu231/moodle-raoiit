<?php
defined('MOODLE_INTERNAL') || die();
class block_library extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_library');
    }

    public function get_content() {
     global $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $books_view_url = new moodle_url('/blocks/library/add_books.php', array('blockid' => $this->instance->id,'courseid'=>$COURSE->id));
        $this->content->text = html_writer::link($books_view_url,"Add Books");;
        return $this->content;

}
     public function instance_allow_multiple() {
          return false;
    }

    function has_config() {return true;}         
}