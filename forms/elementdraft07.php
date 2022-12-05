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

 class elementdraft07_form extends moodleform
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

        $mform->addElement('hidden', 'question', 'learning');
        $mform->setType('question', PARAM_TEXT);

        $optionslearning = array(
            '1' => 'Escuchar',
            '2' => 'Hablar',
            '3' => 'Escribir',
            '4' => 'Leer'
        );

        $selectlearning = $mform->addElement('select', 'answer', get_string('learning', 'local_repositoryciae'), $optionslearning, []);
        $selectlearning->setMultiple(true);

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }