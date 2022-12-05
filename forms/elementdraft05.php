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

 class elementdraft05_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG, $USER;
        $user_id = $USER->id;
        $mform = $this->_form;
      
        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'user_id', $user_id);
        $mform->setType('user_id', PARAM_INT);

        $attributes=array('id'=>'discussion_id');
        $mform->addElement('hidden', 'discussion_id', 0, $attributes);
        $mform->setType('discussion_id', PARAM_INT);

        $mform->addElement('hidden', 'question', 'territory');
        $mform->setType('question', PARAM_TEXT);

        $optionsterritories = array(
            '1' => 'Pewenche',
            '2' => 'Wentenche',
            '3' => 'Nagche',
            '4' => 'Lafkenche',
            '5' => 'Williche'
        );


        $mform->addElement('select', 'answer', get_string('territories', 'local_repositoryciae'), $optionsterritories, []);
        
        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }