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
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen <Ivan.Volosyak@@@hochschule-rhein-waal.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
define('ASSIGNSUBMISSION_FILEAREA', 'submission_files');

class assign_submission_atmegacode extends assign_submission_plugin {

    public function get_name() {
        return get_string('pluginname', 'assignsubmission_atmegacode');
    }

    public function get_settings(MoodleQuickForm $mform) {
        $minhex = $this->get_config('minhex');
        if ($minhex === false) {
            $minhex = 3;
        }

        $mform->addElement('text', 'assignsubmission_atmegacode_minhex',
            get_string('minhex', 'assignsubmission_atmegacode'));
        $mform->setType('assignsubmission_atmegacode_minhex', PARAM_INT);
        $mform->setDefault('assignsubmission_atmegacode_minhex', $minhex);
        $mform->disabledIf('assignsubmission_atmegacode_minhex', 'assignsubmission_atmegacode_enabled', 'notchecked');
        $mform->addHelpButton('assignsubmission_atmegacode_minhex', 'minhex', 'assignsubmission_atmegacode');

        $prefix = $this->get_config('prefix');
        if ($prefix === false) {
            $prefix = 'Lab1';
        }

        $mform->addElement('text', 'assignsubmission_atmegacode_prefix',
            get_string('filenameprefix', 'assignsubmission_atmegacode'));
        $mform->setType('assignsubmission_atmegacode_prefix', PARAM_ALPHANUMEXT);
        $mform->setDefault('assignsubmission_atmegacode_prefix', $prefix);
        $mform->disabledIf('assignsubmission_atmegacode_prefix', 'assignsubmission_atmegacode_enabled', 'notchecked');
        $mform->addHelpButton('assignsubmission_atmegacode_prefix', 'filenameprefix', 'assignsubmission_atmegacode');

        $semester = $this->get_config('semester');
        if ($semester === false) {
            $year = (int)date('Y');
            $month = (int)date('n');
            $semester = 'WS' . ($month <= 3 ? $year - 1 : $year);
        }

        $mform->addElement('text', 'assignsubmission_atmegacode_semester',
            get_string('filenamesemester', 'assignsubmission_atmegacode'));
        $mform->setType('assignsubmission_atmegacode_semester', PARAM_ALPHANUMEXT);
        $mform->setDefault('assignsubmission_atmegacode_semester', $semester);
        $mform->disabledIf('assignsubmission_atmegacode_semester', 'assignsubmission_atmegacode_enabled', 'notchecked');
        $mform->addHelpButton('assignsubmission_atmegacode_semester', 'filenamesemester', 'assignsubmission_atmegacode');
    }

    public function save_settings(stdClass $data) {
        $enabled = !empty($data->assignsubmission_atmegacode_enabled);
        $this->set_config('enabled', $enabled);

        if (isset($data->assignsubmission_atmegacode_minhex)) {
            $this->set_config('minhex', (int)$data->assignsubmission_atmegacode_minhex);
        }

        if (isset($data->assignsubmission_atmegacode_prefix)) {
            $this->set_config('prefix', $data->assignsubmission_atmegacode_prefix);
        }

        if (isset($data->assignsubmission_atmegacode_semester)) {
            $this->set_config('semester', $data->assignsubmission_atmegacode_semester);
        }

        return true;
    }

    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
        global $SESSION;

        $context = $this->assignment->get_context();
        $instance = $this->assignment->get_instance();

