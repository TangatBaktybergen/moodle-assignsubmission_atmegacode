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

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox(
        'assignsubmission_atmegacode/default',
        get_string('default', 'assignsubmission_atmegacode'),
        get_string('default_help', 'assignsubmission_atmegacode'),
        0
    ));

    $settings->add(new admin_setting_configtext(
        'assignsubmission_atmegacode/requiredhexcount',
        get_string('requiredhexcount', 'assignsubmission_atmegacode'),
        get_string('requiredhexcount_help', 'assignsubmission_atmegacode'),
        2,
        PARAM_INT
    ));
}
