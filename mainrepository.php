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

require_login();
$materials = array();
$objmaterials = $DB->get_records('local_repositoryciae_files');
foreach($objmaterials as $mat){
    if($mat->image){
        $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $mat->image . " LIMIT 1");
        //$mat->imageurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_fielpath(), $file->get_filename(), false);
        //$mat->imageurl = $CFG->wwwroot.'/pluginfile.php/5/local_repositoryciae/draft/'.$fileimage->filename; 
        // $fs = get_file_storage();
        // $file = $fs->get_file(5, 'local_repositoryciae', 'draft', $mat->image,'/', $fileimage->filename);
        
    }
    array_push($materials, $mat);
}


$PAGE->set_url('/local/repositoryciae/index.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));

$data = new stdClass();
$data->materials = $materials;


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/mainrepository', $data);

echo $OUTPUT->footer();
