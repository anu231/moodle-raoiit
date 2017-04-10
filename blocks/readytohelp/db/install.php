<?php

function xmldb_block_readytohelp_install(){
    // TODO VERSION CHECK
    global $DB;
    $category = array('Ranklist/Result','TimeTable[Class/Tests]','Student Portal','Branch Administration','Study Materials','Student Welfare',
    'Faculties','Rao IIT App','Pre-Foundation','Student-Profile');
    $category_records = array();
    foreach ($category as $key => $value) {
        $rec = new stdClass();
        $rec->name = $value;
        array_push($category_records,$rec);
    }
    $DB->insert_records('grievance_categories',$category_records);
}