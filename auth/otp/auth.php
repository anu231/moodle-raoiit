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
 * Authentication Plugin: External Database Authentication
 *
 * Checks against an external database.
 *
 * @package    auth_db
 * @author     Martin Dougiamas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');
//require_once($CFG->libdir)

/**
 * External database authentication plugin.
 */
class auth_plugin_otp extends auth_plugin_base {

    /**
     * Constructor.
     */
    function __construct() {
        global $CFG;
        //require_once($CFG->libdir.'/adodb/adodb.inc.php');

        $this->authtype = 'otp';
        $this->config = get_config('auth/otp');
        if (empty($this->config->extencoding)) {
            $this->config->extencoding = 'utf-8';
        }
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     * checks the username and password against the otp table
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        global $CFG, $DB, $SESSION;
        $otp = new auth_otp_otputil();
        $status = $otp->check_otp($username,$password);
        if (!$status){
            $SESSION->otp_error = $otp->error;
            redirect(new moodle_url('/auth/otp/otpchk.php'));
            return false;
        }
        return true;
    }

    function pre_loginpage_hook(){
        //redirect(new moodle_url('/auth/otp/login.php'));
    }
}


