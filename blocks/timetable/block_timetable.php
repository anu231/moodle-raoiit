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
		global $CFG;
	    if ($this->content !== null) {
	        return $this->content;
	    }
        $this->content_type = BLOCK_TYPE_TEXT;
	    $this->content =  new stdClass;
		$timetable = get_timetable();
	    $this->content->text   = $this->to_html($timetable);
	    $this->content->footer = "<a href='$CFG->wwwroot/blocks/timetable/view.php'>View Week Timetable</a>";
	    return $this->content;
	}

    private function to_html($timetable) {
        // Convert timetable json to HTML
        global $CFG;
        $today = date('l, j-M'); // Used as a key. Do not change!
		if (!$timetable){
			return "Time Table For <b>$today</b> Is Not Available Right Now.
            <br> Please Check Again Later.";
		}
        $html = '<ul class="timetable-list">';
            foreach($timetable[$today] as &$lecture){
                $html .= $this->format_lecture($lecture);
            }
        $html .= "</ul>";
        $html .= "<hr>";
        $html .= "<link rel='stylesheet' href='$CFG->wwwroot/blocks/timetable/templates/css/block.css'>";
        return $html;
HTML;
    }

    private function format_lecture($lecture) {
        // Returns html for a single lecture
        // Standard Stuff
        $starttime = $lecture['sh'].':'.$lecture['sm'];
        $endtime = $lecture['eh'].':'.$lecture['em'];
        $teacher = $lecture['sn'];
        $subject = $this->subj_map[$lecture['subj']];
        $topicname = $lecture['ton'];
        $topicnumber = $lecture['ln'];
        // Contextual Classes
        $testclass = $lecture['t']=='1' ? 'test' : '';
        $cancelclass = $lecture['del']=='1' ? 'cancelled' : '';

        $template = <<<HTML
<li>
    <div class="lecture-item">
    <div class="time">$starttime - $endtime</div>
    <div class="subject"><span class="label $subject">$subject</span></div>
    <div class="topic">$topicname</div>
    <div class="teacher">- $teacher</div>
    </div>
</li>
HTML;
        return $template;
    }
}
?>