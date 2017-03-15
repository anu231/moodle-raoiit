<?php

class block_idcard extends block_base {
    public function init() {
        $this->title = get_string('idcard', 'block_idcard');
    }

    public function get_content() {
        global $CFG, $USER, $DB;
        if ($this->content !== null) {
            return $this->content;
        }

        ! $DB->record_exists('idcard', array('username'=>$USER->username)) ? $Chidden ='' : $Chidden = 'hidden';

        $this->content = new stdClass;
        $this->content->text = <<<HTML
        <div class="profile-wrapper">
            <img class="profile-pic" src="http://jennstrends.com/wp-content/uploads/2013/10/bad-profile-pic-2.jpeg" alt="Your Face Here">
        </div>
        <div class="info">
            <div>Name: <b>$USER->username</b></div>
            <div>RollNo: <b>$USER->id</b></div>
        </div>
        <div class="approve $Chidden">
            <p>Is this profile picture correct? 
            <a href="$CFG->wwwroot/blocks/idcard/action.php?approve=yes">Yes</a> / 
            <a href="$CFG->wwwroot/blocks/idcard/action.php?approve=no">No!</a>
            </p>
        </div>
        <style>
            .profile-wrapper{
                position: relative;
                display: inline-block;
                width: 80px;
                max-height: 100px;
                vertical-align: top;
                border: 1px solid rgba(0,0,0,0.3);
            }
            .info{
                display: inline-block;
                padding: 0px 0px 0px 10px;
            }
            .profile-pic{
                max-width: 100%;
                height: auto;
            }
            .approve.hidden {
                display: none;
            }
        </style>
HTML;
        return $this->content;

    }
}