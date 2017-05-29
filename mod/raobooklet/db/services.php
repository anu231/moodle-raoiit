<?php

$services = array(
      'raobookletservice' => array(                                                //the name of the web service
          'functions' => array ('mod_raobooklet_list_booklet'), //web service functions of this service
          'requiredcapability' => '',                //if set, the web service user need this capability to access 
                                                                              //any function of this service. For example: 'some/capability:specified'                 
          'restrictedusers' =>0,                                             //if enabled, the Moodle administrator must link some user to this service
                                                                              //into the administration
          'enabled'=>1,                                                       //if enabled, the service can be reachable on a default installation
       )
  );

$functions = array(
    'mod_raobooklet_list_booklet' => array(         //web service function name
        'classname'   => 'mod_raobooklet_external',  //class containing the external function
        'methodname'  => 'list_booklet',          //external function name
        'classpath'   => 'mod/raobooklet/externallib.php',  //file containing the class/external function
        'description' => 'List all the booklets.',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
);