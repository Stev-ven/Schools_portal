<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Admin extends General
{

    protected $bg = "bg/";
    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function deleteLead($idata = array(), $return = false)
    {
        /**
         * @param Array $idata
         * @return String
         */

        $admin_id = $this->decryptString($idata["admin_id"] ?? null);
        $id = $this->decryptString($idata["id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "DELETE FROM `leads` WHERE `id` = :id;",
            array(
                "id" => $id,
            )
        );

        if ($result) {
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Lead successfully deleted.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Failed to delete lead.";
        die($this->encodeJSONdata($response));
    }

    public function getLead($idata = array(), $return = false)
    {
        /**
         * @param Array $idata
         * @return String
         */

        $admin_id = $this->decryptString($idata["admin_id"] ?? null);
        $id = $this->decryptString($idata["id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT * FROM `leads` WHERE `id` = :id;",
            array(
                "id" => $id,
            ),
            true,
            true
        );

        if ($result) {
            $result["id"] = $this->encryptString($result["id"], true);

            $response["status"] = SUCCESS_CODE;
            $response["data"] = $result;
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Lead not found.";
        die($this->encodeJSONdata($response));
    }

    public function manageLead($idata = array(), $return = false)
    {
        /**
         * @param Array $idata
         * @return String
         */

        $admin_id = $this->decryptString($idata["admin_id"] ?? null);
        $id = $this->decryptString($idata["id"] ?? null);
        $full_name = $this->filterData($idata["full_name"] ?? null);
        $email_address = $this->filterData($idata["email_address"] ?? null);
        $mobile_number = $this->filterData($idata["mobile_number"] ?? null);
        $country = $this->filterData($idata["country"] ?? null);
        $request_category = $this->filterData($idata["request_category"] ?? null);
        $request_item = $this->filterData($idata["request_item"] ?? null);
        $note = $this->filterData($idata["note"] ?? null);
        $is_business = $this->filterData($idata["is_business"] ?? null);
        $company_name = $this->filterData($idata["company_name"] ?? null);
        $next_call_date = $this->filterData($idata["next_call_date"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        if (is_numeric($id) && $id > 0) {
            $result = $this->runQuery(
                $this->conn,
                "UPDATE `leads` SET
                    `full_name` = :full_name,
                    `note` = :note,
                    `mobile_number` = :mobile_number,
                    `email_address` = :email_address,
                    `request_category` = :request_category,
                    `request_item` = :request_item,
                    `next_expected_call_date` = :next_expected_call_date,
                    `company` = :company,
                    `is_business` = :is_business,
                    `datetime_updated` = :datetime_updated,
                    `country` = :country
                    WHERE `id` = :id
                ",
                array(
                    "id" => $id,
                    "full_name" => $full_name,
                    "note" => $note,
                    "mobile_number" => $mobile_number,
                    "email_address" => $email_address,
                    "request_category" => $request_category,
                    "request_item" => $request_item,
                    "next_expected_call_date" => $next_call_date,
                    "company" => $company_name,
                    "is_business" => $is_business,
                    "datetime_updated" => $this->getDatetime(),
                    "country" => $country,
                )
            );

            if ($result) {
                $response["status"] = SUCCESS_CODE;
                $response["data"] = "Lead has been successfully updated.";
                $response["action"] = "replace";
                $response["id"] = $this->encryptString($id);
                die($this->encodeJSONdata($response));
            }

            $response["status"] = ERROR_CODE;
            $response["data"] = "Failed to update lead.";
            die($this->encodeJSONdata($response));
        } else {
            $result = $this->runQuery(
                $this->conn,
                "INSERT INTO `leads` (
                    `full_name`,
                    `note`,
                    `mobile_number`,
                    `email_address`,
                    `request_category`,
                    `request_item`,
                    `next_expected_call_date`,
                    `company`,
                    `is_business`,
                    `datetime_added`,
                    `last_contacted`,
                    `country`
                ) VALUES (
                    :full_name,
                    :note,
                    :mobile_number,
                    :email_address,
                    :request_category,
                    :request_item,
                    :next_expected_call_date,
                    :company,
                    :is_business,
                    :datetime_added,
                    :last_contacted,
                    :country
                )",
                array(
                    "full_name" => $full_name,
                    "note" => $note,
                    "mobile_number" => $mobile_number,
                    "email_address" => $email_address,
                    "request_category" => $request_category,
                    "request_item" => $request_item,
                    "next_expected_call_date" => $next_call_date,
                    "company" => $company_name,
                    "is_business" => $is_business,
                    "datetime_added" => $this->getDatetime(),
                    "last_contacted" => $this->getDatetime(),
                    "country" => $country,
                )
            );

            if ($result) {
                $id = $this->encryptString($this->lastInsertId($this->conn));

                $response["status"] = SUCCESS_CODE;
                $response["data"] = "Lead has been successfully saved.";
                $response["action"] = "add";
                $response["id"] = $id;
                die($this->encodeJSONdata($response));
            }

            $response["status"] = ERROR_CODE;
            $response["data"] = "Failed to save lead.";
            die($this->encodeJSONdata($response));
        }
    }
    
    public function viewAdminAccountDetails($idata = array())
    {
        /**
         * @param Array $idata
         * @return String
         */

        $user_id = $this->decryptString($idata["user_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT usr.`user_name`, usr.`is_blog_writer`, usr.`envoyer_role`, usr.`email_address`, usr.`first_name`, usr.`last_name`, usr.`gender`, usr.`datetime_joined`, usr.`mobile_number_country_number`, usr.`mobile_number` FROM `users_admin` usr WHERE usr.`id` = :user_id LIMIT 1",
            array(
                "user_id" => $user_id
            ),
            true,
            true
        );

        if ($result) {
            $result["permissions"] = array();

            $response["status"] = SUCCESS_CODE;
            $response["data"] = $result;
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Account details not found.";
        die($this->encodeJSONdata($response));
    }

    public function blockUnblockAdminAccount($idata = array(), $return = false)
    {
        /**
         * @param Array $idata
         * @return Void
         */

        $user_id = $this->decryptString($idata["user_id"] ?? null);
        $status = $this->filterData($idata["status"] ?? 0) > 0 ? 0 : 1;

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `users_admin` SET `is_blocked` = :is_blocked WHERE `id` = :user_id;",
            array(
                "is_blocked" => $status,
                "user_id" => $user_id,
            ),
        );

        if($result){
            $response["status"] = SUCCESS_CODE;
            $response["data"] = $status > 0 ? "Admin account has been successfully blocked." : "Admin account has been successfully unblocked.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Something went wrong.";
        die($this->encodeJSONdata($response));
    }

    public function deleteCompanyDocument($idata = array()) 
    {
        /**
         * @param Array $idata
         * @return String
         */

        $admin_id = $this->decryptString($idata["admin_id"] ?? null);
        $id = $this->decryptString($idata["id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `document_filename` FROM `company_documents` WHERE `id` = :id",
            array(
                "id" => $id,
            ),
            true,
            true
        );

        if ($result) {
            //Delete record
            $this->runQuery( 
                $this->conn, 
                "DELETE FROM `company_documents` WHERE `id` = :id", 
                array(
                    "id" => $id, 
                ) 
            ); 
            
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Document has been successfully removed.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Document not found.";
        die($this->encodeJSONdata($response));
    }

}
