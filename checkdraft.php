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
$id = required_param('id', PARAM_INT);

require_login();
$usercontext = context_user::instance($USER->id);

$data = new stdClass();
$data->locallink = $CFG->wwwroot."/";
$data->isadmin = false;

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

$optionsterritories = array(
    '1' => 'Pewenche',
    '2' => 'Wentenche',
    '3' => 'Nagche',
    '4' => 'Lafkenche',
    '5' => 'Williche'
);

$optionsaxis = array(
    '1' => 'Lengua, tradición oral, iconografía, prácticas de lectura y escritura de los pueblos originarios.',
    '2' => 'Territorio, territorialidad, identidad y memoria histórica de los pueblos originarios.',
    '3' => 'Cosmovisión de los pueblos originarios.',
    '4' => 'Patrimonio, tecnologías, técnicas, ciencias y artes ancestrales de los pueblos originarios.'
);

$optionslearning = array(
    '1' => 'Escuchar',
    '2' => 'Hablar',
    '3' => 'Escribir',
    '4' => 'Leer'
);

$discussions = array();
$objdiscussion = $DB->get_record_sql('SELECT * FROM {forum_discussions} WHERE id = '.$id);
if (is_siteadmin()){
    $data->isadmin = true;
    //Draft form
    $objdraft = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_draft} WHERE discussion_id = '.$id);
    $cantdraft = 0;
    if($objdraft){
        if($objdraft->grades){
            $data->grades = $optionsgrades[$objdraft->grades];
            $data->grades_exist = true;
            $cantdraft++;
        }else{
            $data->grades = 'SIN RESPONDER';
            $data->grades_exist = false;
        } 
        if($objdraft->materialtype){
            $data->materialtype = $optionsmaterials[$objdraft->materialtype];
            $data->materialtype_exist = true;
            $cantdraft++;
        }else{
            $data->materialtype = 'SIN RESPONDER';
            $data->materialtype_exist = false;
        } 
        if($objdraft->linguistic){
            $data->linguistic = $objdraft->linguistic;
            $data->linguistic_exist = true;
            $cantdraft++;
        }else{
            $data->linguistic = 'SIN RESPONDER';
            $data->linguistic_exist = false;
        } 
        if($objdraft->suggestions){
            $data->suggestions = $objdraft->suggestions;
            $data->suggestions_exist = true;
            $cantdraft++;
        }else{
            $data->suggestions = 'SIN RESPONDER';
            $data->suggestions_exist = false;
        } 
        if($objdraft->territory){
            $data->territory = $optionsterritories[$objdraft->territory];
            $data->territory_exist = true;
            $cantdraft++;
        }else{
            $data->territory = 'SIN RESPONDER';
            $data->territory_exist = false;
        } 
        if($objdraft->axis){
            $data->axis = $optionsaxis[$objdraft->axis];
            $data->axis_exist = true;
            $cantdraft++;
        }else{
            $data->axis = 'SIN RESPONDER';
            $data->axis_exist = false;
        } 
        if($objdraft->learning){
            $learn = null;
            $learning_ids = explode(",", $objdraft->learning);
            $i=0;
            foreach($optionslearning as $key=>$value){
                foreach($learning_ids as $k=>$v){
                    if($key==$v){
                        if($i>0){$learn.= ", ";}
                        $learn.= $optionslearning[$v];
                        $i++;  
                    }
                }
            }
            $data->learning = $learn;
            
            $data->learning_exist = true;
            $cantdraft++;
        }else{
            $data->learning = 'SIN RESPONDER';
            $data->learning_exist = false;
        } 
        if($objdraft->guidelines){
            $data->guidelines = $objdraft->guidelines;
            $data->guidelines_exist = true;
            $cantdraft++;
        }else{
            $data->guidelines = 'SIN RESPONDER';
            $data->guidelines_exist = false;
        }
        if($cantdraft == 8){
            $data->totaldraft=true;
        }else{
            $data->totaldraft= false;
        }
    }else{
        $data->grades = 'SIN RESPONDER';
        $data->grades_exist = false;
        $data->materialtype = 'SIN RESPONDER';
        $data->materialtype_exist = false;
        $data->linguistic = 'SIN RESPONDER';
        $data->linguistic_exist = false;
        $data->suggestions = 'SIN RESPONDER';
        $data->suggestions_exist = false;
        $data->territory = 'SIN RESPONDER';
        $data->territory_exist = false;
        $data->axis = 'SIN RESPONDER';
        $data->axis_exist = false;
        $data->learning = 'SIN RESPONDER';
        $data->learning_exist = false;
        $data->guidelines = 'SIN RESPONDER';
        $data->guidelines_exist = false;
    }
}else{
    if($USER->id){
        $cant_user1 = 0;
        $cant_user2 = 0;

        $gr = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "grades" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($gr){
            $objdiscussion->grades = $optionsgrades[$gr->answer];
            $data->grades_exist = true;
            $cant_user1++;
        } else {
            $objdiscussion->grades = 'SIN RESPONDER';
            $data->grades_exist = false;
        }
        
        $m = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "materialtype" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($m) {
            $objdiscussion->materialtype = $optionsmaterials[$m->answer];
            $data->materialtype_exist = true;
            $cant_user1++;
        }else{
            $objdiscussion->materialtype = 'SIN RESPONDER';
            $data->materialtype_exist = false;
        } 
        
        $li = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "linguistic" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($li) {
            $objdiscussion->linguistic = $li->answer;
            $data->linguistic_exist = true;
            $cant_user1++;
        }else {
            $objdiscussion->linguistic = 'SIN RESPONDER';
            $data->linguistic_exist = false;
        }
        
        $s = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "suggestions" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($s)  {
            $objdiscussion->suggestions = $s->answer;
            $data->suggestions_exist = true;
            $cant_user1++;
        }else {
            $objdiscussion->suggestions = 'SIN RESPONDER';
            $data->suggestions_exist = false;
        }
        
        $t = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "territory" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($t) {
            $objdiscussion->territory = $optionsterritories[$t->answer];
            $data->territory_exist = true;
            $cant_user1++;
        }else {
            $objdiscussion->territory = 'SIN RESPONDER';
            $data->territory_exist = false;
        }
        $a = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "axis" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($a) {
            $objdiscussion->axis = $optionsaxis[$a->answer];
            $data->axis_exist = true;
            $cant_user2++;
        }else {
            $objdiscussion->axis = 'SIN RESPONDER';
            $data->axis_exist = false;
        }
        $le = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "learning" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($le) {
            $learn = null;
            $learning_ids = explode(",", $le->answer);
            $i=0;
            foreach($optionslearning as $key=>$value){
                foreach($learning_ids as $k=>$v){
                    if($key==$v){
                        if($i>0){$learn.= ", ";}
                        $learn.= $optionslearning[$v];
                        $i++; 
                    }
                }
            }       

            $objdiscussion->learning = $learn;
            $data->learning_exist = true;
            $cant_user2++;
        }else {
            $objdiscussion->learning = 'SIN RESPONDER';
            $data->learning_exist = false;
        }
        $gu = $DB->get_record_sql('SELECT * FROM {local_repositoryciae_answer} WHERE question = "guidelines" AND discussion_id = '.$id.' AND user_id = '.$USER->id);
        if($gu)  {
            $objdiscussion->guidelines = $gu->answer;
            $data->guidelines_exist = true;
            $cant_user2++;
        }else {
            $objdiscussion->guidelines = 'SIN RESPONDER';
            $data->guidelines_exist = false;
        }
        if($cant_user1 > 0){
            $percentage_user1 = ($cant_user1*100)/5;
        }else{
            $percentage_user1 = 0;
        }
        if($cant_user2 > 0){
            $percentage_user2 = ($cant_user2*100)/3;
        }else{
            $percentage_user2 = 0;
        }
        $data->cant_user1 = intval($cant_user1);
        $data->cant_user2 = intval($cant_user2);
        $data->percentage_user1 = intval($percentage_user1);
        $data->percentage_user2 = intval($percentage_user2);
        
    }
}
if($objdiscussion){
    //filename
    $objfirstpost = $DB->get_record_sql('SELECT * FROM {forum_discussions} mfd JOIN {forum_posts} mfp on mfd.id = mfp.discussion JOIN {files} mf on mf.itemid = mfp.id WHERE mfd.id= '.$objdiscussion->id.' AND mf.mimetype IS NOT NULL LIMIT 1');
    $data->filename = $objfirstpost->filename;

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
            $objdiscussion->state = "Borrador incompleto";
        }elseif($objdiscussion->percentage1 + $objdiscussion->percentage2 == 100){
            //State
            $objdiscussion->state = "Para revisión";
        }else{
            $objdiscussion->state = "Borrador incompleto";
        }

        $objstate = $DB->get_record_sql('SELECT d.state_id,s.state FROM {local_repositoryciae_d_state} d JOIN {local_repositoryciae_state} s ON  s.id = d.state_id WHERE discussion_id = '.$objdiscussion->id.' AND files_draft_id = '.$objstageone->id);
        if($objstate){
            $objdiscussion->state = $objstate->state;
        }
    }else{
        $objdiscussion->percentage1 = 0;
        $objdiscussion->percentage2 = 0;
        $objdiscussion->state = "Borrador incompleto";
    }
    ($objdiscussion->percentage1 < 100) ? $objdiscussion->percentage1complete = true : $objdiscussion->percentage1complete = false;
    ($objdiscussion->percentage2 < 100) ? $objdiscussion->percentage2complete = true : $objdiscussion->percentage2complete = false;

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
   
    array_push($discussions, $objdiscussion);
}

$data->discussions = $discussions;
$PAGE->set_url('/local/repositoryciae/checkdraft.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ".get_string('checkdraft', 'local_repositoryciae'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/checkdraft', $data);

echo $OUTPUT->footer();