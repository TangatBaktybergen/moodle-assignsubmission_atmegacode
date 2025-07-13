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
 * Main library for Auto PDF Form plugin
 *
 * @package    mod_autopdfform
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen <Ivan.Volosyak@hochschule-rhein-waal.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

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
    // Permissions: allow students to access their own files and teachers to view any
    if (!has_capability('mod/assign:grade', $context) && $USER->id !== $file->get_userid()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}
