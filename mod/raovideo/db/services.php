<?php

$services = array(
      'moodlemobile' => array(                                                //the name of the web service
          'functions' => array ('mod_raovideo_info'), //web service functions of this service
          'requiredcapability' => '',                //if set, the web service user need this capability to access 
                                                                              //any function of this service. For example: 'some/capability:specified'                 
          'restrictedusers' =>0,                                             //if enabled, the Moodle administrator must link some user to this service
                                                                              //into the administration
          'enabled'=>1,                                                       //if enabled, the service can be reachable on a default installation
       )
  );

$functions = array(
    'mod_raovideo_info' => array(         //web service function name
        'classname'   => 'mod_raovideo_external',  //class containing the external function
        'methodname'  => 'info',          //external function name
        'classpath'   => 'mod/raovideo/externallib.php',  //file containing the class/external function
        'description' => 'Get Info of the Video',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
);