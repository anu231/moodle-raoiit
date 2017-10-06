<?php

class block_readytohelp_renderer extends plugin_renderer_base {
    public function grievance_list($username) {
        // Display a list of all the grievances for a vaild user
        $grievance_list = new grievance_list($username);
        return $this->render($grievance_list);
    }

    public function render_grievance_list($gl){
        return $this->render_from_template('block_readytohelp/grievance_list', $gl);
    }

    public function grievance_detail($gid, $gmode='',$branch_view=false) {
        // Display the grievance thread
        $grievance_detail = new grievance_detail($gid, $gmode,$branch_view);
        return $this->render($grievance_detail);
    }

    public function render_grievance_detail(grievance_detail $gd){
        return $this->render_from_template('block_readytohelp/grievance_detail', $gd);
    }

    public function grievance_responses(){
        // Mod management page
        return $this->render(new grievance_responses());
    }

    public function render_grievance_responses(grievance_responses $gr){
        return $this->render_from_template('block_readytohelp/grievance_responses', $gr);
    }

    public function manage_departments(){
        // Manage departments, emails etc
        return $this->render(new manage_departments());
    }

    public function render_manage_departments(manage_departments $depts){
        return $this->render_from_template('block_readytohelp/grievance_manage_departments', $depts);
    }
}

// Detailed view of a grievance for student/mod (Chat bubble view)
class grievance_detail implements renderable{
    public function __construct($gid, $gmode, $branch_view){
        $grievance = $this->get_grievance_thread($gid, $gmode);
        $this->query = $grievance['query'];
        $this->responses = $grievance['responses'];
        //if ($branch_view){
        //    $this->username = 'anonymous';    
        //} else{
            $this->username = $grievance['userdetails']['username'];
        //}
        if(local_raomanager_has_permission('ReadyToHelp'))
            $this->showstatusbuttons = TRUE;
        else
            $this->showstatusbuttons = FALSE;
        $statusaction = $this->query['status'] == 'open' ? 'close' : 'open';
        $this->statusactionname = $statusaction == 'open' ? 'Open Grievance' : 'Close Grievance';
        $this->statuslink = "view.php?gid=$gid&action=$statusaction";
    }

