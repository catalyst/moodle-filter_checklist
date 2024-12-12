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
 * Display checklist via filter
 *
 * @package    filter_checklist
 * @copyright  2022 onwards Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configcheckbox(
        'filter_checklist/edit_checklist_from_filter',
        new lang_string('edit_checklist_from_filter', 'filter_checklist'),
        new lang_string('edit_checklist_from_filter_desc', 'filter_checklist'),
        0)
    );

    $settings->add(new admin_setting_configtextarea(
        'filter_checklist/edit_button_help_message',
        new lang_string('edit_button_help_message', 'filter_checklist'),
        new lang_string('edit_button_help_message_desc', 'filter_checklist'),
        '')
    );
}
