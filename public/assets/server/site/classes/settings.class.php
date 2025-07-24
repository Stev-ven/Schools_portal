<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Settings extends General
{

    protected $bg = "bg/";
    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getSettingValue($setting_key = null)
    {
        /**
         * @param Array $idata
         * @return String
         */

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        //Check if setting exists
        $result = $this->runQuery(
            $this->conn,
            "SELECT `setting_value` FROM `admin_dashboard_settings` WHERE `setting_key` = :setting_key LIMIT 1",
            array(
                "setting_key" => $setting_key
            ),
            true,
            true
        );

        if($result){
            switch($setting_key){
                case "admin_dahsboard_login_page_background_photo":
                    return IMAGE_PATH_URL.$this->bg.$result["setting_value"];
                    break;
                default:
                    return $result["setting_value"];
            }
        } 

        switch($setting_key){
            case "admin_dahsboard_login_page_background_photo":
                return IMAGE_PATH_URL.$this->bg."about-banner.jpg";
                break;
            default:
                return $result["setting_value"];
        }
        
    }

    public function uploadAdminBackgroundPhoto($idata = array())
    {
        /**
         * @param Array $idata
         * @return String
         */

        $admin_id = $this->decryptString($idata["admin_id"] ?? null);
        $filename = $this->filterData($idata["filename"] ?? null);
        $setting_key = "admin_dahsboard_login_page_background_photo";

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        // $uploadFile = $this->uploadFile("file", "photo", IMAGE_PATH . $this->bg, "cdiphoto" . time() . uniqid() . ".jpg", true);
        // if ($uploadFile["status"] === "failed") {
        //     $response["status"] = ERROR_CODE;
        //     $response["data"] = $uploadFile["status_text"];
        //     die($this->encodeJSONdata($response));
        // }

        //Check if setting exists
        $settingsExists = $this->runQuery(
            $this->conn,
            "SELECT `setting_value` FROM `admin_dashboard_settings` WHERE `setting_key` = :setting_key LIMIT 1",
            array(
                "setting_key" => $setting_key
            ),
            true,
            true
        );

        if($settingsExists){
            $result = $this->runQuery(
                $this->conn,
                "UPDATE `admin_dashboard_settings` SET `setting_value` = :setting_value, `datetime_updated` = :datetime_updated WHERE `setting_key` = :setting_key LIMIT 1",
                array(
                    "setting_key" => $setting_key,
                    "setting_value" => $filename,
                    "datetime_updated" => $this->getDatetime(),
                )
            );
        }
        else{
            $result = $this->runQuery(
                $this->conn,
                "INSERT INTO `admin_dashboard_settings`(`setting_key`, `setting_value`, `setting_type`, `datetime_updated`) VALUES(:setting_key, :setting_value, :setting_type, :datetime_updated);",
                array(
                    "setting_type" => "photo",
                    "setting_key" => $setting_key,
                    "setting_value" => $filename,
                    "datetime_updated" => $this->getDatetime(),
                )
            );
        }

        if ($result) {
            //delete previous photo
            $this->deleteFileDO([MEDIA_PATH . $this->bg . ($settingsExists["setting_value"] ?? null)]);

            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Background photo has been successfully updated.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Something went wrong. Failed to upload background photo. Please try again.";
        die($this->encodeJSONdata($response));
    }

}
