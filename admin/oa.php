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

require_once('../../../config.php');
global $USER, $DB, $CFG; 

$id = optional_param('id', 0, PARAM_INT);
$lang = current_language();


$PAGE->set_url('/local/repositoryciae/oa.php');
$PAGE->set_context(context_system::instance());
$contextid = $PAGE->context->id;
require_login();

if (!is_siteadmin()){
    header("Location: ". $CFG->wwwroot."/local/repositoryciae/");
    exit();
}

require_once("../forms/oa.php");

$PAGE->set_title(get_string('title', 'local_repositoryciae')." - ".get_string('oalist', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ".get_string('oalist', 'local_repositoryciae'));


$mform = new oa_form();
$toform = [];

if($mform->is_cancelled()){
    redirect("/local/repositoryciae/admin/oalist.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($fromform->id != 0){        
        //Update data
        $oa = $DB->get_record('local_repositoryciae_oa', ['id'=>$fromform->id]);
        $oa->grades_id = $fromform->grades_id;
        $oa->description = $fromform->description;
        $DB->update_record('local_repositoryciae_oa', $oa);
    }else{
        //Add new record
        $oa = new stdClass();
        $oa->grades_id = $fromform->grades_id;
        $oa->description = $fromform->description;
        $storedfile = $DB->insert_record('local_repositoryciae_oa', $oa, true, false);
    }
    redirect("/local/repositoryciae/admin/oalist.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}else{
    if($id != 0){
        $toform = $DB->get_record('local_repositoryciae_oa', ['id'=>$id]);
    }
    $mform->set_data($toform);
    
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}


