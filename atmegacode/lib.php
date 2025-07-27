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
 * Core functions for the ATmega code submission plugin.
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Serves files from the ATmega code submission filearea.
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param context $context The context of the module.
 * @param string $filearea The file area being accessed.
 * @param array $args Extra path arguments (file path and name).
 * @param bool $forcedownload Whether the file should be forced to download.
 * @param array $options Additional options affecting file serving.
 * @return bool True if file was sent, false otherwise.
 */
function assignsubmission_atmegacode_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $USER;

    if ($filearea !== 'submission_files') {
        return false;
    }

    require_login($course, false, $cm);

    $itemid = array_shift($args);
    $filepath = '/' . implode('/', array_slice($args, 0, -1)) . '/';
    $filename = end($args);

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'assignsubmission_atmegacode', $filearea, $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    // Permissions: allow students to access their own files and teachers to view any.
    if (!has_capability('mod/assign:grade', $context) && $USER->id !== $file->get_userid()) {
        return false;
    }

    // Force download for .txt files.
    $force = $forcedownload;
    if (pathinfo($file->get_filename(), PATHINFO_EXTENSION) === 'txt') {
        $force = true;
    }

    send_stored_file($file, 0, 0, $force, $options);
}

/**
 * Called when an assignment using this plugin is deleted.
 * Deletes all related database entries (files are deleted automatically by Moodle).
 *
 * @param stdClass $assignment The assignment object.
 * @return bool True on success.
 */
function assignsubmission_atmegacode_delete_instance($assignment) {
    global $DB;

    // Delete all ATmega submission records.
    $DB->delete_records('assignsubmission_atmegacode', ['assignment' => $assignment->id]);

    // Delete all warning flag records related to this assignment.
    $sql = "DELETE FROM {atmega_flags}
            WHERE submissionid IN (
                SELECT id FROM {assign_submission}
                WHERE assignment = :assignmentid
            )";
    $DB->execute($sql, ['assignmentid' => $assignment->id]);

    return true;
}
