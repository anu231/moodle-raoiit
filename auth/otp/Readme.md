### Instructions
1. Push `"otp"` to the `$auths` array in `authenticate_user_login()` function in `moodlelib.php`.
Then surround the 
``` 
// moodlelib.php

array_push($auths,'otp');                           // Line no 4136

...

if($authplugin->authtype != 'otp'){                 // Line no 4196
    update_internal_user_password($user, $password);
}
```


2. Change **phone number** (`$no`) in `classes/otputil.php` for dev purposes

3. Change **login forms** from `/login/index.php` to `/auth/otp/login.php`.\
    - lambda navbar login form located in `/theme/lambda/layout/includes/header.php`

5. *( IDK why I wrote this step! Do at your own risk! )* modify /login/index.php to handle wrong password 


