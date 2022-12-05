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

 require_once("$CFG->libdir/formslib.php");

 class forum_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG, $DB;

        $data = new stdClass();
        $objforum = $DB->get_records('forum');
        $data->forums = array_values($objforum);
        $forums = array();
        foreach($data->forums as $key => $value){
            $forums[$data->forums[$key]->id] = $data->forums[$key]->name;
        }


        $mform = $this->_form;

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'keyname', 'forum');
        $mform->setType('keyname', PARAM_TEXT);

        $select = $mform->addElement('select', 'data', get_string('select_forum_label', 'local_repositoryciae'), $forums, []);

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }