<?php

/**
 * Define the complete raovideo structure for backup, with file and id annotations
 */     
class backup_raovideo_activity_structure_step extends backup_activity_structure_step {
 
    protected function define_structure() {
 
        // To know if we are including userinfo
        //$userinfo = $this->get_setting_value('userinfo');
 
        // Define each element separated
        $raovideo = new backup_nested_element('raovideo', array('id'), array(
            'name', 'intro', 'introformat', 'videoname', 'url', 'timecreated', 'timemodified'));
 
        /*$options = new backup_nested_element('options');
 
        $option = new backup_nested_element('option', array('id'), array(
            'text', 'maxanswers', 'timemodified'));
 
        $answers = new backup_nested_element('answers');
 
        $answer = new backup_nested_element('answer', array('id'), array(
            'userid', 'optionid', 'timemodified'));*/
        // Build the tree
 
        // Define sources
        $raovideo->set_source_table('raovideo', array('id' => backup::VAR_ACTIVITYID));
        // Define id annotations
 
        // Define file annotations
 
        // Return the root element (raovideo), wrapped into standard activity structure
        return $this->prepare_activity_structure($raovideo);
    }
}