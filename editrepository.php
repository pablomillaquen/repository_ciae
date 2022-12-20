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
$usercontext = context_user::instance($USER->id);

$search = optional_param('search', '', PARAM_TEXT); 
$grades = optional_param('grades', '', PARAM_TEXT); 
$order = optional_param('order', '', PARAM_TEXT); 
$types = optional_param('types', '', PARAM_TEXT); 
if($types=="[]") $types=null;
$page = "editrepository";

$PAGE->requires->js_call_amd('local_repositoryciae/search', 'init', array($page));

$lang = current_language();

if (!is_siteadmin()){
    header("Location: ". $CFG->wwwroot."/local/repositoryciae/");
    exit();
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
if($search != "" || $grades != "" || $order != "" || $types != "" ){
    $i = 0;
    $sql = "SELECT * FROM {local_repositoryciae_files} ";
    if($search !=""){
        $sql.= "WHERE name LIKE '".$search."' OR abstract LIKE '".$search."' ";
        $i++;
    }
    if($grades!=""){
        if($i>0){
            $sql.=" AND ";
        }else{
            $sql.=" WHERE ";
        }
        $sql.=" grades =".$grades;
        $i++;
    }
   
    if(!empty($types) || isset($types)){
        if($i>0){
            $sql.=" AND ";
        }
        $j=0;
        $types_array1 = json_decode($types);
        foreach($types_array1 as $t){
            if($j>0) {
                $sql.=" OR ";
            }elseif($j==0 && $i==0){
                $sql.=" WHERE ";
            }
            switch($t){
                case 'chk_cards':
                    $sql.=" materialtype = 5";
                    $j++;
                    break;
                case 'chk_guides':
                    $sql.=" materialtype = 1";
                    $j++;
                    break;
                case 'chk_books':
                    $sql.=" materialtype = 4";
                    $j++;
                    break;
                case 'chk_capsules':
                    $sql.=" materialtype = 12";
                    $j++;
                    break;
                case 'chk_images':
                    $sql.=" materialtype = 7";
                    $j++;
                    break;
                case 'chk_audios':
                    $sql.=" materialtype = 13";
                    $j++;
                    break;
                case 'chk_songs':
                    $sql.=" materialtype = 11";
                    $j++;
                    break;
                case 'chk_texts':
                    $sql.=" materialtype = 4";
                    $j++;
                    break;            
                case 'chk_graphics':
                    $sql.=" materialtype = 8";
                    $j++;
                    break;
            }
        }
    }
    if($order!=""){
        if($order==1){
            $sql.= " ORDER BY id ASC ";
        }elseif($order==2){
            $sql.= " ORDER BY id DESC ";
        }
    }
    
    $objmaterials = $DB->get_records_sql($sql);
}else{
    $objmaterials = $DB->get_records('local_repositoryciae_files');
}

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
    $description_cc = "";
    switch($lang){
        case "es":
            $description_cc = "description_es";
            break;
        case "en":
            $description_cc = "description_en";
            break;
        case "arn":
            $description_cc = "description_arn";
            break;
    }
    $sql = "SELECT ".$description_cc." as description_cc FROM {local_repositoryciae_cc} WHERE id = ".$mat->culturalcontent;
    if($mat->culturalcontent){
        $culturalcontent = $DB->get_record_sql($sql);
        if(isset($culturalcontent->description_cc)){
            $mat->culturalcontent = $culturalcontent->description_cc;
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
if($search){
    $data->search = $search;
}
if($grades){
    $grades == 1 ? $data->grades_one = true : $data->grades_one = false;
    $grades == 2 ? $data->grades_two = true : $data->grades_two = false;
    $grades == 3 ? $data->grades_three = true : $data->grades_three = false;
    $grades == 4 ? $data->grades_four = true : $data->grades_four = false;
    $grades == 5 ? $data->grades_five = true : $data->grades_five = false;
    $grades == 6 ? $data->grades_six = true : $data->grades_six = false;
    $grades == 7 ? $data->grades_seven = true : $data->grades_seven = false;
    $grades == 8 ? $data->grades_eight = true : $data->grades_eight = false;
}
if($order){
    $order == 1 ? $data->order_one = true : $data->order_one = false;
    $order == 2 ? $data->order_two = true : $data->order_two = false;
}
if($types){
    
    $types_array = json_decode($types);
    $data->chk_cards = false;
    $data->chk_guides = false;
    $data->chk_books = false;
    $data->chk_capsules = false;
    $data->chk_images = false;
    $data->chk_audios = false;
    $data->chk_songs = false;
    $data->chk_texts = false;
    $data->chk_graphics = false;
    
    foreach($types_array as $type){
        switch($type){
            case 'chk_cards':
                $data->chk_cards = true;
                break;
            case 'chk_guides':
                $data->chk_guides = true;
                break;
            case 'chk_books':
                $data->chk_books = true;
                break;
            case 'chk_capsules':
                $data->chk_capsules = true;
                break;
            case 'chk_images':
                $data->chk_images = true;
                break;
            case 'chk_audios':
                $data->chk_audios = true;
                break;
            case 'chk_songs':
                $data->chk_songs = true;
                break;
            case 'chk_texts':
                $data->chk_texts = true;
                break;            
            case 'chk_graphics':
                $data->chk_graphics = true;
                break;
            
        }
    }
}
$data->locallink = $CFG->wwwroot."/local/repositoryciae/";

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/mainrepositoryedit', $data);

echo $OUTPUT->footer();
