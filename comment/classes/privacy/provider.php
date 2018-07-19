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
 * Privacy class for requesting user data.
 *
 * @package    core_comment
 * @copyright  2018 Adrian Greeve <adrian@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_comment\privacy;

defined('MOODLE_INTERNAL') || die();

use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\transform;

/**
 * Privacy class for requesting user data.
 *
 * @package    core_comment
 * @copyright  2018 Adrian Greeve <adrian@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\subsystem\plugin_provider {

    /**
     * Returns meta data about this system.
     *
     * @param   collection     $collection The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_database_table('comments', [
                'content' => 'privacy:metadata:comment:content',
                'timecreated' => 'privacy:metadata:comment:timecreated',
                'userid' => 'privacy:metadata:comment:userid',
            ], 'privacy:metadata:comment');

        return $collection;
    }

    /**
     * Writes user data to the writer for the user to download.
     *
<<<<<<< HEAD
     * @param  array  $context Contexts to run through and return data.
=======
     * @param  \context $context The context to export data for.
>>>>>>> v3.5.1
     * @param  string $component The component that is calling this function
     * @param  string $commentarea The comment area related to the component
     * @param  int    $itemid An identifier for a group of comments
     * @param  array  $subcontext The sub-context in which to export this data
     * @param  bool   $onlyforthisuser  Only return the comments this user made.
     */
<<<<<<< HEAD
    public static function export_comments($context, $component, $commentarea, $itemid, $subcontext, $onlyforthisuser = true) {

        $data = new \stdClass;
        $data->context   = $context;
        $data->area      = $commentarea;
        $data->itemid    = $itemid;
        $data->component = $component;

        $commentobject = new \comment($data);
        $commentobject->set_view_permission(true);
        $comments = $commentobject->get_comments(0);
        $subcontext[] = get_string('commentsubcontext', 'core_comment');

        $comments = array_filter($comments, function($comment) use ($onlyforthisuser) {
            global $USER;

            return (!$onlyforthisuser || $comment->userid == $USER->id);
        });

        $comments = array_map(function($comment) {
            return (object) [
                'content' => $comment->content,
                'time' => transform::datetime($comment->timecreated),
                'userid' => transform::user($comment->userid),
            ];
        }, $comments);

        if (!empty($comments)) {
=======
    public static function export_comments(\context $context, string $component, string $commentarea, int $itemid,
                                           array $subcontext, bool $onlyforthisuser = true) {
        global $USER, $DB;
        $params = [
            'contextid' => $context->id,
            'component' => $component,
            'commentarea' => $commentarea,
            'itemid' => $itemid
        ];
        $sql = "SELECT c.id, c.content, c.format, c.timecreated, c.userid
                  FROM {comments} c
                 WHERE c.contextid = :contextid AND
                       c.commentarea = :commentarea AND
                       c.itemid = :itemid AND
                       (c.component IS NULL OR c.component = :component)";
        if ($onlyforthisuser) {
            $sql .= " AND c.userid = :userid";
            $params['userid'] = $USER->id;
        }
        $sql .= " ORDER BY c.timecreated DESC";

        $rs = $DB->get_recordset_sql($sql, $params);
        $comments = [];
        foreach ($rs as $record) {
            if ($record->userid != $USER->id) {
                // Clean HTML in comments that were added by other users.
                $comment = ['content' => format_text($record->content, $record->format, ['context' => $context])];
            } else {
                // Export comments made by this user as they are stored.
                $comment = ['content' => $record->content, 'contentformat' => $record->format];
            }
            $comment += [
                'time' => transform::datetime($record->timecreated),
                'userid' => transform::user($record->userid),
            ];
            $comments[] = (object)$comment;
        }
        $rs->close();

        if (!empty($comments)) {
            $subcontext[] = get_string('commentsubcontext', 'core_comment');
>>>>>>> v3.5.1
            \core_privacy\local\request\writer::with_context($context)
                ->export_data($subcontext, (object) [
                    'comments' => $comments,
                ]);
        }
    }

    /**
<<<<<<< HEAD
     * Deletes all comments for a specified context.
     *
     * @param  \context $context Details about which context to delete comments for.
     */
    public static function delete_comments_for_all_users_in_context(\context $context) {
        global $DB;
        $DB->delete_records('comments', ['contextid' => $context->id]);
=======
     * Deletes all comments for a specified context, component, and commentarea.
     *
     * @param  \context $context Details about which context to delete comments for.
     * @param  string $component Component to delete.
     * @param  string $commentarea Comment area to delete.
     * @param  int $itemid The item ID for use with deletion.
     */
    public static function delete_comments_for_all_users(\context $context, string $component, string $commentarea = null,
            int $itemid = null) {
        global $DB;
        $params = [
            'contextid' => $context->id,
            'component' => $component
        ];
        if (isset($commentarea)) {
            $params['commentarea'] = $commentarea;
        }
        if (isset($itemid)) {
            $params['itemid'] = $itemid;
        }
        $DB->delete_records('comments', $params);
    }

    /**
     * Deletes all comments for a specified context, component, and commentarea.
     *
     * @param  \context $context Details about which context to delete comments for.
     * @param  string $component Component to delete.
     * @param  string $commentarea Comment area to delete.
     * @param  string $itemidstest an SQL fragment that the itemid must match. Used
     *      in the query like WHERE itemid $itemidstest. Must use named parameters,
     *      and may not use named parameters called contextid, component or commentarea.
     * @param array $params any query params used by $itemidstest.
     */
    public static function delete_comments_for_all_users_select(\context $context, string $component, string $commentarea,
            $itemidstest, $params = []) {
        global $DB;
        $params += ['contextid' => $context->id, 'component' => $component, 'commentarea' => $commentarea];
        $DB->delete_records_select('comments',
            'contextid = :contextid AND component = :component AND commentarea = :commentarea AND itemid ' . $itemidstest,
            $params);
>>>>>>> v3.5.1
    }

    /**
     * Deletes all records for a user from a list of approved contexts.
     *
     * @param  \core_privacy\local\request\approved_contextlist $contextlist Contains the user ID and a list of contexts to be
     * deleted from.
<<<<<<< HEAD
     */
    public static function delete_comments_for_user(\core_privacy\local\request\approved_contextlist $contextlist) {
=======
     * @param  string $component Component to delete from.
     * @param  string $commentarea Area to delete from.
     * @param  int $itemid The item id to delete from.
     */
    public static function delete_comments_for_user(\core_privacy\local\request\approved_contextlist $contextlist,
            string $component, string $commentarea = null, int $itemid = null) {
>>>>>>> v3.5.1
        global $DB;

        $userid = $contextlist->get_user()->id;
        $contextids = implode(',', $contextlist->get_contextids());
<<<<<<< HEAD
        $params = ['userid' => $userid];
        list($insql, $inparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);
        $params += $inparams;

        $select = "userid = :userid and contextid $insql";
=======
        $params = [
            'userid' => $userid,
            'component' => $component,
        ];
        $areasql = '';
        if (isset($commentarea)) {
            $params['commentarea'] = $commentarea;
            $areasql = 'AND commentarea = :commentarea';
        }
        $itemsql = '';
        if (isset($itemid)) {
            $params['itemid'] = $itemid;
            $itemsql = 'AND itemid = :itemid';
        }
        list($insql, $inparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);
        $params += $inparams;

        $select = "userid = :userid AND component = :component $areasql $itemsql AND contextid $insql";
>>>>>>> v3.5.1
        $DB->delete_records_select('comments', $select, $params);
    }
}
