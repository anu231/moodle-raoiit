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
    * @package   profilefield_databasefieldmap
    * @copyright  2008 onwards Shane Elliot {@link http://pukunui.com}
    * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
    */

    /**
    * Class profile_define_databasefieldmap
    * @copyright  2008 onwards Shane Elliot {@link http://pukunui.com}
    * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
    */
    class profile_define_databasefieldmap extends profile_define_base {

        /**
        * Add elements for creating/editing a checkbox profile field.
        *
        * @param moodleform $form
        */

        //function define_form_specific//
        public function define_form_specific($form) {
    
            $form->addElement('selectyesno', 'defaultdata','readonly',  get_string('profiledefaultchecked', 'admin'));
            $form->setDefault('defaultdata', 0); // Defaults to 'no'.
            $form->setType('defaultdata', PARAM_BOOL);
            $options = array(
            'branchadmin_ttbatches' => 'Batch',
            'branchadmin_centre_info' => 'Center'
      
            );
            $select = $form->addElement('select', 'param1', "Select Source Table", $options);

        }


    //Form validate function//

    function define_validate_specific($data) {
    $errors = array();
    // Make sure defaultdata is not false
    if ($data->param1 == false) {
        $errors['param1'] = get_string('noprovided', 'profilefield_databasefieldmap');
    }
    return $errors;
    }



    /*
    function define_after_data(&$form) {
    if (empty($data->param1)) {
        $mform->addElement('static', 'description', get_string('shouldbeyes', 'profilefield_databasefieldmap'));
    }
    }
    */

    function define_save_preprocess($data) {
    if (empty($data->param1)) {
        $data->param1 = NULL;
    }
    return $data;
    }



    }//end class profile_define_databasefieldmap



