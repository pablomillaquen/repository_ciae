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
 * External groups API
 *
 * @package    core_group
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

/**
 * Repositoriciae external functions
 *
 * @package    local_repositoryciae
 * @category   external
 * @copyright  2021 Pablo Millaquén
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_repositoryciae_external extends external_api {

    /**
     * Describes the parameters for submit_create_collab_form webservice.
     * @return external_function_parameters
     */
    public static function submit_create_collab_form_parameters() {
        return new external_function_parameters(
            array(
                'contextid' => new external_value(PARAM_INT, 'The context id for the course'),
                'jsonformdata' => new external_value(PARAM_RAW, 'The data from the create group form, encoded as a json array')
            )
        );
    }

    /**
     * Submit the create group form.
     *
     * @param int $contextid The context id for the course.
     * @param string $jsonformdata The data from the form, encoded as a json array.
     * @return int new group id.
     */
    public static function submit_create_collab_form($contextid, $jsonformdata) {
        global $CFG, $USER;

        // require_once($CFG->dirroot . '/group/lib.php');
        require_once($CFG->dirroot . '/local/repositoryciae/getdiscussions.php');

        // We always must pass webservice params through validate_parameters.
        $params = self::validate_parameters(self::submit_create_collab_form_parameters(),
                                            ['contextid' => $contextid, 'jsonformdata' => $jsonformdata]);

        $context = context::instance_by_id($params['contextid'], MUST_EXIST);

        // We always must call validate_context in a webservice.
        self::validate_context($context);
        //require_capability('moodle/course:managegroups', $context);

        //list($ignored, $course) = get_context_info_array($context->id);
        $serialiseddata = json_decode($params['jsonformdata']);

        $data = array();
        parse_str($serialiseddata, $data);

        $warnings = array();

        // $editoroptions = [
        //     'maxfiles' => EDITOR_UNLIMITED_FILES,
        //     'maxbytes' => $course->maxbytes,
        //     'trust' => false,
        //     'context' => $context,
        //     'noclean' => true,
        //     'subdirs' => false
        // ];
        // $group = new stdClass();
        // $group->courseid = $course->id;
        // $group = file_prepare_standard_editor($group, 'description', $editoroptions, $context, 'group', 'description', null);

        //The last param is the ajax submitted data.
        $mform = new discussions_form(null, null, 'post', '', null, true, $data);

        $validateddata = $mform->get_data();

        if ($validateddata) {
            // Do the action.
            //$groupid = groups_create_group($validateddata, $mform, $editoroptions);
            
        } else {
            // Generate a warning.
            throw new moodle_exception('erroreditgroup', 'group');
        }

        return $groupid;
    }

    /**
     * Returns description of method result value.
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function submit_create_collab_form_returns() {
        return new external_value(PARAM_INT, 'group id');
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function loadjson_parameters() {
        return new external_function_parameters(
                array(
                    'selectedTypes' => new external_value(PARAM_RAW, 'The user id'),
                    'searchText' => new external_value(PARAM_RAW, 'The user id'),
                    'gradesSelected' => new external_value(PARAM_RAW, 'The user id'),
                    'selectOrder' => new external_value(PARAM_RAW, 'The field id')
                )
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function loadjson($selectedTypes, $searchText, $gradesSelected, $selectOrder) {
        global $DB;
        //$params = self::validate_parameters(self::getExample_parameters(), array());
        $params = self::validate_parameters(self::loadjson_parameters(), 
                array('selectedTypes'=>$selectedTypes, 'searchText'=>$searchText,'gradesSelected'=>$gradesSelected, 'selectOrder'=>$selectOrder));

        $sql = 'SELECT data FROM {user_info_data} WHERE userid = ? AND fieldid = ?';
        $paramsDB = $params; //array($itemid);
        $db_result = $DB->get_records_sql($sql,$paramsDB);
        
        return $db_result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function loadjson_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'data' => new external_value(PARAM_NOTAGS, 'Data for json form'),
                )
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function load_discussion_files_parameters() {
        return new external_function_parameters(
                array(
                    'discussionId' => new external_value(PARAM_RAW, 'The discussion id')
                )
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function load_discussion_files($discussionId) {
        global $DB;
        //$params = self::validate_parameters(self::getExample_parameters(), array());
        $params = self::validate_parameters(self::load_discussion_files_parameters(), 
                array('discussionId'=>$discussionId));

        $sql = 'SELECT mf.id, mf.filename FROM {files} mf 
        join {forum_posts} p on p.id = mf.itemid 
        join {forum_discussions} d on d.id = p.discussion 
        where d.id = ? and length(mf.filename) > 1';
        $paramsDB = $params; //array($itemid);
        $db_result = $DB->get_records_sql($sql,$paramsDB);
        
        return $db_result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function load_discussion_files_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_NOTAGS, 'Data for json form'),
                    'filename' => new external_value(PARAM_NOTAGS, 'Data for new form')
                )
            )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function load_oa_parameters() {
        return new external_function_parameters(
                array(
                    'gradesSelected' => new external_value(PARAM_RAW, 'The user id')
                )
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function load_oa($gradesSelected) {
        global $DB;
        //$params = self::validate_parameters(self::getExample_parameters(), array());
        $params = self::validate_parameters(self::load_oa_parameters(), 
                array('gradesSelected'=>$gradesSelected));

        $sql = 'SELECT id, description FROM {local_repositoryciae_oa} WHERE grades_id = ?';
        $paramsDB = $params; //array($itemid);
        $db_result = $DB->get_records_sql($sql,$paramsDB);
        
        return $db_result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function load_oa_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_NOTAGS, 'id for oa'),
                    'description' => new external_value(PARAM_NOTAGS, 'Description for oa'),
                )
            )
        );
    }
}