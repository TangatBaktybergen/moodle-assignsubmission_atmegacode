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
 * This file contains the class for restoring the ATmega code submission plugin.
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Restore subplugin class for ATmega code submission.
 */
class restore_assignsubmission_atmegacode_subplugin extends restore_subplugin {
    /**
     * Returns the paths to be handled by the subplugin at assignment level.
     *
     * @return array
     */
    protected function define_submission_subplugin_structure() {
        $paths = [];

        $elename = $this->get_namefor('submission');
        $elepath = $this->get_pathfor('/assignsubmission_atmegacode');

        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;
    }

    /**
     * Processes one assignsubmission_atmegacode element.
     *
     * @param mixed $data The data to process
     */
    public function process_assignsubmission_atmegacode_submission($data) {
        global $DB;

        $data = (object) $data;

        $data->assignment = $this->get_new_parentid('assign');
        $oldsubmissionid = $data->submission;

        $data->submission = $this->get_mappingid('submission', $data->submission);

        $DB->insert_record('assignsubmission_atmegacode', $data);

        $this->add_related_files('assignsubmission_atmegacode', 'submission_files', 'submission', null, $oldsubmissionid);
    }
}
