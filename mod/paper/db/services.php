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
 * URL external functions and service definitions.
 *
 * @package    mod_paper
 * @category   external
 * @copyright  2015 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(

    /*'mod_paper_view_url' => array(
        'classname'     => 'mod_url_external',
        'methodname'    => 'view_url',
        'description'   => 'Trigger the course module viewed event and update the module completion status.',
        'type'          => 'write',
        'capabilities'  => 'mod/url:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),*/
    'mod_paper_get_papers_by_courses' => array(
        'classname'     => 'mod_paper_external',
        'methodname'    => 'get_papers_by_courses',
        'description'   => 'Returns a list of papers in a provided list of courses, if no list is provided all urls that the user
                            can view will be returned.',
        'type'          => 'read',
        'capabilities'  => 'mod/paper:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    ),
    'mod_paper_get_paper' => array(
        'classname'     => 'mod_paper_external',
        'methodname'    => 'get_paper',
        'description'   => 'Returns a paper whose module id is provided',
        'type'          => 'read',
        'capabilities'  => 'mod/paper:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    ),
    'mod_paper_get_paper_result' => array(
        'classname'     => 'mod_paper_external',
        'methodname'    => 'get_paper_result',
        'description'   => 'Returns the marks scored by the user in paper whose module id is provided',
        'type'          => 'read',
        'capabilities'  => 'mod/paper:view',
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    ),
);

$services = array(
      'raopaperservice' => array(                                                //the name of the web service
        'functions' => $functions,
        'requiredcapability' => '',                //if set, the web service user need this capability to access 
                                                                              //any function of this service. For example: 'some/capability:specified'                 
        'restrictedusers' =>0,                                             //if enabled, the Moodle administrator must link some user to this service
                                                                              //into the administration
        'enabled'=>1,                                                       //if enabled, the service can be reachable on a default installation
      )
  );


