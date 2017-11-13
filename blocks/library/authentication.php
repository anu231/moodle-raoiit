<?php
require_once('../../config.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once('locallib.php');

    global $DB;
    $fine_price=30; // Fine //
    $stud_auth=array();
    $barcode=NULL;
    $return_date=NULL; // initial Return date is null //
    $branch_issuer = required_param('branch_issuer', PARAM_TEXT);
    $branch_issuer_password = required_param('branch_issuer_password', PARAM_TEXT);
    $student_username = required_param('student_username', PARAM_INT);
    $barcode = required_param('code', PARAM_INT); // Scan Barcode //
    $issue_date = date('Y-m-d');
    $nextWeek = time() + (7 * 24 * 60 * 60);
    $limit_date = date('Y-m-d', $nextWeek); // limit date is 7 times greater than issue date //
    $remark = required_param('remark', PARAM_TEXT);
    $book_status = required_param('book_status', PARAM_TEXT); // book status //
    $book = $DB->get_records('lib_bookmaster', array("status"=>1,"barcode"=>$barcode));
    $books=json_decode(json_encode($book),True);
        foreach ($books as $value)
        {
            $id=$value['id'];
        }

//print_r($books);

        $booksBarcode=$books[$id]['barcode'];
        $branchcode=$books[$id]['branch'];
        $is_issued=$books[$id]['issued']; // Availibity of book  if '0'= Available, if '1' = Already Issued//
    

        if ($booksBarcode == $barcode){
            $books_id= $books[$id]['bookid'];
            $branch_id= $books[$id]['branch'];
        }
        else{
           echo "Barcode Not found in Database";
            exit();
        }
 

    $stud_auth = authenticate_user_login($branch_issuer,$branch_issuer_password);
    $result = json_decode(json_encode($stud_auth), True);
    $auth=$result['auth']; 
    if ($auth=='manual'){ // checking Auth //
        if ($booksBarcode == $barcode){ // checking book status and scan barcode //
             // if book is availabe in bookmaster table //
            if($book_status=='issue'){
                if($is_issued=='0'){
                $issue_rec  = $DB->get_record('lib_issue_record', array('code'=>$barcode,'student_username'=>$student_username));
                $issue_rec=json_decode(json_encode($issue_rec),True);
               // print_r($issue_rec);
                        if(!empty($issue_rec)){
                        $curr_date = date('Y-m-d');
                        $limit_date = $issue_rec['limit_date'];
                        $return_date = $issue_rec['return_date'];
                        $curr_date.'-'.$limit_date.'-'.$return_date;
                        $next_issue_date = date('Y-m-d', strtotime($return_date.'7 days'));
                        
                        if($curr_date <= $next_issue_date){
                            echo "You are not allowed to take this book till ";
                            echo $next_issue_date;
                            exit;
                        }
                        
                        else{
                            unset($limit_date);
                            unset($return_date);
                            unset($curr_date);
                            $issue_date = date('Y-m-d');
                            $nextWeek = time() + (7 * 24 * 60 * 60);
                            $limit_date = date('Y-m-d', $nextWeek); // limit date is 7 times greater than issue date //
                            global $DB;
                            $table='lib_issue_record';
                            $book_record = new stdClass();  
                            $book_record->bookid = $books_id;
                            $book_record->branch_issuer = $branch_issuer;
                            $book_record->student_username = $student_username;
                            $book_record->code = $barcode;
                            $book_record->issue_date = $issue_date;
                            $book_record->limit_date = $limit_date;
                            $book_record->return_date = '';
                            $book_record->branch_id = $branch_id;
                            $book_record->remark = $remark;
                            $book_record->book_status = $book_status;
                            $DB->insert_record_raw($table, $book_record, $returnid=true, $bulk=false) ;
                            $issued='1';  // book is isssue so bookmaster table  is_issued=='1' //
                            $update = "UPDATE {lib_bookmaster} SET issued='$issued' WHERE barcode='$barcode'";
                            $DB->execute($update);
                            echo "Book Issued successfully";
                            exit();
                        }
                    } 
                    else{
                        global $DB;
                        $table='lib_issue_record';
                        $book_record = new stdClass();  
                        $book_record->bookid = $books_id;
                        $book_record->branch_issuer = $branch_issuer;
                        $book_record->student_username = $student_username;
                        $book_record->code = $barcode;
                        $book_record->issue_date = $issue_date;
                        $book_record->limit_date = $limit_date;
                        $book_record->return_date = $return_date;
                        $book_record->branch_id = $branch_id;
                        $book_record->remark = $remark;
                        $book_record->book_status = $book_status;
                        $DB->insert_record_raw($table, $book_record, $returnid=true, $bulk=false) ;
                        $issued='1';  // book is isssue so bookmaster table  is_issued=='1' //
                        $update = "UPDATE {lib_bookmaster} SET issued='$issued' WHERE barcode='$barcode'";
                        $DB->execute($update);
                        echo "Book Issued successfully";
                        exit();
                    }
                }
                if($is_issued=='1'){ // if book is not availabe in bookmaster table ie. $is_issued=='1' //
                    echo "Book is already issued to another student";
                    exit();
                }
            }
            $issue_rec  = $DB->get_record('lib_issue_record', array('code'=>$barcode,'student_username'=>$student_username));
            $issue_rec=json_decode(json_encode($issue_rec),True);
            
            if($book_status=='return'){
                $return_date=date('Y-m-d');
                global $DB;
                //$table='lib_issue_record';
                $book_record = new stdClass();
                $book_record->id = $book_record->code;            
                $book_record->return_date = $return_date;
                $book_record->book_status = $book_status;
                $sql = "UPDATE {lib_issue_record} SET return_date='$return_date',book_status='$book_status' WHERE code='$barcode'";
                $DB->execute($sql);
                // return book //
                $issued_return='0';
                $update_return = "UPDATE {lib_bookmaster} SET issued='$issued_return' WHERE barcode='$barcode'";
                $DB->execute($update_return);
                // return book //
                echo "Book returned";
                
                //$ldate  = $DB->get_record('lib_issue_record', array('code'=>$barcode));
                //$ldate_new=json_decode(json_encode($ldate),True);
              
                 $ldate=$DB->get_record_sql('SELECT * FROM {lib_issue_record} WHERE code = ? AND student_username = ? ORDER BY issue_date DESC', 
                 array($barcode, $student_username));
                 $ldate_new=json_decode(json_encode($ldate),True);
                    //print_r($ldate_new);exit;
                 $limit_date_new=$ldate_new['limit_date'];
                $issue_id=$ldate_new['id'];
                $dateDiffrence=compute_fine($limit_date_new,$return_date);
                if($dateDiffrence>=0){
                    global $DB;
                    $fine=$dateDiffrence*$fine_price;
                    $table='lib_fine_record';
                    $issue_record = new stdClass();  
                    $issue_record->issue_id = $issue_id;
                    $issue_record->bookid = $books_id;
                    $issue_record->branch_issuer = $branch_issuer;                  
                    $issue_record->amount = $fine;
                    $issue_record->return_date = $return_date;
                    $issue_record->book_status = $book_status;
                    $DB->insert_record_raw($table, $issue_record, $returnid=true, $bulk=false) ;
                    echo $fine." .RS Fine submit successfully";
                }
            
                else{
                    echo "Returned book record not found in issued book records";
                }
            }
        }
    }
    else{
        echo "Auth is not manual";
    }