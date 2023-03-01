<?php

/**
 * The mod_uniexam settings.
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_configtext('uniexam/apiurl', get_string('apiurl', 'uniexam'),
        get_string('configapiurl', 'uniexam'), '', PARAM_URL));

    $settings->add(new admin_setting_configtext('uniexam/apiuser', get_string('apiuser', 'uniexam'),
        get_string('configapiuser', 'uniexam'), '', PARAM_TEXT));

    $settings->add( new admin_setting_configpasswordunmask('uniexam/apipwd', get_string('apipwd', 'uniexam'),
        get_string('configapipwd', 'uniexam'), ''));
}
