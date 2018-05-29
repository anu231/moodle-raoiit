<?php

require_once('../../config.php');
require_once('locallib.php');
global $DB, $COURSE, $USER,$PAGE;

$id = required_param('id', PARAM_INT);
// $coursecontext = context_course::instance($COURSE->id);
// $usercontext = context_user::instance($USER->id);
//$PAGE->requires->js('/mod/paper/js/Chart.bundle.js');
//$PAGE->requires->js('/mod/paper/js/utils.js');
//$PAGE->requires->js('/mod/paper/js/myJS.js');


require_login();
// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('paper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $paper = $DB->get_record('paper', array('id'=>$cm->instance), '*', MUST_EXIST);
} else {
    error('Your must specify a course_module ID or an instance ID');
}


// Page setup
$PAGE->set_url('/mod/paper/view.php', array('id' => $id));
$PAGE->set_title(format_string("Paper"));
$PAGE->set_heading(format_string("Paper"));
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('mod_paper');

//$performance = get_performance($USER->username, $paper->paperid);
//$performance = get_performance(920471, 1601);
$performance = get_performance(807464,768);
$performance = format_performance($performance);

echo $output->header();
echo $output->paper($paper);

//comparison with highest
//chart - highest

$max_comaprison_series = array();
$self_comparison_series = array();
$comparison_labels = array();
foreach ($performance as $result){
            $renderable = new subject_performance($result);
            echo $output->render($renderable); 
            //charts
            //chart 1 - total, correct, wrong, unattempted
            $attempt_chart = new core\chart_bar();
            $attempt_chart->set_title('Students Performance in Paper');
            $attempt_chart->add_series(new core\chart_series('Total',array($result['nques'])));
            $attempt_chart->add_series(new core\chart_series('Correct',array($result['corr'])));
            $attempt_chart->add_series(new core\chart_series('Wrong',array($result['wrong'])));
            $attempt_chart->add_series(new core\chart_series('Unattempted',array($result['unattempt'])));
            $attempt_chart->get_xaxis(0, true)->set_label("Students Performance in ".$result['name']); 
            $attempt_chart->get_yaxis(0, true)->set_label("Students Performance in ".$result['name']);
            echo $OUTPUT->render($attempt_chart);
            //chart 2 - corr_percent, wrong_percent, unattempt_percent, corr_accuracy
            $percentage_chart = new core\chart_bar();
            $percentage_chart->set_title('Students Performance Analysis in Percentage');
            $percentage_chart->add_series(new core\chart_series('Correct',array($result['corr_percent'])));
            $percentage_chart->add_series(new core\chart_series('Wrong',array($result['wrong_percent'])));
            $percentage_chart->add_series(new core\chart_series('Unattempted',array($result['unattempt_percent'])));
            $percentage_chart->add_series(new core\chart_series('Accuracy In Solving',array($result['corr_accuracy'])));
            $percentage_chart->get_yaxis(0, true)->set_label($result['name']." Performance Analysis in Percentage");
            $percentage_chart->get_yaxis(0, true)->set_label($result['name']." Performance Analysis in Percentage");
            echo $OUTPUT->render($percentage_chart);

            //chart 3 - obt, marks_correct, negmarks
            $marks_analysis_chart = new core\chart_bar();
            $marks_analysis_chart->set_title('Students Marks Analysis');
            $marks_analysis_chart->add_series(new core\chart_series('Positive Marks',array($result['marks_correct'])));
            $marks_analysis_chart->add_series(new core\chart_series('Negative Marks',array($result['negmarks'])));
            $marks_analysis_chart->add_series(new core\chart_series('Marks Obtained',array($result['obt'])));
            $marks_analysis_chart->get_yaxis(0, true)->set_label($result['name']." Marks Analysis");
            $marks_analysis_chart->get_yaxis(0, true)->set_label($result['name']." Marks Analysis");
            echo $OUTPUT->render($marks_analysis_chart);


            array_push($max_comaprison_series, $result['max_obt']);
            array_push($self_comparison_series, $result['obt']);
            array_push($comparison_labels, $result['name']);
}
$comparison_chart = new core\chart_bar();
$comparison_chart->set_title('Highest and Self Comparison Chart');
$comparison_chart->add_series(new core\chart_series('Highest', $max_comaprison_series));
$comparison_chart->add_series(new core\chart_series('Self', $self_comparison_series));
$comparison_chart->set_labels($comparison_labels);
echo $OUTPUT->render($comparison_chart);
    
echo $output->footer();
//chart//

//chart//



