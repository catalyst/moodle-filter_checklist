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

class filter_checklist extends moodle_text_filter {

    function filter($text, array $options = array()): string {
        global $DB, $USER, $COURSE;

        if (empty($COURSE->id) || $COURSE->id == 0) {
            return $text;
        }

        if (stripos($text, '{checklist:') !== false) {
            // Checklist course module ID specified
            preg_match_all('/\{checklist:(.+)}/', $text, $matches);
            $checklistname = $matches[1][0];
            unset($matches);

            $checklistinstance = $DB->get_record('checklist', array('name' => $checklistname, 'course' => $COURSE->id));
            if ($checklistinstance == null) {
                return $text;
            }

            $modinfo = get_fast_modinfo($COURSE);
            $cm = null;
            foreach ($modinfo->get_cms() as $modinfo) {
                if ($modinfo->modname == 'checklist' && $modinfo->instance == $checklistinstance->id) {
                    $cm = $modinfo;
                }
            }
            if ($cm == null) {
                return $text;
            }

            $context = context_module::instance($cm->id, IGNORE_MISSING);
            // Can't find course module, do not display.
            if (!($context instanceof context_module)) {
                return $text;
            }

            $checklist = $DB->get_record('checklist', array('id' => $cm->instance), '*', MUST_EXIST);
            $checklist = new checklist_class($cm->id, $USER->id, $checklist, $cm, $COURSE);

            $output = "<div id='filter_checklist'>";
            $output .= $checklist->view(true);
            $output .= "</div>";

            $text = preg_replace('/\{checklist:' . preg_quote($cm->name, '/') . '\}/isuU', $output, $text) ?? $text;
        }

        return $text;
    }
}


