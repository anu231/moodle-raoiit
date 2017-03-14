<?php

require_once('../../config.php');
require_once('forms.php');
require_once('lib.php');

require_login();

global $DB, $PAGE;

$uploadform = new mod_raobooklet_upload_form();
$context = context_system::instance();
/**************** DO NOT MESS ****************/
if (empty($entry->id)) {
    $entry = new stdClass;
    $entry->id = 0;
}
$draftitemid = file_get_submitted_draft_itemid('attachments');
file_prepare_draft_area($draftitemid, $context->id, 'mod_raobooklet', 'uploads', $entry->id,
                        array('subdirs' => 0, 'maxbytes' => 100485760, 'maxfiles' => 50));
$entry->attachments = $draftitemid;
$uploadform->set_data($entry);
/**************** /DO NOT MESS ****************/


echo $OUTPUT->header();
echo $OUTPUT->heading($draftitemid);
if($data = $uploadform->get_data()){
    file_save_draft_area_files($data->attachments, $context->id, 'mod_raobooklet', 'uploads',
                   0, array('subdirs' => 0, 'maxbytes' =>100485760 , 'maxfiles' => 50));
    echo $OUTPUT->heading("Success");
} else {
    $uploadform->display();
}

echo $OUTPUT->footer();

