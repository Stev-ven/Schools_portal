<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class More extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getDashboardCounts($idata = array()){
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);

        $subQuery = null;

        if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
            $cdateStart = strtotime($dateStart) * 1000;
            $cdateEnd = strtotime($dateEnd) * 1000;
            if($cdateStart <= $cdateEnd){
                $subQuery .= " '".$dateStart."' AND '".$dateEnd."' ";
            }
        }

        return array();
    }

    public function getListOfMessages($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"]) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $message_subject = $this->filterData($idata["message_subject"] ?? null);
        $message_sender_email = $this->filterData($idata["message_sender_email"] ?? null);
        $message_receiver_email = $this->filterData($idata["message_receiver_email"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY m.`id` ASC",
            "id_desc" => " ORDER BY m.`id` DESC",
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
                        $subQuery .= " AND (DATE(m.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(m.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(m.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        switch($message_subject){
            case "project_message":
            case "service_item":
                $subQuery .= " AND (m.`message_subject` = '".$message_subject."') ";
                break;
            case "others":
                $subQuery .= " AND (m.`message_subject` NOT IN('project_message', 'service_item')) ";
                break;
        }

        if(!$this->isEmpty($message_sender_email) && !$this->isEmpty($message_receiver_email)){
            $message_receiver_id = $this->getUserIdWithEmail($message_receiver_email);
            $message_sender_id = $this->getUserIdWithEmail($message_sender_email);
            $subQuery .= " AND ((m.`user_id` = '".$message_receiver_id."' AND m.`sender` = '".$message_sender_id."') OR (m.`user_id` = '".$message_sender_id."' AND m.`sender` = '".$message_receiver_id."')) ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (m.`message` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT usr.`account_type`, usr.`profile_photo`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address`, m.`id`, m.`user_id`, (SELECT `email_address` FROM `users` WHERE `id` = m.`sender`) AS `sender_email`, (SELECT `is_blocked` FROM `users` WHERE `id` = m.`sender`) AS `sender_user_block_status`, m.`sender`, (SELECT `email_address` FROM `users` WHERE `id` = m.`user_id`) AS `receiver_email`, (SELECT `is_blocked` FROM `users` WHERE `id` = m.`user_id`) AS `receiver_user_block_status`, m.`receiver`, (SELECT `full_name` FROM `users` WHERE `id` = m.`sender`) AS `sender_name`, (SELECT `full_name` FROM `users` WHERE `id` = m.`user_id`) AS `receiver_name`, m.`datetime`, m.`seen`, m.`message`, m.`message_subject` FROM `messages` m LEFT JOIN `users` usr ON m.`user_id` = usr.`id` WHERE usr.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(m.`id`) FROM `messages` m LEFT JOIN `users` usr ON m.`user_id` = usr.`id` WHERE usr.`id` IS NOT NULL {$subQuery}");

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
                $blockStatus = $this->FactoryClass()->User()->isUserBlocked(array(
                    "user_id" => $rdata["user_id"],
                    "related_user_id" => $rdata["sender"],
                    "block_type" => "message",
                ), true);

                error_log($rdata["user_id"]." : ".$rdata["sender"]);

                $rdata["block_status"] = $blockStatus === true ? "block" : "unblock";

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

    public function getListOfQuestions($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"]) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $blocked = $this->filterData($idata["blocked"] ?? null);
        $category = $this->filterData($idata["category"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY d.`discussion_id` ASC",
            "id_desc" => " ORDER BY d.`discussion_id` DESC",
            "title_asc" => " ORDER BY d.`title` ASC",
            "title_desc" => " ORDER BY d.`title` DESC",
            "views_asc" => " ORDER BY d.`views` ASC",
            "views_desc" => " ORDER BY d.`views` DESC",
            "responses_asc" => " ORDER BY `number_of_responses` ASC",
            "responses_desc" => " ORDER BY `number_of_responses` DESC",
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
                        $subQuery .= " AND (DATE(d.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(d.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(d.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($blocked)){
            $subQuery .= " AND (d.`is_blocked` = '".$blocked."') ";
        }

        if(!$this->isEmpty($category)){
            $subQuery .= " AND (d.`category` = '".$category."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (d.`special_record_id` LIKE '%{$searchQuery}%' OR d.`title` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT (SELECT COUNT(*) FROM `discussion_answers` WHERE `discussion_id` = d.`discussion_id`) AS `number_of_responses`, usr.`is_worker_at_cedijob`, usr.`user_id`, usr.`account_type`, usr.`profile_photo`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address`, d.`special_record_id`, d.`discussion_id`, d.`user_id`, d.`title`, d.`datetime_added`, d.`datetime_updated`, d.`category`, d.`content_title`, d.`views`, d.`is_blocked` FROM `discussions` d LEFT JOIN `users` usr ON d.`user_id` = usr.`id` WHERE d.`discussion_id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(d.`discussion_id`) FROM `discussions` d LEFT JOIN `users` usr ON d.`user_id` = usr.`id` WHERE d.`discussion_id` IS NOT NULL {$subQuery}");

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

    public function getListOfAnswers($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"]) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);

        $sortList = array(
            "id_asc" => " ORDER BY d.`answer_id` ASC",
            "id_desc" => " ORDER BY d.`answer_id` DESC",
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
                        $subQuery .= " AND (DATE(d.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(d.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(d.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }
        
        $subQuery .= !empty($searchQuery) ? " AND (d.`special_record_id` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT usr.`is_worker_at_cedijob`, usr.`user_id`, usr.`account_type`, usr.`profile_photo`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address`, d.`special_record_id`, d.`discussion_id`, d.`answer_id`, d.`user_id`, d.`description`, d.`datetime_added`, d.`datetime_updated`, dq.`category`, dq.`content_title` FROM `discussion_answers` d LEFT JOIN `discussions` dq ON d.`discussion_id` = dq.`discussion_id` LEFT JOIN `users` usr ON d.`user_id` = usr.`id` WHERE d.`answer_id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(d.`answer_id`) FROM `discussion_answers` d LEFT JOIN `users` usr ON d.`user_id` = usr.`id` WHERE d.`answer_id` IS NOT NULL {$subQuery}");

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

    public function getListOfReviews($idata = array(), $id = null, $return = false)
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 25);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"]) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $reply = $this->filterData($idata["reply"] ?? null);
        $rating = $this->filterData($idata["rating"] ?? null);
        $category = $this->filterData($idata["category"] ?? null);
        $country = $this->filterData($idata["country"] ?? null);
        $block_status = $this->filterData($idata["block_status"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY pr.`id` ASC",
            "id_desc" => " ORDER BY pr.`id` DESC",
            "relevance_asc" => " ORDER BY pr.`relevant` ASC",
            "relevance_desc" => " ORDER BY pr.`relevant` DESC",
            "rating_asc" => " ORDER BY pr.`rating` ASC",
            "rating_desc" => " ORDER BY pr.`rating` DESC",
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
                        $subQuery .= " AND (DATE(pr.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(pr.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(pr.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($rating)){
            $subQuery .= " AND (pr.`rating` = '".$rating."') ";
        }

        switch($reply){
            case "replied":
                $subQuery .= " AND (pr.`reply` IS NOT NULL OR pr.`reply` != '') ";
                break;
            case "not_replied":
                $subQuery .= " AND (pr.`reply` IS NULL OR pr.`reply` = '') ";
                break;
        }

        switch($block_status){
            case 1:
                $subQuery .= " AND (pr.`is_blocked` > 0) ";
                break;
            case 0:
                $subQuery .= " AND (pr.`is_blocked` < 0) ";
                break;
        }

        if(!$this->isEmpty($category)){
            $subQuery .= " AND (pr.`reviewed_category` = '".$category."') ";
        }

        if(!$this->isEmpty($country)){
            $subQuery .= " AND (pr.`reviewed_from` = '".$country."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%' OR pr.`project_id` = '{$searchQuery}' OR pr.`special_record_id` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (pr.`id` = '{$id}')" : null;

        $query = "SELECT usr.`user_id`, usr.`account_type`, usr.`profile_photo`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address`, pr.`special_record_id`, pr.`is_blocked`, pr.`id`, pr.`reviewed_by`, pr.`user_id` AS `reviewed`, pr.`reviewed_category`, pr.`review`, pr.`rating`, pr.`datetime`, pr.`reply`, pr.`relevant`, p.`project_id` FROM `project_reviews` pr LEFT JOIN `users` usr ON pr.`reviewed_by` = usr.`id` LEFT JOIN `projects` p ON pr.`project_id` = p.`id` WHERE pr.`reviewed_by` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(pr.`id`) FROM `project_reviews` pr LEFT JOIN `users` usr ON pr.`reviewed_by` = usr.`id` WHERE pr.`reviewed_by` IS NOT NULL {$subQuery}");

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

    public function getListOfLeads($idata = array(), $id = null, $return = false)
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
        $is_business = $this->filterData($idata["is_business"] ?? null);
        $next_expected_call_date_start = $this->filterData($idata["next_expected_call_date_start"] ?? null);
        $next_expected_call_date_end = $this->filterData($idata["next_expected_call_date_end"] ?? null);
        $country = $this->filterData($idata["country"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY l.`id` ASC",
            "id_desc" => " ORDER BY l.`id` DESC",
            "next_expected_call_date_asc" => " ORDER BY l.`next_expected_call_date` ASC",
            "next_expected_call_date_desc" => " ORDER BY l.`next_expected_call_date` DESC",
            "full_name_asc" => " ORDER BY l.`full_name` ASC",
            "full_name_desc" => " ORDER BY l.`full_name` DESC",
            "email_asc" => " ORDER BY l.`email_address` ASC",
            "email_desc" => " ORDER BY l.`email_address` DESC",
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

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(l.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(l.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(l.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($is_business)){
            $subQuery .= " AND (l.`is_business` = '".$is_business."') ";
        }

        if(!$this->isEmpty($next_expected_call_date_start) && !$this->isEmpty($next_expected_call_date_end)){
            $cdateStartTwo = strtotime($next_expected_call_date_start) * 1000;
            $cdateEndTwo = strtotime($next_expected_call_date_end) * 1000;
            if($cdateStartTwo <= $cdateEndTwo){
                $subQuery .= " AND (DATE(l.`next_expected_call_date`) BETWEEN '".$next_expected_call_date_start."' AND '".$next_expected_call_date_end."') ";
            }
        }

        $subQuery .= !empty($searchQuery) ? " AND (l.`email_address` LIKE '%{$searchQuery}%' OR l.`full_name` LIKE '%{$searchQuery}%' OR l.`mobile_number` LIKE '%{$searchQuery}%' OR l.`company` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (l.`id` = '{$id}')" : null;

        $query = "SELECT l.`id`, l.`note`, l.`full_name`, l.`mobile_number`, l.`email_address`, l.`next_expected_call_date`, l.`company`, l.`is_business`, l.`datetime_added`, l.`last_contacted` FROM `leads` l WHERE l.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `leads` l WHERE l.`id` IS NOT NULL {$subQuery}");
        
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

    public function getListOfNotices($idata = array(), $id = null, $return = false)
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

        $sortList = array(
            "id_asc" => " ORDER BY n.`id` ASC",
            "id_desc" => " ORDER BY n.`id` DESC",
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

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(n.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(n.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(n.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(!$this->isEmpty($country)){
            $subQuery .= " AND (n.`country` = '".$country."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (n.`title` LIKE '%{$searchQuery}%' OR n.`country` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (n.`id` = '{$id}')" : null;
        
        $query = "SELECT n.`can_close`, n.`id`, n.`title`, n.`note`, n.`country`, n.`expiry_date`, n.`datetime_added` FROM `notices` n WHERE n.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `notices` n WHERE n.`id` IS NOT NULL {$subQuery}");
        
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

    public function getListOfCompanyDocuments($idata = array(), $id = null, $return = false)
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
            "id_asc" => " ORDER BY cd.`id` ASC",
            "id_desc" => " ORDER BY cd.`id` DESC",
            "document_title_asc" => " ORDER BY cd.`document_title` ASC",
            "document_title_desc" => " ORDER BY cd.`document_title` DESC",
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

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(cd.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(cd.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(cd.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (cd.`document_title` LIKE '%{$searchQuery}%' OR cd.`document_description` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (cd.`id` = '{$id}')" : null;

        $query = "SELECT cd.`id`, cd.`document_title`, cd.`document_description`, cd.`document_filename`, cd.`uploaded_by`, (SELECT `full_name` FROM `users_admin` WHERE `id` = cd.`uploaded_by`) AS `uploader_name`, cd.`datetime_added` FROM `company_documents` cd WHERE cd.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `company_documents` cd WHERE cd.`id` IS NOT NULL {$subQuery}");
        
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

    public function getSingleLeadHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfLeads($this->getListOfLeads(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleCompanyDocumentsHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfDocuments($this->getListOfCompanyDocuments(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getListOfSkills($idata = array(), $id = null, $return = false)
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $viewType = $this->filterData($idata["viewType"] ?? null);
        $resultPerPage = $this->filterData($idata["resultPerPage"] ?? 100);
        $filterType = $this->filterData($idata["filterType"] ?? null);
        $sortBy = $this->filterData(!$this->isEmpty($idata["sortBy"] ?? null) ? $idata["sortBy"] : "id_desc");
        $dateStart = $this->filterData($idata["dateStart"] ?? null);
        $dateEnd = $this->filterData($idata["dateEnd"] ?? null);
        $searchQuery = $this->filterData($idata["searchQuery"] ?? null);
        $pageNum = $this->filterData($idata["pageNum"] ?? 1);
        $is_hidden = $this->filterData($idata["is_hidden"] ?? null);
        $skill_category = $this->filterData($idata["skill_category"] ?? 0);
        $skill_id = $this->filterData($idata["skill_id"] ?? 0);

        $sortList = array(
            "id_asc" => " ORDER BY s.`skill_id` ASC",
            "id_desc" => " ORDER BY s.`skill_id` DESC",
            "skill_name_asc" => " ORDER BY s.`skill_name` ASC",
            "skill_name_desc" => " ORDER BY s.`skill_name` DESC",
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

        if(is_numeric($is_hidden)){
            switch($is_hidden){
                case 1:
                    $subQuery .= " AND (s.`is_hidden` > 0) ";
                    break;
                case 0:
                    $subQuery .= " AND (s.`is_hidden` < 1) ";
                    break;
            }
        }
        
        if(!$this->isEmpty($skill_category)){
            $subQuery .= " AND (s.`skill_category` IN('".$skill_category."')) ";
        }

        if(!$this->isEmpty($skill_id)){
            $subQuery .= " AND (s.`skill_id` IN('".$skill_id."')) ";
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(s.`is_new`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(s.`is_new`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(s.`is_new`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (s.`skill_name` LIKE '%{$searchQuery}%' OR s.`skill_alias` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (s.`skill_id` = '{$id}')" : null;

        $query = "SELECT s.`is_hidden`, s.`skill_id`, s.`skill_name`, s.`skill_alias`, s.`skill_reference`, s.`skill_category`, s.`is_new` FROM `skills` s WHERE s.`skill_id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `skills` s WHERE s.`skill_id` IS NOT NULL {$subQuery}");
        
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

    public function getSingleSkillHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfSkills($this->getListOfSkills(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getListOfVideos($idata = array(), $id = null, $return = false)
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
        $category = $this->filterData($idata["category"] ?? 0);
        $notify_users_on_signup = $this->filterData($idata["notify_users_on_signup"] ?? 0);

        $sortList = array(
            "id_asc" => " ORDER BY v.`id` ASC",
            "id_desc" => " ORDER BY v.`id` DESC",
            "title_asc" => " ORDER BY v.`title` ASC",
            "title_desc" => " ORDER BY v.`title` DESC",
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
        
        if(!$this->isEmpty($category)){
            $subQuery .= " AND (v.`account_type` IN('".$category."')) ";
        }
        
        if($notify_users_on_signup > 0){
            $subQuery .= " AND (v.`notify_users_on_signup` = 1) ";
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(v.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(v.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(v.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        $subQuery .= !empty($searchQuery) ? " AND (v.`title` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (v.`id` = '{$id}')" : null;

        $query = "SELECT v.`all_users_notified`, v.`notify_users_on_signup`, v.`id`, v.`video_id`, v.`status`, v.`url`, v.`title`, v.`duration`, v.`description`, v.`cover_photo`, v.`account_type`, v.`datetime` FROM `videos` v WHERE v.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `videos` v WHERE v.`id` IS NOT NULL {$subQuery}");
        
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

    public function getSingleVideoHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfVideos($this->getListOfVideos(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleReviewHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfReviews($this->getListOfReviews(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

    public function getSingleNoticeHTMLData($idata = array())
    {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfNotices($this->getListOfNotices(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

}
