<?php

class pdf2jpg extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $file_path = $data['path'];
        $file_name = $data['name'];
        //convert this file into images
        //make a dir named images in the folder
        mkdir($file_path.'images');
        //convert the pdf to images 
        $cmd = 'convert -density 200 -q 90 '.$file_path.$file_name.' '.$file_path.'images/%d.jpg';
        $ret = exec($cmd,$output,$ret_var);
        if ($ret_var==0){
            //successful
            return 1;
        } else {
            return -1;
        }
        //exit
    }
}

function list_booklets_select(){
    //lists all the booklets in the system
    global $DB;
    $booklets = $DB->get_records_menu('raobooklet_info', null, $sort='', $fields='bookletid, name');
    return $booklets;
}