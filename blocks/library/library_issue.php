<?php
require_once('../../config.php');
require_once('locallib.php');
require_once('books_form.php');
require_once('../branchadmin/locallib.php');

require_login();
global $DB, $USER, $CFG, $PAGE;

$PAGE->set_url('/blocks/library/library_issue.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Issue a book to student');
$PAGE->requires->js('/blocks/library/js/jquery.scannerdetection.min.js');
$PAGE->requires->js('/blocks/library/js/barcode.js');
$output = $PAGE->get_renderer('block_library');
echo $output->header();

//render the form
$issue_form = new issue_book_form();
if ($data = $issue_form->get_data()){
    $issue_date = issue_date();
    $book = $DB->get_record('lib_bookmaster', array("status"=>1,"barcode"=>$data->book_barcode));
    if($book->issued=='0'){
         if( $data->status=='0'){
            $table='lib_issue_record';
            $new_issue_bookstatus=1;
            $book_record = new stdClass();  
            $book_record->bookid = $book->id;
            $book_record->branch_issuer = $USER->username;
            $book_record->student_username = $data->student_username;
            $book_record->issue_date = $issue_date;
            $book_record->branch_id = get_user_center();
            $book_record->status = $data->status;
            $DB->insert_record($table, $book_record, $bulk=false) ;
            $issued='1';  // book is isssue so bookmaster table  is_issued=='1' //
            $update = "UPDATE {lib_bookmaster} SET issued='$issued' WHERE id='$book->id'";
            $DB->execute($update);
            echo "Book Successfully issue";
        } else{
            echo "Book Already Issued";
        }
    }
    else if($book->issued=='1'){
        if( $data->status=='1'){
                $return_date=date('Y-m-d');
                global $DB;
                $book_record = new stdClass();
                $book_record->return_date = $return_date;
                $book_record->status = $data->status;
                $sql = "UPDATE {lib_issue_record} SET return_date='$return_date', status='$data->status' WHERE bookid='$book->id'";
                $DB->execute($sql);
                //echo $sql;
                // return book //
                $issued_return='0';
                $update_return = "UPDATE {lib_bookmaster} SET issued='$issued_return' WHERE id='$book->id'";
                $DB->execute($update_return);
                // return book //
                echo "Book returned";
           if ($update_return != ''){
                // fine record //
                $fine_rec = <<<SQL
                select book.id, book.name,
                issue.student_username, issue.return_date
                from {lib_bookmaster} as book join {lib_issue_record} as issue
                on book.id = issue.bookid
                where issue.student_username = ? ORDER BY issue.return_date DESC
SQL;
+-
+--------------



+
+


















































































































































































































































































        $issued_books1 = $DB->get_records_sql($fine_rec,array($book->student_username));

           echo  $issue_date_new=$issued_books1->issue_date;
          echo   $issue_id=$issued_books1->id;
          echo $dateDiffrence=compute_fine($issue_date_new,$return_date);
            if($dateDiffrence>0){
                 global $DB;
                 echo   $fine=$dateDiffrence*$fine_price;
                 $table='lib_fine_record';
                 $issue_record = new stdClass();  
                 $issue_record->issue_id = $issue_id;
                 $issue_record->bookid = $books->id;
                 $issue_record->branch_issuer = $USER->username;                
                 $issue_record->amount = $fine;
                 $issue_record->return_date = $return_date;
                 $issue_record->book_status = $data->status;
                 $DB->insert_record($table, $issue_record, $bulk=false) ;
                 echo $fine." .RS Fine submit successfully";
             }
           }
              // fine record //
        }
    }
} else {
    //render the form
    $issue_form->display();
}
echo $output->footer();
