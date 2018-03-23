<?php
require_once('../../config.php');
// Variables used in the page
$errormessage = isset($SESSION->otp_error) ? $SESSION->otp_error : '';
unset($SESSION->otp_error);
if (isset($SESSION->otp_username)){
  $username = $SESSION->otp_username;
} else {
  //redirecto login page 
  redirect(new moodle_url('/login/index.php'));
}
if (isset($SESSION->otp_error)){
  $otp_error = $SESSION->otp_error;
}
$sesskey = $USER->sesskey;
//$formaction = $CFG->wwwroot.'/auth/otp/otplogin.php';
$formaction = $CFG->wwwroot.'/login/index.php';
?>

<html dir="ltr" lang="en" xml:lang="en">
<head>
  <title>Rao IIT: OTP</title>
  <link rel="shortcut icon" href="<?php echo $CFG->wwwroot.'/theme/image.php/lambda/theme/1485429272/favicon'?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="moodle, Rao IIT: Log in to the site" />
  <link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/styles.php/lambda/1485429272/all'?>" />
  <meta name="robots" content="noindex" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Google web fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400|Open+Sans:700" rel="stylesheet">
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  <script type='text/javascript'>
    function resend_otp_sms(){
      //var username = '<?php echo $username; ?>';
      $.ajax({
        url:'/auth/otp/otp_resend.php?t='+Math.random(),
        success: function(data, text){
          alert(data);
        },
        error: function (request, status, error) {
          alert(request.responseText);
        } 
      })
    };
    
</script>
</head>

<body id="page-login-index" class="format-site  path-login safari dir-ltr lang-en yui-skin-sam yui3-skin-sam localhost--moodle pagelayout-login course-1 context-1 notloggedin login_lambda has-region-footer-left empty-region-footer-left has-region-footer-middle empty-region-footer-middle has-region-footer-right empty-region-footer-right content-only">
  <div id="wrapper" style="background: transparent none repeat scroll 0 0; border: medium none;">
    <header id="page-header" class="clearfix">
      <div class="container-fluid">
        <div class="row-fluid">
          <!-- HEADER: LOGO AREA -->
          <div class="span6">
            <h1 id="title" style="line-height: 2em">Rao IIT</h1>
          </div>
        </div>
      </div>
    </header>

    <div id="page" class="container-fluid">
      <div id="page-content" class="row-fluid" style="background-clip:padding-box;background-color: rgba(255, 255, 255, 0.85);border: 8px solid rgba(255, 255, 255, 0.35);border-radius: 3px;">
        <section id="region-main" class="span12">
          <div role="main"><span id="maincontent"></span>
            <div class="loginbox clearfix onecolumn">
              <div class="loginpanel">
                <h2>Log in</h2>
                <div class="subcontent loginsub">
                  <form autocomplete="off" accept-charset="utf-8" action="<?php echo $formaction ?>" method="post" id="mform1">
                    <input name="sesskey" type="hidden" value="<?php echo $sesskey ?>" />
                    <div class="loginform">
                      <input type='hidden' name='username' value='<?php echo $username;?>'/>
                      <div class="form-label">
                        <?php if($errormessage){ echo "<p class='error'>$errormessage</p>"; }?>
                        <label for="password">Enter OTP to continue</label>
                      </div>
                      <div class="form-input">
                        <input type="text" name="password" id="password" size="15" value="" placeholder="Enter OTP"/>
                      </div>
                    </div>
                    <input type="submit" id="loginbtn" value="Submit" />
                    <input type="button" id="resend_otp" value="Resend OTP" onclick="resend_otp_sms()" />
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <footer id="page-footer" class="container-fluid" style="display:none;">
      <div class="footerlinks">
        <div class="row-fluid">
          <p class="helplink"></p>
          <div class="footnote">
            <p>Copyright (c) of Raoedusolutions</p>
          </div>
        </div>
      </div>
    </footer>
</body>

</html>

