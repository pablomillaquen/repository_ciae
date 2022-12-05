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
$contextid = $PAGE->context->id;
$id = required_param('id', PARAM_INT);

require_login();
$usercontext = context_user::instance($USER->id);
$PAGE->requires->js_call_amd('local_repositoryciae/getDraftFiles', 'init', array($id));

$data = new stdClass();
$data->locallink = $CFG->wwwroot."/";
$data->id = $id;
if (is_siteadmin()){
    $PAGE->set_url('/local/repositoryciae/statedraft.php');
    $PAGE->set_title(get_string('title', 'local_repositoryciae'));
    $PAGE->set_heading(get_string('title', 'local_repositoryciae'));

    echo $OUTPUT->header();
    
    require_once("forms/statedraft.php");

    $mform = new statedraft_form();
    $toform = [];
    if($mform->is_cancelled()){
        redirect("/local/repositoryciae/sharedfiles.php", '', 10);
    }elseif($fromform = $mform->get_data()){
        if($fromform->id != 0){     
            //Update data
            $draft = $DB->get_record('local_repositoryciae_draft', ['id'=>$fromform->id]);
            $draft->name = $fromform->name;
            $draft->grades = $fromform->grades;
            $draft->territory = $fromform->territory;
            $draft->materialtype = $fromform->materialtype;
            $draft->culturalcontent = $fromform->culturalcontent;
            $draft->link = $fromform->link;
            $draft->filetype = 3;
            $draft->image = $fromform->image;
            $draft->oa = $fromform->oa;
            $draft->abstract = $fromform->abstract;
            $draft->axis = $fromform->axis;
            $draft->linguistic = $fromform->linguistic;
            $draft->suggestions = $fromform->suggestions;
            $draft->learning = implode(",",$fromform->learning);
            $draft->guidelines = $fromform->guidelines;
            $draft->discussion_id = $fromform->discussion_id;
            $draft->user_id = $USER->id;
            $storeddraft = $DB->update_record('local_repositoryciae_draft', $draft);
            $draftimageid = file_get_submitted_draft_itemid('image');
            file_save_draft_area_files ( $draftimageid, $contextid, 'local_repositoryciae', 'image', $draftimageid, array('subdirs' => 0, 'maxfiles' => 1) );

            if($fromform->state_id == 3){//Se debe subir a repositorio
                $files = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                if($files){                    
                    $files->name = $fromform->name;
                    $files->grades = $fromform->grades;
                    $files->territory = $fromform->territory;
                    $files->materialtype = $fromform->materialtype;
                    $files->culturalcontent = $fromform->culturalcontent;
                    $files->link = $fromform->link;
                    $files->filetype = 3;
                    $files->image = $fromform->image;
                    $files->oa = $fromform->oa;
                    $files->abstract = $fromform->abstract;
                    $files->axis = $fromform->axis;
                    $files->linguistic = $fromform->linguistic;
                    $files->suggestions = $fromform->suggestions;
                    $files->learning = implode(",",$fromform->learning);
                    $files->guidelines = $fromform->guidelines;
                    $files->discussion_id = $fromform->discussion_id;
                    $files->user_id = $USER->id;
                    $storedfile = $DB->update_record('local_repositoryciae_files', $files);
                    $filescheck = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                    $fromform->files_draft_id = $filescheck->id;
                }else{
                    $newfile = new stdClass();
                    $newfile->name = $fromform->name;
                    $newfile->grades = $fromform->grades;
                    $newfile->territory = $fromform->territory;
                    $newfile->materialtype = $fromform->materialtype;
                    $newfile->culturalcontent = $fromform->culturalcontent;
                    $newfile->link = $fromform->link;
                    $newfile->filetype = 3;
                    $newfile->image = $fromform->image;
                    $newfile->oa = $fromform->oa;
                    $newfile->abstract = $fromform->abstract;
                    $newfile->axis = $fromform->axis;
                    $newfile->linguistic = $fromform->linguistic;
                    $newfile->suggestions = $fromform->suggestions;
                    $newfile->learning = implode(",",$fromform->learning);
                    $newfile->guidelines = $fromform->guidelines;
                    $newfile->discussion_id = $fromform->discussion_id;
                    $newfile->user_id = $USER->id;
                    
                    $storedfile = $DB->insert_record('local_repositoryciae_files', $newfile, true, false);
                    $filescheck = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                    $fromform->files_draft_id = $filescheck->id;
                }
            }

            $state = $DB->get_record('local_repositoryciae_d_state', ['discussion_id'=>$fromform->discussion_id]);
            if($state){
                $state->discussion_id = $fromform->discussion_id;        
                $state->files_draft_id = $fromform->files_draft_id;
                $state->state_id = $fromform->state_id;
                $storedfile = $DB->update_record('local_repositoryciae_d_state', $state);
            }else{
                $newstate = new stdClass();
                $newstate->discussion_id = $fromform->discussion_id;        
                $newstate->files_draft_id = $fromform->files_draft_id;
                $newstate->state_id = $fromform->state_id;
                $storedfile = $DB->insert_record('local_repositoryciae_d_state', $newstate, true, false);
            }  
        }else{
            $draft = new stdClass();
            $draft->name = $fromform->name;
            $draft->grades = $fromform->grades;
            $draft->territory = $fromform->territory;
            $draft->materialtype = $fromform->materialtype;
            $draft->culturalcontent = $fromform->culturalcontent;
            $draft->link = $fromform->link;
            $draft->filetype = 3;
            $draft->image = $fromform->image;
            $draft->oa = $fromform->oa;
            $draft->abstract = $fromform->abstract;
            $draft->axis = $fromform->axis;
            $draft->linguistic = $fromform->linguistic;
            $draft->suggestions = $fromform->suggestions;
            $draft->learning = implode(",",$fromform->learning);;
            $draft->guidelines = $fromform->guidelines;
            $draft->discussion_id = $fromform->discussion_id;
            $draft->user_id = $USER->id;
            $storedfile = $DB->insert_record('local_repositoryciae_draft', $draft, true, false);
          
            if($fromform->state_id == 3){//Se debe subir a repositorio
                $files = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                if($files){                    
                    $files->name = $fromform->name;
                    $files->grades = $fromform->grades;
                    $files->territory = $fromform->territory;
                    $files->materialtype = $fromform->materialtype;
                    $files->culturalcontent = $fromform->culturalcontent;
                    $files->link = $fromform->link;
                    $files->filetype = 3;
                    $files->image = $fromform->image;
                    $files->oa = $fromform->oa;
                    $files->abstract = $fromform->abstract;
                    $files->axis = $fromform->axis;
                    $files->linguistic = $fromform->linguistic;
                    $files->suggestions = $fromform->suggestions;
                    $files->learning = implode(",",$fromform->learning);
                    $files->guidelines = $fromform->guidelines;
                    $files->discussion_id = $fromform->discussion_id;
                    $files->user_id = $USER->id;
                    $storedfile = $DB->update_record('local_repositoryciae_files', $files);
                    
                    $filescheck = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                    $fromform->files_draft_id = $filescheck->id;
                }else{
                    $newfile = new stdClass();
                    $newfile->name = $fromform->name;
                    $newfile->grades = $fromform->grades;
                    $newfile->territory = $fromform->territory;
                    $newfile->materialtype = $fromform->materialtype;
                    $newfile->culturalcontent = $fromform->culturalcontent;
                    $newfile->link = $fromform->link;
                    $newfile->filetype = 3;
                    $newfile->image = $fromform->image;
                    $newfile->oa = $fromform->oa;
                    $newfile->abstract = $fromform->abstract;
                    $newfile->axis = $fromform->axis;
                    $newfile->linguistic = $fromform->linguistic;
                    $newfile->suggestions = $fromform->suggestions;
                    $newfile->learning = implode(",",$fromform->learning);
                    $newfile->guidelines = $fromform->guidelines;
                    $newfile->discussion_id = $fromform->discussion_id;
                    $newfile->user_id = $USER->id;
                    
                    $storedfile = $DB->insert_record('local_repositoryciae_files', $newfile, true, false);
                    $filescheck = $DB->get_record('local_repositoryciae_files', ['discussion_id'=>$fromform->discussion_id]);
                    $fromform->files_draft_id = $filescheck->id;
                }
                
            }
            $state = $DB->get_record('local_repositoryciae_d_state', ['discussion_id'=>$fromform->discussion_id]);
            if($state){
                $state->discussion_id = $fromform->discussion_id;        
                $state->files_draft_id = $fromform->files_draft_id;
                $state->state_id = $fromform->state_id;
                $storedfile = $DB->update_record('local_repositoryciae_d_state', $state);
            }else{
                $newstate = new stdClass();
                $newstate->discussion_id = $fromform->discussion_id;        
                $newstate->files_draft_id = $fromform->files_draft_id;
                $newstate->state_id = $fromform->state_id;
                $storedfile = $DB->insert_record('local_repositoryciae_d_state', $newstate, true, false);
            }  
        }
        redirect("/local/repositoryciae/sharedfiles.php",'Cambios guardados', 1,  \core\output\notification::NOTIFY_SUCCESS);
    }else{
        $draft = $DB->get_record('local_repositoryciae_draft', ['discussion_id'=>$id]);
        
        if($draft){
            $states = $DB->get_record('local_repositoryciae_d_state', ['discussion_id'=>$draft->discussion_id]);
            $toform = new stdClass();
            $toform->id = $draft->id;
            $toform->name = $draft->name;
            $toform->grades = $draft->grades;
            $toform->territory = $draft->territory;
            $toform->materialtype = $draft->materialtype;
            $toform->culturalcontent = $draft->culturalcontent;
            $toform->link = $draft->link;
            $toform->filetype = $draft->filetype;
            $toform->image = $draft->image;
            $toform->oa = $draft->oa;
            $toform->abstract = $draft->abstract;
            $toform->axis = $draft->axis;
            $toform->linguistic = $draft->linguistic;
            $toform->suggestions = $draft->suggestions;
            $toform->learning = $draft->learning;
            $toform->guidelines = $draft->guidelines;
            $toform->discussion_id = $draft->discussion_id;
            $toform->user_id = $USER->id;

            if($states){
                $toform->files_draft_id = $states->files_draft_id;
                $toform->state_id = $states->state_id;
            }
            
        }
        $mform->set_data($toform);
                    
    $mform->display();
    echo $OUTPUT->footer();
}

    // echo $OUTPUT->render_from_template('local_repositoryciae/statedraft', $data);

    // echo $OUTPUT->footer();
}else{
    header("Location: ". $CFG->wwwroot."/local/repositoryciae/");
    exit();
} 

