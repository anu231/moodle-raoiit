<?php

function xmldb_block_readytohelp_upgrade($oldversion=0) {
    global $DB;
    $dbman = $DB->get_manager();
    /*$version = 2017041000;
    // Grievance Categories
    if ($oldversion < $version) {
        $table = new xmldb_table('grievance_categories');

        // ID
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Name
        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null, 'id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    // Grievance Departments
    if ($oldversion < $version) {
        $table = new xmldb_table('grievance_departments');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('email', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    // Grievance Entries
    if ($oldversion < $version) {
        $table = new xmldb_table('grievance_entries');

        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('username', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'username');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        $field = new xmldb_field('category', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'timecreated');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('department', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'category');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('subject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'department');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'subject');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('status', XMLDB_TYPE_TEXT, null, null, null, null, null, 'description');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $key = new xmldb_key('category', XMLDB_KEY_FOREIGN, array('category'), 'grievance_categories', array('id'));
        $dbman->add_key($table, $key);

    }

    // Grievance Response
    if ($oldversion < $version) {
        $table = new xmldb_table('grievance_responses');

        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('grievance_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('deptid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'grievance_id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('email', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'deptid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('approved', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'email');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'approved');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('body', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'timecreated');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $key = new xmldb_key('grievance_id', XMLDB_KEY_FOREIGN, array('grievance_id'), 'grievance_entries', array('id'));
        $dbman->add_key($table, $key);

        $key = new xmldb_key('deptid', XMLDB_KEY_FOREIGN, array('deptid'), 'grievance_departments', array('id'));
        $dbman->add_key($table, $key);

    }

    // Grievance Admins
    if ($oldversion < $version) {
        $table = new xmldb_table('grievance_admins');

        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('username', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('haspermission', XMLDB_TYPE_INTEGER, '5', null, XMLDB_NOTNULL, null, '1', 'username');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        $key = new xmldb_key('username', XMLDB_KEY_FOREIGN, array('username'), 'user', array('username'));
        $dbman->add_key($table, $key);

    }

    // If everything occurs without any errors
    upgrade_plugin_savepoint(true, $version, 'block', 'readytohelp');*/
}