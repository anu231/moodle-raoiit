<?php

// We defined the web service functions to install.
$functions = array(
        'block_readytohelp_grievance_departments' => array(
                'classname'   => 'block_readytohelp_external',
                'methodname'  => 'grievance_departments',
                'classpath'   => 'blocks/readytohelp/externallib.php',
                'component'   => 'blocks/readytohelp',
                'description' => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'        => 'write',
                'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE) 
        )
        
);
// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'readytohelp' => array(
                'functions' => array ('block_readytohelp_grievance_departments'),
                'requiredcapability' => '',   
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);