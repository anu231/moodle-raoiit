<?php

defined('MOODLE_INTERNAL') || die();


class block_branchadmin_observer {

    public static function usercreated(\core\event\base $event){
        print_r($event);
        echo 'helllloooo anurag';
    }

}