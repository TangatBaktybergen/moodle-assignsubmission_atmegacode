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
    $mform->hideIf('assignsubmission_atmegacode_minhex', 'assignsubmission_atmegacode_enabled', 'notchecked');
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
    $mform->hideIf('assignsubmission_atmegacode_prefix', 'assignsubmission_atmegacode_enabled', 'notchecked');
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
    $mform->hideIf('assignsubmission_atmegacode_semester', 'assignsubmission_atmegacode_enabled', 'notchecked');
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
    $fs = get_file_storage();

    // Prepare draft area
    $draftitemid = file_get_submitted_draft_itemid('atmegacode_files');

    // Remove .txt files from view (validation report)
    $files = $fs->get_area_files(
        $context->id,
        'assignsubmission_atmegacode',
        ASSIGNSUBMISSION_FILEAREA,
        $submission->id,
        'id',
        false
    );

    foreach ($files as $file) {
        $filename = $file->get_filename();
        if ($filename !== '.' && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'txt') {
            $file->delete(); // only hides from form view
        }
    }

    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'assignsubmission_atmegacode',
        ASSIGNSUBMISSION_FILEAREA,
        $submission->id,
        ['subdirs' => 0, 'maxfiles' => 2, 'accepted_types' => ['.zip', '.pdf']]
    );

    // Add file upload field
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

    // Client-side required rule (better message)
    $mform->addRule(
        'atmegacode_files',
        get_string('error_filecount', 'assignsubmission_atmegacode', (object)[
            'pdf' => 'expected.pdf',
            'zip' => 'expected.zip'
        ]),
        'required',
        null,
        'client'
    );

    $mform->setDefault('atmegacode_files', $draftitemid);

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

        // Extract student id from username or fallback.
        if (!empty($USER->username) && strpos($USER->username, '@') !== false) {
            $studentid = substr($USER->username, 0, strpos($USER->username, '@'));
        } elseif (!empty($USER->username)) {
            $studentid = $USER->username;
        } else {
            $studentid = $USER->id;
        }

        // Load config or use defaults.
        $prefix        = $this->get_config('prefix') ?: 'Lab1';
        $semester      = $this->get_config('semester');
        if (empty($semester)) {
            $semester = 'WS' . ((date('n') <= 3) ? date('Y') - 1 : date('Y'));
        }
        $requiredhex   = (int) $this->get_config('minhex');

        // Expected filenames.
        $expectedpdf   = "{$prefix}_{$studentid}_{$semester}.pdf";
        $expectedzip   = "{$prefix}_{$studentid}_{$semester}.zip";

        // Prepare storage and context.
        $context       = $this->assignment->get_context();
        $fs            = get_file_storage();

        // Constants & containers.
        $bannedext     = ['exe' , 'py' , 'php'];
        $ignorec       = ['init' , 'lcd' , 'led' , 'i2c_master' , 'analog' , 'servo'];
        $errors        = [];

        // Save draft files.
        file_save_draft_area_files(
            $data->atmegacode_files,
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            ['subdirs' => 0,'maxfiles' => 2,'accepted_types' => ['.zip' , '.pdf']]
        );
		$files = $fs->get_area_files(
    $context->id,
    'assignsubmission_atmegacode',
    ASSIGNSUBMISSION_FILEAREA,
    $submission->id,
    'id',
    false
);

        // Basic file checks.
        $pdf = false; 
        $zip = false;
        $sizelimit = 50 * 1024 * 1024;
        foreach ($files as $file) {
            $eext = strtolower(pathinfo($file->get_filename() , PATHINFO_EXTENSION));
            if ($eext === 'pdf') {
                $pdf=true;
            } else if ($eext === 'zip') {
                $zip = true;
                if ($file->get_filesize() > $sizelimit) {
                    $SESSION->atmegacode_error = get_string('error_filesizelimit' , 'assignsubmission_atmegacode');
                    redirect(new moodle_url('/mod/assign/view.php' , ['id'=>$this->assignment->get_course_module()->id]));
                }
            }
        }
        if (count($files) !== 2 || !$pdf || !$zip) {
             $SESSION->atmegacode_error = get_string('error_filecount', 'assignsubmission_atmegacode', (object)[
    'pdf' => $expectedpdf,
    'zip' => $expectedzip
]);

return false;

        }

        // Hex/zip validation.
        $hexcount = 0;
        $hexun = [];
        $hexid = [];

        if (!function_exists('extract_intel_hex_data')) {
            function extract_intel_hex_data($text) {
                $out = '';
                $lines = preg_split('/\r\n|\r|\n/' , $text);
                foreach ($lines as $l) {
                    if (strlen($l) < 11 || $l[0] !== ':') continue;
                    $c = hexdec(substr($l , 1 , 2));
                    $out .= strtoupper(substr($l , 9 , $c*2));
                }
                return $out;
            }
        }

        foreach ($files as $file) {
            if (strtolower(pathinfo($file->get_filename() , PATHINFO_EXTENSION)) !== 'zip') continue;
            $tmp = tempnam(sys_get_temp_dir() , 'atmegazip_');
            file_put_contents($tmp , $file->get_content());
            $zipper = new ZipArchive();
            if ($zipper->open($tmp) === true) {
                $nested = [];
                $grouped = [];
                $makefolders = [];
                $nestedzips = [];
                // First pass: detect folders and Makefiles.
                for ($i = 0; $i<$zipper->numFiles;$i++) {
                    $e = $zipper->getNameIndex($i);
                    $ext = strtolower(pathinfo($e , PATHINFO_EXTENSION));
                    if (strtolower(basename($e)) === 'makefile'){
                        $p = dirname($e);
                        $makefolders[] = ($p === '.'?'root':$p);
                    }
                    if(substr($e,-1) === '/'){
                        $d = trim($e,'/');
                        $depth = substr_count($d , '/');
                        if($depth > 1){		// here update 1
                            $p = explode('/' , $d)[0];
                            $nested[$p][] = rtrim(substr($d , strlen($p)+1) , '/');
                        }
                    }
                }
                if (!empty($nested)) {
                    foreach ($nested as $p => $subs) {
                        $formatted = implode('<br>- ', array_map('s', $subs));
                        $errors[] = get_string('error_nestedfolders', 'assignsubmission_atmegacode', (object)[
                        'folder' => s($p),
                        'subfolders' => $formatted
                        ]);
                    }
                }
               


                // Second pass: per-entry checks.
                for ($i=0;$i<$zipper->numFiles;$i++) {
                    $e = $zipper->getNameIndex($i);
                    $ext = strtolower(pathinfo($e , PATHINFO_EXTENSION));
                    // Ignore any directory entries (they always end in “/”).
                    if (substr($e , -1) === '/') {
                        continue;
                    }
                    // Skip empty files.
                    $size   = $zipper->statIndex($i)['size'] ?? 0;
                    if ($size === 0) {
                        if ($ext === 'hex') {
							$folder = dirname($e);
                            if ($folder === '.' || $folder === '\\') {
                            $folder = 'root';
                        }

                            $errors[] = get_string('error_hexempty', 'assignsubmission_atmegacode', (object)[
                            'file' => basename($e),
                            'folder' => dirname($e)
                             ]);

                        } else {
                            $errors[] = get_string('error_fileempty' , 'assignsubmission_atmegacode', $e);
                        }
                        continue;
                    }
                    // Skip empty files.

                    // Skip video files entirely.
                    if (in_array($ext , ['mp4' , 'avi' , 'mov' , 'mkv'] , true)) {
                        continue;
                    }

                    if ($ext === 'zip') {
                        $nestedzips[] = $e;
                        continue;
                    }

                    if (in_array($ext,$bannedext,true)) {
                        $errors[] = get_string('error_bannedfile' , 'assignsubmission_atmegacode' , $e);
                        continue;
                    }

                    $mtime = $zipper->statIndex($i)['mtime'] ?? 0;
                    if ($mtime < strtotime('2000-01-01')) {
                        $errors[] = get_string('error_oldtimestamp' , 'assignsubmission_atmegacode' , (object)['file' => $e,'date' => userdate($mtime)]);
                    }

                    if (in_array($ext , ['c' , 'h' , 'hex'] , true)) {
                        $d = dirname($e);
                        if ($d === '.' || $d === '\\') $d = 'root';
                        $name = pathinfo($e , PATHINFO_FILENAME);
                        $grouped[$d][$ext][$name] = $mtime;
                    }
                }
				 if (!empty($nestedzips)) {
                    $formatted = implode('<br>- ', array_map('s', $nestedzips));
                    $errors[] = get_string('error_nestedzip_grouped', 'assignsubmission_atmegacode', (object)[
                    'zips' => $formatted
                    ]);
                }

                // Folder-level checks.
                foreach ($grouped as $folder => $types){
                    if ($folder === 'root') continue;
                    // Check Makefile presence.
                    if (!in_array($folder , $makefolders , true)) {
                        $errors[] = get_string('error_nomakefile' , 'assignsubmission_atmegacode' , (object)['folder' => $folder]);
                    }
                    // C/H vs HEX checks.
                    foreach ($types['c'] ?? [] as $name => $time){
                        if (in_array($name  ,$ignorec , true)) continue;
                        if (empty($types['hex'][$name])){
                            $errors[] = get_string('error_nohexfile' , 'assignsubmission_atmegacode' , (object)['folder' => $folder , 'file' => $name.'.c']);
                        } elseif($time > $types['hex'][$name]){
                            $errors[] = get_string('error_timestamp_c', 'assignsubmission_atmegacode', (object)[
                            'file' => $name,
                            'folder' => $folder
                             ]);

                        }
                    }
                    foreach ($types['h'] ?? [] as $name => $time) {
                        if (in_array($name,$ignorec,true)) continue;
                        if (!empty($types['hex'][$name]) && $time > $types['hex'][$name]){
                            $errors[] = get_string('error_timestamp_h', 'assignsubmission_atmegacode', (object)[
                            'file' => $name,
                            'folder' => $folder
                        ]);

                        }
                    }
                }

                // Check HEX content and IDs.
                for ($i=0;$i<$zipper->numFiles;$i++) {
                    $e = $zipper->getNameIndex($i);
                    if (strtolower(pathinfo($e , PATHINFO_EXTENSION)) !== 'hex') continue;
                    $hexcount++;
                    $stream = $zipper->getStream($e);
                    if ($stream){
                        $content = stream_get_contents($stream);
                        fclose($stream);
                        $clean = preg_replace('/[^A-F0-9]/' , '',strtoupper($content));
                        if (strpos($clean , '3030303030') !== false){
                            $hexun[] = $e;
                        }
                        $idhex = ''; 
                        foreach (str_split($studentid) as $c) {
                            $idhex .= sprintf('%02X' , ord($c));
                        }
                        $datahex = extract_intel_hex_data($content);
if (strpos($datahex, $idhex) === false) {
    $found = null;

    // Attempt to extract a 5-digit numeric ID from decoded ASCII hex payload
    for ($j = 0; $j < strlen($datahex) - 9; $j += 2) {
        $substr = substr($datahex, $j, 10);
        $asc = '';
        for ($k = 0; $k < 10; $k += 2) {
            $ch = chr(hexdec(substr($substr, $k, 2)));
            if ($ch < '0' || $ch > '9') {
                $asc = '';
                break;
            }
            $asc .= $ch;
        }
        if ($asc && preg_match('/^\d{5}$/', $asc)) {
            $found = $asc;
            break;
        }
    }

    $fld = dirname($e);
    if ($fld === '.' || $fld === '\\') {
        $fld = 'root';
    }

    if (!$found) {
        // Use error_missing_id if no valid ID found at all
        $hexid[] = get_string('error_missing_id', 'assignsubmission_atmegacode', (object)[
            'folder' => $fld,
            'file' => basename($e)
        ]);
    } else {
        // Use error_idmismatch if another ID (not student's) is found
        $hexid[] = get_string('error_idmismatch', 'assignsubmission_atmegacode', (object)[
            'folder' => $fld,
            'file' => basename($e),
            'expected' => $studentid,
            'found' => $found
        ]);
    }
}

                    }
                }
                $zipper->close();
            } else {
                $errors[] = get_string('error_zipopen' , 'assignsubmission_atmegacode');
            }
            unlink($tmp);
        }

        // Final hex count checks.
        if($hexcount < $requiredhex){
            $errors[] = get_string('error_hexcount' , 'assignsubmission_atmegacode' , (object)['required' => $requiredhex , 'found' => $hexcount]);
        }
        foreach ($hexun as $f) { 
            $errors[] = get_string('error_hexuncompiled', 'assignsubmission_atmegacode', (object)[
                'file' => basename($f),
              'folder' => dirname($f) === '.' ? 'root' : dirname($f)
               ]);

        }

      // Abort on errors.
