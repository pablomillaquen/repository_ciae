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
 * @copyright  2022 Pablo Millaquén
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once("$CFG->libdir/formslib.php");

 class culturalcontent_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG, $DB;

        $data = new stdClass();

        // $objgrades = $DB->get_records('local_repositoryciae_grades');
        // $data->grades = array_values($objgrades);
        // $grades = array();
        // foreach($data->grades as $key => $value){
        //     $grades[$data->grades[$key]->id] = $data->grades[$key]->name;
        // }
        $grades = array(
            '1' => 'Primero básico',
            '2' => 'Segundo básico',
            '3' => 'Tercero básico',
            '4' => 'Cuarto básico',
            '5' => 'Quinto básico',
            '6' => 'Sexto básico',
            '7' => 'Séptimo básico',
            '8' => 'Octavo básico'
        );

        $mform = $this->_form;

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $select = $mform->addElement('select', 'grades_id', get_string('recomendedgrades', 'local_repositoryciae'), $grades, []);
        
        $attributes='size="300"';
        $mform->addElement('text', 'description_es', get_string('description_es', 'local_repositoryciae'), $attributes);
        $mform->setType('description_es', PARAM_TEXT);

        $attributes='size="300"';
        $mform->addElement('text', 'description_en', get_string('description_en', 'local_repositoryciae'), $attributes);
        $mform->setType('description_en', PARAM_TEXT);

        $attributes='size="300"';
        $mform->addElement('text', 'description_arn', get_string('description_arn', 'local_repositoryciae'), $attributes);
        $mform->setType('description_arn', PARAM_TEXT);

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }