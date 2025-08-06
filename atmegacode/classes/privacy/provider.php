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
 * Privacy provider for assignsubmission_atmegacode.
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_atmegacode\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;
use core_privacy\local\request\contextlist;
use mod_assign\privacy\assign_plugin_request_data;

/**
 * Privacy provider class for ATmega code submission plugin.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \mod_assign\privacy\assignsubmission_provider,
    \mod_assign\privacy\assignsubmission_user_provider {
    /**
     * Returns metadata about the plugin's data storage.
     *
     * @param collection $collection The metadata collection.
     * @return collection Updated collection.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('assignsubmission_atmegacode', [
            'assignment' => 'privacy:metadata:assignmentid',
            'submission' => 'privacy:metadata:submissionid',
            'filename' => 'privacy:metadata:filename',
            'timemodified' => 'privacy:metadata:timemodified',
        ], 'privacy:metadata:tablepurpose');

        $collection->add_database_table('atmega_flags', [
            'submissionid' => 'privacy:metadata:submissionid',
            'warnings' => 'privacy:metadata:warnings',
            'timecreated' => 'privacy:metadata:timemodified',
        ], 'privacy:metadata:flagstablepurpose');

        $collection->link_subsystem('core_files', 'privacy:metadata:filepurpose');

        return $collection;
    }

    /**
     * Returns the list of contexts that contain user information for the given user ID.
     *
     * @param int $userid The user ID to search contexts for.
     * @param contextlist $contextlist The context list to append to.
     */
    public static function get_context_for_userid_within_submission(int $userid, contextlist $contextlist): void {
        // No specific additional context required beyond mod_assign.
    }

    /**
     * Returns the list of student user IDs associated with this plugin.
     *
     * @param \mod_assign\privacy\useridlist $useridlist An object for obtaining user IDs of students.
     */
    public static function get_student_user_ids(\mod_assign\privacy\useridlist $useridlist): void {
        // Not required for this plugin.
    }

    /**
     * Returns user IDs associated with this plugin in the given context.
     *
     * @param \core_privacy\local\request\userlist $userlist The userlist object to append user IDs to.
     */
    public static function get_userids_from_context(\core_privacy\local\request\userlist $userlist): void {
        // Not implemented.
    }

    /**
     * Exports all user data relating to this submission plugin.
     *
     * @param assign_plugin_request_data $exportdata Contains information about the context and user to export.
     */
    public static function export_submission_user_data(assign_plugin_request_data $exportdata): void {
        global $DB;

        $submission = $exportdata->get_pluginobject();
        $context = $exportdata->get_context();
        $subcontext = $exportdata->get_subcontext();
        $path = array_merge($subcontext, [get_string('pluginname', 'assignsubmission_atmegacode')]);

        $record = $DB->get_record('assignsubmission_atmegacode', [
            'assignment' => $exportdata->get_assignid(),
            'submission' => $submission->id,
        ]);

        if ($record) {
            $data = (object)[
                'filename' => $record->filename,
                'timemodified' => userdate($record->timemodified),
            ];

            writer::with_context($context)->export_data($path, $data);

            writer::with_context($context)
                ->export_area_files($path, 'assignsubmission_atmegacode', 'submission_files', $submission->id);
        }
    }

    /**
     * Deletes all submission plugin data for the given context.
     *
     * @param assign_plugin_request_data $requestdata Context data used for deletion.
     */
    public static function delete_submission_for_context(assign_plugin_request_data $requestdata): void {
        global $DB;

        $fs = get_file_storage();
        $fs->delete_area_files($requestdata->get_context()->id, 'assignsubmission_atmegacode', 'submission_files');

        $DB->delete_records('assignsubmission_atmegacode', ['assignment' => $requestdata->get_assignid()]);
        $DB->delete_records('atmega_flags', ['submissionid' => $requestdata->get_pluginobject()->id]);
    }

    /**
     * Deletes plugin data for a specific user submission.
     *
     * @param assign_plugin_request_data $requestdata Data related to the user and submission to delete.
     */
    public static function delete_submission_for_userid(assign_plugin_request_data $requestdata): void {
        global $DB;

        $submissionid = $requestdata->get_pluginobject()->id;
        $fs = get_file_storage();
        $fs->delete_area_files($requestdata->get_context()->id, 'assignsubmission_atmegacode', 'submission_files', $submissionid);

        $DB->delete_records('assignsubmission_atmegacode', [
            'assignment' => $requestdata->get_assignid(),
            'submission' => $submissionid,
        ]);

        $DB->delete_records('atmega_flags', ['submissionid' => $submissionid]);
    }

    /**
     * Deletes plugin data for a list of submission IDs.
     *
     * @param assign_plugin_request_data $requestdata Contains the list of submission IDs to delete.
     */
    public static function delete_submissions(assign_plugin_request_data $requestdata): void {
        global $DB;

        $fs = get_file_storage();

        if (empty($requestdata->get_submissionids())) {
            return;
        }

        [$sql, $params] = $DB->get_in_or_equal($requestdata->get_submissionids(), SQL_PARAMS_NAMED);
        $fs->delete_area_files_select(
            $requestdata->get_context()->id,
            'assignsubmission_atmegacode',
            'submission_files',
            $sql,
            $params
        );
        $params['assignid'] = $requestdata->get_assignid();
        $DB->delete_records_select('assignsubmission_atmegacode', "assignment = :assignid AND submission $sql", $params);
        $DB->delete_records_select('atmega_flags', "submissionid $sql", $params);
    }
}
