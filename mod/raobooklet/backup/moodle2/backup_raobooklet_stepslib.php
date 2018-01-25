<?php

/**
 * Define the complete raobooklet structure for backup, with file and id annotations
 */     
class backup_raobooklet_activity_structure_step extends backup_activity_structure_step {
 
    protected function define_structure() {
 
        // To know if we are including userinfo
        //$userinfo = $this->get_setting_value('userinfo');
 
        // Define each element separated
        $raobooklet = new backup_nested_element('raobooklet', array('id'), array(
            'name', 'intro', 'introformat', 'bookletid', 'timecreated', 'timemodified'));
 
        /*$options = new backup_nested_element('options');
 
        $option = new backup_nested_element('option', array('id'), array(
            'text', 'maxanswers', 'timemodified'));
 
        $answers = new backup_nested_element('answers');
 
        $answer = new backup_nested_element('answer', array('id'), array(
            'userid', 'optionid', 'timemodified'));*/
        // Build the tree
 
        // Define sources
        $raobooklet->set_source_table('raobooklet', array('id' => backup::VAR_ACTIVITYID));
        // Define id annotations
 
        // Define file annotations
 
        // Return the root element (raobooklet), wrapped into standard activity structure
        return $this->prepare_activity_structure($raobooklet);
    }
}