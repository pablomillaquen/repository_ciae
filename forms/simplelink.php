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

 class simplelink_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('html', '<h3>Formulario para subir un enlace a página externa</h3><br><br>');

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $attributeslink=array('size'=>'100');
        $mform->addElement('text', 'link', get_string('link', 'local_repositoryciae'), $attributeslink);
        $mform->setType('link', PARAM_TEXT);

        $mform->addElement('textarea', 'content', get_string('content', 'local_repositoryciae'), 'wrap="virtual" rows="10" cols="50"');

        $mform->addElement('filepicker', 'photo', get_string('image', 'local_repositoryciae'), null, array('accepted_types' => '*'));

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $buttonArray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }