<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Request extends General
{

    protected $bg = "bg/";
    protected $conn = null;

    public function __construct()
    {
        $this->conn = $this->PDOConnection(DB[4]);
    }

    public function getListOfRequests($idata = array(), $id = null, $return = false)
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
            "id_asc" => " ORDER BY req.`request_id` ASC",
            "id_desc" => " ORDER BY req.`request_id` DESC",
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

        switch ($filterType) {
            case "custom_date":
                if (!$this->isEmpty($dateStart) && !$this->isEmpty($dateEnd)) {
                    $cdateStart = strtotime($dateStart) * 1000;
                    $cdateEnd = strtotime($dateEnd) * 1000;
                    if ($cdateStart <= $cdateEnd) {
                        $subQuery .= " AND (DATE(req.`datetime_posted`) BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "') ";
                    }
                }
                break;
            case "today":
                $subQuery .= " AND (DATE(req.`datetime_posted`) = '" . date("Y-m-d") . "') ";
                break;
            case "yesterday":
                $subQuery .= " AND (DATE(req.`datetime_posted`) = '" . date("Y-m-d", strtotime("-1 day")) . "') ";
                break;
        }

        $subQuery .= !$this->isEmpty($searchQuery) ? " AND (req.`request_special_id` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`user_id` LIKE '%{$searchQuery}%' OR usr.`firstname` LIKE '%{$searchQuery}%' OR usr.`surname` LIKE '%{$searchQuery}%' OR usr.`full_name` LIKE '%{$searchQuery}%' OR usr.`email` LIKE '%{$searchQuery}%' OR usr.`mobile_number` LIKE '%{$searchQuery}%' OR usr.`date_of_birth` LIKE '%{$searchQuery}%' OR usr.`username` LIKE '%{$searchQuery}%') " : null;
        $subQuery .= !$this->isEmpty($id) ? " AND (req.`request_id` = '{$id}')" : null;

        $query = "SELECT req.`request_id`, req.`current_status`, req.`request_special_id`, req.`datetime_posted`, req.`title`, req.`from_location`, req.`to_location`, usr.`user_id`, usr.`firstname`, usr.`surname`, usr.`full_name`, usr.`username`, usr.`is_verified`, usr.`datetime_joined`, usr.`email`, usr.`mobile_number` FROM `delivery_requests` req LEFT JOIN `users` usr ON req.`client_id` = usr.`user_id` WHERE usr.`id` IS NOT NULL {$subQuery}";
        $query_count = $this->countData($this->conn, "SELECT COUNT(req.`request_id`) FROM `delivery_requests` req LEFT JOIN `users` usr ON req.`client_id` = usr.`user_id` WHERE usr.`id` IS NOT NULL {$subQuery}");

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

    public function getDeliveryRequestPreviewDetails ($idata = array(), $return = false) {
        /**
         * @param Array $idata
         * @return Array
         */

        $request_id = $this->decryptString($idata["request_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT *, (SELECT `full_name` FROM `users` WHERE `user_id` = dr.`client_id`) AS `client_name`, (SELECT `mobile_number` FROM `users` WHERE `user_id` = dr.`client_id`) AS `client_mobile_number`, (SELECT `email` FROM `users` WHERE `user_id` = dr.`client_id`) AS `client_email` FROM `delivery_requests` dr WHERE `request_id` = :request_id;",
            array(
                "request_id" => $request_id
            ),
            true,
            true
        );

        if($result){
            $result["datetime_posted"] = $this->convertDatetimeToSimpleString($result["datetime_posted"]);
            $result["datetime_requested"] = $this->convertDatetimeToSimpleString($result["datetime_requested"]);

            $response["status"] = SUCCESS_CODE;
            $response["data"] = $result;
            if($return) return $response;
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Delivery request details not found.";
        if($return) return $response;
        die($this->encodeJSONdata($response));
    }

    public function deleteWaitingDeliveryRequest ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $request_id = $this->decryptString($idata["request_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "SELECT `photo` FROM `delivery_requests` WHERE `request_id` = :request_id AND `current_status` = 'waiting';",
            array(
                "request_id" => $request_id,
            ),
            true,
            true
        );

        if ($result) {
            //Delete the photo from here. May be connected to APIs
            $this->deleteFile($result["photo"]);

            //Delete delivery request
            $deleteQuery = $this->runQuery(
                $this->conn,
                "DELETE FROM `delivery_requests` WHERE `request_id` = :request_id;",
                array(
                    "request_id" => $request_id,
                )
            );

            if($deleteQuery){
                $response["status"] = SUCCESS_CODE;
                $response["data"] = "Request has been deleted successfully.";
                die($this->encodeJSONdata($response));
            }

            $response["status"] = ERROR_CODE;
            $response["data"] = "Failed to delete delivery request.";
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Delivery request not found or it don't have the conditions to get it deleted.";
        die($this->encodeJSONdata($response));
    }

    public function acceptDeliveryRequest ($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $request_id = $this->decryptString($idata["request_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `delivery_requests` SET `current_status` = 'accepted' WHERE `request_id` = :request_id;",
            array(
                "request_id" => $request_id
            )
        );

        if($result){
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Delivery request has been successfully accepted.";
            if($return) return $response;
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Failed to accept delivery request.";
        if($return) return $response;
        die($this->encodeJSONdata($response));
    }

    public function declineDeliveryRequest($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $request_id = $this->decryptString($idata["request_id"] ?? null);

        if (!$this->conn) {
            $this->throwConnectionError();
        }

        $result = $this->runQuery(
            $this->conn,
            "UPDATE `delivery_requests` SET `current_status` = 'declined' WHERE `request_id` = :request_id;",
            array(
                "request_id" => $request_id
            )
        );

        if($result){
            $response["status"] = SUCCESS_CODE;
            $response["data"] = "Delivery request has been successfully declined.";
            if($return) return $response;
            die($this->encodeJSONdata($response));
        }

        $response["status"] = ERROR_CODE;
        $response["data"] = "Failed to decline delivery request.";
        if($return) return $response;
        die($this->encodeJSONdata($response));
    }

    public function getSingleDeliveryRequestHTMLData($idata = array()) {
        /**
         * @param Array $idata
         * @return Array
         */

        $id = $this->decryptString($idata["id"] ?? null, true);
        
        $response["status"] = SUCCESS_CODE;
        $response["data"] = $this->FactoryClass()->Views()->renderListOfRequests($this->getListOfRequests(array(), $id, true), null, null, null, 0, true);
        return $this->encodeJSONdata($response);
    }

}
