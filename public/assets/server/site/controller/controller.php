<?php
    ignore_user_abort(true);
	require_once "../../../../dir.php";
    require_once "../models/session-start.php";
    require_once "../access/unlock.php";
    require_once "../models/config.php";
    require_once "../models/headers.php";
    require_once "../models/error-reporting.php";
    require_once "../models/default-timezone.php";
    require_once "../models/autoload.php";
	
    $Factory = new Factory();
    $General = $Factory->General();

    //Authenticate API key
    if (!$General->verifyApiKey()) {
        require "../http_responses/401.php";
    }
	
    $response = array();
    
    if (!$General->isEmpty(preg_replace("/[\n\t\r]/", "", file_get_contents('php://input')))) {
        if ($General->verifyDataStructureFromHeader() === true) {
            $_POST = json_decode(preg_replace("/[\n\t\r]/", "", file_get_contents('php://input')), true);
        }
        $aid = $_POST['aid'] ?? $_GET['aid'] ?? null;
        $task = $_POST['task'] ?? $_GET['task'] ?? null;
        $data = $_POST['data'] ?? $_GET ?? array();
    } else {
        $aid = $_POST['aid'] ?? $_GET['aid'] ?? null;
        $task = $_POST['task'] ?? $_GET['task'] ?? null;
        $data = $_POST['data'] ?? $_GET ?? array();
    }

    $General->logRequestInput(array(
        "task" => $task,
        "data" => $data,
        "user_id" => $General->decryptString($aid),
        "user_type" => "admin"
    ));
	
    switch($task){
        case "sign_in":
			echo $Factory->User()->signIn($data);
            break;
        case "sign_up":
			echo $Factory->User()->signUp($data);
            break;
        case "recover_user_account_password":
			echo $Factory->User()->recoverUserAccountPassword($data);
            break;
        case "change_user_account_password":
			echo $Factory->User()->changeUserAccountPassword($data);
            break;
        case "change_user_account_password_manual":
			echo $Factory->User()->changeUserAccountPasswordManual($data);
            break;
        case "update_profile_photo_session_var":
			echo $Factory->User()->updateProfilePhotoSessionVar($data);
            break;
        case "update_profile_names_session_var":
			echo $Factory->User()->updateProfileNamesSessionVar($data);
            break;
        case "get_single_user_html_data":
			echo $Factory->User()->getSingleUserHTMLData($data);
            break;
        case "get_single_lead_html_data":
			echo $Factory->More()->getSingleLeadHTMLData($data);
            break;
        case "get_single_company_documents_html_data":
			echo $Factory->More()->getSingleCompanyDocumentsHTMLData($data);
            break;
        case "get_single_skill_html_data":
			echo $Factory->More()->getSingleSkillHTMLData($data);
            break;
        case "get_single_video_html_data":
			echo $Factory->More()->getSingleVideoHTMLData($data);
            break;
        case "get_single_service_html_data":
			echo $Factory->Project()->getSingleServiceHTMLData($data);
            break;
        case "get_single_project_html_data":
			echo $Factory->Project()->getSingleProjectHTMLData($data);
            break;
        case "get_single_application_html_data":
			echo $Factory->Project()->getSingleApplicationHTMLData($data);
            break;
        case "get_single_review_html_data":
			echo $Factory->More()->getSingleReviewHTMLData($data);
            break;
        case "get_single_notice_html_data":
			echo $Factory->More()->getSingleNoticeHTMLData($data);
            break;
        case "get_single_invoice_html_data":
			echo $Factory->Payments()->getSingleInvoiceHTMLData($data);
            break;
        case "get_single_service_payment_html_data":
			echo $Factory->Payments()->getSingleServicePaymentHTMLData($data);
            break;
        case "get_single_payment_account_html_data":
			echo $Factory->Payments()->getSinglePaymentAccountHTMLData($data);
            break;
        case "get_single_payment_withdrawals_html_data":
			echo $Factory->Payments()->getSinglePaymentWithdrawalsHTMLData($data);
            break;
        case "render_view_item":
			echo $Factory->Render()->renderViewItem($data);
            break;
        case "get_all_skills_and_categories":
			echo $Factory->General()->getAllSkillsAndCategories($data);
            break;
        case "get_google_map_API_Key":
            echo $Factory->General()->getGoogleMapAPIKey($data);
            break;
        case "upload_admin_background_photo":
            echo $Factory->Settings()->uploadAdminBackgroundPhoto($data);
            break;
        case "manage_lead":
            echo $Factory->Admin()->manageLead($data);
            break;
        case "get_lead":
            echo $Factory->Admin()->getLead($data);
            break;
        case "delete_lead":
            echo $Factory->Admin()->deleteLead($data);
            break; 
        case "view_admin_account_details":
            echo $Factory->Admin()->viewAdminAccountDetails($data);
            break;
        case "block_unblock_admin_account":
            echo $Factory->Admin()->blockUnblockAdminAccount($data);
            break;
        case "delete_company_document":
            echo $Factory->Admin()->deleteCompanyDocument($data);
            break;
        case "get_delivery_request_preview_details":
            echo $Factory->Request()->getDeliveryRequestPreviewDetails($data);
            break;
        case "delete_waiting_delivery_request":
            echo $Factory->Request()->deleteWaitingDeliveryRequest($data);
            break;
        case "accept_delivery_request":
            echo $Factory->Request()->acceptDeliveryRequest($data);
            break;
        case "decline_delivery_request":
            echo $Factory->Request()->declineDeliveryRequest($data);
            break;
        case "activate_deactivate_user_account":
            echo $Factory->User()->activateDeactivateUserAccount($data);
            break;
        case "block_unblock_user_account":
            echo $Factory->User()->blockUnblockUserAccount($data);
            break;
        case "verify_unverify_user_account":
            echo $Factory->User()->verifyUnverifyUserAccount($data);
            break;
        case "delete_user_account":
            echo $Factory->User()->deleteUserAccount($data);
            break;
        case "get_single_delivery_request_html_data":
            echo $Factory->Request()->getSingleDeliveryRequestHTMLData($data);
            break;
        default:
            $response["status"] = ERROR_CODE;
            $response["data"] = "Hmmm! Task not recognized. Please try again.";
            echo $General->encodeJSONdata($response);
    }
?>