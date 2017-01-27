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
 * CUL Activity Stream event handlers
 *
 * @package    local
 * @subpackage culactivity_stream
 * @copyright  2013 Amanda Doughty <amanda.doughty.1@city.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

//require_once(dirname(__FILE__).'/../../config.php');


defined('MOODLE_INTERNAL') || die();
//require_once('../../../vendor/autoload.php');
//require_once('../../../vendor/rmccue/requests/library/Requests.php');
/**
 * Event observer.
 *
 * Responds to course module events emitted by the Moodle event manager.
 */
class local_culactivity_stream_observer {
    /**
     * Course module created.
     *
     * @param \core\event\course_module_created $event The event that triggered our execution.
     *
     * @return void
     */
    public static function course_module_created(\core\event\course_module_created $event) {
        static::schedule_notification($event);
    }

    /**
     * Course module updated.
     *
     * @param \core\event\course_module_updated $event The event that triggered our execution.
     *
     * @return void
     */
    public static function course_module_updated(\core\event\course_module_updated $event) {
        static::schedule_notification($event);
    }

    public static function user_creation_event(\core\event\user_created $event){
        //require_once('/var/www/moodle/config.php');
        require_once('/var/www/moodle/vendor/autoload.php');
        require_once('/var/www/moodle/vendor/rmccue/requests/library/Requests.php');
        $created_id = $event->get_data()['relateduserid'];
        global $DB;
        global $CFG;
        $user_data = $DB->get_record('user',array('id'=>$created_id));
        $post_data = array('username'=>$user_data->username,'email'=>$user_data->email,'fname'=>$user_data->firstname,'lname'=>$user_data->lastname,'passwd'=>$user_data->password);
        $response = Requests::post($CFG->django_server.'mdl_create_user',array(),$post_data);
        if ($response->body=='1'){
            //success
        }
    }
    public static function user_deletion_event(\core\event\user_deleted $event){
        require_once('/var/www/moodle/vendor/autoload.php');
        require_once('/var/www/moodle/vendor/rmccue/requests/library/Requests.php');
        $deleted_id = $event->get_data()['relateduserid'];
        $response = Requests::post($GLOBAL['CFG']->django_server.'mdl_delete_user?id='.$deleted_id);
        if ($response->body=='1'){
            //success
        }
    }



    /**
     * Event handler.
     *
     * Called by observers to handle notification sending.
     *
     * @param \core\event\base $event The event object.
     *
     * @return boolean true
     *
     */
    protected static function schedule_notification(\core\event\base $event) {
            global $CFG, $DB;

            $course = $DB->get_record('course', array('id' => $event->courseid));
            $module = $event->other['modulename'];
            $modulename = $event->other['name'];
            $messagetext = get_string($event->action, 'local_culactivity_stream', $modulename);
            $coursename = $course->idnumber ? $course->idnumber : $course->fullname;
            $messagetext .= get_string('incourse', 'local_culactivity_stream', $coursename);

            $message = new stdClass();
            $message->userfromid = $event->userid;
            $message->courseid = $event->courseid;
            $message->cmid = $event->objectid;
            $message->smallmessage     = $messagetext;
            $message->component = 'local_culactivity_stream';
            $message->modulename = $module;
            $message->timecreated = time();
            $message->contexturl = "$CFG->wwwroot/mod/$module/view.php?id=$event->objectid";
            $message->contexturlname  = $modulename;

            // Add base message to queue - message_culactivity_queue.
            $result = $DB->insert_record('message_culactivity_stream_q', $message);

            return $result;
    }
}
