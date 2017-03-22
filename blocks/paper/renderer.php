<?php

// require_once('locallib.php');

class block_paper_renderer extends plugin_renderer_base {

    public function paper($paperid) {
        // Fetch paper information
        global $DB;
        if($paper = $DB->get_record('block_paper', array('paperid' => $paperid))){
            $URL = "http://192.168.1.19:8000/papers/";
            $response = paper_get_request($URL.$paperid.'/');
            $paper->lastdate = $response ? $response->lastdate : '';
            return $this->render(new paper($paper));
        } else {
            return "<h1>Paper not found</h1>";
        }
    }

    public function render_paper(paper $context) {
        return $this->render_from_template('block_paper/paper', $context);
    }

    public function course_paper_list($courseid) {
        // List of papers in a course
        return $this->render(new course_paper_list($courseid));
    }

    public function render_course_paper_list(course_paper_list $papers) {
        return $this->render_from_template('block_paper/course_paper_list', $papers);
    }

    public function all_paper_list() {
        return $this->render(new all_paper_list());
    }

    public function render_all_paper_list(all_paper_list $papers) {
        return $this->render_from_template('block_paper/all_paper_list', $papers);
    }
}

class paper implements renderable {
    public function __construct(stdclass $paper){
        $this->paper = $paper; // Just in case
        $this->name = $paper->name;
        $this->duration = $paper->duration;
        $this->date = $this->formatdate($paper->date);
        $this->lastdate = $this->formatdate($paper->lastdate);
        $this->showSolution = $this->shouldShowSolution($paper);
        $this->syllabus = $paper->syllabus;
        $this->markingscheme = $this->formatmarkingscheme($paper->markingscheme);
        $this->instructions = $paper->instructions;
        $this->solutions = $paper->solutions;
    }

    private function formatdate($datestr){
        $timestamp = strtotime($datestr);
        $f = strftime('%d-%m-%G', $timestamp);
        return $f;
    }

    private function shouldShowSolution($paper){
        $diff = strtotime($paper->lastdate) - strtotime($paper->date);
        return $diff <= 0 ? TRUE : FALSE;
    }

    private function formatmarkingscheme($markstr){
        $json = json_decode($markstr);
        $marks = array();
        $json->sccor ? $marks["Single Choice"] = array($json->sccor, $json->scnegmarks) : null;
        $json->mccor ? $marks["Multiple Choice"] = array($json->mccor, $json->mcnegmarks) : null;
        $json->arcor ? $marks["Arithmetic"] =  array($json->arcor, $json->arnegmarks) : null;
        $json->chcor ? $marks["Comprehension"] = array($json->chcor, $json->chnegmarks) : null;
        $json->tfcor ? $marks["True or False"] = array($json->tfcor, $json->tfnegmarks) : null;
        $json->fbcor ? $marks["FB"] = array($json->fbcor, $json->fbnegmarks): null;
        $json->mscor ? $marks["MS"] = array($json->mscor, $json->msnegmarks): null;

        $markup = "";
        foreach ($marks as $mark => $type) {
            $markup .= "<tr><td>$mark</td><td>$type[0]</td><td>$type[1]</td></tr>";
        }
        return $markup;
    }
}

// List of papers from a course
class course_paper_list implements renderable {
    public function __construct($courseid) {
        $this->papers = $this->get_papers_for_course($courseid);
    }

    private function get_papers_for_course($courseid) {
        global $DB, $CFG;

        $papers = $DB->get_records('block_paper', array('courseid' => $courseid));
        $formattedpapers = [];
        foreach($papers as $paper){
            $paper->viewlink = "$CFG->wwwroot/blocks/paper/view.php?pid=$paper->paperid&cid=$paper->courseid";
            $paper->date = strftime('%d/%m/%G',strtotime($paper->date));
            $formattedpapers[] = $paper;
        }
        return $formattedpapers;
    }
    private function get_course_name($courseid) {
        global $DB;
        $course = $DB->get_record();
    }
}

// List of papers from all courses
class all_paper_list implements renderable {
    public function __construct() {
        $this->coursemap = $this->get_course_names();
        $this->courses = $this->get_all_papers();
        $this->heading = "Viewing papers from all courses";
    }

    private function get_all_papers() {
        global $DB, $CFG;
        $papers = $DB->get_records('block_paper');
        $courses = [];
        foreach($papers as $paper){
            $paper->viewlink = "$CFG->wwwroot/blocks/paper/view.php?pid=$paper->paperid&cid=$paper->courseid";
            $paper->date = strftime('%d/%m/%G',strtotime($paper->date));
            $coursename = $this->coursemap[$paper->courseid];
            $courses[$coursename]['coursename'] = $coursename; // Map courseid to course name
            $courses[$coursename]['papers'][] = $paper;
        }
        $formattedcourses = array();
        foreach($courses as $course){
            $formattedcourses[] = $course;
        }
        return $formattedcourses;
    }

    private function get_course_names() {
        // Return a hashmap of courseid->coursename
        global $DB;
        $courses = $DB->get_records_menu('course', null, null, $fields='id, fullname');
        return $courses;
    }
}