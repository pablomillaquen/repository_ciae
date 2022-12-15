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
$filter = optional_param('filter', 0, PARAM_INT);
$order = optional_param('order', 0, PARAM_INT);
$orderusers = optional_param('orderusers', 0, PARAM_INT);

$data = new stdClass();
$data->locallink = $CFG->wwwroot."/local/repositoryciae/";
$data->actions = 40;
$data->valuepixel = 20;
$data->valuedifference = 100 - $data->valuepixel;
$data->generoartista = "El";
$data->generoartista2 = "del";
$data->nombreartista = "Eduardo Rapimán";
$data->mes = "Octubre";
$data->anho = "2022";

$PAGE->requires->js_call_amd('local_repositoryciae/imagePixelated', 'init', array($data->valuepixel));

$lang = current_language();

$forum = $DB->get_record('local_repositoryciae_admin', ['keyname'=>'forum'] );

$discussions = array();
if($order == 1){
    $order = "mfd.id DESC";
    $data->orderselect1 = true;
}elseif($order == 2){
    $order = "mfd.id ASC";
    $data->orderselect2 = true;
}else{
    $order = "mfd.id DESC";
}

if($filter!=0){
    switch($filter){
        case "1": //Sólo subidas al repositorio
            $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} mfd join {local_repositoryciae_d_state} mlrds on mfd.id = mlrds.discussion_id WHERE mfd.forum = '.$forum->data.' and mlrds.state_id = 3 ORDER BY '.$order.' LIMIT 3');
            $data->filterselect1 = true;
            break;
        case "2": //Formulario incompleto
            $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} mfd WHERE forum = '.$forum->data.' ORDER BY '.$order.' LIMIT 3');
            $data->filterselect2 = true;
            break;
        case "3": //Para revisión
            $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} mfd join {local_repositoryciae_d_state} mlrds on mfd.id = mlrds.discussion_id WHERE mfd.forum = '.$forum->data.' and mlrds.state_id = 2 ORDER BY '.$order.' LIMIT 3');
            $data->filterselect3 = true;
            break;
        default:
            $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} mfd WHERE forum = '.$forum->data.' ORDER BY '.$order.' LIMIT 3');
            break;
    }
}else{
    $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} mfd WHERE forum = '.$forum->data.' ORDER BY '.$order.' LIMIT 3');
}

foreach($objdiscussions as $objdiscussion){

    //quantity
    $objquantitypost = $DB->get_record_sql('SELECT count(*) as num FROM {forum_posts} WHERE discussion = '.$objdiscussion->id);
    $objdiscussion->quantity = $objquantitypost->num - 1;

    //keywords
    $keywords = array();
    $objkeywords = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_keys} ORDER BY id');
    if($objkeywords){
        foreach($objkeywords as $k => $v){
            array_push($keywords,$objkeywords[$k]->keyword);
        }        
    }
    $i=0;
    foreach($keywords as $keyword){
        $sql = "SELECT COUNT(*) AS num FROM {forum_posts} WHERE discussion = ".$objdiscussion->id." and (message  LIKE '%".$keyword."%' OR subject LIKE '%".$keyword."%' )";
        $times = $DB->get_records_sql($sql);
       
        $t = array_values($times); 
        $times = $t[0]->num; 
        
        if($times == 0){
            unset($keywords[$i]);
        }
        
        $i++;
    }
   
    $presentkeywords = null;
    if(!empty($keywords)){
        $keywords = array_values($keywords);
        
        foreach($keywords as $clave=>$valor){
            if($clave>0){
                $presentkeywords .= ", ".$keywords[$clave];
            }else{
                $presentkeywords = $keywords[$clave];
                }
            }
        }
    $objdiscussion->keywords = $presentkeywords;
           
    
    //firstpost
    $objpost = $DB->get_record_sql('SELECT * FROM {forum_posts} WHERE id = '.$objdiscussion->firstpost.' AND discussion = '.$objdiscussion->id);
    $objpost->message = strip_tags($objpost->message);
    if (strlen($objpost->message) > 30)
        $objpost->message = substr($objpost->message, 0, 30) . '...';
    $objdiscussion->firstpost = $objpost->message;
   
    

    //Percentage
    $objstageone = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_draft} WHERE discussion_id = '.$objdiscussion->id);
    if($objstageone){
        $g1 = 0; 
        $g2 = 0; 
        $percentage1 = 0; 
        $percentage2 = 0;
        ($objstageone->grades) ? $g1++ : '';
        ($objstageone->materialtype) ? $g1++ : '';
        ($objstageone->linguistic) ? $g1++ : '';
        ($objstageone->suggestions) ? $g1++ : '';
        ($objstageone->territory) ? $g1++ : '';
        ($objstageone->axis) ? $g2++ : '';
        ($objstageone->learning) ? $g2++ : '';
        ($objstageone->guidelines) ? $g2++ : '';
        if($g1){
            $percentage1 = ($g1*100)/5;
        }
        if($g2){
            $percentage2 = ($g2*100)/3;
        }
        $objdiscussion->percentage1 = intval($percentage1);
        $objdiscussion->percentage2 = intval($percentage2);
        if($objdiscussion->percentage1 + $objdiscussion->percentage2 == 0){
            $objdiscussion->state = "Formulario incompleto";
        }elseif($objdiscussion->percentage1 + $objdiscussion->percentage2 == 100){
            //State
            $objdiscussion->state = "Para revisión";
        }else{
            $objdiscussion->state = "Formulario incompleto";
        }

        $objstate = $DB->get_record_sql('SELECT d.state_id,s.state FROM {local_repositoryciae_d_state} d JOIN {local_repositoryciae_state} s ON  s.id = d.state_id WHERE discussion_id = '.$objdiscussion->id);
       
        if($objstate){
            $objdiscussion->state = $objstate->state;
        }
    }else{
        $objdiscussion->percentage1 = 0;
        $objdiscussion->percentage2 = 0;
        $objdiscussion->state = "Formulario incompleto";
    }
    ($objdiscussion->percentage1 < 100) ? $objdiscussion->percentage1complete = true : $objdiscussion->percentage1complete = false;
    ($objdiscussion->percentage2 < 100) ? $objdiscussion->percentage2complete = true : $objdiscussion->percentage2complete = false;
    array_push($discussions, $objdiscussion);
}

