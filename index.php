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

require('../../config.php');
global $USER, $DB, $CFG;
$PAGE->set_context(context_system::instance());
require_login();

$lang = current_language();

$optionsculturelang = array();

$json = file_get_contents('culturalcontent.json');
$obj = json_decode($json);
foreach($obj as $key=>$value){
    if($key == $lang){
        $optionsculturelang= $value;
    }
}

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

$materials = array();
$objmaterials = $DB->get_records('local_repositoryciae_files');
foreach($objmaterials as $mat){
    //Link
    if($mat->filetype == 1){
        $mat->linktype = 'newfile.php';
    }elseif($mat->filetype == 2){
        $mat->linktype = 'newlink.php';
    }else{
        $mat->linktype = 'collabfile.php';
    }
    //Image
    if($mat->image){
        $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $mat->image ." AND filesize > 1 AND component = 'local_repositoryciae'  LIMIT 1");
        if($fileimage){
            $url = moodle_url::make_pluginfile_url($fileimage->contextid, $fileimage->component, $fileimage->filearea, $fileimage->itemid, $fileimage->filepath, $fileimage->filename, false);
            $mat->imageurl = $url;
        }else{
            $mat->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
        }
    }else{
        $mat->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
    }
    //Material type
    foreach($optionsmaterials as $key => $value){
        if($mat->materialtype == $key){
            $mat->materialtype = $value;
        }
    }
    //Culture
    foreach($optionsculturelang as $key => $value){
        if($key == $mat->grades){
            foreach($value as $key2 => $value2){
                if($mat->culturalcontent == $key2){
                    $mat->culturalcontent = $value2;
                    
                }
            }
        }
    }
    //Grades
    foreach($optionsgrades as $key => $value){
        if($mat->grades == $key){
            $mat->grades = $value;
        }
    }
    
    array_push($materials, $mat);
}

$PAGE->set_url('/local/repositoryciae/index.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));

$data = new stdClass();
$data->materials = $materials;


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/mainrepository', $data);

echo $OUTPUT->footer();
