<?php
defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');
function compute_fine($limit_date,$return_date){
    global $DB;
        $limit_date=date_create($limit_date);
        $return_date = date_create($return_date);
        $diff=date_diff($limit_date,$return_date);
        $date_diff1=$diff->format("%R%a days");
return $date_diff1;
}

function get_center(){
    global $DB;
    $table='raomanager_centers';
    $result = $DB->get_records($table,array('status'=>1));
    $center_name=json_decode(json_encode($result),True);
    foreach ($center_name as $value)
    {
        $centers_id=$value['id'];
       return $centers=$value['name'];
    }

}

 function get_all_books(){
    global $CFG,$USER,$DB;
    $book = $DB->get_records('lib_bookmaster', array("status"=>1,"barcode"=>357121072993705));
    print_r($book);
            return $book;
}
 

