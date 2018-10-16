<?php
require_once('locallib.php');
class mod_raovideo_renderer extends plugin_renderer_base {
    
    public function render_view_raovideo($page){
        $data = array();
        $data['get_videos'] = $page->export_for_template($this);
        return $this->render_from_template('mod_raovideo/view_raovideo', $data);
    }
}
class view_raovideo implements renderable, templatable {
    var $vid_id = null;

    public function __construct($vid_id){
        $this->vid_id = $vid_id;
    }
   
    private function get_raovideo(){
        global $USER, $DB,$CFG;
        return array('url'=>get_video_akamai($this->vid_id));
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_raovideo();
        return $data;
    }
}
