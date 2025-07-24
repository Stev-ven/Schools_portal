<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Newsletter extends General
{

    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfNewsletterSubscribers($idata = array())
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
        $country = $this->filterData($idata["country"] ?? null);

        $sortList = array(
            "id_asc" => " ORDER BY nw.`id` ASC",
            "id_desc" => " ORDER BY nw.`id` DESC",
            "email_asc" => " ORDER BY nw.`email_address` ASC",
            "email_desc" => " ORDER BY nw.`email_address` DESC"
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
                        $subQuery .= " AND (DATE(nw.`datetime_subscribed`) BETWEEN '".$dateStart."' AND '".$dateEnd."') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(nw.`datetime_subscribed`) = '".date("Y-m-d")."') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(nw.`datetime_subscribed`) = '".date("Y-m-d", strtotime("-1 day"))."') ";
                break;
        }

        if(!$this->isEmpty($country)){
            $subQuery .= " AND (nw.`country_code` = '".$country."') ";
        }

        $subQuery .= !empty($searchQuery) ? " AND (nw.`email_address` LIKE '%{$searchQuery}%' OR nw.`ip_address` LIKE '%{$searchQuery}%') " : null;

        $query = "SELECT nw.`id`, nw.`datetime_subscribed`, nw.`country_name`, nw.`country_code`, nw.`ip_address`, nw.`email_address` FROM `newsletter_subscriptions` nw WHERE nw.`id`IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(*) FROM `newsletter_subscriptions` nw WHERE nw.`id`IS NOT NULL {$subQuery}");

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
