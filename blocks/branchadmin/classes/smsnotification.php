<?php

defined('MOODLE_INTERNAL') || die();
require_once('../locallib.php');

class block_branchadmin_smsnotification extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $usernames = $data->usernames;
        $msg = $data->message;
        foreach($usernames as $user){
            $mob_nos = fetch_numbers_for_username($user);
            
        }
    }
}