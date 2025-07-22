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
 * Main library for ATmega code submission plugin
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('ASSIGNSUBMISSION_FILEAREA', 'submission_files');

/**
 * ATmega code submission plugin class.
 *
 * Handles settings, submission form elements, validation,
 * file saving, and display of ATmega code submissions.
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_submission_atmegacode extends assign_submission_plugin {
    /**
     * Returns the plugin name for display.
     *
     * @return string The localized plugin name.
     */
    public function get_name() {
        return get_string('pluginname', 'assignsubmission_atmegacode');
    }

    /**
     * Adds plugin settings form elements.
     *
     * @param MoodleQuickForm $mform The Moodle form object.
     * @return void
     */
    public function get_settings(MoodleQuickForm $mform) {
        $minhex = $this->get_config('minhex');
        if ($minhex === false) {
            $minhex = 3;
        }

        $mform->addElement(
            'text',
            'assignsubmission_atmegacode_minhex',
            get_string('minhex', 'assignsubmission_atmegacode')
        );
        $mform->setType('assignsubmission_atmegacode_minhex', PARAM_INT);
        $mform->setDefault('assignsubmission_atmegacode_minhex', $minhex);
        $mform->disabledIf('assignsubmission_atmegacode_minhex', 'assignsubmission_atmegacode_enabled', 'notchecked');
        $mform->addHelpButton('assignsubmission_atmegacode_minhex', 'minhex', 'assignsubmission_atmegacode');

        $prefix = $this->get_config('prefix');
        if ($prefix === false) {
            $prefix = 'Lab1';
        }

        $mform->addElement(
            'text',
            'assignsubmission_atmegacode_prefix',
            get_string('filenameprefix', 'assignsubmission_atmegacode')
        );
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

        $mform->addElement(
            'text',
            'assignsubmission_atmegacode_semester',
            get_string('filenamesemester', 'assignsubmission_atmegacode')
        );
        $mform->setType('assignsubmission_atmegacode_semester', PARAM_ALPHANUMEXT);
        $mform->setDefault('assignsubmission_atmegacode_semester', $semester);
        $mform->disabledIf('assignsubmission_atmegacode_semester', 'assignsubmission_atmegacode_enabled', 'notchecked');
        $mform->addHelpButton('assignsubmission_atmegacode_semester', 'filenamesemester', 'assignsubmission_atmegacode');
    }

    /**
     * Saves plugin settings from the form.
     *
     * @param stdClass $data The data submitted from the settings form.
     * @return bool True on success.
     */
    public function save_settings(stdClass $data) {
        $this->set_config('enabled', !empty($data->assignsubmission_atmegacode_enabled));
        $this->set_config('minhex', (int)$data->assignsubmission_atmegacode_minhex);
        $this->set_config('prefix', $data->assignsubmission_atmegacode_prefix);
        $this->set_config('semester', $data->assignsubmission_atmegacode_semester);
        return true;
    }

    /**
     * Adds form elements for the submission form.
     *
     * @param stdClass $submission The submission object.
     * @param MoodleQuickForm $mform The Moodle form instance.
     * @param stdClass $data Data used to fill the form.
     * @return bool True if elements were added.
     */
    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
        global $SESSION;

        $context = $this->assignment->get_context();
        $draftitemid = file_get_submitted_draft_itemid('atmegacode_files');

        file_prepare_draft_area(
            $draftitemid,
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            ['subdirs' => 0, 'maxfiles' => 2, 'accepted_types' => ['.zip', '.pdf']]
        );

        $mform->addElement(
            'filemanager',
            'atmegacode_files',
            get_string('submissionfiles', 'assignsubmission_atmegacode'),
            null,
            [
                'subdirs' => 0,
                'maxbytes' => 50 * 1024 * 1024,
                'maxfiles' => 2,
                'accepted_types' => ['.zip', '.pdf'],
            ]
        );

        $mform->addRule('atmegacode_files', null, 'required', null, 'client');
        $mform->setDefault('atmegacode_files', $draftitemid);

        if (!empty($SESSION->atmegacode_error)) {
            $mform->addElement('html', '<div class="error" style="color:red; margin-top:10px;">' .
                '<strong>' . get_string('error_title', 'assignsubmission_atmegacode') . '</strong><br><br>' .
                $SESSION->atmegacode_error . '</div>');
            unset($SESSION->atmegacode_error);
        }

        if (!empty($SESSION->atmegacode_success)) {
            $mform->addElement('html', '<div class="success" style="color:green; margin-top:10px;">' .
                $SESSION->atmegacode_success . '</div>');
            unset($SESSION->atmegacode_success);
        }

        return true;
    }
    /**
     * Validates and saves the student's submission data.
     *
     * This includes handling uploaded files, validating ZIP content,
     * renaming files, checking for required .hex files, student ID
     * validation inside hex files, and managing errors/warnings.
     *
     * @param stdClass $submission The submission record.
     * @param stdClass $data The data submitted by the student.
     * @return bool True on successful save and validation.
     * @throws moodle_exception If validation fails and redirection occurs.
     */
    public function save(stdClass $submission, stdClass $data) {
        global $USER, $DB, $SESSION;

        // Extract student ID from username or fallback.
        if (!empty($USER->username) && strpos($USER->username, '@') !== false) {
            $studentid = substr($USER->username, 0, strpos($USER->username, '@'));
        } else if (!empty($USER->username)) {
            $studentid = $USER->username;
        } else {
            $studentid = $USER->id;
        }

        // Load config or use defaults.
        $prefix = $this->get_config('prefix') ?: 'Lab1';
        $semester = $this->get_config('semester');
        if (empty($semester)) {
            $semester = 'WS' . ((date('n') <= 3) ? date('Y') - 1 : date('Y'));
        }
        $requiredhex = (int) $this->get_config('minhex');

        // Expected filenames (case-insensitive).
        $expectedpdfdisplay = "{$prefix}_{$studentid}_{$semester}.pdf";
        $expectedzipdisplay = "{$prefix}_{$studentid}_{$semester}.zip";

        $context = $this->assignment->get_context();
        $fs = get_file_storage();

        // Save uploaded files to filearea.
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
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            "id",
            false
        );

        // Rename files to expected format if needed.
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') {
                continue;
            }
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, ['zip', 'pdf'])) {
                continue;
            }

            $newname = "{$prefix}_{$studentid}_{$semester}.{$ext}";

            if ($filename === $newname) {
                continue;
            }

            // Delete existing with new name, if any.
            $existing = $fs->get_file(
                $context->id,
                'assignsubmission_atmegacode',
                ASSIGNSUBMISSION_FILEAREA,
                $submission->id,
                '/',
                $newname
            );
            if ($existing) {
                $existing->delete();
            }

            try {
                $fs->create_file_from_storedfile([
                    'contextid' => $context->id,
                    'component' => 'assignsubmission_atmegacode',
                    'filearea'  => ASSIGNSUBMISSION_FILEAREA,
                    'itemid'    => $submission->id,
                    'filepath'  => '/',
                    'filename'  => $newname,
                    'userid'    => $USER->id,
                ], $file);

                $file->delete(); // Delete old wrongly named file.
            } catch (Exception $e) {
                debugging("❌ Rename failed: " . $e->getMessage(), DEBUG_DEVELOPER);
                $SESSION->atmegacode_error = get_string('error_rename_failed', 'assignsubmission_atmegacode');
                $cmid = $this->assignment->get_course_module()->id;
                redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
            }
        }

        // Reload files after rename.
        $files = $fs->get_area_files(
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            "id",
            false
        );

        // File count & type check.
        if (count($files) !== 2) {
            $SESSION->atmegacode_error = get_string('error_filecount', 'assignsubmission_atmegacode', (object)[
                'pdf' => $expectedpdfdisplay,
                'zip' => $expectedzipdisplay,
            ]);
            $cmid = $this->assignment->get_course_module()->id;
            redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
        }

        // Check file types present.
        $pdffound = false;
        $zipfound = false;
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $pdffound = true;
            } else if ($ext === 'zip') {
                $zipfound = true;
            }
        }

        if (!$pdffound || !$zipfound) {
            $SESSION->atmegacode_error = get_string('error_filecount', 'assignsubmission_atmegacode', (object)[
                'pdf' => $expectedpdfdisplay,
                'zip' => $expectedzipdisplay,
            ]);
            $cmid = $this->assignment->get_course_module()->id;
            redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
        }

        // ZIP file size limit 50 MB check.
        $zipsizelimit = 50 * 1024 * 1024;
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
            if ($ext === 'zip' && $file->get_filesize() > $zipsizelimit) {
                $SESSION->atmegacode_error = get_string('error_filesizelimit', 'assignsubmission_atmegacode');
                $cmid = $this->assignment->get_course_module()->id;
                redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
            }
        }

        // Begin ZIP content validation.
        $hexcount = 0;
        $hexuncompiled = [];
        $hexwrongid = [];
        $bannedextensions = ['exe', 'py', 'php'];

        // Helper: extract Intel HEX data function.
        if (!function_exists('extract_intel_hex_data')) {
            /**
             * Extracts and concatenates the data bytes from Intel HEX formatted text.
             *
             * Parses each line of the HEX file and concatenates the data fields,
             * returning a string of uppercase hexadecimal characters representing
             * only the data bytes.
             *
             * @param string $hextext The contents of the Intel HEX file as a string.
             * @return string Concatenated data bytes as uppercase hex characters.
             */
            function extract_intel_hex_data($hextext) {
                $data = '';
                $lines = preg_split('/\r\n|\r|\n/', $hextext);
                foreach ($lines as $line) {
                    if (strlen($line) < 11 || $line[0] !== ':') {
                        continue;
                    }
                    $bytecount  = hexdec(substr($line, 1, 2));
                    $datafield = substr($line, 9, $bytecount * 2);
                    $data .= strtoupper($datafield);
                }
                return $data;
            }
        }

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file->get_filename(), PATHINFO_EXTENSION));
            if ($ext !== 'zip') {
                continue;
            }

            // Save ZIP content to temp file.
            $zipcontent = $file->get_content();
            $tmpzip = tempnam(sys_get_temp_dir(), 'atmegazip_');
            file_put_contents($tmpzip, $zipcontent);

            $zip = new ZipArchive();
            if ($zip->open($tmpzip) === true) {
                // Folder count and nested folder detection.
                $foldercount = 0;
                $nestedfolders = [];

                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    if (substr($entry, -1) === '/') {
                        $foldercount++;
                        $depth = substr_count(trim($entry, '/'), '/');
                        if ($depth > 0) {
                            $parent = explode('/', trim($entry, '/'))[0];
                            $nestedfolders[$parent][] = rtrim(substr($entry, strlen($parent) + 1), '/');
                        }
                    }
                }

                if ($foldercount > 50) {
                    $errors[] = get_string('error_foldercount', 'assignsubmission_atmegacode');
                }
                foreach ($nestedfolders as $parent => $subs) {
                    $errors[] = get_string('error_nestedfolders', 'assignsubmission_atmegacode', (object)[
                        'folder' => $parent,
                        'subfolders' => implode(', ', $subs),
                    ]);
                }

                // Iterate files in ZIP.
                $folderfiles = [];

                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    $entryext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    if ($entryext === 'zip') {
                        $errors[] = get_string('error_nestedzip', 'assignsubmission_atmegacode', $entry);
                    }
                    if (in_array($entryext, $bannedextensions)) {
                        $errors[] = get_string('error_bannedfile', 'assignsubmission_atmegacode', $entry);
                    }

                    if (in_array($entryext, ['c', 'h', 'hex'])) {
                        $folder = dirname($entry);
                        if ($folder === '.' || $folder === '\\' || $folder === '/') {
                            $folder = 'root';
                        }
                        $basename = pathinfo($entry, PATHINFO_FILENAME);
                        $filetime = $zip->statIndex($i)['mtime'] ?? 0;
                        $folderfiles[$folder][$entryext][$basename] = $filetime;
                    }
                }

                // Check .hex files.
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    $entryext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    if ($entryext !== 'hex') {
                        continue;
                    }

                    $hexcount++;

                    // Empty file check.
                    if ($zip->statIndex($i)['size'] === 0) {
                        $errors[] = get_string('error_hexempty', 'assignsubmission_atmegacode', $entry);
                    }

                    $stream = $zip->getStream($entry);
                    if ($stream) {
                        $content = stream_get_contents($stream);
                        fclose($stream);

                        $cleanedhex = strtoupper(preg_replace('/[^A-F0-9]/', '', $content));
                        // Uncompiled 00000 check.
                        if (strpos($cleanedhex, '3030303030') !== false) {
                            $hexuncompiled[] = $entry;
                        }

                        // Prepare ASCII hex for student ID.
                        $idhex = '';
                        foreach (str_split($studentid) as $char) {
                            $idhex .= sprintf('%02X', ord($char));
                        }

                        // Extract data portion from Intel HEX.
                        $datahex = extract_intel_hex_data($content);

                        // Check if student ID hex pattern is present.
                        if (strpos($datahex, $idhex) === false) {
                            // Try to find any 5-digit ASCII ID in data.
                            $foundid = null;
                            for ($j = 0; $j < strlen($datahex) - 9; $j += 2) {
                                $substr = substr($datahex, $j, 10);
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
                            $folder = dirname($entry);
                            if ($folder === '.' || $folder === '\\' || $folder === '/') {
                                $folder = 'root';
                            }
                            $basename = basename($entry);

                            $idmessage = get_string('error_idmismatch', 'assignsubmission_atmegacode', (object)[
                                'folder' => $folder,
                                'file' => $basename,
                                'expected' => $studentid,
                                'found' => $foundid ?: get_string('none', 'assignsubmission_atmegacode'),
                            ]);

                            $hexwrongid[] = $idmessage;
                        }
                    }
                }

                // Check for missing .hex for .c files and timestamp mismatch.
                $ignorecfiles = ['init', 'lcd', 'led', 'i2c_master', 'analog', 'servo'];

                foreach ($folderfiles as $folder => $types) {
                    if ($folder === 'root') {
                        continue;
                    }
                    $cfiles = $types['c'] ?? [];
                    $hfiles = $types['h'] ?? [];
                    $hexfiles = $types['hex'] ?? [];

                    foreach ($cfiles as $cf => $cmtime) {
                        if (in_array($cf, $ignorecfiles)) {
                            continue;
                        }
                        if (!isset($hexfiles[$cf])) {
                            $errors[] = get_string('error_nohexfile', 'assignsubmission_atmegacode', (object)[
                                'folder' => $folder,
                                'file' => $cf . '.c',
                            ]);
                        } else if ($cmtime > $hexfiles[$cf]) {
                            $errors[] = get_string('error_timestamp_c', 'assignsubmission_atmegacode', $cf);
                        }
                    }
                    foreach ($hfiles as $hf => $hmtime) {
                        if (in_array($hf, $ignorecfiles)) {
                            continue;
                        }
                        if (isset($hexfiles[$hf]) && $hmtime > $hexfiles[$hf]) {
                            $errors[] = get_string('error_timestamp_h', 'assignsubmission_atmegacode', $hf);
                        }
                    }
                }

                $zip->close();
            } else {
                $errors[] = get_string('error_zipopen', 'assignsubmission_atmegacode');
            }
            unlink($tmpzip);
        }

        // Check if enough .hex files.
        if ($hexcount < $requiredhex) {
            $errors[] = get_string('error_hexcount', 'assignsubmission_atmegacode', (object)[
                'required' => $requiredhex,
                'found' => $hexcount,
            ]);
        }

        // Uncompiled hex warnings.
        foreach ($hexuncompiled as $file) {
            $errors[] = get_string('error_hexuncompiled', 'assignsubmission_atmegacode', $file);
        }

        // If errors, display and redirect.
        if (!empty($errors)) {
            $html = '<ul>';
            foreach ($errors as $err) {
                $html .= '<li>' . $err . '</li>';
            }
            $html .= '</ul>';

            $SESSION->atmegacode_error = $html;
            $cmid = $this->assignment->get_course_module()->id;
            redirect(new moodle_url('/mod/assign/view.php', ['id' => $cmid]));
        }

        // Mark submission as submitted.
        $submission->status = ASSIGN_SUBMISSION_STATUS_SUBMITTED;
        $DB->update_record('assign_submission', $submission);

        // Save teacher warnings for cheating IDs.
        $flagdata = (object)[
            'submissionid' => $submission->id,
            'warnings' => json_encode($hexwrongid),
            'timecreated' => time(),
        ];

        $existing = $DB->get_record('atmega_flags', ['submissionid' => $submission->id]);
        if ($existing) {
            $flagdata->id = $existing->id;
            $DB->update_record('atmega_flags', $flagdata);
        } else {
            $DB->insert_record('atmega_flags', $flagdata);
        }

        // Success message.
        $SESSION->atmegacode_success = get_string('submission_success', 'assignsubmission_atmegacode');
        unset($SESSION->atmegacode_error);

        return true;
    }

    /**
     * Checks if the submission has any files.
     *
     * @param stdClass $submission The submission object.
     * @return bool True if there are no files submitted, false otherwise.
     */
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

    /**
     * Retrieves files associated with a submission.
     *
     * @param stdClass $submissionorgrade The submission or grade object.
     * @param stdClass $user The user whose files to retrieve.
     * @return array List of stored_file objects.
     */
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

    /**
     * Returns a summary of the submission including links to files and any warnings.
     *
     * @param stdClass $submission The submission object.
     * @param bool $showviewlink Reference variable to determine if a view link should be shown.
     * @return string HTML content summarizing the submission.
     */
    public function view_summary(stdClass $submission, &$showviewlink) {
        global $USER, $DB;

        $files = $this->get_files($submission, $USER);
        if (empty($files)) {
            return get_string('no_files', 'assign');
        }

        $output = '';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') {
                continue;
            }
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

        // Show cheating warnings to teachers only.
        if (has_capability('mod/assign:grade', $this->assignment->get_context())) {
            $flag = $DB->get_record('atmega_flags', ['submissionid' => $submission->id]);
            if ($flag) {
                $warnings = json_decode($flag->warnings);
                if (!empty($warnings)) {
                    $output .= '<div class="error" style="color:red; margin-top:10px;"><strong>' .
                        get_string('cheating_warning', 'assignsubmission_atmegacode') . '</strong><br>';
                    foreach ($warnings as $w) {
                        $output .= '⚠️ ' . s($w) . '<br>';
                    }
                    $output .= '</div>';
                }
            }
        }
        $showviewlink = true;
        return $output;
    }

    /**
     * Displays the full submission files and details.
     *
     * @param stdClass $submission The submission object.
     * @return string HTML output to display.
     */
    public function view(stdClass $submission) {
        global $USER;
        $files = $this->get_files($submission, $USER);
        if (empty($files)) {
            return get_string('no_files', 'assign');
        }

        $output = '';
        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') {
                continue;
            }
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

    /**
     * Determines if the plugin can upgrade from a previous version.
     *
     * @param string $type Type of plugin.
     * @param int $version Version number.
     * @return bool True if upgrade possible, false otherwise.
     */
    public function can_upgrade($type, $version) {
        return false;
    }

    /**
     * Upgrade the submission from the old assignment to the new one
     *
     * @param context $oldcontext The context of the old assignment
     * @param stdClass $oldassignment The data record for the old oldassignment
     * @param stdClass $oldsubmission The data record for the old submission
     * @param stdClass $submission The data record for the new submission
     * @param string $log Record upgrade messages in the log
     * @return bool true or false - false will trigger a rollback
     */
    public function upgrade(
        context $oldcontext,
        stdClass $oldassignment,
        stdClass $oldsubmission,
        stdClass $submission,
        &$log
    ) {
        return true;
    }
}
