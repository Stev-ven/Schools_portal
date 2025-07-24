<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Mailer extends General
{

    public $mail_body = null;

    public $mail_type = null;

    public $bcc_list = array();

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function mailBody($mailLBody = array())
    {
        $email = $mailLBody["email"] ?? null;
        $subject = $mailLBody["subject"] ?? null;
        $message = $mailLBody["body"] ?? null;

        return '
                    <!DOCTYPE html>
                    <html lang="en-UK">
                        <head>
                            <title>' . $subject . '</title>
                            <meta charset="utf-8"/>
                            <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
                        </head>
                        <body style="padding:0;margin:0;background-color:#fff;overflow-x:hidden;font-family:segoe ui,Arial;box-sizing:border-box;">
                            <div style="width:100%;padding:15px 15px;box-sizing:border-box;">
                                <div style="text-align: center;"><img rel="nofollow" style"max-width: 350px; width: 400px;" src="' . APP_DEFAULT_LOGO . '"/></div>
                                <br />
                                <h2 style="box-sizing:border-box;margin-left:0;margin-right:0;box-sizing:border-box;text-align: center; color: #1c395e;">' . $subject . '</h2>
                            </div>
                            <div style="width:100%;padding:15px 15px;background-color:rgb(255,255,255);box-sizing:border-box;">
                                <p style="color: #333; line-height:24px;box-sizing:border-box;font-size:18px;text-align:left;max-width: 700px;margin: auto;margin-bottom:35px;line-height:30px;">' . $message . '</p>
                                <table style="max-width: 700px;margin: 50px auto">
                                    <tr>
                                        <td style="padding: 40px 20px; border-top: 1px solid #dbdbdb; text-align: center;" colspan="2">
                                            <p style="max-width: 500px; margin:15px auto;word-wrap:break-word;color:#4C4C4C;font-weight:400;font-size:13px;line-height:1.5;">
                                                <a style="cursor: pointer; text-decoration: none; color: #4C4C4C;">
                                                    <img src="' . APP_DEFAULT_LOGO_COA . '" style="max-width: 130px; margin-top: -10px; vertical-align: middle; margin-top: -8px;" alt="">
                                                </a>
                                            </p>
                                            <p style="max-width: 500px; margin:15px auto;word-wrap:break-word;color:#4C4C4C;font-weight:400;font-size:13px;line-height:1.5;">You received this email from ' . OFFICIAL_APPNAME . ' (' . EMAIL_REMOTE . ').</p>
                                            <p style="max-width: 500px; margin:15px auto;word-wrap:break-word;color:#4C4C4C;font-weight:400;font-size:13px;line-height:1.5;">This email was sent to <b>' . $email . '</b>.</p>
                                            <p style="max-width: 500px; margin:15px auto;word-wrap:break-word;color:#4C4C4C;font-weight:400;font-size:13px;line-height:1.5;">© <script>document.write(new Date().getFullYear());</script> Ghana.gov - National Schools Inspectorate Authority. Private Mail Bag 18, Ministries Post Office, Armeda St. Yooyi Lane, Roman Ridge, Accra. [ GA-089-1361 ]. Official email: info@nasia.gov.gh. Call line: +233-302-907-589</p>
                                            <p>
                                                <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.moe.gov.gh/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">Ministry of Education</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                    <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.ges.gov.gh/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">Ghana Education Service</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                    <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.moe.gov.gh/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">About NaSIA</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                    <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.nasia.gov.gh/contact/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">Contact Us</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                    <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.nasia.gov.gh/faq/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">FAQs</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                    <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.moe.gov.gh/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">The Executive Director</span></a>
                                                &nbsp;
                                                <span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;">|</span>
                                                &nbsp;
                                                <a rel="nofollow noopener noreferrer" target="_blank" href="https://www.ntc.gov.gh/" style="color:#75787D;font-weight:normal;text-decoration:underline;"><span style="color:#75787D;font-family:Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:1.5;text-decoration:underline;">National Teaching Council</span></a>
                                            </p>
                                            <p style="max-width: 500px; margin:15px auto;word-wrap:break-word;color:#4C4C4C;font-weight:400;font-size:13px;line-height:1.5;">
                                                <a href="https://web.facebook.com/NaSIAgh"><img style="max-width: 40px; margin: 5px 10px;" src="./media/img/icons/mail_facebook.jpg" alt=""/></a>
                                                <a href="https://twitter.com/nasiagh"><img style="max-width: 40px; margin: 5px 10px;" src="./media/img/icons/mail_twitter.jpg" alt=""/></a>
                                                <a href="https://www.instagram.com/nasia_ghana/"><img style="max-width: 40px; margin: 5px 10px;" src="./media/img/icons/mail_instagram.jpg" alt=""/></a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </body>
                    </html>
                ';
    }

    public function sendEMail($mailLBody = array())
    {
        $email = $mailLBody["email"] ?? null;
        $subject = $mailLBody["subject"] ?? null;
        $message = $mailLBody["body"] ?? null;
        $attachment = $mailLBody["attachment"] ?? null;
        $returnResponse = $mailLBody["response"] ?? false;

        if ($returnResponse) {
            if (empty($email)) {
                $response["status"] = ERROR_CODE;
                $response["statusText"] = "The email is required.";
                return $response;
            }
        } else {
            if (empty($email)) {
                return false;
            }

        }

        require_once ABSOLUTE_PROJECT_ROOT_DIRECTORY . "/app/mvc/mail/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->isSMTP();
        $mail->Host = MAILER[0];
        $mail->Port = MAILER[1];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'STARTTLS';
        $mail->SMTPAutoTLS = true;
        $mail->Username = MAILER[2];
        $mail->Password = MAILER[3];
        $mail->Subject = $subject;
        $mail->setFrom(MAILER[2], APPNAME);
        $mail->addReplyTo(MAILER[2], APPNAME);
        $mail->addAddress($email, $email);
        $mail->msgHTML($this->mailBody($mailLBody), dirname(__FILE__));

        if (count($this->bcc_list) > 0) {
            foreach ($this->bcc_list as $bcc) {
                $mail->addBCC($bcc);
            }
        }

        $emailLog = $this->executePreparedDataBindValue(
            $this->conn,
            "INSERT INTO `email_logs`(
                    `email`,
                    `bcc_list`,
                    `subject`,
                    `message`,
                    `length_of_characters`,
                    `datetime_logged`
                ) VALUES(
                    :email,
                    :bcc_list,
                    :subject,
                    :message,
                    :length_of_characters,
                    :datetime_logged
                )",
            array(
                "email" => array($email, self::PDOSTR),
                "bcc_list" => array(implode(", ", ($this->bcc_list ?? array())), self::PDOSTR),
                "subject" => array($subject, self::PDOSTR),
                "message" => array($message, self::PDOSTR),
                "length_of_characters" => array(strlen($message), self::PDOSTR),
                "datetime_logged" => array($this->getDatetime(), self::PDOSTR),
            )
        );

        if ($returnResponse) {
            $emailLogId = $this->lastInsertID($this->conn);

            if (!$mail->send()) {
                $updateEmailLog = $this->executePreparedDataBindValue(
                    $this->conn,
                    "UPDATE `email_logs` SET
                        `fails` = `fails` + 1
                        WHERE `id` = :id",
                    array(
                        "id" => array($emailLogId, self::PDOINT),
                    )
                );

                $response["status"] = ERROR_CODE;
                $response["statusText"] = "Failed to send email.";
                return $response;
            } else {
                $updateEmailLog = $this->executePreparedDataBindValue(
                    $this->conn,
                    "UPDATE `email_logs` SET
                        `datetime_sent` = :datetime_sent,
                        `is_sent` = 1
                        WHERE `id` = :id",
                    array(
                        "id" => array($emailLogId, self::PDOINT),
                        "datetime_sent" => array($this->getDatetime(), self::PDOSTR),
                    )
                );

                $response["status"] = SUCCESS_CODE;
                $response["statusText"] = "The email has been successfully sent.";
                return $response;
            }
        } else {
            $emailLogId = $this->lastInsertID($this->conn);

            if (!$mail->send()) {
                $updateEmailLog = $this->executePreparedDataBindValue(
                    $this->conn,
                    "UPDATE `email_logs` SET
                        `fails` = `fails` + 1
                        WHERE `id` = :id",
                    array(
                        "id" => array($emailLogId, self::PDOINT),
                    )
                );
                return false;
            }

            $updateEmailLog = $this->executePreparedDataBindValue(
                $this->conn,
                "UPDATE `email_logs` SET
                    `datetime_sent` = :datetime_sent,
                    `is_sent` = 1
                    WHERE `id` = :id",
                array(
                    "id" => array($emailLogId, self::PDOINT),
                    "datetime_sent" => array($this->getDatetime(), self::PDOSTR),
                )
            );
            return true;
        }
    }

}