    private function get_grievance_thread($gid, $gmode){
        // return array of grievance entry, its category and all its responses, if any
        global $DB, $USER;
        $entry = $DB->get_record('grievance_entries', array('id' => $gid));
        $category = $DB->get_record('grievance_categories', array('id' => $entry->category));
        $responses = $DB->get_records('grievance_responses', array('grievance_id' => $gid));
        $deptmap = $DB->get_records_menu('grievance_departments'); // Cache deptid->name map
        $deptmap[-1] = 'admin';  // Admin replies have deptid = -1
        $formatted = array(
            'query' => null,
            'responses' => array(),
            'userdetails' => array()
        );

        $formatted['userdetails'] = array(
            'username' => $entry->username,
        );

        $formatted['query'] = array(
            'time' => strftime('%d/%m/%G-%R', $entry->timecreated),
            'category' => $category->name,
            'subject' => $entry->subject,
            'description' => $entry->description,
            'status' => $entry->status
        );

        $showresponses = FALSE;
        foreach($responses as $r){
            if($r->approved == 1){ // Show responses if approved
                $showresponses = TRUE;
                break;
            }
        }
        if($gmode != ''){
            $showresponses = TRUE;
        }

        if($showresponses){
            $index = 0;
            $lastmodrespindex = null;
            foreach($responses as $resp){
                $isuser = (int)$resp->email ? true : false; // Tracks whether the response belongs to a user. (user responses dont have emails in them)
                $islast = $index == count($responses) - 1; // Track last response index.

                $lastmodrespindex = !$isuser && $resp->approved == 1 ? $index : $lastmodrespindex; // index of the last APPROVED mod reply
                $tmp = array(
                    'gid' => $gid,
                    'rid' => $resp->id,
                    'deptid' => $resp->deptid,
                    'userid' => $USER->username, // Due to mustache scoping issue, inject this in every response
                    'email' => $resp->email,
                    'time' => strftime('%d/%m/%G-%R', $resp->timecreated),
                    'body' => $resp->body,
                    'department' => $isuser ? '' : $deptmap[$resp->deptid],
                    'class' => $isuser ? 'user' : 'mod',  // determine author (For chat bubble styling)
                    'modctrl' => $isuser ? 'hide' : 'show', // TODO remove this
                    'reply-div-show' => 'hide' // Show reply button for the last bubble
                );
                if (isset($USER->username)){
                    $tmp['userid'] = $USER->username;
                }
                if( $gmode != '' ){ // Dont skip anything in godmode
                    $tmp['gmode'] = $gmode;
                    if($resp->approved == 1){
                        $tmp['approved'] = 'yes';
                    } else if ($resp->approved == -1){
                        $tmp['approved'] = 'no';
                    } else {
                        $tmp['approved'] = 'pending'; // Wtf is this?
                    }
                    $tmp['mod_controls'] = $isuser == true ? 'hide' : 'show'; // Don't show controls for user chatbox
                    $formatted['responses'][$index] = $tmp;
                    $index++;
                } else if ($resp->approved == 1 || $isuser ){ // Skip unapproved responses
                    $formatted['responses'][$index] = $tmp;
                    $index++;
                }

            }
            // Show reply button for the last mod reply
            if(isset($lastmodrespindex) && $gmode == ''){
                $formatted['responses'][$lastmodrespindex]['reply-div-show'] = 'show';
            }
        } else {
            if( $gmode != '' ){ // Dont skip anything in godmode
                $tmp = array(
                    'gid' => $gid,
                    'deptid' => $entry->category,
                    'time' => strftime('%d/%m/%G-%R', time()),
                    'body' => '<em> No response yet<br>You will be notified by email as soon as we reply.</em>',
                    'class' => 'mod',
                    'reply-div-show' => 'show'
                );
                $tmp['gmode'] = $gmode;
                $tmp['mod_controls'] = 'hide'; // hide modcontrols for zero replies
                $formatted['responses'][0] = $tmp;
            } else {
                 $formatted['responses'][0] = array(
                    'time' => "",
                    'body' => '<em> No response yet<br>You will be notified by email as soon as we reply.</em>',
                    'class' => 'mod',
                    'reply-div-show' => 'show'
                );
            }
           
        }
        return $formatted;
    }
}

// List of a students grievances for student view
class grievance_list implements renderable {
    public function __construct($username){
        $grievances = $this->get_entries_for_username($username);
        if($grievances){
            $this->grievances = $grievances;
        } else {
            $this->grievances = array('You have not raised any grievances yet');
        }
    }

    private function get_entries_for_username($username){
        // Return db records 
        global $DB;
        $grievances = $DB->get_records('grievance_entries',array('username'=>$username));
        $categories = $DB->get_records_menu('grievance_categories');

        if(!$grievances){
            return false;
        }

        $gids = ""; // Options string
        $counter = 0;
        foreach ($grievances as $g) {
            if($counter == count($grievances) - 1){
                $gids .= "'".$g->id."'"; // No trailing comma for last element
            } else {
                $gids .= "'".$g->id."', ";
            }
            $counter++;
        }
        // Get responses for each grievance
        $where = "grievance_id IN ($gids)";
        $responses = $DB->get_records_select('grievance_responses', $where);

        $formatted = array();
        $index = 1;
        foreach($grievances as $g){
            $temp = array();
            $temp['index'] = $index;
            $temp['_gid'] = $g->id;
            // $temp['_deptid'] = $g->category;
            $temp['date'] =  strftime('%d-%m-%G', $g->timecreated);
            $temp['category'] = $categories[$g->category];
            $temp['subject'] = $g->subject;
            $temp['status'] = $g->status;
            $temp['response'] = $this->get_number_responses($responses, 'grievance_id', $g->id);
            $formatted[] = $temp;
            $index++;
        }
        // return array("list" => $formatted);
        return $formatted;
    }

