<?php
require_once SITE_ROOT . "access/lock.php";
require_once SITE_ROOT . "models/default-timezone.php";
class Views extends General
{

    public function renderPagination($data = array(), $link_btn = null)
    {
        $cdata = null;
        foreach ($data as $d) {
            $text = $d["text"];
            $page = $d["page"];
            $active = ($d["active"] == "yes") ? 'background-color: #eee !important;' : '';

            if (!is_numeric($text)) {
                $text = ($text == "next") ? '&nbsp; Next &nbsp; <i class="la la-angle-right"></i> &nbsp;' : '&nbsp; <i class="la la-angle-left"></i> &nbsp; Previous &nbsp;';
            }

            $cdata .= '<li class="paginate_button page-item ' . $link_btn . '" data-page-num="' . $page . '"><a aria-controls="kt_table_1" style="' . $active . '" class="page-link">' . $text . '</a></li>';
        }
        return $cdata;
    }

	public function renderTableDataOff($data = null, $state = null)
    {
        $color = $bgcolor = null;

        switch ($state) {
            case "empty":
                $color = "#000";
                $bgcolor = "#faebd7";
                break;
            case "error":
                $color = "#fff";
                $bgcolor = "#ff0000";
                break;
        }

        return '<tr role="row" class="odd rx-no-data-container"><td colspan="1000" style="background-color: ' . $bgcolor . '; color: ' . $color . '; padding-top: 15px !important;"><b>' . $data . '</b></td></tr>';
    }

	public function renderListOfUsers($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["id"]);

			$account_type = null;

			switch($this->filterData($dataItem["user_type"])){
				case 1:
					$account_type = "Individual";
					break;
				case 2:
					$account_type = "Business";
					break;
				case 3:
					$account_type = "Driver/Rider";
					break;
				case 4:
					$account_type = "Freight forwarder";
					break;
				case 5:
					$account_type = "Clearing agent";
					break;
			}
			
