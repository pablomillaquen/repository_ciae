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

$filter = optional_param('filter', null, PARAM_TEXT);
$order = optional_param('order', 0, PARAM_INT);

$data = new stdClass();
$data->locallink = $CFG->wwwroot."/local/repositoryciae/";
$lang = current_language();
$PAGE->requires->js_call_amd('local_repositoryciae/searchShared', 'init');
$data->isadmin = false;
if (is_siteadmin()){
    $data->isadmin = true;
} 

$forum = $DB->get_record('local_repositoryciae_admin', ['keyname'=>'forum'] );

if($order == 1){
    $order = "id ASC";
    $data->orderselect1 = true;
}elseif($order == 2){
    $order = "id DESC";
    $data->orderselect2 = true;
}else{
    $order = "id DESC";
}

$discussions = array();
if($filter){
    $data->filter = $filter;
    $objdiscussions = $DB->get_records_sql("SELECT * FROM {forum_discussions} WHERE forum = ".$forum->data." AND name LIKE '%".$filter."%'  ORDER BY ".$order);
}else{
    $objdiscussions = $DB->get_records_sql('SELECT * FROM {forum_discussions} WHERE forum = '.$forum->data.' ORDER BY '.$order);
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
    if(substr( $objpost->message, 0, 2 ) === "@@")
        $objdiscussion->firstpost = "Sin descripción";
    $objdiscussion->firstpost = $objpost->message;
   
    array_push($discussions, $objdiscussion);

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
}

$data->discussions = $discussions;

$PAGE->set_url('/local/repositoryciae/sharedfiles.php');
$PAGE->set_title(get_string('title', 'local_repositoryciae'));
$PAGE->set_heading(get_string('title', 'local_repositoryciae')." - ". get_string('discussion_list', 'local_repositoryciae'));

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_repositoryciae/sharedfiles', $data);

echo $OUTPUT->footer();