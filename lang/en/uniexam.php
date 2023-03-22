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
 * English strings for uniexam
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Session Examen  (bêta)';
$string['modulenameplural'] = 'Session Examen  (bêta)';

$string['uniexam'] = 'UniExam';
$string['uniexam:addinstance'] = 'Add a new Session Examen';
$string['pluginadministration'] = 'Session Examen Administration';
$string['pluginname'] = 'Session Examen (bêta)';

$string['uniexamname'] = 'Name';
$string['uniexamname_help'] = 'Name';
$string['apiurl'] = 'UniExam API Endpoint';
$string['configapiurl'] = 'UniExam API Endpoint';
$string['apiuser'] = 'User';
$string['configapiuser'] = 'User';
$string['apipwd'] = 'Password';
$string['configapipwd'] = 'Password';
$string['panelurl'] = 'URL UniExam Manager Interface';
$string['configpanelurl'] = 'URL UniExam Manager Interface';
$string['open'] = 'Start at';
$string['close'] = 'Close at';
$string['sessionid'] = 'Id Session';
$string['sessionid_help'] = 'Leave the value 0 by default';
$string['degree'] = 'Degree';
$string['degree_help'] = 'Degree label';
$string['testtype'] = 'Type of Test';
$string['testtype_help'] = 'Values: Exam';
$string['testkind'] = 'Kind of Test';
$string['testkind_help'] = 'Values: Oral, Written';
$string['generatesession'] = 'Create or Update UniExam Session';
$string['linkpanel'] = 'Managing interface';
$string['createupdatesession'] = 'Session Create/Update';
$string['processended'] = '👌 The process has ended';
$string['invaliduniexamid'] = 'UniExam ID was incorrect';
$string['userslist'] = 'Users list:';
$string['cnxerr'] = '❌ Unable to connect UniExam Services ...';
$string['sessioncreated'] = '✔️ Session created ({$a}) !';
$string['sessioncreationerr'] = '❌ Unable to create session ...';
$string['roomassociated'] = '✔️ Room ({$a->room}) associated to session ({$a->session}) !';
$string['roomassociationerr'] = '❌ Unable to associate room to session ...';
$string['userassociated1'] = '✔️ 👩‍🏫 User ({$a->user}) associated to session ({$a->session}) !';
$string['userassociated2'] = '✔️ 👨‍🎓 User ({$a->user}) associated to session ({$a->session}) !';
$string['userassociationerr'] = '❌ Unable to associate User ({$a->user}) to session ({$a->session}) ...';
$string['userremoved2'] = '✔️ 👨‍🎓 User ({$a->user}) removed from session ({$a->session}) !';
$string['userremoveerr'] = 'Unable to remove User ({$a->user}) from session ({$a->session}) ...';
$string['listerr'] = '❌ Unable to retrieve users list ...';
