<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");

class local_raomanager_notification_form extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;


        // Method - sms/email
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'medium', '', 'SMS ', 0);
        $radioarray[] = $mform->createElement('radio', 'medium', '', '  e-mail', 1);
        $mform->addGroup($radioarray, 'radioar', 'Medium', array(' '), false);

        // Parent / Student with Parent
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'recipient', '', 'Student only ', 0);
        $radioarray[] = $mform->createElement('radio', 'recipient', '', '  Student with their parent', 1);
        $mform->addGroup($radioarray, 'radioar', 'Recipients', array(' '), false);
        

    // Filters
        // Target year
        $mform->addElement('header', 'headertargetyear', 'Target Year');
        $mform->addElement('checkbox', 'alltargets', "Send to all target years");
        $years = $mform->addElement('select', 'years', 'Select Target Years (Use ctrl+click for multiple selection/de-selection)', array(
            2017 => '2017', 2018 => '2018', 2019 => '2019', 2020 => '2020',
            2021 => '2021', 2022 => '2022', 2023 => '2023', 2024 => '2024',
            2025 => '2025', 2026 => '2026', 2027 => '2027',
        ), "style=height:16em");
        $years->setMultiple(true);

        // CENTERS  Multi-Select
        $mform->addElement('header', 'headercenters', 'Centers');
        $mform->addElement('checkbox', 'allcenters', "Send to All centers");
        $CENTERS = $DB->get_records_menu('raomanager_centers');
        $centers = $mform->addElement('select', 'centers', 'Select Centers (Use ctrl+click for multiple selection/de-selection)',$CENTERS, "style=height:25em");
        $centers->setMultiple(true);
        
        
        // Batches Multi-Select
        $mform->addElement('header', 'headerbatches', 'Batches');
        $mform->addElement('checkbox', 'allbatches', "Send to All Batches");
        $BATCHES = $DB->get_records_menu('raomanager_batches', null, $sort='batch', 'id, batch');
        $Cheight = sizeof($BATCHES) >= 12 ? 12 : sizeof($BATCHES) + 3; // Set Height of select form
        $batches = $mform->addElement('select', 'batches', 'Select Batches (Use ctrl+click for multiple selection)', $BATCHES, "style=height:".$Cheight."em");
        $batches->setMultiple(true);


    // SMS Body textarea
        $mform->addElement('header', 'headersms', 'SMS Message');
        $mform->addElement('textarea', 'smsbody', "Message body (*160 characters)", 'wrap="virtual" rows="3" cols="70" maxlength="160"');
        $mform->setType('smsbody', PARAM_RAW);

    // E-mail Subject and Body textareas
        $mform->addElement('header', 'headeremail', 'Email Subject and Body');
        $mform->addElement('textarea', 'emailsubject', "Email Subject", 'wrap="virtual" rows="2" cols="70" maxlength="160"');
        $mform->addElement('textarea', 'emailbody', "Email Body", 'rows="14" cols="70"');
        $mform->setType('emailsubject', PARAM_RAW);
        $mform->setType('emailbody', PARAM_RAW);

    // Enable either email or sms textareas
        $mform->disabledif('smsbody', 'medium', 'eq', '1');
        $mform->disabledif('emailsubject', 'medium', 'eq', '0');
        $mform->disabledif('emailbody', 'medium', 'eq', '0');


        $this->add_action_buttons(true, "Send Notification!");
    }
}