			$dataView .= '<tr class="table-data-list-item user-table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$uid.'">
								<td><div class="icover" style="width: 40px; height: 40px; overflow: hidden; margin: auto;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="user-list-photo-item mriv-view-image-btn trans" data-user-id="'.$uid.'" src="'.MEDIA_DOMAIN.'media/img/profile/'.$this->cExt($dataItem["photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/profile_large/'.$this->cExt($dataItem["profile_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></div></td>
								<td style="min-width: 90px; text-align: left;"><span class="user-profile-account-name '.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span>'.(!$this->isEmpty($dataItem["username"]) ? '<br /><small>@' . $dataItem["username"] : null).'<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td>' . $account_type . '</td>
								<td style="max-width: 70px; word-wrap: break-word;">' . ($this->isEmpty($dataItem["email"]) ? "N/A" : $dataItem["email"]) . '</td>
								<td>' . ($this->isEmpty($dataItem["mobile_number"]) ? "N/A" : $dataItem["mobile_number"]) . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_joined"], "1") . '</td>
								<td><span class="user-profile-account-activate-text">' . ($this->filterData($dataItem["is_activated"]) > 0 ? "YES" : "NO") . '</span></td>
								<td><span class="user-profile-account-block-text">' . ($this->filterData($dataItem["is_blocked"]) > 0 ? "YES" : "NO") . '</span></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<!--<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View user profile</div>
												</div>
											</li>-->
											<li>
												<div class="cur pdrop-item delete-user-account-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>Delete user account</div>
												</div>
											</li>
											'.($this->filterData($dataItem["is_verified"]) > 0 ? '<li><div class="cur pdrop-item manage-user-account-verify-btn" data-user-id="'.$uid.'"><div></div><div>Unverify user account</div></div></li>' : '<li><div class="cur pdrop-item manage-user-account-verify-btn" data-user-id="'.$uid.'"><div></div><div>Verify user account</div></div></li>').'
											'.($this->filterData($dataItem["is_activated"]) > 0 ? '<li><div class="cur pdrop-item manage-user-account-activation-btn" data-user-id="'.$uid.'"><div></div><div>Deactivate user account</div></div></li>' : '<li><div class="cur pdrop-item manage-user-account-activation-btn" data-user-id="'.$uid.'"><div></div><div>Activate user account</div></div></li>').'
											'.($this->filterData($dataItem["is_blocked"]) < 1 ? '<li><div class="cur pdrop-item manage-user-account-block-btn" data-user-id="'.$uid.'"><div></div><div>Block user account</div></div></li>' : '<li><div class="cur pdrop-item manage-user-account-block-btn" data-user-id="'.$uid.'"><div></div><div>Unblock user account</div></div></li>').'
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfAdministrators($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$dataItem["id"] = $this->encryptString($dataItem["id"]);

			$dataView .= '<tr class="table-data-list-item admin-table-data-list-item" data-id="'.$dataItem["id"].'">
								<td><div class="icover" style="width: 40px; height: 40px; overflow: hidden; margin: auto;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="mriv-view-image-btn trans" src="'.MEDIA_DOMAIN.'media/img/profile/'.$this->cExt($dataItem["profile_photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/profile_large/'.$this->cExt($dataItem["profile_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></div></td>
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td>' . $dataItem["envoyer_role"] . '</td>
								<td>' . $dataItem["email_address"] . '</td>
								<td>' . ($this->isEmpty($dataItem["mobile_number"]) ? "N/A" : $dataItem["mobile_number_country_number"].$dataItem["mobile_number"]) . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_joined"], "1") . '</td>
								<td>' . ($this->filterData($dataItem["is_blocked"]) > 0 ? "YES" : "NO") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div data-toggle="modal" data-user-id="'.$dataItem["id"].'" data-target="#preview-admin-account-details-container" class="cur pdrop-item preview-admin-account-details-btn">
													<div></div>
													<div>View account details</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-user-id="'.$dataItem["id"].'" data-target="#manage-admin-account-container" class="cur pdrop-item edit-admin-account-details-btn">
													<div></div>
													<div>Edit account details</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-user-id="'.$dataItem["id"].'" data-target="#send-email-sms-container" class="cur pdrop-item send-email-sms-btn">
													<div></div>
													<div>Send Direct Message/Email</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-user-id="'.$dataItem["id"].'" data-target="#send-in-app-notification-container" class="cur pdrop-item send-in-app-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-admin-account-block-btn" data-user-id="'.$dataItem["id"].'" data-block-status="'.$dataItem["is_blocked"].'">
													<div></div>
													<div>'.($this->filterData($dataItem["is_blocked"]) > 0 ? "Unblock" : "Block").' account <br /><small>'.($this->filterData($dataItem["is_blocked"]) > 0 ? "Would you like to unblock this admin?" : "Would you like to block this admin?").'</small></div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfRequests($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["request_id"]);

			$account_type = null;
			
			$dataView .= '<tr class="table-data-list-item delivery-request-table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 90px; text-align: left;"><span class="user-profile-account-name '.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span>'.(!$this->isEmpty($dataItem["username"]) ? '<br /><small>@' . $dataItem["username"] : null).'<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left; max-width: 300px;">' . ($this->isEmpty($dataItem["title"]) ? "N/A" : (strlen($dataItem["title"]) > 70 ? substr($dataItem["title"], 0, 70)."..." : $dataItem["title"])) . '</td>
								<td style="text-align: left; max-width: 300px;">' . ($this->isEmpty($dataItem["current_status"]) ? "N/A" : ucfirst($dataItem["current_status"])) . '</td>
								<td style="text-align: left; max-width: 300px;">' . ($this->isEmpty($dataItem["from_location"]) ? "N/A" : $dataItem["from_location"]) . '</td>
								<td style="text-align: left; max-width: 300px;">' . ($this->isEmpty($dataItem["to_location"]) ? "N/A" : $dataItem["to_location"]) . '</td>
								<td style="text-align: left; max-width: 300px;">' .	$this->convertDateTime($dataItem["datetime_posted"], "0") . '</td>
								<td style="max-width: 70px; word-wrap: break-word;">' . ($this->isEmpty($dataItem["email"]) ? "N/A" : $dataItem["email"]) . '</td>
								<td>' . ($this->isEmpty($dataItem["mobile_number"]) ? "N/A" : $dataItem["mobile_number"]) . '</td>
								<td>'.$dataItem["request_special_id"].'</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item preview-delivery-request-btn" data-toggle="modal" data-target="#preview_delivery_request_modal" data-request-id="'.$id.'">
													<div></div>
													<div>Preview details</div>
												</div>
											</li>
											'.($this->toLowerCase($dataItem["current_status"]) == "waiting" ? '<li>
												<div class="cur pdrop-item delete-waiting-delivery-request-btn" data-request-id="'.$id.'">
													<div></div>
													<div>Delete request</div>
												</div>
											</li>' : '').'
											'.(in_array($this->toLowerCase($dataItem["current_status"]), array("waiting", "declined")) ? '<li>
												<div class="cur pdrop-item accept-delivery-request-btn" data-request-id="'.$id.'">
													<div></div>
													<div>Accept request</div>
												</div>
											</li>' : '').'
											'.(in_array($this->toLowerCase($dataItem["current_status"]), array("waiting", "accepted")) ? '<li>
												<div class="cur pdrop-item decline-delivery-request-btn" data-request-id="'.$id.'">
													<div></div>
													<div>Decline request</div>
												</div>
											</li>' : '').'
											
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfVerificationRequests($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["uid"]);
			$dataView .= '<tr class="table-data-list-item" data-id="'.$uid.'">
								<td><div class="icover" style="width: 40px; height: 40px; overflow: hidden; margin: auto;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="mriv-view-image-btn trans" src="'.MEDIA_DOMAIN.'media/img/profile/'.$this->cExt($dataItem["profile_photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/profile_large/'.$this->cExt($dataItem["profile_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></div></td>
								<td style="min-width: 140px; text-align: left;"><span class="user-profile-account-name '.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="min-width: 140px; text-align: left;">' . (!$this->isEmpty($dataItem["verification_description"]) ? $dataItem["verification_description"] : "N/A") . '</td>
								<td>' . ucfirst($dataItem["account_type"]) . '</td>
								<td>' . $this->toUpperCase($dataItem["verification_document_name"]) . '</td>
								<td>' . $this->toUpperCase($dataItem["verification_document_code"]) . '</td>
								<td>' . $this->toUpperCase($dataItem["verification_status"]) . '</td>
								<td>' . $this->convertDateTime($dataItem["verification_datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View user profile</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice</div>
												</div>
											</li>
											'.(!$this->isEmpty($dataItem["verification_document_attachment"]) ? '<li><div class="cur pdrop-item mriv-view-image-btn mvamg" data-src="'.$dataItem["verification_document_attachment"].'"><div></div><div class="btn-text">Preview verification attachment</div></div></li>' : '').'
											'.($this->filterData($dataItem["is_verified"]) > 0 ? '<li><div class="cur pdrop-item verify-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unverify"><div></div><div class="btn-text">Unverify user account</div></div></li>' : '<li><div class="cur pdrop-item verify-user-account-profile-btn" data-user-id="'.$uid.'" data-action="verify"><div></div><div class="btn-text">Verify user account</div></div></li>').'
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderJobStatus($status){
		switch($this->toLowerCase($status)){
			case "accepting_applicants":
				return "Accepting Applicants";
			case "completed":
				return "Completed";
			case "cancelled":
				return "Cancelled";
			case "in_review":
				return "In Review";
			case "awarded_to_applicant":
				return "Awarded to Applicant";
			case "deleted":
				return "Deleted";
			case "delivered":
				return "Delivered";
		}
	}

	public function renderServiceItemStatus($status){
		switch($this->toLowerCase($status)){
			case "accepting_applicants":
				return "Accepting Applicants";
			case "completed":
				return "Completed";
			case "cancelled":
				return "Cancelled";
			case "in_review":
				return "In Review";
			case "awarded_to_applicant":
				return "Given to freelancer";
			case "deleted":
				return "Deleted";
			case "delivered":
				return "Delivered";
		}
	}
	
	public function renderListOfJobs($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["pid"], true);
			$uid = $this->encryptString($dataItem["puid"], true);
			
			$dataView .= '<tr class="table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="text-align: center;"><input type="checkbox" class="manage-watch-list-btn" '.($dataItem["on_watchlist"] == "yes" ? "checked" : "").' data-id="'.$id.'" data-type="job"/></td>
								<td style="min-width: 130px; text-align: left;"><span class="'.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left; min-width: 180px;">' . ucwords($dataItem["title"]) . '<br>(ID: '.$dataItem["project_id"].' - TYPE: '.ucfirst($dataItem["project_type"]).')</td>
								<td>' . $dataItem["number_of_applicants"] . '</td>
								<td>' . $dataItem["views"] . '</td>
								<td>' . $dataItem["payment_type"] . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["minimum_payment"] . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["maximum_payment"] . '</td>
								<td><span class="job-status">' . $this->renderJobStatus($dataItem["status"]) . '</span></td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-project-details-btn" data-project-id="'.$id.'">
													<div></div>
													<div>Preview details</div>
												</div>
											</li>
											'.($dataItem["status"] == "in_review" ? '<li><div class="cur pdrop-item get-project-edit-details-btn" data-toggle="modal" data-target="#edit_job_modal" data-project-id="'.$id.'"><div></div><div>Edit details</div></div></li>' : '').'
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View client profile</div>
												</div>
											</li>
											'.(in_array($dataItem["status"], array("awarded_to_applicant", "completed")) && $dataItem["is_applicant_chosen"] > 0 ? '<li><div class="cur pdrop-item send-invoice-to-client-btn" data-toggle="modal" data-target="#send_invoice_modal" data-invoice-amount="'.$dataItem["total_invoice_amount"].'" data-amount-paid="'.$dataItem["amount_paid"].'" data-amount-pending="'.$dataItem["amount_pending"].'" data-charge-agreed="'.$dataItem["charge_agreed"].'" data-id="'.$id.'"><div></div><div>View / Send invoice</div></div></li>' : null).'
											'.($dataItem["project_type"] == "artisan" && !in_array($dataItem["status"], array("cancelled", "deleted", "blocked", "completed")) ? '<li><div data-project-id="'.$id.'" data-location="'.$dataItem["location"].'" data-longitude="'.$dataItem["longitude"].'" data-latitude="'.$dataItem["latitude"].'" data-toggle="modal" data-target="#other_artisans_modal" class="cur pdrop-item get-artisans-closer-to-the-job-btn initial"><div></div><div>Change artisan</div></div></li>' : null).'
											'.($dataItem["is_applicant_chosen"] > 0 && !in_array($dataItem["status"], array("cancelled", "deleted", "blocked", "in_review", "completed")) && $this->isEmpty($dataItem["project_started_date"]) ? '<li><div class="cur pdrop-item mark-project-as-started-btn" data-project-id="'.$id.'"><div></div><div>Start project</div></div></li>' : null).'
											'.(in_array($dataItem["status"], array("awarded_to_applicant", "completed")) && $dataItem["number_of_reviews"] < 1 && !$this->isEmpty($dataItem["project_started_date"]) ? '<li><div class="cur pdrop-item add-client-review-btn" data-toggle="modal" data-target="#add_review_modal" data-type="job" data-id="'.$id.'"><div></div><div>Add a review</div></div></li>' : null).'
											'.($dataItem["number_of_reviews"] > 0 ? '<li><div class="cur pdrop-item add-client-review-btn" data-toggle="modal" data-target="#add_review_modal" data-type="job" data-id="'.$id.'"><div></div><div>Edit review</div></div></li>' : null).'
											'.($dataItem["number_of_reviews"] > 0 ? '<li><div class="cur pdrop-item preview-client-review-btn" data-toggle="modal" data-target="#preview_review_modal" data-type="job" data-id="'.$id.'"><div></div><div>See review</div></div></li>' : null).'
											'.(in_array($dataItem["status"], array("awarded_to_applicant", "completed")) && $dataItem["number_of_reviews"] < 1 && !$this->isEmpty($dataItem["project_started_date"]) ? '<li><div class="cur pdrop-item request-for-client-review-btn" data-type="job" data-id="'.$id.'"><div></div><div>Request for client review</div></div></li>' : null).'
											'.(in_array($dataItem["status"], array("in_review", "accepting_applicants")) ? '<li><div data-toggle="modal" data-target="#send_edit_notice_modal" data-id="'.$id.'" data-to="'.$uid.'" data-type="job" class="cur pdrop-item send-edit-notice-btn"><div></div><div>Request edit from owner</div></div></li>' : null).'
											<li><div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn"><div></div><div>Send notice to client</div></div></li>
											'.(((in_array($dataItem["status"], array("in_review", "accepting_applicants")) && $dataItem["project_type"] == "freelance") || (in_array($dataItem["status"], array("in_review", "accepting_applicants")) && $dataItem["project_type"] == "artisan")) ? '<li class="approve-disapprove-project-btn-parent"><div class="cur pdrop-item approve-disapprove-project-btn-item" data-toggle="modal" data-target="#approve_disapprove_job_modal" data-project-type="'.$dataItem["project_type"].'" data-project-id="'.$id.'" data-status="'.($dataItem["status"] == "in_review" ? "approve" : ($dataItem["status"] == "accepting_applicants" ? "disapprove" : null)).'"><div></div><div class="btn-text">'.($dataItem["status"] == "in_review" ? "Approve job" : ($dataItem["status"] == "accepting_applicants" ? "Disapprove job" : null)).'</div></div></li>' : null).'
											'.(!in_array($dataItem["status"], array("cancelled", "completed")) ? '<li class="cancel-job-btn-parent"><div class="cur pdrop-item cancel-job-btn-item" data-toggle="modal" data-target="#cancel_job_modal" data-project-id="'.$id.'"><div></div><div>Cancel job</div></div></li>' : null).'
											'.($this->filterData($dataItem["is_user_blocked"]) < 1 ? '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="block"><div></div><div class="btn-text">Block user account</div></div></li>' : '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unblock"><div></div><div class="btn-text">Unblock user account</div></div></li>').'
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}
	
	public function renderListOfProjectAttachments($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$pid = $this->encryptString($dataItem["pid"], true);
			$id = $this->encryptString($dataItem["id"], true);
			$uid = $this->encryptString($dataItem["uid"], true);
			$puid = $this->encryptString($dataItem["puid"], true);
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-attachment '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 130px; text-align: left;"><span class="'.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left; min-width: 180px; max-width: 180px;">' . ucwords($dataItem["title"]) . '<br>(ID: '.$dataItem["project_id"].' - TYPE: '.ucfirst($dataItem["project_type"]).')</td>
								<td style="max-width: 130px; text-align: left; word-break: break-all;">' . $dataItem["actual_attached_document_name"] . '</td>
								<td style="word-break: break-all; max-width: 120px;">' . $dataItem["special_record_id"] . '</td>
								<td style="text-align: left; min-width: 180px; max-width: 180px;">' . $dataItem["description"] . '</td>
								<td>' . $this->convertDateTime($dataItem["attachment_datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<a class="cur pdrop-item" download="' . $dataItem["actual_attached_document_name"] . '" href="'.USER_PORTAL_DOMAIN.'media/project/' . $dataItem["attached_document_name"] . '">
													<div></div>
													<div>Download file</div>
												</a>
											</li>
											<li>
												<a class="cur pdrop-item delete-attachment-btn" data-id="' . $id . '">
													<div></div>
													<div>Delete file</div>
												</a>
											</li>
											'.($dataItem["service_id_type"] == "service" ? '<li>
													<div class="cur pdrop-item get-service-purchase-details-btn" data-project-id="'.$pid.'">
														<div></div>
														<div>Preview purchase details</div>
													</div>
												</li>' : '<li>
												<div class="cur pdrop-item get-project-details-btn" data-project-id="'.$pid.'">
													<div></div>
													<div>Preview job details</div>
												</div>
											</li>').'
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$puid.'">
													<div></div>
													<div>View client profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View '.($dataItem["service_id_type"] == "service" ? "seller" : "worker").' profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$puid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage client blocks</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage '.($dataItem["service_id_type"] == "service" ? "seller" : "worker").' blocks</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to uploader</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}
	
	public function renderListOfProjectRequirements($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$pid = $this->encryptString($dataItem["pid"], true);
			$id = $this->encryptString($dataItem["id"], true);
			$uid = $this->encryptString($dataItem["uid"], true);
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-requirement '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 130px; text-align: left;"><span class="'.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left; min-width: 180px; max-width: 180px;">' . $dataItem["order_id"] . '</td>
								<td style="max-width: 130px; text-align: left; word-break: break-all;">' . ($this->isEmpty($dataItem["item_type"]) ? "N/A" : $dataItem["item_value"]) . '</td>
								<td style="word-break: break-all; max-width: 120px;">' . $dataItem["special_record_id"] . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_added"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											'.($dataItem["item_type"] == "file" ? '<li>
												<a class="cur pdrop-item" download="' . $dataItem["item_value"] . '" href="'.USER_PORTAL_DOMAIN.'media/orders/' . $dataItem["item_data"] . '">
													<div></div>
													<div>Download file</div>
												</a>
											</li>
											' : '').'
											<li>
												<a class="cur pdrop-item delete-requirement-btn" data-id="' . $id . '">
													<div></div>
													<div>Delete</div>
												</a>
											</li>
											<li>
												<div class="cur pdrop-item get-service-purchase-details-btn" data-project-id="'.$pid.'">
													<div></div>
													<div>Preview purchase details</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage blocks</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to uploader</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}
	
	public function renderListOfPaymentAccounts($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"], true);
			$uid = $this->encryptString($dataItem["uid"], true);
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-paymentaccount '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 130px; text-align: left;"><span class="'.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left;">' . $dataItem["account_type"] . '</td>
								<td style="text-align: left; min-width: 150px;">' . $dataItem["account_name"] . '</td>
								<td style="text-align: left;">' . $dataItem["account_number"] . '</td>
								<td style="text-align: left;">' . $dataItem["account_vendor_name"] . '</td>
								<td style="text-align: left;">' . $dataItem["account_vendor_type"] . '</td>
								<td style="text-align: left;">' . ($dataItem["use_as_default_payment"] > 0 ? "YES" : "NO") . '</td>
								<td style="text-align: left;">' . ($dataItem["is_active"] > 0 ? "YES" : "NO") . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<a class="cur pdrop-item edit-payout-account-btn" data-toggle="modal" data-target="#manage_payout_account_modal" data-id="' . $id . '" data-user-id="'.$uid.'">
													<div></div>
													<div>Edit</div>
												</a>
											</li>
											<li>
												<a class="cur pdrop-item delete-payout-account-btn" data-id="' . $id . '" data-user-id="'.$uid.'">
													<div></div>
													<div>Delete</div>
												</a>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View user profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage blocks</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to user</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}
	
	public function renderListOfApplications($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["pid"], true);
			$uid = $this->encryptString($dataItem["puid"], true);
			$application_id = $this->encryptString($dataItem["application_id"], true);
			
			$dataView .= '<tr class="table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$application_id.'">
								<td><div class="icover" style="width: 40px; height: 40px; overflow: hidden; margin: auto;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="user-list-photo-item mriv-view-image-btn trans" data-user-id="'.$uid.'" src="'.MEDIA_DOMAIN.'media/img/profile/'.$this->cExt($dataItem["profile_photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/profile_large/'.$this->cExt($dataItem["profile_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></div></td>
								<td style="min-width: 140px; text-align: left;"><span class="'.($this->filterData($dataItem["is_verified"]) > 0 ? "verified-user levelled mini" : "").'">' . $dataItem["full_name"] . '</span><br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="max-width: 200px; min-width: 200px; text-align: left;">' . ucwords($dataItem["cover_letter"]) . '</td>
								<td style="max-width: 160px; word-break: break-all;">' . $dataItem["title"] . '<br>(ID: '.$dataItem["project_id"].' - TYPE: '.ucfirst($dataItem["project_type"]).')</td>
								<td style="max-width: 160px; word-break: break-all;">' . $dataItem["special_record_id"] . '</td>
								<td>' . ($dataItem["applicant_block_status"] > 0 ? "BLOCKED" : "NOT BLOCKED") . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_applied"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-project-details-btn" data-project-id="'.$id.'">
													<div></div>
													<div>Preview job details</div>
												</div>
											</li>
											'.($dataItem["status"] == "in_review" ? '<li><div class="cur pdrop-item get-project-edit-details-btn" data-toggle="modal" data-target="#edit_job_modal" data-project-id="'.$id.'"><div></div><div>Edit details</div></div></li>' : '').'
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View applicant profile</div>
												</div>
											</li>
											'.($this->filterData($dataItem["applicant_block_status"]) < 1 ? '<li><div class="cur pdrop-item block-application-btn" data-id="'.$application_id.'" data-action="block"><div></div><div class="btn-text">Block application</div></div></li>' : '<li><div class="cur pdrop-item block-application-btn" data-id="'.$application_id.'" data-action="unblock"><div></div><div class="btn-text">Unblock application</div></div></li>').'
											'.($this->filterData($dataItem["is_verified"]) > 0 ? '<li><div class="cur pdrop-item verify-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unverify"><div></div><div class="btn-text">Unverify user account</div></div></li>' : '<li><div class="cur pdrop-item verify-user-account-profile-btn" data-user-id="'.$uid.'" data-action="verify"><div></div><div class="btn-text">Verify user account</div></div></li>').'
											'.($this->filterData($dataItem["is_activated"]) > 0 ? '<li><div class="cur pdrop-item activate-user-account-profile-btn" data-user-id="'.$uid.'" data-action="deactivate"><div></div><div class="btn-text">Deactivate user account</div></div></li>' : '<li><div class="cur pdrop-item activate-user-account-profile-btn" data-user-id="'.$uid.'" data-action="activate"><div></div><div class="btn-text">Activate user account</div></div></li>').'
											'.($this->filterData($dataItem["is_blocked"]) < 1 ? '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="block"><div></div><div class="btn-text">Block user account</div></div></li>' : '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unblock"><div></div><div class="btn-text">Unblock user account</div></div></li>').'
											'.($this->filterData($dataItem["profile_photo_valid"]) > 0 ? '<li><div class="cur pdrop-item flag-user-account-profile-btn" data-user-id="'.$uid.'" data-action="flag"><div></div><div class="btn-text">Flag profile photo</div></div></li>' : '<li><div class="cur pdrop-item flag-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unflag"><div></div><div class="btn-text">Unflag profile photo</div></div></li>').'
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-user-account-details-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>Edit user profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage user blocks</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item see-warnings-btn load-more-warnings-btn first" data-user-id="'.$uid.'" data-toggle="modal" data-target="#warnings_modal">
													<div></div>
													<div>See warnings</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfPurchases($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["main_project_id"]);
			$purchase_id = $this->encryptString($dataItem["purchase_id"]);
			$market_item_id = $this->encryptString($dataItem["market_item_id"]);
			$user_id = $this->encryptString($dataItem["user_id"]);
			$seller_id = $this->encryptString($dataItem["seller_id"]);

			$dataView .= '<tr class="table-data-list-item" data-id="">
								<td style="text-align: center;"><input type="checkbox" class="manage-watch-list-btn" '.($dataItem["on_watchlist"] == "yes" ? "checked" : "").' data-id="'.$purchase_id.'" data-type="purchase"/></td>
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left;">' . ucwords($dataItem["title"]) . '</td>
								<td>' . ($this->isEmpty($dataItem["order_id"]) ? "N/A" : $dataItem["order_id"]) . '</td>
								<td>' . ($dataItem["is_requirement_submitted"] > 0 ? "Submitted" : ($dataItem["status"] == "completed" ? "Submitted" : "Pending")) . '</td>
								<td>' . CURRENCY_RAW_GHS.($this->filterData($dataItem["total_amount"]) < 1 ? 0 : $dataItem["total_amount"]) . '</td>
								<td>' . CURRENCY_RAW_GHS.($this->filterData($dataItem["service_fee"]) < 1 ? 0 : $dataItem["service_fee"]) . '</td>
								<td>' . $this->renderServiceItemStatus($dataItem["status"]) . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-service-purchase-details-btn" data-project-id="'.$id.'">
													<div></div>
													<div>View purchase details</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-service-details-btn" data-service-id="'.$market_item_id.'">
													<div></div>
													<div>Preview service details</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$user_id.'">
													<div></div>
													<div>View buyer profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$seller_id.'">
													<div></div>
													<div>View seller profile</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$user_id.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to buyer</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$seller_id.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to seller</div>
												</div>
											</li>
											'.($dataItem["is_requirement_submitted"] < 1 && !in_array($dataItem["status"], array("completed")) ? '<li><div data-id="'.$purchase_id.'" class="cur pdrop-item request-order-requirements-btn"><div></div><div>Request requirements</div></div></li>' : null).'
											'.(in_array($dataItem["status"], array("awarded_to_applicant", "completed")) && $dataItem["number_of_reviews"] < 1 && $dataItem["has_order_started"] > 0 ? '<li><div class="cur pdrop-item add-client-review-btn" data-toggle="modal" data-target="#add_review_modal" data-type="service" data-id="'.$id.'"><div></div><div>Add a review</div></div></li>' : null).'
											'.($dataItem["number_of_reviews"] > 0 ? '<li><div class="cur pdrop-item add-client-review-btn" data-toggle="modal" data-target="#add_review_modal" data-type="service" data-id="'.$id.'"><div></div><div>Edit review</div></div></li>' : null).'
											'.($dataItem["number_of_reviews"] > 0 ? '<li><div class="cur pdrop-item preview-client-review-btn" data-toggle="modal" data-target="#preview_review_modal" data-type="service" data-id="'.$id.'"><div></div><div>See review</div></div></li>' : null).'
											'.(in_array($dataItem["status"], array("awarded_to_applicant", "completed")) && $dataItem["number_of_reviews"] < 1 && $dataItem["has_order_started"] > 0 ? '<li><div class="cur pdrop-item request-for-client-review-btn" data-type="service" data-id="'.$id.'"><div></div><div>Request for client review</div></div></li>' : null).'
											<li>
												<div data-user-id="'.$user_id.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email - Buyer</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$user_id.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message - Buyer</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$user_id.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification - Buyer</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$seller_id.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email - Seller</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$seller_id.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message - Seller</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$seller_id.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification - Seller</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderServiceStatus($status){
		switch($this->toLowerCase($status)){
			case "active":
				return "Active";
			case "blocked":
				return "Blocked";
			case "cancelled":
				return "Cancelled";
			case "in_review":
				return "In Review";
		}
	}

	public function renderListOfServices($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["user_id"]);
			$id = $this->encryptString($dataItem["market_item_id"]);

			$dataView .= '<tr class="table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left;">' . ucwords($dataItem["title"]) . '</td>
								<td>' . ($this->isEmpty($dataItem["item_id"]) ? "N/A" : $dataItem["item_id"]) . '</td>
								<td>' . $dataItem["number_of_orders"] . '</td>
								<td>' . $dataItem["clicks"] . '</td>
								<td>' . $dataItem["views"] . '</td>
								<td><span class="service-status">' . $this->renderServiceStatus($dataItem["status"], "service") . '</span></td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-service-details-btn" data-service-id="'.$id.'">
													<div></div>
													<div>Preview details</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View seller profile</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#edit_service_modal" data-service-id="'.$id.'" class="cur pdrop-item get-service-edit-details-btn">
													<div></div>
													<div>Edit service</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_edit_notice_modal" data-id="'.$id.'" data-to="'.$uid.'" data-type="service" class="cur pdrop-item send-edit-notice-btn">
													<div></div>
													<div>Request edit from owner</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to seller</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item add-to-pick-list-btn" data-id="'.$id.'" data-type="service">
													<div></div>
													<div class="btn-text">'.($this->filterData($dataItem["is_picked"]) < 1 ? "Add to picklist" : "Remove from picklist").'</div>
												</div>
											</li>
											'.($dataItem["status"] != "cancelled" ? '<li class="approve-disapprove-service-btn-parent"><div class="cur pdrop-item approve-disapprove-service-btn-item" data-toggle="modal" data-target="#approve_disapprove_service_modal" data-service-id="'.$id.'"  data-status="'.($dataItem["status"] == "in_review" ? "approve" : ($dataItem["status"] == "active" ? "disapprove" : null)).'"><div></div><div class="btn-text">'.($dataItem["status"] == "in_review" ? "Approve service" : ($dataItem["status"] == "active" ? "Disapprove service" : null)).'</div></div></li>' : null).'
											'.($dataItem["status"] != "cancelled" ? '<li><div class="cur pdrop-item cancel-service-btn-item" data-toggle="modal" data-target="#cancel_service_modal" data-service-id="'.$id.'"><div></div><div>Cancel service</div></div></li>' : null).'
											'.($this->filterData($dataItem["is_user_blocked"]) < 1 ? '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="block"><div></div><div class="btn-text">Block user account</div></div></li>' : '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$uid.'" data-action="unblock"><div></div><div class="btn-text">Unblock user account</div></div></li>').'
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfJobPayments($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$invoice_id = $this->encryptString($dataItem["invoice_id"]);
			$project_id = $this->encryptString($dataItem["main_project_id"]);

			$dataView .= '<tr class="table-data-list-item" data-id="">
								<td style="min-width: 100px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="min-width: 120px; text-align: left;">' . ucwords($dataItem["title"]) . '(ID: '.$dataItem["project_id"].')</td>
								<td style="text-align: left;">' . strtoupper($dataItem["status"]) . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["amount"] . '</td>
								<td>' . ($this->isEmpty($dataItem["payment_reference_code"]) ? "N/A" : $dataItem["payment_reference_code"]) . '</td>
								<td style="max-width: 90px; word-break: break-all;">' . $dataItem["email_address"] . '</td>
								<td>' . $dataItem["mobile_number_country_number"].(preg_replace("/^0/i", "", $dataItem["mobile_number"])) . '</td>
								<td>' . ($dataItem["is_transferred"] < 0 ? "YES" : "NOT YET") . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item clickable-project-preview-invoice-item" data-invoice-id="'.$invoice_id.'">
													<div></div>
													<div>Preview invoice</div>
												</div>
											</li>
											'.(($dataItem["status"] == "confirmed") ? '<li>
												<div class="cur pdrop-item change-payment-transfer-status-btn" data-invoice-id="'.$this->encryptString($dataItem["pip_id"]).'" data-type="job">
													<div></div>
													<div class="btn-text">'.($dataItem["is_transferred"] < 1 ? "Switch to transferred" : "Switch to not transferred").'</div>
												</div>
											</li>' : '').'
											<li>
												<div class="cur pdrop-item get-project-details-btn" data-project-id="'.$project_id.'">
													<div></div>
													<div>Preview job details</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfJobInvoices($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$invoice_id = $this->encryptString($dataItem["id"]);
			$project_id = $this->encryptString($dataItem["main_project_id"]);

			$dataView .= '<tr class="table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$invoice_id.'">
								<td style="min-width: 100px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="min-width: 120px; text-align: left;">' . ucwords($dataItem["title"]) . '(ID: '.$dataItem["project_id"].')<br /><span style="color: #828282;">(SPECIAL ID:' . $dataItem["special_record_id"] . ')</span><br /><span style="color: #828282;">(WITHDRAWAL STATUS: ' . $dataItem["withdrawal_request_status"] . ')</span></td>
								<td style="text-align: left;">' . strtoupper($dataItem["status"]) . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["charge"] . '</td>
								<td>' . ($this->isEmpty($dataItem["payment_reference_code"]) ? "N/A" : $dataItem["payment_reference_code"]) . '</td>
								<td style="max-width: 90px; word-break: break-all;">' . $dataItem["email_address"] . '</td>
								<td>' . $dataItem["mobile_number_country_number"].(preg_replace("/^0/i", "", $dataItem["mobile_number"])) . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											'.($this->toLowerCase($dataItem["status"]) != "paid" ? '<li><div class="cur pdrop-item confirm-invoice-btn" data-toggle="modal" data-target="#confirm_invoice_modal" data-id="'.$invoice_id.'"><div></div><div>Confirm invoice</div></div></li>' : null).'
											'.($this->toLowerCase($dataItem["status"]) != "paid" ? '<li><div class="cur pdrop-item prompt-client-invoice-btn" data-id="'.$invoice_id.'"><div></div><div>Prompt client to pay</div></div></li>' : null).'
											'.($this->toLowerCase($dataItem["status"]) != "paid" ? '<li><div class="cur pdrop-item delete-invoice-btn" data-id="'.$invoice_id.'"><div></div><div>Delete invoice</div></div></li>' : null).'
											'.($this->toLowerCase($dataItem["withdrawal_request_status"]) != "active" ? '<li><div class="cur pdrop-item activate-deactivate-invoice-withdrawal-btn" data-id="'.$invoice_id.'" data-status="activatate"><div></div><div>Activate withdrawal</div></div></li>' : '<li><div class="cur pdrop-item activate-deactivate-invoice-withdrawal-btn" data-id="'.$invoice_id.'" data-status="deactivate"><div></div><div>Deactivate withdrawal</div></div></li>').'
											<li>
												<div class="cur pdrop-item clickable-project-preview-invoice-item" data-invoice-id="'.$invoice_id.'">
													<div></div>
													<div>Preview invoice</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-project-details-btn" data-project-id="'.$project_id.'">
													<div></div>
													<div>Preview job details</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfServicePayments($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$project_id = $this->encryptString($dataItem["main_project_id"]);
			$service_id = $this->encryptString($dataItem["market_item_id"]);
			$main_order_id = $this->encryptString($dataItem["main_order_id"]);
			$order_id = $dataItem["order_id"];

			$dataView .= '<tr class="table-data-list-item '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$main_order_id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="text-align: left;">' . ucwords($dataItem["title"]) . '</td>
								<td style="text-align: left;">' . $dataItem["order_id"] . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["total_amount"] . '</td>
								<td>' . CURRENCY_RAW_GHS.$dataItem["service_fee"] . '</td>
								<td style="max-width: 150px; word-break: break-all;">' . $dataItem["email_address"] . '</td>
								<td>' . $dataItem["mobile_number_country_number"].(preg_replace("/^0/i", "", $dataItem["mobile_number"])) . '</td>
								<td>' . ($dataItem["is_transferred"] < 0 ? "YES" : "NOT YET") . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-order-invoice-details" data-order-id="'.$order_id.'">
													<div></div>
													<div>Preview invoice</div>
												</div>
											</li>
											'.($this->toLowerCase($dataItem["withdrawal_request_status_worker"]) != "active" ? '<li><div class="cur pdrop-item activate-deactivate-order-withdrawal-btn" data-order-id="'.$main_order_id.'" data-type="worker" data-status="activatate"><div></div><div>Activate withdrawal - Worker</div></div></li>' : '<li><div class="cur pdrop-item activate-deactivate-order-withdrawal-btn" data-order-id="'.$main_order_id.'" data-type="worker" data-status="deactivate"><div></div><div>Deactivate withdrawal - Worker</div></div></li>').'
											'.($this->toLowerCase($dataItem["withdrawal_request_status_client"]) != "active" ? '<li><div class="cur pdrop-item activate-deactivate-order-withdrawal-btn" data-order-id="'.$main_order_id.'" data-type="client" data-status="activatate"><div></div><div>Activate withdrawal - Client</div></div></li>' : '<li><div class="cur pdrop-item activate-deactivate-order-withdrawal-btn" data-order-id="'.$main_order_id.'" data-type="client" data-status="deactivate"><div></div><div>Deactivate withdrawal - Client</div></div></li>').'
											'.(($dataItem["payment_status"] == "paid") ? '<li>
												<div class="cur pdrop-item change-payment-transfer-status-btn" data-invoice-id="'.$this->encryptString($dataItem["order_id"]).'" data-type="order">
													<div></div>
													<div class="btn-text">'.($dataItem["is_transferred"] < 1 ? "Switch to transferred" : "Switch to not transferred").'</div>
												</div>
											</li>' : '').'
											<li>
												<div class="cur pdrop-item get-service-purchase-details-btn" data-project-id="'.$project_id.'">
													<div></div>
													<div>View purchase details</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-service-details-btn" data-service-id="'.$service_id.'">
													<div></div>
													<div>View service details</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfReports($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);

			$dataView .= '<tr class="table-data-list-item" data-id="" data-report-item-id="'.$dataItem["report_item_id"].'" data-report-item-type="'.$dataItem["report_item_type"].'">
								<td style="min-width: 140px; text-align: left;">' . (!$this->isEmpty($dataItem["full_name"]) ? ($dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"]) : ($this->isEmpty($dataItem["report_email"]) ? "N/A" : $dataItem["report_email"])) . '</small></td>
								<td style="text-align: left;">' . ($this->isEmpty($dataItem["report_subject"]) ? "N/A" : ucwords($dataItem["report_subject"])) . '</td>
								<td style="text-align: left; max-width: 100px; word-break: break-all;">' . ($this->isEmpty($dataItem["report_item_id"]) ? "N/A" : ucwords($dataItem["report_item_id"])) . ' / ' . ($this->isEmpty($dataItem["report_item_type"]) ? "N/A" : ucwords($dataItem["report_item_type"])) . '</td>
								<td style="max-width: 160px; text-align: left;">' . ($this->isEmpty($dataItem["report_description"]) ? "N/A" : $dataItem["report_description"]) . '</td>
								<td style="max-width: 120px; word-wrap: break-word;">' . ($this->isEmpty($dataItem["email_address"]) ? $dataItem["report_email"] : $dataItem["email_address"]) . ' / ' . ($this->isEmpty($dataItem["mobile_number"]) ? "N/A" : ($dataItem["mobile_number_country_number"].(preg_replace("/^0/i", "", $dataItem["mobile_number"])))) . '</td>
								<td><span class="report-status">' . ($this->toUpperCase($dataItem["status"])) . '</span></td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item mark-report-as-responded-btn" data-report-id="'.$id.'">
													<div></div>
													<div>Mark as responded</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfSiteMessages($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);

			$dataView .= '<tr class="table-data-list-item" data-id="">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["name"] . '</small></td>
								<td style="text-align: left;">' . $dataItem["email"] . '</td>
								<td style="max-width: 160px; text-align: left;">' . $dataItem["message"] . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime"], "1") . '</td>
								<td><span class="message-status">' . ($this->toUpperCase($dataItem["answered"])) . '</span></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item mark-message-as-responded-btn" data-message-id="'.$id.'">
													<div></div>
													<div>Mark as responded</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfNewsletterSubscribers($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);
			$country = $this->getCountryAndDialCodes($dataItem["country_code"], "code");
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-newsletter" data-id="'.$id.'">
								<td style="text-align: left;">' . $dataItem["email_address"] . '</td>
								<td>' . $this->convertDatetime($dataItem["datetime_subscribed"], "1") . '</td>
								<td>' . $country["name"] . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item delete-newsletter-email-btn" data-id="'.$id.'">
													<div></div>
													<div>Delete</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfBlog($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);
			$uid = $this->encryptString($dataItem["user_id"]);
			$country = $this->getCountryAndDialCodes($dataItem["country"], "code");

			$dataView .= '<tr class="table-data-list-item table-data-list-item-blog" data-id="'.$id.'">
								<td><div class="icover" style="width: 40px; height: 40px; overflow: hidden; margin: auto;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="mriv-view-image-btn trans" src="'.MEDIA_DOMAIN.'media/img/profile/'.$this->cExt($dataItem["profile_photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/profile_large/'.$this->cExt($dataItem["profile_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></div></td>
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="min-width: 140px; text-align: left;">' . $dataItem["title"] . '<br /><span style="color: #828282;">(SPECIAL ID:' . $dataItem["special_record_id"] . ')</span></small></td>
								<td>' . $this->getBlogCategory($dataItem["category"]) . '</td>
								<td>' . $dataItem["views"] . '</td>
								<td>' . $this->convertDatetime($dataItem["datetime_added"]) . '</td>
								<td>' . (!$this->isEmpty($dataItem["datetime_updated"]) ? $this->convertDatetime($dataItem["datetime_updated"]) : "N/A") . '</td>
								<td><span class="block-status">' . ($dataItem["is_blocked"] > 0 ? "YES" : "NO") . '</span></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View writer profile</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item custom-a-link" data-href="'.$dataItem["content_link"].'" data-target="_blank">
													<div></div>
													<div>Read blog</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item block-unblock-blog-btn" data-blog-id="'.$id.'">
													<div></div>
													<div class="btn-text">'.($dataItem["is_blocked"] > 0 ? "Unblock" : "Block").'</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item delete-blog-btn" data-blog-id="'.$id.'" data-user-id="'.$uid.'">
													<div></div>
													<div>Delete blog</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item allow-disallow-blog-btn" data-user-id="'.$uid.'">
													<div></div>
													<div class="btn-text">'.($dataItem["is_blog_writer"] > 0 ? "Disallow blog writing" : "Allow blog writing").'</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfMessages($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$sender = $this->encryptString($dataItem["sender"]);
			$receiver = $this->encryptString($dataItem["user_id"]);

			$dataView .= '<tr class="table-data-list-item" data-id="">
								<td style="text-align: left;">' . $dataItem["receiver_name"]."<br />(".$dataItem["receiver_email"] . ')</td>
								<td style="text-align: left;">' . $dataItem["sender_name"]."<br />(".$dataItem["sender_email"] . ')</td>
								<td style="text-align: left;">' . $dataItem["message"] . '</small></td>
								<td>' . $this->convertDatetime($dataItem["datetime"], "1") . '</td>
								<td>' . ($dataItem["seen"] > 0 ? "YES" : "NO") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$sender.'">
													<div></div>
													<div>View sender profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$receiver.'">
													<div></div>
													<div>View receiver profile</div>
												</div>
											</li>
											'.($this->filterData($dataItem["sender_user_block_status"]) < 1 ? '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$sender.'" data-action="block"><div></div><div class="btn-text">Block sender account</div></div></li>' : '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$sender.'" data-action="unblock"><div></div><div class="btn-text">Unblock sender account</div></div></li>').'
											'.($this->filterData($dataItem["receiver_user_block_status"]) < 1 ? '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$receiver.'" data-action="block"><div></div><div class="btn-text">Block receiver account</div></div></li>' : '<li><div class="cur pdrop-item block-user-account-profile-btn" data-user-id="'.$receiver.'" data-action="unblock"><div></div><div class="btn-text">Unblock receiver account</div></div></li>').'
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$sender.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to sender</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$receiver.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to receiver</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item block-message-communication-btn" data-sender="'.$sender.'" data-receiver="'.$receiver.'" data-status="'.($dataItem["block_status"]).'">
													<div></div>
													<div class="btn-text">'.ucfirst($dataItem["block_status"]).' communication</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#see_doctor_details" class="cur pdrop-item see-all-messages-btn" data-sender-email="'.$dataItem["sender_email"].'" data-receiver-email="'.$dataItem["receiver_email"].'">
													<div></div>
													<div>See all messages</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfDocuments($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);
			$downloadLink = MEDIA_DOMAIN."media/document/company_docs/".$dataItem["document_filename"];

			$dataView .= '<tr class="table-data-list-item table-data-list-item-document '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="text-align: left;">' . $dataItem["document_title"] . '</td>
								<td style="text-align: left;">' .($this->isEmpty($dataItem["document_description"]) ? "N/A" : $dataItem["document_description"]). '</td>
								<td style="text-align: left;">' .($this->isEmpty($dataItem["uploader_name"]) ? "N/A" : $dataItem["uploader_name"]). '</small></td>
								<td>' . $this->convertDatetime($dataItem["datetime_added"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<a class="cur pdrop-item" href="'.$downloadLink.'" download>
													<div></div>
													<div>Download</div>
												</a>
											</li>
											<li>
												<div class="cur pdrop-item delete-company-document-btn" data-document-id="'.$id.'">
													<div></div>
													<div>Delete document</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfSkills($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["skill_id"]);
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-skill '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="text-align: left;">' .$dataItem["skill_name"]. '</td>
								<td style="text-align: left;">' .($dataItem["is_hidden"] > 0 ? "YES" : "NO"). '</td>
								<td style="text-align: left;">' .$dataItem["skill_alias"]. '</td>
								<td style="text-align: left;">' . $this->getSkillCategoryName($dataItem["skill_category"]) . '</small></td>
								<td>' . $this->convertDateOnly($dataItem["is_new"]) . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-skill-info-btn" data-toggle="modal" data-target="#add_skill_modal" data-id="'.$id.'">
													<div></div>
													<div>Edit</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfVideos($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-video '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="text-align: center;"><img style="width: 40px; height: 40px; border-radius: 8px; border: 1px solid #eee; vertical-align: middle;" class="user-list-photo-item mriv-view-image-btn trans" src="'.MEDIA_DOMAIN.'media/img/videos/cover/small/'.$this->cExt($dataItem["cover_photo"], "webp").'" data-src="'.MEDIA_DOMAIN.'media/img/videos/cover/large/'.$this->cExt($dataItem["cover_photo"], "webp").'" onerror="this.src=\''.PROFILE_ERROR_IMAGE.'\'; this.removeAttribute(\'onerror\'); this.classList.remove(\'mriv-view-image-btn\');" alt=""/></td>
								<td style="text-align: left;">' .$dataItem["title"]. '</td>
								<td style="text-align: left;">' .$dataItem["duration"]. '</td>
								<td style="text-align: left;">' .ucfirst($dataItem["account_type"]). '</td>
								<td style="text-align: left;">' .($dataItem["notify_users_on_signup"] > 0 ? "YES" : "NO"). '</td>
								<td style="text-align: left;">' .ucfirst($dataItem["status"]). '</td>
								<td>' . $this->convertDatetime($dataItem["datetime"]) . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item get-video-info-btn" data-toggle="modal" data-target="#add_video_modal" data-id="'.$id.'">
													<div></div>
													<div>Edit</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item delete-video-btn" data-id="'.$id.'">
													<div></div>
													<div>Delete</div>
												</div>
											</li>
											'.($dataItem["all_users_notified"] < 1 ? '<li><div class="cur pdrop-item send-video-notification-btn" data-toggle="modal" data-target="#send_video_notification_modal" data-id="'.$id.'"><div></div><div>Send notification</div></div></li>' : null).'
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfReviews($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"]);
			$client = $this->encryptString($dataItem["reviewed_by"]);
			$worker = $this->encryptString($dataItem["reviewed"]);

			$dataView .= '<tr class="table-data-list-item  '.($is_new === true ? "new-tb-data active" : "").'"" data-id="'.$id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td>' . $dataItem["project_id"] . '</td>
								<td style="min-width: 220px; text-align: left;">' . $dataItem["review"] . '<br /><span style="color: #828282;">REPLY: </span>' . $dataItem["reply"] . '<br /><span style="color: #828282;">(SPECIAL ID:' . $dataItem["special_record_id"] . ')</span></td>
								<td>' . $dataItem["rating"] . '</td>
								<td>' . $this->getReviewCategoryValue($dataItem["reviewed_category"]) . '</small></td>
								<td>' . $dataItem["relevant"] . '</td>
								<td>' . $this->convertDatetime($dataItem["datetime"], "1") . '</small></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item cur pdrop-item view-user-profile-btn" data-user-id="'.$client.'">
													<div></div>
													<div>View client profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item cur pdrop-item view-user-profile-btn" data-user-id="'.$worker.'">
													<div></div>
													<div>View worker profile</div>
												</div>
											</li>
											'.($this->filterData($dataItem["is_blocked"]) < 1 ? '<li><div class="cur pdrop-item block-review-btn" data-id="'.$id.'" data-action="block"><div></div><div class="btn-text">Block review</div></div></li>' : '<li><div class="cur pdrop-item block-review-btn" data-id="'.$id.'" data-action="unblock"><div></div><div class="btn-text">Unblock review</div></div></li>').'
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$client.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to client</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$worker.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to worker</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfQuestions($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["user_id"]);
			$discussion_id = $this->encryptString($dataItem["discussion_id"]);
			$content_link = USER_PORTAL_DOMAIN."discussion/".$dataItem["category"]."/".$dataItem["content_title"];
			
			$dataView .= '<tr class="table-data-list-item table-data-list-item-question" data-id="'.$discussion_id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td>' . ($dataItem["is_worker_at_envoyer"] > 0 ? "YES" : "NO") . '</td>
								<td style="text-align: left;">' . $dataItem["title"] . '<br /><span style="color: #828282;">(SPECIAL ID:' . $dataItem["special_record_id"] . ')</span></td>
								<td>' . $dataItem["number_of_responses"] . '</td>
								<td>' . $this->getDiscussionCategoryValue($dataItem["category"]) . '</small></td>
								<td>' . $dataItem["views"] . '</td>
								<td><span class="block-status">' . ($dataItem["is_blocked"] > 0 ? "YES" : "NO") . '</span></td>
								<td>' . $this->convertDatetime($dataItem["datetime_added"], "1") . '</small></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View asker profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item custom-a-link" data-href="'.$content_link.'" data-target="_blank">
													<div></div>
													<div>View question</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to asker</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-user-account-details-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>Edit user profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage user blocks</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item block-unblock-question-btn" data-question-id="'.$discussion_id.'">
													<div></div>
													<div class="btn-text">'.($dataItem["is_blocked"] > 0 ? "Unblock question" : "Block question").'</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item delete-question-btn" data-question-id="'.$discussion_id.'">
													<div></div>
													<div>Delete question</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfAnswers($dataList, $viewType, $style = null, $print = null, $startRow = 0){
		$dataView = null;

		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["user_id"]);
			$discussion_id = $this->encryptString($dataItem["discussion_id"]);
			$answer_id = $this->encryptString($dataItem["answer_id"]);
			$content_link = USER_PORTAL_DOMAIN."discussion/".$dataItem["category"]."/".$dataItem["content_title"];

			$dataView .= '<tr class="table-data-list-item table-data-list-item-answer" data-id="'.$answer_id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td>' . ($dataItem["is_worker_at_envoyer"] > 0 ? "YES" : "NO") . '</td>
								<td style="text-align: left;">' . $dataItem["description"] . '<br /><span style="color: #828282;">(SPECIAL ID:' . $dataItem["special_record_id"] . ')</span></td>
								<td>' . $this->convertDatetime($dataItem["datetime_added"], "1") . '</small></td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>View asker profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item custom-a-link" data-href="'.$content_link.'" data-target="_blank">
													<div></div>
													<div>View question</div>
												</div>
											</li>
											<li>
												<div data-toggle="modal" data-target="#send_notice_modal" data-user-id="'.$uid.'" class="cur pdrop-item send-notice-btn">
													<div></div>
													<div>Send notice to author</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item get-user-account-details-btn" data-user-id="'.$uid.'">
													<div></div>
													<div>Edit user profile</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage user blocks</div>
												</div>
											</li>
											<li>
												<div class="cur pdrop-item delete-answer-btn" data-answer-id="'.$answer_id.'">
													<div></div>
													<div>Delete answer</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfLeads($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;

		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"], true);
			$dataView .= '<tr class="table-data-list-item table-data-list-item-lead  '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '</td>
								<td style="text-align: left;">' . $dataItem["email_address"] . '<br />' . $dataItem["mobile_number"] . '</td>
								<td style="text-align: left; min-width: 200px;">' . $dataItem["note"] . '</td>
								<td>' . ($dataItem["is_business"] > 0 ? "YES" : "NO") . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_added"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div data-id="'.$id.'" data-toggle="modal" data-target="#manage_lead_modal" class="cur pdrop-item edit-lead-btn">
													<div></div>
													<div>Edit</div>
												</div>
											</li>
											<li>
												<div data-id="'.$id.'" class="cur pdrop-item delete-lead-btn">
													<div></div>
													<div>Delete</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfNotices($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false){
		$dataView = null;
		
		foreach($dataList as $dataItem){
			$id = $this->encryptString($dataItem["id"], true);
			$dataView .= '<tr class="table-data-list-item table-data-list-item-notice  '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 140px; text-align: left; word-break: break-all;">' . $dataItem["note"] . '</td>
								<td style="">' . $dataItem["can_close"] . '</td>
								<td style="">' . ($this->isEmpty($dataItem["country"]) ? "All" : $this->getCountryName($dataItem["country"])) . '</td>
								<td style="">' . $dataItem["expiry_date"] . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_added"], "1") . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											<li>
												<div data-id="'.$id.'" data-toggle="modal" data-target="#manage_notice_modal" class="cur pdrop-item edit-notice-btn">
													<div></div>
													<div>Edit</div>
												</div>
											</li>
											<li>
												<div data-id="'.$id.'" class="cur pdrop-item delete-notice-btn">
													<div></div>
													<div>Delete</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

	public function renderListOfPaymentWithdrawals($dataList, $viewType, $style = null, $print = null, $startRow = 0, $is_new = false)
	{
		$dataView = null;

		foreach($dataList as $dataItem){
			$uid = $this->encryptString($dataItem["uid"], true);
			$id = $this->encryptString($dataItem["id"], true);
			$dataView .= '<tr class="table-data-list-item table-data-list-item-payment-withdrawal '.($is_new === true ? "new-tb-data active" : "").'" data-id="'.$id.'">
								<td style="min-width: 140px; text-align: left;">' . $dataItem["full_name"] . '<br /><small>@' . $dataItem["user_name"] . '<br /><span style="color: #828282;">(USER ID:' . $dataItem["user_id"] . ')</span></small></td>
								<td style="">' . $dataItem["request_id"] . '</td>
								<td style="">' . (CURRENCY_RAW_GHS.$this->numberFormat($dataItem["amount_requested"])) . '</td>
								<td style="">' . $dataItem["payment_status"] . '</td>
								<td style="">' . $dataItem["request_status"] . '</td>
								<td>' . $this->convertDateTime($dataItem["datetime_requested"], "1") . '</td>
								<td>' . ($this->isEmpty($dataItem["datetime_paid"]) ? "N/A" : $this->convertDateTime($dataItem["datetime_paid"], "1")) . '</td>
								<td>' . ($this->isEmpty($dataItem["mobile_number"]) ? "N/A" : $dataItem["mobile_number"]) . '</td>
								<td>
									<div class="pos-rel dropdown-menu-2 drop-btn">
										<button type="button" class="btn btn-mini btn-outline-success btn-sm cur">&nbsp;More <i class="icon-arrow-down22"></i></button>
										<ul class="drop-child-item hiddible exc">
											'.(($dataItem["request_status"] == "pending") ? '<li><div data-id="'.$id.'" class="cur pdrop-item confirm-withdrawal-payment-request-btn"><div></div><div>Confirm request</div></div></li>' : '').'
											'.(($dataItem["request_status"] == "failed") ? '<li><div data-id="'.$id.'" class="cur pdrop-item confirm-withdrawal-payment-request-btn"><div></div><div>Re-confirm request</div></div></li>' : '').'
											'.(($dataItem["request_status"] == "in-progress") ? '<li><div data-id="'.$id.'" data-transfer-code="'.$dataItem["transfer_code"].'" class="cur pdrop-item confirm-withdrawal-payment-verification-btn"><div></div><div>Verify transfer status</div></div></li>' : '').'
											<li><div class="cur pdrop-item view-user-profile-btn" data-user-id="'.$uid.'"><div></div><div>View user profile</div></div></li>
											<li>
												<div class="cur pdrop-item manage-user-blocks-btn" data-user-id="'.$uid.'" data-toggle="modal" data-target="#manage_user_blocks_modal">
													<div></div>
													<div>Manage user blocks</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="email" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Email</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="message" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send Direct Message</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-type="in-app-notification" class="cur pdrop-item send-simple-notification-btn">
													<div></div>
													<div>Send in-app notification</div>
												</div>
											</li>
											<li>
												<div data-user-id="'.$uid.'" data-target="#manage_payout_account_modal" data-toggle="modal" class="cur pdrop-item add-payout-account-btn">
													<div></div>
													<div>Add payout account</div>
												</div>
											</li>
										</ul>
									</div>
								</td>
							</tr>';
		}

		return $dataView;
	}

}
