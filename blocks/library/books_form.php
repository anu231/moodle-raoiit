<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/raolib.php");
require_once('../branchadmin/locallib.php');
require_once('locallib.php');

class add_books_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'bookid', 'Book Id','required');
        $mform->setType('bookid', PARAM_TEXT);
        $mform->addElement('text', 'name', 'Book Name','required');
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'subject', 'Subject','required');
        $mform->setType('subject', PARAM_TEXT);
        $mform->addElement('text', 'volume', get_string('volume', 'block_library'));
        $mform->setDefault('volume',0);
        $mform->setType('volume', PARAM_INT); 
        $mform->addElement('text', 'publisher', get_string('publisher', 'block_library'),'required');
        $mform->setType('publisher', PARAM_TEXT);
        $mform->addElement('text', 'author', get_string('author', 'block_library'),'required');
        $mform->setType('author', PARAM_TEXT);
        $mform->addElement('text', 'price', get_string('price', 'block_library'),'required');
        $mform->setType('price', PARAM_INT);
        $mform->addElement('text', 'barcode', get_string('barcode', 'block_library'),'required','maxlength="13"');
        $mform->setType('barcode', PARAM_INT);
        $center_list = convert_std_to_array(get_centers());
        $mform->addElement('select', 'branch', get_string('branch', 'block_library'), $center_list);
        $mform->addElement('date_selector', 'purchasedate', 'Purchase Date');
        $mform->addElement('date_selector', 'branchissuedate', 'Branch Issue Date');
        $options = array('1' => 'Available');
        $select = $mform->addElement('select', 'status', get_string('status', 'block_library'), $options);
        $select->setSelected('1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Add Book");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $book = $DB->get_records('lib_bookmaster', array("status"=>1));
        foreach($book as $result)
        {
             $bookid=$result->bookid;
             $bookname=$result->name;
             $bookbarcode=$result->barcode;
        }
        if($bookid == $data['bookid']){
            $errors['bookid'] = 'Book is Already present in database';
        }
        if($bookbarcode == $data['barcode']){
            $errors['barcode'] = 'Book Barcode is Already present in database';
        }
        if($bookname == $data['name']){
            $errors['name'] = 'Book Name is Already present in database';
        }
        if ($data['price'] != is_numeric($data['price'])){
            $errors['price'] = 'Please enter numeric value';    
        }
       
        return $errors;
    }
}
        // Fine Record //
class lost_books_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden', 'issue_id','Lost Issue Id');
        $mform->setType('issue_id', PARAM_INT);
        $mform->setDefault('issue_id',$this->_customdata['issue_id']);
        
        $mform->addElement('hidden', 'book_id','Lost Book Id');
        $mform->setType('book_id', PARAM_INT);
        $mform->setDefault('book_id',$this->_customdata['book_id']);

        $mform->addElement('hidden', 'from','Lost From');
        $mform->setType('from', PARAM_TEXT);
        $mform->setDefault('from',$this->_customdata['from']);
        //$mform->addElement('static', 'static_student_username', 'Student Roll Number',
        //$this->_customdata['lost_student_username']);

        /*$mform->addElement('hidden', 'lost_student_username','Student Roll Number','maxlength="6"');
        $mform->setType('lost_student_username', PARAM_INT);
        $mform->setDefault('lost_student_username',$this->_customdata['lost_student_username']);
        
        $mform->addElement('static', 'static_bookid', 'Lost Book Id',
        $this->_customdata['lost_bookid']);

        $mform->addElement('hidden', 'lost_bookid','Lost Book Id');
        $mform->setType('lost_bookid', PARAM_INT);
        $mform->setDefault('lost_bookid',$this->_customdata['lost_bookid']);*/
        
        $mform->addElement('textarea', 'lost_remark', 'Remark', 'wrap="virtual" rows="10" cols="50",required');
        $mform->setType('lost_remark', PARAM_RAW);

        /*$lost_options = array('-1' => 'Lost Book from Student');*/
        //$select = $mform->addElement('select', 'lost_status','Lost Status',$lost_options);
        //$select->setSelected('-1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Update Lost Book");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}
        // lost book from branch //
        class lost_books_branch_form extends moodleform {
            function definition(){
              
                $mform =& $this->_form;
             
                $mform->addElement('hidden', 'book_id','Lost Issue Id');
                $mform->setType('book_id', PARAM_INT);
                $mform->setDefault('book_id',$this->_customdata['book_id']);
              
                $mform->addElement('static', 'static_lost_bookid', 'Lost Book Id',
                $this->_customdata['bookid']);

                $mform->addElement('hidden', 'lost_bookid','Lost Book Id');
                $mform->setType('lost_bookid', PARAM_INT);
                $mform->setDefault('lost_bookid',$this->_customdata['bookid']);
                
                $mform->addElement('static', 'static_lost_bookname', 'Lost Book Name',
                $this->_customdata['book_name']);

                $mform->addElement('hidden', 'lost_book_name','Lost Book Name');
                $mform->setType('lost_book_name', PARAM_INT);
                $mform->setDefault('lost_book_name',$this->_customdata['book_name']);
                
                $mform->addElement('textarea', 'lost_remark', 'Remark', 'wrap="virtual" rows="10" cols="50",required');
                $mform->setType('lost_remark', PARAM_RAW);
        
                $lost_options = array('-1' => 'Lost Book from Branch');
                $select = $mform->addElement('select', 'lost_status','Lost Book Status',$lost_options);
                $select->setSelected('-1');
                $buttonarray=array();
                $buttonarray[] = $mform->createElement('submit', 'submit', "Lost Books");
                $buttonarray[] = $mform->createElement('cancel');
                $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
            }
        }
        // lost book from branch //

        // Pay Fine form //
        class pay_fine_form extends moodleform {
            function definition(){
                $mform =& $this->_form;
                $mform->addElement('hidden', 'fineid','Fine Id');
                $mform->setType('fineid', PARAM_INT);
                $mform->setDefault('fineid',$this->_customdata['fineid']);
              
                $mform->addElement('static', 'static_lost_bookid', 'Fine Amount',
                $this->_customdata['fine_amount']);

                $mform->addElement('hidden', 'fine_amount','Fine Amount');
                $mform->setType('fine_amount', PARAM_INT);
                $mform->setDefault('fine_amount',$this->_customdata['fine_amount']);
                
                /*$mform->addElement('static', 'static_lost_bookname', 'Branch Issuer',
                $this->_customdata['branch_issuer']);

                $mform->addElement('hidden', 'branch_issuer','Branch Issuer');
                $mform->setType('branch_issuer', PARAM_INT);
                $mform->setDefault('branch_issuer',$this->_customdata['branch_issuer']);*/
                
                $mform->addElement('textarea', 'fine_remark', 'Remark', 'wrap="virtual" rows="10" cols="50",required');
                $mform->setType('fine_remark', PARAM_RAW);
        
                //$fine_options = array('1' => 'Pay Fine');
                //$select = $mform->addElement('select', 'fine_status','Pay Status',$fine_options);
                //$select->setSelected('1');
                $buttonarray=array();
                $buttonarray[] = $mform->createElement('submit', 'submit', "Pay Fine");
                $buttonarray[] = $mform->createElement('cancel');
                $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
            }
        }

        // Pay fine form //

        // Fine Record //
