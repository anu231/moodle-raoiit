<?php

require_once('classes/pdf2jpg.php');

// Required functions

/**
* Save a new instance and return its id
* if the file is uploaded.
* NOTE: PDF is saved in $CFG->dataroot/raobooklets/contextid/ path
*
* @param $raobooklet instance
* @return boolean Success/Failure
*/
function raobooklet_add_instance($raobooklet, $mform=NULL){
    global $DB, $COURSE, $CFG;
    
    $context = context_module::instance($raobooklet->coursemodule); // Need context for saving file
    $component = "mod_raobooklet";
    $filearea = "booklets";
    $itemid = (int)$raobooklet->year;
    $file = $mform->save_stored_file('attachment',$context->id, $component, $filearea, $itemid );
    if(!$file){
        echo "No file uploaded";
        return FALSE;
    }
    $raobooklet->timecreated = time();
    $raobooklet->timemodified = time();
    $raobooklet->filename = $mform->get_new_filename('attachment'); // TODO: Assumes stored filename
    // will be same as that passed throught the form. Although something otherwise has never happened during development
    // Also, do not rely on $mform.
    
    // Make a directory named `context->id`
    $basedir = $CFG->dataroot.'/raobooklets/'.$context->id.'/'; // Base directory (with trailing '/')
    $absfilepath = $basedir.$raobooklet->filename; // Absolute filepath
    $success1 = mkdir($CFG->dataroot.'/raobooklets/'.$context->id,0774,true);
    if( !$success1 ){
        echo "Couldn't create directory for saving pdf: $CFG->dataroot.'/raobooklets/'.$context->id ";
        return FALSE;
    }
    // Save pdf at $absfilepath
    $success2 = $mform->save_file('attachment', $absfilepath, true);
    if( !$success2 ){
        echo "Couldn't save pdf as : $absfilepath";
        return FALSE;
    }
    // Add conversion task to queue
    $success3 = raobooklet_convert_pdf($raobooklet, $basedir);
    if( !$success3 ){
        echo "Couldn't create task";
        return FALSE;
    }
    if( $file && $success1 && $success2 && $success3) {
        $raobooklet->id = $DB->insert_record('raobooklet', $raobooklet);
        return $raobooklet->id;
    } else {
        echo "Something failed";
        return FALSE;
    }
}

/**
* Creates a new moodle ad hoc task for converting pdf to images.
* (note: does not manage directories. Making/deleting directories is handled by the create/update methods)
*
* @param $raobooklet instance ( Used only for filename. )
* @param $dir base directory for storing file
* @return bool TRUE/FALSE for success/failure
*/
function raobooklet_convert_pdf($raobooklet, $basedir) {
    try {
        $task = new mod_raobooklet_pdf2jpg();
        $task->set_custom_data(array(
            'path' => $basedir, // includes trailing '/'
            'name' => $raobooklet->filename
        ));
        \core\task\manager::queue_adhoc_task($task);
        return TRUE;
    } catch (Exception $e) {
        echo "Exception occured while creating raobooklet pdf creation : ".var_dump($e->getMessage());
        return FALSE;
    }
}


/**
* Update an instance and return boolean Success/Fail
* if the file is uploaded.
*
* @param $raobooklet instance
* @return boolean Success/Failure
*/
function raobooklet_update_instance(stdClass $raobooklet, $mform=NULL){ //TODO
    global $DB, $COURSE, $CFG;
    $context = context_module::instance($raobooklet->coursemodule);
    $oldbooklet = $DB->get_record('raobooklet', array('id'=>$raobooklet->instance), '*', MUST_EXIST);
    $newfile = $mform->get_new_filename('attachment');
    // Check if a new file is uploaded.
    if($newfile){
        // A file has been uploaded. Check whether it's a duplicate.
        $raobooklet->filename = $newfile;
        if($oldbooklet->filename != $raobooklet->filename){
            // Uploaded file is different. Replace old one with this one
            $fs = get_file_storage();
            $component = "mod_raobooklet";
            $filearea = "booklets";
            $filepath = '/';
            $itemid = $raobooklet->year;
            $filename = $raobooklet->filename;
            // Save uploaded file in the moodle DB
            $file = $mform->save_stored_file('attachment',$context->id, $component, $filearea,
            $itemid, $filepath, null, true); // Last option (true) == overwrite file
            
            if( ! $file ) {
                echo "File not present!";
                return FALSE;
            }
            
            // Delete old files from 'moodledata/raobooklets' folder
            $path = $CFG->dataroot.'/raobooklets/'.$context->id;
            $out = exec("rm -r $path");
            
            // Save file in 'moodledata/raobooklets/contextid/' folder
            $basedir = $CFG->dataroot.'/raobooklets/'.$context->id.'/';
            $absfilepath = $basedir.$raobooklet->filename;
            mkdir($CFG->dataroot.'/raobooklets/'.$context->id);
            $success1 = $mform->save_file('attachment', $absfilepath, true);
            
            if( !$success1 ) {
                echo "Couldn't save pdf : $absfilepath";
                return FALSE;
            } 
            
            $success2 = raobooklet_convert_pdf($raobooklet, $basedir);
            if( !$success2 ) {
                echo "Couldn't create task";
                return FALSE;
            }
            
        }
    }
    
    // Update other fields
    $raobooklet->timemodified = time();
    $raobooklet->id = $raobooklet->instance;
    $result = $DB->update_record('raobooklet', $raobooklet);
    
    if ($result){
        return $result;
    } else {
        return FALSE;
    }
    
}

