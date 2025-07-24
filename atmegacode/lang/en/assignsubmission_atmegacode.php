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

// General
$string['amendment_required'] = '🛠️ This must be amended.';
$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = '📄 Submit your C code and lab report for automatic evaluation.';
$string['cheating_warning'] = '⚠️ Cheating Warning:';
$string['enabled'] = 'Enable ATmega code submission';
$string['enabled_help'] = 'If enabled, students can upload their code and HEX files for automated validation.';
$string['filenameprefix'] = '🔧 Filename prefix';
$string['filenameprefix_help'] = 'Short string (e.g., “Lab1”) prepended to all submitted files.';
$string['filenamesemester'] = '🗓️ Semester for filenames';
$string['filenamesemester_help'] = 'Semester code (e.g., “WS2025”) appended to all submitted files.';
$string['minhex'] = '🧪 Minimum required .hex files';
$string['minhex_help'] = 'How many compiled HEX files must be included in each submission.';
$string['none'] = 'none';
$string['pluginname'] = 'MCU code submission (ATmega)';
$string['submissionfiles'] = '📁 Upload your submission files (1 PDF and 1 ZIP)';
$string['submission_failed'] = '⚠️ Due to the following problems, your submission cannot be accepted. Please amend accordingly and re-submit.';
$string['submission_success'] = '✅ Thank you, your submission was successfully processed.';
$string['success_feedback_title'] = '✅ Your submission passed all checks.';
$string['validationreport'] = '📄 Download validation report (.txt)';
$string['validationreport_note'] = '⚠️ Please download your validation report below, fix all listed issues, and re-submit your files.';

// Errors – ZIP and file structure
$string['error_bannedfile'] = '🚫 ZIP contains forbidden filetype: {$a}.';
$string['error_filecount'] = '📦 Please upload exactly one PDF named <code>{$a->pdf}</code> and one ZIP named <code>{$a->zip}</code>.';
$string['error_fileempty'] = '📂 Empty file detected: <code>{$a}</code>';
$string['error_filesizelimit'] = '📏 File size limit exceeded: 5MB for PDF and 50MB for ZIP.';
$string['error_foldercount'] = '📁 ZIP contains more than 50 folders/subfolders — please reduce the number.';
$string['error_nestedfolders'] = '📁 Folder <code>{$a->folder}</code> contains nested subfolder(s):<br>- {$a->subfolders}<br>Nested folders are not allowed.';
$string['error_nestedzip'] = '🧨 ZIP file contains another ZIP: <code>{$a}</code>. Remove all nested ZIPs.';
$string['error_nestedzip_grouped'] = '🧨 The following nested ZIP(s) were found:<br>- {$a->zips}<br>Please remove them. ZIP files inside submissions are not allowed.';
$string['error_nomakefile'] = '⚠️ Missing <code>Makefile</code> in folder: <strong>{$a->folder}</strong>';
$string['error_oldtimestamp'] = '📆 File <code>{$a->file}</code> has a suspicious timestamp: <strong>{$a->date}</strong>.';
$string['error_title'] = '⚠️ Please read the checklist below and fix all issues before resubmitting.';
$string['error_zipopen'] = '❌ Failed to open the submitted ZIP file.';
$string['error_rename_failed'] = '❌ Could not rename uploaded files. Please try again.';

// Errors – HEX file checks
$string['error_hexcount'] = '🧪 Required: {$a->required} .hex files; Found: {$a->found}.';
$string['error_hexempty'] = '🧪 Hex file <code>{$a->file}</code> in folder <code>{$a->folder}</code> is empty (0 bytes).';
$string['error_hexuncompiled'] = '🧪 Hex file <code>{$a->file}</code> in folder <code>{$a->folder}</code> appears uncompiled (contains ID = 00000).';
$string['error_missing_id'] = '🧩 File <strong>{$a->file}</strong> in folder <strong>{$a->folder}</strong> does not contain any student ID. Enter your ID in the C file and recompile.';
$string['error_idmismatch'] = '🔐 File <strong>{$a->file}</strong> in folder <strong>{$a->folder}</strong> contains ID <code>{$a->found}</code> but expected <code>{$a->expected}</code>.';
$string['flag_cheating_id'] = '⚠️ Cheating suspicion: file <strong>{$a->file}</strong> contains ID <strong>{$a->found}</strong> (expected: <strong>{$a->expected}</strong>).';
$string['id_mismatch_header'] = '⚠️ Possible cheating detected – student ID mismatch in .hex files';

// Errors – Timestamp validation
$string['error_nohexfile'] = '📌 Folder <code>{$a->folder}</code>: No matching .hex for <code>{$a->file}</code> found. Please recompile.';
$string['error_timestamp_c'] = '📅 <code>{$a->file}.c</code> in folder <code>{$a->folder}</code> is newer than <code>{$a->file}.hex</code> — please recompile.';
$string['error_timestamp_h'] = '📅 <code>{$a->file}.h</code> in folder <code>{$a->folder}</code> is newer than <code>{$a->file}.hex</code> — please recompile.';
$string['timestampfoldererror'] = '<span style="color:#8B0000;font-weight:bold">📅 Source code is newer than compiled hex in folder <strong>{$a}</strong>. This must be amended.</span>';
