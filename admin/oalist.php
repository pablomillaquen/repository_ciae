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
 * List of all resources in course
 *
 * @package    local_repositoryciae
 * @copyright  2021 Pablo Millaquén
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
global $USER, $DB, $CFG;
$PAGE->set_context(context_system::instance());

if (!is_siteadmin()){
    header("Location: ". $CFG->wwwroot."/local/repositoryciae/");
    exit();
}

require_login();
$usercontext = context_user::instance($USER->id);
$data = new stdClass();
$data->locallink = $CFG->wwwroot."/local/repositoryciae/";
$oalist = $DB->get_records("local_repositoryciae_oa");

$grades = array(
    '1' => 'Primero básico',
    '2' => 'Segundo básico',
    '3' => 'Tercero básico',
    '4' => 'Cuarto básico',
    '5' => 'Quinto básico',
    '6' => 'Sexto básico',
    '7' => 'Séptimo básico',
    '8' => 'Octavo básico'
);

foreach($oalist as $oa){
    foreach($grades as $key=>$value){
        if($oa->grades_id == $key){
            $oa->grade = $value;
        }
    }
}
$data->oalist = array_values($oalist);

$PAGE->set_url('/local/repositoryciae/oalist.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae')." - ".get_string('oalist', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ".get_string('oalist', 'local_repositoryciae'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/oalist', $data);

echo $OUTPUT->footer();