$data->discussions = $discussions;

//Users
switch($orderusers){
    case "1":
        $objusers = $DB->get_records_sql('SELECT mlra.user_id, count(mlra.user_id) as quantity, mu.firstname, mu.lastname FROM {local_repositoryciae_answer} mlra JOIN {user} mu ON mu.id = mlra.user_id GROUP BY mlra.user_id ORDER BY count(mlra.user_id) DESC limit 3');
        $data->orderusersselect1 = true;
        break;
    case "2":
        $objusers = $DB->get_records_sql('SELECT mfp.userid, count(mfp.userid) as quantity, mu.firstname, mu.lastname FROM {forum_posts} mfp JOIN {user} mu ON mu.id = mfp.userid WHERE mfp.parent != 0 GROUP BY mfp.userid ORDER BY count(mfp.userid) DESC limit 3');
        $data->orderusersselect2 = true;
        break;
    case "3":
        $objusers = $DB->get_records_sql('SELECT mfp.userid, count(mfp.userid) as quantity, mu.firstname, mu.lastname FROM {forum_posts} mfp JOIN {user} mu ON mu.id = mfp.userid WHERE mfp.parent = 0 GROUP BY mfp.userid ORDER BY count(mfp.userid) DESC limit 3');
        $data->orderusersselect3 = true;
        break;
    default:
        $objusers = $DB->get_records_sql('SELECT mlra.user_id, count(mlra.user_id) as quantity, mu.firstname, mu.lastname FROM {local_repositoryciae_answer} mlra JOIN {user} mu ON mu.id = mlra.user_id GROUP BY mlra.user_id ORDER BY count(mlra.user_id) DESC limit 3');
        $data->orderusersselect1 = true;
        break;
}

$data->editors = array_values($objusers);


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
$optionsaxis = array(
    '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
    '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
    '3' => 'Cosmovisión de los pueblos originarios.',
    '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
);

$materials = array();
$objmaterials = $DB->get_records_sql('SELECT * FROM {local_repositoryciae_files} ORDER BY id DESC LIMIT 3');
foreach($objmaterials as $objmaterial){
    $arrayfiles = array();
    $islink = false;
    $ismaterial = false;
    $objmaterial->abstract = substr($objmaterial->abstract,0,80).'...';
    if($objmaterial->filetype==1){//It's a file
        
    
    }elseif($objmaterial->filetype==2){
        $islink = true;
        $objmaterial->fileurl = $objmaterial->link;
    }elseif($objmaterial->filetype==3){
        $islink = true;
        $ismaterial = true;
        if($objmaterial->link){
            $link = $DB->get_records_sql("SELECT * FROM mdl_forum_discussions WHERE id = ". $objmaterial->link);
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

        //$objmaterial->fileurl = $objmaterial->link;
    }
    $objmaterial->islink = $islink;
    $objmaterial->ismaterial = $ismaterial;
    
    //Image
    if($objmaterial->image){
        $fileimage = $DB->get_record_sql("SELECT * FROM mdl_files WHERE itemid = ". $objmaterial->image ." AND filesize > 1 AND component = 'local_repositoryciae'  LIMIT 1");
        if($fileimage){
            $url = moodle_url::make_pluginfile_url($fileimage->contextid, $fileimage->component, $fileimage->filearea, $fileimage->itemid, $fileimage->filepath, $fileimage->filename, false);
            $objmaterial->imageurl = $url;
        }else{
            $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
        }
    }else{
        $objmaterial->imageurl = $CFG->wwwroot.'/local/repositoryciae/img/no-image-icon-23485.png';
    }
    //Material type
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
    
    array_push($materials, $objmaterial);
}
$data->materials = $materials;

$PAGE->set_url('/local/repositoryciae/index.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/imagepixelated', $data);

echo $OUTPUT->footer();