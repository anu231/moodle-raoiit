<?php

$services = array(
      'raowebservice' => array(             
          'functions' => array ('local_raowebservice_list_user'),
          'requiredcapability' => '',
          'restrictedusers' =>0,
          'enabled'=>1,
          ),
      'raouserprofile' => array(                                          
          'functions' => array ('local_raowebservice_raouser_profile_update'), 
          'requiredcapability' => '',
          'restrictedusers' =>0,     
          'enabled'=>1,              
       ) 
  );

$functions = array(
    'local_raowebservice_list_user' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'list_user',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'List all ready to help.',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
     'local_raowebservice_raouser_profile_update' => array(
        'classname'   => 'local_raowebservice_profile',
        'methodname'  => 'raouser_profile_update',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'List all ready to help.',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
);