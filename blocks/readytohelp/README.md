# ReadyToHelp Plugin
Study material for students - display booklets, get feedback for each assignment.

## Installation
1. Clone the repository in the **moodle/mod/readytohelp** directory: <br>
    ```
    cd moodle/mod/
    git clone https://github.com/anu231/moodle_booklet_plugin.git readytohelp
    ```

2. Configure **rsettings.php** file if needed
3. Check if the **mdl_grievance_categories** table is populated. If not, run this sql
    ```
    INSERT INTO  `mdl_grievance_categories`
        (  `name` ) 
    VALUES
        ('Ranklist/Result'),
        ('Timetable'),
        ('Student Portal'),
        ('Branch Administration'),
        ('Study Material'),
        ('Student Welfare'),
        ('Faculties'),
        ('Rao IIT App'),
        ('Pre-Foundation'),
        ('Student-Profile');
    ```