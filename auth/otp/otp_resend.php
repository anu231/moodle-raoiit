<?php

require('../../config.php');
//require('../../lib/moodlelib.php');
global $CFG, $SESSION;
$msg = '';

if (isset($SESSION->otp_username)){
  $username = $SESSION->otp_username;
  $otp = new auth_otp_otputil();
  $ret = $otp->resend_otp_sms($username);
  if ($ret == 1){
      //success
      $msg = 'OTP sent successfully';
  } else if ($ret == -1){
      http_response_code(400);
      $msg = 'No OTP exists for this session';
  } else if ($ret == -2){
      http_response_code(400);
      $msg = 'OTP has expired. Please generate another OTP from the login page';
  } else if ($ret == -3){
      http_response_code(500);
      $msg = 'Server encountered an error in sending OTP. Please try again after sometime';
  }
} else {
  $msg = 'No requests for OTP has been made';
}

echo $msg;