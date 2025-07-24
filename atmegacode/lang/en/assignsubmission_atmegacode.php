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
 * Language file for ATmega Code Submission Plugin
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Language file for ATmega Code Submission Plugin
$string['amendment_required'] = '❌ This must be amended.';
$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = 'Submit your C code and lab report for automatic validation.';
$string['cheating_warning'] = '⚠️ Cheating Warning:';
$string['enabled'] = 'Enable ATmega code submission';
$string['enabled_help'] = 'If enabled, students can upload their code and HEX files for validation.';
$string['filenameprefix'] = 'Filename prefix';
$string['filenameprefix_help'] = 'Short string (e.g., “Lab1”) prepended to all submitted files.';
$string['filenamesemester'] = 'Semester for filenames';
$string['filenamesemester_help'] = 'Semester code (e.g., “WS2025”) appended to all submitted files.';
$string['minhex'] = 'Minimum required .hex files';
$string['minhex_help'] = 'Number of compiled HEX files each student must include.';
$string['none'] = 'none';
$string['pluginname'] = 'MCU code submission (ATmega)';
$string['submissionfiles'] = 'Upload your submission files (1 PDF and 1 ZIP)';
$string['submission_failed'] = '❌ Your submission cannot be accepted. Please fix all listed issues and try again.';
$string['submission_success'] = '✅ Thank you, your submission was successfully processed.';
$string['success_feedback_title'] = '✅ Your submission passed all checks.';
$string['validationreport'] = 'Download validation report (.txt)';
$string['validationreport_note'] = '❌ Unfortunately your submission must be amended – please fix all listed issues, and re-submit your files.';

// Errors – ZIP and structure
$string['error_bannedfile'] = '❌ ZIP contains forbidden filetype: {$a}.';
$string['error_filecount'] = '❌ Upload exactly one PDF named "{$a->pdf}" and one ZIP named "{$a->zip}".';
$string['error_fileempty'] = '❌ Empty file detected: {$a}';
$string['error_filesizelimit'] = '❌ File size exceeds the limit: 5MB for PDF, 50MB for ZIP.';
$string['error_foldercount'] = '❌ ZIP contains more than 50 folders/subfolders — please reduce the number.';
$string['error_nestedfolders'] = '❌ Folder "{$a->folder}" contains subfolder(s):<br>- {$a->subfolders}<br>Nested folders are not allowed.';
$string['error_nestedzip'] = '❌ Nested ZIP file detected: {$a}. Remove all ZIP files from inside.';
$string['error_nestedzip_grouped'] = '❌ Nested ZIP files found:<br>- {$a->zips}<br>Remove them before submitting.';
$string['error_nomakefile'] = '❌ Missing Makefile in folder "{$a->folder}".';
$string['error_oldtimestamp'] = '⚠️ File "{$a->file}" has a suspicious timestamp: {$a->date}.';
$string['error_rename_failed'] = '❌ Could not rename uploaded files. Please try again.';
$string['error_title'] = '❌ Please read the checklist below and amend your submission:';
$string['error_zipopen'] = '❌ Failed to open the submitted ZIP file.';

// Errors – HEX file checks
$string['error_hexcount'] = '❌ Required: {$a->required} .hex files; Found: {$a->found}.';
$string['error_hexempty'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" is empty (0 bytes).';
$string['error_hexuncompiled'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" appears uncompiled (contains 00000).';
$string['error_missing_id'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" does not contain a student ID.';
$string['error_idmismatch'] = '❌ File "{$a->file}" in folder "{$a->folder}" contains ID "{$a->found}" instead of expected "{$a->expected}".';
$string['flag_cheating_id'] = '⚠️ Cheating suspicion: File "{$a->file}" contains ID "{$a->found}" (expected: {$a->expected}).';
$string['id_mismatch_header'] = '⚠️ Student ID mismatch detected in .hex files';

// Errors – Timestamp validation
$string['error_nohexfile'] = '❌ In folder {$a->folder}: No matching .hex file for {$a->file}.c. Please recompile.';
$string['error_timestamp_c'] = '⚠️ File {$a->file}.c in folder {$a->folder} is newer than {$a->file}.hex.';
$string['error_timestamp_h'] = '⚠️ File {$a->file}.h in folder {$a->folder} is newer than {$a->file}.hex.';
$string['timestampfoldererror'] = '⚠️ Source code is newer than compiled hex in folder "{$a}". Recompile to fix.';
