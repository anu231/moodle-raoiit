<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
 
class branchadmin_sms_form extends moodleform {
    //Add elements to form
function definition(){
    $mform =& $this->_form;
    $mform->addElement('text', 'roll_number', get_string('enroll','block_branchadmin'), 'maxlength="25" size="50" ');
    $mform->addRule('roll_number', null, 'required', null, 'client');
$mform->setType('roll_number', PARAM_TEXT);
    global $CFG,$USER,$DB;
    $sql='SELECT name FROM mdl_raomanager_centers ORDER BY name DESC';
    $users_by_branch = $DB->get_records_sql($sql,array($user->profile_field_center));
//echo"<pre>";print_r($users_by_branch);
    $user_list = array(); 
      
        foreach ($users_by_branch as $user){
            $temp = array();
            $temp['center'] = $user->name; //var_dump($temp['center']);
            $user_list[] = $temp;
           // var_dump($user_list);
             
        }
        
$options = array
             (
                 $temp['center'] = $user->name,
             );

$select = $mform->addElement('select', 'select_branch', get_string('select_branch', 'block_branchadmin'), $options);
// This will select the colour blue.
$select->setSelected('0000ff');



 // Set default value by using a passed parameter
    $mform->setDefault('email',$this->_customdata['email']);
    $mform->addElement('submit', 'submit', get_string('submit'));   
    $batches = get_batches_for_user(get_user_center());

    }

}