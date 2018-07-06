<?php

require_once('locallib.php');

class block_timetable_renderer extends plugin_renderer_base {
    public function week($batch=null, $faculty=null) {
        return $this->render(new week($batch,$faculty));
    }
    public function render_week($context) {
        return $this->render_from_template('block_timetable/week', $context);
    }
    public function batchreport(){
        return $this->render(new batchreport());
    }
    public function render_batchreport($context){
        return $this->render_from_template('block_timetable/batchreport', $context);
    }
}

class week implements renderable {
    public function __construct($batch=null, $faculty=null) {
        //$this->days = $this->get_days_lectures();
	//print_r($faculty);
        if ($batch != null){
            $this->days = $this->get_week_timetable($batch);

        } else if ($faculty != null){
	    $this->days = $this->get_week_faculty_timetable($faculty);		
	} 
	else if ($batch==null) {
            $this->days = $this->get_week_timetable();
        } 
    }
    private $subj_map = array(
		'p'=>'physics',
		'c'=>'chemistry',
		'm'=>'mathematics',
		'z'=>'zoology',
		'b'=>'botany'
	);

    private function get_week_start_end_dates(){
        date_default_timezone_set("Asia/Calcutta");
        /*$start_date = date("Y-m-d", strtotime('monday this week', strtotime(date('Y-m-d'))));
        $end_date = date("Y-m-d", strtotime('sunday this week', strtotime(date('Y-m-d'))));*/
        $ts = strtotime('now');
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', strtotime('next sunday', $start));
        if (date('w',$ts)==6){
            $end_date = date('Y-m-d',strtotime('next sunday',strtotime($end_date)));
        }
        return array('start_date'=>$start_date,'end_date'=>$end_date);
    }

     private function get_week_timetable($batch=null){
        global $USER;
        $dates = $this->get_week_start_end_dates();
	
        if ($batch != null){
            return get_timetable($dates['start_date'],$dates['end_date'],null, $batch);
        } else {
            return get_timetable($dates['start_date'],$dates['end_date'],$USER);
        }
        
    }
	
    private function get_week_faculty_timetable($faculty=null){
	global $USER;
	     $faculty_timetable = $this->get_week_start_end_dates();

		 if ($faculty != null){
			
			return get_faculty_timetable($faculty_timetable['start_date'],$faculty_timetable['end_date'],null,$faculty);
		} 
	}

    /*
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
    }*/
}

class batchreport implements renderable{
    public function __construct(){
        $this->data = new stdClass();
        $this->data->report = $this->get_completion_report();
        $this->data->ptm = $this->get_ptm_record_renderer();
    }

    private function get_completion_report(){
        global $USER;
        $report = get_completed_topics($USER->username);
        return $report;
    }
    private function get_ptm_record_renderer(){
        global $USER;
        return get_ptm_records($USER->username);
    }

}
