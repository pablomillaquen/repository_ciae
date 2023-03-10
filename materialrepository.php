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

$PAGE->requires->js_call_amd('local_repositoryciae/downloadfile', 'init', array($id));

$lang = current_language();
$optionsculturelang = array();
$islink = false;
$ismaterial = false;

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

$optionsaxis = array(
    '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
    '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
    '3' => 'Cosmovisión de los pueblos originarios.',
    '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
); 

$materials = array();
$objmaterial = $DB->get_record('local_repositoryciae_files', ['id'=>$id]);

//File
$arrayfiles = array();

if($objmaterial->filetype==1){//It's a file
    if(isset($objmaterial->user_id) && $objmaterial->user_id !=0){
        $author = $DB->get_record('user', ['id'=> $objmaterial->user_id]);
        $objmaterial->author = $author->firstname." ".$author->lastname;
    }else{
        $objmaterial->author = "No registrado";
    }
    if($objmaterial->link){
        $file = $DB->get_records_sql("SELECT * FROM mdl_files WHERE itemid = ". $objmaterial->link ." AND filesize > 1 AND component = 'local_repositoryciae'");
        if($file){
            foreach($file as $key=>$value){
                $file = new stdClass();
                //$url = moodle_url::make_pluginfile_url($value->contextid, $value->component, $value->filearea, $value->itemid, $value->filepath, $value->filename, false);
                $url = $CFG->wwwroot."/pluginfile.php/".$value->contextid."/local_repositoryciae/".$value->filearea."/".$value->itemid."/".$value->filename;
                $file->url = $url;
                $file->filename = $value->filename;
                $arrayfiles[]= $file; 
            }
            $objmaterial->fileurl = $arrayfiles;
        }
    }
  
}elseif($objmaterial->filetype==2){
    $islink = true;
    $objmaterial->fileurl = $objmaterial->link;
    if(isset($objmaterial->user_id) && $objmaterial->user_id !=0){
        $author = $DB->get_record('user', ['id'=> $objmaterial->user_id]);
        $objmaterial->author = $author->firstname." ".$author->lastname;
    }else{
        $objmaterial->author = "No registrado";
    }
}elseif($objmaterial->filetype==3){
    $islink = true;
    $ismaterial = true;
    if($objmaterial->link){
        
        $link = $DB->get_records_sql("SELECT * FROM mdl_forum_discussions WHERE id = ". $objmaterial->discussion_id);
       
        if($link){
            foreach($link as $key=>$value){
                $objmaterial->filename = $value->name;
                $file = $DB->get_records_sql("SELECT * FROM mdl_files WHERE itemid = ". $value->firstpost ." AND filesize > 1 AND component = 'mod_forum'");
                if($file){
                    foreach($file as $key=>$value){
                        $file = new stdClass();
                        $url = $CFG->wwwroot."/pluginfile.php/".$value->contextid."/mod_forum/".$value->filearea."/".$value->itemid."/".$value->filename;
                        $file->url = $url;
                        $file->filename = $value->filename;
                        $arrayfiles[]= $file;
                    }
                    $objmaterial->fileurl = $file->url;
                }
            }
        }
    }
    if($objmaterial->discussion_id){
        $conversation = $DB->get_records_sql("SELECT * FROM mdl_forum_discussions WHERE id = ". $objmaterial->discussion_id );
        if($conversation){
            foreach($conversation as $key=>$value){
                $objmaterial->conversation_url = $CFG->wwwroot."/mod/forum/discuss.php?d=".$value->id;
            }
        }
    }
    $draft = $DB->get_record('local_repositoryciae_draft', ['discussion_id'=> $objmaterial->discussion_id]);
    
    if(isset($draft) && $draft->user_id !=0){
        $editor = $DB->get_record('user', ['id'=> $draft->user_id]);
        $objmaterial->editor = $editor->firstname." ".$editor->lastname;
    }else{
        $objmaterial->editor = "No registrado";
    }
    $answers = $DB->get_records('local_repositoryciae_answer', ['discussion_id'=> $objmaterial->discussion_id, 'selected'=> 1]);
    
    $objmaterial->colaborators = array();
    foreach($answers as $answer){
        $user = $DB->get_record('user', ['id'=> $answer->user_id]);
        
        if($user && !in_array($user, $objmaterial->colaborators)){
            array_push($objmaterial->colaborators, $user);
        }
    }
    
}
$objmaterial->islink = $islink;
$objmaterial->ismaterial = $ismaterial;

//Material Types
foreach($optionsmaterials as $key => $value){
    if($objmaterial->materialtype == $key){
        $objmaterial->materialtype = $value;
    }
}
//Culture
foreach($optionsculturelang as $key => $value){
    if($key == $objmaterial->grades){
        foreach($value as $key2 => $value2){
            if($objmaterial->culturalcontent == $key2){
                $objmaterial->culturalcontent = $value2;
                
            }
        }
    }
}

//Grades
foreach($optionsgrades as $key => $value){
    if($objmaterial->grades == $key){
        $objmaterial->grades = $value;
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

//Imagen
if($objmaterial->image){
    $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $objmaterial->image ." AND filesize > 1 AND component = 'local_repositoryciae'  LIMIT 1");
    if($fileimage){
        $url = moodle_url::make_pluginfile_url($fileimage->contextid, $fileimage->component, $fileimage->filearea, $fileimage->itemid, $fileimage->filepath, $fileimage->filename, false);
        $objmaterial->imageurl =  $CFG->wwwroot.'/pluginfile.php/'.$fileimage->contextid.'/local_repositoryciae/'.$fileimage->filearea.'/'.$fileimage->itemid.'/'.$fileimage->filename;
    }else{
        $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
    }
}else{
    $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
}
$objmaterial->user_id = $USER->id;

$data = new stdClass();
$data = $materials;


$PAGE->set_url('/local/repositoryciae/index.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));
echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/materialrepository', $objmaterial);

echo $OUTPUT->footer();
