<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Reports extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfReports($idata = array())
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
        $country_reported_from = $this->filterData($idata["country_reported_from"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY ur.`id` ASC",
            "id_desc" => " ORDER BY ur.`id` DESC",
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
            case "pending_reports":
                $subQuery .= " AND (ur.`status` IN('pending')) ";
                break;
            case "responded_reports":
                $subQuery .= " AND (ur.`status` IN('responded')) ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(ur.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(ur.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(ur.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(!$this->isEmpty($country_reported_from)){
            $subQuery .= " AND (ur.`country_code` = '".$country_reported_from."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (`report_email` LIKE '%{$searchText}%' OR ur.`user_id` IN (SELECT `id` FROM `users` WHERE `user_name` LIKE '%$searchText%' OR `first_name` LIKE '%$searchText%' OR `last_name` LIKE '%$searchText%' OR `email_address` LIKE '%$searchText%' OR `mobile_number` LIKE '%$searchText%' OR `user_id` LIKE '%$searchText%')) " : null;

        $query = "SELECT ur.*, (SELECT `full_name` FROM `users` WHERE `id` = ur.`user_id`) AS `full_name`, (SELECT `user_name` FROM `users` WHERE `id` = ur.`user_id`) AS `user_name`, (SELECT `email_address` FROM `users` WHERE `id` = ur.`user_id`) AS `email_address`, (SELECT `mobile_number` FROM `users` WHERE `id` = ur.`user_id`) AS `mobile_number`, (SELECT `mobile_number_country_number` FROM `users` WHERE `id` = ur.`user_id`) AS `mobile_number_country_number` FROM `users_reports` ur WHERE ur.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(ur.`id`) FROM `users_reports` ur WHERE ur.`id` IS NOT NULL {$subQuery}");
        
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

    public function getListOfSiteMessages($idata = array())
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
        $country_reported_from = $this->filterData($idata["country_sent_from"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY sm.`id` ASC",
            "id_desc" => " ORDER BY sm.`id` DESC",
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
            case "pending_messages":
                $subQuery .= " AND (sm.`answered` IN('no')) ";
                break;
            case "responded_messages":
                $subQuery .= " AND (sm.`answered` IN('yes')) ";
                break;
        }

        switch($filterType){
            case "custom_date":
                if(!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)){
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if($cdateStart <= $cdateEnd){
                        $subQuery .= " AND (DATE(sm.`datetime`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(sm.`datetime`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(sm.`datetime`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(!$this->isEmpty($country_reported_from)){
            $subQuery .= " AND (sm.`country_code` = '".$country_reported_from."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (sm.`email` LIKE '%{$searchText}%' OR sm.`name` LIKE '%{$searchText}%') " : null;

        $query = "SELECT sm.* FROM `site_messages` sm WHERE sm.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `site_messages` sm WHERE sm.`id` IS NOT NULL {$subQuery}");
        
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

}
