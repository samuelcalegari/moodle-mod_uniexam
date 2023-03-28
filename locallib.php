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
 * Internal library of functions for module uniexam
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * get Token API
 *
 * @return string if success, null on error
 */
function get_token()
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');
        $param = array('compte' => get_config('uniexam', 'apiuser'), 'motDePasse' => get_config('uniexam', 'apipwd'));
        $response = $soapclient->Authentification($param);
        return $response->AuthentificationResult;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Create uniexam session by API Call
 *
 * @param object $uniexam An object from the form in mod_form.php
 * @param string $token for API Call
 * @param int $sessionid
 * @return int > 0 if success
 */
function create_session(object $uniexam, string $token, int $sessionid = 0)
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');

        $param = array(
            'jeton' => $token,
            'idSession' => $sessionid,
            'nom' => $uniexam->name,
            'date' => date('d/m/Y', $uniexam->timeopen),
            'heureDebut' => date('H:i:00', $uniexam->timeopen),
            'heureFin' => date('H:i:00', $uniexam->timeclose),
            'idTypeSession' => 3, // Correspond à examen
            'diplome' => $uniexam->degree,
            'typeEpreuve' => $uniexam->testtype,
            'natureEpreuve' => $uniexam->testkind,
            'certificatMedical' => 0,
            'NbLimitePlace' => 0
        );

        $response = $soapclient->ajouterModifierSession($param);
        return $response->idSessionOut;
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * Associate room with a session by API Call
 *
 * @param int $sessionid
 * @param int $roomid
 * @param string $token for API Call
 * @return boolean Success/Failure
 */
function room_association(int $sessionid, int $roomid, string $token)
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');

        $param = array(
            'jeton' => $token,
            'idSession' => $sessionid,
            'idSalle' => $roomid, // Default Value
        );
        $response = $soapclient->ajouterSalleSession($param);
        $code = $response->ajouterSalleSessionResult->codeResultat;
        return ($code == 0);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Associate user with a session by API Call
 *
 * @param int $sessionid
 * @param string $uid
 * @param string $lastname
 * @param int $resp
 * @param string $token for API Call
 * @return boolean Success/Failure
 */
function user_association(int $sessionid, string $uid, string $lastname, int $resp, string $token)
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');

        $param=array(
            'jeton' => $token,
            'idSession' => $sessionid,
            'numeroPersonne' => $uid,
            'nom' => $lastname,
            'responsable' => $resp,
            'doitSigner' => 0,
            'numeroPlace' => 0
        );
        $response = $soapclient->ajouterParticipantSession($param);
        $code = $response->ajouterParticipantSessionResult->codeResultat;
        return ($code == 0);
    } catch (\Exception $e) {
        return false;
    }
}
/**
 * Dissociate user with a session by API Call
 *
 * @param int $sessionid
 * @param string $uid
 * @param string $token for API Call
 * @return boolean Success/Failure
 */
function user_dissociation(int $sessionid, string $uid, string $token)
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');

        $param=array(
            'jeton' => $token,
            'idSession' => $sessionid,
            'numeroPersonne' => $uid
        );
        $response = $soapclient->supprimerParticipantSession($param);
        $code = $response->supprimerParticipantSessionResult->codeResultat;
        return ($code == 0);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Get users list from a session by API Call
 *
 * @param int $sessionid
 * @param string $token for API Call
 * @param array $users
 * @return bool
 */
function user_list(int $sessionid, string $token, array &$users)
{
    try {
        $apiUrl = get_config('uniexam', 'apiurl');
        $soapclient = new SoapClient($apiUrl . '?wsdl');

        $param=array(
            'jeton' => $token,
            'idSession' => $sessionid,
        );
        $response = $soapclient->listeParticipantSession($param);
        $participants = $response->participantsOut->C_Participant;
        if (is_array($participants)) {
            foreach($participants as $participant) {
                array_push($users, $participant->numeroPersonne);
            }
        } elseif(is_object($participants)) {
            array_push($users, $participants->numeroPersonne);
        }

        $code = $response->listeParticipantSessionResult->codeResultat;
        return ($code == 0);
    } catch (\Exception $e) {
        return false;
    }
}


/**
 * Get User ID from LDAP
 *
 * @param string $uid
 * @return string
 */
function get_ldap_user_id(string $uid)
{
    try {
        $server = "ldap://ldap.univ-perp.fr";
        $ds = ldap_connect($server);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        $result = ldap_bind($ds);
        if ($result == 1) {
            $dn = "ou=people,dc=univ-perp,dc=fr";
            $filter = "(uid=$uid)";
            $justthese = array('supannempid', 'supannetuid');

            $sr = ldap_search($ds, $dn, $filter, $justthese);
            $info = ldap_get_entries($ds, $sr);
            $result = $info[0]['supannempid'][0] ?? $info[0]['supannetuid'][0] ?? '';
            return $result;
        } else {
            return '';
        }
    } catch (\Exception $e) {
        return '';
    }
}
