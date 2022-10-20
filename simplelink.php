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
$lang = current_language();

$PAGE->set_context(context_system::instance());
$contextid = $PAGE->context->id;

$id = optional_param('id', 0, PARAM_INT);

require_login();

require_once("forms/simplelink.php");

//$id = required_param('id', PARAM_INT); // course id

$PAGE->set_url('/local/repositoryciae/simplelink.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));
echo $OUTPUT->header();

$mform = new simplelink_form();
$toform = [];

if($mform->is_cancelled()){
    redirect("/local/repositoryciae/editrepositorylink.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($fromform->id != 0){
        //Update data
        $newfile = $DB->get_record('local_repositoryciae_links', ['id'=>$fromform->id]);
        $newfile->link = $fromform->link;
        $newfile->content = $fromform->content;
        $newfile->photo = $fromform->photo;
        $DB->update_record('local_repositoryciae_links', $newfile);
        $draftimageid = file_get_submitted_draft_itemid('photo');
        file_save_draft_area_files ( $draftimageid, $contextid, 'local_repositoryciae', 'photo', $draftimageid, array('subdirs' => 0, 'maxfiles' => 1) );
    }else{
        //Add new record
        $newfile = new stdClass();
        $newfile->link = $fromform->link;
        $newfile->content = $fromform->content;
        $newfile->photo = $fromform->photo;
        $storedfile = $DB->insert_record('local_repositoryciae_links', $newfile, true, false);
        $draftimageid = file_get_submitted_draft_itemid('photo');
        file_save_draft_area_files ( $draftimageid, $contextid, 'local_repositoryciae', 'photo', $draftimageid, array('subdirs' => 0, 'maxfiles' => 1) );
        
    }
    redirect("/local/repositoryciae/editrepositorylink.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}else{
    if($id){
        $toform = $DB->get_record('local_repositoryciae_links', ['id'=>$id]);
    }
    $mform->set_data($toform);
    
    $mform->display();
}

echo $OUTPUT->footer();
