<?php

require_once('locallib.php');

class block_timetable_renderer extends plugin_renderer_base {
    public function week() {
        return $this->render(new week());
    }
    public function render_week($context) {
        return $this->render_from_template('block_timetable/week', $context);
    }
}

class week implements renderable {
    public function __construct() {
        $json = get_timetable();
        $this->days = $this->get_days_lectures();
    }
    private $subj_map = array(
		'p'=>'Physics',
		'c'=>'Chemistry',
		'm'=>'Maths',
		'z'=>'Zoology',
		'b'=>'Botany'
	);

    private function get_days_lectures(){
        global $CFG;
        $json = get_timetable();
        $processed_lectures = array();
        $index = 0;
        foreach($json as $date => $lectures){
            $day = array();
            foreach($lectures as $lecture){
                $tmp = array();
                $tmp['fancydate'] = $date;
                $tmp['date'] = "{$lecture['d']}-{$lecture['m']}-{$lecture['y']}";
                $tmp['starttime'] = $lecture['sh'].':'.$lecture['sm'];
                $tmp['endtime'] = $lecture['eh'].':'.$lecture['em'];
                $tmp['teacher'] = isset($lecture['sn']) ? $lecture['sn'] : '';
                $tmp['subject'] = isset($lecture['subj']) ? $this->subj_map[$lecture['subj']] : '';
                $tmp['topicname'] = isset($lecture['ton']) ? $lecture['ton'] : '';
                $tmp['notes'] = $lecture['n'] != "null" ? $lecture['n'] : '';
                $tmp['cancelclass'] = $lecture['del'] == '1' ? 'cancelled' : '';
                $tmp['testclass'] = $lecture['t'] == '1' ? 'test' : '';
                $tmp['batch'] = $lecture['c'].' - '.$lecture['b'];
                $day[] = $tmp;
            }
            $processed_lectures[$index]['fancydate'] = $date;
            $processed_lectures[$index]['items'] = $day;
            $index++;
        }
        
        return $processed_lectures;
    }
}