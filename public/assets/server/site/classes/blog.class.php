<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Blog extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfBlog($idata = array())
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
            "id_asc" => " ORDER BY b.`id` ASC",
            "id_desc" => " ORDER BY b.`id` DESC",
            "title_asc" => " ORDER BY b.`title` ASC",
            "title_desc" => " ORDER BY b.`title` DESC",
            "views_asc" => " ORDER BY b.`views` ASC",
            "views_desc" => " ORDER BY b.`views` DESC",
            "first_name_asc" => " ORDER BY usr.`first_name` ASC",
            "first_name_desc" => " ORDER BY usr.`first_name` DESC",
            "last_name_asc" => " ORDER BY usr.`last_name` ASC",
            "last_name_desc" => " ORDER BY usr.`last_name` DESC",
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
                        $subQuery .= " AND (DATE(b.`datetime_added`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(b.`datetime_added`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(b.`datetime_added`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(is_numeric($blocked)){
            $subQuery .= " AND (b.`is_blocked` = '".$blocked."') ";
        }

        if(!$this->isEmpty($category)){
            $subQuery .= " AND (b.`category` = '".$category."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (b.`special_record_id` LIKE '%{$searchQuery}%' OR b.`title` LIKE '%{$searchQuery}%' OR b.`headline` LIKE '%{$searchQuery}%' OR usr.`email_address` LIKE '%{$searchQuery}%' OR usr.`first_name` LIKE '%{$searchQuery}%' OR usr.`last_name` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`user_name` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT usr.`is_blog_writer`, usr.`account_type`, usr.`profile_photo`, usr.`first_name`, usr.`last_name`, usr.`full_name`, usr.`user_name`, usr.`email_address`, b.`special_record_id`, b.`id`, b.`user_id`, b.`title`, b.`headline`, b.`content_title`, b.`datetime_added`, b.`datetime_updated`, b.`category`, b.`views`, b.`is_blocked`, b.`country` FROM `blog` b LEFT JOIN `users` usr ON b.`user_id` = usr.`id` WHERE b.`user_id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(b.`user_id`) FROM `blog` b LEFT JOIN `users` usr ON b.`user_id` = usr.`id` WHERE b.`user_id` IS NOT NULL {$subQuery}");

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
                $rdata["content_link"] = USER_PORTAL_DOMAIN."blog/".$rdata["category"]."/".$rdata["content_title"];
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
