<?php


defined('MOODLE_INTERNAL') || die();

class mod_raobooklet_pdf2jpg extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $file_path = $data->path;
        $file_name = $data->name;
        //convert this file into images
        //make a dir named images in the folder
        mkdir($file_path.'images');
        //convert the pdf to images 
        $cmd = 'convert -density 200 -quality 90 "'.$file_path.$file_name.'" "'.$file_path.'images/%d.jpg"';
        echo 'Executing the following command : '.$cmd;
        $ret = exec($cmd,$output,$ret_var);
        if ($ret_var==0){
            echo 'Conversion complete';
            return 1;
        } else {
            echo 'Conversion failed';
            return -1;
        }
    }
}