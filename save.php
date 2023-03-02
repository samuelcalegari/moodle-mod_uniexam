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
 * This file is responsible for saving the results of a users survey and displaying
 * the final message.
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
require_once('locallib.php');

$msg = "";

// Make sure this is a legitimate posting

if (!$formdata = data_submitted() or !confirm_sesskey()) {
    throw new \moodle_exception('cannotcallscript');
}

$id = required_param('id', PARAM_INT);    // Course Module ID

if (!$cm = get_coursemodule_from_id('uniexam', $id)) {
    throw new \moodle_exception('invalidcoursemodule');
}

if (!$course = $DB->get_record("course", array("id" => $cm->course))) {
    throw new \moodle_exception('coursemisconf');
}

$PAGE->set_url('/mod/uniexam/save.php', array('id' => $id));
require_login($course, false, $cm);

$context = context_module::instance($cm->id);

if (!$uniexam = $DB->get_record("uniexam", array("id" => $cm->instance))) {
    throw new \moodle_exception('invaliduniexamid', 'uniexam');
}

$coursecontext = context_course::instance($cm->course);
// Search Students
$users = get_enrolled_users($coursecontext, 'moodle/course:isincompletionreports');

// Search Teachers
$role = $DB->get_record('role', array('shortname' => 'editingteacher'));
$teachers = get_role_users($role->id, $coursecontext);

if ($uniexam->sessionid == 0) {
    // Session Creation
    $token = get_token();
    if ($token != NULL) {
        $sessionid = create_session($uniexam, $token);
        if ($sessionid > 0) {
            $msg .= get_string('sessioncreated', 'uniexam', $sessionid) . "<br>";
            $roomid = 1;

            // Room Association
            if (room_association($sessionid, $roomid, $token)) {
                $msg .= get_string('roomassociated', 'uniexam', ['room' => $roomid, 'session' => $sessionid]) . "<br>";

                // Add Users
                foreach ($teachers as $teacher) {
                    $uid = get_ldap_user_id($teacher->username);
                    if (isset($uid)) {
                        if (user_association($sessionid, $uid, $teacher->lastname, 1, $token)) {
                            $msg .= get_string('userassociated1', 'uniexam', ['user' => $uid . ", " . $teacher->username, 'session' => $sessionid]) . "<br>";
                        } else {
                            $msg .= get_string('userassociationerr', 'uniexam', ['user' => $uid . ", " . $teacher->username, 'session' => $sessionid]) . "<br>";
                        }
                    }
                }

                foreach ($users as $user) {
                    $uid = get_ldap_user_id($user->username);
                    if (isset($uid)) {
                        if (user_association($sessionid, $uid, $user->lastname, 0, $token)) {
                            $msg .= get_string('userassociated2', 'uniexam', ['user' => $uid . ", " . $user->username, 'session' => $sessionid]) . "<br>";
                        } else {
                            $msg .= get_string('userassociationerr', 'uniexam', ['user' => $uid . ", " . $user->username, 'session' => $sessionid]) . "<br>";
                        }
                    }
                }
                // Update Session ID
                $uniexam->sessionid = $sessionid;
                $DB->update_record('uniexam', $uniexam);
            } else {
                $msg .= get_string('roomassociationerr', 'uniexam') . "<br>";
            }
        } else {
            $msg .= get_string('sessioncreationerr', 'uniexam') . "<br>";
        }
    } else {
        $msg .= get_string('cnxerr', 'uniexam') . "<br>";
    }
} else {
    $token = get_token();
    if ($token != NULL) {
        $sessionid = $uniexam->sessionid;

        // Update Session
        create_session($uniexam, $token, $uniexam->sessionid);

        $usersUniExam = array();
        if (user_list($uniexam->sessionid, $token, $usersUniExam)) {
            foreach ($users as $user) {
                $uid = get_ldap_user_id($user->username);
                if (isset($uid)) {

                    // Not exits yet =>  add
                    if (!in_array($uid, $usersUniExam)) {
                        if (user_association($sessionid, $uid, $user->lastname, 0, $token)) {
                            $msg .= get_string('userassociated2', 'uniexam', ['user' => $uid . ", " . $user->username, 'session' => $sessionid]) . "<br>";
                        } else {
                            $msg .= get_string('userassociationerr', 'uniexam', ['user' => $uid . ", " . $user->username, 'session' => $sessionid]) . "<br>";
                        }
                    }
                    // Remove from process
                    if (($key = array_search($uid, $usersUniExam)) !== false) {
                        echo($uid);
                        unset($usersUniExam[$key]);
                    }
                }
            }
            // Remove users existing in Uniexam and not present in Moodle course
            foreach ($usersUniExam as $userUniExam) {
                if (user_dissociation($sessionid, $userUniExam, $token)) {
                    $msg .= get_string('userremoved2', 'uniexam', ['user' => $userUniExam, 'session' => $sessionid]) . "<br>";
                } else {
                    $msg .= get_string('userremoveerr', 'uniexam', ['user' => $userUniExam, 'session' => $sessionid]) . "<br>";
                }
            }

        } else {
            $msg .= get_string('listerr', 'uniexam') . "<br>";
        }
    } else {
        $msg .= get_string('cnxerr', 'uniexam') . "<br>";
    }
}


$PAGE->set_title(get_string("createupdatesession", "uniexam"));
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($uniexam->name));
echo($msg);
notice(get_string("processended", "uniexam"), "$CFG->wwwroot/course/view.php?id=$course->id");
exit;