class issue_book_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'book_barcode', "barcode for the book",'maxlength="100",required');
        $mform->setType('book_barcode', PARAM_TEXT);
        $mform->addElement('text', 'student_username', get_string('student', 'block_library'),'maxlength="6",required');
        $mform->setType('student_username', PARAM_INT); 
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'status', '', get_string('issue', 'block_library'), 0);
        $radioarray[] = $mform->createElement('radio', 'status', '', get_string('return', 'block_library'), 1);
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        $mform->setDefault('status', 0);

        $this->add_action_buttons(true,'Issue / Return Book');
        
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $book = $DB->get_record('lib_bookmaster', array("status"=>1,"barcode"=>$data['book_barcode']));
        if (empty($book)){
            $errors['book_barcode'] = 'Book does not exist in the system or is damaged/lost';
            //return  $errors;
        }
        //check if student has paid the library money
        $student = $DB->get_record('user',array('username'=>$data['student_username']));
        $library_fee = get_rao_user_profile_fields(array('libraryfee'),$student->id);
        if ((int)$library_fee['libraryfee'] < 2000){
            //$errors['student_username'] = 'Can\'t issue books as student has not paid the library fee';
        }
        //check if book is already issued
        if($book->issued=='1' && $data['status']==0){
            $errors['book_barcode'] = 'Book is Already Issued';
        }
        //check a return can be processed only against an issued book
        if($book->issued=='0' && $data['status']==1){
            $errors['book_barcode'] = 'Only an issued book can be returned';
        }
        if ($data['status']==0){
            //check if book was issued to this student within the last 7 days
            $issue_rec  = $DB->get_record('lib_issue_record', array('bookid'=>$book->id,'student_username'=>$data['student_username']));
            if(!empty($issue_rec)){
                $curr_date = date('Y-m-d');
                $return_date = $issue_rec->return_date;
                $reissue_days = get_config('library','reissue');
                $next_issue_date = date('Y-m-d', strtotime($return_date.$reissue_days.' days'));
                if($curr_date <= $next_issue_date){
                    $errors['book_barcode'] = 'You are not allowed to take this book till after '.$next_issue_date;
                } 
            }
            //check if there are any unpaid fines for this user
            $fine_records = $DB->get_records('lib_fine_record',array('student_username'=>$data['student_username'],'paid'=>0));
            if (!empty($fine_records)){
                $errors['student_username'] = 'Book cant be issued to the student as he still has unpaid fines';
            }
        }
        if ($data['status']==1){
            //check if the book was issued to the same user
            //get the issue record
            $issue_record = $DB->get_record('lib_issue_record',array('student_username'=>$data['student_username'],'bookid'=>$book->id));
            if (empty($issue_record)){
                $errors['book_barcode'] = 'Book was not issued to this student';
                return $errors;
            }
        }
        //check the branch of the book and issuer is same
        if ($book->branch != get_user_center()){
            $errors['book_barcode'] = 'Book does not belong to the center assigned to the issuer';
        }

        //check if the book branch and the student branch are the same
        $student_user = $DB->get_record('user',array('username'=>$data['student_username']));
        if ($book->branch != get_user_center($student_user->id)){
            $errors['book_barcode'] = 'Book does not belong to the center assigned to the student';
        }
        
        return $errors;
    }
   
}

