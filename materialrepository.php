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

$id = required_param('id', PARAM_INT); // material id
$data = new stdClass();

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

$optionsaxis = array(
    '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
    '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
    '3' => 'Cosmovisión de los pueblos originarios.',
    '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
); 

$materials = array();
$objmaterial = $DB->get_record('local_repositoryciae_files', ['id'=>$id]);

//Image
if($objmaterial->image){
    $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $objmaterial->image ." AND filesize > 1 LIMIT 1");
    if($fileimage){
        $objmaterial->imageurl = $CFG->wwwroot.'/draftfile.php/'.$fileimage->contextid.'/'.$fileimage->component.'/'.$fileimage->filearea.'/'.$fileimage->itemid.'/'.$fileimage->filename;   
    }else{
        $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
    }
}else{
    $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
}

//File
if($objmaterial->filetype==1){//It's a file
    if($objmaterial->link){
        $file = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $objmaterial->link ." AND filesize > 1 LIMIT 1");
        if($file){
            $objmaterial->fileurl = $CFG->wwwroot.'/draftfile.php/'.$file->contextid.'/'.$file->component.'/'.$file->filearea.'/'.$file->itemid.'/'.$file->filename;   
        }
    }
}elseif($objmaterial->filetype==2){
    $objmaterial->fileurl = $objmaterial->link;
}

//Material Types
foreach($optionsmaterials as $key => $value){
    if($objmaterial->materialtype == $key){
        $objmaterial->materialtype = $value;
    }
}
//Grades
foreach($optionsgrades as $key => $value){
    if($objmaterial->grades == $key){
        $objmaterial->grades = $value;
    }
}
//Culture
foreach($optionsculture as $key => $value){
    if($objmaterial->culturalcontent == $key){
        $objmaterial->culturalcontent = $value;
    }
}
//Axis
foreach($optionsaxis as $key => $value){
    if($objmaterial->axis == $key){
        $objmaterial->axis = $value;
    }
}

foreach($objmaterial as $mat){
    array_push($materials, $mat);
}

if(!$objmaterial->guidelines){
    $objmaterial->guidelines = "Sin información";
}

$data = new stdClass();
$data = $materials;


$PAGE->set_url('/local/repositoryciae/index.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));
echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/materialrepository', $objmaterial);

echo $OUTPUT->footer();
