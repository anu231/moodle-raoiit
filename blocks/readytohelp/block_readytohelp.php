<?php
// require('locallib.php');

class block_readytohelp extends block_list {
    public function init() {
        $this->title = get_string('readytohelp', 'block_readytohelp');
    }

    public function get_content() {
        global $CFG, $COURSE;
        if ($this->content !== null) {
        return $this->content;
        }
        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons  = array();
        $this->content->items[] = array();
        if(local_raomanager_has_permission('ReadyToHelp')){
                $this->content->items[0] = html_writer::tag('a','Review Responses',
                    array('href'=>"$CFG->wwwroot/blocks/readytohelp/review_response.php?cid=$COURSE->id"));
        } else {
            $url = new moodle_url('/blocks/readytohelp/raise.php', array('blockid' => $this->instance->id));
            $this->content->items[0] = html_writer::link($url, get_string('addgrievance', 'block_readytohelp'));
            $this->content->items[1] = html_writer::tag('a','Show Raised Grievances',
                                                        array('href'=>"$CFG->wwwroot/blocks/readytohelp/list.php"));
        }

        return $this->content;
    }


    // Configuration
    function has_config() { return false; }

}