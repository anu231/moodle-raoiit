<?php

class mod_raovideo_renderer extends plugin_renderer_base {
    public function view_raovideo() {
        $data = $page->export_for_template($this);
        return parent::render_from_template('mod_raovideo/view_raovideo', $data); 
    }
}
class view_raovideo implements renderable, templatable {
    
    private function get_raovideo(){
        global $USER, $DB;
       
        //return $get_raovideo;
    }

    public function export_for_template(renderer_base $output){
        $data = $this->get_raovideo();
        return $data;
    }
}
