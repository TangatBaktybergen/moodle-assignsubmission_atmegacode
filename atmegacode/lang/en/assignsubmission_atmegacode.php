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
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen <Ivan.Volosyak@@@hochschule-rhein-waal.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = 'Submit your C code and lab report for automatic validation.';
$string['default'] = 'Enabled by default';
$string['default_help'] = 'If enabled, this submission plugin will be enabled by default for all new assignments.';
$string['enabled'] = 'Enable ATmega code submission';
$string['enabled_help'] = 'If enabled, students can upload their code and HEX files for validation.';
$string['error_bannedfile'] = '❌ ZIP contains forbidden filetype: {$a}.';
$string['error_filecount'] = '❌ Upload exactly one PDF named "{$a->pdf}" and one ZIP named "{$a->zip}".';
$string['error_fileempty'] = '❌ Empty file detected: {$a}';
$string['error_filesizelimit'] = '❌ File size exceeds the limit: 5MB for PDF, 50MB for ZIP.';
$string['error_folder_nohex'] = '❌ Folder "{$a}" contains no hex file. Please recompile and amend your submission.';
$string['error_foldercount'] = '❌ ZIP contains more than 50 folders/subfolders — please reduce the number.';
$string['error_hex_mismatch'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" is not recompiled. Timestamp mismatch: {$a->timestamp}. Please recompile.';
$string['error_hexcount'] = '❌ Required: {$a->required} .hex files; found: {$a->found}.';
$string['error_hexempty'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" is empty (0 bytes).';
$string['error_hexuncompiled'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" appears uncompiled (contains 00000).';
$string['error_idmismatch'] = '❗ In folder "{$a->folder}": Student ID mismatch in {$a->file}. Expected "{$a->expected}", found "{$a->found}".';
$string['error_missing_id'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" does not contain a student ID.';
$string['error_nestedfolders'] = '❌ Folder "{$a->folder}" contains subfolder(s):<br>- {$a->subfolders}<br>Nested folders are not allowed.';
$string['error_nestedzip'] = '❌ Nested ZIP file detected: {$a}. Remove all ZIP files from inside.';
$string['error_nestedzip_grouped'] = '❌ Nested ZIP files found:<br>- {$a->zips}<br>Remove them before submitting.';
$string['error_nohexelf'] = '❌ In folder "{$folder}": No compiled .hex or .elf file found for "{$file}". Please compile your code before submitting.';
$string['error_oldtimestamp'] = '⚠️ File "{$a->file}" has a suspicious timestamp: {$a->date}.';
$string['error_rename_failed'] = '❌ Could not rename uploaded files. Please try again.';
$string['error_timestamp_c'] = '⚠️ File {$a->file}.c in folder {$a->folder} is newer than {$a->file}.hex.';
$string['error_timestamp_h'] = '⚠️ File {$a->file}.h in folder {$a->folder} is newer than {$a->file}.hex.';
$string['error_timestamp_mismatch'] = '❌ Hex file "{$a->file}" in folder "{$a->folder}" does not match source file timestamps. Please recompile.';
$string['error_title'] = '❌ Please read the checklist below and amend your submission:';
$string['error_zipopen'] = '❌ Failed to open the submitted ZIP file.';
$string['filenameprefix'] = 'Filename prefix';
$string['filenameprefix_help'] = 'Short string (e.g., “Lab1”) prepended to all submitted files.';
$string['filenamesemester'] = 'Semester for filenames';
$string['filenamesemester_help'] = 'Semester code (e.g., “WS2025”) appended to all submitted files.';
$string['id_mismatch_header'] = '☠️ Suspected Cheating: Student ID mismatches detected';
$string['minhex'] = 'Minimum required .hex files';
$string['minhex_help'] = 'Number of compiled HEX files each student must include.';
$string['minor_warnings_header'] = '⚠️ Other warnings: timestamp or metadata issues';
$string['pluginname'] = 'MCU code submission (ATmega)';
$string['privacy:metadata:assignmentid'] = 'The ID of the assignment.';
$string['privacy:metadata:filename'] = 'The name of the submitted file.';
$string['privacy:metadata:filepurpose'] = 'Files uploaded as part of the ATmega code submission.';
$string['privacy:metadata:flagstablepurpose'] = 'Stores validation warnings (cheating flags) for teacher reference.';
$string['privacy:metadata:submissionid'] = 'The ID of the student\'s submission.';
$string['privacy:metadata:tablepurpose'] = 'Stores metadata about the ATmega code submission.';
$string['privacy:metadata:timemodified'] = 'The time the submission was modified.';
$string['submission_success'] = '✅ Thank you, your submission was successfully processed.';
$string['submissionfiles'] = 'Upload your submission files (1 PDF and 1 ZIP)';
$string['success_feedback_title'] = '✅ Your submission passed all checks.';
$string['validationreport'] = 'Download validation report (.txt)';
$string['validationreport_note'] = '❌ Unfortunately your submission must be amended – please fix all listed issues, and re-submit your files.';
$string['warning_hex_newer'] = '⚠️ HEX file "{$a->file}" in folder "{$a->folder}" is significantly newer than the latest .c/.h file. Please ensure the correct version is compiled.';
$string['warning_nomakefile'] = '⚠️ Folder "{$folder}" does not contain a Makefile. Ensure the folder was compiled manually if this is intended.';
