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

 class stagetwodraft_form extends moodleform
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

        $mform->addElement('hidden', 'question', 'stagetwo');
        $mform->setType('question', PARAM_TEXT);

        $optionsaxis = array(
            '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
            '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
            '3' => 'Cosmovisión de los pueblos originarios.',
            '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
        );

        $select3 = $mform->addElement('select', 'axis', get_string('axis', 'local_repositoryciae'), $optionsaxis, []);

        $optionslearning = array(
            '1' => 'Escuchar',
            '2' => 'Hablar',
            '3' => 'Escribir',
            '4' => 'Leer'
        );

        $selectlearning = $mform->addElement('select', 'learning', get_string('learning', 'local_repositoryciae'), $optionslearning, []);
        $selectlearning->setMultiple(true);

        $mform->addElement('textarea', 'guidelines', get_string('guidelines', 'local_repositoryciae'), 'wrap="virtual" rows="6" cols="50"');
        $mform->addHelpButton('guidelines', 'guidelines', 'local_repositoryciae');

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }