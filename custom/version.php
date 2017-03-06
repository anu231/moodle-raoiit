    // Add rao_user table
    if($oldversion < 2017120501.00) {
        // Create table object
        $table = new xmldb_table('rao_user');

        // Add fields to rao_user table
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);        
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '35', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('admissionlocation', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('admissiondate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('firstname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('middlename', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lastname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('birthdate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gender', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('category', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fathername', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mothername', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fatheroccupation', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('motheroccupation', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('address1', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('address2', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('city', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('pincode', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('studentmobile', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fathermobile', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mothermobile', XMLDB_TYPE_INTEGER, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('landline', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('studentemail', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fatheremail', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('motheremail', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('classtype', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursename', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('studycenter', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('preferredtieup', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('targetyear', XMLDB_TYPE_CHAR, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('ttbatchid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('extbatchid', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('idcardstatus', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('idcardremarks', XMLDB_TYPE_CHAR, '150', null, XMLDB_NOTNULL, null, null);


        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));

        $table->add_index('id', XMLDB_INDEX_UNIQUE, array('id'));
        $table->add_index('userid', XMLDB_INDEX_UNIQUE, array('userid'));

        // Conditionally launch add field id.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Main savepoint reached.
        upgrade_main_savepoint(true, 2017120501.00);
    }

    // Add academic_details table
    if($oldversion < 2017120501.00) {
        $table = new xmldb_table('academic_details');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);        
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '35', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('class', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('board', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('percentmarks', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('year', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('lastschool', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'rao_user', array('userid'));

        $table->add_index('id', XMLDB_INDEX_UNIQUE, array('id'));
        $table->add_index('userid', XMLDB_INDEX_UNIQUE, array('userid'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_main_savepoint(true, 2017120501.00);        
    }    