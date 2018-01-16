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

    public function render_view_fine_books($page){
        $data = array();
        $data['fine_books'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/fine_books', $data);
    }
    public function render_view_all_lost_books($page){
        $data = array();
        $data['lost_books'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/lost_books', $data);
    }
    public function render_view_student_fine($page){
        $data = array();
        $data['view_student_fine'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/view_student_fine', $data);
    }
    public function render_view_all_booksfine($page){
        $data = array();
        $data['branch_book_fine'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/view_all_bookfine', $data);
    }
    public function render_view_all_rao_branch_fine($page){
        $data = array();
        $data['all_rao_branch_fine'] = $page->export_for_template($this);
        return $this->render_from_template('block_library/view_all_rao_branch_fine', $data);
    }

  
}

class view_issued_books implements renderable, templatable {
    
    private function get_issued_books(){
        global $USER, $DB;
       $center_id = get_user_center($USER->id);
        //get books issued at this center
        
        $sql = <<<SQL
        select  book.name,book.price,book.bookid,
        issue.id,issue.branch_id,issue.student_username, issue.issue_date,issue.return_date
        from {lib_bookmaster} as book join {lib_issue_record} as issue
        on book.id = issue.bookid
        where book.issued = 1 and issue.branch_id=?
SQL;
        $issued_books = $DB->get_records_sql($sql,array($center_id));
        /*
        //
        $issued_books = $DB->get_records('lib_bookmaster', array('status'=>1,'issued'=>'1','branch'=>get_user_center()));
        //
        */
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
    //
    private function get_available_books(){
     
       global $USER, $DB;
       $center_id = get_user_center($USER->id);
       $available_books = $DB->get_records('lib_bookmaster', array('status'=>1,'issued'=>0,'branch'=>$center_id));
       $available_books_array = array();
        foreach($available_books as $book){
            $available_books_array[] = $book;
           
        }
        //print_r($available_books_array);
        return $available_books_array;
   
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_available_books();
        return $data;
    }
    //
}
//fine
class view_fine_books implements renderable, templatable {
    private function get_fine_books(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        //get books issued at this center
        //
        $sql = <<<SQL
        select issue.id as issue_id,issue.bookid,fine.id as fineid,fine.student_username,fine.issue_id,fine.amount,fine.branch_issuer,fine.remark,fine.paid
        from {lib_issue_record} as issue join {lib_fine_record} as fine
        on issue_id = fine.issue_id
        where issue.student_username = fine.student_username and paid = 0 and issue.branch_id=?
SQL;
        $issued_books = $DB->get_records_sql($sql,array($center_id));
        //
        $issued_books_array = array();
        foreach($issued_books as $entry){
            $issued_books_array[] = $entry;
        }
        return $issued_books_array;
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_fine_books();
        return $data;
    }
}
//fine
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

// view all lost books
class view_all_lost_books implements renderable, templatable {
    private function get_lostbook_record(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        $lost_books = $DB->get_records('lib_bookmaster', array('status'=>-1,'branch'=>$center_id));
        $lost_books_array = array();
         foreach($lost_books as $book){
             $lost_books_array[] = $book;
         }
         //print_r($available_books_array);
         return $lost_books_array;
     }

    public function export_for_template(renderer_base $output){
        $data = $this->get_lostbook_record();
        return $data;
    }
}
//
class view_student_fine implements renderable, templatable {
    private function get_student_fine(){
        global $USER, $DB;
        $center_id = get_user_center($USER->id);
        $toatal_amount=NULL;
        //$fine = $DB->get_records('lib_fine_record', array('paid'=>1));
        $sql = <<<SQL
        select issue.id as newissueid,issue.bookid, issue.branch_id as issuebranch_id,fine.student_username,fine.id as fineid,fine.branch_id as finebranch_id, fine.amount,fine.branch_issuer,fine.paid,fine.remark,book.name, book.bookid as newbookid
        from {lib_issue_record} as issue join {lib_fine_record} as fine
        on issue.id = fine.issue_id join {lib_bookmaster} book on book.id = issue.bookid
        where issue.student_username = fine.student_username and issue.branch_id = fine.branch_id and fine.paid=1
SQL;
        // added branch_id in where condition //
      $fine = $DB->get_records_sql($sql,array($center_id));
  
      $paid_fine_entry = array();
        foreach($fine as $entry){
           // $count = count($entry->amount);
            $paid_fine_entry[] = $entry;
           
        }
        return $paid_fine_entry;
    }
    public function export_for_template(renderer_base $output){
        $data = $this->get_student_fine();
        return $data;
    }
}

class view_all_booksfine implements renderable, templatable {
    private function get_available_booksfine(){
        global $USER, $DB;
        $result = $DB->get_records_sql('SELECT id,student_username,branch_id,sum(amount) as sumaount FROM {lib_fine_record} WHERE paid=1 AND is_submitted=0 GROUP BY branch_id');
        foreach($result as $fine_branch){
            $book_fine_array[] = $fine_branch;
        }
      // print_r($book_fine_array);
      if (isset($book_fine_array)) {
        return $book_fine_array;
      }
}
    public function export_for_template(renderer_base $output){
        $data = $this->get_available_booksfine();
        return $data;
    }
    
}

class view_all_rao_branch_fine implements renderable, templatable {
    private function get_rao_branchfine(){
        global $USER, $DB;
        $result = $DB->get_records_sql('SELECT id,student_username,branch_id,sum(amount) as sumaount FROM {lib_fine_record} WHERE paid=1 AND is_submitted=1 GROUP BY branch_id');
        foreach($result as $fine_branch){
            $book_fine_array[] = $fine_branch;
        }
        if (isset($book_fine_array)) {
        return $book_fine_array;
        }
}
  
private function get_total_branches_fine(){
    global $USER, $DB;
    $result = $DB->get_records_sql('SELECT sum(amount) as totalamount FROM {lib_fine_record} WHERE paid=1 AND is_submitted=1');
    foreach($result as $fine_branch){
        $book_fine_array[] = $fine_branch;
    }
    
    return $book_fine_array;
}

    public function export_for_template(renderer_base $output){
        $branch_fine = $this->get_rao_branchfine();
        $total_branches_fine = $this->get_total_branches_fine();
        $data = array_merge($branch_fine,$total_branches_fine);
        return $data;
    }
}