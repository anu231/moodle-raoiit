<?php

$services = array(
      'raowebservice' => array(             
          'functions' => array (
              'local_raowebservice_user_info',
              'local_raowebservice_user_course_info',
              'local_raowebservice_user_paper_list',
              'local_raowebservice_user_grievance_list',
              'local_raowebservice_user_grievance_response',
              'local_raowebservice_user_timetable',
              'local_raowebservice_user_grievance_reply',
              'local_raowebservice_user_grievance_metadata',
              'local_raowebservice_user_attendance',
              'local_raowebservice_user_topic_completion_report',
              'local_raowebservice_booklet_info',
              'local_raowebservice_library_booksavailable',
              'local_raowebservice_library_bookshistory',
              'local_raowebservice_testing_token',
              'local_raowebservice_video_url',
          ),
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
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_user_grievance_post' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'user_grievance_post',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Post the User-grievance',
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
    'local_raowebservice_user_grievance_reply' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'post_user_grievance_reply',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Post a user reply to the grievance',
        'type'        => 'write',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_user_grievance_metadata' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'grievance_metadata',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Fetch Available Categories & Departments',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_user_attendance' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'biometric_attendance',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Fetch User Attendance',
        'type'        => 'read',
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
    'local_raowebservice_user_topic_completion_report' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'topic_completion_report',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Topic Completion Report for User',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_user_ptm_records' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'ptm_records',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the PTM Records for User',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_booklet_info' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'booklet_info',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Booklet Info',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_library_booksavailable' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'library_books_available',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Available books at center',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_library_bookshistory' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'library_books_history',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the library book issue history',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_library_booksissued' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'library_books_issued',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Issued books',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_testing_token' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_testing_token',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Token for test portal',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
    'local_raowebservice_video_url' => array(
        'classname'   => 'local_raowebservice_external',
        'methodname'  => 'get_akamai_video_url',
        'classpath'   => 'local/raowebservice/externallib.php',
        'description' => 'Get the Akamai Video URL',
        'type'        => 'read',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
);

