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
 * Language file for ATmegacode Submission Plugin
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['amendment_required'] = 'This must be amended.';
$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = 'Submit your code and lab report for evaluation.';
$string['bannedfile_error'] = 'ZIP contains forbidden filetype: {$a}.';
$string['cheating_warning'] = 'Cheating Warning:';
$string['enabled'] = 'Enable Atmega code submission';
$string['enabled_help'] = 'If enabled, students can upload their code and HEX files via this plugin.';
$string['error_bannedfile'] = 'ZIP contains forbidden filetype: {$a}.';
$string['error_feedback_title'] = 'Due to these problems you cannot submit the file now, amend accordingly and re-submit';
$string['error_filecount'] = 'Please upload exactly one PDF named {$a->pdf} and one ZIP named {$a->zip}.';
$string['error_fileempty'] = 'Empty file detected: {$a}';
$string['error_filesizelimit'] = 'All submission files must be under 50MB.';
$string['error_foldercount'] = 'ZIP contains more than 50 folders/subfolders — please reduce the folder count.';
$string['error_hexcount'] = 'Submission requires {$a->required} .hex files, but only {$a->found} were found.';
$string['error_hexempty'] = 'Hex file {$a} is empty (0 bytes).';
$string['error_hexuncompiled'] = 'Hex file {$a} appears uncompiled (contains 00000).';
$string['error_idmismatch'] = 'In folder {$a->folder}: File {$a->file} contains ID "{$a->found}" but expected "{$a->expected}".';
$string['error_missing_id'] = 'Hex file <strong>{$a}</strong> does not contain any student ID. Enter your ID in the C file and recompile.';
$string['error_nestedfolders'] = 'Folder "{$a->folder}" contains nested subfolder(s): {$a->subfolders} — please remove them. Nested folders are not allowed.';
$string['error_nestedzip'] = 'Nested ZIP file detected: {$a}. Please do not include ZIP files inside your submission.';
$string['error_nohexfile'] = 'In folder {$a->folder}: No matching hex file for {$a->file} found. Please recompile.';
$string['error_nomakefile'] = 'Missing Makefile in folder "{$a->folder}"';
$string['error_oldtimestamp'] = 'File "{$a->file}" has a suspicious timestamp: {$a->date}.';
$string['error_rename_failed'] = 'Could not rename uploaded files. Please try again.';
$string['error_timestamp_c'] = 'File {$a}.c is newer than {$a}.hex — please recompile.';
$string['error_timestamp_h'] = 'File {$a}.h is newer than {$a}.hex — please recompile.';
$string['error_title'] = 'Please read the checklist below and amend your submission until all issues are resolved:';
$string['error_zipopen'] = 'Failed to open the submitted ZIP file.';
$string['filenameprefix'] = 'Filename prefix';
$string['filenameprefix_help'] = 'Short string (e.g. “Lab1”) prepended to all submitted files.';
$string['filenamesemester'] = 'Semester for filenames';
$string['filenamesemester_help'] = 'Semester code (e.g. “WS2025”) appended to all submitted files.';
$string['flag_cheating_id'] = 'Cheating: student ID <strong>{$a->found}</strong> in file <strong>{$a->file}</strong> (expected: <strong>{$a->expected}</strong>).';
$string['id_mismatch_header'] = '⚠️ Possible cheating detected – student ID mismatch in .hex files';
$string['minhex'] = 'Minimum required .hex files';
$string['minhex_help'] = 'How many compiled HEX files each student must include.';
$string['none'] = 'none';
$string['pluginname'] = 'MCU code submission (ATmega)';
$string['submission_failed'] = 'Due to the following problems, you cannot submit the file now. Please amend accordingly and re-submit.';
$string['submission_success'] = 'Thank you, your submission was successfully processed. You may now submit.';
$string['submissionfiles'] = 'Upload your submission files (one PDF and one ZIP)';
$string['success_feedback_title'] = 'Thank you, your submission was successfully processed';
$string['timestampfoldererror'] = '<span style="color:#8B0000;font-weight:bold">Source code is newer than compiled hex in folder <strong>{$a}</strong>. This must be amended.</span>';
