<?php

class local_raomanager_renderer extends plugin_renderer_base {
    public function batch_info() {
        $batch_info = new batch_info();
        return $this->render($batch_info);
    }

    public function render_batch_info($info){
        return $this->render_from_template('local_raomanager/batchinfo', $info);
    }

    public function error($code) {
        switch ($code) {
            case 1:
                $message = "Couldn't Create a New Batch (Error 1)";
                break;
            case 2:
                $message = "Couldn't Save Changes to batch (Error 2)";
                break;
            case 3:
                $message = "Couldn't Delete the Batch (Error 3)";
                break;
            default:
                $message = '';
                break;
        }
        return $this->render_from_template('local_raomanager/error', $message);
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
                'edit_link' => "batches.php?action=edit&batchid=$batch->id",
                'delete_link' => "batches.php?action=delete&batchid=$batch->id"
            );
            $processed_batches[] = $tmp;
            $counter++;
        }
        return $processed_batches;
    }
}