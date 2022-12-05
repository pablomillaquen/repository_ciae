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
require_login();
$usercontext = context_user::instance($USER->id);
require_once("forms/forum.php");

$PAGE->set_url('/local/repositoryciae/selectforum.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ".get_string('select_forum', 'local_repositoryciae'));


//$objforum = $DB->get_records('forum');

$data = new stdClass();
//$data->forums = array_values($objforum);

$data->locallink = $CFG->wwwroot."/local/repositoryciae/";

$mform = new forum_form();
$toform = [];

if($mform->is_cancelled()){
    redirect("/local/repositoryciae/editrepository.php", '', 10);
}elseif($fromform = $mform->get_data()){
    if($fromform->id != 0){        
        //Update data
        $newforum = $DB->get_record('local_repositoryciae_admin', ['id'=>$fromform->id]);
        $newforum->keyname = $fromform->keyname;
        $newforum->data = $fromform->data;        
        $DB->update_record('local_repositoryciae_admin', $newforum);
    }else{
        //Add new record
        $newforum = new stdClass();
        $newforum->keyname = $fromform->keyname;
        $newforum->data = $fromform->data;
        $storedfile = $DB->insert_record('local_repositoryciae_admin', $newforum, true, false);
    }
    redirect("/local/repositoryciae/index.php", 'Cambios guardados', 10,  \core\output\notification::NOTIFY_SUCCESS);
}else{
    $toform = $DB->get_record('local_repositoryciae_admin', ['keyname'=>'forum'] );
    $mform->set_data($toform);
    
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}