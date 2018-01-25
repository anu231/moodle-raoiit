<?php

$services = array(
      'raowebservice' => array(             
          'functions' => array (
              'local_raowebservice_user_info','local_raowebservice_user_course_info',
              'local_raowebservice_user_paper_list','local_raowebservice_user_grievance_list',
              'local_raowebservice_user_grievance_response','local_raowebservice_user_timetable'),
          'requiredcapability' => '',
          'restrictedusers' =>0,
          'enabled'=>1,
          ),
      'raouserprofile' => array(                                          
          'functions' => array ('local_raowebservice_raouser_profile_update'), 
          'requiredcapability' => '',
          'restrictedusers' =>0,     
          'enabled'=>1,              
       )/*,
      'raousertimetable' => array(                                          
          'functions' => array ('local_raowebservice_get_timetable'), 
          'requiredcapability' => '',
          'restrictedusers' =>0,     
          'enabled'=>1,              
       ) */
  );

$functions = array(
    
     'local_raowebservice_user_info' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'user_info',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Show the User Information',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
     'local_raowebservice_user_course_info' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_course_info',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Show the User course information',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
     'local_raowebservice_user_paper_list' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_user_paper_list',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Show the User-Course Paper List',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
     'local_raowebservice_user_grievance_list' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_user_grievance_list',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Show the User-grievance List',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
     'local_raowebservice_user_grievance_response' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_user_grievance_response',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Show the User-grievance responses',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_user_timetable' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_user_timetable',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the timetable of User',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ), 
);