if (!empty($errors)) {
    $html = '<ul>';
    $plaintext = "ATmega Submission Error Report\n\n";

    foreach ($errors as $err) {
        $html .= '<li>' . $err . '</li>';
$plaintext .= "- " . html_to_text($err) . "\n";
    }
    $html .= '</ul>';
    $SESSION->atmegacode_error = $html;

    //  Save error_report_XXXXX.txt file.
    $reportname = "error_report_{$studentid}.txt";
	// Remove old error_report_*.txt files before adding a new one
$oldtxtfiles = $fs->get_area_files(
    $context->id,
    'assignsubmission_atmegacode',
    ASSIGNSUBMISSION_FILEAREA,
    $submission->id,
    'id',
    false
);

foreach ($oldtxtfiles as $file) {
    $filename = $file->get_filename();
    if ($filename === '.') {
        continue;
    }
    if (str_starts_with($filename, 'error_report_')) {
        $file->delete();
    }
}

   $fs->create_file_from_string([
    'contextid' => $context->id,
    'component' => 'assignsubmission_atmegacode',
    'filearea'  => ASSIGNSUBMISSION_FILEAREA,
    'itemid'    => $submission->id,
    'filepath'  => '/',
    'filename'  => $reportname,
    'userid'    => $USER->id  // 
], $plaintext);


    //  Delete ZIP file from stored submission area if validation failed.
    $files = $fs->get_area_files(
        $context->id,
        'assignsubmission_atmegacode',
        ASSIGNSUBMISSION_FILEAREA,
        $submission->id,
        'id',
        false
    );
    foreach ($files as $file) {
        $name = $file->get_filename();
        if ($name === '.') {
            continue;
        }
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($ext === 'zip') {
            $file->delete(); // Remove invalid ZIP file
        }
    }

    redirect(new moodle_url('/mod/assign/view.php', ['id' => $this->assignment->get_course_module()->id]));
}


        // Mark submitted and save warnings.
        $submission->status = ASSIGN_SUBMISSION_STATUS_SUBMITTED;
        $DB->update_record('assign_submission' , $submission);
		 // Load files.
        $files = $fs->get_area_files(
            $context->id,
            'assignsubmission_atmegacode',
            ASSIGNSUBMISSION_FILEAREA,
            $submission->id,
            'id',
            false
        );
		// Rename to expected.
        foreach ($files as $file) {
            $name = $file->get_filename();
            if ($name === '.') {
                continue;
            }
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext , ['zip' , 'pdf'])) {
                continue;
            }
            $new = "{$prefix}_{$studentid}_{$semester}.{$ext}";
            if ($name === $new) {
                continue;
            }
            $exists = $fs->get_file(
                $context->id,
                'assignsubmission_atmegacode',
                ASSIGNSUBMISSION_FILEAREA,
                $submission->id,
                '/',
                $new
            );
            if ($exists) {
                $exists->delete();
            }
            try {
                $fs->create_file_from_storedfile([
                    'contextid' => $context->id,
                    'component' => 'assignsubmission_atmegacode',
                    'filearea' => ASSIGNSUBMISSION_FILEAREA,
                    'itemid' => $submission->id,
                    'filepath' => '/',
                    'filename' => $new,
                    'userid' => $USER->id,
                ], $file);
                $file->delete();
            } catch (Exception $e) {
                debugging('Rename failed: '. $e->getMessage() , DEBUG_DEVELOPER);
                $SESSION->atmegacode_error = get_string('error_rename_failed' , 'assignsubmission_atmegacode');
                redirect(new moodle_url('/mod/assign/view.php' , ['id' => $this->assignment->get_course_module()->id]));
            }
        }
        
        $flag = (object)[ 'submissionid' => $submission->id , 'warnings'=>json_encode($hexid) , 'timecreated' => time() ];
        if($ex = $DB->get_record('atmega_flags' , ['submissionid' => $submission->id])){
            $flag->id = $ex->id; $DB->update_record('atmega_flags' , $flag);
        } else {
            $DB->insert_record('atmega_flags' , $flag);
        }

        // Success.
        $SESSION->atmegacode_success = get_string('submission_success' , 'assignsubmission_atmegacode');
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
    public function get_files(stdClass $submissionorgrade , stdClass $user) {
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
     * Summary view shown directly in the submission table (no "plus" click needed).
     *
     * @param stdClass $submission
     * @param bool $showviewlink
     * @return string HTML summary content
     */
   public function view_summary(stdClass $submission, &$showviewlink) {
    global $SESSION, $OUTPUT;

    $output = '';

    // ❌ Show blocking error message if present
    if (!empty($SESSION->atmegacode_error)) {
        $output .= $OUTPUT->notification(
            $SESSION->atmegacode_error,
            \core\output\notification::NOTIFY_ERROR
        );
        unset($SESSION->atmegacode_error);
    }

    // ✅ Show success message if available
    if (!empty($SESSION->atmegacode_success)) {
        $message = html_writer::tag('strong',
            get_string('success_feedback_title', 'assignsubmission_atmegacode')
        );

        $output .= $OUTPUT->notification(
            $message,
            \core\output\notification::NOTIFY_SUCCESS
        );

        unset($SESSION->atmegacode_success);
    }

    // ✅ Show validation report note and download link if .txt exists
    $fs = get_file_storage();
    $context = $this->assignment->get_context();
    $files = $fs->get_area_files(
        $context->id,
        'assignsubmission_atmegacode',
        ASSIGNSUBMISSION_FILEAREA,
        $submission->id,
        'timemodified',
        false
    );

    foreach ($files as $file) {
        $filename = $file->get_filename();
        if ($filename === '.' || pathinfo($filename, PATHINFO_EXTENSION) !== 'txt') {
            continue;
        }
        if (strpos($filename, 'error_report_') === 0) {
            $url = moodle_url::make_pluginfile_url(
                $context->id,
                'assignsubmission_atmegacode',
                ASSIGNSUBMISSION_FILEAREA,
                $submission->id,
                '/',
                $filename
            );

            $output .= $OUTPUT->notification(
                get_string('validationreport_note', 'assignsubmission_atmegacode') . '<br>' .
                html_writer::link($url, get_string('validationreport', 'assignsubmission_atmegacode')),
                \core\output\notification::NOTIFY_ERROR
            );
            break;
        }
    }

    // ✅ Let Moodle display attached files (PDF/ZIP)
    $showviewlink = true;

    return $output;
}


    /**
     * Displays the full submission files and details, with inline notifications.
     *
     * @param stdClass $submission The submission object.
     * @return string HTML output to display.
     */
    public function view(stdClass $submission) {
        global $OUTPUT, $USER;

        $output = '';

        // List files (no notifications here — we moved them to view_summary).
        $files = $this->get_files($submission, $USER);
        if (empty($files)) {
        return $output . get_string('no_files', 'assign');
        }

        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename === '.') {
                continue;
            }
            $url  = moodle_url::make_pluginfile_url(
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
        // Show teacher-only ID mismatch warnings (if any).
        if (has_capability('mod/assign:grade', $this->assignment->get_context())) {
            global $DB;

            $record = $DB->get_record('atmega_flags', ['submissionid' => $submission->id]);
            if ($record && !empty($record->warnings)) {
                $warnings = json_decode($record->warnings);
                if (!empty($warnings)) {
                    $output .= html_writer::tag('div', '<strong>' .
                    get_string('id_mismatch_header', 'assignsubmission_atmegacode') . '</strong>', ['style' => 'margin-top: 20px; color: darkred;']);
                    $output .= html_writer::start_tag('ul');
                        foreach ($warnings as $warning) {
                            $output .= html_writer::tag('li', s($warning));
                        }
                        $output .= html_writer::end_tag('ul');
                }
            }
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

