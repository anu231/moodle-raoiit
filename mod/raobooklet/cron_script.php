<?php
define('CLI_SCRIPT', true);
require_once('../../config.php');

/**
 * cron_script.php
 * 
 * This script is supposed to be run every hour.
 * Loops over all uploaded files and converts pdfs to jpegs
 *
 * Pattern: "$PARENTPATH/fileid/123.jpg"
 */

global $CFG, $DB;
$fs = get_file_storage();

$PARENTPATH = $CFG->dataroot.'/raobooklets';
$LOGFILE = $CFG->dataroot.'/raobooklets/logfile.log';


function log2file($message, $type=null){
    global $LOGFILE;
    echo $message."<br>";
    if($type == 0) $message = "[SUCCESS] ".$message.'\n';
    else if($type == 1) $message = "[WARNING] ".$message.'\n';
    else if($type == 2) $message = "[ERROR] ".$message.'\n';
    else $message = $message.'\n';
    file_put_contents($LOGFILE, $message, FILE_APPEND);
}


// Get list of uploaded files
// $booklets = $DB->get_records('files', array('component'=>'mod_raobooklet'));
$context = context_system::instance();
$files = $fs->get_area_files($context->id, 'mod_raobooklet', 'uploads', 0);
echo count($files);
foreach ($files as $file) {
    if(is_dir($PARENTPATH.'/'.$file->get_id())) continue;
    if($file->get_filename() == '.') continue;
    $hash = $file->get_contenthash();
    
    // Get the filepath
    $filepath = $CFG->dataroot.'/filedir/'.substr($hash, 0, 2).'/'.substr($hash,2, 2).'/'.$hash;
    $dir = $PARENTPATH.'/'.$file->get_id();
    $success1 = mkdir($dir,0774,true);

    if(!is_dir($dir)) 
        log2file("Couldn't create directory: ".$dir, 2);
    else {
        // Start Conversion;
        log2file("Starting conversion at: ".time());
        $cmd = 'convert -density 200 -quality 90 "'.$filepath.'" "'.$dir.'/%d.jpg"';
        log2file('Executing the following command : '.$cmd);
        $ret = exec($cmd,$output,$ret_var);
        if ($ret_var==0){
            log2file('Conversion complete', 0);
        } else {
            log2file('Conversion failed', 2);
            log2file('Attempting to delete folder for bookletid: '.$file->id, 1);
            $success2 = rmdir($dir);
            if(!$success2)
                log2file("Directory deletion failed: ".$dir);
            else
                log2file("Done ".$dir);
        }

        log2file("Conversion Ended at: ".time(), 0);
    }
}

