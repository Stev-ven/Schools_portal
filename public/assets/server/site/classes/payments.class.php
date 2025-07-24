<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Payments extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfPaymentAccounts($idata = array(), $id = null, $return = false)
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
        $is_default = $this->filterData($idata["is_default"] ?? null);
        $account_type = $this->filterData($idata["account_type"] ?? null);
        $account_vendor_type = $this->filterData($idata["account_vendor_type"] ?? null);
        $is_active = $this->filterData($idata["is_active"] ?? null);
        
        $sortList = array(
            "id_asc" => " ORDER BY upa.`id` ASC",
            "id_desc" => " ORDER BY upa.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
            "account_vendor_name_asc" => " ORDER BY upa.`account_vendor_name` ASC",
            "account_vendor_name_desc" => " ORDER BY upa.`account_vendor_name` DESC",
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

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(upa.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(upa.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(upa.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        switch($is_default){
            case 0:
                $subQuery .= " AND (upa.`use_as_default_payment` = 0) ";
                break;
            case 1:
                $subQuery .= " AND (upa.`use_as_default_payment` = 1) ";
                break;
        }

        switch ($account_vendor_type) {
            case "bank":
                $subQuery .= " AND (upa.`account_vendor_type` = 'bank') ";
                break;
            case "mobile money":
                $subQuery .= " AND (upa.`account_vendor_type` = 'mobile money') ";
                break;
        }

        switch ($account_type) {
            case "personal":
                $subQuery .= " AND (upa.`account_type` = 'personal') ";
                break;
            case "business":
                $subQuery .= " AND (upa.`account_type` = 'business') ";
                break;
        }

        switch($is_active){
            case 0:
                $subQuery .= " AND (upa.`is_active` = 0) ";
                break;
            case 1:
                $subQuery .= " AND (upa.`is_active` = 1) ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (upa.`account_name` LIKE '%{$searchQuery}%' OR upa.`account_number` LIKE '%{$searchQuery}%' OR upa.`account_vendor_name` LIKE '%{$searchQuery}%' OR upa.`account_vendor_code` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (upa.`id` = '{$id}')" : null;

        $query = "SELECT upa.`user_id` AS `uid`, upa.`id`, upa.`account_type`, upa.`account_name`, upa.`account_number`, upa.`account_vendor_name`, upa.`account_vendor_code`, upa.`account_vendor_type`, upa.`datetime`, upa.`use_as_default_payment`, upa.`is_active`, usr.`first_name`, usr.`last_name`, usr.`mobile_number_country_number`, usr.`user_name` ,usr.`full_name`, usr.`email_address`, usr.`mobile_number`, usr.`user_id`, usr.`is_verified` FROM `users_payment_accounts` upa LEFT JOIN `users` usr ON upa.`user_id` = usr.`id` WHERE upa.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(upa.`id`) FROM `users_payment_accounts` upa LEFT JOIN `users` usr ON upa.`user_id` = usr.`id` WHERE upa.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfJobInvoices($idata = array(), $id = null, $return = false)
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
        $job_payment_category = $this->filterData($idata["job_payment_category"] ?? null);
        $job_payment_min_amount = $this->filterData($idata["job_payment_min_amount"] ?? 0);
        $job_payment_max_amount = $this->filterData($idata["job_payment_max_amount"] ?? 0);
        
        $sortList = array(
            "id_asc" => " ORDER BY piv.`id` ASC",
            "id_desc" => " ORDER BY piv.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
            "amount_asc" => " ORDER BY piv.`charge` ASC",
            "amount_desc" => " ORDER BY piv.`charge` DESC",
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
            case "unpaid":
                $subQuery .= " AND (piv.`status` = 'pending') ";
                break;
            case "paid":
                $subQuery .= " AND (piv.`status` = 'paid') ";
                break;
            case "not_confirmed":
                $subQuery .= " AND (piv.`status` = 'pending' AND (SELECT COUNT(*) FROM `project_invoice_payments` WHERE `invoice_id` = piv.`id` AND `status` != 'confirmed') > 0) ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(piv.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(piv.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(piv.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($user_account_activation_status)){
            $subQuery .= " AND (usr.`is_activated` = '".$user_account_activation_status."') ";
        }

        if(is_numeric($user_account_block_status)){
            $subQuery .= " AND (usr.`is_blocked` = '".$user_account_block_status."') ";
        }

        if(!$this->isEmpty($user_account_country)){
            $subQuery .= " AND (usr.`country_of_residence` = '".$user_account_country."') ";
        }

        if(!$this->isEmpty($job_payment_category)){
            $subQuery .= " AND (p.`project_category` = '".$job_payment_category."') ";
        }

        if($job_payment_max_amount > $job_payment_min_amount && is_numeric($job_payment_max_amount) && is_numeric($job_payment_min_amount)){
            $subQuery .= " AND (piv.`charge` >= ".$job_payment_min_amount." AND piv.`charge` <= ".$job_payment_max_amount.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (piv.`special_record_id` LIKE '%{$searchQuery}%' OR piv.`payment_reference_code` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR p.`project_id` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (piv.`id` = '{$id}')" : null;

        $query = "SELECT piv.`withdrawal_request_status`, piv.`special_record_id`, piv.`id`, piv.`charge`, piv.`payment_reference_code`, piv.`datetime`, piv.`status`, p.`title`, p.`id` AS `main_project_id`, usr.`first_name`, usr.`last_name`, usr.`mobile_number_country_number`, usr.`user_name` ,usr.`full_name`, usr.`email_address`, usr.`mobile_number`, usr.`user_id`, usr.`is_verified`, p.`project_id`, (SELECT `is_transferred` FROM `project_invoice_payments` WHERE `invoice_id` = piv.`id` AND `status` = 'confirmed' LIMIT 1) AS `payment_transfer_status`, (SELECT `id` FROM `project_invoice_payments` WHERE `invoice_id` = piv.`id` AND `status` = 'confirmed' LIMIT 1) AS `payment_invoice_id` FROM `project_invoice` piv LEFT JOIN `projects`p ON piv.`project_id` = p.`id` LEFT JOIN `users` usr ON piv.`user_id` = usr.`id` WHERE piv.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(piv.`id`) FROM `project_invoice` piv LEFT JOIN `projects`p ON piv.`project_id` = p.`id` LEFT JOIN `users` usr ON piv.`user_id` = usr.`id` WHERE piv.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfJobPayments($idata = array())
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
        $job_payment_category = $this->filterData($idata["job_payment_category"] ?? null);
        $job_payment_min_amount = $this->filterData($idata["job_payment_min_amount"] ?? 0);
        $job_payment_max_amount = $this->filterData($idata["job_payment_max_amount"] ?? 0);
        
        $sortList = array(
            "id_asc" => " ORDER BY piv.`id` ASC",
            "id_desc" => " ORDER BY piv.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
            "amount_asc" => " ORDER BY piv.`charge` ASC",
            "amount_desc" => " ORDER BY piv.`charge` DESC",
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
            case "payments_transferred":
                $subQuery .= " AND (pip.`is_transferred` > 0) AND (pip.`status` = 'confirmed') ";
                break;
            case "payments_not_transferred":
                $subQuery .= " AND (pip.`is_transferred` < 1) AND (pip.`status` = 'confirmed') ";
                break;
            case "not_confirmed":
                $subQuery .= " AND (pip.`status` != 'confirmed') ";
                break;
            case "default":
                $subQuery .= " AND (pip.`status` = 'confirmed') ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(pip.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pip.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pip.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($user_account_activation_status)){
            $subQuery .= " AND (usr.`is_activated` = '".$user_account_activation_status."') ";
        }

        if(is_numeric($user_account_block_status)){
            $subQuery .= " AND (usr.`is_blocked` = '".$user_account_block_status."') ";
        }

        if(!$this->isEmpty($user_account_country)){
            $subQuery .= " AND (usr.`country_of_residence` = '".$user_account_country."') ";
        }

        if(!$this->isEmpty($job_payment_category)){
            $subQuery .= " AND (p.`project_category` = '".$job_payment_category."') ";
        }

        if($job_payment_max_amount > $job_payment_min_amount && is_numeric($job_payment_max_amount) && is_numeric($job_payment_min_amount)){
            $subQuery .= " AND (pip.`amount` >= ".$job_payment_min_amount." AND pip.`amount` <= ".$job_payment_max_amount.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (piv.`payment_reference_code` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR p.`project_id` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT pip.`id` AS `pip_id`, pip.`invoice_id`, piv.`charge`, piv.`payment_reference_code`, pip.`is_transferred`, pip.`amount`, pip.`datetime`, pip.`status`, p.`title`, p.`project_id`, p.`id` AS `main_project_id`, usr.`first_name`, usr.`last_name`, usr.`mobile_number_country_number`, usr.`user_name` ,usr.`full_name`, usr.`email_address`, usr.`user_id`, usr.`is_verified`, usr.`mobile_number` FROM `project_invoice_payments` pip LEFT JOIN `projects`p ON pip.`project_id` = p.`id` LEFT JOIN `users` usr ON pip.`user_id` = usr.`id` LEFT JOIN `project_invoice` piv ON pip.`invoice_id` = piv.`id` WHERE pip.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `project_invoice_payments` pip LEFT JOIN `projects`p ON pip.`project_id` = p.`id` LEFT JOIN `users` usr ON pip.`user_id` = usr.`id` LEFT JOIN `project_invoice` piv ON pip.`invoice_id` = piv.`id` WHERE pip.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfServicePayments($idata = array(), $id = null, $return = false)
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
        $service_payment_job_category = $this->filterData($idata["service_payment_job_category"] ?? null);
        $service_payment_minimum_amount = $this->filterData($idata["service_payment_minimum_amount"] ?? 0);
        $service_payment_maximum_amount = $this->filterData($idata["service_payment_maximum_amount"] ?? 0);
        $service_payment_minimum_fee = $this->filterData($idata["service_payment_minimum_fee"] ?? 0);
        $service_payment_maximum_fee = $this->filterData($idata["service_payment_maximum_fee"] ?? 0);

        $sortList = array(
            "id_asc" => " ORDER BY mi.`id` ASC",
            "id_desc" => " ORDER BY mi.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
            "amount_asc" => " ORDER BY mio.`total_amount` ASC",
            "amount_desc" => " ORDER BY mio.`total_amount` DESC",
            "service_fee_asc" => " ORDER BY mio.`service_fee` ASC",
            "service_fee_desc" => " ORDER BY mio.`service_fee` DESC",
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
            case "payments_transferred":
                $subQuery .= " AND (mio.`is_transferred` > 0) ";
                break;
            case "payments_not_transferred":
                $subQuery .= " AND (mio.`is_transferred` < 1) ";
                break;
            case "payments_refundable":
                $subQuery .= " AND (mio.`refundable` = 'yes') ";
                break;
            case "payments_not_refundable":
                $subQuery .= " AND (mio.`refundable` = 'no') ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(mio.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(mio.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(mio.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($user_account_activation_status)){
            $subQuery .= " AND (usr.`is_activated` = '".$user_account_activation_status."') ";
        }

        if(is_numeric($user_account_block_status)){
            $subQuery .= " AND (usr.`is_blocked` = '".$user_account_block_status."') ";
        }

        if(!$this->isEmpty($user_account_country)){
            $subQuery .= " AND (usr.`country_of_residence` = '".$user_account_country."') ";
        }

        if(!$this->isEmpty($service_payment_job_category)){
            $subQuery .= " AND (mio.`category` = '".$service_payment_job_category."') ";
        }

        if($service_payment_maximum_amount > $service_payment_minimum_amount && is_numeric($service_payment_maximum_amount) && is_numeric($service_payment_minimum_amount)){
            $subQuery .= " AND (mio.`total_amount` >= ".$service_payment_minimum_amount." AND mio.`total_amount` <= ".$service_payment_maximum_amount.") ";
        }

        if($service_payment_maximum_fee > $service_payment_minimum_fee && is_numeric($service_payment_maximum_fee) && is_numeric($service_payment_minimum_fee)){
            $subQuery .= " AND (mio.`service_fee` >= ".$service_payment_minimum_fee." AND mio.`service_fee` <= ".$service_payment_maximum_fee.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (mio.`order_id` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR mi.`title` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (mio.`id` = '{$id}')" : null;

        $query = "SELECT mio.`id` AS `main_order_id`, mio.`withdrawal_request_status_worker`, mio.`withdrawal_request_status_client`, (SELECT `id` FROM `projects` WHERE `reference_id` = mio.`order_id` LIMIT 1) AS `main_project_id`, mi.`title`, mio.`payment_status`, mio.`market_item_id`, mio.`order_id`, mio.`datetime`, mio.`total_amount`, mio.`service_fee`, mio.`is_transferred`, usr.`first_name`, usr.`last_name`, usr.`mobile_number_country_number`, usr.`user_name` ,usr.`full_name`, usr.`email_address`, usr.`user_id`, usr.`is_verified`, usr.`mobile_number` FROM `market_items_orders` mio LEFT JOIN `market_items` mi ON mio.`market_item_id` = mi.`id` LEFT JOIN `users` usr ON mio.`user_id` = usr.`id` WHERE mio.`payment_status` = 'paid' AND mio.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(mio.`id`) FROM `market_items_orders` mio LEFT JOIN `market_items` mi ON mio.`market_item_id` = mi.`id` LEFT JOIN `users` usr ON mio.`user_id` = usr.`id` WHERE mio.`payment_status` = 'paid' AND mio.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfPaymentWithdrawals($idata = array(), $id = null, $return = false)
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
        $withdrawal_type = $this->filterData($idata["withdrawal_type"] ?? null);
        $request_status = $this->filterData($idata["request_status"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY pwr.`id` ASC",
            "id_desc" => " ORDER BY pwr.`id` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
            "email_asc" => " ORDER BY usr.`email_address` ASC",
            "email_desc" => " ORDER BY usr.`email_address` DESC",
            "amount_asc" => " ORDER BY pwr.`amount_requested` ASC",
            "amount_desc" => " ORDER BY pwr.`amount_requested` DESC"
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

        switch ($request_status) {
            case "pending":
                $subQuery .= " AND (pwr.`request_status` = 'pending') ";
                break;
            case "completed":
                $subQuery .= " AND (pwr.`request_status` = 'completed') ";
                break;
            case "in_progress":
                $subQuery .= " AND (pwr.`request_status` = 'in-progress') ";
                break;
            case "cancelled":
                $subQuery .= " AND (pwr.`request_status` = 'cancelled') ";
                break;
        }

        switch ($viewType) {
            case "pending_withdrawals":
                $subQuery .= " AND (pwr.`payment_status` = 'pending') ";
                break;
            case "paid_withdrawals":
                $subQuery .= " AND (pwr.`payment_status` = 'paid') ";
                break;
            case "in_progress_withdrawals":
                $subQuery .= " AND (pwr.`payment_status` = 'in-progress') ";
                break;
            case "cancelled_withdrawals":
                $subQuery .= " AND (pwr.`payment_status` = 'cancelled') ";
                break;
        }

        switch ($filterType) {
            case "custom_date":
                if (!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)) {
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if ($cdateStart <= $cdateEnd) {
                        $subQuery .= " AND (DATE(pwr.`datetime_requested`) BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pwr.`datetime_requested`) = '" . date("Y-m-d") . "') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pwr.`datetime_requested`) = '" . date("Y-m-d", strtotime("-1 day")) . "') ";
                break;
        }

        if (is_numeric($user_account_activation_status)) {
            $subQuery .= " AND (usr.`is_activated` = '" . $user_account_activation_status . "') ";
        }

        if (is_numeric($user_account_block_status)) {
            $subQuery .= " AND (usr.`is_blocked` = '" . $user_account_block_status . "') ";
        }

        if (!$this->isEmpty($user_account_country)) {
            $subQuery .= " AND (usr.`country_of_residence` = '" . $user_account_country . "') ";
        }

        if (!$this->isEmpty($withdrawal_type)) {
            $subQuery .= " AND (pwr.`withdrawal_type` = '" . $withdrawal_type . "') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (pwr.`request_id` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (pwr.`id` = '{$id}')" : null;

        $query = "SELECT pwr.`user_id` AS `uid`, pwr.`transfer_code`, pwr.`id`, pwr.`request_status`, pwr.`withdrawal_type`, pwr.`request_id`, pwr.`amount_requested`, pwr.`datetime_requested`, pwr.`payment_status`, pwr.`datetime_paid`, usr.`first_name`, usr.`last_name`, usr.`mobile_number_country_number`, usr.`user_name` ,usr.`full_name`, usr.`email_address`, usr.`user_id`, usr.`is_verified`, usr.`mobile_number` FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(pwr.`id`) FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`id` IS NOT NULL {$subQuery}");
        
        $total_amount = $this->selectSingleData($this->conn, "SELECT SUM(pwr.`amount_requested`) AS `request_amount_sum` FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`id` IS NOT NULL {$subQuery}");
        $total_amount_pending = $this->selectSingleData($this->conn, "SELECT SUM(pwr.`amount_requested`) AS `request_amount_sum` FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`payment_status` = 'pending' AND pwr.`request_status` = 'pending' AND pwr.`id` IS NOT NULL {$subQuery}");
        $total_amount_in_progress = $this->selectSingleData($this->conn, "SELECT SUM(pwr.`amount_requested`) AS `request_amount_sum` FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`payment_status` = 'pending' AND pwr.`request_status` = 'in-progress' AND pwr.`id` IS NOT NULL {$subQuery}");
        $total_amount_paid = $this->selectSingleData($this->conn, "SELECT SUM(pwr.`amount_requested`) AS `request_amount_sum` FROM `payment_withdrawal_requests` pwr LEFT JOIN `users` usr ON pwr.`user_id` = usr.`id` WHERE pwr.`withdrawal_assembling_completed` = 'yes' AND pwr.`payment_status` = 'paid' AND pwr.`request_status` = 'completed' AND pwr.`id` IS NOT NULL {$subQuery}");

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

        if ($return) {
            return $result;
        }

        $response["status"] = SUCCESS_CODE;
        $response["data"] = array(
            "data" => $result,
            "data_total_amount" => $this->numberFormat($total_amount["request_amount_sum"]),
            "data_total_amount_pending" => $this->numberFormat($total_amount_pending["request_amount_sum"]),
            "data_total_amount_in_progress" => $this->numberFormat($total_amount_in_progress["request_amount_sum"]),
            "data_total_amount_paid" => $this->numberFormat($total_amount_paid["request_amount_sum"]),
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

    public function getSingleInvoiceHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfJobInvoices($this->getListOfJobInvoices(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleServicePaymentHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfServicePayments($this->getListOfServicePayments(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSinglePaymentAccountHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfPaymentAccounts($this->getListOfPaymentAccounts(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSinglePaymentWithdrawalsHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfPaymentWithdrawals($this->getListOfPaymentWithdrawals(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

}
