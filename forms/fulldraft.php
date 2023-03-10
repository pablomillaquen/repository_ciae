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

 class fulldraft_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG, $USER;
        $user_id = $USER->id;
        $mform = $this->_form;
        $mform->addElement('html', '<h3>Formulario para subir materiales compartidos</h3><br><br>');

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $attr=array('id'=>'discussion_id');
        $mform->addElement('hidden', 'discussion_id', 0, $attr);
        $mform->setType('discussion_id', PARAM_INT);

        $mform->addElement('hidden', 'user_id', $user_id);
        $mform->setType('user_id', PARAM_INT);

        $optionsgrades = array(
            '1' => 'Primero básico',
            '2' => 'Segundo básico',
            '3' => 'Tercero básico',
            '4' => 'Cuarto básico',
            '5' => 'Quinto básico',
            '6' => 'Sexto básico',
            '7' => 'Séptimo básico',
            '8' => 'Octavo básico'
        );

        $select = $mform->addElement('select', 'grades', get_string('recomendedgrades', 'local_repositoryciae'), $optionsgrades, []);
    
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

        $mform->addElement('select', 'materialtype', get_string('materials', 'local_repositoryciae'), $optionsmaterials, []);

        $mform->addElement('textarea', 'linguistic', get_string('linguistic', 'local_repositoryciae'), 'wrap="virtual" rows="6" cols="50"');
        $mform->addHelpButton('linguistic', 'linguistic', 'local_repositoryciae');

        $mform->addElement('textarea', 'suggestions', get_string('suggestions', 'local_repositoryciae'), 'wrap="virtual" rows="6" cols="50"');
        $mform->addHelpButton('suggestions', 'suggestions', 'local_repositoryciae');

        $optionsterritories = array(
            '1' => 'Pewenche',
            '2' => 'Wentenche',
            '3' => 'Nagche',
            '4' => 'Lafkenche',
            '5' => 'Williche'
        );

        $mform->addElement('select', 'territory', get_string('territories', 'local_repositoryciae'), $optionsterritories, []);
        
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
        $buttonArray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonArray, 'buttonar', '', '', false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }