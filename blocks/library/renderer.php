<?php
defined('MOODLE_INTERNAL') || die;
require_once('../../config.php');
require_once('locallib.php');

class block_library_renderer extends plugin_renderer_base {
    
    public function render_view_all_books($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_library/view_books', $data); 
    }
      
}
class view_all_books implements renderable,templatable {
    private function get_all_books(){
        global $CFG,$USER,$DB;
        $book_field = new stdClass();
        $book = $DB->get_records('lib_bookmaster', array("status"=>1,"barcode"=>357121072993705));

        $user_list = array(); 
        foreach ($book as $book_field){
            $temp = array();
              
            $temp['name'] = $book_field->name;
            $temp['bookid'] = $book_field->bookid;
            $temp['price'] = $book_field->price;

           //  $temp['id'] = $user->id;echo "<br>";
            //array_push($user_list,$temp);
            $user_list[] = $temp;
        }
        print_r($user_list);
        return $user_list;
    }
    public function export_for_template(renderer_base $output) {                                                                    
        $data = $this->get_all_books();
        return $data;     
                                                                                                          
    }

}