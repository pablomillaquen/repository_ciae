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
    if($oldversion < 2019111829) {
        // Define field regraderestrictdates to be added to repositoryciae.
        $table = new xmldb_table('local_repositoryciae_files');

        $field = new xmldb_field('conversation', XMLDB_TYPE_CHAR, '20', null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if (! $dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // RepositoryCIAE savepoint reached.
        upgrade_plugin_savepoint(true, 2019111829, 'local','repositoryciae');
    }
    if ($oldversion < 2019111819) {
        // Define field regraderestrictdates to be added to repositoryciae.
        $table = new xmldb_table('local_repositoryciae_files');

        $field = new xmldb_field('abstract', XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        $field = new xmldb_field('linguistic',  XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        $field = new xmldb_field('suggestions',  XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        $field = new xmldb_field('learning',  XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        $field = new xmldb_field('guidelines',  XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Conditionally launch add field regraderestrictdates.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }
        
        // RepositoryCIAE savepoint reached.
        upgrade_plugin_savepoint(true, 2019111819, 'local','repositoryciae');
    }
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
    if ($oldversion < 2019111843) {
        // Define table local_repositoryciae_links to be created.
        $table = new xmldb_table('local_repositoryciae_links');
        // Adding fields to table local_repositoryciae_links.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('link', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('photo',  XMLDB_TYPE_CHAR, '255', null, null, null, null);
        // Adding keys to table local_repositoryciae_links.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array(
            'id'));
        // Conditionally launch create table for local_repositoryciae_links.
        if (! $dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Local_repositoryciae savepoint reached.
        upgrade_plugin_savepoint(true, 2019111843, 'local','repositoryciae');
    }
    if ($oldversion < 2019111844) {
        // Define table local_repositoryciae_paints to be created.
        $table = new xmldb_table('local_repositoryciae_paints');
        // Adding fields to table local_repositoryciae_paints.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('image', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('author', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('order',  XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('percentage', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, '100');
        $table->add_field('description',  XMLDB_TYPE_TEXT, null, null, null, null, null);
        // Adding keys to table local_repositoryciae_paints.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array(
            'id'));
        // Conditionally launch create table for local_repositoryciae_paints.
        if (! $dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Local_repositoryciae savepoint reached.
        upgrade_plugin_savepoint(true, 2019111844, 'local','repositoryciae');
    }
    return true;
}