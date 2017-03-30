<?php


defined('MOODLE_INTERNAL') || die();

/**
 * A collection of mobile numbers to be sent
 */
class local_raomanager_notificationsms extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $recipients = $data->numbers; // Array of mobile numbers
        $body = $data->body;
        $recipients = array('8446481468'); // TODO Remove in prod
        
        $this->sendSMS($recipients, $body);

        // Job is always successful
        return 1;
    }

    private function sendSMS(&$recipients, &$s_text){
        //initialize the request variable
        $request = "";
        //this is the key of our sms account
        $param["workingkey"] = "3693f2jl9yh7375b0o1i";
        //this is the message that we want to send
        $param["message"] = stripslashes($s_text);
        //these are the recipients of the message
        $param["to"] = join(',', $recipients);
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
            echo "Notification delivered successfully to " . sizeof($successary) . " mobile numbers";
            return true;
        }
        if (strlen(trim($responseary[1])) != 0)	// errors
        {
            echo "Invalid/DND Numbers: " . trim($responseary[1]);
            return false;
        }

    }

}