<?php
require_once('locallib.php');
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
		global $CFG, $PAGE;
	    if ($this->content !== null) {
	        return $this->content;
	    }
        $this->content_type = BLOCK_TYPE_TEXT;
	    $this->content =  new stdClass;
	    $this->content->text   = $this->render_today_timetable();
	    $this->content->footer = "<a href='$CFG->wwwroot/blocks/timetable/view.php'>View Week Timetable</a>".
                                "<link rel='stylesheet' href='$CFG->wwwroot/blocks/timetable/templates/css/block.css'>";
        $this->content->footer .= "<br><a href='$CFG->wwwroot/blocks/timetable/batch_report.php'>View Batch Report</a>";
		//$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/blocks/timetable/templates/js/ttblock.js') );
	    return $this->content;
	}
    
    private function render_today_timetable(){
        global $USER;
        $html = '<ul class="timetable-list">';
        $date_today = date('Y-m-d');
        $today_lectures = get_timetable($date_today,$date_today, $USER->username);
        if (count($today_lectures)==0){
            return '<b>No Lectures Today</b>';
        }
        foreach($today_lectures[0]['items'] as $lecture){
            $lecture_content = isset($lecture['topicname'])?$lecture['topicname']:$lecture['notes'];
            $html .= '<li class="lecture-item">'.
                    '<div class="header">'.
                        '<div class="time">'.$lecture['starttime']."-". $lecture['endtime'].'</div>'.
                        '<div class="subject"><span class="label "'.$lecture['subject'].'">'.$lecture['subject'].'</span></div>'.
                    '</div>'.
                    '<div class="topic">'.$lecture_content.'</div>'.
                    '<div class="teacher">- '.$lecture['teacher'].'</div>'.
                '</li>'; 
        }
        $html .='</ul>';
        return $html;
    }

    private function template() {
		global $CFG;
        return '<ul class="timetable-list">'.
                    "<h3> Loading ... </h3>".
                "</ul>".
                "<hr>".
                '<input type="hidden" id="tturl" value="'.$CFG->timetable_url.$_SESSION['USER']->username.'">';
        // $url = 'http://192.168.1.161/moodle/timetable.php?id='.$_SESSION['USER']->username; // TODO Remove
    }
}
?>
