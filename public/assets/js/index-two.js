(function(){

    //Preview delivery request
    $(document).on("click", ".preview-delivery-request-btn", function(){
        let btn, requestId;
        btn = $(this),
        requestId = btn.attr("data-request-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "get_delivery_request_preview_details",
            data: {
                request_id: requestId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    $("#preview-request-title").text(response.data.title);
                    $("#preview-request-description").text(response.data.description);
                    $("#preview-request-id").text("#" + response.data.request_special_id);
                    $("#preview-client-name").text(response.data.client_name);
                    $("#preview-client-id").text(response.data.client_id);
                    $("#preview-datetime-posted").text(response.data.datetime_posted);
                    $("#preview-datetime-requested").text(response.data.datetime_requested);
                    $("#preview-current-status").text(response.data.current_status);
                    $("#preview-current-location-name").text(response.data.current_location_name);
                    $("#preview-from-location").text(response.data.from_location);
                    $("#preview-to-location").text(response.data.to_location);
                    $("#preview-truck-type").text(response.data.truck_type);
                    $("#preview-current-vehicle-type").text(response.data.vehicle_type);
                    $("#preview-from-minimum-amount").text("GHS" + response.data.minimum_amount);
                    $("#preview-to-maximum-amount").text("GHS" + response.data.maximum_amount);
                    $("#preview-client-mobile-number").text(response.data.client_mobile_number);
                    $("#preview-client-email").text(response.data.client_email);
                    $("#preview-request-package-details").text(response.data.package_details);
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Delete waiting delivery request
    $(document).on("click", ".delete-waiting-delivery-request-btn", function(){
        callAlert({
            body: 'Are you sure you want to delete this request? It cannot be reversed once it\'s confirmed.',
            button: '<button class="focused delete-waiting-delivery-request-btn-confirmation" data-request-id="' + $(this).attr("data-request-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Delete waiting delivery request - Confirmation
    $(document).on("click", ".delete-waiting-delivery-request-btn-confirmation", function(){
        let btn, requestId;
        btn = $(this),
        requestId = btn.attr("data-request-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "delete_waiting_delivery_request",
            data: {
                request_id: requestId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    $(".delivery-request-table-data-list-item").each(function(){
                        if($(this).attr("data-id") == requestId){
                            $(this).remove();
                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.OKAY
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Accept delivery request
    $(document).on("click", ".accept-delivery-request-btn", function(){
        callAlert({
            body: 'Do you want to accept this delivery request?',
            button: '<button class="focused accept-delivery-request-btn-confirmation" data-request-id="' + $(this).attr("data-request-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Accept delivery request - Confirmation
    $(document).on("click", ".accept-delivery-request-btn-confirmation", function(){
        let btn, requestId, responseMsg;
        btn = $(this),
        requestId = btn.attr("data-request-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "accept_delivery_request",
            data: {
                request_id: requestId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    responseMsg = response.data;
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "get_single_delivery_request_html_data",
                        data: {
                            id: requestId
                        }
                    }, (response) => {
                        try{
                            Modules.togglePageLoader(false);
                            if(response.status == Modules.status.OKAY){
                                $(".delivery-request-table-data-list-item").each(function(){
                                    if($(this).attr("data-id") == requestId){
                                        $(this).replaceWith(response.data);
                                        Modules.toggleToastContainer({
                                            message: responseMsg,
                                            status: Modules.status.OKAY
                                        });
                                    }
                                });
                                
                                if($(".new-tb-data").length > 0){
                                    Modules.scrollTo(".new-tb-data");
                                    let tm = setTimeout(function(){
                                        $(".new-tb-data").removeClass("new-tb-data");
                                        clearTimeout(tm);
                                    }, 6000);
                                }
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                        catch(err){
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Decline delivery request
    $(document).on("click", ".decline-delivery-request-btn", function(){
        callAlert({
            body: 'Do you want to accept this delivery request?',
            button: '<button class="focused decline-delivery-request-btn-confirmation" data-request-id="' + $(this).attr("data-request-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Decline delivery request - Confirmation
    $(document).on("click", ".decline-delivery-request-btn-confirmation", function(){
        let btn, requestId, responseMsg;
        btn = $(this),
        requestId = btn.attr("data-request-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "decline_delivery_request",
            data: {
                request_id: requestId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    responseMsg = response.data;
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "get_single_delivery_request_html_data",
                        data: {
                            id: requestId
                        }
                    }, (response) => {
                        try{
                            Modules.togglePageLoader(false);
                            if(response.status == Modules.status.OKAY){
                                $(".delivery-request-table-data-list-item").each(function(){
                                    if($(this).attr("data-id") == requestId){
                                        $(this).replaceWith(response.data);
                                        Modules.toggleToastContainer({
                                            message: responseMsg,
                                            status: Modules.status.OKAY
                                        });
                                    }
                                });
                                
                                if($(".new-tb-data").length > 0){
                                    Modules.scrollTo(".new-tb-data");
                                    let tm = setTimeout(function(){
                                        $(".new-tb-data").removeClass("new-tb-data");
                                        clearTimeout(tm);
                                    }, 6000);
                                }
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                        catch(err){
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Activate-Deactivate user account
    $(document).on("click", ".manage-user-account-activation-btn", function(){
        callAlert({
            body: 'Do you want to proceed with this action?',
            button: '<button class="focused manage-user-account-activation-btn-confirmation" data-user-id="' + $(this).attr("data-user-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Activate-Deactivate user account - Confirmation
    $(document).on("click", ".manage-user-account-activation-btn-confirmation", function(){
        let btn, userId, responseMsg;
        btn = $(this),
        userId = btn.attr("data-user-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "activate_deactivate_user_account",
            data: {
                user_id: userId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    responseMsg = response.data;
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "get_single_user_html_data",
                        data: {
                            id: userId
                        }
                    }, (response) => {
                        try{
                            Modules.togglePageLoader(false);
                            if(response.status == Modules.status.OKAY){
                                $(".user-table-data-list-item").each(function(){
                                    if($(this).attr("data-id") == userId){
                                        $(this).replaceWith(response.data);
                                        Modules.toggleToastContainer({
                                            message: responseMsg,
                                            status: Modules.status.OKAY
                                        });
                                    }
                                });
                                
                                if($(".new-tb-data").length > 0){
                                    Modules.scrollTo(".new-tb-data");
                                    let tm = setTimeout(function(){
                                        $(".new-tb-data").removeClass("new-tb-data");
                                        clearTimeout(tm);
                                    }, 6000);
                                }
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                        catch(err){
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Block-Unblock user account
    $(document).on("click", ".manage-user-account-block-btn", function(){
        callAlert({
            body: 'Do you want to proceed with this action?',
            button: '<button class="focused manage-user-account-block-btn-confirmation" data-user-id="' + $(this).attr("data-user-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Block-Unblock user account - Confirmation
    $(document).on("click", ".manage-user-account-block-btn-confirmation", function(){
        let btn, userId, responseMsg;
        btn = $(this),
        userId = btn.attr("data-user-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "block_unblock_user_account",
            data: {
                user_id: userId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    responseMsg = response.data;
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "get_single_user_html_data",
                        data: {
                            id: userId
                        }
                    }, (response) => {
                        try{
                            Modules.togglePageLoader(false);
                            if(response.status == Modules.status.OKAY){
                                $(".user-table-data-list-item").each(function(){
                                    if($(this).attr("data-id") == userId){
                                        $(this).replaceWith(response.data);
                                        Modules.toggleToastContainer({
                                            message: responseMsg,
                                            status: Modules.status.OKAY
                                        });
                                    }
                                });
                                
                                if($(".new-tb-data").length > 0){
                                    Modules.scrollTo(".new-tb-data");
                                    let tm = setTimeout(function(){
                                        $(".new-tb-data").removeClass("new-tb-data");
                                        clearTimeout(tm);
                                    }, 6000);
                                }
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                        catch(err){
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Verify-Unverify user account
    $(document).on("click", ".manage-user-account-verify-btn", function(){
        callAlert({
            body: 'Do you want to proceed with this action?',
            button: '<button class="focused manage-user-account-verify-btn-confirmation" data-user-id="' + $(this).attr("data-user-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Verify-Unverify user account - Confirmation
    $(document).on("click", ".manage-user-account-verify-btn-confirmation", function(){
        let btn, userId, responseMsg;
        btn = $(this),
        userId = btn.attr("data-user-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "verify_unverify_user_account",
            data: {
                user_id: userId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    responseMsg = response.data;
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "get_single_user_html_data",
                        data: {
                            id: userId
                        }
                    }, (response) => {
                        try{
                            Modules.togglePageLoader(false);
                            if(response.status == Modules.status.OKAY){
                                $(".user-table-data-list-item").each(function(){
                                    if($(this).attr("data-id") == userId){
                                        $(this).replaceWith(response.data);
                                        Modules.toggleToastContainer({
                                            message: responseMsg,
                                            status: Modules.status.OKAY
                                        });
                                    }
                                });
                                
                                if($(".new-tb-data").length > 0){
                                    Modules.scrollTo(".new-tb-data");
                                    let tm = setTimeout(function(){
                                        $(".new-tb-data").removeClass("new-tb-data");
                                        clearTimeout(tm);
                                    }, 6000);
                                }
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                        catch(err){
                            Modules.toggleToastContainer({
                                message: "Failed to fetch info.",
                                status: Modules.status.FAILED
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

    //Delete user account
    $(document).on("click", ".delete-user-account-btn", function(){
        callAlert({
            body: 'Are you sure you want to delete this account? It cannot be reversed once it\'s confirmed.',
            button: '<button class="focused delete-user-account-btn-confirmation" data-user-id="' + $(this).attr("data-user-id") + '">Yes, continue.</button>',
            otext: 'No'
        });
    });

    //Delete user account - Confirmation
    $(document).on("click", ".delete-user-account-btn-confirmation", function(){
        let btn, userId;
        btn = $(this),
        userId = btn.attr("data-user-id");

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "delete_user_account",
            data: {
                user_id: userId,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    $(".user-table-data-list-item").each(function(){
                        if($(this).attr("data-id") == userId){
                            $(this).remove();
                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.OKAY
                            });
                        }
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.FAILED
                    });
                }
                return;
            }
            Modules.toggleToastContainer({
                message: Modules.status.UNKNOWN_ERROR,
                status: Modules.status.FAILED
            });
        });
    });

})();