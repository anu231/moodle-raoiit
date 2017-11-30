<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/raolib.php");
require_once('../branchadmin/locallib.php');
require_once('locallib.php');

class add_books_form extends moodleform {
    //Add elements to form
    function definition(){

        $mform =& $this->_form;
        $mform->addElement('text', 'book_name', get_string('book_name', 'block_library'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'volume', get_string('volume', 'block_library'));
        $mform->setType('volume', PARAM_INT); 
        $mform->addElement('text', 'publisher', get_string('publisher', 'block_library'));
        $mform->setType('publisher', PARAM_TEXT);
        $mform->addElement('text', 'author', get_string('author', 'block_library'));
        $mform->setType('author', PARAM_TEXT);
        $mform->addElement('text', 'price', get_string('price', 'block_library'));
        $mform->setType('price', PARAM_INT);
        $mform->addElement('text', 'barcode', get_string('barcode', 'block_library'),'maxlength="13" ');
        $mform->setType('barcode', PARAM_INT);
        $center_list = convert_std_to_array(get_centers());
        $mform->addElement('select', 'branch', get_string('branch', 'block_library'), $center_list);
        $options = array('1' => 'Available','0' => 'Lost');
        $select = $mform->addElement('select', 'status', get_string('status', 'block_library'), $options);
        $select->setSelected('1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Add Books");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}

class issue_book_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'book_barcode', "barcode for the book");
        $mform->setType('book_barcode', PARAM_TEXT);
        $mform->addElement('text', 'student_username', "Student Username who is issuing the book");
        $mform->setType('student_username', PARAM_INT); 
        
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'status', '', get_string('issue', 'block_library'), 0);
        $radioarray[] = $mform->createElement('radio', 'status', '', get_string('return', 'block_library'), 1);
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        $mform->setDefault('status', 0);

        $this->add_action_buttons(true,'Issue Book');
        
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER;
        $book = $DB->get_record('lib_bookmaster', array("status"=>1,"barcode"=>$data['book_barcode']));
        if (empty($book)){
            $errors['book_barcode'] = 'Book does not exist in the system or is damaged/lost';
            return  $errors;
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
                //$curr_date.'-'.$return_date;
                $next_issue_date = date('Y-m-d', strtotime($return_date.'7 days'));
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