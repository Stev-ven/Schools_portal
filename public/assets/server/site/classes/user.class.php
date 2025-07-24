<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class User extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function setCookieVars($idata = array(), $time = "+1 month")
    {
        foreach ($idata as $key => $value) {
            $_COOKIE[$key] = $value;
            setcookie($key, $value, strtotime($time), "/", null, false, true);
        }
    }

    public function unsetCookieVars($vars = array())
    {
        foreach ($vars as $var) {
            if (isset($_COOKIE[$var])) {
                unset($_COOKIE[$var]);
                setcookie($var, null, strtotime("-10 years"), "/", null, false, true);
                return true;
            } else {
                return false;
            }
        }
    }

    public function clearAllCookies()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }

    public function signOut()
    {
        $_SESSION["admin_is_logged_in"] = "no";
        $_SESSION["emode"] = "no";
        unset($_SESSION["emode"], $_SESSION["admin_full_name"], $_SESSION["admin_id"], $_SESSION["admin_id_encrypted"], $_SESSION["admin_profile_photo_small"], $_SESSION["admin_user_name"]);
        $this->redirectURL(DOMAIN . "login");
    }

    public function updateProfilePhotoSessionVar($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */
        
        $_SESSION["admin_profile_photo_small"] = $idata["photo"];
    }

    public function updateProfileNamesSessionVar($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $_SESSION["admin_full_name"] = $idata["full_name"];
        $_SESSION["admin_user_name"] = $idata["user_name"];
    }
    
    public function getSingleUserHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfUsers($this->getListOfUsers(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function recoverUserAccountPassword($idata = array())
    {
        /**
         * @param array $idata
         * @return string
         */

        $email = $this->filterData($idata["account_email"] ?? null);

        if(!$errors){
            if ($this->isEmpty($email)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "The email is required.";
                die($this->encodeJSONdata($response));
            }
        }
        else{
            if ($this->isEmpty($email)) {
                $errHTMLids[] = array(
                    "account_email",
                    "Email is required.",
                );
            }
    
            if (isset($errHTMLids) && count($errHTMLids) > 0) {
                $response["status"] = ERROR_CODE;
                $response["errors"] = $errHTMLids;
                $response["data"] = "There are some issues with your form. Kindly fix them to continue.";
                die($this->encodeJSONdata($response));
            }
        }

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        if (!$this->userEmailExists($this->conn, $email)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "User was not found.";
            die($this->encodeJSONdata($response));
        }

        $willChangePasswordToken = $this->hashGenerator($email . time() . uniqid());

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `users_admin` SET `will_change_password_token` = :willChangePasswordToken WHERE `email_address` = :email LIMIT 1",
            array(
                "willChangePasswordToken" => $willChangePasswordToken,
                "email" => $email,
            )
        );

        if ($result) {
            //send email for change of account password
            // $this->FactoryClass()->MailerClass()->sendEMail(
            //     $email,
            //     "Change Your User Account Password - ".APPNAME,
            //     $this->FactoryClass()->MailerClass()->changePasswordEmailTemplate(array(
            //         "email" => $email,
            //         "title" => "Change Your User Account Password - ".APPNAME,
            //         "link" => $isAdmin ? ADMIN_DOMAIN."change-user-account-password?token=".$willChangePasswordToken : DOMAIN."change-password?token=".$willChangePasswordToken,
            //     ))
            // );

            $response["status"] = SUCCESS_CODE;
            $response["data"] = "We have sent you an email containing a link to change your user account password. Try resending it if you did not receive the email.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Something went wrong. Please try again.";
        die($this->encodeJSONdata($response));
    }

    public function changeUserAccountPassword($idata = array())
    {
        /**
         * @param array $idata
         * @return string
         */

        $token = $this->filterData($idata["account_token"] ?? null);
        $password = $this->filterData($idata["account_password"] ?? null);
        $password_confirm = $this->filterData($idata["account_password_confirm"] ?? null);

        if (!$this->isPasswordMatching($password, $password_confirm)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Password do not match.";
            die($this->encodeJSONdata($response));
        }

        if(!$errors){
            if ($this->isEmpty($token)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Token is required.";
                die($this->encodeJSONdata($response));
            }
    
            if ($this->isEmpty($password)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Password must contain 8 or more characters. To make your password stronger, consider mixing it with capital letters, numbers or some symbols.";
                die($this->encodeJSONdata($response));
            }
    
            if ($this->isEmpty($password_confirm)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Password do not match.";
                die($this->encodeJSONdata($response));
            }
        }
        else{
            if ($this->isEmpty($token)) {
                $errHTMLids[] = array(
                    "account_token",
                    "Token is required.",
                );
            }
    
            if ($this->isEmpty($password)) {
                $errHTMLids[] = array(
                    "account_password",
                    "Password must contain 8 or more characters. To make your password stronger, consider mixing it with capital letters, numbers or some symbols.",
                );
            }
    
            if ($this->isEmpty($password_confirm)) {
                $errHTMLids[] = array(
                    "account_password_confirm",
                    "Password do not match.",
                );
            }
    
            if (isset($errHTMLids) && count($errHTMLids) > 0) {
                $response["status"] = ERROR_CODE;
                $response["errors"] = $errHTMLids;
                $response["data"] = "There are some issues with your form. Kindly fix them to continue.";
                die($this->encodeJSONdata($response));
            }
        }

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        //Check if token exists
        $tokenExists = $this->runQuery(
            $this->conn,
            "SELECT NULL FROM `users_admin` WHERE `will_change_password_token` = :token AND `will_change_password_token` != '' LIMIT 1",
            array("token" => $token),
            true,
            true
        );

        if (!$tokenExists) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Password change token does not exist.";
            die($this->encodeJSONdata($response));
        }

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `users_admin` SET `will_change_password_token` = NULL, `password` = :account_password WHERE `will_change_password_token` = :willChangePasswordToken LIMIT 1",
            array(
                "willChangePasswordToken" => $token,
                "account_password" => $this->hashGenerator($password),
            )
        );

        if ($result) {
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Your user account password has been successfully changed. Redirecting you in a moment... Please wait.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Something went wrong. Please try again.";
        die($this->encodeJSONdata($response));
    }

    public function changeUserAccountPasswordManual($idata = array())
    {
        /**
         * @param array $idata
         * @return string
         */
        
        $is_admin = "yes";
        $user_id = USERID;
        $old_password = $this->filterData($idata["old_account_password"] ?? null);
        $password = $this->filterData($idata["account_password"] ?? null);
        $password_confirm = $this->filterData($idata["account_password_confirm"] ?? null);

        if (!$this->isPasswordValid($password)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Password must contain 8 or more characters. At least one symbol and one capital letter are required. To make your password stronger, consider mixing it with capital letters, numbers or some symbols. Example: 2#@{}Qwer4r.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->isPasswordMatching($password, $password_confirm)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Password do not match.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        //Check if token exists
        $checkUserPassword = $this->runQuery(
            $this->conn,
            "SELECT `password` FROM `users_admin` WHERE `id` = :id LIMIT 1",
            array("id" => $user_id),
            true,
            true
        );

        if (!$checkUserPassword) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "User not found.";
            die($this->encodeJSONdata($response));
        }

        if (!password_verify($old_password, $checkUserPassword["password"])) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your old password is incorrect.";
            die($this->encodeJSONdata($response));
        }

        if (password_verify($password, $checkUserPassword["password"])) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your old password must be different from your new password.";
            die($this->encodeJSONdata($response));
        }

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `users_admin` SET `password` = :account_password WHERE `id` = :id LIMIT 1",
            array(
                "id" => $user_id,
                "account_password" => $this->hashGenerator($password),
            )
        );

        if ($result) {
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Your user account password has been successfully changed.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Something went wrong. Please try again.";
        die($this->encodeJSONdata($response));
    }
    
    public function signIn($idata = array())
    {
        /**
         * @param array $idata
         * @return string
         */

        $email = $this->filterData($idata["account_email"] ?? null);
        $password = $this->filterData($idata["account_password"] ?? null);

        if ($this->isEmpty($email)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Email is required.";
            if ($return) {
                return $response;
            }

            die($this->encodeJSONdata($response));
        }

        if ($this->isEmpty($password)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your password must no be empty.";
            if ($return) {
                return $response;
            }

            die($this->encodeJSONdata($response));
        }

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `id`, `user_id`, `is_super_admin`, `mobile_number_country_number`, `mobile_number`, `account_type`, `email_address`, `short_biography`, `profile_photo`, `user_name`, `first_name`, `last_name`, `full_name`, `gender`, `password`, `is_activated`, `is_blocked`, `country_of_residence`, (SELECT `country_code` FROM `users_logins` WHERE `user_id` = u.`id` ORDER BY `id` DESC LIMIT 1) AS `last_country_code`, (SELECT COUNT(*) FROM `user_logins_allowed_countries` WHERE `activated` = 0 AND `user_id` = u.`id`) AS `number_of_inactive_countries` FROM `users_admin` u WHERE (`email_address` = :email OR `user_name` = :email) AND `password` != '' AND `is_admin_at_envoyer` > 0 LIMIT 1",
            array(
                "email" => $email,
            ),
            true,
            true
        );

        if ($result) {
            //Check if user password match email
            if (!password_verify($password, $result["password"])) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Either your email/username or password is incorrect.";
                if ($return) {
                    return $response;
                }

                die($this->encodeJSONdata($response));
            }

            //Check if user account is activated
            if ($this->filterData($result["is_activated"]) < 1) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Your user account is not yet activated.";
                if ($return) {
                    return $response;
                }

                die($this->encodeJSONdata($response));
            }

            //Check is user account is blocked
            if ($this->filterData($result["is_blocked"]) > 0) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Your user account has been blocked. Please contact " . APPNAME . "(" . EMAIL . ") for more inquiries on this issue with your user account.";
                if ($return) {
                    return $response;
                }

                die($this->encodeJSONdata($response));
            }

            //Get IP info
            $getIPInfo = $this->getIPInfo();
            $deviceP = $this->detectDevice(true);

            //Update latest login information
            $this->runQuery(
                $this->conn,
                "UPDATE `users_admin` SET
                                `skip_location_code` = NULL,
                                `last_login_ip_address` = :last_login_ip_address,
                                `last_login_device` = :last_login_device,
                                `last_login_device_channel` = :last_login_device_channel,
                                `number_of_user_signins` = `number_of_user_signins` + 1,
                                `last_datetime_signin` = :last_datetime_signin,
                                `online_since` = :online_since,
                                `country_of_residence` = :country_of_residence,
                                `online_status` = 1
                                WHERE `id` = :user_id
                            ",
                array(
                    "last_login_ip_address" => $this->getIPaddress(),
                    "last_login_device" => $this->systemInfo()["device"] ?? null,
                    "last_login_device_channel" => $this->detectPlatform(),
                    "last_datetime_signin" => $this->getDatetime(),
                    "online_since" => $this->getDatetime(),
                    "country_of_residence" => $getIPInfo["country_code2"] ?? $result["country_of_residence"],
                    "user_id" => $result["id"],
                )
            );

            //Record new user login information
            $loginToken = $this->generateSpecialID("LGTK");
            $ulog = $this->runQuery(
                $this->conn,
                "INSERT INTO `users_logins`(
                                `user_id`,
                                `datetime`,
                                `token`,
                                `token_expiry`,
                                `device`,
                                `channel`,
                                `ip_address`,
                                `country_name`,
                                `country_code`,
                                `isp`,
                                `ip_version`
                            )
                            VALUES(
                                :user_id,
                                :datetime_added,
                                :token,
                                :token_expiry,
                                :device,
                                :channel,
                                :ip_address,
                                :country_name,
                                :country_code,
                                :isp,
                                :ip_version
                            )",
                array(
                    "user_id" => $result["id"],
                    "datetime_added" => $this->getDatetime(),
                    "token" => $loginToken,
                    "token_expiry" => $this->getDatetime("+1 month"),
                    "device" => $this->detectDevice() ?? null,
                    "channel" => $this->detectPlatform(),
                    "ip_address" => $getIPInfo["ip_address"] ?? null,
                    "country_name" => $getIPInfo["country_name"] ?? null,
                    "country_code" => $getIPInfo["country_code2"] ?? null,
                    "isp" => $getIPInfo["isp"] ?? null,
                    "ip_version" => $getIPInfo["ip_version"] ?? null,
                )
            );

            $_SESSION["admin_is_logged_in"] = "yes";
            $_SESSION["admin_full_name"] = $result["full_name"];
            $_SESSION["admin_user_name"] = $result["user_name"];
            $_SESSION["admin_profile_photo_small"] = $result["profile_photo"];
            $_SESSION["admin_id"] = $result["id"];
            $_SESSION["admin_id_encrypted"] = $this->encryptString($result["id"]);

            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Sign in successful. Redirecting you in a moment...";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Either your email/username or password is incorrect.";
        die($this->encodeJSONdata($response));

    }

    public function generateUserID($count=0, $max=50){
        if($count > 20){
            return $this->generateIDNumeric(1, $max, 8);
        }

        $user_id = "UAC".$this->generateIDNumeric(1, $max, 8);
        
        if(!$this->conn){
            $this->throwConnectionError();
        }

        $result = $this->selectSingleData($this->conn, "SELECT `user_id` FROM `users_admin` WHERE `user_id` = '{$user_id}'");
        if($result){
            return $this->generateUserID(($count + 1), ($count > 10 ? ($max + 1) : $max));
        }
        else{
            return $user_id;
        }
    }

    public function signUp($idata = array())
    {
        /**
         * @param array $idata
         * @return string
         */

        $account_type = $view_type = "business";
        $permissions = $idata["permissions"] ?? array();
        $user_id = $this->decryptString($idata["account_id"] ?? 0);
        $is_blog_writer = $this->filterData($idata["account_is_blog_writer"] ?? 0);
        $role = $this->filterData($idata["account_role"] ?? null);
        $email = $this->filterData($idata["account_email"] ?? null);
        $first_name = $this->filterData($idata["account_first_name"] ?? null);
        $last_name = $this->filterData($idata["account_last_name"] ?? null);
        $gender = $this->toLowerCase($this->filterData($idata["account_gender"]) ?? null);
        $account_mobile_number = $this->filterData($idata["account_mobile_number"] ?? null);
        $account_mobile_number_country_number = $this->filterData($idata["account_mobile_number_country_number"] ?? null);
        $password = $password_confirm = $this->randomPasswordGenerator();
        $user_name = $this->generateUserName($this->conn, $first_name);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        if(!in_array($this->toLowerCase($role), array("manager", "accountant", "support staff", "engineer", "analytics"))){
            $response["status"] = ERROR_CODE;
            $response["data"] = "Role is not recognized.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->isEmailValid($email)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your email address is invalid.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->checkCountryAndDialCodes($account_mobile_number_country_number, "dial_code")) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Country's dial code is invalid.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->isMobileNumberValid($account_mobile_number)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "The mobile number has an invalid format.";
            die($this->encodeJSONdata($response));
        }

        //Check if email is already in use by another user
        if(is_numeric($user_id) && $user_id > 0){
            if ($this->userEmailExistsWithID($this->conn, $email, $user_id)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Sorry! The email address is already in use by another user account. To continue, use another email address.";
                die($this->encodeJSONdata($response));
            }
    
            if ($this->userMobileNumberExistsWithID($this->conn, $account_mobile_number_country_number, $account_mobile_number, $user_id)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "The mobile phone number is in use by another account.";
                die($this->encodeJSONdata($response));
            }
        }
        else{
            if ($this->userEmailExists($this->conn, $email)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "Sorry! The email address is already in use by another user account. To continue, use another email address.";
                die($this->encodeJSONdata($response));
            }
    
            if ($this->userMobileNumberExists($this->conn, $account_mobile_number_country_number, $account_mobile_number, $user_id)) {
                $response["status"] = ERROR_CODE;
                $response["data"] = "The mobile phone number is in use by another account.";
                die($this->encodeJSONdata($response));
            }
        }

        if (!in_array($account_type, array("freelancer", "artisan", "business"))) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "The user account type is not recognized.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->isNameValid($first_name)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your first name is invalid. Only letters and hyphen(-) are allowed.";
            die($this->encodeJSONdata($response));
        }

        if (!$this->isNameValid($last_name)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Your last name is invalid. Only letters and hyphen(-) are allowed.";
            die($this->encodeJSONdata($response));
        }

        if (!in_array($this->toLowerCase($gender), array("male", "female"))) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "The chosen gender is invalid. Must be either male or female.";
            die($this->encodeJSONdata($response));
        }

        $willChangePasswordToken = $this->hashGenerator($email . time() . uniqid());
        $willActivateAccountToken = $this->hashGenerator($email . $password . time() . uniqid());

        $getIPInfo = $this->getIPInfo();
        $country_of_residence = $getIPInfo["country_code2"] ?? null;

        if(is_numeric($user_id) && $user_id > 0){
            $result = $this->runQuery(
                $this->conn,
                "UPDATE `users_admin` SET 
                        `envoyer_role` = :envoyer_role,
                        `first_name` = :first_name,
                        `last_name` = :last_name,
                        `full_name` = :full_name,
                        `user_name` = :user_name,
                        `gender` = :gender,
                        `email_address` = :email_address,
                        `mobile_number` = :mobile_number,
                        `mobile_number_country_number` = :mobile_number_country_number,
                        `will_change_password_token` = :will_change_password_token 
                        WHERE `id` = :user_id LIMIT 1
                ",
                array(
                    "user_id" => $user_id,
                    "envoyer_role" => $role,
                    "first_name" => $this->fixCaps($first_name),
                    "last_name" => $this->fixCaps($last_name),
                    "full_name" => $this->fixCaps($first_name . " " . $last_name),
                    "user_name" => $user_name,
                    "gender" => $gender,
                    "email_address" => $email,
                    "mobile_number" => $account_mobile_number,
                    "mobile_number_country_number" => "+".$account_mobile_number_country_number,
                    "will_change_password_token" => $willChangePasswordToken,
                )
            );
    
            if ($result) {
                $response["status"] = SUCCESS_CODE;
                $response["data"] = array(
                    "id" => $this->encryptString($user_id),
                    "action" => "replace",
                    "msg" => "Admin account has been successufully updated for " . $this->fixCaps($first_name) . ". Login credentials will be sent for " . ($gender == "male" ? "him" : "her") . " to signin to the admin dashboard."
                );
                die($this->encodeJSONdata($response));
            }
    
            $response["status"] = ERROR_CODE;
            $response["data"] = "Could not update admin account. Please try again.";
            die($this->encodeJSONdata($response));
        }
        else{
            $result = $this->runQuery(
                $this->conn,
                "INSERT INTO `users_admin`(
                        `envoyer_role`,
                        `is_admin_at_envoyer`,
                        `is_worker_at_envoyer`,
                        `can_post_portfolio`,
                        `user_id`,
                        `first_name`,
                        `last_name`,
                        `full_name`,
                        `user_name`,
                        `gender`,
                        `account_type`,
                        `view_type`,
                        `email_address`,
                        `password`,
                        `joined_via`,
                        `country_of_residence`,
                        `datetime_joined`,
                        `is_activated`,
                        `mobile_number`,
                        `mobile_number_country_number`,
                        `requires_account_verification`,
                        `will_change_password_token`
                    )
                    VALUES(
                        :envoyer_role,
                        '1',
                        '1',
                        '0',
                        :user_id,
                        :first_name,
                        :last_name,
                        :full_name,
                        :user_name,
                        :gender,
                        :account_type,
                        :view_type,
                        :email_address,
                        :user_password,
                        :joined_via,
                        :country_of_residence,
                        :datetime_joined,
                        '1',
                        :mobile_number,
                        :mobile_number_country_number,
                        :requires_account_verification,
                        :will_change_password_token
                    );
                ",
                array(
                    "envoyer_role" => $role,
                    "user_id" => $this->generateUserID(),
                    "first_name" => $this->fixCaps($first_name),
                    "last_name" => $this->fixCaps($last_name),
                    "full_name" => $this->fixCaps($first_name . " " . $last_name),
                    "user_name" => $user_name,
                    "gender" => $gender,
                    "account_type" => $account_type,
                    "view_type" => $view_type,
                    "email_address" => $email,
                    "user_password" => $this->hashGenerator($password),
                    "joined_via" => APPNAME,
                    "country_of_residence" => $country_of_residence,
                    "datetime_joined" => $this->getDatetime(),
                    "mobile_number" => $account_mobile_number,
                    "mobile_number_country_number" => "+".$account_mobile_number_country_number,
                    "requires_account_verification" => "no",
                    "will_change_password_token" => $willChangePasswordToken,
                )
            );
    
            if ($result) {
                $user_id = $idata["uid"] = $this->lastInsertID($this->conn);
                $enc_user_id = $this->encryptString($user_id);
    
                $response["status"] = SUCCESS_CODE;
                $response["data"] = array(
                    "id" => $this->encryptString($user_id),
                    "action" => "new",
                    "msg" => "Admin account has been successufully created for " . $this->fixCaps($first_name) . ". Login credentials will be sent for " . ($gender == "male" ? "him" : "her") . " to signin to the admin dashboard."
                );
                die($this->encodeJSONdata($response));
            }
    
            $response["status"] = ERROR_CODE;
            $response["data"] = "Could not create admin account. Please try again.";
            die($this->encodeJSONdata($response));
        }
    }

    public function getListOfAdministrators($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"] ?? null) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $admin_account_role = $this->filterData($idata["admin_account_role"] ?? null);
        $admin_account_create_manage_admins = $this->filterData($idata["admin_account_create_manage_admins"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY usr.`id` ASC",
            "id_desc" => " ORDER BY usr.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
        );

        if (!in_array(strtolower($sortBy), array_keys($sortList), true)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Sort is not recognized.";
            return $this->encodeJSONdata($response);
        }

        if (!is_int($pageNum)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Page number is not valid.";
            return $this->encodeJSONdata($response);
        }

        if (!is_int($resultPerPage)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page is not valid.";
            return $this->encodeJSONdata($response);
        }

        if ($resultPerPage > 100) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page must not exceed 100.";
            return $this->encodeJSONdata($response);
        }

        $subQuery = null;

        switch ($viewType) {
            case "blocked":
                $subQuery .= " AND (`is_blocked` > 0) ";
                break;
            case "unblocked":
                $subQuery .= " AND (`is_blocked` < 1) ";
                break;
        }

        switch ($filterType) {
            case "custom_date":
                if (!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)) {
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if ($cdateStart <= $cdateEnd) {
                        $subQuery .= " AND (DATE(usr.`datetime_joined`) BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(usr.`datetime_joined`) = '" . date("Y-m-d") . "') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(usr.`datetime_joined`) = '" . date("Y-m-d", strtotime("-1 day")) . "') ";
                break;
        }

        if(!$this->isEmpty($admin_account_role)){
            $subQuery .= " AND (usr.`envoyer_role` = '{$admin_account_role}') ";
        }

        switch($admin_account_create_manage_admins){
            case "yes":
                $subQuery .= " AND (`id` IN(SELECT `user_id` FROM `users_admins_permissions` WHERE (`permission_title` = 'create_administrators' AND `permission_value` > 0))) ";
                break;
            case "no":
                $subQuery .= " AND (`id` NOT IN(SELECT `user_id` FROM `users_admins_permissions` WHERE (`permission_title` = 'create_administrators' AND `permission_value` > 0))) ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR usr.`date_of_birth` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= " AND (`is_admin_at_envoyer` > 0) ";
        $subQuery .= " AND (`id` NOT IN(SELECT `user_id` FROM `users_admins_permissions` WHERE (`permission_title` = 'create_administrators' AND `permission_value` > 0))) ";
        
        $query = "SELECT usr.`envoyer_role`, usr.`contact_entry_warnings`, usr.`word_usage_warnings`, usr.`id`, usr.`is_worker_at_envoyer`, usr.`is_blog_writer`, usr.`user_id`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`country_of_residence`, usr.`datetime_joined`, usr.`account_type`, usr.`profile_photo`, usr.`is_blocked`, usr.`is_activated`, usr.`profile_photo_valid`, usr.`user_name`, usr.`email_address`, usr.`mobile_number`, usr.`mobile_number_country_number` FROM `users_admin` usr WHERE usr.`id` IS NOT NULL AND `is_super_admin` < 1 {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(usr.`id`) FROM `users_admin` usr WHERE usr.`id` IS NOT NULL AND `is_super_admin` < 1 {$subQuery}");

        $pagerows = $resultPerPage;
        $totalRows_rsodest = $query_count;

        $total_pages = $lastpage = ceil($totalRows_rsodest / $pagerows);
        $page = $pageNum;

        if (!(isset($pageNum))) {
            $pageNum = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        } else if ($pageNum > $total_pages) {
            $pageNum = $total_pages;
        }

        $start_row = (($pageNum - 1) * $pagerows) + 1;
        $end_row = $start_row + $pagerows;

        if ($end_row > $totalRows_rsodest) {
            $end_row = $totalRows_rsodest;
        }

        $result_data = $this->selectMultipleData($this->conn, $query . $sortList[$sortBy] . ' LIMIT ' . abs(($pageNum - 1) * $pagerows) . ',' . $pagerows);
        if ($totalRows_rsodest > 0) {
            $result = array();
            foreach ($result_data as $rdata) {
                $result[] = $rdata;
            }
        } else {
            $result = array();
            $start_row = 0;
        }

        $pagination = array();
        $range = 2;
        $r2 = 2;
        $prevPage = $nextPage = 1;

        if ($page != 1) {
            $prev = $page - 1;
            $prevPage = $prev;
            $pagination[] = array(
                "text" => 'previous',
                "page" => $prev,
                "active" => ($prev == $pageNum) ? "yes" : "no",
            );
        }

        for ($x = ($page - $range); $x < ($page + $range) + $r2; $x++) {
            if ($x > 0 && $x <= $lastpage) {
                if ($x == $page) {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                } else {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                }
            }
        }

        if ($page != $lastpage) {
            $next = $page + 1;
            $nextPage = $next;
            $pagination[] = array(
                "text" => "next",
                "page" => $next,
                "active" => ($next == $pageNum) ? "yes" : "no",
            );
        }

        if ($lastpage == 1 || $lastpage == 0) {
            $pagination = array();
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "data" => $result,
            "pagination" => $pagination,
            "current_page" => $pageNum,
            "next_page" => $nextPage,
            "prev_page" => $prevPage,
            "start_row" => $start_row,
            "end_row" => $end_row,
            "total_records" => $totalRows_rsodest,
            "result_per_page" => $resultPerPage,
        );

        return $this->encodeJSONdata($response);
    }
    
    public function getListOfUsers($idata = array(), $uid = null, $return = false)
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"] ?? null) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $user_account_activation_status = $this->filterData($idata["user_account_activation_status"] ?? null);
        $user_account_block_status = $this->filterData($idata["user_account_block_status"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY usr.`id` ASC",
            "id_desc" => " ORDER BY usr.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`firstname` ASC",
            "first_name_desc" => " ORDER BY usr.`firstname` DESC",
            "last_name_asc" => " ORDER BY usr.`surname` ASC",
            "last_name_desc" => " ORDER BY usr.`surname` DESC",
            "email_asc" => " ORDER BY usr.`email` ASC",
            "email_desc" => " ORDER BY usr.`email` DESC",
        );

        if (!in_array(strtolower($sortBy), array_keys($sortList), true)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Sort is not recognized.";
            if($return) return array();
            return $this->encodeJSONdata($response);
        }

        if (!is_int($pageNum)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Page number is not valid.";
            if($return) return array();
            return $this->encodeJSONdata($response);
        }

        if (!is_int($resultPerPage)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page is not valid.";
            if($return) return array();
            return $this->encodeJSONdata($response);
        }

        if ($resultPerPage > 100) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page must not exceed 100.";
            if($return) return array();
            return $this->encodeJSONdata($response);
        }

        $subQuery = null;

        if(!$this->isEmpty($user_account_activation_status)){
            switch($user_account_activation_status){
                case 1:
                    $subQuery .= " AND (`is_activated` = 1) ";
                    break;
                case 0:
                    $subQuery .= " AND (`is_activated` = 0) ";
                    break;
            }
        }
        
        if(!$this->isEmpty($user_account_block_status)){
            switch($user_account_block_status){
                case 1:
                    $subQuery .= " AND (`is_blocked` = 1) ";
                    break;
                case 0:
                    $subQuery .= " AND (`is_blocked` = 0) ";
                    break;
            }
        }

        switch ($viewType) {
            case "individual":
                $subQuery .= " AND (usr.`user_type` = 1) ";
                break;
            case "business":
                $subQuery .= " AND (usr.`user_type` = 2) ";
                break;
            case "driver_rider":
                $subQuery .= " AND (usr.`user_type` = 3) ";
                break;
            case "freight_forwarder":
                $subQuery .= " AND (usr.`user_type` = 4) ";
                break;
            case "clearing_agent":
                $subQuery .= " AND (usr.`user_type` = 5) ";
                break;
        }

        switch ($filterType) {
            case "custom_date":
                if (!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)) {
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if ($cdateStart <= $cdateEnd) {
                        $subQuery .= " AND (DATE(usr.`datetime_joined`) BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(usr.`datetime_joined`) = '" . date("Y-m-d") . "') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(usr.`datetime_joined`) = '" . date("Y-m-d", strtotime("-1 day")) . "') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`firstname` LIKE '%{$searchQuery}%' OR usr.`surname` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`email` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR usr.`whatsapp_number` LIKE '%{$searchQuery}%' OR usr.`date_of_birth` LIKE '%{$searchQuery}%' OR usr.`username` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($uid) ? " AND (usr.`id` = '{$uid}')" : null;
        
        $query = "SELECT usr.`id`, usr.`user_id`, usr.`firstname`, usr.`surname`, usr.`full_name`, usr.`datetime_joined`, usr.`user_type`, usr.`photo`, usr.`is_blocked`, usr.`is_activated`, usr.`username`, usr.`email`, usr.`mobile_number`, usr.`is_verified` FROM `users` usr WHERE usr.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(usr.`id`) FROM `users` usr WHERE usr.`id` IS NOT NULL {$subQuery}");
        
        $pagerows = $resultPerPage;
        $totalRows_rsodest = $query_count;

        $total_pages = $lastpage = ceil($totalRows_rsodest / $pagerows);
        $page = $pageNum;

        if (!(isset($pageNum))) {
            $pageNum = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        } else if ($pageNum > $total_pages) {
            $pageNum = $total_pages;
        }

        $start_row = (($pageNum - 1) * $pagerows) + 1;
        $end_row = $start_row + $pagerows;

        if ($end_row > $totalRows_rsodest) {
            $end_row = $totalRows_rsodest;
        }

        $result_data = $this->selectMultipleData($this->conn, $query . $sortList[$sortBy] . ' LIMIT ' . abs(($pageNum - 1) * $pagerows) . ',' . $pagerows);
        if ($totalRows_rsodest > 0) {
            $result = array();
            foreach ($result_data as $rdata) {
                $result[] = $rdata;
            }
        } else {
            $result = array();
            $start_row = 0;
        }

        $pagination = array();
        $range = 2;
        $r2 = 2;
        $prevPage = $nextPage = 1;

        if ($page != 1) {
            $prev = $page - 1;
            $prevPage = $prev;
            $pagination[] = array(
                "text" => 'previous',
                "page" => $prev,
                "active" => ($prev == $pageNum) ? "yes" : "no",
            );
        }

        for ($x = ($page - $range); $x < ($page + $range) + $r2; $x++) {
            if ($x > 0 && $x <= $lastpage) {
                if ($x == $page) {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                } else {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                }
            }
        }

        if ($page != $lastpage) {
            $next = $page + 1;
            $nextPage = $next;
            $pagination[] = array(
                "text" => "next",
                "page" => $next,
                "active" => ($next == $pageNum) ? "yes" : "no",
            );
        }

        if ($lastpage == 1 || $lastpage == 0) {
            $pagination = array();
        }

        if($return) return $result;

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "data" => $result,
            "pagination" => $pagination,
            "current_page" => $pageNum,
            "next_page" => $nextPage,
            "prev_page" => $prevPage,
            "start_row" => $start_row,
            "end_row" => $end_row,
            "total_records" => $totalRows_rsodest,
            "result_per_page" => $resultPerPage,
        );

        return $this->encodeJSONdata($response);
    }

    public function getListOfVerificationRequests($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"] ?? null) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $user_account_activation_status = $this->filterData($idata["user_account_activation_status"] ?? null);
        $user_account_block_status = $this->filterData($idata["user_account_block_status"] ?? null);
        $user_account_country = $this->filterData($idata["user_account_country"] ?? null);
        $user_account_verification_status = $this->filterData($idata["user_account_verification_status"] ?? null);
        $user_account_profile_photo_flag_status = $this->filterData($idata["user_account_profile_photo_flag_status"] ?? null);
        $user_account_work_status = $this->filterData($idata["user_account_work_status"] ?? null);
        $user_has_purchased_a_service = $this->filterData($idata["user_has_purchased_a_service"] ?? null);
        $user_has_posted_a_job = $this->filterData($idata["user_has_posted_a_job"] ?? null);
        $user_has_hired_a_freelancer = $this->filterData($idata["user_has_hired_a_freelancer"] ?? null);
        $user_has_hired_an_artisan = $this->filterData($idata["user_has_hired_an_artisan"] ?? null);
        $user_account_blog_status = $this->filterData($idata["user_account_blog_status"] ?? null);
        $user_account_gender = $this->filterData($idata["user_account_gender"] ?? null);
        $user_account_categtory = $this->filterData($idata["user_account_categtory"] ?? null);
        $user_account_date_of_birth = $this->filterData($idata["user_account_date_of_birth"] ?? null);
        $user_account_min_rate = $this->filterData($idata["user_account_min_rate"] ?? 0);
        $user_account_max_rate = $this->filterData($idata["user_account_max_rate"] ?? 0);

        $sortList = array(
            "id_asc" => " ORDER BY usrv.`id` ASC",
            "id_desc" => " ORDER BY usrv.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
        );

        if (!in_array(strtolower($sortBy), array_keys($sortList), true)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Sort is not recognized.";
            return $this->encodeJSONdata($response);
        }

        if (!is_int($pageNum)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Page number is not valid.";
            return $this->encodeJSONdata($response);
        }

        if (!is_int($resultPerPage)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page is not valid.";
            return $this->encodeJSONdata($response);
        }

        if ($resultPerPage > 100) {
            $response["status"] = ERROR_CODE;
            $response["data"] = "Result per page must not exceed 100.";
            return $this->encodeJSONdata($response);
        }

        $subQuery = null;

        switch ($viewType) {
            case "approved_verification":
                $subQuery .= " AND (usrv.`status` IN('approved')) ";
                break;
            case "pending_verification":
                $subQuery .= " AND (usrv.`status` IN('pending')) ";
                break;
        }

        switch ($filterType) {
            case "custom_date":
                if (!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)) {
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if ($cdateStart <= $cdateEnd) {
                        $subQuery .= " AND (DATE(usrv.`datetime_joined`) BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(usrv.`datetime_joined`) = '" . date("Y-m-d") . "') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(usrv.`datetime_joined`) = '" . date("Y-m-d", strtotime("-1 day")) . "') ";
                break;
        }

        if (is_numeric($user_account_activation_status)) {
            $subQuery .= " AND (`is_activated` = '" . $user_account_activation_status . "') ";
        }

        if (is_numeric($user_account_block_status)) {
            $subQuery .= " AND (`is_blocked` = '" . $user_account_block_status . "') ";
        }

        if (!$this->isEmpty($user_account_country)) {
            $subQuery .= " AND (`country_of_residence` = '" . $user_account_country . "') ";
        }

        if (is_numeric($user_account_verification_status)) {
            $subQuery .= " AND (`is_verified` = '" . $user_account_verification_status . "') ";
        }

        if (is_numeric($user_account_profile_photo_flag_status)) {
            $subQuery .= " AND (`profile_photo_valid` = '" . $user_account_profile_photo_flag_status . "') ";
        }

        if (is_numeric($user_account_work_status)) {
            $subQuery .= " AND (`is_worker_at_envoyer` = '" . $user_account_work_status . "') ";
        }

        if (!$this->isEmpty($user_has_purchased_a_service) && $user_has_purchased_a_service > 0) {
            $subQuery .= " AND ((SELECT COUNT(*) FROM `market_items_orders` WHERE `user_id` = usr.`id` AND `payment_status` = 'paid') > 0) ";
        }

        if (!$this->isEmpty($user_has_posted_a_job) && $user_has_posted_a_job > 0) {
            $subQuery .= " AND ((SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL)) > 0) ";
        }

        if (!$this->isEmpty($user_has_hired_a_freelancer) && $user_has_hired_a_freelancer > 0) {
            $subQuery .= " AND ((SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL) AND `project_type` = 'freelance') > 0) ";
        }

        if (!$this->isEmpty($user_has_hired_an_artisan) && $user_has_hired_an_artisan > 0) {
            $subQuery .= " AND ((SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL) AND `project_type` = 'artisan') > 0) ";
        }

        if (is_numeric($user_account_blog_status)) {
            $subQuery .= " AND (`is_blog_writer` = '" . $user_account_blog_status . "') ";
        }

        if (!$this->isEmpty($user_account_gender)) {
            $subQuery .= " AND (`gender` = '" . $user_account_gender . "') ";
        }

        if (!$this->isEmpty($user_account_categtory)) {
            $subQuery .= " AND (`user_category` = '" . $user_account_categtory . "') ";
        }

        if (!$this->isEmpty($user_account_date_of_birth)) {
            $subQuery .= " AND (`date_of_birth` = '" . $user_account_date_of_birth . "') ";
        }

        if ($user_account_min_rate >= 0 && $user_account_max_rate > 0 && $user_account_max_rate > $user_account_min_rate && is_numeric($user_account_min_rate) && is_numeric($user_account_max_rate)) {
            $subQuery .= " AND (`charge_per_hour` BETWEEN " . $user_account_min_rate . " AND " . $user_account_max_rate . ") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR usr.`date_of_birth` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usrv.`verification_document_code` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT (SELECT `verification_document_attachment` FROM `users_verifications` WHERE `user_id` = usr.`id` LIMIT 1) AS `verification_document_attachment`, (SELECT COUNT(*) FROM `market_items_orders` WHERE `user_id` = usr.`id` AND `payment_status` = 'paid') AS `services_purchased`, (SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL)) AS `jobs_posted`, (SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL) AND `project_type` = 'artisan') AS `artisan_jobs_posted`, (SELECT COUNT(*) FROM `projects` WHERE `user_id` = usr.`id` AND (`service_id_type` = '' OR `service_id_type` IS NULL) AND `project_type` = 'freelance') AS `freelance_jobs_posted`, usr.`id` AS `uid`, usr.`is_verified`, usr.`is_worker_at_envoyer`, usr.`is_blog_writer`, usr.`user_id`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`country_of_residence`, usr.`datetime_joined`, usr.`account_type`, usr.`profile_photo`, usr.`is_blocked`, usr.`is_activated`, usr.`profile_photo_valid`, usr.`user_name`, usr.`email_address`, usr.`mobile_number`, usrv.`id` AS `verification_id`, usrv.`verification_document_name`, usrv.`verification_document_code`, usrv.`verification_description`, usrv.`verification_document_attachment`, usrv.`datetime` AS `verification_datetime`, usrv.`status` AS `verification_status`  FROM `users_verifications` usrv LEFT JOIN `users_admin` usr ON usrv.`user_id` = usr.`id` WHERE usr.`id` IS NOT NULL AND usrv.`user_id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(usrv.`id`) FROM `users_verifications` usrv LEFT JOIN `users_admin` usr ON usrv.`user_id` = usr.`id` WHERE usr.`id` IS NOT NULL AND usrv.`user_id` IS NOT NULL {$subQuery}");

        $pagerows = $resultPerPage;
        $totalRows_rsodest = $query_count;

        $total_pages = $lastpage = ceil($totalRows_rsodest / $pagerows);
        $page = $pageNum;

        if (!(isset($pageNum))) {
            $pageNum = 1;
        }
        if ($pageNum < 1) {
            $pageNum = 1;
        } else if ($pageNum > $total_pages) {
            $pageNum = $total_pages;
        }

        $start_row = (($pageNum - 1) * $pagerows) + 1;
        $end_row = $start_row + $pagerows;

        if ($end_row > $totalRows_rsodest) {
            $end_row = $totalRows_rsodest;
        }

        $result_data = $this->selectMultipleData($this->conn, $query . $sortList[$sortBy] . ' LIMIT ' . abs(($pageNum - 1) * $pagerows) . ',' . $pagerows);
        if ($totalRows_rsodest > 0) {
            $result = array();
            foreach ($result_data as $rdata) {
                $rdata["verification_document_attachment"] = !$this->isEmpty($rdata["verification_document_attachment"]) ? MEDIA_DOMAIN."media/img/verifications/".$rdata["verification_document_attachment"] : null;
                $result[] = $rdata;
            }
        } else {
            $result = array();
            $start_row = 0;
        }

        $pagination = array();
        $range = 2;
        $r2 = 2;
        $prevPage = $nextPage = 1;

        if ($page != 1) {
            $prev = $page - 1;
            $prevPage = $prev;
            $pagination[] = array(
                "text" => 'previous',
                "page" => $prev,
                "active" => ($prev == $pageNum) ? "yes" : "no",
            );
        }

        for ($x = ($page - $range); $x < ($page + $range) + $r2; $x++) {
            if ($x > 0 && $x <= $lastpage) {
                if ($x == $page) {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                } else {
                    $pagination[] = array(
                        "text" => $x,
                        "page" => $x,
                        "active" => ($x == $pageNum) ? "yes" : "no",
                    );
                }
            }
        }

        if ($page != $lastpage) {
            $next = $page + 1;
            $nextPage = $next;
            $pagination[] = array(
                "text" => "next",
                "page" => $next,
                "active" => ($next == $pageNum) ? "yes" : "no",
            );
        }

        if ($lastpage == 1 || $lastpage == 0) {
            $pagination = array();
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "data" => $result,
            "pagination" => $pagination,
            "current_page" => $pageNum,
            "next_page" => $nextPage,
            "prev_page" => $prevPage,
            "start_row" => $start_row,
            "end_row" => $end_row,
            "total_records" => $totalRows_rsodest,
            "result_per_page" => $resultPerPage,
        );

        return $this->encodeJSONdata($response);
    }

    public function isUserBlocked($idata = array(), $return = false)
    {
        /**
         * @param Array $idata
         * @return Void
        */

        $blockMessages = array(
            "all_messages" => "You have been temporarily blocked from sending messages to any user.",
            "message" => "You have been temporarily blocked from sending messages to this user.",
            "job_post" => "You are temporarily blocked from posting jobs.",
            "service_sale" => "You are temporarily not allowed to sell services.",
            "job_application" => "You are temporarily blocked from applying to projects.",
        );

        $blockPermanentMessages = array(
            "all_messages" => "You have been permanently blocked from sending messages to any user.",
            "message" => "You have been permanently blocked from sending messages to this user.",
            "job_post" => "You are permanently blocked from posting jobs.",
            "service_sale" => "You are permanently not allowed to sell services.",
            "job_application" => "You are permanently blocked from applying to projects.",
        );
        
        $allowedBlockTypes = array("all_messages", "message", "job_post", "service_sale", "job_application");

        $user_id = $this->filterData($idata["user_id"] ?? null);
        $related_user_id = $this->filterData($idata["related_user_id"] ?? null);
        $block_type = $this->toLowerCase($this->filterData($idata["block_type"] ?? null));

        if (!in_array($block_type, $allowedBlockTypes)) {
            $response["status"] = ERROR_CODE;
            $response["data"] = $blockPermanentMessages[$block_type];
            if($return) return false;
            return $response;
        }

        if (!$this->conn) {
            $response["status"] = ERROR_CODE;
            $response["data"] = $blockPermanentMessages[$block_type];
            if($return) return false;
            return $response;
        }

        switch ($block_type) {
            case "job_post":
            case "service_sale":
            case "job_application":
            case "all_messages":
                $blockExists = $this->runQuery(
                    $this->conn,
                    "SELECT `block_priority`, `block_date` FROM `users_blocks` WHERE `block_type` = :block_type AND `user_id` = :user_id LIMIT 1",
                    array(
                        "block_type" => $block_type,
                        "user_id" => $user_id,
                    ),
                    true,
                    true
                );

                if ($blockExists) {
                    if($blockExists["block_priority"] === "permanent"){
                        $response["status"] = ERROR_CODE;
                        $response["data"] = $blockPermanentMessages[$block_type];
                        if($return) return false;
                        return $response;
                    }
                    if($blockExists["block_priority"] === "temporary"){
                        $cdate = strtotime("today") * 1000;
                        $bdate = strtotime($blockExists["block_date"]) * 1000;
                        if ($bdate >= $cdate) {
                            $response["status"] = ERROR_CODE;
                            $response["data"] = $blockMessages[$block_type];
                            if($return) return false;
                            return $response;
                        }
                    }
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = null;
                    if($return) return true;
                    return $response;
                } else {
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = null;
                    if($return) return true;
                    return $response;
                }
                break;
            case "message":
                $blockExists = $this->runQuery(
                    $this->conn,
                    "SELECT `block_priority`, `block_date` FROM `users_blocks` WHERE `block_type` = :block_type AND ((`user_id` = :user_id AND `related_user_id` = :related_user_id) OR (`user_id` = :related_user_id AND `related_user_id` = :user_id)) LIMIT 1",
                    array(
                        "block_type" => $block_type,
                        "user_id" => $user_id,
                        "related_user_id" => $related_user_id,
                    ),
                    true,
                    true
                );

                if ($blockExists) {
                    if($blockExists["block_priority"] === "permanent"){
                        $response["status"] = ERROR_CODE;
                        $response["data"] = $blockPermanentMessages[$block_type];
                        if($return) return false;
                        return $response;
                    }
                    if($blockExists["block_priority"] === "temporary"){
                        $cdate = strtotime("today") * 1000;
                        $bdate = strtotime($blockExists["block_date"]) * 1000;
                        if ($bdate >= $cdate) {
                            $response["status"] = ERROR_CODE;
                            $response["data"] = $blockMessages[$block_type];
                            if($return) return false;
                            return $response;
                        }
                    }
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = null;
                    if($return) return true;
                    return $response;
                } else {
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = null;
                    if($return) return true;
                    return $response;
                }
                break;
        }
    }

    public function activateDeactivateUserAccount ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $user_id = $this->decryptString($idata["user_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `is_activated` FROM `users` WHERE `id` = :user_id;",
            array(
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            switch ($this->filterData($result["is_activated"])) {
                case 1:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_activated` = 0 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully deactivated.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to deactivate user account.";
                    die($this->encodeJSONdata($response));
                    break;
                default:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_activated` = 1 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully activated.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to activate user account.";
                    die($this->encodeJSONdata($response));
            }
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "User not found.";
        die($this->encodeJSONdata($response));
    }

    public function blockUnblockUserAccount ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */
        
        $user_id = $this->decryptString($idata["user_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `is_blocked` FROM `users` WHERE `id` = :user_id;",
            array(
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            switch ($this->filterData($result["is_blocked"])) {
                case 1:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_blocked` = 0 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully unblocked.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to unblock user account.";
                    die($this->encodeJSONdata($response));
                    break;
                default:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_blocked` = 1 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully blocked.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to block user account.";
                    die($this->encodeJSONdata($response));
            }
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "User not found.";
        die($this->encodeJSONdata($response));
    }

    public function verifyUnverifyUserAccount ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */
        
        $user_id = $this->decryptString($idata["user_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `is_verified` FROM `users` WHERE `id` = :user_id;",
            array(
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            switch ($this->filterData($result["is_verified"])) {
                case 1:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_verified` = 0 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully unverified.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to unverify user account.";
                    die($this->encodeJSONdata($response));
                    break;
                default:
                    $query = $this->runQuery(
                        $this->conn,
                        "UPDATE `users` SET `is_verified` = 1 WHERE `id` = :user_id;",
                        array(
                            "user_id" => $user_id,
                        )
                    );

                    if($query){
                        $response["status"] = SUCCESS_CODE;
                        $response["data"] = "User account has been successfully verified.";
                        die($this->encodeJSONdata($response));
                    }

                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Failed to verify user account.";
                    die($this->encodeJSONdata($response));
            }
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "User not found.";
        die($this->encodeJSONdata($response));
    }

    public function deleteUserAccount ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $user_id = $this->decryptString($idata["user_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `photo` FROM `users` WHERE `id` = :user_id;",
            array(
                "user_id" => $user_id,
            ),
            true,
            true
        );

        if ($result) {
            //Delete the photo from here. May be connected to APIs
            $this->deleteFile($result["photo"]);

            //Delete user account   
            $deleteQuery = $this->runQuery(
                $this->conn,
                "DELETE FROM `users` WHERE `id` = :user_id;",
                array(
                    "user_id" => $user_id,
                )
            );

            if ($deleteQuery) {
                $response["status"] = SUCCESS_CODE;
                $response["data"] = "User account has been deleted successfully.";
                die($this->encodeJSONdata($response));
            }

            $response["status"] = ERROR_CODE;
            $response["data"] = "Failed to delete user account.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "User account not found.";
        die($this->encodeJSONdata($response));
    }

}
