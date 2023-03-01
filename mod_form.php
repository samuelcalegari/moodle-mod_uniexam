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
 * The main uniexam configuration form
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * uniexam settings form.
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_uniexam_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        global $COURSE;
        $mform =& $this->_form;

        // Add the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Add the standard "name" field.
        $mform->addElement('text', 'name', get_string('uniexamname', 'uniexam'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'uniexamname', 'uniexam');

        $name = get_string('open', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('date_time_selector', 'timeopen', $name, $options);

        $name = get_string('close', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('date_time_selector', 'timeclose', $name, $options);

        $name = get_string('sessionid', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('text', 'sessionid', $name, $options);
        $mform->setDefault('sessionid', 0);
        $mform->addHelpButton('sessionid', 'sessionid', 'uniexam');

        $name = get_string('degree', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('text', 'degree', $name, $options);
        $mform->setDefault('degree', 'Master');
        $mform->addHelpButton('degree', 'degree', 'uniexam');

        $name = get_string('testtype', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('text', 'testtype', $name, $options);
        $mform->setDefault('testtype', 'Examen');
        $mform->addHelpButton('testtype', 'testtype', 'uniexam');

        $name = get_string('testkind', 'uniexam');
        $options = array('optional' => false);
        $mform->addElement('text', 'testkind', $name, $options);
        $mform->setDefault('testkind', 'Ecrite');
        $mform->addHelpButton('testkind', 'testkind', 'uniexam');

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }
}
