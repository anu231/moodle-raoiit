<?php

class local_raomanager_renderer extends plugin_renderer_base {
    public function batch_info() {
        $batch_info = new batch_info();
        return $this->render($batch_info);
    }

    public function render_batch_info($info){
        return $this->render_from_template('local_raomanager/batchinfo', $info);
    }

    public function render_dialog($dialog) {
        return $this->render_from_template('local_raomanager/dialog', $dialog);
    }

    public function admin_info(){
        $admin_info = new admin_info();
        return $this->render($admin_info);
    }

    public function render_admin_info($info){
        return $this->render_from_template('local_raomanager/admininfo', $info);
    }

    public function center_info(){
        $center_info = new center_info();
        return $this->render($center_info);
    }

    public function render_center_info($info){
        return $this->render_from_template('local_raomanager/centerinfo', $info);
    }
}

// Display feedback dialog box
class dialog implements renderable {
    public function __construct($message, $code=null) {
        $this->status = $code == 0 ? 'success' : 'failure';
        $this->message = $message;
    }
}

class batch_info implements renderable {
    public function __construct(){
        $this->batches = $this->get_batches();
    }

    private function get_batches(){
        global $DB, $CFG;
        $processed_batches = array();
        $batches = $DB->get_records('raomanager_batches', array());
        $counter = 1;
        foreach ($batches as $batch) {
            $tmp = array(
                'index' => $counter,
                'id' => $batch->id,
                'batch' => $batch->batch,
                'edit_link' => "index.php?action=edit&batchid=$batch->id",
                'delete_link' => "index.php?action=delete&batchid=$batch->id"
            );
            $center = $DB->get_record('raomanager_centers', array('id' => $batch->centerid));
            $tmp['centername'] = $center->name;
            $processed_batches[] = $tmp;
            $counter++;
        }
        return $processed_batches;
    }
}

class admin_info implements renderable {
    public function __construct() {
        global $DB, $CFG;
        $processed_admins = array();
        $admins = $DB->get_records('raomanager_admins', array());
        if($admins){
            $counter = 1;
            foreach ($admins as $admin) {
                $tmp = array(
                    "index" => $counter,
                    "admin" => $admin->username,
                    "plugin" => $admin->pluginname,
                    'edit_link' => "index.php?action=edit&id=$admin->id",
                    'delete_link' => "index.php?action=delete&id=$admin->id"
                );
                $processed_admins[] = $tmp;
                $counter++;
            }
        }
        $this->admins = $processed_admins;
    }
}

class center_info implements renderable {
    public function __construct() {
        global $DB, $CFG;
        $processed_centers = array();
        $centers = $DB->get_records('raomanager_centers', array());
        if($centers){
            $counter = 1;
            foreach ($centers as $center) {
                $tmp = array(
                    "index" => $counter,
                    "centername" => $center->name,
                    "zonename" => $center->zone,
                    'edit_link' => "index.php?action=edit&centerid=$center->id",
                    'delete_link' => "index.php?action=delete&centerid=$center->id"
                );
                $processed_centers[] = $tmp;
                $counter++;
            }
        }
        $this->centers = $processed_centers;
    }
}
