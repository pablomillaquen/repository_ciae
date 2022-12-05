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

$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae') ." - ".get_string('draftform', 'local_repositoryciae'));

$PAGE->set_url('/local/repositoryciae/element02.php?discussionid='.$discussionid);
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

require_once("../forms/elementdraft02.php");
$mform = new elementdraft02_form();
$toform = [];
if($mform->is_cancelled()){
    redirect("/local/repositoryciae/sharedfiles.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($fromform->id != 0){        
        //Update data
        $newdraft = $DB->get_record('local_repositoryciae_answer', ['id'=>$fromform->id]);
        $newdraft->user_id = $fromform->user_id;
        $newdraft->question = $fromform->question;
        $newdraft->answer = $fromform->answer;
        $newdraft->discussion_id = $fromform->discussion_id;
        $DB->update_record('local_repositoryciae_answer', $newdraft);
    }else{
        //Add new record
        $newdraft = new stdClass();
        $newdraft->user_id = $fromform->user_id;
        $newdraft->question = $fromform->question;
        $newdraft->answer = $fromform->answer;
        $newdraft->discussion_id = $fromform->discussion_id;
        $storedfile = $DB->insert_record('local_repositoryciae_answer', $newdraft, true, false);
    }
    redirect("/local/repositoryciae/sharedfiles.php",'Cambios guardados', 1,  \core\output\notification::NOTIFY_SUCCESS);
}else{
        $toform = $DB->get_record('local_repositoryciae_answer', ['question'=>'materialtype','discussion_id'=>$discussionid, 'user_id'=>$USER->id]);
    $mform->set_data($toform);
                    
    $mform->display();
    echo $OUTPUT->footer();
}
             
