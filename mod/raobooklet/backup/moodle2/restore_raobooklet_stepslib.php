<?php

class restore_raobooklet_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        //$userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('raobooklet', '/activity/raobooklet');
        //$paths[] = new restore_path_element('raobooklet_option', '/activity/raobooklet/options/option');
        /*if ($userinfo) {
            $paths[] = new restore_path_element('raobooklet_answer', '/activity/raobooklet/answers/answer');
        }
        */
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_raobooklet($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        //$data->timeclose = $this->apply_date_offset($data->timeclose);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the raobooklet record
        $newitemid = $DB->insert_record('raobooklet', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }
}