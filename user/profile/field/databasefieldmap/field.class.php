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
 * Database field Map Plugin
 *
 * @package    profilefield_databasefieldmap
 * @copyright  2007 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class profile_field_databasefieldmap
 *
 * @copyright  2007 onwards Shane Elliot {@link http://pukunui.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */require_once($CFG->dirroot . '/mnet/xmlrpc/client.php');
 require_once("$CFG->dirroot/user/profile/lib.php");
global $DB,$CFG,$USER;
class profile_field_databasefieldmap extends profile_field_base {

    /** @var array $options */
    public $options;

    /** @var int $datakey */
    public $datakey;

    /**
     * Constructor method.
     *
     * Pulls out the options for the menu from the database and sets the the corresponding key for the data if it exists.
     *
     * @param int $fieldid
     * @param int $userid
     */

    function edit_field_add($mform) {
      // Create form field
      global $DB;
      $param1 = $this->field->param1;
      //$id = $this->field->id;
      $result=$DB->get_records($param1,array());

      $attributelist=array();
      foreach($result as $res){
        $attributelist[$res->id] = $res->name;
      } 
      $checkbox = &$mform->addElement('select', $this->inputname, format_string('Select '.$this->field->name), $attributelist);

    }

    function display_data() {
      global $DB;
      $result_new=$DB->get_record($this->field->param1,array('id'=>$this->data));
      return $result_new->name;
    }



    function edit_field_set_default($mform) {
      if (!empty($param1)) {
        $mform->setDefault($this->inputname, $this->field->defaultdata);
      }
    }


    function edit_validate_field($usernew) {
      $errors = array();
      if (isset($usernew->{$this->inputname})) {
          if ($usernew->{$this->inputname} === '') {
            $errors[$this->inputname] = 'error';
          }
      }
      return $errors;
    }
    function edit_save_data_preprocess($data, $datarecord) {
      if (is_array($data)) {
          $datarecord->dataformat = $data['format'];
          $data = $data['text'];
      }
      return $data;
    }
    function edit_field_set_locked($mform) {
      if (!$mform->elementExists($this->inputname)) {
          return;
      }
      if ($this->is_locked() and !has_capability('moodle/user:update', get_context_instance(CONTEXT_SYSTEM))) {
          $mform->hardFreeze($this->inputname);
          $mform->setConstant($this->inputname, $this->data);
      }
    }

}


