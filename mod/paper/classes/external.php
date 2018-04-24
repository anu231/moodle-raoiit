<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * URL external API
 *
 * @package    mod_paper
 * @category   external
 * @copyright  2015 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * URL external functions
 *
 * @package    mod_paper
 * @category   external
 * @copyright  2015 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
class mod_paper_external extends external_api {

    /**
     * Describes the parameters for get_papers_by_courses.
     *
     * @return external_function_parameters
     * @since Moodle 3.3
     */
    public static function get_papers_by_courses_parameters() {
        return new external_function_parameters (
            array(
                'courseids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'Course id'), 'Array of course ids', VALUE_DEFAULT, array()
                ),
            )
        );
    }

    /**
     * Returns a list of papers in a provided list of courses.
     * If no list is provided all papers that the user can view will be returned.
     *
     * @param array $courseids course ids
     * @return array of warnings and urls
     * @since Moodle 3.3
     */
    public static function get_papers_by_courses($courseids = array()) {

        $warnings = array();
        $returnedpapers = array();

        $params = array(
            'courseids' => $courseids,
        );
        $params = self::validate_parameters(self::get_urls_by_courses_parameters(), $params);

        $mycourses = array();
        if (empty($params['courseids'])) {
            $mycourses = enrol_get_my_courses();
            $params['courseids'] = array_keys($mycourses);
        }

        // Ensure there are courseids to loop through.
        if (!empty($params['courseids'])) {

            list($courses, $warnings) = external_util::validate_courses($params['courseids'], $mycourses);

            // Get the urls in this course, this function checks users visibility permissions.
            // We can avoid then additional validate_context calls.
            $urls = get_all_instances_in_courses("paper", $courses);
            foreach ($urls as $url) {
                $context = context_module::instance($url->coursemodule);
                // Entry to return.
                $url->name = external_format_string($url->name, $context->id);

                list($url->intro, $url->introformat) = external_format_text($url->intro,
                                                                $url->introformat, $context->id, 'mod_url', 'intro', null);
                $url->introfiles = external_util::get_area_files($context->id, 'mod_paper', 'intro', false, false);

                $returnedurls[] = $url;
            }
        }

        $result = array(
            'papers' => $returnedpapers,
            'warnings' => $warnings
        );
        return $result;
    }

    /**
     * Describes the get_urls_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_papers_by_courses_returns() {
        return new external_single_structure(
            array(
                'papers' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'coursemodule' => new external_value(PARAM_INT, 'Course module id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_RAW, 'Paper name'),
                            'intro' => new external_value(PARAM_RAW, 'Summary'),
                            'introformat' => new external_format_value('intro', 'Summary format'),
                            'introfiles' => new external_files('Files in the introduction text'),
                            'externalurl' => new external_value(PARAM_RAW_TRIMMED, 'External URL'),
                            'display' => new external_value(PARAM_INT, 'How to display the url'),
                            'displayoptions' => new external_value(PARAM_RAW, 'Display options (width, height)'),
                            'parameters' => new external_value(PARAM_RAW, 'Parameters to append to the URL'),
                            'timemodified' => new external_value(PARAM_INT, 'Last time the url was modified'),
                            'section' => new external_value(PARAM_INT, 'Course section id'),
                            'visible' => new external_value(PARAM_INT, 'Module visibility'),
                            'groupmode' => new external_value(PARAM_INT, 'Group mode'),
                            'groupingid' => new external_value(PARAM_INT, 'Grouping id'),
                        )
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function get_paper_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
                'cm_id' => new external_value(PARAM_INT, 'Course Module id')
                )
            );
    }

    /**
     * Returns the paper info of the requested paper
     *
     * @param array $courseids course ids
     * @return array of warnings and urls
     * @since Moodle 3.3
     */
    public static function get_paper($courseid, $cm_id) {
        global $USER, $DB;
        $warnings = array();
        $paper = array();

        $params = array(
            'courseid' => $courseid,
            'cm_id' => $cm_id
        );
        $params = self::validate_parameters(self::get_paper_parameters(), $params);

        $context = context_course::instance($params['courseid']);
        if (!is_enrolled($context, $USER->id, '', true)){
            //error
            return;
        }

        //get the paper entry
        $sql = <<<SQL
        select p.* from 
        {paper} as p join {course_modules} as cm
        on p.id = cm.instance
        where cm.id = ? and cm.course = ?
SQL;
        $paper_rec = $DB->get_records_sql($sql, array($params['cm_id'], $params['courseid']));
        if (empty($paper_rec)){
            return;
        }
        $paper_rec = $paper_rec[array_keys($paper_rec)[0]];
        $paper['id'] = $paper_rec->id;
        $paper['pid'] = $paper_rec->paperid;
        $paper['course'] = $paper_rec->course;
        $paper['name'] = $paper_rec->name;
        $paper['date'] = $paper_rec->date;
        $paper['standard'] = $paper_rec->standard;
        $paper['duration'] = $paper_rec->duration;
        $paper['syllabus'] = $paper_rec->syllabus;
        $paper['markingscheme'] = $paper_rec->markingscheme;
        $paper['instructions'] = $paper_rec->instructions;
        $paper['offline'] = $paper_rec->offline;
        $paper['stream'] = $paper_rec->stream;
        return $paper;
    }

    /**
     * Describes the get_urls_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_paper_returns() {
        return new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'pid' => new external_value(PARAM_INT, 'Paper Id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_TEXT, 'Paper name'),
                            'date' => new external_value(PARAM_RAW, 'Date'),
                            'standard' => new external_value(PARAM_TEXT, 'Standard'),
                            'duration' => new external_value(PARAM_RAW ,'Duration'),
                            'syllabus' => new external_value(PARAM_TEXT, 'Syllabus'),
                            'markingscheme' => new external_value(PARAM_RAW, 'MArking Scheme'),
                            'instructions' => new external_value(PARAM_TEXT, 'Instructions'),
                            'offline' => new external_value(PARAM_TEXT, 'Offline Paper'),
                            'stream' => new external_value(PARAM_TEXT, 'Stream')
                        )
        );
    }


    //////////////////////////////////////////////////////////////////////////////RESULT/////////////////////////////////////////
    
    public static function get_paper_result_parameters() {
        return new external_function_parameters (
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
                'cm_id' => new external_value(PARAM_INT, 'Course Module id')
                )
            );
    }

    /**
     * Returns the paper info of the requested paper
     *
     * @param array $courseids course ids
     * @return array of warnings and urls
     * @since Moodle 3.3
     */
    public static function get_paper_result($courseid, $cm_id) {
        global $USER, $DB;
        $warnings = array();
        $paper = array();

        $params = array(
            'courseid' => $courseid,
            'cm_id' => $cm_id
        );
        $params = self::validate_parameters(self::get_paper_parameters(), $params);

        $context = context_course::instance($params['courseid']);
        if (!is_enrolled($context, $USER->id, '', true)){
            //error
            return;
        }

        //get the paper entry
        $sql = <<<SQL
        select p.* from 
        {paper} as p join {course_modules} as cm
        on p.id = cm.instance
        where cm.id = ? and cm.course = ?
SQL;
        $paper_rec = $DB->get_records_sql($sql, array($params['cm_id'], $params['courseid']));
        if (empty($paper_rec)){
            return;
        }
        $paper_rec = $paper_rec[array_keys($paper_rec)[0]];
        
        return $paper;
    }

    /**
     * Describes the get_urls_by_courses return value.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function get_paper_result_returns() {
        return new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Module id'),
                            'pid' => new external_value(PARAM_INT, 'Paper Id'),
                            'course' => new external_value(PARAM_INT, 'Course id'),
                            'name' => new external_value(PARAM_TEXT, 'Paper name'),
                            'date' => new external_value(PARAM_RAW, 'Date'),
                            'standard' => new external_value(PARAM_TEXT, 'Standard'),
                            'duration' => new external_value(PARAM_RAW ,'Duration'),
                            'syllabus' => new external_value(PARAM_TEXT, 'Syllabus'),
                            'markingscheme' => new external_value(PARAM_RAW, 'MArking Scheme'),
                            'instructions' => new external_value(PARAM_TEXT, 'Instructions'),
                            'offline' => new external_value(PARAM_TEXT, 'Offline Paper'),
                            'stream' => new external_value(PARAM_TEXT, 'Stream')
                        )
        );
    }
}
