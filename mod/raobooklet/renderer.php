<?php

class mod_raobooklet_renderer extends plugin_renderer_base {
    public function booklet_selector() {
        $booklet_selector = new booklet_selector();
        return $this->render($booklet_selector);
    }

    public function render_booklet_selector($bs) {
         return $this->render_from_template('mod_raobooklet/booklet_selector', $bs);
    }

    public function booklet_info($bookletid, $instanceid) {
        $booklet_info = new booklet_info($bookletid, $instanceid);
        return $this->render($booklet_info);
    }

    public function render_booklet_info($booklet_info) {
         return $this->render_from_template('mod_raobooklet/booklet', $booklet_info);
    }

    public function booklet_feedback($feedback) {
        $booklet_feedback = new booklet_feedback($feedback);
        return $this->render($booklet_feedback);
    }

    public function render_booklet_feedback($booklet_feedback) {
         return $this->render_from_template('mod_raobooklet/booklet_feedback', $booklet_feedback);
    }
}

class booklet_selector implements renderable{
    public function __construct(){
        $this->booklets = $this->get_booklets();
    }

    private function get_booklets(){
        global $DB, $CFG;
        $files; // Raw file records
        $booklets; // booklet_info
        $processedinfo = array(); // return array
        
        $files = $DB->get_records('files', array('component'=>'mod_raobooklet'));
        $booklets = $DB->get_records('raobooklet_info');

        $counter = 1;
        foreach ($files as $file) {
            if($file->filename == '.' ) continue; // Filter out invalid filenames
            $index = array_search($file->filename, array_column($booklets, 'name'));
            $tmp = array();
            $tmp['index'] = $counter;
            $tmp['id'] = $file->id;
            $tmp['name'] = $file->filename;
            $tmp['isNew'] = is_bool($index) ? 'yes' : 'no'; // $index will be an int or false.
            $tmp['href'] = $CFG->wwwroot."/mod/raobooklet/edit.php?fileid=$file->id";

            $processedinfo[] = $tmp;
            $counter++;
        }
        return $processedinfo;
    }
}

class booklet_info implements renderable{
    public function __construct($bookletid, $instanceid){
        global $CFG, $DB, $USER;
        $booklet = $DB->get_record('raobooklet_info', array('bookletid'=>$bookletid));
        if(!$booklet){
            $this->name = "Sorry. Booklet not found";
            return;
        }
        $this->name = $booklet->name;
        $this->subject = $booklet->subject;
        $this->topics = $booklet->topics;
        $this->standard = $booklet->standard;

        // Hyperlinks to gallery
        $this->bookletid = $bookletid;
        $this->instance = $instanceid;
        // $this->back_link = $raobooklet->back_link;
    }
}

class booklet_feedback implements renderable{
    public function __construct($feedback){
        $this->id = $feedback->id;
        $this->rating = $feedback->rating;
        $this->comment = $feedback->comment;
        $this->timestamp = date('l jS \of F Y h:i:s A', $feedback->timecreated);
        $this->updatelink = $feedback->updatelink;
    }
}