    // Return string for number of responses for a grievance
    private function get_number_responses($elements, $key, $val){
        $index = 0;
        $counter = 0;
        foreach($elements as $element){
            if( $element->$key == $val && $element->approved == 1){
                $counter++;
            }
            $index++;
        }
        if($counter == 0){
            return "No responses yet";
        } else if ($counter == 1){
            return "1 Response (click to view)";
        } else {
            return "$counter Responses (click to view)";
        }
    }
}



// Grievance overview for mods
class grievance_responses implements renderable {
    public function __construct($username=null){
        $results = $this->get_all_grievances();
        $this->grievances = $this->process_grievances($results);
        $this->message = $this->grievances ? '' : 'No responses found';
        // echo var_dump($this->responses);
    }

    // Returns grievanceset iterable
    private function get_all_grievances(){
        global $DB, $CFG;
        $sql = <<<SQL
        SELECT
            response.id AS rid,
            entry.id AS eid,
            category.name AS cname,
            category.id AS cid,
            entry.timecreated AS etimecreated,
            entry.subject AS esubject,
            entry.description AS edescription,
            entry.username AS username,
            entry.status AS estatus,
            entry.department AS edepts,
            department.name AS deptname,
            response.approved AS rapproved,
            response.email AS email,
            response.body AS body,
            userinfo_b.data as ttbatchid,
            userinfo_c.data as centerid
        FROM
            `mdl_grievance_entries` as entry
        LEFT OUTER JOIN
            `mdl_grievance_responses` as response
            ON response.grievance_id = entry.id
        LEFT OUTER JOIN
            `mdl_grievance_departments` as department
            ON department.id = entry.department
        JOIN
            `mdl_grievance_categories` as category
            ON category.id = entry.category
        JOIN 
            `mdl_user` as user
            on entry.username=user.username
        JOIN
            (SELECT userid as userid, data as data, fieldid as fieldid FROM mdl_user_info_data where fieldid = '$CFG->BATCH_FIELD_ID') as userinfo_b
            on user.id = userinfo_b.userid
        JOIN
            (SELECT userid as userid, data as data, fieldid as fieldid FROM mdl_user_info_data where fieldid = '$CFG->CENTER_FIELD_ID') as userinfo_c
            on user.id = userinfo_c.userid
        ORDER BY
            -response.timecreated
SQL;
        $grievanceset = $DB->get_recordset_sql($sql);
        return $grievanceset;
    }

