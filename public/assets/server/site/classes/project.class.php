<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Project extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfProjects($idata = array(), $id = null, $return = false)
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
        $job_verification_status = $this->filterData($idata["job_verification_status"] ?? null);
        $job_category = $this->filterData($idata["job_category"] ?? null);
        $skills_list = $this->filterData($idata["skills_list"] ?? null);
        $job_type = $this->filterData($idata["job_type"] ?? null);
        $job_country = $this->filterData($idata["job_country"] ?? null);
        $job_expiry_date = $this->filterData($idata["job_expiry_date"] ?? null);
        $job_minimum_amount = $this->filterData($idata["job_minimum_amount"] ?? null);
        $job_maximum_amount = $this->filterData($idata["job_maximum_amount"] ?? null);
        $job_start_status = $this->filterData($idata["job_start_status"] ?? null);
        $job_review_replied = $this->filterData($idata["job_review_replied"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY p.`id` ASC",
            "id_desc" => " ORDER BY p.`id` DESC",
            "title_asc" => " ORDER BY p.`title` ASC",
            "title_desc" => " ORDER BY p.`title` DESC",
            "applicants_asc" => " ORDER BY `number_of_applicants` ASC",
            "applicants_desc" => " ORDER BY `number_of_applicants` DESC",
            "views_asc" => " ORDER BY p.`views` ASC",
            "views_desc" => " ORDER BY p.`views` DESC",
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
            case "pending_approval":
                $subQuery .= " AND (p.`status` IN('in_review')) ";
                break;
            case "approved_jobs":
                $subQuery .= " AND (p.`status` IN('accepting_applicants')) ";
                break;
            case "having_applicants":
                $subQuery .= " AND ((SELECT COUNT(*) FROM `project_applications` WHERE `project_id` = p.`id`) > 0) ";
                break;
            case "awarded_to_applicant":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant')) ";
                break;
            case "awarded_to_applicant_not_started":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant') AND (p.`project_started_date` = '' OR p.`project_started_date` IS NULL)) ";
                break;
            case "awarded_to_applicant_not_reviewed":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant') AND p.`id` NOT IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id`)) ";
                break;
            case "ongoing_jobs":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant') AND p.`id` IN(SELECT `project_id` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_project_started` > 0)) ";
                break;
            case "completed_jobs":
                $subQuery .= " AND (p.`status` IN('completed')) ";
                break;
            case "pending_payment":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND ((SELECT IFNULL(SUM(`amount`), 0) FROM `project_invoice_payments` WHERE `project_id` = p.`id` AND `status` = 'confirmed') < (SELECT IFNULL(`charge_agreed`, 0) FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0))) ";
                break;
            case "paid_jobs":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND ((SELECT IFNULL(SUM(`amount`), 0) FROM `project_invoice_payments` WHERE `project_id` = p.`id` AND `status` = 'confirmed') > 0) AND ((SELECT IFNULL(SUM(`amount`), 0) FROM `project_invoice_payments` WHERE `project_id` = p.`id` AND `status` = 'confirmed') >= (SELECT IFNULL(`charge_agreed`, 0) FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0))) ";
                break;
            case "payment_transferred":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND ((SELECT IFNULL(SUM(`amount`), 0) FROM `project_invoice_payments` WHERE `project_id` = p.`id` AND `status` = 'confirmed' AND `is_transferred` > 0) >= (SELECT IFNULL(`charge_agreed`, 0) FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0))) ";
                break;
            case "payment_not_transferred":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND  AND p.`id` IN(SELECT `project_id` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_project_started` > 0) AND ((SELECT COUNT(*) FROM `project_invoice_payments` WHERE `project_id` = p.`id`) > 0) AND ((SELECT COUNT(*) FROM `project_invoice_payments` WHERE `project_id` = p.`id` AND `is_transferred` > 0) < 1)) ";
                break;
            case "reviewed":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND p.`id` IN(SELECT `project_id` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_project_started` > 0) AND p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id`)) ";
                break;
            case "pending_reviews":
                $subQuery .= " AND (p.`status` IN('in_review')) ";
                break;
            case "pending_reviews_artisan":
                $subQuery .= " AND (p.`project_type` = 'artisan' AND p.`status` IN('in_review')) ";
                break;
            case "pending_reviews_freelance":
                $subQuery .= " AND (p.`project_type` = 'freelance' AND p.`status` IN('in_review')) ";
                break;
            case "cancelled_jobs":
                $subQuery .= " AND (p.`status` IN('cancelled', 'deleted')) ";
                break;
            case "expired_without_applicants":
                $subQuery .= " AND (p.`expiry_date` <= '".$this->getDatetime()."' AND (SELECT COUNT(*) FROM `project_applications` WHERE `project_id` = p.`id`) <= 0) ";
                break;
            case "expired_with_applicants":
                $subQuery .= " AND (p.`expiry_date` <= '".$this->getDatetime()."' AND (SELECT COUNT(*) FROM `project_applications` WHERE `project_id` = p.`id`) > 0) ";
                break;
            case "on_watchlist":
                $subQuery .= " AND (p.`id` IN(SELECT `watch_id` FROM `watch_list` WHERE `watch_id` = p.`id` AND `watch_type` = 'job')) ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(p.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(p.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(p.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $exps = explode(",", $skills_list);
        $sl = array();

        if(count($exps)){
            foreach($exps as $ex){
                if(!$this->isEmpty($ex)){
                    $sl[] = "'".$ex."'";
                }
            }
        }

        if(count($sl) > 0){
            $subQuery .= " AND (p.`id` IN(SELECT `project_id` FROM `project_skills` WHERE `skill_id` IN(".implode(",", $sl).") AND `project_id` = p.`id`)) ";
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

        if(is_numeric($job_verification_status)){
            $subQuery .= " AND (p.`is_verified` = '".$job_verification_status."') ";
        }

        if(!$this->isEmpty($job_category)){
            $subQuery .= " AND (p.`project_category` = '".$job_category."') ";
        }

        if(!$this->isEmpty($job_type)){
            $subQuery .= " AND (p.`project_type` = '".$job_type."') ";
        }

        if(!$this->isEmpty($job_country)){
            $subQuery .= " AND (p.`country_posted_from` = '".$job_country."') ";
        }

        if(!$this->isEmpty($job_expiry_date)){
            $subQuery .= " AND (p.`expiry_date` = '".$job_expiry_date."') ";
        }

        switch($job_start_status){
            case "started":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant') AND p.`id` IN(SELECT `project_id` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_project_started` > 0)) ";
                break;
            case "not_started":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant') AND p.`id` IN(SELECT `project_id` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_project_started` < 1)) ";
                break;
        }

        switch($job_review_replied){
            case "review_replied":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id` AND (`reply` IS NOT NULL OR `reply` != ''))) ";
                break;
            case "review_not_replied":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id` AND (`reply` IS NULL OR `reply` = ''))) ";
                break;
        }

        if($job_maximum_amount > $job_minimum_amount && is_numeric($job_maximum_amount) && is_numeric($job_minimum_amount)){
            $subQuery .= " AND (p.`minimum_payment` >= ".$job_minimum_amount." AND p.`maximum_payment` <= ".$job_maximum_amount.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (p.`project_id` = '{$searchQuery}' OR p.`title_id` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (p.`id` = '{$id}')" : null;

        $query = "SELECT (SELECT IFNULL(SUM(`charge`), 0) FROM `project_invoice` WHERE `status` IN('paid', 'pending') AND `project_id` = p.`id`) AS `total_invoice_amount`, (SELECT IFNULL(SUM(`charge`), 0) FROM `project_invoice` WHERE `status` = 'paid' AND `project_id` = p.`id`) AS `amount_paid`, (SELECT IFNULL(SUM(`charge`), 0) FROM `project_invoice` WHERE `status` = 'pending' AND `project_id` = p.`id`) AS `amount_pending`, (SELECT `charge_agreed` FROM `project_applications` WHERE `project_id` = p.`id` AND `is_user_selected` > 0 AND `has_client_accepted_user` > 0 AND `has_user_accepted_contract` > 0) AS `charge_agreed`, (SELECT COUNT(*) FROM `project_applications` WHERE `is_user_selected` > 0 AND `has_client_accepted_user` > 0 AND `has_user_accepted_contract` > 0 AND `project_id` = p.`id`) AS `is_applicant_chosen`, (SELECT COUNT(*) FROM `project_reviews` WHERE `project_id` = p.`id`) AS `number_of_reviews`, (SELECT COUNT(*) FROM `project_applications` WHERE `project_id` = p.`id`) AS `number_of_applicants`, p.`user_id` AS `puid`, p.`location`, p.`longitude`, p.`latitude`, p.`project_started_date`, p.`id` AS `pid`, p.`views`, p.`project_type`, p.`project_id`, p.`title_id`, p.`datetime`, p.`mobile_number_country_number`, p.`title`, p.`description`, p.`payment_type`, p.`status`, p.`minimum_payment`, p.`maximum_payment`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address` AS `user_email_address`, usr.`mobile_number` AS `user_mobile_number`, usr.`mobile_number_country_number` AS `user_mobile_number_country_number`, usr.`is_blocked` AS `is_user_blocked`, usr.`is_verified`, usr.`user_id` FROM `projects` p LEFT JOIN `users` usr ON p.`user_id` = usr.`id` WHERE p.`id` IS NOT NULL AND (`service_id_type` IS NULL OR `service_id_type` = '') {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(p.`id`) FROM `projects` p LEFT JOIN `users` usr ON p.`user_id` = usr.`id` WHERE p.`id` IS NOT NULL AND (`service_id_type` IS NULL OR `service_id_type` = '') {$subQuery}");

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
                $rdata["on_watchlist"] = $this->isOnWatchList($rdata["pid"], "job") === true ? "yes" : "no";
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

    public function getListOfProjectApplicants($idata = array(), $id = null, $return = false)
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
        $country = $this->filterData($idata["country"] ?? null);
        $blockStatus = $this->filterData($idata["block_status"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY pa.`id` ASC",
            "id_desc" => " ORDER BY pa.`id` DESC",
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
                        $subQuery .= " AND (DATE(pa.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pa.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pa.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(!$this->isEmpty($job_country)){
            $subQuery .= " AND (pa.`country` = '".$job_country."') ";
        }

        switch($blockStatus){
            case 0:
                $subQuery .= " AND (pa.`is_blocked` < 1) ";
                break;
            case 1:
                $subQuery .= " AND (pa.`is_blocked` > 0) ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (pa.`special_record_id` LIKE '%{$searchQuery}%' OR p.`project_id` = '{$searchQuery}' OR p.`title_id` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (pa.`id` = '{$id}')" : null;

        $query = "SELECT pa.`id` AS `application_id`, pa.`datetime` AS `datetime_applied`, pa.`cover_letter`, pa.`special_record_id`, pa.`is_blocked` AS `applicant_block_status`, pa.`user_id` AS `puid`, p.`location`, p.`longitude`, p.`latitude`, p.`project_started_date`, p.`id` AS `pid`, p.`views`, p.`project_type`, p.`project_id`, p.`title_id`, p.`datetime`, p.`mobile_number_country_number`, p.`title`, p.`description`, p.`payment_type`, p.`status`, p.`minimum_payment`, p.`maximum_payment`, usr.`user_id`, usr.`profile_photo`, usr.`profile_photo_valid`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address` AS `user_email_address`, usr.`mobile_number` AS `user_mobile_number`, usr.`mobile_number_country_number` AS `user_mobile_number_country_number`, usr.`is_blocked` AS `is_user_blocked`, usr.`is_verified`, usr.`user_id` FROM `project_applications` pa LEFT JOIN `projects` p ON pa.`project_id` = p.`id` LEFT JOIN `users` usr ON pa.`user_id` = usr.`id` WHERE p.`id` IS NOT NULL AND (p.`service_id_type` IS NULL OR p.`service_id_type` = '') {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(pa.`id`) FROM `project_applications` pa LEFT JOIN `projects` p ON pa.`project_id` = p.`id` LEFT JOIN `users` usr ON pa.`user_id` = usr.`id` WHERE p.`id` IS NOT NULL AND (p.`service_id_type` IS NULL OR p.`service_id_type` = '') {$subQuery}");

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

    public function getListOfProjectAttachments($idata = array(), $id = null, $return = false)
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

        $sortList = array(
            "id_asc" => " ORDER BY pa.`id` ASC",
            "id_desc" => " ORDER BY pa.`id` DESC",
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
                        $subQuery .= " AND (DATE(pa.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pa.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pa.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (pa.`actual_attached_document_name` LIKE '%{$searchQuery}%' OR pa.`attached_document_name` LIKE '%{$searchQuery}%' OR pa.`special_record_id` LIKE '%{$searchQuery}%' OR p.`project_id` = '{$searchQuery}' OR p.`title_id` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (pa.`id` = '{$id}')" : null;

        $query = "SELECT p.`service_id_type`, pa.`special_record_id`, pa.`id`, pa.`datetime` AS `attachment_datetime`, pa.`description`, pa.`attached_document_name`, pa.`actual_attached_document_name`, pa.`special_record_id`, p.`user_id` AS `puid`, pa.`shared_by` AS `uid`, p.`location`, p.`longitude`, p.`latitude`, p.`project_started_date`, p.`id` AS `pid`, p.`views`, p.`project_type`, p.`project_id`, p.`title_id`, p.`datetime`, p.`mobile_number_country_number`, p.`title`, p.`payment_type`, p.`status`, p.`minimum_payment`, p.`maximum_payment`, usr.`user_id`, usr.`profile_photo`, usr.`profile_photo_valid`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address` AS `user_email_address`, usr.`mobile_number` AS `user_mobile_number`, usr.`mobile_number_country_number` AS `user_mobile_number_country_number`, usr.`is_blocked` AS `is_user_blocked`, usr.`is_verified`, usr.`user_id` FROM `project_attachments` pa LEFT JOIN `projects` p ON pa.`project_id` = p.`id` LEFT JOIN `users` usr ON pa.`shared_by` = usr.`id` WHERE p.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(pa.`id`) FROM `project_attachments` pa LEFT JOIN `projects` p ON pa.`project_id` = p.`id` LEFT JOIN `users` usr ON pa.`shared_by` = usr.`id` WHERE p.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfProjectRequirements($idata = array(), $id = null, $return = false)
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

        $sortList = array(
            "id_asc" => " ORDER BY pa.`id` ASC",
            "id_desc" => " ORDER BY pa.`id` DESC",
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
                        $subQuery .= " AND (DATE(pa.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pa.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pa.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (pa.`item_date` LIKE '%{$searchQuery}%' OR pa.`item_value` LIKE '%{$searchQuery}%' OR pa.`special_record_id` LIKE '%{$searchQuery}%' OR pa.`order_id` = '{$searchQuery}' OR p.`title_id` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (pa.`id` = '{$id}')" : null;

        $query = "SELECT (SELECT `id` FROM `projects` WHERE `reference_id` = pa.`order_id` LIMIT 1) AS `pid`, pa.`item_type`, pa.`order_id`, pa.`special_record_id`, pa.`id`, pa.`datetime_added`, pa.`item_value`, pa.`item_data`, pa.`special_record_id`, pa.`user_id` AS `uid`, usr.`user_id`, usr.`profile_photo`, usr.`profile_photo_valid`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address` AS `user_email_address`, usr.`mobile_number` AS `user_mobile_number`, usr.`mobile_number_country_number` AS `user_mobile_number_country_number`, usr.`is_blocked` AS `is_user_blocked`, usr.`is_verified`, usr.`user_id` FROM `market_items_requirements` pa LEFT JOIN `users` usr ON pa.`user_id` = usr.`id` WHERE pa.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(pa.`id`) FROM `market_items_requirements` pa LEFT JOIN `users` usr ON pa.`user_id` = usr.`id` WHERE pa.`id` IS NOT NULL {$subQuery}");

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

    public function getListOfPurchases($idata = array())
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
        $purchase_review_reply = $this->filterData($idata["purchase_review_reply"] ?? null);
        $purchase_service_category = $this->filterData($idata["purchase_service_category"] ?? null);
        $purchase_country_purchased_from = $this->filterData($idata["purchase_country_purchased_from"] ?? null);
        $purchase_package_type = $this->filterData($idata["purchase_package_type"] ?? null);
        $purchase_refundable = $this->filterData($idata["purchase_refundable"] ?? null);
        $purchase_minimum_amount = $this->filterData($idata["purchase_minimum_amount"] ?? 0);
        $purchase_maximum_amount = $this->filterData($idata["purchase_maximum_amount"] ?? 0);
        $purchase_minimum_amount_service = $this->filterData($idata["purchase_minimum_amount_service"] ?? 0);
        $purchase_maximum_amount_service = $this->filterData($idata["purchase_maximum_amount_service"] ?? 0);
        
        $sortList = array(
            "id_asc" => " ORDER BY p.`id` ASC",
            "id_desc" => " ORDER BY p.`id` DESC",
            "total_amount_asc" => " ORDER BY mio.`total_amount` ASC",
            "total_amount_desc" => " ORDER BY mio.`total_amount` DESC",
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
            case "completed_purchases":
                $subQuery .= " AND (p.`status` IN('completed'))  AND (mio.`payment_status` = 'paid')";
                break;
            case "ongoing_purchases":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant'))  AND (mio.`payment_status` = 'paid')";
                break;
            case "work_started":
                $subQuery .= " AND (mio.`has_order_started` > 0)  AND (mio.`payment_status` = 'paid')";
                break;
            case "requirements_submitted":
                $subQuery .= " AND (mio.`is_requirement_submitted` > 0)  AND (mio.`payment_status` = 'paid')";
                break;
            case "requirements_submitted_not_started":
                $subQuery .= " AND (mio.`is_requirement_submitted` > 0 AND mio.`has_order_started` < 1)  AND (mio.`payment_status` = 'paid')";
                break;
            case "requirements_not_submitted":
                $subQuery .= " AND (mio.`is_requirement_submitted` < 1)  AND (mio.`payment_status` = 'paid')";
                break;
            case "requirements_not_submitted_not_started":
                $subQuery .= " AND (mio.`is_requirement_submitted` < 1 AND mio.`has_order_started` < 1)  AND (mio.`payment_status` = 'paid')";
                break;
            case "cancelled":
                $subQuery .= " AND (mio.`order_agreement_status` IN('cancelled'))  AND (mio.`payment_status` = 'paid')";
                break;
            case "reviewed":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id`))  AND (mio.`payment_status` = 'paid')";
                break;
            case "pending_reviews":
                $subQuery .= " AND (p.`status` IN('awarded_to_applicant', 'completed') AND p.`id` NOT IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id`))  AND (mio.`payment_status` = 'paid')";
                break;
            case "on_watchlist":
                $subQuery .= " AND (mio.`id` IN(SELECT `watch_id` FROM `watch_list` WHERE `watch_id` = mio.`id` AND `watch_type` = 'purchase'))  AND (mio.`payment_status` = 'paid')";
                break;
            case "pending_payment":
                $subQuery .= " AND (mio.`payment_status` = 'pending') ";
                break;
            default:
                $subQuery .= " AND (mio.`payment_status` = 'paid') ";
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

        if(!$this->isEmpty($purchase_service_category)){
            $subQuery .= " AND (p.`project_category` = '".$purchase_service_category."') ";
        }

        if(!$this->isEmpty($purchase_package_type)){
            $subQuery .= " AND (p.`service_package_type` = '".$purchase_package_type."') ";
        }

        if(!$this->isEmpty($purchase_country_purchased_from)){
            $subQuery .= " AND (p.`country_posted_from` = '".$purchase_country_purchased_from."') ";
        }

        if(!$this->isEmpty($purchase_refundable)){
            $subQuery .= " AND (mio.`refundable` = '".$purchase_refundable."') ";
        }

        switch($purchase_review_reply){
            case "review_replied":
                $subQuery .= " AND (p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id` AND (`reply` IS NOT NULL OR `reply` != ''))) ";
                break;
            case "review_not_replied":
                $subQuery .= " AND (p.`id` IN(SELECT `project_id` FROM `project_reviews` WHERE `project_id` = p.`id` AND (`reply` IS NULL OR `reply` = ''))) ";
                break;
        }

        if($purchase_maximum_amount > $purchase_minimum_amount && is_numeric($purchase_maximum_amount) && is_numeric($purchase_minimum_amount)){
            $subQuery .= " AND (mio.`total_amount` >= ".$purchase_minimum_amount." AND mio.`total_amount` <= ".$purchase_maximum_amount.") ";
        }

        if($purchase_maximum_amount_service > $purchase_minimum_amount_service && is_numeric($purchase_maximum_amount_service) && is_numeric($purchase_minimum_amount_service)){
            $subQuery .= " AND (mio.`service_fee` >= ".$purchase_minimum_amount_service." AND mio.`service_fee` <= ".$purchase_maximum_amount_service.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (mio.`market_item_special_id` = '%{$searchQuery}%' OR  mio.`order_id` = '%{$searchQuery}%' OR p.`project_id` LIKE '%{$searchQuery}%' OR p.`title_id` LIKE '%{$searchQuery}%' OR p.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR p.`reference_id` LIKE '%{$searchQuery}%') " : null;
        
        $query = "SELECT (SELECT COUNT(*) FROM `project_reviews` WHERE `project_id` = p.`id`) AS `number_of_reviews`, mio.`id` AS `purchase_id`, mio.`has_order_started`, mio.`order_started_datetime`, mio.`is_requirement_submitted`, mio.`seller_id`, mio.`user_id`, mio.`market_item_id`, mio.`order_id`, mio.`total_amount`, mio.`service_fee`, p.`id` AS `main_project_id`, p.`project_id`, p.`title_id`, p.`datetime`, p.`mobile_number_country_number`, p.`title`, p.`description`, p.`payment_type`, p.`status`, p.`minimum_payment`, p.`maximum_payment`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address` AS `user_email_address`, usr.`mobile_number` AS `user_mobile_number`, usr.`mobile_number_country_number` AS `user_mobile_number_country_number` FROM `projects` p LEFT JOIN `users` usr ON p.`user_id` = usr.`id` LEFT JOIN `market_items_orders` mio ON mio.`order_id` = p.`reference_id` WHERE p.`id` IS NOT NULL AND `service_id_type` = 'service' {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(p.`id`) FROM `projects` p LEFT JOIN `users` usr ON p.`user_id` = usr.`id` LEFT JOIN `market_items_orders` mio ON mio.`order_id` = p.`reference_id` WHERE p.`id` IS NOT NULL AND `service_id_type` = 'service' {$subQuery}");
        
        error_log($query);

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
                $rdata["on_watchlist"] = $this->isOnWatchList($rdata["purchase_id"], "purchase") === true ? "yes" : "no";
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
    
    public function getListOfServices($idata = array(), $id = null, $return = false)
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
        $service_is_deleted = $this->filterData($idata["service_is_deleted"] ?? null);
        $service_category = $this->filterData($idata["service_category"] ?? null);
        $skills_list = $this->filterData($idata["skills_list"] ?? null);
        $service_country_posted_from = $this->filterData($idata["service_country_posted_from"] ?? null);
        $service_min_package_price = $this->filterData($idata["service_min_package_price"] ?? 0);
        $service_max_package_price = $this->filterData($idata["service_max_package_price"] ?? 0);

        $sortList = array(
            "id_asc" => " ORDER BY mi.`id` ASC",
            "id_desc" => " ORDER BY mi.`id` DESC",
            "sales_asc" => " ORDER BY `number_of_orders` ASC",
            "sales_desc" => " ORDER BY `number_of_orders` DESC",
            "views_asc" => " ORDER BY mi.`views` ASC",
            "views_desc" => " ORDER BY mi.`views` DESC",
            "clicks_asc" => " ORDER BY mi.`clicks` ASC",
            "clicks_desc" => " ORDER BY mi.`clicks` DESC",
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
            case "pending_approval":
                $subQuery .= " AND (mi.`status` IN('in_review')) ";
                break;
            case "approved":
                $subQuery .= " AND (mi.`status` IN('active')) ";
                break;
            case "picked":
                $subQuery .= " AND (mi.`is_picked` > 0) ";
                break;
            case "blocked":
                $subQuery .= " AND (mi.`status` IN('blocked')) ";
                break;
            case "cancelled":
                $subQuery .= " AND (mi.`status` IN('cancelled')) ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(mi.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(mi.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(mi.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($user_account_activation_status)){
            $subQuery .= " AND (usr.`is_activated` = '".$user_account_activation_status."') ";
        }

        if(is_numeric($user_account_block_status)){
            $subQuery .= " AND (usr.`is_blocked` = '".$user_account_block_status."') ";
        }

        if(is_numeric($service_is_deleted)){
            $subQuery .= " AND (mi.`is_deleted` = '".$service_is_deleted."') ";
        }

        $exps = explode(",", $skills_list);
        $sl = array();

        if(count($exps)){
            foreach($exps as $ex){
                if(!$this->isEmpty($ex)){
                    $sl[] = "'".$ex."'";
                }
            }
        }

        if(count($sl) > 0){
            $subQuery .= " AND (mi.`id` IN(SELECT `market_item_id` FROM `market_items_skills` WHERE `skill_id` IN(".implode(",", $sl).") AND `market_item_id` = mi.`id`)) ";
        }

        if(!$this->isEmpty($user_account_country)){
            $subQuery .= " AND (usr.`country_of_residence` = '".$user_account_country."') ";
        }

        if(!$this->isEmpty($service_category)){
            $subQuery .= " AND (mi.`category` = '".$service_category."') ";
        }

        if(!$this->isEmpty($service_country_posted_from)){
            $subQuery .= " AND (mi.`country_posted_from` = '".$service_country_posted_from."') ";
        }

        if($service_max_package_price > $service_min_package_price && is_numeric($service_max_package_price) && is_numeric($service_min_package_price)){
            $subQuery .= " AND ((SELECT `price` FROM `market_items_packages` WHERE `market_item_id` = mi.`id` AND `package_type` = 'basic') >= ".$service_min_package_price." AND (SELECT `price` FROM `market_items_packages` WHERE `market_item_id` = mi.`id` AND `package_type` = 'premium') <= ".$service_max_package_price.") ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (mi.`item_id` = '%{$searchQuery}%' OR mi.`title_id` LIKE '%{$searchQuery}%' OR mi.`item_id` LIKE '%{$searchQuery}%' OR mi.`title` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (mi.`id` = '{$id}')" : null;

        $query = "SELECT (SELECT COUNT(*) FROM `market_items_orders` WHERE `market_item_id` = mi.`id` AND `payment_status` = 'paid') AS `number_of_orders`, mi.`datetime`, mi.`id` AS `market_item_id`, mi.`is_picked`, mi.`user_id`, mi.`item_id`, mi.`title`, mi.`description`, mi.`title_id`, mi.`views`, mi.`clicks`, mi.`status`, usr.`email_address`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`is_blocked` AS `is_user_blocked` FROM `market_items` mi LEFT JOIN `users` usr ON mi.`user_id` = usr.`id` WHERE mi.`id` IS NOT NULL AND mi.`service_category` != 'artisan' {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(mi.`id`) FROM `market_items` mi LEFT JOIN `users` usr ON mi.`user_id` = usr.`id` WHERE mi.`id` IS NOT NULL AND mi.`service_category` != 'artisan' {$subQuery}");

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

    public function getSingleProjectHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfJobs($this->getListOfProjects(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleApplicationHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfApplications($this->getListOfProjectApplicants(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleServiceHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfServices($this->getListOfServices(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleAttachmentHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfProjectAttachments($this->getListOfProjectAttachments(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

}
