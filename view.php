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
 * Prints a particular instance of uniexam
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or.
$n  = optional_param('n', 0, PARAM_INT);  // uniexam instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('uniexam', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $uniexam  = $DB->get_record('uniexam', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $uniexam = $DB->get_record('uniexam', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $uniexam->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('uniexam', $uniexam->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_uniexam\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $uniexam);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/uniexam/view.php', array('id' => $cm->id));
$PAGE->set_title($uniexam->name);
$PAGE->set_heading($course->shortname);

// Output starts here.
echo $OUTPUT->header();

// Replace the following lines with you own code.
echo $OUTPUT->heading($uniexam->name);

$renderer = $PAGE->get_renderer('mod_uniexam');
$renderable = new \mod_uniexam\uniexam($uniexam);
echo $renderer->render($renderable);

echo "<form method=\"post\" action=\"save.php\" id=\"surveyform\">";
echo '<div>';
echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />";
echo "<input type=\"hidden\" name=\"sesskey\" value=\"".sesskey()."\" />";
echo '<br />';
echo '<input type="submit" class="btn btn-primary" value="'. get_string('generatesession','uniexam'). '" />';
echo '</div>';
echo "</form>";

// Finish the page.
echo $OUTPUT->footer();
