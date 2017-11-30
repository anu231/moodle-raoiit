<?php
defined('MOODLE_INTERNAL') || die;
require_once('../../config.php');
require_once('../branchadmin/locallib.php');
require_once('locallib.php');

class block_library_renderer extends plugin_renderer_base {
    
    public function render_view_all_books($page){
        $data = $page->export_for_template($this);
        return parent::render_from_template('block_library/view_books', $data); 
    }
    
    public function render_view_issued_books($page){
        $data = array();
        $data['issued_books'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/issued_books', $data);
    }

    public function render_view_available_books($page){
        $data = array();
        $data['available_books'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/available_books', $data);
    }
}

class view_issued_books implements renderable, templatable {
    
    private function get_issued_books(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        //get books issued at this center
        $sql = <<<SQL
        select book.id, book.name,
        issue.student_username, issue.issue_date
        from {lib_bookmaster} as book join {lib_issue_record} as issue
        on book.id = issue.bookid
        where issue.status = 0 and issue.branch_id=?
SQL;
        $issued_books = $DB->get_records_sql($sql,array($center_id));
        $issued_books_array = array();
        foreach($issued_books as $entry){
            $issued_books_array[] = $entry;
        }
        return $issued_books_array;
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_issued_books();
        return $data;
    }
}

class view_available_books implements renderable, templatable {
    private function get_available_books(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        $available_books = $DB->get_records('lib_bookmaster', array('status'=>1,'branch'=>get_user_center()));
        $available_books_array = array();
        foreach($available_books as $book){
            $available_books_array[] = $book;
        }
        return $available_books_array;
    }
    public function export_for_template(renderer_base $output){
        $data = $this->get_available_books();
        return $data;
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
        //print_r($user_list);
        return $user_list;
    }
    public function export_for_template(renderer_base $output) {                                                                    
        $data = $this->get_all_books();
        return $data;     
                                                                                                          
    }

}