        $draftitemid = file_get_submitted_draft_itemid('atmegacode_files');
        file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'assignsubmission_atmegacode',
        ASSIGNSUBMISSION_FILEAREA,
        $submission->id,
        ['subdirs' => 0, 'maxfiles' => 2, 'accepted_types' => ['.zip', '.pdf']]
        );

        $mform->addElement('filemanager', 'atmegacode_files', get_string('submissionfiles', 'assignsubmission_atmegacode'), null, [
            'subdirs' => 0,
            'maxbytes' => $instance->maxbytes ?? 10485760,
            'maxfiles' => 2,
            'accepted_types' => ['.zip', '.pdf']
        ]);

        $mform->addRule('atmegacode_files', null, 'required', null, 'client');
        $mform->setDefault('atmegacode_files', $draftitemid);

        if (!empty($SESSION->atmegacode_error)) {
            $mform->addElement('html', '<div class="error" style="color:red; margin-top:10px;">' . $SESSION->atmegacode_error . '</div>');
            unset($SESSION->atmegacode_error);
        }

        return true;
    }
     
    public function save(stdClass $submission, stdClass $data) {
        global $USER, $DB, $SESSION;

        if (!empty($USER->idnumber) && preg_match('/^\d{5}$/', $USER->idnumber)) {
            // Use idnumber if it's a 5-digit number
            $student_id = $USER->idnumber;
        } elseif (!empty($USER->username) && preg_match('/^(\d{5})@students\.hsrw/i', $USER->username, $m)) {
            // Extract the 5-digit number from username
            $student_id = $m[1];
        } elseif (!empty($USER->username) && strpos($USER->username, '@') !== false) {
            // Use everything before @ as a fallback
            $student_id = substr($USER->username, 0, strpos($USER->username, '@'));
        } elseif (!empty($USER->username)) {
            $student_id = $USER->username;
        } else {
            $student_id = $USER->id;
        }

        // Prefix and semester logic
        $prefix = $this->get_config('prefix');
        if (empty($prefix)) {
            $prefix = 'Lab1';
        }
        $semester = $this->get_config('semester');
        if (empty($semester)) {
            $semester = 'WS' . ((date('n') <= 3) ? date('Y') - 1 : date('Y'));
        }
        $required = (int) $this->get_config('minhex');
        $maxSizeBytes = 1048576;

        // Build expected filenames (display and lower-case for comparison)
        $expectedpdf_display = $prefix . '_' . $student_id . '_' . $semester . '.pdf';
        $expectedpdf = strtolower($expectedpdf_display);
        $expectedzip_display = $prefix . '_' . $student_id . '_' . $semester . '.zip';
        $expectedzip = strtolower($expectedzip_display);

        // Context and file storage
        $context = $this->assignment->get_context();
        $fs = get_file_storage();

        file_save_draft_area_files(
            $data->atmegacode_files,
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            ['subdirs' => 0, 'maxfiles' => 2, 'accepted_types' => ['.zip', '.pdf']]
        );

        $files = $fs->get_area_files(
            $context->id,
            'assignsubmission_atmegacode',
            'submission_files',
            $submission->id,
            "id",
            false
        );

        $pdffound = false;
        $zipfound = false;

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $pdffound = true;
            }
            if ($ext === 'zip') {
                $zipfound = true;
            }
        }

        // Use display names for expected file warnings
        if (!$pdffound || !$zipfound) {
            $SESSION->atmegacode_error = '<ul><li> Please upload exactly one PDF and one ZIP file.<br>Expected filenames:<br>' .
                '<strong>' . $expectedpdf_display . '</strong><br>' .
                '<strong>' . $expectedzip_display . '</strong></li></ul>';
            $cmid = $this->assignment->get_course_module()->id;
            redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
        }

        $hexCount = 0;
        $hexUncompiled = [];
        $hexWrongID = [];
        $errors = [];

        // For hex ID match
        $asciiSequence = '';
        foreach (str_split($student_id) as $char) {
            $asciiSequence .= sprintf('%02X', ord($char));
        }

        $bannedExtensions = ['exe', 'py', 'php'];

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
        
            // --- PDF file validation ---
            if ($ext === 'pdf') {
                $filename = $file->get_filename();

                if (strtolower($filename) !== $expectedpdf) {
                    $errors[] = 'PDF filename must follow this format: <strong>' . $expectedpdf_display . '</strong>';
                }
                if ($file->get_filesize() > $maxSizeBytes) {
                    $errors[] = 'PDF file exceeds 1MB size limit.';
                }
            }

            // --- ZIP file validation and inner content checks ---
            if ($ext === 'zip') {
                $filename = $file->get_filename();

                if (strtolower($filename) !== $expectedzip) {
                $errors[] = 'ZIP filename must follow this format: <strong>' . $expectedzip_display . '</strong>';
                }
                if ($file->get_filesize() > $maxSizeBytes) {
                    $errors[] = 'ZIP file exceeds 1MB size limit.';
                }

                $zipContent = $file->get_content();
                $tmpzip = tempnam(sys_get_temp_dir(), 'atmegazip_');
                file_put_contents($tmpzip, $zipContent);

                $zip = new ZipArchive();
                if ($zip->open($tmpzip) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $entry = $zip->getNameIndex($i);
                        $entryext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    
                        if ($entryext === 'zip') {
                            $errors[] = "Nested ZIP file detected: <code>{$entry}</code>. Please do not include ZIP files inside your submission.";
                        }
                        if (in_array($entryext, $bannedExtensions)) {
                            $errors[] = 'ZIP contains forbidden filetype: ' . $entry;
                        }
                        if ($entryext === 'hex') {
                            $hexCount++; // Count EVERY .hex file, only once!

                            // Check for empty hex file
                            if ($zip->statIndex($i)['size'] === 0) {
                                $errors[] = 'Hex file ' . $entry . ' is empty (0 bytes).';
                            }

                            // Check hex content
                            $stream = $zip->getStream($entry);
                            if ($stream) {
                                $content = stream_get_contents($stream);
                                fclose($stream);

                                $cleanedHex = strtoupper(preg_replace('/[^A-F0-9]/', '', $content));
                                if (strpos($cleanedHex, '3030303030') !== false) {
                                    $hexUncompiled[] = $entry;
                                }

                                // --- Robust Student ID Check ---

                                // Step 1: Prepare the ASCII hex sequence of the expected student ID
                                $id_ascii = (string)$student_id;
                                $id_hex = '';
                                foreach (str_split($id_ascii) as $char) {
                                    $id_hex .= sprintf('%02X', ord($char));
                                }

                                // Step 2: Extract and concatenate only the data bytes from the Intel HEX file
                                // (Move this function definition outside of your main loop for best practice)
                                if (!function_exists('extract_intel_hex_data')) {
                                    function extract_intel_hex_data($hextext) {
                                        $data = '';
                                        $lines = preg_split('/\r\n|\r|\n/', $hextext);
                                        foreach ($lines as $line) {
                                            if (strlen($line) < 11 || $line[0] !== ':') continue; // skip invalid lines
                                            $byte_count = hexdec(substr($line, 1, 2));
                                            $datafield = substr($line, 9, $byte_count * 2);
                                            $data .= strtoupper($datafield);
                                        }
                                        return $data;
                                    }
                                }

                                $datahex = extract_intel_hex_data($content);

                                // Step 3: Search for the expected student ID hex pattern
                                if (strpos($datahex, $id_hex) === false) {
                                    // Try to extract *any* 5-digit ASCII-coded ID in the data
                                    $foundid = null;
                                    for ($j = 0; $j < strlen($datahex) - 9; $j += 2) { // each ASCII char = 2 hex chars
                                        $substr = substr($datahex, $j, 10); // 5 digits
                                        $ascii = '';
                                        for ($k = 0; $k < 10; $k += 2) {
                                            $c = chr(hexdec(substr($substr, $k, 2)));
                                                if ($c < '0' || $c > '9') {
                                                    $ascii = '';
                                                    break;
                                                }
                                            $ascii .= $c;
                                        }
                                        if ($ascii && preg_match('/^\d{5}$/', $ascii)) {
                                            $foundid = $ascii;
                                            break;
                                        }
                                    }
                                    $idmessage = "Student ID mismatch in $entry → Expected ID: $student_id";
                                    if (!empty($foundid)) {
                                        $idmessage .= " → Found: $foundid";
                                    } else {
                                        $idmessage .= " → Found: [none]";
                                    }
                                    $hexWrongID[] = $idmessage;
                                }
                            }
                        }

                    }

                    $folderfiles = []; // [folder => ['c' => [basename=>mtime], 'h' => [basename=>mtime], 'hex' => [basename=>mtime]]]

                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $entry = $zip->getNameIndex($i);
                        $entryext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                        $folder = dirname($entry);
                        if ($folder === '.' || $folder === '\\' || $folder === '/') {
                            $folder = 'root';
                        }
                        $basename = pathinfo($entry, PATHINFO_FILENAME);
                        $filetime = $zip->statIndex($i)['mtime'] ?? 0;
                        if (in_array($entryext, ['c', 'h', 'hex'])) {
                            $folderfiles[$folder][$entryext][$basename] = $filetime;
                        }
                    }

                    // List of c files (without .hex requirement) to skip
                    $ignore_cfiles = [
                    'init', 'lcd', 'led', 'i2c_master', 'analog', 'servo'
                    // Add more if you use additional libraries
                    ];

                    // Timestamp and .hex presence check
                    foreach ($folderfiles as $folder => $types) {
                        if ($folder === 'root') continue;

                        $cfiles = isset($types['c']) ? $types['c'] : [];
                        $hfiles = isset($types['h']) ? $types['h'] : [];
                        $hexfiles = isset($types['hex']) ? $types['hex'] : [];

                        // Check each .c file for a matching .hex and timestamp
                        foreach ($cfiles as $cf => $cmtime) {
                            if (in_array($cf, $ignore_cfiles)) continue; // skip libraries
                            if (!isset($hexfiles[$cf])) {
                                $errors[] = "In folder <strong>$folder</strong>: No matching hex file for <code>$cf.c</code> (expected <code>$cf.hex</code>). Please recompile or amend your submission.";
                            } else {
                            if ($cmtime > $hexfiles[$cf]) {
                                $errors[] = "Code in folder <strong>$folder</strong>: <code>$cf.c</code> is newer than <code>$cf.hex</code> — please recompile.";
                            }
                            }
                        }

                        // (Optional) Check each .h file for a matching .hex and timestamp
                        foreach ($hfiles as $hf => $hmtime) {
                            if (in_array($hf, $ignore_cfiles)) continue; // skip libraries
                            if (isset($hexfiles[$hf]) && $hmtime > $hexfiles[$hf]) {
                                $errors[] = "Code in folder <strong>$folder</strong>: <code>$hf.h</code> is newer than <code>$hf.hex</code> — please recompile.";
                            }
                        }
                    }



                    // ... now you can build and show the $errors list
                    $zip->close();
                }
                unlink($tmpzip);
            }
        } 

        // Not enough hex files
        if ($hexCount < $required) {
            $a = (object)[
                'required' => $required,
                'actual' => $hexCount,
            ];
            $errors[] = get_string('notenoughhexfiles', 'assignsubmission_atmegacode', $a);
        }

        // Uncompiled hex files
        foreach ($hexUncompiled as $file) {
            $errors[] = get_string('submissionerror_hexuncompiled', 'assignsubmission_atmegacode', $file);
        }

        // If any errors, show and redirect
        if (!empty($errors)) {
            unset($SESSION->atmegacode_error);
            $html = '<ul>';
            foreach ($errors as $err) {
                $color = '#FF8C00'; // default: orange
                $weight = 'bold';

                $lower = strtolower($err);

                // Very dark red for code not recompiled (c/h newer)
                if (str_contains($lower, 'code in the folder') && str_contains($lower, 'not re-compiled')) {
                    $color = '#6B0000';
                    $weight = 'bold';
                }
                // Very dark red for severe cheating/id mismatch
                elseif (str_contains($lower, 'student id mismatch') || str_contains($lower, 'cheating')) {
                    $color = '#8B0000';
                    $weight = 'bold';
                }
                // Orange-red for hex file 00000
                elseif (str_contains($lower, 'student id 00000')) {
                    $color = '#FF4500';
                    $weight = 'bold';
                }
                // Blue for filename/capital letter issues
                elseif (str_contains($lower, 'filename') || str_contains($lower, 'capital letters')) {
                    $color = '#4682B4';
                }
                // Orange for folder with no hex file
                elseif (str_contains($lower, 'contains no hex file')) {
                    $color = '#FF8C00';
                }
                // Orange-red for time mismatch (hex newer)
                elseif (str_contains($lower, 'time mismatch with the hex file')) {
                    $color = '#FF8C00';
                }

                $html .= '<li><span style="color:' . $color . ';font-weight:' . $weight . '">' . $err . '</span></li>';
            }
            $html .= '</ul>';

            $SESSION->atmegacode_error = $html;
            $cmid = $this->assignment->get_course_module()->id;
            redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
        }

        $submission->status = ASSIGN_SUBMISSION_STATUS_SUBMITTED;
        $DB->update_record('assign_submission', $submission);

        // Teacher-only flags
        // Teacher-only flags
        $teacherWarnings = [];
        foreach ($hexWrongID as $msg) {
            $teacherWarnings[] = $msg;
        }

        $flagdata = (object)[
            'submissionid' => $submission->id,
            'warnings' => json_encode($teacherWarnings),
            'timecreated' => time()
        ];

        $existing = $DB->get_record('assignsubmission_atmegacode_flags', ['submissionid' => $submission->id]);
        if ($existing) {
            $flagdata->id = $existing->id;
            $DB->update_record('assignsubmission_atmegacode_flags', $flagdata);
        } else {
            $DB->insert_record('assignsubmission_atmegacode_flags', $flagdata);
        }

        return true;
    }

    public function is_empty(stdClass $submission) {
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $this->assignment->get_context()->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            "id",
            false
        );
        return count($files) === 0;
    }

    public function get_files(stdClass $submissionorgrade, stdClass $user) {
        $context = $this->assignment->get_context();
        $fs = get_file_storage();
        return $fs->get_area_files(
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submissionorgrade->id,
            "timemodified",
            false
        );
    }

   
    
    public function view_summary(stdClass $submission, & $showviewlink) {
        global $USER, $DB;

        $files = $this->get_files($submission, $USER);

        if (empty($files)) {
            return get_string('no_files', 'assign');
        }

        $output = '';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') continue;

            $url = moodle_url::make_pluginfile_url(
            $this->assignment->get_context()->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            $file->get_filepath(),
            $filename
            );
            $date = userdate($file->get_timemodified());
            $output .= html_writer::link($url, $filename) . ' – ' . $date . '<br>';
        }

        // Show cheating warnings to teachers only
        if (has_capability('mod/assign:grade', $this->assignment->get_context())) {
            $flag = $DB->get_record('assignsubmission_atmegacode_flags', ['submissionid' => $submission->id]);
            if ($flag) {
                $warnings = json_decode($flag->warnings);
                if (!empty($warnings)) {
                    $output .= '<div class="error" style="color:red; margin-top:10px;"><strong>Cheating Warning:</strong><br>';
                    foreach ($warnings as $w) {
                        $output .= '⚠️ ' . s($w) . '<br>';
                    }
                    $output .= '</div>';
                }
            }
        }

        return $output;
    }


    public function view(stdClass $submission) {
        global $USER;
        $files = $this->get_files($submission, $USER);

        if (empty($files)) {
            return get_string('no_files', 'assign');
        }

        $output = '';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') continue;

            $url = moodle_url::make_pluginfile_url(
            $this->assignment->get_context()->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            $file->get_filepath(),
            $filename
            );
            $date = userdate($file->get_timemodified());
            $output .= html_writer::link($url, $filename) . ' – ' . $date . '<br>';
        }

        return $output;
    }



    public function can_upgrade($type, $version) {
        return false;
    }

    public function upgrade(context $oldcontext, stdClass $oldassignment, stdClass $oldsubmissionorgrade, stdClass $submissionorgrade, &$log) {
        return true;
    }
}