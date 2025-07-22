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
//

/**
 *  Language file for ATmegacode Submission Plugin
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen <Ivan.Volosyak@@@hochschule-rhein-waal.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['amendment_required'] = 'This must be amended.';
$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = 'Submit your code and lab report for evaluation.';
$string['bannedfile_error'] = 'ZIP contains forbidden filetype: {$a}.';
$string['cheating_warning'] = '⚠️ Cheating Warning:';
$string['error_bannedfile'] = 'ZIP contains forbidden filetype: {$a}.';
$string['error_filecount'] = 'Please upload exactly one PDF named {$a->pdf} and one ZIP named {$a->zip}.';
$string['error_filesizelimit'] = 'All submission files must be under 50MB.';
$string['error_foldercount'] = 'ZIP contains more than 50 folders/subfolders — please reduce the folder count.';
$string['error_hexcount'] = 'Submission requires {$a->required} .hex files, but only {$a->found} were found.';
$string['error_hexempty'] = 'Hex file {$a} is empty (0 bytes).';
$string['error_hexuncompiled'] = 'Hex file {$a} appears uncompiled (contains 00000).';
$string['error_idmismatch'] = 'Student ID mismatch in folder {$a->folder}, file {$a->file} → Expected ID: {$a->expected} → Found: {$a->found}.';
$string['error_missing_id'] = 'Hex file <strong>{$a}</strong> does not contain any student ID. Enter your ID in the C file and recompile.';
$string['error_nestedfolders'] = 'Folder \'{$a->folder}\' contains nested subfolder(s): {$a->subfolders} — please remove them. Nested folders are not allowed.';
$string['error_nestedzip'] = 'Nested ZIP file detected: {$a}. Please do not include ZIP files inside your submission.';
$string['error_nohexfile'] = 'In folder {$a->folder}: No matching hex file for {$a->file} found. Please recompile.';
$string['error_timestamp_c'] = 'File {$a}.c is newer than {$a}.hex — please recompile.';
$string['error_timestamp_h'] = 'File {$a}.h is newer than {$a}.hex — please recompile.';
$string['error_title'] = '⚠ Please read the checklist below and amend your submission until all issues are resolved:';
$string['filenameprefix'] = 'Filename prefix';
$string['filenamesemester'] = 'Semester for filenames';
$string['flag_cheating_id'] = 'Cheating: student ID <strong>{$a->found}</strong> in file <strong>{$a->file}</strong> (expected: <strong>{$a->expected}</strong>).';
$string['minhex'] = 'Minimum required .hex files';
$string['none'] = '[none]';
$string['pluginname'] = 'ATmega code submission';
$string['submission_failed'] = '❌ Due to the following problems, you cannot submit the file now. Please amend accordingly and re-submit.';
$string['submission_success'] = '✅ Thank you, your submission was successfully processed. You may now submit.';
$string['submissionfiles'] = 'Upload your submission files (one PDF and one ZIP)';
$string['timestampfoldererror'] = '<span style="color:#8B0000;font-weight:bold">Source code is newer than compiled hex in folder <strong>{$a}</strong>. This must be amended.</span>';
