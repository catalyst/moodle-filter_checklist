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

namespace filter_checklist;

defined('MOODLE_INTERNAL') || die();
/**
 * checklist filter
 *
 * Documentation: {@link https://moodledev.io/docs/apis/plugintypes/filter}
 *
 * @package    filter_checklist
 * @copyright  2025 Pramith Dayananda <pramith.dayananda@catalyst.net.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {

    function filter($text, array $options = array()): string {
        global $DB, $USER, $COURSE;

        if (empty($COURSE->id) || $COURSE->id == 0) {
            return $text;
        }

        if (stripos($text, '{checklist:') !== false) {
            // Checklist course module ID specified
            preg_match_all('/\{checklist:([^{}]+)}/', $text, $matches);
            foreach ($matches[1] as $checklistname) {
                $checklistinstance = $DB->get_record('checklist', array('name' => $checklistname, 'course' => $COURSE->id));
                if ($checklistinstance == null) {
                    continue;
                }

                $modinfo = get_fast_modinfo($COURSE);
                $cm = null;
                foreach ($modinfo->get_cms() as $modinfo) {
                    if ($modinfo->modname == 'checklist' && $modinfo->instance == $checklistinstance->id) {
                        $cm = $modinfo;
                    }
                }
                if ($cm == null) {
                    continue;
                }

                $context = \context_module::instance($cm->id, IGNORE_MISSING);
                // Can't find course module, do not display.
                if (!($context instanceof \context_module)) {
                    continue;
                }

                $checklist = $DB->get_record('checklist', array('id' => $cm->instance), '*', MUST_EXIST);
                $checklist = new \checklist_class($cm->id, $USER->id, $checklist, $cm, $COURSE);

                $output = "<div id='filter_checklist'>";
                $output .= $checklist->view(true);
                if (has_capability('mod/checklist:edit', $context) && get_config('filter_checklist', 'edit_checklist_from_filter')) {
                    $editlink = new moodle_url('/mod/checklist/edit.php', ['id' => $cm->id]);
                    $output .= $this->filter_checklist_get_button($checklistname, $editlink);
                    if (!empty($helpmessage = get_config('filter_checklist', 'edit_button_help_message'))) {
                        $output .= "<p>" . $helpmessage . "</p>";
                    }
                }
                $output .= "</div>";

                $text = preg_replace('/\{checklist:' . preg_quote($cm->name, '/') . '\}/isuU', $output, $text) ?? $text;
            }
        }

        return $text;
    }

    private function filter_checklist_get_button($name, $link) {
        return '<a href="' . $link->out() . '" class="btn btn-primary filter-checklist-edit" target="_blank">' . get_string('edit') . ' <i>' . $name . '</i></a>';
    }
}
