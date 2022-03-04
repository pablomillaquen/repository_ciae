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

require_once('../../config.php');
global $USER, $DB, $CFG; 
$lang = current_language();
$id = optional_param('id', 0, PARAM_INT);

global $CFG, $DB;

$sql = 'SELECT * FROM {local_repositoryciae_files} WHERE id = ?';
$paramsDB = array($id); //array($itemid);
$db_result = $DB->get_records_sql($sql,$paramsDB);

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

$optionsterritories = array(
    '1' => 'Pewenche',
    '2' => 'Wentenche',
    '3' => 'Nagche',
    '4' => 'Lafkenche',
    '5' => 'Williche'
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

$json = file_get_contents('culturalcontent.json');
$objcultural = json_decode($json);
foreach($objcultural as $key=>$value){
    if($key == $lang){
        $optionsculturelang= $value;
    }
}



$optionsaxis = array(
    '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
    '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
    '3' => 'Cosmovisión de los pueblos originarios.',
    '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
);

foreach($db_result as $db_row) {
    foreach($optionsculturelang as $key => $value){
        if($key == $db_row->grades){
            foreach($value as $key2 => $value2){
                if($db_row->culturalcontent == $key2){
                    $db_row->culturalcontent = $value2;
                    
                }
            }
        }
    }
    foreach($optionsgrades as $key => $value) {
        if($db_row->grades == $key) {
            $db_row->grades = $value;
        }
    }
    foreach($optionsterritories as $key => $value) {
        if($db_row->territory == $key) {
            $db_row->territory = $value;
        }
    }
    foreach($optionsmaterials as $key => $value) {
        if($db_row->materialtype == $key) {
            $db_row->materialtype = $value;
        }
    }
    
    foreach($optionsaxis as $key => $value) {
        if($db_row->axis == $key) {
            $db_row->axis = $value;
        }
    }
    if($db_row->image){
        $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $db_row->image ." AND filesize > 1 AND component = 'local_repositoryciae'  LIMIT 1");
        if($fileimage){
            $url = moodle_url::make_pluginfile_url($fileimage->contextid, $fileimage->component, $fileimage->filearea, $fileimage->itemid, $fileimage->filepath, $fileimage->filename, false);
            $db_row->imageurl = $url;
        }else{
            $db_row->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
        }
    }else{
        $db_row->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
    }
}


require_once($CFG->libdir . '/pdflib.php');

$pdf = new \pdf();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
// set document information
$pdf->SetAuthor('Repositorio CIAE');
$pdf->SetTitle('Ficha didáctica');
$pdf->SetSubject('Educadores Tradicionales');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$obj = new stdClass();
$obj = $db_result[$id];

$pdf->WriteHTML('<h1>'.$obj->culturalcontent.'</h1>
<table border="0">
<tr>
    <td>
        <tr>
            <td><b>Nombre:</b>'.$obj->name.'</td>
        </tr>
        <tr>
            <td><b>Curso en que puedo utilizarlo:</b>'.$obj->grades.'</td>
        </tr>
        <tr>
            <td><b>Identidad territorial:</b>'.$obj->territory.'</td>
        </tr>
        <tr>
            <td><b>Tipo de Material:</b>'.$obj->materialtype.'</td>
        </tr>
        <tr>
            <td><b>Eje:</b>'.$obj->axis.'</td>
        </tr>
        <tr>
            <td><b>OA con el que se relaciona:</b>'.$obj->oa.'</td>
        </tr>
    </td>
    <td><img src="'.$obj->imageurl.'" alt="test alt attribute" width="250" border="0" /></td>
</tr>
<tr>
    <td>
        <b>Resumen:</b>
    </td>
</tr>
<tr>
    <td>
        '.$obj->abstract.'
    </td>
</tr>
<tr>
    <td>
        <b>Contenido de la lengua que se trabaja:</b>
    </td>
</tr>
<tr>
    <td>
        '.$obj->linguistic.'
    </td>
</tr>
<tr>
    <td>
        <b>¿En qué actividad(es) puedo usar el material?</b>
    </td>
</tr>
<tr>
    <td>
        '.$obj->suggestions.'
    </td>
</tr>
<tr>
    <td>
        <b>Sugerencias:</b>
    </td>
</tr>
<tr>
    <td>
        '.$obj->guidelines.'
    </td>
</tr>
</table>

');
$pdf->Output('mypdf.pdf', 'D');