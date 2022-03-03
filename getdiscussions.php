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

 class discussions_form extends moodleform
 {
    //Add elements to form
    public function definition()
    {
        global $DB, $CFG;

        $mform = $this->_form;

        $sql = "SELECT d.* FROM mdl_forum_discussions d JOIN mdl_forum f on d.forum = f.id WHERE f.id = 15";
        $discussions = $DB->get_records_sql($sql, null);
        $discussionsarray = [];
        foreach($discussions as $discussion){
            $discussionsarray[$discussion->id] = $discussion->name;
        }

        $select = $mform->addElement('select', 'discussions', get_string('find_discussion', 'local_repositoryciae'), $discussionsarray, []);
        $select->setMultiple(false);

        $sql2 = "SELECT d.* FROM mdl_forum_discussions d JOIN mdl_forum f on d.forum = f.id WHERE f.id = 16";
        $discussionfile = $DB->get_records_sql($sql2, null);
        $discussionfilesarray = [];
        foreach($discussionfile as $file){
            $discussionfilesarray[$file->id] = $file->name;
        }

        $select = $mform->addElement('select', 'discussionfile', get_string('find_discussion_file', 'local_repositoryciae'), $discussionfilesarray, []);
        $select->setMultiple(false);

    }

    public function validation($data, $files)
    {
        return array();
    }
 }