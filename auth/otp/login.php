<?php

require('../../config.php');
//require('../../lib/moodlelib.php');
global $CFG, $SESSION;

$err = '';
if (($data=data_submitted())){
    //check the data
    $username = $data->username;
    $password = $data->password;
    if (($user=authenticate_user_login($username, $password))){
        //generate otp
        $otp = new auth_otp_otputil();
        if ($otp->generate_otp($user)){
            //otp successfully generated
            //redirect to otp checking page
            $SESSION->otp_username = $user->username;
            redirect(new moodle_url('/auth/otp/otpchk.php'));
        } else {
            $err = $otp->error;
        }
    } else{
        $SESSION->login_error = 'Invalid Login, please try again';
        redirect(new moodle_url('/login'));
    }
} else {
    redirect(new moodle_url('/login'));
}
