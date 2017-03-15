<?php
class block_timetable extends block_base {
    private $subj_map = array(
		'p'=>'Physics',
		'c'=>'Chemistry',
		'm'=>'Maths',
		'z'=>'Zoology',
		'b'=>'Botany'
	);

	public function init() {
        $this->title = get_string('timetable', 'block_timetable');
    }

    public function get_content() {
		global $CFG;
	    if ($this->content !== null) {
	        return $this->content;
	    }
        $this->content_type = BLOCK_TYPE_TEXT;
	    $this->content =  new stdClass;
	    $this->content->text   = $this->template();
	    $this->content->footer = "<a href='$CFG->wwwroot/blocks/timetable/view.php'>View Week Timetable</a>".
                                "<link rel='stylesheet' href='$CFG->wwwroot/blocks/timetable/templates/css/block.css'>".
                                "<script src='$CFG->wwwroot/blocks/timetable/templates/js/block.js'>";
	    return $this->content;
	}

    private function template() {
        return '<ul class="timetable-list">'.
                    "<h3> Loading ... </h3>".
                "</ul>".
                "<hr>".
                '<input type="hidden" id="ttusername" value="'.$_SESSION['USER']->username.'">';
        // $url = 'http://192.168.1.161/moodle/timetable.php?id='.$_SESSION['USER']->username; // TODO Remove
    }
}
?>