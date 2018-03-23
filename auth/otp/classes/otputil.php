<?php
//namespace local_otpauth;

defined('MOODLE_INTERNAL') || die();

class auth_otp_otputil {
    //generates a 6 digit otp
    const max = 999999;
    const min = 100001;
    const validity = 15;//validity of otp in min
    const sms_text = 'OTP for edumate.raoiit.com is - ';
    public $success;
    public $error;
    private function sendSMS(&$s_mobile, &$s_text){
        //initialize the request variable
        $this->success = '';
        $this->error = '';
        $request = "";
        //this is the key of our sms account
        $param["workingkey"] = "3693f2jl9yh7375b0o1i";
        //this is the message that we want to send
        $param["message"] = stripslashes($s_text);
        //these are the recipients of the message
        $param["to"] = $s_mobile;
        //this is our sender
        $param["sender"] = "RAOIIT";

        //traverse through each member of the param array
        foreach($param as $key=>$val){
            //we have to urlencode the values
            $request.= $key."=".urlencode($val);
            //append the ampersand (&) sign after each paramter/value pair
            $request.= "&";
        }
        //remove the final ampersand sign from the request
        $request = substr($request, 0, strlen($request)-1); 

        //this is the url of the gateway's interface
        $url = "http://alerts.prioritysms.com/api/web2sms.php";

        //initialize curl handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //set the url
        curl_setopt($ch, CURLOPT_POST, count($param)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 	
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle

        $responseary = explode("Invalid/DND Numbers:", $response);

        if (substr(trim($responseary[0]), 0, 12) == "Message GID=") // delivered successfully
        {
            $successary = explode(",", trim($responseary[0]));
            $this->success .= "OTP successfully delivered to " . sizeof($successary) . " mobile numbers.";
            return true;
        }
        if (strlen(trim($responseary[1])) != 0)	// errors
        {
            $this->error .= "Invalid/DND Numbers: " . trim($responseary[1]);
            return false;
        }

    }
    /*
    generates the otp and stores it in the database
    */
    public function generate_otp($user){
        $otp = mt_rand(self::min,self::max);
        global $DB;
        //store this otp in database
        $data = new stdClass();
        $data->username = $user->username;
        $data->otp = $otp;
        $data->timecreated = time();
        $data->used = 0;
        $ret = $DB->insert_record('auth_otp',$data);
        if (!empty($ret)){
            //otp created successfully
            //send it to user
            //$no = '8446481468';
            $no = '9619410635';
            $text = 'OTP for analysis.raoiit.com is - '.$otp;
            if ($this->sendSMS($no,$text)){
                //successfully sent sms
                return true;
            } else {
                return false;
            }
        } else {
            $this->error = 'Error in entering the OTP in database';
            return false;
        }
    }

    public function send_parent_login_otp($username, $mob){
        $otp = mt_rand(self::min,self::max);
        global $DB;
        //store this otp in database
        $data = new stdClass();
        $data->username = $username;
        $data->otp = $otp;
        $data->timecreated = time();
        $data->used = 0;
        $data->mobile = $mob;
        $ret = $DB->insert_record('auth_otp',$data);
        if (!empty($ret)){
            //otp created successfully
            //send it to user
            $text = self::sms_text.$otp;
            if ($this->sendSMS($mob,$text)){
                //successfully sent sms
                return true;
            } else {
                return false;
            }
        } else {
            $this->error = 'Error in entering the OTP in database';
            return false;
        }
    }

    public function resend_otp_sms($username) {
        global $DB;
        $otp_record = $DB->get_record('auth_otp', array('username'=>$username, 'used'=>0));
        if (empty($otp_record)){
            return -1;
        } else{
            if ((time()-$otp_record->timecreated)/60 > self::validity){
                return -2;
            } else {
                //resend otp
                $sms_text = self::sms_text.$otp_record->otp;
                if ($this->sendSMS($otp_record->mobile, $sms_text)){
                    return 1;
                } else {
                    return -3;
                }
            }
        }
    }
    /*
    checks the otp, if valid deletes it from the table
    Also checks the validity of the otp
    return true if successful else false
    */
    public function check_otp($username, $otp){
        //fetch otp for userid from database
        global $DB;
        $records = $DB->get_records('auth_otp',array('username'=>$username));
        if (empty($records)){
            $this->error = 'No OTP entry exists for user - '.$username;
            return false;
        }
        foreach ($records as $rec){
            //check otp
            if ($otp != $rec->otp){
                //otp mismatch
                continue;
            }
            //check validity
            if ((time()-$rec->timecreated)/60 > self::validity){
                $this->error = 'OTP expired';
                $this->use_otp_by_id($rec->id);
                return false;
            }
            $this->use_otp($rec->username);
            return true;
        }
        $this->error = 'Wrong OTP';
        return false;
    }
    /*
    marks the otp entries as used for the provided user
    */
    private function use_otp_by_id($id){
        global $DB;
        $data = array();
        $data['id'] = $id;
        $DB->execute('update mdl_auth_otp set used=1 where id=?',$data);
    }
    /*
    marks the otp entries as used for the provided user
    */
    private function use_otp($user_id){
        global $DB;
        $data = array();
        $data['username'] = $user_id;
        $DB->execute('update mdl_auth_otp set used=1 where username=?',$data);
    }
}