/**
* Delete an instance and return boolean Success/Failure
*
* @param $id is the id of the instance
* @return boolean Success/Failure
*/
function raobooklet_delete_instance($id) {
    global $DB, $CFG;
    if (! $raobooklet = $DB->get_record('raobooklet', array('id' => $id))) {
        return false;
    }
    $cm = get_coursemodule_from_instance('raobooklet', $raobooklet->id, $raobooklet->course, false, MUST_EXIST);
    $context = context_module::instance($cm->id);
    $path = $CFG->dataroot.'/raobooklets/'.$context->id;
    $out = exec("rm -r $path");
    if($output == ''){
        $DB->delete_records('raobooklet', array('id' => $raobooklet->id));
        return true;
    } else {
        return FALSE;
    }

    // TODO: Delete feedbacks also
}

function raobooklet_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}
        
        
        
/**
* Move the file from the path to the archives and change its name to $path_date
*
* @param $path Full path to the file
* @return boolean True/False
*/
function raobooklet_archive_file($filename) {
    return TRUE;
}
        
        
/**
* The file download callback function used for sending file to user
*
* @return boolean file/False False for any kind of error
*/
function raobooklet_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Checks for contextlevel, view permissions, logged in, etc.
    
    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }
    if ($filearea !== 'booklets') {
        return false;
    }
    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    if (!has_capability('mod/raobooklet:view', $context)) {
        return false;
    }
    // if (!has_capability('mod/raobooklet:download', $context)) {
    //     return false
    // }
    
    $itemid = array_shift($args); // Year. The first item in the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }
    
    // Retrieve the file
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_raobooklet', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }
    
    // Send the file
    send_stored_file($file);
    // TODO Analytics for file download
}
        
        
// Feedback functions

/**
* Add feedback
* @param $id int Raobooklet instanceof
* @param $rating int Rating out of 5
* @param $comment str Comment
* @return bool Success/Failure
*/
function raobooklet_add_or_update_feedback($rbid, $rating, $comment, $userid) {
    global $DB;
    
    
    
    if($fb = $DB->get_record('raobooklet_feedback', array('userid'=> $userid))) {
        $fb->rating = $rating;
        $fb->comment = $comment;
        $fb->timemodified = time();
        $result = $DB->update_record('raobooklet_feedback', $fb);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        // Setup feedback object
        $feedback = new stdclass();
        $feedback->raobookletid = $rbid;
        $feedback->rating = $rating;
        $feedback->comment = $comment;
        $feedback->userid = $userid;
        $feedback->timecreated = time();
        $result = $DB->insert_record('raobooklet_feedback', $feedback);
        if ( $result ){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

function raobooklet_update_feedback($fb, $newrating, $newcomment) {
    $fb->rating = $newrating;
    $fb->comment = $newcomment;
    
    $result = $DB->update_record('raobooklet_feedback', $fb);
    if ($result) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function mod_raobooklet_myprofile_navigation(core_user\output\myprofile\tree $tree, $user, $iscurrentuser, $course){
    $category = new core_user\output\myprofile\category('RaoSettings', 'RaoSettings', null);
    $yesno = <<<HTML
    Click yes to confirm the profile picture. If you want it changed, click no. <br>
    <a class="badge badge-success" href="http://www.google.com/">Yes</a>
    <a class="badge badge-danger" href="http://www.google.com/">No</a>
HTML;
    $node = new core_user\output\myprofile\node('RaoSettings', 'raosetting_pic', '<h6>Approve you profile picture?</h6>', null, null, $yesno);
    $category->add_node($node);
    $tree->add_category($category);
    return TRUE;
}