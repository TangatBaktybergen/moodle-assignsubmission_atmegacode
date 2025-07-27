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
 * This file contains the class for backup of the ATmega code submission plugin.
 *
 * @package    assignsubmission_atmegacode
 * @copyright  2025 Ivan Volosyak and Tangat Baktybergen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/assign/backup/moodle2/backup_assign_subplugin.class.php');

/**
 * Provides the information to backup ATmega code submissions.
 *
 * @package    assignsubmission_atmegacode
 */
class backup_assignsubmission_atmegacode_subplugin extends backup_subplugin {
    /**
     * Returns the subplugin information to attach to the submission element.
     *
     * @return backup_subplugin_element
     */
    protected function define_submission_subplugin_structure() {

        // Create XML structure.
        $subplugin = $this->get_subplugin_element();
        $subpluginwrapper = new backup_nested_element($this->get_recommended_name());
        $subpluginelement = new backup_nested_element('assignsubmission_atmegacode', ['id'], [
            'assignment', 'submission', 'filename', 'timemodified',
        ]);

        // Attach to tree.
        $subplugin->add_child($subpluginwrapper);
        $subpluginwrapper->add_child($subpluginelement);

        // Source table.
        $subpluginelement->set_source_table('assignsubmission_atmegacode', [
            'submission' => backup::VAR_PARENTID,
        ]);

        // Annotate files in submission_files area.
        $subpluginelement->annotate_files(
            'assignsubmission_atmegacode',
            'submission_files',
            'submission'
        );

        return $subplugin;
    }
}
