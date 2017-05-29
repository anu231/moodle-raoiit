<?php
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
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
class mod_raobooklet_external extends external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function list_booklet_parameters() {
        return new external_function_parameters(
                array('courseid' => new external_value(PARAM_INT, 'Course whose booklets need to be listed', VALUE_REQUIRED))
        );
    }
    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function list_booklet($courseid) {
        global $USER, $DB;
        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::list_booklet_parameters(),
                array('courseid' => $courseid));
        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);
        //Capability checking
        //OPTIONAL but in most web service it should present
        /*if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }*/
        //check if user is enrolled in the specified course
        $context = context_course::instance($courseid, IGNORE_MISSING);
        try {
            self::validate_context($context);
        } catch (Exception $e) {
            $exceptionparam = new stdClass();
            $exceptionparam->message = $e->getMessage();
            $exceptionparam->courseid = $course->id;
            throw new moodle_exception('errorcoursecontextnotvalid', 'webservice', '', $exceptionparam);
        }
        //fetch all booklets in the specified course
        $booklet_records = $DB->get_records('raobooklet',array());
        return json_encode((array)$booklet_records) ;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function list_booklet_returns() {
        return new external_value(PARAM_TEXT, 'List of booklets availbale in the specified course');
    }
}