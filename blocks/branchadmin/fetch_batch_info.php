<?php
//fetches book info and returns json for it
require_once('../../config.php');
require_once("$CFG->dirroot/blocks/timetable/locallib.php");
require_once('locallib.php');
require_login();

/**
gets the lectures for the batch on specified date
**/
global $DB,$USER;
$batch = required_param('batch_id',PARAM_TEXT);
$date = required_param('date', PARAM_TEXT);
$ret = array();
$ret['students'] = get_students_by_batch($batch);
$ret['lectures'] = get_timetable($date, $date, null, $batch);
echo json_encode($ret);