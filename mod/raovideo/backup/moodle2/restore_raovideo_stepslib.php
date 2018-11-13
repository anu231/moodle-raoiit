<?php

class restore_raovideo_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        //$userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('raovideo', '/activity/raovideo');
        //$paths[] = new restore_path_element('raovideo_option', '/activity/raovideo/options/option');
        /*if ($userinfo) {
            $paths[] = new restore_path_element('raovideo_answer', '/activity/raovideo/answers/answer');
        }
        */
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_raovideo($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        //$data->timeclose = $this->apply_date_offset($data->timeclose);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the raovideo record
        $newitemid = $DB->insert_record('raovideo', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }
}