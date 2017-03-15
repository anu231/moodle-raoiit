<?php
require_once('../../config.php');

$approve = required_param('approve', PARAM_RAW);

global $DB, $USER;
require_login();

switch ($approve) {
    case 'yes':
        if( ! $DB->record_exists('idcard', array('username'=>$USER->username))){
            $record = new stdClass;
            $record->username = $USER->username;
            $record->status = 1;
            $DB->insert_record('idcard', $record);
        } else {
            $rec = $DB->get_record('idcard', array('username'=>$USER->username));
            echo var_dump($rec);
        }
        break;
    default:
        // redirect(new moodle_url("/"));
        break;
}