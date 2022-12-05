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

require('../../config.php');
global $USER, $DB, $CFG;
$PAGE->set_context(context_system::instance());
$id = optional_param('id', 0, PARAM_INT);
$option = optional_param('option', null, PARAM_ALPHA);

require_login();
$usercontext = context_user::instance($USER->id);
require_once("forms/keywords.php");

$PAGE->set_url('/local/repositoryciae/keywords.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ".get_string('keywords', 'local_repositoryciae'));

$objkeywords = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_keys} ORDER BY id');

if($objkeywords){
    foreach($objkeywords as $k => $v){
        $sql = "SELECT COUNT(*) as num FROM mdl_local_repositoryciae_draft WHERE abstract LIKE '%".$objkeywords[$k]->keyword."%'";
        
        $key_times = $DB->get_records_sql($sql);    
        $objkeywords[$k]->times = $key_times[0]->num;
    }
}


if($id!=0){
    $file = $DB->get_record('local_repositoryciae_keys', ['id'=>$id]);
    if($option == "delete"){
        $params = array('id' => $id);
        $deletedfile = $DB->delete_records_select('local_repositoryciae_keys', 'id = :id', $params); 
        if($deletedfile){
            redirect("/local/repositoryciae/keywords.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
        }else{
            redirect("/local/repositoryciae/keywords.php", 'Hubo un error al eliminar la palabra clave', 10,  \core\output\notification::NOTIFY_ERROR);
        }
    }
}


$data = new stdClass();
$data->keywords = array_values($objkeywords);

$data->locallink = $CFG->wwwroot."/local/repositoryciae/";

$mform = new newkeyword_form();

if($fromform = $mform->get_data()){
    //Add new record
    $newfile = new stdClass();
    $newfile->keyword = $fromform->keyword;
    $storedfile = $DB->insert_record('local_repositoryciae_keys', $newfile, true, false);
    redirect("/local/repositoryciae/keywords.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}
        
echo $OUTPUT->header();


echo $OUTPUT->render_from_template('local_repositoryciae/keywords', $data);
$mform->display();
echo $OUTPUT->footer();