<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');

class block_branchadmin_smsnotification extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $numbers = $data->numbers;
        $msg = $data->message;
        $num_array = Array();
        foreach($numbers as $num){
            //echo $num->data.'<br>';
            sendSMS($num->data, $msg);
            $num_array[] = $num->data;
        }
        //add this data to the branch_sms_record table
        global $DB;
        $sms_rec = new stdClass();
        $sms_rec->sender = $data->sender;
        /*$sms_rec->receipients = implode(',',array_map(function($c){
            return $c->data;
        }, $numbers));*/
        $sms_rec->receipients = implode(',', $num_array);
        $sms_rec->message = $msg;
        $sms_rec->timestamp = time();
        $DB->insert_record('branch_sms_record',$sms_rec);
    }
}