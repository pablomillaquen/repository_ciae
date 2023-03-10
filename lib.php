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
 * Extra library for groups and groupings.
 *
 * @copyright 2006 The Open University, J.White AT open.ac.uk, Petr Skoda (skodak)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   core_group
 */

/*
 * INTERNAL FUNCTIONS - to be used by moodle core only
 * require_once $CFG->dirroot.'/group/lib.php' must be used
 */
defined('MOODLE_INTERNAL') || die();

/// CONSTANTS ///////////////////////////////////////////////////////////

//define('CONTEXT_MODULE', 10);


/**
 * Serve the new group form as a fragment.
 *
 * @param array $args List of named arguments for the fragment loader.
 * @return string
 */
function local_repositoryciae_output_fragment_new_discussions_form($args) {
    global $CFG;

    require_once($CFG->dirroot . '/local/repositoryciae/getdiscussions.php');
    //     $args = (object) $args;
    //     $context = $args->context;
    //     $o = '';

    //     $formdata = [];
    //     if (!empty($args->jsonformdata)) {
    //         $serialiseddata = json_decode($args->jsonformdata);
    //         parse_str($serialiseddata, $formdata);
    //     }

    //    // list($ignored, $course) = get_context_info_array($context->id);
    //     $group = new stdClass();
    //     $group->courseid = $course->id;

    //     require_capability('moodle/course:managegroups', $context);
    //     $editoroptions = [
    //         'maxfiles' => EDITOR_UNLIMITED_FILES,
    //         'maxbytes' => $course->maxbytes,
    //         'trust' => false,
    //         'context' => $context,
    //         'noclean' => true,
    //         'subdirs' => false
    //     ];
    //     $group = file_prepare_standard_editor($group, 'description', $editoroptions, $context, 'local_repositoryciae', 'description', null);

    $mform = new discussions_form(null, array('editoroptions' => $editoroptions), 'post', '', null, true, $formdata);
    // Used to set the courseid.
    //    $mform->set_data($group);

    if (!empty($args->jsonformdata)) {
        // If we were passed non-empty form data we want the mform to call validation functions and show errors.
        //$mform->is_validated();
    }

    return $mform->render();
}

function local_repositoryciae_extend_navigation(global_navigation $navigation) {
    $main_node = $navigation->add(get_string('pluginname', 'local_repositoryciae'), '/local/repositoryciae/');
    $main_node->nodetype = 1;
    $main_node->collapse = false;
    $main_node->forceopen = true;
    $main_node->isexpandable = false;
    $main_node->showinflatnavigation = true;
}

function local_repositoryciae_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    $lifetime = 60 * 60 * 24 * 36500;
    $filter = 0;
    $pathisstring = false;
	$forcedownload = false;
	$mimetype = '';
	$dontdie = false;
    $filename = array_pop ( $args );
	$itemid = array_pop ( $args );
    if ($filearea === 'attachment') {
        $forcedownload = true;
    }
   
    $fs = get_file_storage ();
	if (! $file = $fs->get_file ( $context->id, 'local_repositoryciae', $filearea, $itemid, '/', $filename )) {
		echo $context->id . ".." . $filearea . ".." . $itemid . ".." . $filename;
		echo "File really not found";
		send_file_not_found ();
	}
	send_file ( $file, $filename, $lifetime, $filter, $pathisstring, $forcedownload, $mimetype = '', $dontdie );
}
