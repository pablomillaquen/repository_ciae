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

require_once('../../config.php');
global $USER, $DB, $CFG; 

$id = optional_param('id', 0, PARAM_INT);
$question = optional_param('question', null, PARAM_TEXT);
$discussion_id = optional_param('discussion_id',0, PARAM_INT);
$lang = current_language();


$PAGE->set_url('/local/repositoryciae/selectanswer.php');
$PAGE->set_context(context_system::instance());
$contextid = $PAGE->context->id;
require_login();

$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));


    //get discussions
    $objdiscussion = $DB->get_record('forum_discussions', ['id'=>$discussion_id]);

    //Update answers to zero
    $objanswers = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "'.$question.'"  AND discussion_id = '.$discussion_id);
    foreach($objanswers as $obj){
        if($obj->selected == 1 ){
            $obj->selected = 0;
            $DB->update_record('local_repositoryciae_answer', $obj);
        }
    }
    //update new answer
    $objanswer = $DB->get_record('local_repositoryciae_answer', ['id'=>$id]);
    $objanswer->selected = 1;
    $DB->update_record('local_repositoryciae_answer', $objanswer);

    //Update draft
    $objdraft = $DB->get_record('local_repositoryciae_draft', ['discussion_id'=>$discussion_id]);
    if($objdraft){ //If draft exist, update
        $objdraft->$question = $objanswer->answer;
        $selectedanswer = $DB->update_record('local_repositoryciae_draft', $objdraft);
        
    }else{//If not, I create one
        $objdraft = new stdClass();
        $objdraft->$question = $objanswer->answer;
        $objdraft->discussion_id = $discussion_id;
        $selectedanswer = $DB->insert_record('local_repositoryciae_draft', $objdraft, true, false);
    }
    $element = 0;
    switch($question){
        case "grades":
            $element = 1;
            break;
        case "materialtype":
            $element = 2;
            break;
        case "linguistic":
            $element = 3;
            break;
        case "suggestions":
            $element = 4;
            break;            
        case "territory":
            $element = 5;
            break;
        case "axis":
            $element = 6;
            break;            
        case "learning":
            $element = 7;
            break;
        case "guidelines":
            $element = 8;
            break;
    }
    if($selectedanswer){
        redirect("/local/repositoryciae/drafts/element0".$element."admin.php?discussionid=".$discussion_id, 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
    }else{
        redirect("/local/repositoryciae/drafts/element0".$element."admin.php?discussionid=".$discussion_id, 'Hubo un error al actualizar la respuesta', 10,  \core\output\notification::NOTIFY_ERROR);
    }
