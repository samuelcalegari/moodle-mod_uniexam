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
 * French strings for uniexam
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Session Examen  (bêta)';
$string['modulenameplural'] = 'Session Examen  (bêta)';

$string['uniexam'] = 'Session Examen';
$string['uniexam:addinstance'] = 'Ajouter une session d\'examen';
$string['pluginadministration'] = 'Administration Session Examen';
$string['pluginname'] = 'Session Examen (bêta)';

$string['uniexamname'] = 'Titre';
$string['uniexamname_help'] = 'Titre';
$string['apiurl'] = 'URL UniExam API';
$string['configapiurl'] = 'URL UniExam API';
$string['apiuser'] = 'Utilisateur';
$string['configapiuser'] = 'Utilisateur';
$string['apipwd'] = 'Mot de passe';
$string['configapipwd'] = 'Mot de passe';
$string['panelurl'] = 'URL Interface Manager UniExam';
$string['configpanelurl'] = 'URL Interface Manager UniExam';
$string['open'] = 'Commence à';
$string['close'] = 'Fini à';
$string['sessionid'] = 'Id Session';
$string['sessionid_help'] = 'Laisser la valeur 0 par défaut';
$string['degree'] = 'Diplôme';
$string['degree_help'] = 'Titre du diplôme';
$string['testtype'] = 'Type de l\épreuve';
$string['testtype_help'] = 'Valeurs possibles: Examen';
$string['testkind'] = 'Nature de l\'épreuve';
$string['testkind_help'] = 'Valeurs possibles: Orale, Ecrite';
$string['generatesession'] = 'Créer ou Mettre à jour la session UniExam';
$string['linkpanel'] = 'Interface de gestion';
$string['createupdatesession'] = 'Création/Modification de la session';
$string['processended'] = '👌 Le processus s\'est achevé';
$string['invaliduniexamid'] = 'UniExam ID est incorrect';
$string['userslist'] = 'Liste des participants:';
$string['cnxerr'] = '❌ Impossible de se connecter aux services UniExam ...';
$string['sessioncreated'] = '✔️ Session créée ({$a}) !';
$string['sessioncreationerr'] = '❌ Impossible de créer une session ...';
$string['roomassociated'] = '✔️ Salle ({$a->room}) associée à la session ({$a->session}) !';
$string['roomassociationerr'] = '❌ Impossible d\'associer la salle à la session ...';
$string['userassociated1'] = '✔️ 👩‍🏫 Utilisateur ({$a->user}) associé à la session ({$a->session}) !';
$string['userassociated2'] = '✔️ 👨‍🎓 Utilisateur ({$a->user}) associé à la session  ({$a->session}) !';
$string['userassociationerr'] = '❌ Impossible d\'associer l\'utilisateur ({$a->user}) à la session ({$a->session}) ...';
$string['userremoved2'] = '✔️ 👨‍🎓 Utilisateur ({$a->user}) retiré de la session ({$a->session}) !';
$string['userremoveerr'] = 'Impossible de retirer l\'utilisateur User ({$a->user}) de la session ({$a->session}) ...';
$string['listerr'] = '❌ Impossible de récupérer la liste des utilisateurs ...';
