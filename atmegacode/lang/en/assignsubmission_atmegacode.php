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

//locallib.php
$string['pluginname'] = 'ATmega code submission';

$string['atmegacode'] = 'ATmega code';
$string['atmegacode_help'] = 'Submit your code and lab report for evaluation.';

$string['minhex'] = 'Minimum number of .hex files';
$string['minhex_help'] = 'Set the required minimum number of .hex files that students must include in their ZIP submission.';

$string['filenameprefix'] = 'Filename prefix';
$string['filenameprefix_help'] = 'Enter the prefix used in ZIP/PDF filenames, e.g., Lab4.';

$string['filenamesemester'] = 'Semester suffix';
$string['filenamesemester_help'] = 'Enter the semester used in filenames, e.g., WS2025.';

$string['submissionfiles'] = 'Submission files (ZIP + PDF)';

$string['notenoughhexfiles'] = ' You must include at least {$a->required} hex files (currently only {$a->actual}).';
$string['submissionerror_hexuncompiled'] = 'The hex file <strong>{$a}</strong> contains the student ID 00000. 
Enter your valid student ID into the C file and recompile.';
$string['no_files'] = 'One or more .hex files are empty (0 bytes).';


//settings.php
$string['default'] = 'Lab1';
$string['default_help'] = 'Default value for lab prefix.';
$string['requiredhexcount'] = 'Required number of .hex files';
$string['requiredhexcount_help'] = 'Minimum number of .hex files required in ZIP archive.';



$string['enabled'] = 'Enable ATmega code submissions';
$string['enabled_help'] = 'If enabled, students can submit ATmega88 lab code.';



$string['nofilesubmitted'] = 'No ZIP file was submitted';

$string['submissionerror_nofile'] = 'No file was submitted.';
$string['submissionerror_missinghex'] = 'You must include at least {$a->required} hex files (currently only {$a->actual}).';
$string['submissionerror_incompletefolder'] = 'Some folders contain .c files but are missing .hex or .elf compiled outputs.';
$string['reportpdf'] = 'Lab Report PDF';

$string['submissionerror_filename'] = 'Filename must be: <strong>{$a}</strong> (e.g., Lab1_XXXXX_WS2026).';
$string['submissionerror_forbiddentype'] = 'ZIP contains forbidden filetype: <strong>{$a}</strong>';
$string['submissionerror_filesize'] = 'Combined file size must be less than 1MB.';
$string['submissionerror_filecount'] = 'You must upload exactly one ZIP and one PDF file.';
$string['submissionerror_hexempty'] = 'Hex file <strong>{$a}</strong> is empty (0 bytes).';
$string['submissionerror_hexnoid'] = 'Hex file <strong>{$a}</strong> does not contain any valid student ID. 
Enter your ID in the C file and recompile.';
$string['submissionerror_hexuncompiled_folders'] = 'Uncompiled .hex files found in folders: {$a}';

$string['flag_cheating_id'] = 'Cheating: student ID <strong>{$a->found}</strong> in file <strong>{$a->file}</strong> 
(expected: <strong>{$a->expected}</strong>).';
$string['submissionerror_emptyhex'] = 'One or more .hex files are empty (0 bytes).';
$string['submissionerror_oversize'] = 'Combined file size must be less than 1MB.';
$string['submissionerror_nestedzip'] = 'Nested ZIP files are not allowed.';

$string['prefix'] = 'Filename prefix';
$string['semester'] = 'Semester suffix';
$string['prefix_help'] = 'Enter the assignment prefix for filenames, e.g., Lab4.';
$string['semester_help'] = 'Enter the semester label, e.g., WS2025.';

$string['submissionerror_invalidzipname'] = 'ZIP filename must be: <strong>{$a}.zip</strong>';
$string['submissionerror_invalidpdfname'] = 'PDF filename must be: <strong>{$a}.pdf</strong>';

$string['defaultsemester'] = 'WS2025';
$string['defaultsemester_help'] = 'Default semester code, auto-filled based on the current date.';



$string['error_exactlytwosubmissions'] = 'You must upload exactly two files: one PDF report and one ZIP archive.';
$string['error_invalidfiletype'] = 'Invalid file type or filename. Only PDF and ZIP files are allowed.';
$string['error_missingpdforzip'] = 'Missing file(s). Please upload both: <strong>{$a}.pdf</strong> and 
<strong>{$a}.zip</strong>';

$string['uploadfiles'] = 'Upload your lab ZIP and report';
$string['submissionalreadysubmitted'] = 'Your submission has already been submitted and is locked.';
$string['submissionlocked'] = 'Your submission is now locked. You cannot make changes.';

$string['timestamp_warning'] = 'The source file <strong>{$a->source}</strong> is newer than the compiled file
 <strong>{$a->hex}</strong>. This must be amended.';
$string['timestampfoldererror'] = '<span style="color:#8B0000;font-weight:bold">Source code is newer than compiled 
hex in folder <strong>{$a}</strong>. This must be amended.</span>';

$string['amendment_required'] = 'This must be amended.';

// Hex ID validation
$string['error_id_00000'] = 'The hex file <strong>{$a}</strong> contains the student ID <code>00000</code>. 
Enter your valid ID in the C file and recompile.';
$string['error_missing_id'] = 'Hex file <strong>{$a}</strong> does not contain any student ID. Enter your ID in 
the C file and recompile.';
$string['error_cheating_teacher'] = 'Student ID mismatch in <strong>{$a->file}</strong> — found: <code>{$a->found}</code>, 
expected: <code>{$a->expected}</code>.';
$string['error_empty_id'] = 'Hex file <strong>{$a}</strong> has no student ID assigned. Recompile with your correct ID.';
$string['error_empty_hex'] = 'Hex file <strong>{$a}</strong> is empty (0 bytes).';
$string['error_folder_id_00000'] = 'The hex file in folder <strong>{$a}</strong> contains the student ID <code>00000</code>. 
Enter your valid student ID in the C file and recompile.';
$string['error_nested_zip'] = 'Nested ZIP file detected: <strong>{$a}</strong>. Please do not include ZIP files inside your 
submission.';
$string['error_forbidden_type'] = 'ZIP contains forbidden filetype: <strong>{$a}</strong>';
$string['error_file_too_large'] = '<strong>{$a}</strong> file exceeds 1MB size limit.';


