<?php
// paper block will display papers and their actions

class block_paper extends block_base {
	public function init() {
		$this->title = "Available Papers";
	}
	
	public function get_content() {
		global $COURSE, $CFG, $USER;
		if( $this->content != null ){
			return $this->content;
		}
		
		$this->content = new stdClass;
		$this->content->text =  $this->getPapersForThisCourse();
		$this->content->footer = "<br><a class='btn btn-primary' href='$CFG->wwwroot/blocks/paper/view_all.php?cid=$COURSE->id'>View all</a>";
		// 		Show assign paper button to mods
		        if (has_capability('block/paper:assignpaper',context_course::instance($COURSE->id))){
			$this->content->footer .= "<br><a".
			            " href='$CFG->wwwroot/blocks/paper/add_paper.php?courseid=$COURSE->id'>Assign Paper's to this course</a>";
		}
		
		return $this->content;
	}
	
	
	/**
	* Returns an array of links to papers.
	    * @param bool $gmode - Display link to edit the paper TODO
	    * @return: array(id => paperlink)
	    */
	    private function getPapersForThisCourse($gmode=FALSE){
		// 		Fetch papers for this course from the db
		        global $DB, $COURSE, $CFG;
		$courseid = $COURSE->id;
		if($courseid == 1){
			// 			Return empty string for home page
			            return 'Please select a course to view its papers, or click below to view all papers';
		}
		$SQL = "SELECT * FROM mdl_block_paper WHERE courseid = $courseid  LIMIT 5 ";
		$papers = $DB->get_records_sql($SQL);
		if(!$papers){
			return 'No papers assigned to this course.';
		}
		$formattedpapers = "<ul class='paper-list'>";
		foreach ($papers as $paper) {
			$formattedpapers .= $this->to_html($paper);
		}
		$formattedpapers .= "</ul>";
		$formattedpapers .= "<link rel='stylesheet' href='$CFG->wwwroot/blocks/paper/templates/css/block.css'>";
		
		return $formattedpapers;
	}
	
	private function to_html($p) {
		$pid = $p->paperid;
		$cid = $p->courseid;
		$name = $p->name;
		// 		$stream = $p->stream;
		$date = strftime("%d-%m-%G", time($p->date));
		$duration = $p->duration;
		$link = "<a href='/moodle/blocks/paper/view.php?pid={$pid}&cid={$cid}'</a> {$name}</a>";
		$template = <<<HTML
<li>
    <div class="paper-div">
        <div class="date">Date: $date</div>
        <div class="duration">$duration</div>
        <hr>
        <div class="name">$name</div>
    </div>
</li>
HTML;
		return $template;
	}
	
	// 	Configuration
	    function has_config() {
		return true;
	}
	public function html_attributes() {
		$attributes = parent::html_attributes();
		$attributes['class'] .= '  block_paper';
		return $attributes;
	}
	
}
