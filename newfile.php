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
$lang = current_language();


$PAGE->set_url('/local/repositoryciae/newfile.php');
$PAGE->set_context(context_system::instance());
$contextid = $PAGE->context->id;
require_login();

require_once("forms/newfile.php");
$PAGE->requires->js_call_amd('local_repositoryciae/conditional', 'init', array($lang));


$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));


$mform = new newfile_form();
$toform = [];

if($mform->is_cancelled()){
    redirect("/local/repositoryciae/editrepository.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($fromform->id != 0){        
        //Update data
        $newfile = $DB->get_record('local_repositoryciae_files', ['id'=>$fromform->id]);
        $newfile->name = $fromform->name;
        $newfile->abstract = $fromform->abstract;
        $newfile->grades = $fromform->grades;
        $newfile->territory = $fromform->territory;
        $newfile->materialtype = $fromform->materialtype;
        $newfile->culturalcontent = $fromform->culturalcontent;
        $newfile->link = $fromform->link;
        $newfile->filetype = 1; //It's a file
        $newfile->image = $fromform->image;
        $newfile->oa = $fromform->oa;
        $newfile->abstract = $fromform->abstract;
        $newfile->axis = $fromform->axis;
        $newfile->linguistic = $fromform->linguistic;
        $newfile->suggestions = $fromform->suggestions;
        $newfile->learning = implode(",",$fromform->learning);
        $newfile->guidelines = $fromform->guidelines;
        $newfile->user_id = $USER->id;
        $DB->update_record('local_repositoryciae_files', $newfile);
        $draftlinkid = file_get_submitted_draft_itemid('link');
        $draftimageid = file_get_submitted_draft_itemid('image');
        file_save_draft_area_files ( $draftlinkid, $contextid, 'local_repositoryciae', 'attachment', $draftlinkid, array('subdirs' => 0, 'maxfiles' => 5) );
        file_save_draft_area_files ( $draftimageid, $contextid, 'local_repositoryciae', 'image', $draftimageid, array('subdirs' => 0, 'maxfiles' => 1) );
        
    }else{
        //Add new record
        $newfile = new stdClass();
        $newfile->name = $fromform->name;
        $newfile->abstract = $fromform->abstract;
        $newfile->grades = $fromform->grades;
        $newfile->territory = $fromform->territory;
        $newfile->materialtype = $fromform->materialtype;
        $newfile->culturalcontent = $fromform->culturalcontent;
        $newfile->link = $fromform->link;
        $newfile->filetype = 1; //It's a file
        $newfile->image = $fromform->image;
        $newfile->oa = $fromform->oa;
        $newfile->abstract = $fromform->abstract;
        $newfile->axis = $fromform->axis;
        $newfile->linguistic = $fromform->linguistic;
        $newfile->suggestions = $fromform->suggestions;
        $newfile->learning = implode(",",$fromform->learning);
        $newfile->guidelines = $fromform->guidelines;
        $newfile->user_id = $USER->id;
        $storedfile = $DB->insert_record('local_repositoryciae_files', $newfile, true, false);
        $draftlinkid = file_get_submitted_draft_itemid('link');
        $draftimageid = file_get_submitted_draft_itemid('image');
        file_save_draft_area_files ( $draftlinkid, $contextid, 'local_repositoryciae', 'attachment', $draftlinkid, array('subdirs' => 0, 'maxfiles' => 5) );
        file_save_draft_area_files ( $draftimageid, $contextid, 'local_repositoryciae', 'image', $draftimageid, array('subdirs' => 0, 'maxfiles' => 1) );
    }
    redirect("/local/repositoryciae/editrepository.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}else{
    if($id != 0){
        $toform = $DB->get_record('local_repositoryciae_files', ['id'=>$id]);
    }
    $mform->set_data($toform);
    
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}


