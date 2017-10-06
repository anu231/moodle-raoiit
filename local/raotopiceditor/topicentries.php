<?php

require_once('../../config.php');
require_once('renderer.php');

global $PAGE, $CFG;
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/raotopiceditor/topicentries.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Rao Topic Editor');

$topicid = required_param('topic',PARAM_INT);
$output = $PAGE->get_renderer('local_raotopiceditor');
$renderable = new topic_entries($topicid);

echo $output->header();
echo $output->render($renderable);
echo $output->footer();