# Embedded Checklist Filter

## Dependencies

This plugin depends on [mod_checklist](https://github.com/davosmith/moodle-checklist) and the changes made in this PR https://github.com/davosmith/moodle-checklist/pull/94.

## Plugin Installation

1. Clone the plugin git repo into your Moodle codebase root `git clone git@github.com:catalyst/moodle-filter_checklist.git filter/checklist`
2. Run the upgrade: `sudo -u www-data php admin/cli/upgrade`
3. Enable plugin
4. Ensure filter is above activitynames in priority or activitynames filter is disabled.

## Usage

The filter looks for the text `{checklist:<course module name>}`.

Replace `<course module name>` with the name of your checklist and the text will be replaced by the checklist view in when displayed on a page.

E.g. I have a checklist called `First Week Tasks` I would add `{checklist:First Week Tasks}` to a course section to have it display the embeddec checklist.

## License ##

2022 Catalyst IT

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.


This plugin was developed by Catalyst IT:

https://www.catalyst.net.nz/

<img alt="Catalyst IT" src="https://raw.githubusercontent.com/catalyst/moodle-filter_checklist/main/pix/catalyst-logo.svg?sanitize=true" width="400">
