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

$discussionid = optional_param('discussionid',0,  PARAM_INT);
$lang = current_language();

$PAGE->requires->js_call_amd('local_repositoryciae/sendDiscussionId', 'init', array($discussionid));

$PAGE->requires->js_call_amd('local_repositoryciae/getDraftFiles', 'init', array($discussionid));

$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae') ." - ".get_string('draftform', 'local_repositoryciae'));

$PAGE->set_url('/local/repositoryciae/drafts/fulldraft.php?discussionid='.$discussionid);
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

require_once("../forms/fulldraft.php");
$mform = new fulldraft_form();
$toform = [];
if($mform->is_cancelled()){
    redirect("/local/repositoryciae/checkdraft.php?id=".discussionid, '', 10);
}elseif($fromform = $mform->get_data()){
    $gr = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "grades" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($gr){
        $gr->answer = $fromform->grades;
        $DB->update_record('local_repositoryciae_answer', $gr);
    }else{
        $newgrade = new stdClass();
        $newgrade->question = "grades";
        $newgrade->answer = $fromform->grades;
        $newgrade->user_id = $USER->id;
        $newgrade->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newgrade, true, false);
    }
    $m = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "materialtype" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($m){
        $m->answer = $fromform->materialtype;
        $DB->update_record('local_repositoryciae_answer', $m);
    }else{
        $newmaterialtype = new stdClass();
        $newmaterialtype->question = "materialtype";
        $newmaterialtype->answer = $fromform->materialtype;
        $newmaterialtype->user_id = $USER->id;
        $newmaterialtype->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newmaterialtype, true, false);
    }
    $li = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "linguistic" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($li){
        $li->answer = $fromform->linguistic;
        $DB->update_record('local_repositoryciae_answer', $li);
    }else{
        $newlinguistic = new stdClass();
        $newlinguistic->question = "linguistic";
        $newlinguistic->answer = $fromform->linguistic;
        $newlinguistic->user_id = $USER->id;
        $newlinguistic->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newlinguistic, true, false);
    }
    $s = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "suggestions" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($s){
        $s->answer = $fromform->suggestions;
        $DB->update_record('local_repositoryciae_answer', $s);
    }else{
        $newsuggestion = new stdClass();
        $newsuggestion->question = "suggestions";
        $newsuggestion->answer = $fromform->suggestions;
        $newsuggestion->user_id = $USER->id;
        $newsuggestion->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newsuggestion, true, false);
    }
    $t = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "territory" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($t){
        $t->answer = $fromform->territory;
        $DB->update_record('local_repositoryciae_answer', $t);
    }else{
        $newterritory = new stdClass();
        $newterritory->question = "territory";
        $newterritory->answer = $fromform->territory;
        $newterritory->user_id = $USER->id;
        $newterritory->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newterritory, true, false);
    }
    $a = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "axis" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($a){
        $a->answer = $fromform->axis;
        $DB->update_record('local_repositoryciae_answer', $a);
    }else{
        $newaxis = new stdClass();
        $newaxis->question = "axis";
        $newaxis->answer = $fromform->axis;
        $newaxis->user_id = $USER->id;
        $newaxis->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newaxis, true, false);
    }
    $le = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "learning" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($le){
        $le->answer = implode(",",$fromform->learning);
        $DB->update_record('local_repositoryciae_answer', $le);
    }else{
        $newlearning = new stdClass();
        $newlearning->question = "learning";
        $newlearning->answer = implode(",",$fromform->learning);
        $newlearning->user_id = $USER->id;
        $newlearning->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newlearning, true, false);
    }
    $gu = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "guidelines" AND discussion_id = '.$fromform->discussion_id.' AND user_id = '.$USER->id);
    if($gu){
        $gu->answer = $fromform->guidelines;
        $DB->update_record('local_repositoryciae_answer', $gu);
    }else{
        $newguideline = new stdClass();
        $newguideline->question = "guidelines";
        $newguideline->answer = $fromform->guidelines;
        $newguideline->user_id = $USER->id;
        $newguideline->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newguideline, true, false);
    }
    redirect("/local/repositoryciae/checkdraft.php?id=".$discussionid,'Cambios guardados', 1,  \core\output\notification::NOTIFY_SUCCESS);
}else{
    $formdata = new stdClass();
    $gr = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "grades" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($gr){ $formdata->grades = $gr->answer; }
    $m = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "materialtype" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($m) {$formdata->materialtype = $m->answer;}
    $li = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "linguistic" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($li){$formdata->linguistic = $li->answer;}
    $s = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "suggestions" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($s){$formdata->suggestions = $s->answer;}
    $t = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "territory" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($t){$formdata->territory = $t->answer;}
    $a = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "axis" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($a){$formdata->axis = $a->answer;}
    $le = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "learning" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($le){$formdata->learning = $le->answer;}
    $gu = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "guidelines" AND discussion_id = '.$discussionid.' AND user_id = '.$USER->id);
    if($gu){$formdata->guidelines = $gu->answer;}
    $toform = $formdata;
    
    $mform->set_data($toform);
                    
    $mform->display();
    echo $OUTPUT->footer();
}
             