    // Return index of an element in an array. -1 if not found
    private function search_by_key($elements, $key, $val){
        $index = 0;
        $found = FALSE;
        foreach($elements as $element){
            if( $element->$key == $val){
                $found = TRUE;
                return $index;
            }
            $index++;
        }
        if( $found == FALSE ){
            return -1;
        }
    }
    private function get_centre_list(){
        global $DB;
        $centrelist = $DB->get_records('branchadmin_centre_info');
        return $centrelist;
    }
    private function get_batch_list(){
        global $DB;
        $batches = $DB->get_records('branchadmin_ttbatches');
        return $batches;
    }
    private function process_grievances($grievances){
        global $CFG, $COURSE, $DB;
        $processed = array();
        $index = 0;
        $stack = array(); // Stack holds grievances only (not responses)
        $deptmap = $DB->get_records_menu('grievance_departments'); // Cache deptid->name map
        $centre_map = $this->get_centre_list();
        $batch_map = $this->get_batch_list();
        foreach($grievances as $g){
            $isuser = (int)$g->email ? true : false;
            $stackindex = $this->search_by_key($stack, 'eid', $g->eid);
            if($stackindex == -1 ){
                // New Entry found. Push it on stack
                $index++;
                $g->index = $index;
                $g->edescription = strlen($g->edescription) >=120 ? substr($g->edescription, 0, 120).'...' : $g->edescription;  // Culled to 120 chars
                $g->username = $g->username;
                $g->centre = $centre_map[$g->centerid]->name;
                $g->ttbatch = $batch_map[$g->ttbatchid]->name;
                $g->approvedcount = $g->rapproved == '1' && $g->rapproved != null && !$isuser ? 1 : 0; // Don't count user replies
                $g->replycount = $g->body != NULL && !$isuser ? 1 : 0; // Don't count user replies
                $g->userreplycount = 0;
                $g->etimecreated = strftime('%G/%m/%d/-%R', $g->etimecreated);
                $g->category = $g->cname;
                $g->departments = $this->get_dept_names_from_ids($g->edepts, $g->eid, $deptmap);
                $gphrase = sha1($g->eid.'aybabtu'.'-1'); // Hash for performing admin stuff
                $email = 'admin';                           // Email = 'admin' for admin
                $hash = sha1($g->eid.'aybabtu'.$email); // Allow admin reply capability
                $g->viewlink = "$CFG->wwwroot/blocks/readytohelp/view.php?gid=$g->eid&deptid=-1&gmode=$gphrase&email=$email&hash=$hash"; // Link to the thread. Department = -1 -> admin replying
                if($g->edepts)  // Display remind link only if a department has been assigned to the query
                    $g->remindlink = "review_response.php?action=remind&gid=$g->eid&cid=$COURSE->id";
                $g->managedeptlink = "view.php?action=assigndept&gid=$g->eid";
                $stack[] = $g;
            } else {
                // Response to entry found. Update the Entry on stack accordingly
                if( !$isuser ){ // Skip user replies
                    $stack[$stackindex]->replycount++;
                    $g->rapproved == 1 ? $stack[$stackindex]->approvedcount++ : null;
                    $stack[$stackindex]->isLastReplyFromUser = FALSE; // If last reply is from user, mark it so.
                } 
                if ($isuser) {
                    $stack[$stackindex]->userreplycount++;
                    $stack[$stackindex]->isLastReplyFromUser = TRUE; // If last reply is from user, mark it so.
                }
            }
            if ($g->estatus == 'closed'){
                $g->status_class = 'success';
            } else if ($g->estatus == 'open'){
                $g->status_class = 'danger';
            }
        }
        return $stack;
    }

    // Return formatted list of departments assigned.
    // $eid - entry id for assigning new departments
    private function get_dept_names_from_ids($ids, $eid, $deptmap){
        global $DB;
        $html = '';
        if($ids){
            $ids = explode(',', $ids);
            $index = 1;
            foreach ($ids as $id) {
                if($name = $deptmap[$id]) {
                    $html .= $index.") ".$name."<br>";
                    $index++;
                }
            }
        }
        if($html)
            return $html;
        else
            return "No departments assigned";
    }
}


class manage_departments implements renderable {
    public function __construct(){
        global $COURSE;
        $this->courseid = $COURSE->id;
        $departments = $this->get_department_info();
        $this->departments = $departments;
    }

    private function get_department_info(){
        // Returns an array of departments along with related data
        global $DB;
        $depts = $DB->get_records("grievance_departments");
        $departments = array();
        foreach($depts as $d) { // Silly loop because mustache doesnt accept raw results
            $d->emails  = $this->get_emails_from_string($d->email);
            if($d->emails == ""){
                $d->message = "No emails assigned";
            }
            $departments[] = $d;
        }
        return $departments;
    }

    private function get_emails_from_string($emailstr){
        if($emailstr == null){
            return "";
        }

        $emails = explode(',', $emailstr);
        $emailarr = array();
        $counter = 0;
        foreach($emails as $email){
            if($email == ""){ // Sometimes explode returns empty strings. Skip them.
                continue;
            }
            $counter++;
            $tmp = new stdClass;
            $tmp->email = trim($email);
            $emailarr[] = $tmp;
        }
        return $emailarr;
    }
}
