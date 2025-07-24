<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Render extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function renderViewItem($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $item_type = $this->toLowerCase($this->filterData($idata["item_type"] ?? null));
        $item_id = $this->decryptString($idata["item_id"] ?? null);
        $view_type = $this->filterData($idata["view_type"] ?? null);

        switch($item_type){
            case "administrator":
                if (!$this->conn) {
                    $this->throwConnectionError();
                }

                $result = $this->runQuery(
                    $this->conn,
                    "SELECT usr.`envoyer_role`, usr.`contact_entry_warnings`, usr.`word_usage_warnings`, usr.`id`, usr.`is_worker_at_envoyer`, usr.`is_blog_writer`, usr.`user_id`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`country_of_residence`, usr.`datetime_joined`, usr.`account_type`, usr.`profile_photo`, usr.`is_blocked`, usr.`is_activated`, usr.`profile_photo_valid`, usr.`user_name`, usr.`email_address`, usr.`mobile_number`, usr.`mobile_number_country_number` FROM `users_admin` usr WHERE usr.`id` = :user_id LIMIT 1",
                    array(
                        "user_id" => $item_id
                    ),
                    true
                );
        
                if ($result) {
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $this->FactoryClass()->Views()->renderListOfAdministrators($result, $view_type);
                    die($this->encodeJSONdata($response));
                }
        
                $response["status"] = ERROR_CODE;
                $response["data"] = "Something went wrong. Please try again.";
                die($this->encodeJSONdata($response));
                break;
        }
    }

}
