<?php

function xmldb_block_readytohelp_install(){
    global $DB;
    $category = array('Ranklist/Result','Timetable','Student Portal','Branch Administration','Study Material','Student Welfare',
    'Faculties','Rao IIT App','Pre-Foundation','Student-Profile');
    $category_records = array();
    foreach ($category as $key => $value) {
        $rec = new stdClass();
        $rec->name = $value;
        array_push($category_records,$rec);
    }
    $DB->insert_records('grievance_categories',$category_records);
}