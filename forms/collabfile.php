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

 class collabfile_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('html', '<h3>Formulario para subir materiales desde el foro de colaboración</h3><br><br>');

        $attributes=array('size'=>'20');
        $mform->addElement('text', 'name', get_string('filename', 'local_repositoryciae'), $attributes);
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('textarea', 'abstract', get_string('abstract', 'local_repositoryciae'), 'wrap="virtual" rows="10" cols="50"');

        $mform->addElement('html', '<div id="fitem_id_search" class="form-group row  fitem femptylabel"><div class="col-md-3"><span class="float-sm-right text-nowrap"></span></div><div class="col-md-9 form-inline felement" data-fieldtype="button"><input type="submit" name="act_showcreateorphangroupform" id="showcreateorphangroupform" data-action="createcollabmodal" value="'.get_string("searchfile", "local_repositoryciae").'" class="btn btn-default" /><div class="form-control-feedback invalid-feedback" id="id_error_search"></div></div></div>');
        
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
        //$select->setMultiple(true);

        $optionsterritories = array(
            '1' => 'Completar...'
        );

        $mform->addElement('select', 'territories', get_string('territories', 'local_repositoryciae'), $optionsterritories, []);
        
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

        $mform->addElement('select', 'materials', get_string('materials', 'local_repositoryciae'), $optionsmaterials, []);

        $optionsoa = array(
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22'
        );

        $mform->addElement('select', 'oa', get_string('oa', 'local_repositoryciae'), $optionsoa, []);

        $optionsculture = array(
            '1' => 'Allkütun zugu',
            '2' => 'Mapuche az chaliwün',
            '3' => 'Fillke mapu ñi az epewkantun mew',
            '4' => 'Mapuche lhawen epewkantun mew',
            '5' => 'Chalintukuwün, Witxankontun egu mapuche pepilüwün',
            '6' => 'Mapuche awkiñ',
            '7' => 'Úlkantun kimün',
            '8' => 'Mapuche Úlkantun'
        );

        $select2 = $mform->addElement('select', 'culture', get_string('culture', 'local_repositoryciae'), $optionsculture, []);
        //$select2->setMultiple(true);

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