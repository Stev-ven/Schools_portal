<?php
    class Courier extends General{

        public $returnMode = null;

        public function getCourierBikeList($idata = array()){
            $response = array();

            if(IS_LOGGED_IN == "no"){
                if($this->returnMode == "return"){
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Please login to continue.";
                    return($this->encodeJSONdata($response));
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "Please login to continue.";
                    die($this->encodeJSONdata($response));
                }
            }
            
            $conn = $this->dbConnect(DB_NAME);
            
            if(!$conn){
                if($this->returnMode == "return"){
                    $response["status"] = DB_CONNECTION_ERROR_CODE;
                    $response["data"] = "Failed to query the server. Try again.";
                    return($this->encodeJSONdata($response));
                }
                else{
                    $response["status"] = DB_CONNECTION_ERROR_CODE;
                    $response["data"] = "Failed to query the server. Try again.";
                    die($this->encodeJSONdata($response));
                }
            }

            $provider_id = PROVIDER_ID;
            $search_by = isset($idata["search_by"]) ? $idata["search_by"] : null;
            $sort = isset($idata["sort"]) ? $idata["sort"] : null;
            $filter_type = isset($idata["filterType"]) ? $idata["filterType"] : "";
            $page_num = isset($idata["pageNum"]) ? $idata["pageNum"] : 1;
            $resultPerPage = isset($idata["resultPerPage"]) ? $idata["resultPerPage"] : 25;
            $search_text = isset($idata["searchText"]) ? $this->dbEscF($idata["searchText"]) : null;

            switch($sort){
                case "rider_name_asc":
                    $sorting = " `rider_name` ASC ";
                    break;
                case "rider_name_desc":
                    $sorting = " `rider_name` DESC ";
                    break;
                case "zone_price_asc":
                    $sorting = " `zone_route_price` ASC ";
                    break;
                case "zone_price_desc":
                    $sorting = " `zone_route_price` DESC ";
                    break;
                case "zone_origin_asc":
                    $sorting = " `zone_origin` ASC ";
                    break;
                case "zone_origin_desc":
                    $sorting = " `zone_origin` DESC ";
                    break;
                case "zone_destination_asc":
                    $sorting = " `zone_destination` ASC ";
                    break;
                case "zone_destination_desc":
                    $sorting = " `zone_destination` DESC ";
                    break;
                default:
                    $sorting = " `id` DESC ";
            }

            $subQuery = null;
            if(!empty($search_text)){
                switch($search_by){
                    case "rider_name":
                        $subQuery = !empty($search_text) ? " AND (`rider_name` LIKE '%{$search_text}%') " : "";
                        break;
                    case "zone_origin":
                        $subQuery = !empty($search_text) ? " AND (`zone_origin` LIKE '%{$search_text}%') " : "";
                        break;
                    case "zone_destination":
                        $subQuery = !empty($search_text) ? " AND (`zone_destination` LIKE '%{$search_text}%') " : "";
                        break;
                    case "courier_id":
                        $subQuery = !empty($search_text) ? " AND (`special_id` LIKE '%{$search_text}%') " : "";
                        break;
                    default:
                        $subQuery = !empty($search_text) ? " AND (`rider_name` LIKE '%{$search_text}%') " : "";
                }
            }
            
            switch($filter_type){
                case "active":
                    $query = "SELECT *, (SELECT COUNT(*) FROM `courier_deliveries` WHERE `rider_id` = cb.id) AS `number_of_deliveries` FROM `courier_bikes` cb WHERE `provider_id` = '{$provider_id}' AND `active_status` = 1 AND `deleted` = 0 {$subQuery}";
                    $query_count = "SELECT COUNT(*) FROM `courier_bikes` WHERE `provider_id` = '{$provider_id}' AND `active_status` = 1 AND `deleted` = 0 {$subQuery}";
                    break;
                case "inactive":
                    $query = "SELECT *, (SELECT COUNT(*) FROM `courier_deliveries` WHERE `rider_id` = cb.id) AS `number_of_deliveries` FROM `courier_bikes` cb WHERE `provider_id` = '{$provider_id}' AND `active_status` = 0 AND `deleted` = 0 {$subQuery}";
                    $query_count = "SELECT COUNT(*) FROM `courier_bikes` WHERE `provider_id` = '{$provider_id}' AND `active_status` = 0 AND `deleted` = 0 {$subQuery}";
                    break;
                default:
                    $query = "SELECT *, (SELECT COUNT(*) FROM `courier_deliveries` WHERE `rider_id` = cb.id) AS `number_of_deliveries` FROM `courier_bikes` cb WHERE `provider_id` = '{$provider_id}' AND `active_status` IN(1, 0) AND `deleted` = 0 {$subQuery}";
                    $query_count = "SELECT COUNT(*) FROM `courier_bikes` WHERE `provider_id` = '{$provider_id}' AND `active_status` IN(1, 0) AND `deleted` = 0 {$subQuery}";
            }
            
            $count = $this->countData($conn, $query_count);

			$pagerows = $resultPerPage;
			$totalRows_rsodest = $count;

			$total_pages = $lastpage = ceil($totalRows_rsodest / $pagerows);
			$pagenum = $page = $page_num;

			if (!(isset($pagenum))) {
				$pagenum = 1;
			}
			if ($pagenum < 1) {  
				$pagenum = 1;
			} 
			else if ($pagenum > $total_pages) {
				$pagenum = $total_pages; 
			}

			$start_row = (($pagenum - 1) * $pagerows) + 1;
			$end_row = $start_row + $pagerows;

			if($end_row > $totalRows_rsodest){
				$end_row = $totalRows_rsodest;
			}

			$result_data = $this->selectData($conn, $query.' ORDER BY '.$sorting.' LIMIT '.abs(($pagenum - 1) * $pagerows).','.$pagerows);

			if($totalRows_rsodest > 0){
				$result = $result_data;
			}
			else{
				$start_row = 0;
            }
			
            $pagination = array();
            $range = 2;
	        $r2 = 2;
            
			if($page != 1){
				$prev = $page - 1;
				$pagination[] =  array(
					"text" => 'previous',
					"page" => $prev,
					"active" => ($prev == $pagenum) ? "yes" : "no"
				);
            }
            
            for($x = ($page - $range);$x < ($page + $range) + $r2;$x++){
                if($x > 0 && $x <= $lastpage){
                    if($x == $page){
                        $pagination[] =  array(
                            "text" => $x,
                            "page" => $x,
                            "active" => ($x == $pagenum) ? "yes" : "no"
                        );
                    }
                    else{
                        $pagination[] =  array(
                            "text" => $x,
                            "page" => $x,
                            "active" => ($x == $pagenum) ? "yes" : "no"
                        );
                    }
                }
            }
			
			if($page != $lastpage){
				$next = $page + 1;
				$pagination[] =  array(
					"text" => "next",
					"page" => $next,
					"active" => ($next == $pagenum) ? "yes" : "no"
				);
			}
			
			if($lastpage == 1 || $lastpage == 0){
				$pagination = array();
			}

            if($this->returnMode == "return"){
                $response["status"] = SUCCESS_CODE;
                $response["data"] = $result;
                $response["pagination"] = $pagination;
                $response["start_row"] = $start_row;
                $response["end_row"] = $end_row;
                $response["total_records"] = $totalRows_rsodest;
                return($this->encodeJSONdata($response));
            }
            else{
                $response["status"] = SUCCESS_CODE;
                $response["data"] = $result;
                $response["pagination"] = $pagination;
                $response["start_row"] = $start_row;
                $response["end_row"] = $end_row;
                $response["total_records"] = $totalRows_rsodest;
                die($this->encodeJSONdata($response));
            }
        }

        public function manageCourier($idata = array()){
            $response = array();
            $provider_id = PROVIDER_ID;
            $rider_id = isset($idata["id"]) ? $this->filterData($idata["id"]) : null;
            $service_id = isset($idata["service_id"]) ? $this->filterData($idata["service_id"]) : null;
            $zone_origin = isset($idata["zone_origin"]) ? $this->filterData($idata["zone_origin"]) : 1;
            $zone_destination = isset($idata["zone_destination"]) ? $this->filterData($idata["zone_destination"]) : null;
            $zone_route_price = isset($idata["zone_route_price"]) ? $this->filterData($idata["zone_route_price"]) : null;
            $rider_name = isset($idata["rider_name"]) ? $this->filterData($idata["rider_name"]) : null;
            $rider_telephone_no = isset($idata["rider_telephone_no"]) ? $this->filterData($idata["rider_telephone_no"]) : null;
            $active_status = isset($idata["active_status"]) ? $this->filterData($idata["active_status"]) : null;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "manage_courier",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "id" => $rider_id,
                    "service_id" => $service_id,
                    "zone_origin" => $zone_origin,
                    "zone_destination" => $zone_destination,
                    "zone_route_price" => $zone_route_price,
                    "rider_name" => $rider_name,
                    "rider_telephone_no" => $rider_telephone_no,
                    "active_status" => $active_status,
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function deleteRider($idata = array()){
            $response = array();
            $rider_id = isset($idata["id"]) ? $this->filterData($idata["id"]) : null;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "remove_courier",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "id" => $rider_id
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function assignOrderToCourierBike($idata = array(), $direct = false){
            $response = array();
            $rider_id = isset($idata["id"]) ? $this->filterData($idata["id"]) : null;
            $order_request_id = isset($idata["order_request_id"]) ? $this->filterData($idata["order_request_id"]) : null;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "assign_courier_to_bike",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "id" => $rider_id,
                    "order_request_id" => $order_request_id
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function checkOrderPrivileges($order_request_id, $rider_id, $provider_id){
            $conn = $this->dbConnect(DB_NAME);
            
            if(!$conn){
                $response["status"] = DB_CONNECTION_ERROR_CODE;
                $response["data"] = "Failed to query the server. Try again.";
                die($this->encodeJSONdata($response));
            }

            //Check whether order is already assigned to another person
            $result = $this->countData($conn, "SELECT COUNT(*) FROM `orders` WHERE `order_request_id` = '{$order_request_id}' AND `service_provider_id` = '{$provider_id}' AND `delivery_status` = 'pending' LIMIT 1");
            if($result < 1){
                $response["status"] = ERROR_CODE;
                $response["data"] = "Order not found, has not been processed yet or is not ready for delivery.";
                die($this->encodeJSONdata($response));
            }

            //Check on the rider
            $result = $this->selectSingleData($conn, "SELECT `active_status` FROM  `courier_bikes` WHERE `id` = '{$rider_id}' AND `provider_id` = '{$provider_id}' AND `deleted` = 0 LIMIT 1");
            if($result){
                if($this->filterData($result["active_status"]) < 1){
                    $response["status"] = ERROR_CODE;
                    $response["data"] = "The courier you have selected is currently inactive.";
                    die($this->encodeJSONdata($response));
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "The courier you have selected was not found.";
                die($this->encodeJSONdata($response));
            }

            //Check whether order is already assigned to a courier
            $result = $this->countData($conn, "SELECT COUNT(*) FROM `courier_deliveries` WHERE `requested_order_id` = '{$order_request_id}' LIMIT 1");
            if($result > 0){
                $response["status"] = ERROR_CODE;
                $response["data"] = "Order delivery has already been assigned a courier.";
                die($this->encodeJSONdata($response));
            }

        }

        public function findCourierBikes($idata = array(), $direct = false){
            $response = array();
            $search_text = isset($idata["q"]) ? $this->filterData($idata["q"]) : null;
            $search_by = isset($idata["search_by"]) ? $this->filterData($idata["search_by"]) : null;
            $offset = isset($idata["offset"]) ? $this->filterData($idata["offset"]) : 0;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "find_courier_bikes",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "q" => $search_text,
                    "search_by" => $search_by,
                    "offset" => $offset
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function getAllCourierServices($idata = array(), $direct = false){
            if(IS_LOGGED_IN == "no"){
                $response["status"] = ERROR_CODE;
                $response["data"] = "Please login to continue.";
                if($direct){
                    return $response;
                }
                else{
                    if($this->returnMode == "return"){
                        return $this->encodeJSONdata($response);
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }

            $conn = $this->dbConnect(DB_NAME);
            
            if(!$conn){
                $response["status"] = DB_CONNECTION_ERROR_CODE;
                $response["data"] = "Failed to query the server. Try again.";
                
                if($direct){
                    return $response;
                }
                else{
                    if($this->returnMode == "return"){
                        return $this->encodeJSONdata($response);
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }

            $search_text = isset($idata["q"]) ? $this->filterData($idata["q"]) : null;
            $subQuery = null;
            if(!empty($search_text)){
                $subQuery = " AND `service_name` LIKE '%{$search_text}%' ";
            }

            $result = $this->selectData($conn, "SELECT * FROM `courier_services` {$subQuery} ORDER BY `id` DESC");
            if($result){
                if($direct){
                    return $result;
                }
                else{
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $result;

                    if($this->returnMode == "return"){
                        return $this->encodeJSONdata($response);
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                if($direct){
                    return array();
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = array();

                    if($this->returnMode == "return"){
                        return $this->encodeJSONdata($response);
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
        }

        public function getRiderDeliveries($idata = array(), $direct = false){
            $response = array();
            $rider_id = isset($idata["id"]) ? $this->filterData($idata["id"]) : null;
            $sort_by = isset($idata["sort_by"]) ? $this->filterData($idata["sort_by"]) : null;
            $offset = isset($idata["offset"]) ? $this->filterData($idata["offset"]) : 0;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "get_courier_deliveries",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "id" => $rider_id,
                    "sort_by" => $sort_by,
                    "offset" => $offset
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function getRiderDetails($idata = array(), $direct = false){
            $response = array();
            $rider_id = isset($idata["id"]) ? $this->filterData($idata["id"]) : null;
            
            //Initiate API request
            $request = $this->initiateAPIRequest(API_URL_EXTERNAL, array(
                "action" => "get_courier",
                "data" => array(
                    "role" => ROLE,
                    "provider_id" => PROVIDER_ID,
                    "ouser_id" => OUSER_ID,
                    "id" => $rider_id
                )
            ));

            if($this->isStringifiedArray($request)){
                $parseResponse = $this->decodeJSONData($request);
                if($parseResponse["status"] === SUCCESS_CODE){
                    $response["status"] = SUCCESS_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
                else{
                    $response["status"] = ERROR_CODE;
                    $response["data"] = $parseResponse["data"];
                    if($this->returnMode == "return"){
                        return($this->encodeJSONdata($response));
                    }
                    else{
                        die($this->encodeJSONdata($response));
                    }
                }
            }
            else{
                $response["status"] = ERROR_CODE;
                $response["data"] = "An unknown error occurred.";
                if($this->returnMode == "return"){
                    return($this->encodeJSONdata($response));
                }
                else{
                    die($this->encodeJSONdata($response));
                }
            }
        }

        public function serviceIDExists($service_id){
            $conn = $this->dbConnect(DB_NAME);
            
            if(!$conn){
                $response["status"] = DB_CONNECTION_ERROR_CODE;
                $response["data"] = "Failed to query the server. Try again.";
                die($this->encodeJSONdata($response));
            }

            $result = $this->countData($conn, "SELECT COUNT(*) FROM  `courier_services` WHERE `id` = '{$service_id}' LIMIT 1");
            if($result > 0){
                return true;
            }
            else{
                return false;
            }
        }

        public function riderExists($zone_origin, $zone_destination, $rider_telephone_no, $rider_id = 0){
            $conn = $this->dbConnect(DB_NAME);
            
            if(!$conn){
                $response["status"] = DB_CONNECTION_ERROR_CODE;
                $response["data"] = "Failed to query the server. Try again.";
                die($this->encodeJSONdata($response));
            }

            if($rider_id > 0){
                $result = $this->selectSingleData($conn, "SELECT `id` FROM `courier_bikes` WHERE `zone_destination` = '{$zone_destination}' AND `rider_telephone_no` = '{$rider_telephone_no}' AND `zone_origin` = '{$zone_origin}' LIMIT 1");
                if($result > 0){
                    if($result["id"] == $rider_id){
                        return false;
                    }
                    else{
                        return true;
                    }
                }
                else{
                    return false;
                }
            }
            else{
                $result = $this->countData($conn, "SELECT COUNT(*) FROM  `courier_bikes` WHERE `zone_destination` = '{$zone_destination}' AND `rider_telephone_no` = '{$rider_telephone_no}' AND `zone_origin` = '{$zone_origin}' LIMIT 1");
                if($result > 0){
                    return true;
                }
                else{
                    return false;
                }
            }
        }

    }
?>