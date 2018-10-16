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
        $this->vid_id;
        $get_videos = $DB->get_record('raovideo',array("id"=>$this->vid_id));
        $get_videos_array = array('url'=>get_akamai_token($get_videos->url));
         /*foreach($get_videos as $video){
             $get_videos_array[] = $video;
         }*/
         //var_dump($get_videos_array);
         return $get_videos_array;
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_raovideo();
        return $data;
    }
}
