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

 class elementdraft02_form extends moodleform
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

        $mform->addElement('hidden', 'question', 'materialtype');
        $mform->setType('question', PARAM_TEXT);

        $optionsmaterials = array(
            '1' => 'Guías de aprendizaje',
            '2' => 'Diccionarios',
            '3' => 'Cuadernillos de ejercicios',
            '4' => 'Textos (géneros textuales mapuches, poesía, obras dramáticas)',
            '5' => 'Fichas temáticas',
            '6' => 'Infografías',
            '7' => 'Imágenes/Fotografías',
            '8' => 'Organizadores gráficos (mapas conceptuales, esquemas, diagramas, etc.)',
            '9' => 'Manuales/libros',
            '10' => 'Videos',
            '11' => 'Canciones',
            '12' => 'Cápsulas audiovisuales',
            '13' => 'Grabaciones de audio',
            '14' => 'Juegos',
            '15' => 'Maquetas',
            '16' => 'Videojuegos',
            '17' => 'Mapas',
            '18' => 'Plataformas Web',
            '19' => 'Otros'
        );

        $mform->addElement('select', 'answer', get_string('materials', 'local_repositoryciae'), $optionsmaterials, []);

        $buttonArray = array();
        $buttonArray[] = $mform->createElement('submit', 'Guardar', 'Guardar');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }