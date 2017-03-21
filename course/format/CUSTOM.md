# Instructions for modifying topics renderer to add color scheme


0. Add code for creating a new field in `course_sections` table

    ```
    // lib/upgrade.php

    // At the bottom of the file just above "return true;"

        // CUSTOM MODIFICATIONS START

        if (TRUE) { // Always add field irrespective of the moodleversion
            $table = new xmldb_table('course_sections');
            $field = new xmldb_field('subject', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', null);

            if (!$dbman->field_exists($table, $field)) {
                $dbman->add_field($table, $field);
            }
            upgrade_main_savepoint(true, 2016120501.02);
        }

        // CUSTOM MODIFICATIONS END
    ```


1. Add a subject field to the topic section editing form.

    Note: This won't be effective unless you've defined a new database field, `subject`, to `course_modules` table. (*see step 0*)

    ```
    // course/editsection_form.php

    class editsection_form extends moodleform {
        // Line ~53
        $SUBJECTS = array(
            0=>'Please Select a Subject',
            1=>'Physics',
            2=>'Chemistry',
            3=>'Maths',
            4=>'Biology',
            5=>'Zoology'
        );
        $subjects = $mform->addElement('select', 'subject', 'Subject', $SUBJECTS);
    ```

    Need to inject the subject field manually (moodle does not do this automatically)

    ```
    // course/editsection.php

    // Line ~50
    $initialdata['subject'] = $DB->get_record('course_sections', array('id'=>$sectioninfo->id))->subject; // Inject Subject.

    ```
    

2. Inject subject field into the _section_info_ class
    This should allow subsequent accessing subject name
    of a section like so :  `$section->subject`
    ```
    // modinfolib.php

    class section_info implements IteratorAggregate {
        //...
        public function __construct() {
        //...

        // Line ~240 (just above $mform->set_data() is called)
        $this->_subject = $DB->get_record('course_sections', array('id'=>$data->id))->subject;
    ```

3. Add the subject name map to the section header
    This should append subject as classname in the topics view
    ```
    // course/format/renderer.php

    protected function section_header {
        //...
        // Line ~193
        $subject_map = array(
                    0 => 'default',
                    1 => 'Physics',
                    2 => 'Chemistry',
                    3 => 'Maths',
                    4 => 'Biology',
                    5 => 'Zoology',
                );
                $customStyle = $subject_map[$section->subject];

        //...
        // Line ~203
        $o.= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
                'class' => 'section main clearfix'.$sectionstyle.' '.$customStyle, 'role'=>'region',
                'aria-label'=> get_section_name($course, $section)));
    ``` 

4. Add css to `course/format/topics/custom.css`

4. Bump moodle version for changes to take effect