class student_fine_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'student_username','Enter Student Username');
        $mform->setType('student_username', PARAM_INT);
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit Roll Number");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $fine = $DB->get_record('lib_fine_record', array("paid"=>0));
        if (empty($fine)){
            $errors['student_username'] = 'There is no fine on this roll number';
            return  $errors;
        }
        if($fine->student_username != $data['student_username']){
            $errors['student_username'] = 'Student Roll Number Not found';
        }
        return $errors;
    }
}
class view_student_fine_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden', 'fineid','Fine Id');
        $mform->setType('fineid', PARAM_INT);
        $mform->setDefault('fineid',$this->_customdata['fineid']);
      
        $mform->addElement('static', 'static_lost_bookid', 'Fine Amount',
        $this->_customdata['fine_amount']);

        $mform->addElement('hidden', 'fine_amount','Fine Amount');
        $mform->setType('fine_amount', PARAM_INT);
        $mform->setDefault('fine_amount',$this->_customdata['fine_amount']);
        
        $mform->addElement('static', 'static_lost_bookid', 'Student Username',
        $this->_customdata['student_username']);
        
        $mform->addElement('hidden', 'student_username','Student Username');
        $mform->setType('student_username', PARAM_INT);
        $mform->setDefault('student_username',$this->_customdata['student_username']);
        
        $mform->addElement('textarea', 'fine_remark', 'Remark', 'wrap="virtual" rows="10" cols="50" required');
        $mform->setType('fine_remark', PARAM_RAW);

        $fine_options = array('1' => 'Pay Fine');
        $select = $mform->addElement('select', 'fine_status','Pay Status',$fine_options);
        $select->setSelected('-1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Pay Fine");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $fine = $DB->get_record('lib_fine_record', array("paid"=>0));
        if (empty($fine)){
            $errors['student_username'] = 'There is no fine on this roll number';
            return  $errors;
        }
        if($fine->student_username != $data['student_username']){
            $errors['student_username'] = 'Student Roll Number Not found';
        }
        return $errors;
    }
}

class view_submitted_ho_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden', 'fineid','Fine Id');
        $mform->setType('fineid', PARAM_INT);
        $mform->setDefault('fineid',$this->_customdata['fineid']);
      
        $mform->addElement('static', 'static_lost_bookid', 'Fine Amount',
        $this->_customdata['fine_amount']);

        $mform->addElement('hidden', 'fine_amount','Fine Amount');
        $mform->setType('fine_amount', PARAM_INT);
        $mform->setDefault('fine_amount',$this->_customdata['fine_amount']);
        
        $mform->addElement('static', 'static_lost_bookid', 'Branch Code',
        $this->_customdata['branch_id']);
        
        $mform->addElement('hidden', 'branch_id','Branch Code');
        $mform->setType('branch_id', PARAM_INT);
        $mform->setDefault('branch_id',$this->_customdata['branch_id']);
  
        $fine_options = array('1' => 'Submitted To HO');
        $select = $mform->addElement('select', 'fine_status','Pay Status',$fine_options);
        $select->setSelected('-1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Pay Fine");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    
}
// add barcode //
class add_barcode_form extends moodleform {
    function definition(){
        $a= get_centers_book();
        $mform =& $this->_form;
        $book_list = convert_std_to_array_bookid(get_centers_book());
        $mform->addElement('select', 'book', 'Name of Book',$book_list);
        $mform->addElement('text', 'book_barcode', "barcode for the book",'maxlength="13",required');
        $mform->setType('book_barcode', PARAM_INT);
        $this->add_action_buttons(true,'Add Barcode');
        
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $book = $DB->get_record('lib_bookmaster', array("status"=>1,"barcode"=>$data['book_barcode']));
        if (!empty($book)){
            $errors['book_barcode'] = 'Barcode is already scanned';
            return  $errors;
        }
        if (strlen($data['book_barcode']) != 13){
            $errors['book_barcode'] = 'Invalid Barcode';
            return  $errors;
        }
        
        return $errors;
    }
    
}
