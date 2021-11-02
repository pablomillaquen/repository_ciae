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
 * This file keeps track of upgrades to the emarking module
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations.
 * The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do. The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package mod
 * @subpackage repositoryciae
 * @copyright 2021 Pablo Millaquén <pablomillaquen@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
/**
 * Execute repositoryciae upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_repositoryciae_upgrade($oldversion) {
    global $DB;
    // Loads ddl manager and xmldb classes.
    $dbman = $DB->get_manager();
    if ($oldversion < 2019111809) {
        // Define field regraderestrictdates to be added to repositoryciae.
        $table = new xmldb_table('local_repositoryciae_files');

        $field = new xmldb_field('axis', XMLDB_TYPE_CHAR, '90', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('linguistic', XMLDB_TYPE_CHAR, '1333', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('suggestions', XMLDB_TYPE_CHAR, '255', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('learning', XMLDB_TYPE_CHAR, '1333', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('guidelines', XMLDB_TYPE_CHAR, '1333', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        // RepositoryCIAE savepoint reached.
        upgrade_plugin_savepoint(true, 2019111809, 'local','repositoryciae');
    }
    return true;
}