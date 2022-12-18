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
 * repositoryciae admin settings.
 *
 * @package local
 * @subpackage repositoryciae
 * @copyright 2022-onwards Pablo Millaquén
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// defined('MOODLE_INTERNAL') || die();
// global $DB;
// // RepositoryCIAE settings.
// $settings->add(
//         new admin_setting_heading('emarking_markingsettings', get_string('markingsettings', 'mod_emarking'),
//                 get_string('markingsettings_help', 'mod_emarking')));
// // Select Forum.
// $forums = $DB->get_records('forum');
// $forum_arr = array();
// foreach($forums as $forum){
//     array_push($forum_arr, [$forum->id => $forum->name]);
// }
// // $yesno = array(
// //         0 => get_string('no'),
// //         1 => get_string('yes')
// // );
// $settings->add(
//     new admin_setting_configselect('emarking_enableconfigtab',
//         get_string('enableconfigtab','mod_emarking'),
//         get_string('enableconfigtab_help','mod_emarking'), 0, $forum_arr));