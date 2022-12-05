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

$PAGE->set_context(context_system::instance());

require_login();
require_once("../forms/elementdraft02.php");

$discussionid = optional_param('discussionid',0,  PARAM_INT);
$lang = current_language();

$PAGE->requires->js_call_amd('local_repositoryciae/sendDiscussionId', 'init', array($discussionid));

$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae') ." - ".get_string('draftform', 'local_repositoryciae'));

$PAGE->set_url('/local/repositoryciae/element01admin.php?discussionid='.$discussionid);
$PAGE->set_context(context_system::instance());

$data = new stdClass();
$data->locallink = $CFG->wwwroot."/local/repositoryciae/";
$data->question = "materialtype";

$answers = array();
$objdiscussion = $DB->get_record_sql('SELECT * FROM {forum_discussions} WHERE id = '.$discussionid);
$data->objdiscussion_id = $discussionid;
$optionsmaterials = array(
    '1' => 'Guías de aprendizaje',
    '2' => 'Diccionarios',
    '3' => 'Cuadernillos de ejercicios',
    '4' => 'Textos (géneros textuales mapuches, poesía, obras dramáticas)',
    '5' => 'Fichas temáticas',
    '6' => 'Infografías',
    '7' => 'Imágenes/Fotografías',
    '8' => 'Organizadores gráficos (mapas conceptuales, esquemas, diagramas, etc.)',
    '9' => 'Manuales/libros',
    '10' => 'Videos',
    '11' => 'Canciones',
    '12' => 'Cápsulas audiovisuales',
    '13' => 'Grabaciones de audio',
    '14' => 'Juegos',
    '15' => 'Maquetas',
    '16' => 'Videojuegos',
    '17' => 'Mapas',
    '18' => 'Plataformas Web',
    '19' => 'Otros'
);

$data->string_title = get_string('materials', 'local_repositoryciae');

$objanswers = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "materialtype" AND discussion_id = '.$discussionid);

if($objanswers){
    foreach($objanswers as $objanswer){
        $user = $DB->get_record_sql('SELECT * FROM {user} WHERE id='.$objanswer->user_id);
        $objanswer->answer = $optionsmaterials[$objanswer->answer];
        $objanswer->username = $user->firstname." ".$user->lastname;
        array_push($answers, $objanswer);
    }
    $data->answers = $answers;
    
}

$mform = new elementdraft02_form();

if($fromform = $mform->get_data()){
    //Update answers to zero
    $objanswers = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "materialtype" AND discussion_id = '.$fromform->discussion_id);
    foreach($objanswers as $obj){
        if($obj->selected == 1 ){
            $obj->selected = 0;
            $DB->update_record('local_repositoryciae_answer', $obj);
        }
    }
    //Add new record
    $newelement = new stdClass();
    $newelement->question = "materialtype";
    $newelement->answer = $fromform->answer;
    $newelement->user_id = $USER->id;
    $newelement->discussion_id = $fromform->discussion_id;
    $newelement->selected = 1;
    $storedfile = $DB->insert_record('local_repositoryciae_answer', $newelement, true, false);

     //Update draft
     $objdraft = $DB->get_record('local_repositoryciae_draft', ['discussion_id'=>$fromform->discussion_id]);
     if($objdraft){ //If draft exist, update
         $objdraft->materialtype = $fromform->answer;
         $selectedanswer = $DB->update_record('local_repositoryciae_draft', $objdraft);
     }else{//If not, I create one
         $objdraft = new stdClass();
         $objdraft->materialtype = $fromform->answer;
         $objdraft->discussion_id = $fromform->discussion_id;
         $selectedanswer = $DB->insert_record('local_repositoryciae_draft', $objdraft, true, false);
     }
    redirect("/local/repositoryciae/drafts/element02admin.php?discussionid=".$fromform->discussion_id, 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}
        
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_repositoryciae/element01admin', $data);
     
$mform->display();    
echo $OUTPUT->footer();

             
