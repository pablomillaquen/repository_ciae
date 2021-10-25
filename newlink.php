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

$id = optional_param('id', '', PARAM_TEXT);

require_login();

require_once("forms/newlink.php");

//$id = required_param('id', PARAM_INT); // course id

$PAGE->set_url('/local/repositoryciae/newlink.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));
echo $OUTPUT->header();

$mform = new newlink_form();
$toform = [];

if($mform->is_cancelled()){
    redirect("/local/repositoryciae/index.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($id){
        //Update data
        $newfile = $DB->get_record('local_repositoryciae_files', ['id'=>$id]);
        $newfile->name = $fromform->name;
        $newfile->abstract = $fromform->abstract;
        $newfile->grades = $fromform->grades;
        $newfile->territory = $fromform->territory;
        $newfile->materialtype = $fromform->materialtype;
        $newfile->culturalcontent = $fromform->culturalcontent;
        $newfile->link = $fromform->link;
        $newfile->filetype = 2; //It's a link
        $newfile->image = $fromform->image;
        $newfile->oa = $fromform->oa;
        $DB->update_record('local_repositoryciae_files', $newfile);
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
        $newfile->filetype = 2; //It's a link
        $newfile->image = $fromform->image;
        $newfile->oa = $fromform->oa;
        $storedfile = $DB->insert_record('local_repositoryciae_files', $newfile, true, false);
    }
}else{
    if($id){
        $toform = $DB->get_record('local_repositoryciae_files', ['id'=>$id]);
    }
    $mform->set_data($toform);
    
    $mform->display();
}

//echo $OUTPUT->render_from_template('local_repositoryciae/newfile', []);

echo $OUTPUT->footer();
