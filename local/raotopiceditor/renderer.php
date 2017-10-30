<?php
// Standard GPL and phpdocs
 
defined('MOODLE_INTERNAL') || die;                                                                                                  
require_once('../../config.php');
require_once('locallib.php');


class local_raotopiceditor_renderer extends plugin_renderer_base {

    public function render_topics($page){
        $data = array();
        $data['topics'] = $page->export_for_template($this);
        return $this->render_from_template('local_raotopiceditor/topics', $data);
    }

    public function render_topic_entries($page){
        $data = array();
        $data['entries'] = $page->export_for_template($this);
        return $this->render_from_template('local_raotopiceditor/topic_entries', $data);
    }
}

class topics implements renderable, templatable {

    public function export_for_template(renderer_base $output){
        global $CFG;
        $topic_list = Array();
        $topics = list_topics();
        foreach($topics as $top){
            $top->subject = $CFG->SUBJECTS[$top->subject];
            $top->edit_url = $CFG->wwwroot.'/local/raotopiceditor/topicentries.php?topic='.$top->id;
            $topic_list[] = $top;
        }
        return $topic_list;
    }
}

class topic_entries implements renderable, templatable {

    public function __construct($id){
        $this->topicid = $id;
    }

    public function export_for_template(renderer_base $output){
        $entries = get_topic_entries($this->topicid);
        $entry_list = Array();
        foreach($entries as $entry){
            $entry_list[] = $entry;
        }
        return $entry_list;
    }

}