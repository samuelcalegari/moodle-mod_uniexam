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
 * The mod_uniexam main class.
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_uniexam;

use IntlDateFormatter;
use renderable;
use renderer_base;
use templatable;
use context_course;
use stdClass;
use ArrayIterator;
use moodle_url;


/**
 * The mod_uniexam main class.
 *
 * @package   mod_uniexam
 * @copyright 2023 Samuel Calegari <samuel.calegari@univ-perp.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class uniexam implements renderable, templatable {

    /**
     * @var int $course
     */
    private $course;

    /**
     * @var int $timeopen
     */
    private $timeopen;

    /**
     * @var int $timeclose
     */
    private $timeclose;

    /**
     * @var int $sessionid
     */
    private $sessionid;

    /**
     * @var string $degree
     */
    private $degree;

    /**
     * @var string $testtype
     */
    private $testtype;

    /**
     * @var string $testkind
     */
    private $testkind;

    /**
     * @var array $students
     */
    private $students = null;

    /**
     * Construct method.
     *
     * @param stdClass $uniexaminstance Some text to show how to pass data to a template.
     * @return void
     */
    public function __construct(stdClass $uniexaminstance) {
        $this->course = $uniexaminstance->course;
        $this->timeopen = $uniexaminstance->timeopen;
        $this->timeclose = $uniexaminstance->timeclose;
        $this->testtype = $uniexaminstance->testtype;
        $this->testtype = $uniexaminstance->testtype;
        $this->testkind = $uniexaminstance->testkind;
        $this->students = [];
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output The output renderer object.
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $fmt = new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
        $fmt->setPattern('H:m (EEEE dd MMMM YYYY)');
        $data['description'] = "🕒 ".$fmt->format($this->timeopen)." ➡️ ".$fmt->format($this->timeclose);

        $coursecontext = context_course::instance($this->course);
        $users = get_enrolled_users($coursecontext, 'moodle/course:isincompletionreports');

        foreach ($users  as $user ) {
            global $DB;
            array_push($this->students,
                array(  'id'=>$user->id,
                        'firstname'=>$user->firstname,
                        'lastname'=>$user->lastname,
                        'email'=>$user->email,
                        'picture' => new moodle_url('/user/pix.php/'.$user->id.'/f2.jpg'),
                )
            );
        }

        $data['students'] = new ArrayIterator( $this->students );
        return $data;
    }
}
