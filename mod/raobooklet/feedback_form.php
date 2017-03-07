<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");

class mod_raobooklet_feedback_form extends moodleform {
    function definition() {
        $mform =& $this->_form;

        $RATINGS = array(
            "1" => "Disliked it",
            "2" => "Could be improved",
            "3" => "Satisfied",
            "4" => "Loved it",
            "5" => "Amazing!"
        );
        $rating = $mform->addElement('select', 'rating', 'Rating', $RATINGS);
        $rating->setSelected(3);
        
        $mform->addElement('textarea', 'comment','Comment', 'rows="8" cols="55" max-cols="70"');

        $this->add_action_buttons();
    }
}