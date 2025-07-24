/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

(function(){

    let sendAs = "general";
    let sendNotificationAs = "general";
    let searcherTimeout;
    let activeProfileViewId, SKILLS, SKILLS_CATEGORIES;
    let activeProjectApplicantId, activeProjectClientId, activeProjectId, activeProjectType, activeProjectStatus, activeProjectPaymentType, activeProjectPaymentPrice;
    let activeContractProjectId, activeContractClientId, activeContractWorkerId, activeServiceItemId;
    let activePurchaseItemId, activePurchaseSellerId, activePurchaseClientId, activePurchaseOrderId;
    let momo_services = [];
    let bank_services = [];

    $('[data-tip="m-tooltip"]').tooltip();

    //Scroll to top before the page unloads or relaods
    window.addEventListener("beforeunload", function(){
        window.scrollTo(0, 0);
    });

    //Load skills
    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
        task: "get_all_skills_and_categories",
        data: {}
    }, (response) => {
        if (Modules.isValidJSON(response)) {
            if (response.status == Modules.status.OKAY) {
                SKILLS = response.data.skills;
                SKILLS_CATEGORIES = response.data.skill_categories;
            }
            else {}
            return;
        }
    });

    const clearEmailSender = function(){
        sendAs = "general";
        $(".send-as-btn").removeClass("active");
        $(".send-as-general-btn").removeClass("active");
        $(".send-email-as-newsletter-btn").removeClass("active");
        $(".email-search-recipients-general-input-searcher, #email-sender-subject").val("");
        $("#email-recipients-container, #email-recipients-selected-container, #email-sender-body").empty();
        $("#email-sender-body").addClass("pholder");
    };

    const clearNotificationSender = function(){
        sendNotificationAs = "general";
        $(".send-notification-as-btn").removeClass("active");
        $(".send-notification-as-general-btn").removeClass("active");
        $(".notification-search-recipients-general-input-searcher, #notification-sender-body").val("");
        $("#notification-recipients-container, #notification-recipients-selected-container").empty();
    };

    const clearMessageSender = function(){
        $(".message-search-recipients-general-input-searcher, #message-sender-body").val("");
        $("#message-recipients-container, #message-recipients-selected-container").empty();
    };

    const fetchAdminDashboardNotifications = function(){
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "get_admin_dashboard_notifications",
            data: {
                admin_id: __GLOBALS__.USERID,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    Modules.render.renderAdminDashboardNotifications(response.data, {
                        artisanJobsNotification: $("#artisan_jobs_notification"),
                        freelanceJobsNotification: $("#freelance_jobs_notification"),
                        serviceNotification: $("#service_notification"),
                        verificationNotification: $("#verification_notification"),
                        reportNotification: $("#report_notification"),
                        directMessagesNotification: $("#direct_messages_notification"),
                        totalNotifications: $("#total_notification"),
                        withdrawalNotifications: $("#withdrawal_notification"),
                    });
                }
                else {}
                return;
            }
        });
    };

    //load initial notifications 
    fetchAdminDashboardNotifications();

    //Load notifications with intervals
    setInterval(() => {
        fetchAdminDashboardNotifications();
    }, 30000);

    const closeModal = function(){
        $(".modal-backdrop").attr("class", "modal fade");
    };

    const dismissModal = function(modal){
        $(modal).modal('hide');
    };

    const openModal = function(modal){
        $(modal).modal('show');
    };

    const openProfileSummaryModal = function(maintainOpener){
        $(".profile-menu-content-container-title").text("BASIC PROFILE");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn").removeClass("active");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn-default").addClass("active");
        $(".profile-menu-content-container").removeClass("active");
        $(".profile-menu-content-container-default").addClass("active");
        if(!maintainOpener) $(".view-info-modal").removeClass("active");
        $(".profile-view-info-modal").addClass("active");
        $(".load-user-profile-item").attr("data-loaded", "no");
        $(".review-switch-container-content-item-btn").removeClass("active");
        $(".review-switch-container-content-item-btn-default").addClass("active");
        $(".notes-container-header-content-body").removeClass("active");
        $(".add-note-btn").text("Add a note");
        $(".save-note-text, .save-note-title").val("");
        $(".menu-content-container-parent").scrollTop(0);
    };

    const openProjectPreviewModal = function(){
        $(".project-menu-content-container-title").text("DESCRIPTION");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn").removeClass("active");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn-default").addClass("active");
        $(".project-menu-content-container").removeClass("active");
        $(".project-menu-content-container-default").addClass("active");
        $(".view-info-modal").removeClass("active");
        $(".project-view-info-modal").addClass("active");
        $(".notes-container-header-content-body").removeClass("active");
        $(".add-note-btn").text("Add a note");
        $(".save-note-text, .save-note-title").val("");
        $(".load-project-item").attr("data-loaded", "no");
        $(".menu-content-container-parent").scrollTop(0);
    };

    const openServicePreviewModal = function(){
        $(".service-menu-content-container-title").text("DESCRIPTION & PACKAGES");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn").removeClass("active");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn-default").addClass("active");
        $(".service-menu-content-container").removeClass("active");
        $(".service-menu-content-container-default").addClass("active");
        $(".view-info-modal").removeClass("active");
        $(".service-view-info-modal").addClass("active");
        $(".notes-container-header-content-body").removeClass("active");
        $(".add-note-btn").text("Add a note");
        $(".save-note-text, .save-note-title").val("");
        $(".load-service-item").attr("data-loaded", "no");
        $(".menu-content-container-parent").scrollTop(0);
    };

    const openPurchasePreviewModal = function(){
        $(".purchase-menu-content-container-title").text("DESCRIPTION");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn").removeClass("active");
        $(".view-modal-menu-list-profile").find(".view-modal-menu-list-item-btn-default").addClass("active");
        $(".purchase-menu-content-container").removeClass("active");
        $(".purchase-menu-content-container-default").addClass("active");
        $(".view-info-modal").removeClass("active");
        $(".purchase-view-info-modal").addClass("active");
        $(".notes-container-header-content-body").removeClass("active");
        $(".add-note-btn").text("Add a note");
        $(".save-note-text, .save-note-title").val("");
        $(".load-purchase-item").attr("data-loaded", "no");
        $(".menu-content-container-parent").scrollTop(0);
    };

    const switchCreateUserAccountUserType = function(type){
        switch(type.toLowerCase()){
            case "business":
                $(".create-new-user-account-item-skill-searcher").val("");
                $(".search-skills-container-results, .search-skills-container-footer").empty();
                $(".create-new-user-account-item-gender").html(`<option value="" selected>Select gender</option><option value="male">Male</option><option value="female">Female</option><option value="none">Gender: None</option>`);
                $(".create-new-user-account-item-check-email-container, .create-new-user-account-item-user-category-container, .create-new-user-account-item-rate-per-hour-container, .create-new-user-account-item-skills-container, .create-new-user-account-item-work-location-container").addClass("kt-hidden");
                $(".create-new-user-account-item-email-address-container, .create-new-user-account-item-date-of-birth-container, .create-new-user-account-item-blog-writing-container").addClass("col-sm-12").removeClass("col-sm-6");
                $(".create-new-user-account-item-user-category").html(``);
                $(".search-skills-container-footer-item").remove();
                $(".create-new-account-main-form").removeClass("kt-hidden");
                break;
            case "freelancer":
                $(".create-new-user-account-item-skill-searcher").val("");
                $(".search-skills-container-results, .search-skills-container-footer").empty();
                $(".create-new-user-account-item-gender").html(`<option value="" selected>Select gender</option><option value="male">Male</option><option value="female">Female</option>`);
                $(".create-new-user-account-item-user-category-container, .create-new-user-account-item-rate-per-hour-container, .create-new-user-account-item-skills-container, .create-new-user-account-item-work-location-container").removeClass("kt-hidden");
                $(".create-new-user-account-item-date-of-birth-container, .create-new-user-account-item-blog-writing-container").removeClass("col-sm-12").addClass("col-sm-6");
                $(".create-new-user-account-item-check-email-container").addClass("kt-hidden");
                $(".create-new-user-account-item-email-address-container").addClass("col-sm-12").removeClass("col-sm-6");
                $(".create-new-user-account-item-date-of-birth-container, .create-new-user-account-item-blog-writing-container").removeClass("col-sm-12").addClass("col-sm-6");
                
                $(".create-new-user-account-item-user-category").html(`<option value="" selected>Select category</option>`);
                SKILLS_CATEGORIES.map(function(cat){
                    if(cat.skill_category.toLowerCase() != "artisans"){
                        $('.create-new-user-account-item-user-category').append(`<option value="${cat.skill_category}">${cat.skill_category_title}</option>`);
                    }
                });
                $(".create-new-account-main-form").removeClass("kt-hidden");
                break;
            case "artisan":
                $(".create-new-user-account-item-skill-searcher").val("");
                $(".search-skills-container-results, .search-skills-container-footer").empty();
                $(".create-new-user-account-item-gender").html(`<option value="" selected>Select gender</option><option value="male">Male</option><option value="female">Female</option>`);
                $(".create-new-user-account-item-user-category-container, .create-new-user-account-item-rate-per-hour-container, .create-new-user-account-item-skills-container, .create-new-user-account-item-work-location-container").removeClass("kt-hidden");
                $(".create-new-user-account-item-date-of-birth-container, .create-new-user-account-item-blog-writing-container").removeClass("col-sm-12").addClass("col-sm-6");
                $(".create-new-user-account-item-check-email-container").removeClass("kt-hidden");
                $(".create-new-user-account-item-email-address-container, .create-new-user-account-item-date-of-birth-container, .create-new-user-account-item-blog-writing-container, .create-new-user-account-item-email-address-container").removeClass("col-sm-12").addClass("col-sm-6");

                $(".create-new-user-account-item-user-category").html(``);
                SKILLS_CATEGORIES.map(function(cat){
                    if(cat.skill_category.toLowerCase() == "artisans"){
                        $('.create-new-user-account-item-user-category').append(`<option value="${cat.skill_category}" selected>${cat.skill_category_title}</option>`);
                    }
                });
                $(".create-new-account-main-form").removeClass("kt-hidden");
                break;
            default:
                $(".create-new-account-main-form").addClass("kt-hidden");
        }
    };

    const initiateUserAccountContainer = function(){
        Modules.emptyForm(".create-new-user-account-item-form");
        $(".create-new-account-main-form, .verification-settings-container").addClass("kt-hidden");
        $(".create-new-user-account-item-uid, .create-new-user-account-item-verified-account, .create-new-user-account-item-account-type, .create-new-user-account-item-skill-searcher, #create-new-user-account-item-location").val("");
        $(".create-new-user-main-profile-photo, .create-new-user-main-verification-photo").remove();
        $(".search-skills-container-results, .search-skills-container-footer").empty();
    };

    const renderListItem = function(params){
        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "render_view_item",
            data: {
                item_type: params.type,
                item_id: params.id,
                view_type: $(".active-form-item").attr("data-view-type"),
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    switch(params.action){
                        case "new":
                            $(".rx-no-data-container").remove();
                            $(params.container).prepend(response.data);
                            break;
                        case "replace":
                            $(".rx-no-data-container").remove();
                            $(params.containerListItem).each(function(){
                                if($(this).attr("data-id") == params.id){
                                    $(this).replaceWith(response.data);
                                }
                            });
                            break;
                    }
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
    };

    const getMoneyServices = function(params){
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "get_money_services",
            data: {}
        }, (response) => {
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    momo_services = response.data.mobile_money_vendors;
                    bank_services = response.data.banks;
                }
                return;
            }
        });
    };

    getMoneyServices();

    const loadReviewsFunc = function(btn){
        let container;
        container = $(".profile-menu-reviews-container");

        if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
            container.empty();
        }

        if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

        if(btn.hasClass("review-switch-container-content-item-btn")) container.empty();

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "get_user_reviews",
            data: {
                admin_id: __GLOBALS__.USERID,
                user_id: activeProfileViewId,
                view: $(".review-switch-container-content-item-btn.active").attr("data-val"),
                offset: $(".profile-review-item").length
            }
        }, (response) => {
            console.log(response.data);
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    container.find(".no-data-indicator").remove();
                    Modules.render.renderReviews(response.data, {
                        container: container,
                        class: "profile-review-item"
                    }, function(){
                        if(response.data.length === 0 && $(".profile-review-item").length === 0){
                            container.html(`<h6 class="no-data-indicator">No reviews available</h6>`);
                        }
                        else{
                            container.find(".no-data-indicator").remove();
                        }

                        if(btn.hasClass("single-load")){
                            btn.attr("data-loaded", "yes");
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
    };

    $(window).on("load", function(){

        //Load custom scrollbars
        const bars = document.querySelectorAll('.custom-scrollbars');
        for (const bar of bars) {
            new SimpleBar(bar, { autoHide: bar.classList.contains("no-hide") ? false : true });
        }
        
    });
    
    $(document).ready(function(){

        $(document).on("click", ".custom-a-link", function(e){
            e.preventDefault();
            e.stopPropagation();
            let l = $(this).attr("data-href"), t = $(this).attr("data-target") || "_self";
            if (Modules.trim(l) !== "") {
                Modules.redirect(l, t);
            }
        });

        $(document).on("click", ".kt-menu__item", function(){
            $(".kt-menu__item--active").removeClass("kt-menu__item--active");
            $(this).addClass("kt-menu__item--active");
            $(this).find(".kt-menu__submenu").show();
        });

        $(document).on("change", ".date-filter-selector", function(){
            switch($(this).val().toLowerCase()){
                case "custom_date":
                    $(".date-filter-selector-date-selector").removeClass("cal-disabled");
                    $(".date-filter-selector-date-selector-start").focus();
                    break;
                default:
                    $(".date-filter-selector-date-selector-start, .date-filter-selector-date-selector-end").val("");
                    $(".date-filter-selector-date-selector").addClass("cal-disabled").blur();
            }
        });

        $(document).on("change input", ".date-filter-selector-date-selector-start", function(){
            $(".date-filter-selector-date-selector-end").focus();
        });

        $(document).on("change", "form[data-task] select.form-control", function(e){
            if($(this).attr("name") == "filterType" && $(this).val() == "custom_date") return;
            let form = $("#" + $(this).closest("form").attr("id"));
            form.find("input[name='pageNum']").val(1);
            loadPage(form.attr("data-task"), form, form.attr("data-view-type"), 'no');
        });

        $(document).on("keydown", "input[name='searchQuery']", function(e){
            if(e.keyCode == 13){
                let form = $("#" + $(this).closest("form").attr("id"));
                loadPage(form.attr("data-task"), form, form.attr("data-view-type"), 'no');
            }
        });

        $(document).on("change input keyup", "input[name='searchQuery']", function(e){
            if(Modules.isEmpty($(this).val())){
                let form = $("#" + $(this).closest("form").attr("id"));
                loadPage(form.attr("data-task"), form, form.attr("data-view-type"), 'no');
            }
        });

        //Dashboard
        $(document).on("click", ".dashboard-info-generate-btn", function(){
            loadPage('dashboard', '#dashboard_form', $("#dashboard_form").attr("data-view-type"), 'no');
        });

        //Admins
        $(document).on("click", ".apply-admin-filters-btn", function(){
            closeModal();
            $("#admin_pageNum").val(1);
            loadPage('admins', '#admins_form', $("#admins_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".admins-page-num-btn", function(){
            $("#admin_pageNum").val($(this).attr("data-page-num"));
            loadPage('admins', '#admins_form', $("#admins_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-admin-filters-btn", function(){
            loadPage('admins', '#admins_form', $("#admins_form").attr("data-view-type"), 'yes');
        });

        //Users
        $(document).on("click", ".apply-user-filters-btn", function(){
            closeModal();
            $("#user_pageNum").val(1);

            let skills = [];

            $(".user-search-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });

            $(".advanced-filter-item[name='skills_list']").val(skills.join(","));

            loadPage('users', '#users_form', $("#users_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-user-filters-btn", function(){
            loadPage('users', '#users_form', $("#users_form").attr("data-view-type"), 'yes');
        });

        $(document).on("click", ".users-page-num-btn", function(){
            $("#user_pageNum").val($(this).attr("data-page-num"));
            loadPage('users', '#users_form', $("#users_form").attr("data-view-type"), 'no');
        });

        //Requests
        $(document).on("click", ".apply-request-filters-btn", function(){
            closeModal();
            $("#request_pageNum").val(1);

            let skills = [];

            $(".request-search-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });

            $(".advanced-filter-item[name='skills_list']").val(skills.join(","));

            loadPage('requests', '#requests_form', $("#requests_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-request-filters-btn", function(){
            loadPage('requests', '#requests_form', $("#requests_form").attr("data-view-type"), 'yes');
        });

        $(document).on("click", ".requests-page-num-btn", function(){
            $("#request_pageNum").val($(this).attr("data-page-num"));
            loadPage('requests', '#requests_form', $("#requests_form").attr("data-view-type"), 'no');
        });

        //Verification requests
        $(document).on("click", ".apply-verification-filters-btn", function(){
            closeModal();
            $("#verification_pageNum").val(1);
            loadPage('verification', '#verification_form', $("#verification_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".verification-page-num-btn", function(){
            $("#verification_pageNum").val($(this).attr("data-page-num"));
            loadPage('verification', '#verification_form', $("#verification_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-verification-filters-btn", function(){
            loadPage('verification', '#verification_form', $("#verification_form").attr("data-view-type"), 'yes');
        });

        //Jobs
        $(document).on("click", ".apply-job-filters-btn", function(){
            closeModal();
            $("#job_pageNum").val(1);

            let skills = [];

            $(".job-search-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });
            
            $(".advanced-filter-item[name='skills_list']").val(skills.join(","));

            loadPage('jobs', '#jobs_form', $("#jobs_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".job-page-num-btn", function(){
            $("#job_pageNum").val($(this).attr("data-page-num"));
            loadPage('jobs', '#jobs_form', $("#jobs_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-job-filters-btn", function(){
            loadPage('jobs', '#jobs_form', $("#jobs_form").attr("data-view-type"), 'yes');
        });

        //Applications
        $(document).on("click", ".apply-application-filters-btn", function(){
            $("#application_pageNum").val(1);
            loadPage('applications', '#application_form', $("#application_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".application-page-num-btn", function(){
            $("#application_pageNum").val($(this).attr("data-page-num"));
            loadPage('applications', '#application_form', $("#application_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-application-filters-btn", function(){
            loadPage('applications', '#application_form', $("#application_form").attr("data-view-type"), 'yes');
        });

        //Attachments
        $(document).on("click", ".apply-attachment-filters-btn", function(){
            $("#attachment_pageNum").val(1);
            loadPage('attachments', '#attachment_form', $("#attachment_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".attachment-page-num-btn", function(){
            $("#attachment_pageNum").val($(this).attr("data-page-num"));
            loadPage('attachments', '#attachment_form', $("#attachment_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-attachment-filters-btn", function(){
            loadPage('attachments', '#attachment_form', $("#attachment_form").attr("data-view-type"), 'yes');
        });

        //Requirements
        $(document).on("click", ".apply-requirement-filters-btn", function(){
            $("#requirement_pageNum").val(1);
            loadPage('requirements', '#requirement_form', $("#requirement_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".requirement-page-num-btn", function(){
            $("#requirement_pageNum").val($(this).attr("data-page-num"));
            loadPage('requirements', '#requirement_form', $("#requirement_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-requirement-filters-btn", function(){
            loadPage('requirements', '#requirement_form', $("#requirement_form").attr("data-view-type"), 'yes');
        });

        //payment accounts
        $(document).on("click", ".apply-paymentaccount-filters-btn", function(){
            $("#paymentaccount_pageNum").val(1);
            loadPage('payment_accounts', '#paymentaccount_form', $("#paymentaccount_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".paymentaccount-page-num-btn", function(){
            $("#paymentaccount_pageNum").val($(this).attr("data-page-num"));
            loadPage('payment_accounts', '#paymentaccount_form', $("#paymentaccount_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-paymentaccount-filters-btn", function(){
            loadPage('payment_accounts', '#paymentaccount_form', $("#paymentaccount_form").attr("data-view-type"), 'yes');
        });

        //Services
        $(document).on("click", ".apply-service-filters-btn", function(){
            closeModal();
            $("#service_pageNum").val(1);
            
            let skills = [];

            $(".service-search-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });
            
            $(".advanced-filter-item[name='skills_list']").val(skills.join(","));

            loadPage('services', '#services_form', $("#services_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".service-page-num-btn", function(){
            $("#service_pageNum").val($(this).attr("data-page-num"));
            loadPage('services', '#services_form', $("#services_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-service-filters-btn", function(){
            loadPage('services', '#services_form', $("#services_form").attr("data-view-type"), 'yes');
        });

        //Purchases
        $(document).on("click", ".apply-purchase-filters-btn", function(){
            closeModal();
            $("#purchase_pageNum").val(1);
            loadPage('purchases', '#purchases_form', $("#purchases_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".purchase-page-num-btn", function(){
            $("#purchase_pageNum").val($(this).attr("data-page-num"));
            loadPage('purchases', '#purchases_form', $("#purchases_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-purchase-filters-btn", function(){
            loadPage('purchases', '#purchases_form', $("#purchases_form").attr("data-view-type"), 'yes');
        });

        //Job payments
        $(document).on("click", ".apply-job-payments-filters-btn", function(){
            closeModal();
            $("#job_payments_pageNum").val(1);
            loadPage('job_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".job-payments-page-num-btn", function(){
            $("#job_payments_pageNum").val($(this).attr("data-page-num"));
            loadPage('job_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-job-payments-filters-btn", function(){
            loadPage('job_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'yes');
        });

        //Job invoices
        $(document).on("click", ".apply-job-invoices-filters-btn", function(){
            closeModal();
            $("#job_payments_pageNum").val(1);
            loadPage('job_invoices', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".job-invoices-page-num-btn", function(){
            $("#job_payments_pageNum").val($(this).attr("data-page-num"));
            loadPage('job_invoices', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-job-invoices-filters-btn", function(){
            loadPage('job_invoices', '#payments_form', $("#payments_form").attr("data-view-type"), 'yes');
        });

        //Service payments
        $(document).on("click", ".apply-service-payments-filters-btn", function(){
            closeModal();
            $("#service_payments_pageNum").val(1);
            loadPage('service_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".service-payments-page-num-btn", function(){
            $("#service_payments_pageNum").val($(this).attr("data-page-num"));
            loadPage('service_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-service-payments-filters-btn", function(){
            loadPage('service_payments', '#payments_form', $("#payments_form").attr("data-view-type"), 'yes');
        });

        //Withdrawal
        $(document).on("click", ".apply-payment-withdrawals-filters-btn", function(){
            closeModal();
            $("#payment_withdrawals_pageNum").val(1);
            loadPage('withdrawals', '#withdrawal_form', $("#withdrawal_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".payment-withdrawals-page-num-btn", function(){
            $("#payment_withdrawals_pageNum").val($(this).attr("data-page-num"));
            loadPage('withdrawals', '#withdrawal_form', $("#withdrawal_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-payment-withdrawals-filters-btn", function(){
            loadPage('withdrawals', '#withdrawal_form', $("#withdrawal_form").attr("data-view-type"), 'yes');
        });

        //Reports
        $(document).on("click", ".apply-report-filters-btn", function(){
            closeModal();
            $("#report_pageNum").val(1);
            loadPage('reports', '#reports_form', $("#reports_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".report-page-num-btn", function(){
            $("#report_pageNum").val($(this).attr("data-page-num"));
            loadPage('reports', '#reports_form', $("#reports_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-report-filters-btn", function(){
            loadPage('reports', '#reports_form', $("#reports_form").attr("data-view-type"), 'yes');
        });

        //Site messages
        $(document).on("click", ".apply-sm-filters-btn", function(){
            closeModal();
            $("#sm_pageNum").val(1);
            loadPage('site_messages', '#sm_form', $("#sm_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".sm-page-num-btn", function(){
            $("#sm_pageNum").val($(this).attr("data-page-num"));
            loadPage('site_messages', '#sm_form', $("#sm_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-sm-filters-btn", function(){
            loadPage('site_messages', '#sm_form', $("#sm_form").attr("data-view-type"), 'yes');
        });

        //Newsletter subscriptions
        $(document).on("click", ".apply-subscription-filters-btn", function(){
            closeModal();
            $("#subscription_pageNum").val(1);
            loadPage('subscriptions', '#subscriptions_form', $("#subscriptions_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".subscription-page-num-btn", function(){
            $("#subscription_pageNum").val($(this).attr("data-page-num"));
            loadPage('subscriptions', '#subscriptions_form', $("#subscriptions_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-subscription-filters-btn", function(){
            loadPage('subscriptions', '#subscriptions_form', $("#subscriptions_form").attr("data-view-type"), 'yes');
        });

        //Blog
        $(document).on("click", ".apply-blog-filters-btn", function(){
            closeModal();
            $("#blog_pageNum").val(1);
            loadPage('blog', '#blog_form', $("#blog_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".blog-page-num-btn", function(){
            $("#blog_pageNum").val($(this).attr("data-page-num"));
            loadPage('blog', '#blog_form', $("#blog_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-blog-filters-btn", function(){
            loadPage('blog', '#blog_form', $("#blog_form").attr("data-view-type"), 'yes');
        });

        //Message
        $(document).on("click", ".apply-message-filters-btn", function(){
            closeModal();
            $("#message_pageNum").val(1);
            loadPage('message', '#message_form', $("#message_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".message-page-num-btn", function(){
            $("#message_pageNum").val($(this).attr("data-page-num"));
            loadPage('message', '#message_form', $("#message_form").attr("data-view-type"), 'no');
        });

        //Documents
        $(document).on("click", ".apply-document-filters-btn", function(){
            closeModal();
            $("#document_pageNum").val(1);
            loadPage('documents', '#document_form', $("#document_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".document-page-num-btn", function(){
            $("#document_pageNum").val($(this).attr("data-page-num"));
            loadPage('documents', '#document_form', $("#document_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-document-filters-btn", function(){
            loadPage('documents', '#document_form', $("#document_form").attr("data-view-type"), 'yes');
        });

        //Questions
        $(document).on("click", ".apply-question-filters-btn", function(){
            closeModal();
            $("#question_pageNum").val(1);
            loadPage('question', '#question_form', $("#question_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".question-page-num-btn", function(){
            $("#question_pageNum").val($(this).attr("data-page-num"));
            loadPage('question', '#question_form', $("#question_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-question-filters-btn", function(){
            loadPage('question', '#question_form', $("#question_form").attr("data-view-type"), 'yes');
        });

        //Answers
        $(document).on("click", ".apply-answer-filters-btn", function(){
            closeModal();
            $("#answer_pageNum").val(1);
            loadPage('answers', '#answer_form', $("#answer_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".answer-page-num-btn", function(){
            $("#answer_pageNum").val($(this).attr("data-page-num"));
            loadPage('answers', '#answer_form', $("#answer_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-answer-filters-btn", function(){
            loadPage('answers', '#answer_form', $("#answer_form").attr("data-view-type"), 'yes');
        });

        //Reviews
        $(document).on("click", ".apply-review-filters-btn", function(){
            closeModal();
            $("#review_pageNum").val(1);
            loadPage('review', '#review_form', $("#review_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".review-page-num-btn", function(){
            $("#review_pageNum").val($(this).attr("data-page-num"));
            loadPage('review', '#review_form', $("#review_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-review-filters-btn", function(){
            loadPage('review', '#review_form', $("#review_form").attr("data-view-type"), 'yes');
        });

        //Leads
        $(document).on("click", ".apply-lead-filters-btn", function(){
            closeModal();
            $("#lead_pageNum").val(1);
            loadPage('lead', '#lead_form', $("#lead_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".lead-page-num-btn", function(){
            $("#lead_pageNum").val($(this).attr("data-page-num"));
            loadPage('lead', '#lead_form', $("#lead_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-lead-filters-btn", function(){
            loadPage('lead', '#lead_form', $("#lead_form").attr("data-view-type"), 'yes');
        });

        //Notices
        $(document).on("click", ".apply-notice-filters-btn", function(){
            closeModal();
            $("#notice_pageNum").val(1);
            loadPage('notice', '#notice_form', $("#notice_form").attr("data-view-type"), 'no');
        });
        
        $(document).on("click", ".notice-page-num-btn", function(){
            $("#notice_pageNum").val($(this).attr("data-page-num"));
            loadPage('notice', '#notice_form', $("#notice_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-notice-filters-btn", function(){
            loadPage('notice', '#notice_form', $("#notice_form").attr("data-view-type"), 'yes');
        });

        $(document).on("click", ".table-data-list-item", function(){
            $(".table-data-list-item").removeClass("active");
            $(this).addClass("active");
        });

        $(document).on("click", ".drop-btn", function(){
            $(".table-data-list-item").removeClass("active");
            $(this).closest(".table-data-list-item").addClass("active");
        });

        //Skills
        $(document).on("click", ".apply-skill-filters-btn", function(){
            closeModal();
            $("#skill_pageNum").val(1);
            loadPage('skill', '#skill_form', $("#skill_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".skill-page-num-btn", function(){
            $("#skill_pageNum").val($(this).attr("data-page-num"));
            loadPage('skill', '#skill_form', $("#skill_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-skill-filters-btn", function(){
            loadPage('skill', '#skill_form', $("#skill_form").attr("data-view-type"), 'yes');
        });

        //videos
        $(document).on("click", ".apply-video-filters-btn", function(){
            closeModal();
            $("#video_pageNum").val(1);
            loadPage('video', '#video_form', $("#video_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".video-page-num-btn", function(){
            $("#video_pageNum").val($(this).attr("data-page-num"));
            loadPage('video', '#video_form', $("#video_form").attr("data-view-type"), 'no');
        });

        $(document).on("click", ".clear-video-filters-btn", function(){
            loadPage('video', '#video_form', $("#video_form").attr("data-view-type"), 'yes');
        });

        //Admin account permission controls
        $(document).on("change", ".admin-account-permission-item", function(){
            if(this.checked){
                $(this).val("1");
            }
            else{
                $(this).val("0");
            }
        });

        //Add a new admin account
        $(document).on("click", ".new-admin-account-btn", function(){
            $(".admin-account-input-item").val("");
            $(".admin-account-permission-item").each(function(){
                $(this).prop("checked", false).val("0");
            });
        });

        $(document).on("click", "#add-new-admin-account-btn", function(){
            let btn, permissions;
            btn = $(this),
            permissions = {};

            $(".admin-account-permission-item").each(function(){
                permissions[$(this).attr("name")] = parseInt($(this).val());
            });

            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "sign_up",
                data: {
                    permissions: permissions,
                    account_id: $("#admin-account-id").val(),
                    account_email: $("#admin-account-email-address").val(),
                    account_first_name: $("#admin-account-first-name").val(),
                    account_last_name: $("#admin-account-last-name").val(),
                    account_gender: $("#admin-gender").val(),
                    account_mobile_number: $("#admin-account-mobile-number").val(),
                    account_mobile_number_country_number: $("#admin-account-country-dial-code").val(),
                    account_role: $("#admin-account-role").val(),
                    account_is_blog_writer: !$.isNumeric($("#admin-account-is-blog-writer").val()) ? 0 : $("#admin-account-is-blog-writer").val(),
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0]);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        renderListItem({
                            type: "administrator",
                            id: response.data.id,
                            action: response.data.action,
                            container: ".administrators-data-list",
                            containerListItem: ".table-data-list-item"
                        });
                        dismissModal("#manage-admin-account-container");
                        $(".admin-account-input-item").val("");
                        Modules.toggleToastContainer({
                            message: response.data.msg,
                            status: Modules.status.OKAY
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

        //Change admin account password - manual
        $(document).on("click", "#change-admin-account-password-btn", function(){
            let btn, old_account_password, account_password, account_password_confirm;
            btn = $(this),
            old_account_password = $("#old_account_password").val(),
            account_password = $("#account_password").val(),
            account_password_confirm = $("#account_password_confirm").val();

            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "change_user_account_password_manual",
                data: {
                    old_account_password: old_account_password,
                    account_password: account_password,
                    account_password_confirm: account_password_confirm
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0]);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#change-password-manual-container");
                        $(".admin-account-password-input-item").val("");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Upload profile photo
        $(document).on("change", "#admin-profile-photo-uploader-input", function(){
            if(!Modules.WEBP_SUPPORTED){
                Modules.toggleToastContainer({
                    message: "Sorry! The browser doesn't support webp image conversion.",
                    status: Modules.status.FAILED
                });
                return;
            }

            $dis = $(this);
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    if(typeof data === "object"){
                        $dis.val("");
                        switch(data.status){
                            case "_OK":
                                const hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";
                                const name = "cedijob_uapp" + (new Date().getTime()) + Modules.uuid(), nameJPG = name + ".jpg", nameWebp = name + ".webp";
                                const imageFile = CustomPhotoProcessor.dataURItoBlob(data.image);
                                const files = [];
                                let f2 = false, f3 = hasWebp == "no" ? true : false, f4 = hasWebp == "no" ? true : false;

                                files.push({
                                    name: Modules.UPLOADPATHS.PROFILEPHOTOLARGE + nameJPG,
                                    content: imageFile
                                });
                                
                                //source 2
                                CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                    if (data.status === "_OK") {
                                        Modules.uploadFilesToBuckets3DO([
                                            {
                                                name: Modules.UPLOADPATHS.PROFILEPHOTO + nameJPG,
                                                content: Modules.dataURItoBlob(data.image)
                                            }
                                        ]).then(function(result){
                                            f2 = true;
                                        });
                                    }
                                    else {
                                        Modules.toggleToastContainer({
                                            message: data.statusText,
                                            status: Modules.status.FAILED
                                        });
                                    }
                                }, {
                                    minWidth: 80,
                                    maxWidthAspectRatio: 100,
                                    maxHeightAspectRatio: 90,
                                });

                                if(hasWebp === "yes"){

                                    //source 3
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            Modules.uploadFilesToBuckets3DO([
                                                {
                                                    name: Modules.UPLOADPATHS.PROFILEPHOTOLARGE + nameWebp,
                                                    content: Modules.dataURItoBlob(data.image)
                                                }
                                            ]).then(function(result){
                                                f3 = true;
                                            });
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 400,
                                        maxHeightAspectRatio: 380,
                                        format: "image/webp"
                                    });

                                    //source 4
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            Modules.uploadFilesToBuckets3DO([
                                                {
                                                    name: Modules.UPLOADPATHS.PROFILEPHOTO + nameWebp,
                                                    content: Modules.dataURItoBlob(data.image)
                                                }
                                            ]).then(function(result){
                                                f4 = true;
                                            })
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 100,
                                        maxHeightAspectRatio: 90,
                                        format: "image/webp"
                                    });

                                }

                                //Upload photos
                                const uploadMultipleFiles = Modules.uploadFilesToBuckets3DO(files, function(err){
                                    Modules.togglePageLoader(false);
                                    Modules.toggleToastContainer({
                                        message: Modules.status.UNKNOWN_ERROR,
                                        status: Modules.status.FAILED
                                    });
                                });
                                
                                uploadMultipleFiles.then(function(result){
                                    if(typeof result === undefined || typeof result === "undefined"){
                                        Modules.togglePageLoader(false);
                                        Modules.toggleToastContainer({
                                            message: Modules.status.UNKNOWN_ERROR,
                                            status: Modules.status.FAILED
                                        });
                                        return;
                                    }
                                    
                                   //Begin::Upload photo
                                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                        task: "upload_user_profile_photo_two",
                                        data: {
                                            user_id: __GLOBALS__.USERID,
                                            is_admin: "yes",
                                            filename: nameJPG,
                                            has_webp: hasWebp
                                        }
                                    }, (response) => {
                                        Modules.togglePageLoader(false);
                                        if(Modules.isValidJSON(response)){
                                            if(response.status == Modules.status.OKAY){
                                                let tInt = setInterval(function(){
                                                    if(f2 === true && f3 === true && f4 === true){
                                                        document.querySelectorAll(".will-change-profile-photo").forEach(function(item){
                                                            item.src = response.photo;
                                                        });
            
                                                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                                            task: "update_profile_photo_session_var",
                                                            data: {
                                                                photo: response.photo
                                                            }
                                                        }, (response) => {});
                                                    }
                                                    else{
                                                        Modules.togglePageLoader(false);
                                                        Modules.toggleToastContainer({
                                                            message: Modules.status.UNKNOWN_ERROR,
                                                            status: Modules.status.FAILED
                                                        });
                                                    }
                                                }, 2000);
                                                return;
                                            }
                                            Modules.toggleToastContainer({
                                                message: response.data,
                                                status: Modules.status.FAILED
                                            });
                                            return;
                                        }
                                        Modules.toggleToastContainer({
                                            message: Modules.status.UNKNOWN_ERROR,
                                            status: Modules.status.FAILED
                                        });
                                    });
                                    //End::Upload photo 
                                });
                                break;
                            case "_FAILED":
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: data.statusText,
                                    status: Modules.status.OKAY
                                });
                                break;
                        }
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 400,
                    maxHeightAspectRatio: 320,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one photo.",
                    status: Modules.status.FAILED
                });
            }
        });

        //Preview admin account details
        $(document).on("click", ".preview-admin-account-details-btn", function(){
            let btn;
            btn = $(this);
            
            $(".preview-admin-account-permission-item").each(function(){
                $(this).prop("checked", false).val("0");
            });

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "view_admin_account_details",
                data: {
                    user_id: btn.attr("data-user-id"),
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        //Render basic profile
                        $("#preview-admin-account-email-address").text(response.data.email_address);
                        $("#preview-admin-account-role").text(response.data.envoyer_role.capitalize());
                        $("#preview-admin-account-first-name").text(response.data.first_name.capitalize());
                        $("#preview-admin-account-last-name").text(response.data.last_name.capitalize());
                        $("#preview-admin-gender").text(response.data.gender.capitalize());
                        $("#preview-admin-account-country-dial-code").text(response.data.mobile_number_country_number);
                        $("#preview-admin-account-mobile-number").text(response.data.mobile_number);
                        $("#preview-admin-account-is-blog-writer").text(parseInt(response.data.is_blog_writer) > 0 ? "Yes" : "No");

                        //Render permissions
                        const permissions = response.data.permissions;
                        for(let x in permissions){
                            $(".preview-admin-account-permission-item").each(function(){
                                if($(this).attr("name") == x && parseInt(permissions[x]) > 0){
                                    $(this).prop("checked", true);
                                }
                            });
                        }
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

        //Edit admin account details
        $(document).on("click", ".edit-admin-account-details-btn", function(){
            let btn;
            btn = $(this);

            $(".admin-account-permission-item").each(function(){
                $(this).prop("checked", false).val("0");
            });

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "view_admin_account_details",
                data: {
                    user_id: btn.attr("data-user-id"),
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        //Render basic profile
                        $("#admin-account-id").val(btn.attr("data-user-id"));
                        $("#admin-account-email-address").val(response.data.email_address);
                        $("#admin-account-role").val(response.data.envoyer_role);
                        $("#admin-account-first-name").val(response.data.first_name);
                        $("#admin-account-last-name").val(response.data.last_name);
                        $("#admin-gender").val(response.data.gender);
                        $("#admin-account-country-dial-code").val(response.data.mobile_number_country_number);
                        $("#admin-account-mobile-number").val(response.data.mobile_number);
                        $("#admin-account-is-blog-writer").val(parseInt(response.data.is_blog_writer));

                        //Render permissions
                        const permissions = response.data.permissions;
                        for(let x in permissions){
                            $(".admin-account-permission-item").each(function(){
                                $(this).val(permissions[x]);
                                if($(this).attr("name") == x && parseInt(permissions[x]) > 0) $(this).prop("checked", true);
                            });
                        }
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

        //Edit admin profile
        $(document).on("click", ".edit-user-profile-btn", function(){
            let btn;
            btn = $(this);

            $(".admin-account-permission-item").each(function(){
                $(this).prop("checked", false).val("0");
            });

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "view_admin_account_details",
                data: {
                    user_id: __GLOBALS__.USERID,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $("#edit-admin-account-first-name").val(response.data.first_name);
                        $("#edit-admin-account-last-name").val(response.data.last_name);
                        $("#edit-admin-account-dial-code").val(response.data.mobile_number_country_number);
                        $("#edit-admin-account-mobile-number").val(response.data.mobile_number);
                        $("#edit-admin-account-user-name").val(response.data.user_name);
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

        //Block/Unblock admin account
        $(document).on("click", ".manage-admin-account-block-btn", function(){
            let btn;
            btn = $(this);

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_admin_account",
                data: {
                    user_id: btn.attr("data-user-id"),
                    status: btn.attr("data-block-status")
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        renderListItem({
                            type: "administrator",
                            id: btn.attr("data-user-id"),
                            action: "replace",
                            container: ".administrators-data-list",
                            containerListItem: ".table-data-list-item"
                        });
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", "#save-admin-account-edit-info-btn", function(){
            let btn;
            btn = $(this);

            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "edit_admin_profile",
                data: {
                    user_id: __GLOBALS__.USERID,
                    first_name: $("#edit-admin-account-first-name").val(),
                    last_name: $("#edit-admin-account-last-name").val(),
                    mobile_number_country_number: $("#edit-admin-account-dial-code").val(),
                    mobile_number: $("#edit-admin-account-mobile-number").val(),
                    user_name: $("#edit-admin-account-user-name").val(),
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0]);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#edit-user-profile-modal");
                        $(".kt-profile-full-name").text($("#edit-admin-account-first-name").val() + " " + $("#edit-admin-account-last-name").val());
                        $(".kt-profile-user-name").text("@" + $("#edit-admin-account-user-name").val());
                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "update_profile_names_session_var",
                            data: {
                                full_name: $("#edit-admin-account-first-name").val() + " " + $("#edit-admin-account-last-name").val(),
                                user_name: $("#edit-admin-account-user-name").val()
                            }
                        }, (response) => {
                            $(".admin-account-edit-input-item").val("");
                        });
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".view-modal-menu-list-item-btn", function(){
            let self, container, target, title, titleContainer;
            self = $(this);
            target = self.attr("data-target");
            title = self.attr("data-title");
            container = $(self.attr("data-container"));
            titleContainer = $(self.attr("data-title-container"));
            container.removeClass("active");
            container.each(function(){
                if($(this).attr("data-target") == target){
                    $(this).addClass("active");
                    self.prevAll().removeClass("active");
                    self.nextAll().removeClass("active");
                    self.addClass("active");
                    titleContainer.text(title);
                }
            });
        });

        $(document).on("click", ".view-info-modal-closer-btn", function(){
            $(this).closest(".view-info-modal").removeClass("active").removeClass("more-z");
        });

        //Upload user profile photo
        $(document).on("change", "#user-profile-photo-uploader", function(){
            $dis = $(this);
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    if(typeof data === "object"){
                        $dis.val("");
                        switch(data.status){
                            case "_OK":
                                //Upload photo
                                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                    task: "upload_user_profile_photo",
                                    data: Modules.encodeJSON({
                                        user_id: activeProfileViewId,
                                        is_admin: "yes"
                                    }),
                                    files: [
                                        {
                                            name: "file",
                                            data: CustomPhotoProcessor.dataURItoBlob(data.image)
                                        }
                                    ]
                                }, (response) => {
                                    Modules.togglePageLoader(false);
                                    if(Modules.isValidJSON(response)){
                                        if(response.status == Modules.status.OKAY){

                                            document.querySelectorAll(".user-profile-view-image-item").forEach(function(item){
                                                item.src = data.image;
                                            });

                                            document.querySelectorAll(".user-list-photo-item").forEach(function(item){
                                                if(item.getAttribute("data-user-id") == activeProfileViewId){
                                                    item.src = data.image;
                                                }
                                            });

                                            return;
                                        }
                                        Modules.toggleToastContainer({
                                            message: response.data
                                        });
                                        return;
                                    }
                                    Modules.toggleToastContainer({
                                        message: Modules.status.UNKNOWN_ERROR,
                                        status: Modules.status.FAILED
                                    });
                                });
                                break;
                            case "_FAILED":
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: data.statusText,
                                    status: Modules.status.OKAY
                                });
                                break;
                        }
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 400,
                    maxHeightAspectRatio: 320,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one photo.",
                    status: Modules.status.FAILED
                });
            }
        });

        $(document).on("change", "#create-new-user-account-item-profile-photo", function(){
            $dis = $(this);
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    Modules.togglePageLoader(false);
                    if(typeof data === "object"){
                        $dis.val("");
                        switch(data.status){
                            case "_OK":
                                Modules.render.renderSelectedPhoto({
                                    photo: data.image
                                }, {
                                    container: $(".create-new-user-main-profile-photo-container"),
                                    additionalClass: "create-new-user-main-profile-photo"
                                });
                                
                                let hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";
                                let imageFile = CustomPhotoProcessor.dataURItoBlob(data.image);

                                //source 2
                                CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                    if (data.status === "_OK") {
                                        $(".create-new-user-main-profile-photo img").attr("data-src-2", data.image);
                                    }
                                    else {
                                        Modules.toggleToastContainer({
                                            message: data.statusText,
                                            status: Modules.status.FAILED
                                        });
                                    }
                                }, {
                                    minWidth: 80,
                                    maxWidthAspectRatio: 100,
                                    maxHeightAspectRatio: 90,
                                });

                                if(hasWebp === "yes"){

                                    //source 3
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            $(".create-new-user-main-profile-photo img").attr("data-src-3", data.image);
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 400,
                                        maxHeightAspectRatio: 380,
                                        format: "image/webp"
                                    });

                                    //source 4
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            $(".create-new-user-main-profile-photo img").attr("data-src-4", data.image);
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 100,
                                        maxHeightAspectRatio: 90,
                                        format: "image/webp"
                                    });

                                }
                                break;
                            case "_FAILED":
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: data.statusText,
                                    status: Modules.status.OKAY
                                });
                                break;
                        }
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 400,
                    maxHeightAspectRatio: 320,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one photo.",
                    status: Modules.status.FAILED
                });
            }
        });

        $(document).on("change", "#create-new-user-account-item-verification-photo", function(){
            $dis = $(this);
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    Modules.togglePageLoader(false);
                    if(typeof data === "object"){
                        $dis.val("");
                        switch(data.status){
                            case "_OK":
                                Modules.render.renderSelectedPhoto({
                                    photo: data.image
                                }, {
                                    container: $(".create-new-user-main-verification-photo-container"),
                                    additionalClass: "create-new-user-main-verification-photo"
                                });

                                let hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";
                                let imageFile = CustomPhotoProcessor.dataURItoBlob(data.image);

                                if(hasWebp === "yes"){

                                    //source 2
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            $(".create-new-user-main-verification-photo-container img").attr("data-src-2", data.image);
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 800,
                                        maxHeightAspectRatio: 600,
                                        format: "image/webp"
                                    });

                                }
                                break;
                            case "_FAILED":
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: data.statusText,
                                    status: Modules.status.OKAY
                                });
                                break;
                        }
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 800,
                    maxHeightAspectRatio: 600,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one photo.",
                    status: Modules.status.FAILED
                });
            }
        });

        $(document).on("click", ".create-new-user-account-item-profile-photo-item-remove-btn", function(){
            $(this).closest(".create-new-user-account-item-profile-photo-item").remove();
        });

        $(document).on("change", ".create-new-user-account-item-verified-account", function(){
            switch(parseInt($(this).val())){
                case 1:
                    $(".verification-settings-container").removeClass("kt-hidden");
                    break;
                default:
                    $(".verification-settings-container").addClass("kt-hidden");
            }
        });
        
        $(document).on("change", ".create-new-user-account-item-account-type", function(){
            switchCreateUserAccountUserType($(this).val());
        });

        $(document).on("click", ".create-new-user-account-btn", function(){
            initiateUserAccountContainer();
        });

        //Create new user account
        $(document).on("click", "#create-new-user-account-btn", function(){
            if(!Modules.WEBP_SUPPORTED){
                Modules.toggleToastContainer({
                    message: "Sorry! The browser doesn't support webp image conversion.",
                    status: Modules.status.FAILED
                });
                return;
            }

            let btn = $(this), data = {}, files = [], skills = [], action = "", user_id = $(".create-new-user-account-item-uid").val(), profilePhotoFilename = "", verificationPhotoFilename = "";

            data = Modules.serializeForm(".create-new-user-account-item-form");

            $(".create-user-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });

            data.skills = skills;

            if($(".create-new-user-main-profile-photo").length > 0){
                let name = "cedijob_uapp" + (new Date().getTime()) + Modules.uuid(), nameJPG = name + ".jpg", nameWebp = name + ".webp", hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";

                profilePhotoFilename = nameJPG;

                files.push({
                    name: Modules.UPLOADPATHS.PROFILEPHOTOLARGE + nameJPG,
                    content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-profile-photo").find("img").attr("src"))
                });

                files.push({
                    name: Modules.UPLOADPATHS.PROFILEPHOTO + nameJPG,
                    content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-profile-photo").find("img").attr("data-src-2"))
                });

                if(hasWebp == "yes"){
                    files.push({
                        name: Modules.UPLOADPATHS.PROFILEPHOTOLARGE + nameWebp,
                        content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-profile-photo").find("img").attr("data-src-3"))
                    });

                    files.push({
                        name: Modules.UPLOADPATHS.PROFILEPHOTO + nameWebp,
                        content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-profile-photo").find("img").attr("data-src-4"))
                    });
                }
            }
            
            if($(".create-new-user-main-verification-photo").length > 0){
                let name = "cedijob_verp" + (new Date().getTime()) + Modules.uuid(), nameJPG = name + ".jpg", nameWebp = name + ".webp", hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";

                verificationPhotoFilename = nameJPG;

                files.push({
                    name: Modules.UPLOADPATHS.VERIFICATIONPHOTO + nameJPG,
                    content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-verification-photo").find("img").attr("src"))
                });

                if(hasWebp == "yes"){
                    files.push({
                        name: Modules.UPLOADPATHS.VERIFICATIONPHOTO + nameWebp,
                        content: CustomPhotoProcessor.dataURItoBlob($(".create-new-user-main-verification-photo").find("img").attr("data-src-2"))
                    });
                }
            }

            if(files.length > 0){
                Modules.togglePageLoader(true);
                data.profile_photo_filename = profilePhotoFilename;
                data.verification_photo_filename = verificationPhotoFilename;

                const uploadMultipleFiles = Modules.uploadFilesToBuckets3DO(files, function(err){
                    Modules.togglePageLoader(false);
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                });

                uploadMultipleFiles.then(function(result){
                    if(typeof result === undefined || typeof result === "undefined"){
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                        return;
                    }

                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "create_new_user_account",
                        data: data,
                    }, (response) => {
                        Modules.togglePageLoader(false);
                        if(Modules.isValidJSON(response)){
                            if(response.status == Modules.status.OKAY){
                                action = response.action;
                                Modules.togglePageLoader(true);
                                dismissModal("#create_new_user_account_modal");
                                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                    task: "get_single_user_html_data",
                                    data: {
                                        user_id: response.user_id
                                    }
                                }, (response) => {
                                    console.log(response);
                                    try{
                                        Modules.togglePageLoader(false);
                                        if(response.status == Modules.status.OKAY){
                                            $(".table-data-list-item").removeClass("active");
                                            switch(action){
                                                case "add":
                                                    $(".rx-no-data-container").remove();
                                                    $(".users-data-list").prepend(response.data);
                                                    break;
                                                case "replace":
                                                    $(".rx-no-data-container").remove();
                                                    $(".users-data-list").find(".table-data-list-item").each(function(){
                                                        if($(this).attr("data-id") == user_id){
                                                            $(this).replaceWith(response.data);
                                                        }
                                                    });
                                                    break;
                                            }
                                            
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
                                            message: "Failed to fetch user profile.",
                                            status: Modules.status.FAILED
                                        });
                                    }
                                    catch(err){
                                        console.log(err);
                                    }
                                });

                                Modules.toggleToastContainer({
                                    message: response.data,
                                    status: Modules.status.OKAY
                                });
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.FAILED
                            });
                            return;
                        }
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    });
                });
            }
            else{
                Modules.togglePageLoader(true);
                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                    task: "create_new_user_account",
                    data: data,
                }, (response) => {
                    Modules.togglePageLoader(false);
                    if(Modules.isValidJSON(response)){
                        if(response.status == Modules.status.OKAY){
                            action = response.action;
                            Modules.togglePageLoader(true);
                            dismissModal("#create_new_user_account_modal");
                            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                task: "get_single_user_html_data",
                                data: {
                                    user_id: response.user_id
                                }
                            }, (response) => {
                                console.log(response);
                                try{
                                    Modules.togglePageLoader(false);
                                    if(response.status == Modules.status.OKAY){
                                        $(".table-data-list-item").removeClass("active");
                                        switch(action){
                                            case "add":
                                                $(".rx-no-data-container").remove();
                                                $(".users-data-list").prepend(response.data);
                                                break;
                                            case "replace":
                                                $(".rx-no-data-container").remove();
                                                $(".users-data-list").find(".table-data-list-item").each(function(){
                                                    if($(this).attr("data-id") == user_id){
                                                        $(this).replaceWith(response.data);
                                                    }
                                                });
                                                break;
                                        }
                                        
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
                                        message: "Failed to fetch user profile.",
                                        status: Modules.status.FAILED
                                    });
                                }
                                catch(err){
                                    console.log(err);
                                }
                            });

                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.OKAY
                            });
                            return;
                        }
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.FAILED
                        });
                        return;
                    }
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                });
            }
        });

        //Get user account details
        $(document).on("click", ".get-user-account-details-btn", function(){
            let btn, user_id, skillsContainer;
            btn = $(this);
            user_id = btn.attr("data-user-id");
            skillsContainer = $(".create-new-user-skills-container-footer");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_user_account_details",
                data: {
                    user_id: user_id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        let nd = response.data;

                        initiateUserAccountContainer();

                        switchCreateUserAccountUserType(nd.account_type);

                        $(".create-new-user-account-item-uid").val(user_id);

                        if(parseInt(nd.verified_account) > 0){
                            $(".verification-settings-container").removeClass("kt-hidden");
                        }

                        skillsContainer.empty();

                        nd.skills.map(function(skill){
                            Modules.render.renderAddedSkill(skill,{
                                container: skillsContainer,
                                class: "create-user-selected-skill-item"
                            });
                        });

                        if($.trim(nd.email_address).length > 0){
                            $(".create-new-user-account-item-check-email").val("1");
                        }

                        for(let x in nd){
                            $(".create-new-user-account-item-form").each(function(){
                                if(x == $(this).attr("name")){
                                    $(this).val(nd[x]);
                                }
                            });
                        }

                        openModal("#create_new_user_account_modal");
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

        //Get user account details
        $(document).on("click", ".get-project-details-btn", function(){
            let btn, project_id;
            btn = $(this);
            project_id = activeProjectId = btn.attr("data-project-id");
            $(".save-project-note-btn").attr("data-type-id", project_id);

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_details",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: project_id
                }
            }, (response) => {
                console.log(response.data);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        activeProjectApplicantId = response.data.selected_applicant_id;
                        activeProjectClientId = response.data.user_id;
                        activeProjectType = response.data.project_type;
                        activeProjectStatus = response.data.status;
                        activeProjectPaymentType = response.data.payment_type;
                        activeProjectPaymentPrice = parseInt(response.data.maximum_payment) > 0 ? (__GLOBALS__.CURRENCY + response.data.minimum_payment + " - " + __GLOBALS__.CURRENCY + response.data.maximum_payment) : "Amount not stated";
                        
                        Modules.render.renderProjectDescription(response.data, {
                            container: $(".project-description-container"),
                            cardContainer: $(".project-card-container"),
                            attachmentsContainer: $(".projet-preview-attachments"),
                        }, function(){
                            openProjectPreviewModal();
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

        //load project applicants
        $(document).on("click", ".load-project-applicants-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".project-menu-applicants-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }

            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_applicants",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activeProjectId,
                    offset: $(".project-applicant-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderProjectApplicant(response.data, {
                            container: container,
                            project_id: activeProjectId,
                            project_type: activeProjectType,
                            project_status: activeProjectStatus,
                            payment_type: activeProjectPaymentType,
                            payment_price: activeProjectPaymentPrice,
                        }, function(){
                            if(response.data.length === 0 && $(".project-applicant-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No applicant found</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
                            
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //load project review
        $(document).on("click", ".load-project-review-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".project-menu-job-review-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }
            
            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_review",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activeProjectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderReviews(response.data, {
                            container: container,
                            class: "project-review-item"
                        }, function(){
                            if(response.data.length === 0 && $(".project-review-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No review available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
    
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //load project shared files
        $(document).on("click", ".load-project-shared-files-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".project-menu-shared-files-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }
            
            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_shared_project_files",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activeProjectId,
                    offset: $(".job-shared-file-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderProjectSharedFiles(response.data, {
                            container: container,
                            class: "job-shared-file-item"
                        }, function(){
                            if(response.data.length === 0 && $(".job-shared-file-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No files available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
    
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //load project contracts between client and worker
        $(document).on("click", ".load-contracts-between-client-worker-btn", function(){
            let btn, container, modal;
            btn = $(this);
            container = $(".contract-list-display-container");
            modal = $(".contract-preview-component-list-modal");

            activeContractProjectId = btn.attr("data-project-id");
            activeContractClientId = btn.attr("data-client-id");
            activeContractWorkerId = btn.attr("data-worker-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_contracts_between_client_and_worker",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: btn.attr("data-project-id"),
                    client_id: btn.attr("data-client-id"),
                    worker_id: btn.attr("data-worker-id"),
                    offset: 0,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.empty();
                        $(".contract-preview-component-list-modal").find(".preview-component-list-modal-body-preview, .preview-component-back-btn").removeClass("active");
                        $(".contract-preview-component-list-modal").find(".preview-component-list-modal-body-contracts").addClass("active");
                        Modules.render.renderProjectWorkerClientContractList(response.data.contracts || [], {
                            container: container
                        }, function(){
                            modal.addClass("active");
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

        //load more project contracts between client and worker
        $(document).on("click", ".load-project-worker-client-contracts-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".contract-list-display-container");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_contracts_between_client_and_worker",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activeContractProjectId,
                    client_id: activeContractClientId,
                    worker_id: activeContractWorkerId,
                    offset: $(".clickable-preview-component-list-item").length,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.render.renderProjectWorkerClientContractList(response.data.contracts || [], {
                            container: container
                        }, function(){
                            
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

        //Preview contract details
        $(document).on("click", ".clickable-preview-component-list-item", function(){
            let btn, container;
            btn = $(this);
            container = $(".preview-component-list-modal-body-preview");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_contracts_between_client_and_worker_detail",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: btn.attr("data-contract-id"),
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        console.log(response.data);
                        Modules.render.renderProjectWorkerClientContractDetails(response.data, {
                            container: container
                        }, function(){
                            $(".contract-preview-component-list-modal").find(".preview-component-list-modal-body-contracts").removeClass("active");
                            $(".contract-preview-component-list-modal").find(".preview-component-list-modal-body-preview, .preview-component-back-btn").addClass("active");
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

        //Go back to component list
        $(document).on("click", ".preview-component-back-btn", function(){
            $(this).closest(".preview-component-list-modal").find(".preview-component-list-modal-body-preview, .preview-component-back-btn").removeClass("active");
            $(this).closest(".preview-component-list-modal").find(".preview-component-list-modal-body-contracts").addClass("active");
        });
        
        //Close contract preview menu
        $(document).on("click", ".preview-component-list-modal-closer", function(){
            $(this).closest(".preview-component-list-modal").removeClass("active");
        });

        //load all project invoices
        $(document).on("click", ".load-project-invoices-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".project-preview-invoice-items-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }

            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_all_project_invoices",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activeProjectId,
                    offset: $(".clickable-project-preview-invoice-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    console.log(response.data);
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderProjectInvoiceList(response.data, {
                            container: container
                        }, function(){
                            if(response.data.length === 0 && $(".clickable-project-preview-invoice-item").length === 0){
                                container.html(`<h6 class="no-data-indicator tb-mode">No invoice found</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
                            
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        $(document).on("click", ".preview-invoice-details-modal-closer", function(){
            $(this).closest(".preview-invoice-details-modal").removeClass("active");
        });

        //Preview project invoice details
        $(document).on("click", ".clickable-project-preview-invoice-item", function(){
            let btn, container;
            btn = $(this);
            container = $(".preview-invoice-details-modal-body");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_invoice_payment_details",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: btn.attr("data-invoice-id"),
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        console.log(response.data);
                        Modules.render.renderProjectInvoiceListDetails(response.data, {
                            container: container
                        }, function(){
                            $(".preview-invoice-details-modal").addClass("active");
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

        //Preview project messages
        $(document).on("click", ".load-project-messages-btn", function(){
        let btn, container;
        btn = $(this);
        container = $(".profile-menu-messages-container");

        if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
            container.empty();
        }

        if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

        Modules.togglePageLoader(true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "get_project_messages",
            data: {
                admin_id: __GLOBALS__.USERID,
                is_project: "yes",
                project_id: activeProjectId,
                sender_id: activeProjectClientId,
                receiver_id: activeProjectApplicantId,
                offset: $(".project-message-item").length,
            }
        }, (response) => {
            Modules.togglePageLoader(false);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    console.log(response.data);
                    Modules.render.renderProjectMessages(response.data, {
                        container: container,
                        class: "job-message-item"
                    }, function(){
                        if(response.data.length === 0 && $(".project-message-item").length === 0){
                            container.html(`<h6 class="no-data-indicator">No mesages found</h6>`);
                        }
                        else{
                            container.find(".no-data-indicator").remove();
                        }
                        
                        if(btn.hasClass("single-load")){
                            btn.attr("data-loaded", "yes");
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

        //Load project notes
        $(document).on("click", ".load-project-notes-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".project-menu-notes-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }

            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_notes",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    type: "project",
                    type_id: activeProjectId,
                    offset: $(".project-note-item").length
                }
            }, (response) => {
                console.log(response);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderNotes(response.data, {
                            container: container,
                            class: "project-note-item"
                        }, function(){
                            if(response.data.length === 0 && $(".project-note-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No notes available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }

                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //Approve/disapprove project confirmation
        $(document).on("click", ".approve-disapprove-project-btn-item", function(){
            $(".approve-disapprove-job-additional-note").val("");
            $(".approve-disapprove-job-start").val("");
            $(".approve-disapprove-job-header").text($(this).attr("data-status") == "approve" ? "APPROVE" : "DISAPPROVE");
            $(".approve-disapprove-project-btn").attr("data-project-id", $(this).attr("data-project-id")).attr("data-status", $(this).attr("data-status"));
        });

        let appdisBtn = false;
        $(document).on("click", ".approve-disapprove-project-btn", function(){
            appdisBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused approve-disapprove-project-btn-confirm" data-project-id="' + appdisBtn.attr("data-project-id") + '" data-status="' + appdisBtn.attr("data-status") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Approve/disapprove project
        $(document).on("click", ".approve-disapprove-project-btn-confirm", function(){
            let projectId, status, additionalNote;
            projectId = appdisBtn.attr("data-project-id");
            status = appdisBtn.attr("data-status");
            additionalNote = $(".approve-disapprove-job-additional-note").val();
            start = $(".approve-disapprove-job-start").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "approve_disapprove_project",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: projectId,
                    status: status,
                    additional_note: additionalNote,
                    start: start,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#approve_disapprove_job_modal");
                        
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".approve-disapprove-project-btn").each(function(){
                            if($(this).attr("data-project-id") == projectId){
                                switch(status){
                                    case "approve":
                                        appdisBtn.attr("data-status", "disapprove");
                                        appdisBtn.find(".btn-text").text("Disapprove job");
                                        appdisBtn.closest(".table-data-list-item").find(".job-status").text(appdisBtn.attr("data-project-type") == "artisan" ? "Awarded To Applicant" : "Accepting Applicants");
                                        if(appdisBtn.attr("data-project-type") == "artisan") appdisBtn.remove();
                                        break;
                                    case "disapprove":
                                        appdisBtn.attr("data-status", "approve");
                                        appdisBtn.find(".btn-text").text("Approve job");
                                        appdisBtn.closest(".table-data-list-item").find(".job-status").text("In Review");
                                        break;
                                }
                            }
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_project_html_data",
                            data: {
                                id: projectId
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".jobs-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == projectId){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Cancel project confirmation
        let cancelProjectBtn = false;
        $(document).on("click", ".cancel-job-btn-item", function(){
            cancelProjectBtn = $(this);
            $(".cancel-job-additional-note").val("");
            $(".cancel-job-btn").attr("data-project-id", $(this).attr("data-project-id"));
        });
        
        $(document).on("click", ".cancel-job-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this action? It cannot be undone once it\'s completed.',
                button: '<button class="focused-caution cancel-job-btn-confirm" data-project-id="' + cancelProjectBtn.attr("data-project-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Cancel project
        $(document).on("click", ".cancel-job-btn-confirm", function(){
            let projectId, additionalNote;
            projectId = cancelProjectBtn.attr("data-project-id");
            additionalNote = $(".cancel-job-additional-note").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "cancel_project", 
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: projectId,
                    additional_note: additionalNote,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#cancel_job_modal");

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".cancel-job-btn").each(function(){
                            if($(this).attr("data-project-id") == projectId){
                                cancelProjectBtn.closest(".table-data-list-item").find(".job-status").text("Cancelled");
                                cancelProjectBtn.closest(".table-data-list-item").find(".approve-disapprove-project-btn-parent").remove();
                                cancelProjectBtn.remove();
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

        //Load service description
        $(document).on("click", ".get-service-details-btn", function(){
            let btn, marketItemId, container, cardContainer, portfolioContainer, faqContainer, reviewContainer;
            btn = $(this);
            marketItemId = btn.attr("data-service-id");
            container = $(".service-description-container");
            cardContainer = $(".service-card-container");
            portfolioContainer = $(".profile-menu-portfolio-container");
            faqContainer = $(".service-menu-faqs-container");
            reviewContainer = $(".service-menu-reviews-container");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_service_item_details", 
                data: {
                    admin_id: __GLOBALS__.USERID,
                    market_item_id: marketItemId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        activeServiceItemId = marketItemId;
                        $(".save-service-note-btn").attr("data-type-id", activeServiceItemId);
                        Modules.render.renderServiceItemDescription(response.data, {
                            container: container,
                            cardContainer: cardContainer,
                            portfolioContainer: portfolioContainer,
                            faqContainer: faqContainer,
                            reviewContainer: reviewContainer,
                        }, function(){
                            openServicePreviewModal();
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

        //load project review
        $(document).on("click", ".load-service-reviews-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".service-menu-reviews-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }
            
            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_service_review",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: activeServiceItemId,
                    type: "service",
                    offset: $(".service-review-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderReviews(response.data, {
                            container: container,
                            class: "service-review-item"
                        }, function(){
                            if(response.data.length === 0 && $(".service-review-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No review available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
    
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //Load service notes
        $(document).on("click", ".load-service-notes-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".service-menu-notes-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }

            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_notes",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    type: "service",
                    type_id: activeServiceItemId,
                    offset: $(".service-note-item").length
                }
            }, (response) => {
                console.log(response);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderNotes(response.data, {
                            container: container,
                            class: "service-note-item",
                        }, function(){
                            if(response.data.length === 0 && $(".service-note-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No notes available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }

                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //Approve/disapprove service confirmation
        $(document).on("click", ".approve-disapprove-service-btn-item", function(){
            $(".approve-disapprove-service-additional-note").val("");
            $(".approve-disapprove-service-header").text($(this).attr("data-status") == "approve" ? "APPROVE SERVICE" : "DISAPPROVE SERVICE");
            $(".approve-disapprove-service-btn").attr("data-service-id", $(this).attr("data-service-id")).attr("data-status", $(this).attr("data-status"));
        });

        let serviceAppdisBtn = false;
        $(document).on("click", ".approve-disapprove-service-btn", function(){
            serviceAppdisBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused approve-disapprove-service-btn-confirm" data-service-id="' + serviceAppdisBtn.attr("data-service-id") + '" data-status="' + serviceAppdisBtn.attr("data-status") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Approve/disapprove service
        $(document).on("click", ".approve-disapprove-service-btn-confirm", function(){
            let marketItemId, status, additionalNote;
            marketItemId = serviceAppdisBtn.attr("data-service-id");
            status = serviceAppdisBtn.attr("data-status");
            additionalNote = $(".approve-disapprove-service-additional-note").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "approve_disapprove_service_item",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    market_item_id: marketItemId,
                    additional_note: additionalNote,
                    status: status
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#approve_disapprove_service_modal");

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".approve-disapprove-service-btn").each(function(){
                            if($(this).attr("data-service-id") == marketItemId){
                                switch(status){
                                    case "approve":
                                        serviceAppdisBtn.attr("data-status", "disapprove");
                                        serviceAppdisBtn.find(".btn-text").text("Disapprove service");
                                        serviceAppdisBtn.closest(".table-data-list-item").find(".service-status").text("Active");
                                        if(serviceAppdisBtn.attr("data-project-type") == "artisan") serviceAppdisBtn.remove();
                                        break;
                                    case "disapprove":
                                        serviceAppdisBtn.attr("data-status", "approve");
                                        serviceAppdisBtn.find(".btn-text").text("Approve service");
                                        serviceAppdisBtn.closest(".table-data-list-item").find(".service-status").text("In Review");
                                        break;
                                }
                            }
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_service_html_data",
                            data: {
                                id: marketItemId
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".services-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == marketItemId){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Cancel service confirmation
        let cancelServiceBtn;
        $(document).on("click", ".cancel-service-btn-item", function(){
            cancelServiceBtn = $(this);
            $(".cancel-service-additional-note").val("");
            $(".cancel-service-btn").attr("data-service-id", $(this).attr("data-service-id"));
        });

        $(document).on("click", ".cancel-service-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this action? It cannot be undone once it\'s completed.',
                button: '<button class="focused-caution cancel-service-btn-confirm" data-service-id="' + cancelServiceBtn.attr("data-service-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Cancel project
        $(document).on("click", ".cancel-service-btn-confirm", function(){
            let marketItemId, additionalNote;
            marketItemId = cancelServiceBtn.attr("data-service-id");
            additionalNote = $(".cancel-service-additional-note").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "cancel_service", 
                data: {
                    admin_id: __GLOBALS__.USERID,
                    market_item_id: marketItemId,
                    additional_note: additionalNote,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#cancel_service_modal");

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".cancel-service-btn").each(function(){
                            if($(this).attr("data-service-id") == marketItemId){
                                cancelServiceBtn.closest(".table-data-list-item").find(".service-status").text("Cancelled");
                                cancelServiceBtn.closest(".table-data-list-item").find(".approve-disapprove-service-btn-parent").remove();
                                cancelServiceBtn.remove();
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

        //Load purchase item details
        $(document).on("click", ".get-service-purchase-details-btn", function(){
            let btn, projectId, cardContainer, container;
            btn = $(this);
            projectId = btn.attr("data-project-id");
            cardContainer = $(".purchase-card-container");
            requirementsContainer = $(".purchase-requirements-container");
            reviewContainer = $(".purchase-menu-reviews-container");
            container = $(".purchase-description-container");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_purchase_item_details", 
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: projectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        console.log(response.data);
                        activePurchaseItemId = projectId;
                        activePurchaseSellerId = response.data.seller_id;
                        activePurchaseClientId = response.data.user_id;
                        activePurchaseOrderId = response.data.order_id;
                        Modules.render.renderPurchaseItemDetails(response.data, {
                            cardContainer: cardContainer,
                            reviewContainer: reviewContainer,
                            requirementsContainer: requirementsContainer,
                            container: container
                        }, function(){
                            openPurchasePreviewModal();
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

        //Preview purchase messages
        $(document).on("click", ".load-purchase-messages-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".purchase-messages-container");
    
            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }
    
            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_messages",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    is_project: "yes",
                    project_id: activePurchaseItemId,
                    sender_id: activePurchaseClientId,
                    receiver_id: activePurchaseSellerId,
                    offset: $(".purchase-message-item").length,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        console.log(response.data);
                        Modules.render.renderProjectMessages(response.data, {
                            container: container,
                            class: "purchase-message-item"
                        }, function(){
                            if(response.data.length === 0 && $(".purchase-message-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No mesages found</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
                            
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //Load user notes
        $(document).on("click", ".load-purchase-notes-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".purchase-menu-notes-container");
            $(".save-purchase-note-btn").attr("data-type-id", activePurchaseItemId);

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }

            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_notes",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    type: "purchase",
                    type_id: activePurchaseItemId,
                    offset: $(".purchase-note-item").length
                }
            }, (response) => {
                console.log(response);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderNotes(response.data, {
                            container: container,
                            class: "purchase-note-item",
                        }, function(){
                            if(response.data.length === 0 && $(".purchase-note-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No notes available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }

                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //load purchase shared files
        $(document).on("click", ".load-purchase-shared-files-btn", function(){
            let btn, container;
            btn = $(this);
            container = $(".purchase-menu-shared-files-container");

            if(btn.hasClass("single-load") && btn.hasClass("empty-element") && btn.attr("data-loaded") === "no"){
                container.empty();
            }
            
            if(btn.hasClass("single-load") && btn.attr("data-loaded") === "yes") return;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_shared_project_files",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    project_id: activePurchaseItemId,
                    offset: $(".purchase-shared-file-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        container.find(".no-data-indicator").remove();
                        Modules.render.renderProjectSharedFiles(response.data, {
                            container: container,
                            class: "purchase-shared-file-item"
                        }, function(){
                            if(response.data.length === 0 && $(".purchase-shared-file-item").length === 0){
                                container.html(`<h6 class="no-data-indicator">No files available</h6>`);
                            }
                            else{
                                container.find(".no-data-indicator").remove();
                            }
    
                            if(btn.hasClass("single-load")){
                                btn.attr("data-loaded", "yes");
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

        //Preview order invoice details
        $(document).on("click", ".get-order-invoice-details", function(){
            let btn, container;
            btn = $(this);
            container = $(".preview-invoice-details-modal-body");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_order_invoice_details",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    invoice_id: btn.attr("data-order-id"),
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        console.log(response.data);
                        Modules.render.renderProjectInvoiceListDetails(response.data, {
                            container: container
                        }, function(){
                            $(".preview-invoice-details-modal").addClass("active");
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

        //Respond to report
        $(document).on("click", ".mark-report-as-responded-btn", function(){
            let btn, reportId;
            btn = $(this);
            reportId = btn.attr("data-report-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "mark_report_as_responded",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    report_id: reportId,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        btn.closest(".table-data-list-item").find(".report-status").text("RESPONDED");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Respond to message
        $(document).on("click", ".mark-message-as-responded-btn", function(){
            let btn, messageId;
            btn = $(this);
            messageId = btn.attr("data-message-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "mark_message_as_responded",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    message_id: messageId,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        btn.closest(".table-data-list-item").find(".message-status").text("YES");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //New lead
        $(document).on("click", ".add-new-lead-btn", function(){
            $(".lead-input-item").val("");
        });

        //Edit lead
        $(document).on("click", ".edit-lead-btn", function(){
            let btn, id;
            btn = $(this);
            id = btn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_lead",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".lead-input-item[name='id']").val(response.data.id);
                        $(".lead-input-item[name='full_name']").val(response.data.full_name);
                        $(".lead-input-item[name='email_address']").val(response.data.email_address);
                        $(".lead-input-item[name='mobile_number']").val(response.data.mobile_number);
                        $(".lead-input-item[name='country']").val(response.data.country);
                        $(".lead-input-item[name='request_category']").val(response.data.request_category);
                        $(".lead-input-item[name='request_item']").val(response.data.request_item);
                        $(".lead-input-item[name='note']").val(response.data.note);
                        $(".lead-input-item[name='is_business']").val(response.data.is_business);
                        $(".lead-input-item[name='company_name']").val(response.data.company);
                        $(".lead-input-item[name='next_call_date']").val(response.data.next_expected_call_date);
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

        //Delete lead confirm
        let deleteLeadBtn;
        $(document).on("click", ".delete-lead-btn", function(){
            deleteLeadBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this lead record?',
                button: '<button class="focused-caution delete-lead-btn-confirm" data-user-id="' + deleteLeadBtn.attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete lead
        $(document).on("click", ".delete-lead-btn-confirm", function(){
            let id;
            id = deleteLeadBtn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_lead",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-lead").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        //Delete blog confirm
        let deleteBlogBtn;
        $(document).on("click", ".delete-blog-btn", function(){
            deleteBlogBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this blog?',
                button: '<button class="focused-caution delete-blog-btn-confirm" data-blog-id="' + deleteBlogBtn.attr("data-blog-id") + '" data-user-id="' + deleteBlogBtn.attr("data-user-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete blog
        $(document).on("click", ".delete-blog-btn-confirm", function(){
            let id, uid;
            id = deleteBlogBtn.attr("data-blog-id");
            uid = deleteBlogBtn.attr("data-user-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_blog_post",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    is_admin: true,
                    blog_id: id,
                    user_id: uid,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-blog").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        //Block/unblock blog confirm
        let blockUnblockBlogBtn;
        $(document).on("click", ".block-unblock-blog-btn", function(){
            blockUnblockBlogBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused block-unblock-blog-btn-confirm" data-blog-id="' + blockUnblockBlogBtn.attr("data-blog-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Block/unblock blog
        $(document).on("click", ".block-unblock-blog-btn-confirm", function(){
            let id;
            id = blockUnblockBlogBtn.attr("data-blog-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_blog_post",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".block-unblock-blog-btn").each(function(){
                            if(id == $(this).attr("data-blog-id")){
                                $(this).find(".btn-text").text(response.btn_text);
                                blockUnblockBlogBtn.closest(".table-data-list-item-blog").find(".block-status").text(response.block_status);
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

        //Save lead
        $(document).on("click", "#save-lead-btn", function(){
            let btn, data, id, msg, action;
            btn = $(this);

            data = Modules.serializeForm(".lead-input-item");
            data.admin_id = __GLOBALS__.USERID;

            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_lead",
                data: data
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0]);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        id = response.id;
                        msg = response.data;
                        action = response.action;
                        closeModal("#manage_lead_modal");
                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_lead_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".table-data-list-item").removeClass("active");
                                    switch(action){
                                        case "add":
                                            $(".rx-no-data-container").remove();
                                            $(".lead-data-list").prepend(response.data);
                                            break;
                                        case "replace":
                                            $(".rx-no-data-container").remove();
                                            $(".lead-data-list").find(".table-data-list-item").each(function(){
                                                if($(this).attr("data-id") == id){
                                                    $(this).replaceWith(response.data);
                                                }
                                            });
                                            break;
                                    }
                                    
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
                                    message: "Failed to fetch lead info.",
                                    status: Modules.status.FAILED
                                });
                            }
                            catch(err){
                                console.log(err);
                            }
                        });

                        Modules.toggleToastContainer({
                            message: msg,
                            status: Modules.status.OKAY
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

        //Allow/Disallow blog writing confirm
        let allowDisallowBlogBtn;
        $(document).on("click", ".allow-disallow-blog-btn", function(){
            allowDisallowBlogBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused allow-disallow-blog-btn-confirm" data-user-id="' + allowDisallowBlogBtn.attr("data-user-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Allow/Disallow blog writing
        $(document).on("click", ".allow-disallow-blog-btn-confirm", function(){
            let id;
            id = allowDisallowBlogBtn.attr("data-user-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "allow_disallow_blog",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".allow-disallow-blog-btn").each(function(){
                            if(id == $(this).attr("data-user-id")){
                                $(this).find(".btn-text").text(response.btn_text);
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

        //Delete newsletter contact confirm
        let newsletterContactBtn;
        $(document).on("click", ".delete-newsletter-email-btn", function(){
            newsletterContactBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused-caution delete-newsletter-email-btn-confirm" data-id="' + newsletterContactBtn.attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete newsletter contact
        $(document).on("click", ".delete-newsletter-email-btn-confirm", function(){
            let id;
            id = newsletterContactBtn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_newsletter_contact",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-newsletter").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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
        
        //Delete question confirm
        let deleteQuestionBtn;
        $(document).on("click", ".delete-question-btn", function(){
            deleteQuestionBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this question?',
                button: '<button class="focused-caution delete-question-btn-confirm" data-question-id="' + deleteQuestionBtn.attr("data-question-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete question
        $(document).on("click", ".delete-question-btn-confirm", function(){
            let id;
            id = deleteQuestionBtn.attr("data-question-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_question",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-question").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        //Delete answer confirm
        let deleteAnswerBtn;
        $(document).on("click", ".delete-answer-btn", function(){
            deleteAnswerBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this answer?',
                button: '<button class="focused-caution delete-answer-btn-confirm" data-answer-id="' + deleteAnswerBtn.attr("data-answer-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete answer
        $(document).on("click", ".delete-answer-btn-confirm", function(){
            let id;
            id = deleteAnswerBtn.attr("data-answer-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_answer",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-answer").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        //Block/unblock blog confirm
        let blockUnblockQuestionBtn;
        $(document).on("click", ".block-unblock-question-btn", function(){
            blockUnblockQuestionBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused block-unblock-question-btn-confirm" data-question-id="' + blockUnblockQuestionBtn.attr("data-question-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Block/unblock blog
        $(document).on("click", ".block-unblock-question-btn-confirm", function(){
            let id;
            id = blockUnblockQuestionBtn.attr("data-question-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_question",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".block-unblock-question-btn").each(function(){
                            if(id == $(this).attr("data-question-id")){
                                $(this).find(".btn-text").text(response.btn_text);
                                blockUnblockQuestionBtn.closest(".table-data-list-item-question").find(".block-status").text(response.block_status);
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

        //See all messages
        $(document).on("click", ".see-all-messages-btn", function(){
            $(".advanced-filter-item[name='message_sender_email']").val($(this).attr("data-sender-email"));
            $(".advanced-filter-item[name='message_receiver_email']").val($(this).attr("data-receiver-email"));
            closeModal();
            $("#message_pageNum").val(1);
            loadPage('message', '#message_form', $("#message_form").attr("data-view-type"), 'no');
        });

        //Block/unblock message communication confirm
        let blockUnblockMessageCommunicationBtn;
        $(document).on("click", ".block-message-communication-btn", function(){
            blockUnblockMessageCommunicationBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused block-message-communication-btn-confirm" data-sender="' + blockUnblockMessageCommunicationBtn.attr("data-sender") + '" data-receiver="' + blockUnblockMessageCommunicationBtn.attr("data-receiver") + '" data-status="' + blockUnblockMessageCommunicationBtn.attr("data-status") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });
        
        //Block/unblock message communication
        $(document).on("click", ".block-message-communication-btn-confirm", function(){
            let id, senderId, receiverId, status;
            senderId = blockUnblockMessageCommunicationBtn.attr("data-sender");
            receiverId = blockUnblockMessageCommunicationBtn.attr("data-receiver");
            status = blockUnblockMessageCommunicationBtn.attr("data-status");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_message_communication",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    user_id: senderId,
                    related_user_id: receiverId,
                    block_priority: "temporary",
                    block_type: "message",
                    status: status
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".block-message-communication-btn").each(function(){
                            if((senderId == $(this).attr("data-sender") && receiverId == $(this).attr("data-receiver")) || (senderId == $(this).attr("data-receiver") && receiverId == $(this).attr("data-sender"))){
                                $(this).attr("data-status", (status == "unblock" ? "block" : "unblock"));
                                $(this).find(".btn-text").text((status == "unblock" ? "Block communication" : "Unblock communication"));
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

        $(document).on("click", ".new-company-document-btn", function(){
            $(".company-document-file-uploader, .company-document-description").val("");
        });

        $(document).on("click", "#upload-company-document-file-btn", function(){
            let $dis, id, files = [], filename, actual_filename;
            $dis = $(".company-document-file-uploader");
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2) {
                filename = "cdi_co_doc_" + (new Date().getTime()) + Modules.uuid() + "." + Modules.getFileEXT($dis[0].files[0].name);
                actual_filename = $dis[0].files[0].name;

                files.push({
                    name: Modules.UPLOADPATHS.COMPANYDOCUMENT + filename,
                    content: $dis[0].files[0]
                });

                const uploadMultipleFiles = Modules.uploadFilesToBuckets3DO(files, function(err){
                    Modules.togglePageLoader(false);
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                });
                
                uploadMultipleFiles.then(function(result){
                    if(typeof result === undefined || typeof result === "undefined"){
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                        return;
                    }

                    //Begin::Upload file
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "upload_company_document",
                        data: {
                            admin_id: __GLOBALS__.USERID,
                            document_description: $(".company-document-description").val(),
                            filename: filename,
                            actual_filename: actual_filename
                        }
                    }, (response) => {
                        if(Modules.isValidJSON(response)){
                            if(response.status == Modules.status.OKAY){
                                id = response.id;
                                Modules.toggleToastContainer({
                                    message: response.data,
                                    status: Modules.status.OKAY
                                });
                                dismissModal("#upload_company_document_modal");
                                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                    task: "get_single_company_documents_html_data",
                                    data: {
                                        id: id
                                    }
                                }, (response) => {
                                    try{
                                        Modules.togglePageLoader(false);
                                        if(response.status == Modules.status.OKAY){
                                            $(".rx-no-data-container").remove();
                                            $(".document-data-list").prepend(response.data);
                                            
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
                                            message: "Failed to fetch document info.",
                                            status: Modules.status.FAILED
                                        });
                                    }
                                    catch(err){
                                        console.log(err);
                                    }
                                });
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.FAILED
                            });
                            return;
                        }
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    });
                    //End::Upload file
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one file.",
                    status: Modules.status.FAILED
                });
            }
        });

        //Delete document confirm
        let deleteDocumentBtn;
        $(document).on("click", ".delete-company-document-btn", function(){
            deleteDocumentBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this document? It cannot be undone once it\'s removed.',
                button: '<button class="focused-caution delete-company-document-btn-confirm" data-document-id="' + deleteDocumentBtn.attr("data-document-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete document
        $(document).on("click", ".delete-company-document-btn-confirm", function(){
            let id;
            id = deleteDocumentBtn.attr("data-document-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_company_document",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-document").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        $(document).on("change", ".block-priority-selecter", function(){
            let name = $(this).attr("data-target");
            switch($(this).val()){
                case "temporary":
                    $(".manage-user-block-item[name='" + name + "']").removeClass("kt-hidden");
                    break;
                default:
                    $(".manage-user-block-item[name='" + name + "']").addClass("kt-hidden");
                    break;
            }
        });

        $(document).on("click", ".manage-user-blocks-btn", function(){
            let btn, userId;
            btn = $(this);
            userId = btn.attr("data-user-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_user_blocks",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    user_id: userId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".manage-user-block-item[name='user_id']").val(userId);

                        //Messaging
                        switch(response.data.all_messages_priority){
                            case "temporary":
                            case "permanent":
                                $(".manage-user-block-item[name='block_messaging']").val("yes");
                                $(".manage-user-block-item[name='block_messaging_priority']").val(response.data.all_messages_priority);
                                $(".manage-user-block-item[name='block_messaging_date']").val(response.data.all_messages_date);
                                if(response.data.all_messages_priority == "temporary"){
                                    $(".manage-user-block-item[name='block_messaging_date']").removeClass("kt-hidden");
                                }
                                else{
                                    $(".manage-user-block-item[name='block_messaging_date']").addClass("kt-hidden");
                                }
                                break;
                            default:
                                $(".manage-user-block-item[name='block_messaging']").val("no");
                                $(".manage-user-block-item[name='block_messaging_priority']").val("");
                                $(".manage-user-block-item[name='block_messaging_date']").addClass("kt-hidden");
                        }

                        //Job posting
                        switch(response.data.job_post_priority){
                            case "temporary":
                            case "permanent":
                                $(".manage-user-block-item[name='block_job_posting']").val("yes");
                                $(".manage-user-block-item[name='block_job_posting_priority']").val(response.data.job_post_priority);
                                $(".manage-user-block-item[name='block_job_posting_date']").val(response.data.job_post_date);
                                if(response.data.job_post_priority == "temporary"){
                                    $(".manage-user-block-item[name='block_job_posting_date']").removeClass("kt-hidden");
                                }
                                else{
                                    $(".manage-user-block-item[name='block_job_posting_date']").addClass("kt-hidden");
                                }
                                break;
                            default:
                                $(".manage-user-block-item[name='block_job_posting']").val("no");
                                $(".manage-user-block-item[name='block_job_posting_priority']").val("");
                                $(".manage-user-block-item[name='block_job_posting_date']").addClass("kt-hidden");
                        }

                        //Job application
                        switch(response.data.job_application_priority){
                            case "temporary":
                            case "permanent":
                                $(".manage-user-block-item[name='block_job_application']").val("yes");
                                $(".manage-user-block-item[name='block_job_application_priority']").val(response.data.job_application_priority);
                                $(".manage-user-block-item[name='block_job_application_date']").val(response.data.job_application_date);
                                if(response.data.job_application_priority == "temporary"){
                                    $(".manage-user-block-item[name='block_job_application_date']").removeClass("kt-hidden");
                                }
                                else{
                                    $(".manage-user-block-item[name='block_job_application_date']").addClass("kt-hidden");
                                }
                                break;
                            default:
                                $(".manage-user-block-item[name='block_job_application']").val("no");
                                $(".manage-user-block-item[name='block_job_application_priority']").val("");
                                $(".manage-user-block-item[name='block_job_application_date']").addClass("kt-hidden");
                        }

                        //Service sale
                        switch(response.data.service_sale_priority){
                            case "temporary":
                            case "permanent":
                                $(".manage-user-block-item[name='block_service_sale']").val("yes");
                                $(".manage-user-block-item[name='block_service_sale_priority']").val(response.data.service_sale_priority);
                                $(".manage-user-block-item[name='block_service_sale_date']").val(response.data.service_sale_date);
                                if(response.data.service_sale_priority == "temporary"){
                                    $(".manage-user-block-item[name='block_service_sale_date']").removeClass("kt-hidden");
                                }
                                else{
                                    $(".manage-user-block-item[name='block_service_sale_date']").addClass("kt-hidden");
                                }
                                break;
                            default:
                                $(".manage-user-block-item[name='block_service_sale']").val("no");
                                $(".manage-user-block-item[name='block_service_sale_priority']").val("");
                                $(".manage-user-block-item[name='block_service_sale_date']").addClass("kt-hidden");
                        }

                        //Payment withdrawal
                        switch(response.data.payment_withdrawal_priority){
                            case "temporary":
                            case "permanent":
                                $(".manage-user-block-item[name='block_payment_withdrawal']").val("yes");
                                $(".manage-user-block-item[name='block_payment_withdrawal_priority']").val(response.data.payment_withdrawal_priority);
                                $(".manage-user-block-item[name='block_payment_withdrawal_date']").val(response.data.payment_withdrawal_date);
                                if(response.data.payment_withdrawal_priority == "temporary"){
                                    $(".manage-user-block-item[name='block_payment_withdrawal_date']").removeClass("kt-hidden");
                                }
                                else{
                                    $(".manage-user-block-item[name='block_payment_withdrawal_date']").addClass("kt-hidden");
                                }
                                break;
                            default:
                                $(".manage-user-block-item[name='block_payment_withdrawal']").val("no");
                                $(".manage-user-block-item[name='block_payment_withdrawal_priority']").val("");
                                $(".manage-user-block-item[name='block_payment_withdrawal_date']").addClass("kt-hidden");
                        }

                        //User account block
                        $(".manage-user-block-item[name='user_account']").val(parseInt(response.data.user_account_block));

                        //User profile photo
                        $(".manage-user-block-item[name='profile_photo']").val(parseInt(response.data.user_account_photo));

                        //User portfolio
                        $(".manage-user-block-item[name='portfolio']").val(parseInt(response.data.user_account_portfolio));

                        //User blog writing
                        $(".manage-user-block-item[name='blog_writing']").val(parseInt(response.data.user_account_blog_writing));

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

        //Save user blocks
        $(document).on("click", "#save-user-blocks-btn", function(){
            let data;
            data = Modules.serializeForm(".manage-user-block-item");
            data.admin_id = __GLOBALS__.userId

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "set_user_blocks",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        dismissModal("#manage_user_blocks_modal");
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

        //Set notice TO::
        $(document).on("click", ".send-notice-btn", function(){
            $(".send-notice-input-item[name='to']").val($(this).attr("data-user-id"));
            $(".send-notice-input-item[name='subject']").val("notice");
            $(".send-notice-input-item[name='message']").val("");
            $(".send-notice-input-item[name='first_paragraph']").val("no");
        });

        //Send platform notice
        $(document).on("click", "#send-platform-notice-btn", function(){
            let data;
            data = Modules.serializeForm(".send-notice-input-item");
            data.admin_id = __GLOBALS__.userId

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_notice",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#send_notice_modal");
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

        //Get project details
        $(document).on("click", ".get-project-edit-details-btn", function(){
            let btn, projectId, skillsContainer;
            btn = $(this);
            projectId = btn.attr("data-project-id");
            skillsContainer = $(".edit-job-skills-container-footer");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_project_info",
                data: {
                    admin_id: __GLOBALS__.userId,
                    project_id: projectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".edit-job-input-item").val("");
                        
                        skillsContainer.empty();

                        response.data.skills.map(function(skill){
                            Modules.render.renderAddedSkill(skill,{
                                container: skillsContainer,
                                class: "edit-job-selected-skill-item"
                            });
                        });

                        switch(response.data.project_category.toString().toLowerCase()){
                            case "artisans":
                                $(".edit-job-skills-container-footer").addClass("one");
                                break;
                            default:
                                $(".edit-job-skills-container-footer").removeClass("one");
                        }

                        $(".edit-job-input-item[name='project_id']").val(response.data.project_id);
                        $(".edit-job-input-item[name='title']").val(response.data.title);
                        $(".edit-job-input-item[name='description']").val(response.data.description);
                        $(".edit-job-input-item[name='expiry_date']").val(response.data.expiry_date);
                        $(".edit-job-input-item[name='duration']").val(response.data.duration);
                        $(".edit-job-input-item[name='duration_starting_date']").val(response.data.duration_starting_date);
                        $(".edit-job-input-item[name='duration_ending_date']").val(response.data.duration_ending_date);
                        $(".edit-job-input-item[name='payment_type']").val(response.data.payment_type);
                        $(".edit-job-input-item[name='project_category']").val(response.data.project_category);
                        $(".edit-job-input-item[name='minimum_payment']").val(response.data.minimum_payment);
                        $(".edit-job-input-item[name='maximum_payment']").val(response.data.maximum_payment);
                    }
                    else {
                        dismissModal("#edit_job_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.FAILED
                        });
                    }
                    return;
                }
                dismissModal("#edit_job_modal");
                Modules.toggleToastContainer({
                    message: Modules.status.UNKNOWN_ERROR,
                    status: Modules.status.FAILED
                });
            });
        });

        //Save project details
        $(document).on("click", "#save-job-details-btn", function(){
            let btn, projectId, data, skills = [];
            btn = $(this);
            projectId = $(".edit-job-input-item[name='project_id']");
            data = Modules.serializeForm(".edit-job-input-item");
            data.admin_id = __GLOBALS__.userId;

            $(".edit-job-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });

            data.skills = skills;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "edit_project_info",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#edit_job_modal");
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

        //Get service details
        $(document).on("click", ".get-service-edit-details-btn", function(e){
            let btn, serviceId, skillsContainer, featuresContainer;
            btn = $(this);
            serviceId = btn.attr("data-service-id");
            skillsContainer = $(".edit-service-skills-container-footer");
            featuresContainer = $(".edit-service-features-container");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_service_info",
                data: {
                    admin_id: __GLOBALS__.userId,
                    service_id: serviceId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".edit-service-input-item").val("");
                        
                        featuresContainer.empty();

                        skillsContainer.empty();

                        response.data.skills.map(function(skill){
                            Modules.render.renderAddedSkill(skill,{
                                container: skillsContainer,
                                class: "edit-service-selected-skill-item"
                            });

                            SKILLS.map(function(s){
                                if(skill.skill_id == s.skill_id){
                                    Modules.render.renderSkillsFeatures(s.features, {
                                        container: featuresContainer,
                                        class: "edit-service-feature-item",
                                        selected: response.data.features
                                    });
                                }
                            });
                        });

                        $(".edit-service-input-item[name='service_id']").val(response.data.service_id);
                        $(".edit-service-input-item[name='title']").val(response.data.title);
                        $(".edit-service-input-item[name='description']").val(response.data.description);
                        $(".edit-service-input-item[name='category']").val(response.data.category);
                    }
                    else {
                        dismissModal("#edit_service_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.FAILED
                        });
                    }
                    return;
                }
                dismissModal("#edit_service_modal");
                Modules.toggleToastContainer({
                    message: Modules.status.UNKNOWN_ERROR,
                    status: Modules.status.FAILED
                });
            });
        });

        //Save service details
        $(document).on("click", "#save-service-details-btn", function(){
            let btn, projectId, data, skills = [], features = [];
            btn = $(this);
            projectId = $(".edit-service-input-item[name='service_id']");
            data = Modules.serializeForm(".edit-service-input-item");
            data.admin_id = __GLOBALS__.userId;

            $(".edit-service-selected-skill-item").each(function(){
                skills.push($(this).attr("data-skill-id"));
            });

            $(".edit-service-feature-item").each(function(){
                if(this.checked){
                    features.push($(this).attr("data-feature-id"));
                }
            });

            data.skills = skills;
            data.features = features;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "edit_service_info",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#edit_service_modal");
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

        //Request edit notice
        $(document).on("click", "#send-edit-notice-btn", function(){
            let btn, data;
            btn = $(this);
            data = Modules.serializeForm(".send-edit-notice-input-item");
            data.admin_id = __GLOBALS__.userId;
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_edit_notice",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#send_edit_notice_modal");
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

        $(document).on("click", ".send-edit-notice-btn", function(){
            $(".send-edit-notice-input-item[name='additional_message']").val("");
            $(".send-edit-notice-input-item[name='to']").val($(this).attr("data-to"));
            $(".send-edit-notice-input-item[name='id']").val($(this).attr("data-id"));
            $(".send-edit-notice-input-item[name='type']").val($(this).attr("data-type"));
        });

        $(document).on("click", ".add-client-review-btn", function(){
            $(".add-review-input-item").val("");
            switch($(this).attr("data-type")){
                case "service":
                    $(".add-review-input-item-category-container").removeClass("kt-hidden");
                    break;
                case "job":
                    $(".add-review-input-item-category-container").addClass("kt-hidden");
                    break;
            }
            $(".add-review-input-item[name='project_id']").val($(this).attr("data-id"));
            $(".add-review-input-item[name='type']").val($(this).attr("data-type"));

            let projectId;
            projectId = $(".add-review-input-item[name='project_id']").val();
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_review",
                data: {
                    project_id: projectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".add-review-input-item[name='rating']").val(response.data.rating);
                        $(".add-review-input-item[name='review_category']").val(response.data.reviewed_category);
                        $(".add-review-input-item[name='review']").val(response.data.review);
                    }
                    else {}
                    return;
                }
            });
        });

        $(document).on("click", ".preview-client-review-btn", function(){
            $(".preview-review-input-item").val("");
            $(".preview-review-input-item-review").empty();

            switch($(this).attr("data-type")){
                case "service":
                    $(".preview-review-input-item-category-container").removeClass("kt-hidden");
                    break;
                case "job":
                    $(".preview-review-input-item-category-container").addClass("kt-hidden");
                    break;
            }

            let projectId;
            projectId = $(this).attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_review",
                data: {
                    project_id: projectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".preview-review-input-item[name='rating']").val(response.data.rating);
                        $(".preview-review-input-item[name='review_category']").val(response.data.reviewed_category);
                        $(".preview-review-input-item-review").text(response.data.review);
                    }
                    else {
                        dismissModal("#preview_review_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.FAILED
                        });
                    }
                    return;
                }
                dismissModal("#preview_review_modal");
                Modules.toggleToastContainer({
                    message: Modules.status.UNKNOWN_ERROR,
                    status: Modules.status.FAILED
                });
            });
        });

        $(document).on("click", "#send-client-review-btn", function(){
            let btn, data;
            btn = $(this);
            data = Modules.serializeForm(".add-review-input-item");
            data.admin_id = __GLOBALS__.userId;
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "mark_project_as_complete",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#add_review_modal");
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

        $(document).on("click", ".request-for-client-review-btn", function(){
            let btn, type, id;
            btn = $(this);
            type = btn.attr("data-type");
            id = btn.attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "request_project_review",
                data: {
                    id: id,
                    type: type
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Mark project as started confirm
        $(document).on("click", ".mark-project-as-started-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue to mark this job as started?',
                button: '<button class="focused mark-project-as-started-btn-confirm" data-project-id="' + $(this).attr("data-project-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".mark-project-as-started-btn-confirm", function(){
            let btn, projectId;
            btn = $(this);
            projectId = btn.attr("data-project-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "mark_project_as_started",
                data: {
                    project_id: projectId
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".mark-project-as-started-btn").each(function(){
                            if($(this).attr("data-project-id") == projectId){
                                $(this).remove();
                            }
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".request-order-requirements-btn", function(){
            let btn, id;
            btn = $(this);
            id = btn.attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "request_purchase_requirements",
                data: {
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".get-skill-info-btn", function(){
            let btn, id, container;
            btn = $(this);
            id = btn.attr("data-id");
            container = $(".manage-skills-features-container");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_skills_info",
                data: {
                    skill_id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".manage-skill-input-item-features-input").val("");
                        $(".manage-skill-input-item[name='skill_id']").val(response.data.skill_id);
                        $(".manage-skill-input-item[name='skill_name']").val(response.data.skill_name);
                        $(".manage-skill-input-item[name='skill_alias']").val(response.data.skill_alias);
                        $(".manage-skill-input-item[name='skill_category']").val(response.data.skill_category);
                        $(".manage-skill-input-item[name='skill_reference']").val(response.data.skill_reference);
                        $(".manage-skill-input-item[name='visibility']").val(response.data.is_hidden);
                        
                        container.empty();
                        Modules.render.renderManageSkillFeatureItem(response.data.features.reverse(), {
                            container: container
                        }, function(){

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

        $(document).on("click", ".get-video-info-btn", function(){
            let btn, id, container;
            btn = $(this);
            id = btn.attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_video_details_for_editing",
                data: {
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".video-cover-photo-selector-container").empty();
                        $(".manage-video-input-item").val("");
                        $(".manage-video-input-item[name='url']").val(response.data.url);
                        $(".manage-video-input-item[name='title']").val(response.data.title);
                        $(".manage-video-input-item[name='duration']").val(response.data.duration);
                        $(".manage-video-input-item[name='description']").val(response.data.description);
                        $(".manage-video-input-item[name='account_type']").val(response.data.account_type);
                        $(".manage-video-input-item[name='status']").val(response.data.status);
                        $(".manage-video-input-item[name='notify_users_on_signup']").val(response.data.notify_users_on_signup);
                        $(".manage-video-input-item[name='id']").val(id);
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

        let skillFeatureRemove;
        $(document).on("click", ".skill-feature-remove-btn", function(e){
            skillFeatureRemove = $(this);
            callAlert({
                body: 'Are you sure you want to continue to mark this job as started?',
                button: '<button class="focused skill-feature-remove-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".skill-feature-remove-btn-confirm", function(e){
            skillFeatureRemove.closest(".manage-skill-feature-item").remove();
        });

        $(document).on("click", "#add-feature-item-btn", function(e){
            let feature, container, exists;
            feature = $(".manage-skill-input-item-features-input").val();
            container = $(".manage-skills-features-container");
            exists = false;

            if(Modules.isEmpty(feature)){
                Modules.toggleToastContainer({
                    message: "Feature name is required.",
                    status: Modules.status.FAILED
                });
                return;
            }

            $(".manage-skill-feature-item-feature-name-input").each(function(){
                if(feature == Modules.trim($(this).val())){
                    exists = true;
                }
            });

            if(exists){
                Modules.toggleToastContainer({
                    message: "Feature name already exists.",
                    status: Modules.status.FAILED
                });
                return;
            }

            Modules.render.renderManageSkillFeatureItem([{
                id: "",
                feature_name: feature,
                is_hidden: 0,
                is_new: true,
            }], {
                container: container
            }, function(){ 
                $(".manage-skill-input-item-features-input").val("");
                $(".manage-skill-input-item-features-input").focus();
            });
        });

        $(document).on("click", ".new-skill-btn", function(){
            $(".manage-skill-input-item").val("");
            $(".manage-skills-features-container").empty();
            $(".manage-skill-input-item[name='visibility']").val("0");
        });

        $(document).on("click", ".skill-feature-hide-btn", function(){
            let h = parseInt($(this).closest(".manage-skill-feature-item").attr("data-hidden"));
            if(h == 0){
                $(this).attr("title", "Show");
                $(this).removeClass("la-eye-slash").addClass("la-eye");
                $(this).closest(".manage-skill-feature-item").attr("data-hidden", "1");
            }
            else{
                $(this).attr("title", "Hide");
                $(this).removeClass("la-eye").addClass("la-eye-slash");
                $(this).closest(".manage-skill-feature-item").attr("data-hidden", "0");
            }
        });

        //Save skill confirm
        $(document).on("click", "#save-skill-btn", function(e){
            callAlert({
                body: 'Are you sure you want to save this skill?',
                button: '<button class="focused save-skill-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".save-skill-btn-confirm", function(){
            let btn, data, features, featureExists, action, id;
            btn = $(this);
            data = Modules.serializeForm(".manage-skill-input-item");
            data.admin_id = __GLOBALS__.userId;
            features = [];
            featureExists = [];

            $(".manage-skill-feature-item").each(function(){
                let fname = $(this).find(".manage-skill-feature-item-feature-name-input").val();
                if(!Modules.inArray(featureExists, fname)){
                    featureExists.push(fname);
                    features.push({
                        feature_id: $(this).attr("data-id"),
                        is_hidden: $(this).attr("data-hidden"),
                        feature_name: fname
                    });
                }
            });

            data.features = features;
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_skills",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        action = response.action;
                        id = response.id;

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        dismissModal("#add_skill_modal");

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_skill_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".table-data-list-item").removeClass("active");
                                    switch(action){
                                        case "add":
                                            $(".rx-no-data-container").remove();
                                            $(".skill-data-list").prepend(response.data);
                                            break;
                                        case "replace":
                                            $(".rx-no-data-container").remove();
                                            $(".skill-data-list").find(".table-data-list-item").each(function(){
                                                if($(this).attr("data-id") == id){
                                                    $(this).replaceWith(response.data);
                                                }
                                            });
                                            break;
                                    }
                                    
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
                                    message: "Failed to fetch skill info.",
                                    status: Modules.status.FAILED
                                });
                            }
                            catch(err){
                                console.log(err);
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

        $(document).on("click", ".new-video-btn", function(){
            $(".manage-video-input-item").val("");
            $(".video-cover-photo-selector-container").empty();
        });

        $(document).on("change", ".video-cover-photo-selector", function(){
            $dis = $(this);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    if(typeof data === "object"){
                        $dis.val("");
                        switch(data.status){
                            case "_OK":
                                $(".video-cover-photo-selector-container").html(`<img src="${data.image}" class="video-cover-photo-selector-container-item"/>`);

                                let hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";
                                let imageFile = CustomPhotoProcessor.dataURItoBlob(data.image);

                                //source 2
                                CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                    if (data.status === "_OK") {
                                        $(".video-cover-photo-selector-container-item").attr("data-src-2", data.image);
                                    }
                                    else {
                                        Modules.toggleToastContainer({
                                            message: data.statusText,
                                            status: Modules.status.FAILED
                                        });
                                    }
                                }, {
                                    minWidth: 80,
                                    maxWidthAspectRatio: 300,
                                    maxHeightAspectRatio: 250,
                                });

                                if(hasWebp === "yes"){

                                    //source 3
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            $(".video-cover-photo-selector-container-item").attr("data-src-3", data.image);
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 500,
                                        maxHeightAspectRatio: 400,
                                        format: "image/webp"
                                    });

                                    //source 4
                                    CustomPhotoProcessor.handlePhotoSelectSingle(imageFile, function (data) {
                                        if (data.status === "_OK") {
                                            $(".video-cover-photo-selector-container-item").attr("data-src-4", data.image);
                                        }
                                        else {
                                            Modules.toggleToastContainer({
                                                message: data.statusText,
                                                status: Modules.status.FAILED
                                            });
                                        }
                                    }, {
                                        minWidth: 80,
                                        maxWidthAspectRatio: 300,
                                        maxHeightAspectRatio: 250,
                                        format: "image/webp"
                                    });

                                }
                                break;
                            case "_FAILED":
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: data.statusText,
                                    status: Modules.status.OKAY
                                });
                                break;
                        }
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 500,
                    maxHeightAspectRatio: 400,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please select 1 photo",
                    status: Modules.status.FAILED
                });
            }
        });

        //Save video confirm
        $(document).on("click", "#save-video-btn", function(e){
            callAlert({
                body: 'Are you sure you want to save this video?',
                button: '<button class="focused save-video-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".save-video-btn-confirm", function(){
            if(!Modules.WEBP_SUPPORTED){
                Modules.toggleToastContainer({
                    message: "Sorry! The browser doesn't support webp image conversion.",
                    status: Modules.status.FAILED
                });
                return;
            }

            let btn, data, files, action, id;
            btn = $(this);
            data = Modules.serializeForm(".manage-video-input-item");
            data.admin_id = __GLOBALS__.userId;
            files = [];

            if($(".video-cover-photo-selector-container-item").length > 0){
                let name = "cdivid" + (new Date().getTime()) + Modules.uuid(), nameJPG = name + ".jpg", nameWebp = name + ".webp", hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no";

                data.filename = nameJPG;

                files.push({
                    name: Modules.UPLOADPATHS.VIDEOCOVERLARGE + nameJPG,
                    content: CustomPhotoProcessor.dataURItoBlob($(".video-cover-photo-selector-container-item").attr("src"))
                });

                files.push({
                    name: Modules.UPLOADPATHS.VIDEOCOVERSMALL + nameJPG,
                    content: CustomPhotoProcessor.dataURItoBlob($(".video-cover-photo-selector-container-item").attr("data-src-2"))
                });

                if(hasWebp == "yes"){
                    files.push({
                        name: Modules.UPLOADPATHS.VIDEOCOVERLARGE + nameWebp,
                        content: CustomPhotoProcessor.dataURItoBlob($(".video-cover-photo-selector-container-item").attr("data-src-3"))
                    });

                    files.push({
                        name: Modules.UPLOADPATHS.VIDEOCOVERSMALL + nameWebp,
                        content: CustomPhotoProcessor.dataURItoBlob($(".video-cover-photo-selector-container-item").attr("data-src-4"))
                    });
                }
            }

            if(files.length > 0){
                Modules.togglePageLoader(true);

                const uploadMultipleFiles = Modules.uploadFilesToBuckets3DO(files, function(err){
                    Modules.togglePageLoader(false);
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                });

                uploadMultipleFiles.then(function(result){
                    if(typeof result === undefined || typeof result === "undefined"){
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                        return;
                    }

                    //Begin::Upload photo
                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                        task: "add_edit_video",
                        data: data
                    }, (response) => {
                        Modules.togglePageLoader(false);
                        if(Modules.isValidJSON(response)){
                            if(response.status == Modules.status.OKAY){
                                action = response.action;
                                id = response.id;

                                Modules.toggleToastContainer({
                                    message: response.data,
                                    status: Modules.status.OKAY
                                });

                                dismissModal("#add_video_modal");

                                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                    task: "get_single_video_html_data",
                                    data: {
                                        id: id
                                    }
                                }, (response) => {
                                    console.log(response);
                                    try{
                                        Modules.togglePageLoader(false);
                                        if(response.status == Modules.status.OKAY){
                                            $(".table-data-list-item").removeClass("active");
                                            switch(action){
                                                case "add":
                                                    $(".rx-no-data-container").remove();
                                                    $(".video-data-list").prepend(response.data);
                                                    break;
                                                case "replace":
                                                    $(".rx-no-data-container").remove();
                                                    $(".video-data-list").find(".table-data-list-item").each(function(){
                                                        if($(this).attr("data-id") == id){
                                                            $(this).replaceWith(response.data);
                                                        }
                                                    });
                                                    break;
                                            }
                                            
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
                                            message: "Failed to fetch video info.",
                                            status: Modules.status.FAILED
                                        });
                                    }
                                    catch(err){
                                        console.log(err);
                                    }
                                });
                                return;
                            }
                            Modules.toggleToastContainer({
                                message: response.data
                            });
                            return;
                        }
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    });
                    //End::Upload photo
                });
            }
            else{
                //Begin::Upload photo
                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                    task: "add_edit_video",
                    data: data
                }, (response) => {
                    Modules.togglePageLoader(false);
                    if(Modules.isValidJSON(response)){
                        if(response.status == Modules.status.OKAY){
                            action = response.action;
                            id = response.id;

                            Modules.toggleToastContainer({
                                message: response.data,
                                status: Modules.status.OKAY
                            });

                            dismissModal("#add_video_modal");

                            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                task: "get_single_video_html_data",
                                data: {
                                    id: id
                                }
                            }, (response) => {
                                console.log(response);
                                try{
                                    Modules.togglePageLoader(false);
                                    if(response.status == Modules.status.OKAY){
                                        $(".table-data-list-item").removeClass("active");
                                        switch(action){
                                            case "add":
                                                $(".rx-no-data-container").remove();
                                                $(".video-data-list").prepend(response.data);
                                                break;
                                            case "replace":
                                                $(".rx-no-data-container").remove();
                                                $(".video-data-list").find(".table-data-list-item").each(function(){
                                                    if($(this).attr("data-id") == id){
                                                        $(this).replaceWith(response.data);
                                                    }
                                                });
                                                break;
                                        }
                                        
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
                                        message: "Failed to fetch video info.",
                                        status: Modules.status.FAILED
                                    });
                                }
                                catch(err){
                                    console.log(err);
                                }
                            });
                            return;
                        }
                        Modules.toggleToastContainer({
                            message: response.data
                        });
                        return;
                    }
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                });
                //End::Upload photo
            }
        });

        //Delete video confirm
        let deleteVideoBtn;
        $(document).on("click", ".delete-video-btn", function(){
            deleteVideoBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this video?',
                button: '<button class="focused delete-video-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete video
        $(document).on("click", ".delete-video-btn-confirm", function(){
            let id;
            id = deleteVideoBtn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_video",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".table-data-list-item-video").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
                            }
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".send-video-notification-btn", function(){
            $(".manage-video-notification-input-item").val("");
            $(".manage-video-notification-input-item[name='video_id']").val($(this).attr("data-id"));
        });

        //Send video notification
        $(document).on("click", "#send-video-notification-btn", function(){
            let btn, data, id;
            btn = $(this);
            data = Modules.serializeForm(".manage-video-notification-input-item");
            data.admin_id = __GLOBALS__.USERID;
            id = $(".manage-video-notification-input-item[name='video_id']").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_video_notification",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".send-video-notification-btn").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).closest("li").remove();
                            }
                        });

                        dismissModal("#send_video_notification_modal");

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Send platform notification
        $(document).on("click", "#send-platform-notification-btn", function(){
            callAlert({
                body: 'Are you sure you want to send this notification? Make sure there are not errors before you proceed. It\'s very crucial that you check for typos.',
                button: '<button class="focused-caution send-platform-notification-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".new-platform-notification", function(){
            $(".platform-notification-input-item").val("");
        });

        $(document).on("click", ".send-platform-notification-btn-confirm", function(){
            let btn, data;
            btn = $(this);
            data = Modules.serializeForm(".platform-notification-input-item");
            data.admin_id = __GLOBALS__.USERID;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_platform_notification",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#platform_notification_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".get-artisans-closer-to-the-job-btn", function(){
            let btn, id, container;
            btn = $(this);
            id = btn.attr("data-project-id");
            container = $(".list-of-other-artisans-container");

            if(btn.hasClass("clear")){
                $(".search-artisan-input-item-searcher").val("");
                container.empty();
            }

            if(btn.hasClass("search")){
                container.empty();
            }

            if(btn.hasClass("initial")){
                container.empty();
                $(".search-artisan-input-item-searcher").val("");
                $(".list-of-artisans").addClass("kt-hidden");
                $(".get-artisans-closer-to-the-job-btn").attr("data-project-id", id);
                $(".search-artisan-input-item[name='id']").val(id);
                $(".search-artisan-input-item[name='location']").val(btn.attr("data-location"));
                $(".search-artisan-input-item[name='latitude']").val(btn.attr("data-latitude"));
                $(".search-artisan-input-item[name='longitude']").val(btn.attr("data-longitude"));
                $(".artisan-search-location").text(btn.attr("data-location"));
                $(".artisan-search-gps").text(btn.attr("data-latitude") + ", " + btn.attr("data-longitude"));
                $(".search-artisan-input-item[name='target']").val("closer_to_the_job");
            }

            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_artisans_closer_to_the_job",
                data: {
                    project_id: id,
                    target: $(".search-artisan-input-item[name='target']").val(),
                    q: $(".search-artisan-input-item-searcher").val(),
                    offset: $(".other-artisan-item").length
                }
            }, (response) => {
                console.log(response.data);
                try{
                    Modules.togglePageLoader(false);
                    Modules.toggleLoadingBtn(btn[0], false);
                    if (Modules.isValidJSON(response)) {
                        if (response.status == Modules.status.OKAY) {
                            Modules.render.renderOtherArtisans(response.data, {
                                container: container
                            }, function(){

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
                }
                catch(err){
                    console.log(err);
                }
            });
        });

        $(document).on("change", ".search-artisan-input-item[name='target']", function(){
            let btn, id, container, target;
            btn = $(".get-artisans-closer-to-the-job-btn");
            id = btn.attr("data-project-id");
            container = $(".list-of-other-artisans-container");
            target = $(".search-artisan-input-item[name='target']").val();

            if($(this).val() == "closer_to_the_job"){ $(".list-of-artisans").addClass("kt-hidden"); }
            else{ $(".list-of-artisans").removeClass("kt-hidden"); }

            container.empty();

            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_artisans_closer_to_the_job",
                data: {
                    project_id: id,
                    target: target,
                    q: $(".search-artisan-input-item-searcher").val(),
                    offset: $(".other-artisan-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.render.renderOtherArtisans(response.data, {
                            container: container
                        }, function(){

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

        let otherArtisanBtn = null;
        $(document).on("click", ".select-other-artisan-btn", function(){
            otherArtisanBtn = $(this);
            callAlert({
                body: 'Are you sure you want to assign this job to <b>' + $(this).attr("data-name") + '</b>?',
                button: '<button class="focused select-other-artisan-btn-confirm" data-id="' + $(this).attr("data-id") + '" data-name="' + $(this).attr("data-name") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".select-other-artisan-btn-confirm", function(){
            let btn, id, name, projectId;
            btn = $(this);
            id = btn.attr("data-id");
            name = btn.attr("data-name");
            projectId = $(".search-artisan-input-item[name='id']").val();
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(otherArtisanBtn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "change_artisan_job_applicant",
                data: {
                    project_id: projectId,
                    user_id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(otherArtisanBtn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#other_artisans_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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
        
        //Search users - email
        $(document).on("input", ".email-search-recipients-general-input-searcher", function(){
            let input, container, selectedContainer, loader;
            input = $(this);
            container = input.attr("data-container");
            selectedContainer = input.attr("data-selected-container");
            loader = input.closest(".recipients-searcher-input-parent").find(".search-loader");
            
            $(container).empty("");
            Modules.toggleLoadingBtn(loader[0], true);
            clearTimeout(searcherTimeout);
            searcherTimeout = setTimeout(function(){
                Modules.toggleLoadingBtn(loader[0], true);
                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                    task: "search_users",
                    data: {
                        q: input.val(),
                        t: "email"
                    }
                }, (response) => {
                    Modules.toggleLoadingBtn(loader[0], false);
                    if (Modules.isValidJSON(response)) {
                        if (response.status == Modules.status.OKAY) {
                            Modules.render.renderSearchUserItem(response.data, {
                                container: $(container),
                                selectedContainer: selectedContainer,
                                searchContainer: container,
                                class: "general-user-search-add-email-btn"
                            }, function(){

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
            }, 500);
        });

        $(document).on("click", ".general-user-search-add-email-btn", function(){
            let btn, name, email, id, container, exists;
            btn = $(this);
            name = Modules.trim(btn.attr("data-name"));
            email = Modules.trim(btn.attr("data-email"));
            id = Modules.trim(btn.attr("data-id"));
            container = $(btn.attr("data-selected-container"));
            exists = false;
            
            container.find(".recipient-item").each(function(){
                if($(this).attr("data-email") == email){
                    exists = true;
                }
            });

            if(exists){
                Modules.toggleToastContainer({
                    message: email + " is already added.",
                    status: Modules.status.FAILED
                });
                return;
            }

            Modules.render.renderRecipientItem({
                name: name,
                email: email,
                id: id,
            }, {
                container: container
            });

            $(".simple-emailmsgsms-container-recipients-searcher-recipients").removeClass("active");
        });

        $(document).on("keydown", ".email-search-recipients-general-input-searcher", function(e){
            if(e.keyCode == 13){
                let btn, name, email, container, exists;
                btn = $(this);
                name = Modules.trim($(this).val());
                email = Modules.trim($(this).val());
                container = $(btn.attr("data-selected-container"));
                exists = false;
                
                container.find(".recipient-item").each(function(){
                    if($(this).attr("data-email") == email){
                        exists = true;
                    }
                });

                if(exists){
                    Modules.toggleToastContainer({
                        message: email + " is already added.",
                        status: Modules.status.FAILED
                    });
                    return;
                }

                Modules.render.renderRecipientItem({
                    name: name,
                    email: email,
                }, {
                    container: container
                });

                btn.val("");
                btn.focus();
                $(".simple-emailmsgsms-container-recipients-searcher-recipients").removeClass("active");
            }
        });

        $(document).on("click", ".remove-recipient-item", function(){
            $(this).closest(".recipient-item").remove();
        });

        $(document).on("click", ".clear-email-sender-container", function(){
            clearEmailSender();
        });

        $(document).on("click", ".confirm-email-clearer-btn", function(){
            $("#send-simple-email-container").removeClass("active");
            clearEmailSender();
        });

        $(document).on("click", ".send-as-btn", function(){
            $(".send-as-btn").removeClass("active");
            sendAs = $(this).attr("data-send-as");

            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }
            else{
                $(this).addClass("active");
            }
        });

        $(document).on("click", "#send-custom-email-btn", function(){
            callAlert({
                body: 'Are you sure you want to send this email?',
                button: '<button class="focused" id="send-custom-email-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", "#send-custom-email-btn-confirm", function(){
            let btn, emailList, subject, body, asNewsletter;
            btn = $("#send-custom-email-btn");
            emailList = [];
            subject = $("#email-sender-subject").val();
            body = $("#email-sender-body").html();

            $("#email-recipients-selected-container").find(".recipient-item").each(function(){
                emailList.push($(this).attr("data-email"));
            });
            
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_custom_email",
                data: {
                    email_list: emailList,
                    subject: subject,
                    body: body,
                    send_as: sendAs,
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        callAlert({
                            body: 'Message has been successfully sent. Would you like to clear the email message or resend the message to other contacts?',
                            button: '<button class="focused confirm-email-clearer-btn">Yes, clear.</button>',
                            otext: 'No'
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("change", ".manage-watch-list-btn", function(){
            let btn, id, type;
            btn = $(this);
            id = btn.attr("data-id");
            type = btn.attr("data-type");
            
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_watch_list",
                data: {
                    id: id,
                    type: type
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0], false);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Search users - notifications
        $(document).on("input", ".notification-search-recipients-general-input-searcher", function(){
            let input, container, selectedContainer, loader;
            input = $(this);
            container = input.attr("data-container");
            selectedContainer = input.attr("data-selected-container");
            loader = input.closest(".recipients-searcher-input-parent").find(".search-loader");
            
            $(container).empty("");
            Modules.toggleLoadingBtn(loader[0], true);
            clearTimeout(searcherTimeout);
            searcherTimeout = setTimeout(function(){
                Modules.toggleLoadingBtn(loader[0], true);
                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                    task: "search_users",
                    data: {
                        q: input.val(),
                        t: "notification"
                    }
                }, (response) => {
                    Modules.toggleLoadingBtn(loader[0], false);
                    if (Modules.isValidJSON(response)) {
                        if (response.status == Modules.status.OKAY) {
                            Modules.render.renderSearchUserItem(response.data, {
                                container: $(container),
                                selectedContainer: selectedContainer,
                                searchContainer: container,
                                class: "general-user-search-add-notification-btn"
                            }, function(){

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
            }, 500);
        });

        $(document).on("click", ".general-user-search-add-notification-btn", function(){
            let btn, name, email, id, container, exists;
            btn = $(this);
            name = Modules.trim(btn.attr("data-name"));
            email = Modules.trim(btn.attr("data-email"));
            id = Modules.trim(btn.attr("data-id"));
            container = $(btn.attr("data-selected-container"));
            exists = false;
            
            container.find(".recipient-item").each(function(){
                if($(this).attr("data-email") == email){
                    exists = true;
                }
            });

            if(exists){
                Modules.toggleToastContainer({
                    message: name + " is already added.",
                    status: Modules.status.FAILED
                });
                return;
            }

            Modules.render.renderRecipientItem({
                name: name,
                email: email,
                id: id,
            }, {
                container: container
            });

            $(".simple-emailmsgsms-container-recipients-searcher-recipients").removeClass("active");
        });

        $(document).on("click", ".clear-notification-sender-container", function(){
            clearNotificationSender();
        });

        $(document).on("click", ".confirm-notification-clearer-btn", function(){
            $("#send-simple-notification-container").removeClass("active");
            clearNotificationSender();
        });

        $(document).on("click", "#send-custom-notification-btn", function(){
            callAlert({
                body: 'Are you sure you want to send this notification?',
                button: '<button class="focused" id="send-custom-notification-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", "#send-custom-notification-btn-confirm", function(){
            let btn, recipientList, body;
            btn = $("#send-custom-notification-btn");
            recipientList = [];
            body = $("#notification-sender-body").val();

            $("#notification-recipients-selected-container").find(".recipient-item").each(function(){
                recipientList.push($(this).attr("data-id"));
            });
            
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_custom_notification",
                data: {
                    recipient_list: recipientList,
                    body: body,
                    send_as: sendNotificationAs
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        callAlert({
                            body: 'Notification has been successfully sent. Would you like to clear the message or resend the message to other users?',
                            button: '<button class="focused confirm-notification-clearer-btn">Yes, clear.</button>',
                            otext: 'No'
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".send-notification-as-btn", function(){
            $(".send-notification-as-btn").removeClass("active");
            sendNotificationAs = $(this).attr("data-send-as");

            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }
            else{
                $(this).addClass("active");
            }
        });

        //Search users - messages
        $(document).on("input", ".message-search-recipients-general-input-searcher", function(){
            let input, container, selectedContainer, loader;
            input = $(this);
            container = input.attr("data-container");
            selectedContainer = input.attr("data-selected-container");
            loader = input.closest(".recipients-searcher-input-parent").find(".search-loader");
            
            $(container).empty("");
            Modules.toggleLoadingBtn(loader[0], true);
            clearTimeout(searcherTimeout);
            searcherTimeout = setTimeout(function(){
                Modules.toggleLoadingBtn(loader[0], true);
                Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                    task: "search_users",
                    data: {
                        q: input.val(),
                        t: "message"
                    }
                }, (response) => {
                    Modules.toggleLoadingBtn(loader[0], false);
                    if (Modules.isValidJSON(response)) {
                        if (response.status == Modules.status.OKAY) {
                            Modules.render.renderSearchUserItem(response.data, {
                                container: $(container),
                                selectedContainer: selectedContainer,
                                searchContainer: container,
                                class: "general-user-search-add-message-btn"
                            }, function(){

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
            }, 500);
        });

        $(document).on("click", ".general-user-search-add-message-btn", function(){
            let btn, name, email, id, container, exists;
            btn = $(this);
            name = Modules.trim(btn.attr("data-name"));
            email = Modules.trim(btn.attr("data-email"));
            id = Modules.trim(btn.attr("data-id"));
            container = $(btn.attr("data-selected-container"));
            exists = false;
            
            container.find(".recipient-item").each(function(){
                if($(this).attr("data-email") == email){
                    exists = true;
                }
            });

            if(exists){
                Modules.toggleToastContainer({
                    message: name + " is already added.",
                    status: Modules.status.FAILED
                });
                return;
            }

            Modules.render.renderRecipientItem({
                name: name,
                email: email,
                id: id,
            }, {
                container: container
            });

            $(".simple-emailmsgsms-container-recipients-searcher-recipients").removeClass("active");
        });

        $(document).on("click", ".clear-message-sender-container", function(){
            clearMessageSender();
        });

        $(document).on("click", ".confirm-message-clearer-btn", function(){
            $("#send-simple-message-container").removeClass("active");
            clearMessageSender();
        });

        $(document).on("click", "#send-custom-message-btn", function(){
            callAlert({
                body: 'Are you sure you want to send this message?',
                button: '<button class="focused" id="send-custom-message-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", "#send-custom-message-btn-confirm", function(){
            let btn, recipientList, body;
            btn = $("#send-custom-message-btn");
            recipientList = [];
            body = $("#message-sender-body").val();

            $("#message-recipients-selected-container").find(".recipient-item").each(function(){
                recipientList.push($(this).attr("data-id"));
            });
            
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_custom_message",
                data: {
                    recipient_list: recipientList,
                    body: body
                }
            }, (response) => {
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        callAlert({
                            body: 'Message has been successfully sent. Would you like to clear the message or resend the message to other users?',
                            button: '<button class="focused confirm-message-clearer-btn">Yes, clear.</button>',
                            otext: 'No'
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".add-to-pick-list-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused add-to-pick-list-btn-confirm" data-id="' + $(this).attr("data-id") + '" data-type="' + $(this).attr("data-type") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".add-to-pick-list-btn-confirm", function(){
            let btn, id, type;
            btn = $(this);
            id = btn.attr("data-id");
            type = btn.attr("data-type");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_picks",
                data: {
                    id: id,
                    type: type
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".add-to-pick-list-btn").each(function(){
                            if($(this).attr("data-id") == id && $(this).attr("data-type") == type){
                                $(this).find(".btn-text").text(response.action == "add" ? "Add to picklist" : "Remove from picklist");
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

        $(document).on("click", ".send-simple-notification-btn", function(){
            let btn, id, type;
            btn = $(this);
            id = btn.attr("data-user-id");
            type = btn.attr("data-type").toString().toLowerCase();
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_simple_user_notification_details",
                data: {
                    id: id,
                    type: type
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        let target, repContainer;

                        switch(type){
                            case "email":
                                target = "#send-simple-email-container";
                                clearEmailSender();
                                break;
                            case "message":
                                target = "#send-simple-message-container";
                                clearMessageSender();
                                break;
                            case "in-app-notification":
                                target = "#send-simple-notification-container";
                                clearNotificationSender();
                                break;
                        }

                        $(target).find(".recipients-selected-container").empty();

                        Modules.render.renderRecipientItem({
                            name: response.data.full_name,
                            email: response.data.email_address,
                            id: response.data.id,
                        }, {
                            container: $(target).find(".recipients-selected-container")
                        });

                        $(".simple-emailmsgsms-container").removeClass("active");
                        $(target).addClass("active");
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

        $(document).on("click", ".clear-warnings-btn", function(){
            let btn, id;
            btn = $(this);
            id = btn.attr("data-user-id");
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "clear_warnings",
                data: {
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        dismissModal("#warnings_modal");
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

        $(document).on("click", ".load-more-warnings-btn", function(){
            let btn, id, container;
            btn = $(this);
            id = btn.attr("data-user-id");
            container = $("#warning-list-container");

            if(btn.hasClass("first")){
                container.empty();
                $(".load-more-warnings-btn-two").attr("data-user-id", id);
                $(".clear-warnings-btn").attr("data-user-id", id);
            }
            
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_user_warnings",
                data: {
                    id: id,
                    offset: $(".warning-list-container-item").length
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.render.renderWarningItems(response.data, {
                            container: container
                        }, function(){

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

        $(document).on("click", ".send-invoice-to-client-btn", function(){
            let amountToPay = (parseFloat($(this).attr("data-charge-agreed")) - parseFloat($(this).attr("data-invoice-amount"))) <= 0 ? 0 : (parseFloat($(this).attr("data-charge-agreed")) - parseFloat($(this).attr("data-invoice-amount")));
            $(".send-invoice-input-agreed-amount").text(Modules.CURRENCY + $(this).attr("data-charge-agreed"));
            $(".send-invoice-input-invoice-amount").text(Modules.CURRENCY + $(this).attr("data-invoice-amount"));
            $(".send-invoice-input-amount-paid").text(Modules.CURRENCY + $(this).attr("data-amount-paid"));
            $(".send-invoice-input-amount-pending").text(Modules.CURRENCY + $(this).attr("data-amount-pending"));
            $(".send-invoice-input-item[name='project_id']").val($(this).attr("data-id"));
            $(".send-invoice-input-item[name='charge']").val(amountToPay);
            $(".send-invoice-input-item[name='topup_state']").val("");
            $(".send-invoice-input-item[name='topup_amount']").val(0);
            $(".send-invoice-input-item[name='details']").val("");
            $(".send-invoice-input-item[name='payment_status']").val("");
        });
        
        $(document).on("click", "#send-client-invoice-btn", function(){
            callAlert({
                body: 'Are you sure you want to issue this invoice to the client? Make sure the form is well checked before proceeding with this action.',
                button: '<button class="focused" id="send-client-invoice-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", "#send-client-invoice-btn-confirm", function(){
            let btn, id, data;
            btn = $(this);
            id = $(".send-invoice-input-item[name='project_id']").val();
            data = Modules.serializeForm(".send-invoice-input-item");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "send_invoice_payments",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        callAlert({
                            body: "Invoice has been successfully created and sent to client.",
                            otext: 'Okay'
                        });

                        dismissModal("#send_invoice_modal");

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_project_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".jobs-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
                            }
                        });
                    }
                    else {
                        callAlert({
                            body: response.data,
                            otext: 'Alright'
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

        $(document).on("click", ".confirm-invoice-btn", function(){
            $(".confirm-invoice-input-item[name='invoice_id']").val($(this).attr("data-id"));
            $(".confirm-invoice-input-item[name='status_channel']").val("");
        });

        $(document).on("click", "#confirm-payment-manually-btn", function(){
            callAlert({
                body: 'Are you sure you want to confirm and indicate this invoice as paid? It cannot be reversed once it\'s confirmed.',
                button: '<button class="focused" id="confirm-payment-manually-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", "#confirm-payment-manually-btn-confirm", function(){
            let btn, id, data;
            btn = $(this);
            id = $(".confirm-invoice-input-item[name='invoice_id']").val();
            data = Modules.serializeForm(".confirm-invoice-input-item");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "confirm_payment_manually",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        dismissModal("#confirm_invoice_modal");

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_invoice_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".job-invoices-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        $(document).on("click", ".prompt-client-invoice-btn", function(){
            let btn, id;
            btn = $(this);
            id = $(this).attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "prompt_client_to_pay",
                data: {
                    invoice_id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".delete-invoice-btn", function(){
            callAlert({
                body: 'Are you sure you want to delete this invoice? It cannot be reversed once it\'s confirmed.',
                button: '<button class="focused delete-invoice-btn-confirm" data-id="' + $(this).attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".delete-invoice-btn-confirm", function(){
            let btn, id;
            btn = $(this);
            id = $(this).attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_invoice_admin",
                data: {
                    invoice_id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".job-invoices-data-list").find(".table-data-list-item").each(function(){
                            if($(this).attr("data-id") == id){
                                $(this).remove();
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

        //Activate or Deactivate payment withdrawal - invoice
        $(document).on("click", ".activate-deactivate-invoice-withdrawal-btn", function(){
            callAlert({
                body: 'Are you sure you want to ' + ($(this).attr("data-status")) + ' withdrawal status for this invoice?',
                button: '<button class="focused activate-deactivate-invoice-withdrawal-btn-confirm" data-id="' + $(this).attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".activate-deactivate-invoice-withdrawal-btn-confirm", function(){
            let btn, id;
            btn = $(this);
            id = $(this).attr("data-id");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "activate_deactivate_invoice_withdrawal_admin",
                data: {
                    invoice_id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_invoice_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".job-invoices-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Activate or Deactivate payment withdrawal - Order
        $(document).on("click", ".activate-deactivate-order-withdrawal-btn", function(){
            callAlert({
                body: 'Are you sure you want to ' + ($(this).attr("data-status")) + ' withdrawal status for this order?',
                button: '<button class="focused activate-deactivate-order-withdrawal-btn-confirm" data-id="' + $(this).attr("data-order-id") + '" data-type="' + $(this).attr("data-type") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        $(document).on("click", ".activate-deactivate-order-withdrawal-btn-confirm", function(){
            let btn, id, type;
            btn = $(this);
            id = $(this).attr("data-id");
            type = $(this).attr("data-type");
            
            Modules.togglePageLoader(true);
            Modules.toggleLoadingBtn(btn[0], true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "activate_deactivate_order_withdrawal_admin",
                data: {
                    id: id,
                    type: type
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                Modules.toggleLoadingBtn(btn[0], false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_service_payment_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".service-payments-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Withdraw available earnings
        $(document).on("click", ".withdraw-available-amount-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this withdrawal? <b>Transfers are only made in Ghana Cedis(GHS). The transfer amount in other currencies such as USD, Naira, Euro, and Pound are not converted to GHS during transfers. The transfer amount is generated based on the sum of all amounts (in Ghana Cedis(GHS)) generated on every job, order, and refunds. If the transfer amount exceeds GHS4500, we will split them and create another request for the remaining amount. Since we don\'t send an amount of less than GHS10 to your payout account, a balance less than the said amount will be kept and added to your next withdrawal request.</b> Note that the action is irreversible. Do you still want to proceed?',
                button: '<button class="focused withdraw-available-amount-btn-confirm" data-user-id="' + $(this).attr("data-user-id") + '" data-type="' + $(this).attr("data-type") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Block user application
        $(document).on("click", ".withdraw-available-amount-btn-confirm", function(){
            let btn, id, type;
            btn = $(this);
            id = btn.attr("data-user-id");
            type = btn.attr("data-type");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "withdraw_available_earnings",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    uid: id,
                    dashboard_view_mode: type
                }
            }, (response) => {
                console.log(response.data);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Block user application confirm
        let blockApplicationBtn;
        $(document).on("click", ".block-application-btn", function(){
            blockApplicationBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused block-application-btn-confirm" data-id="' + blockApplicationBtn.attr("data-id") + '" data-action="' + blockApplicationBtn.attr("data-action") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Block user application
        $(document).on("click", ".block-application-btn-confirm", function(){
            let applicationId, action;
            applicationId = blockApplicationBtn.attr("data-id");
            action = blockApplicationBtn.attr("data-action");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_job_application",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    application_id: applicationId,
                    action: action
                }
            }, (response) => {
                console.log(response.data);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_application_html_data",
                            data: {
                                id: applicationId
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".application-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == applicationId){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Block user review confirm
        let blockReviewBtn;
        $(document).on("click", ".block-review-btn", function(){
            blockReviewBtn = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused block-review-btn-confirm" data-id="' + blockReviewBtn.attr("data-id") + '" data-action="' + blockReviewBtn.attr("data-action") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });
        
        //Block user review
        $(document).on("click", ".block-review-btn-confirm", function(){
            let reviewId, action;
            reviewId = blockReviewBtn.attr("data-id");
            action = blockReviewBtn.attr("data-action");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "block_unblock_job_review",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    review_id: reviewId,
                    action: action
                }
            }, (response) => {
                console.log(response.data);
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_review_html_data",
                            data: {
                                id: reviewId
                            }
                        }, (response) => {
                            console.log(response);
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".review-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == reviewId){
                                            $(this).replaceWith(response.data);
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
                                console.log(err);
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

        //Delete attachment confirm
        let deleteAttachmentBtn;
        $(document).on("click", ".delete-attachment-btn", function(){
            deleteAttachmentBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this attachment?',
                button: '<button class="focused-caution delete-attachment-btn-confirm" data-id="' + deleteAttachmentBtn.attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete attachment
        $(document).on("click", ".delete-attachment-btn-confirm", function(){
            let id;
            id = deleteAttachmentBtn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_attachment",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-attachment").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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

        //Delete requirement confirm
        let deleteRequirementBtn;
        $(document).on("click", ".delete-requirement-btn", function(){
            deleteRequirementBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this requirement?',
                button: '<button class="focused-caution delete-requirement-btn-confirm" data-id="' + deleteRequirementBtn.attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete requirement
        $(document).on("click", ".delete-requirement-btn-confirm", function(){
            let id;
            id = deleteRequirementBtn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "delete_requirement",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        $(".table-data-list-item-requirement").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
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
        
        $(document).on("click", ".add-payout-account-btn", function(){
            $(".payout-account-input").val("");
            $(".payout-account-input[name='account_vendor_name']").html('<option value="">Select option</option>');
            $(".payout-account-input[name='uid']").val($(this).attr("data-user-id"));
        });

        $(".payout-account-input[name='account_vendor_type']").on("change", function(){
            let n = $(".payout-account-input[name='account_vendor_name']");
            switch($(this).val()){
                case "Bank":
                    n.html('<option value="">Select the bank</option>');
                    bank_services.map(function (item) {
                        n.append('<option value="' + item.name + '">' + item.name + '</option>');
                    });
                    break;
                case "Mobile Money":
                    n.html('<option value="">Select the MOMO Vendor</option>');
                    momo_services.map(function (item) {
                        n.append('<option value="' + item.name + '">' + item.name + '</option>');
                    });
                    break;
            }
        });

        //Add payout account confirm
        let addPayoutAccountBtn;
        $(document).on("click", "#save-payout-account-btn", function(){
            addPayoutAccountBtn = $(this);
            callAlert({
                body: 'Are you sure you want to save this account?',
                button: '<button class="focused save-payout-account-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Add payout account
        $(document).on("click", ".save-payout-account-btn-confirm", function(){
            let data, id;
            id = $(".payout-account-input[name='account_id']").val();
            data = Modules.serializeForm(".payout-account-input");
            data.admin_id = __GLOBALS__.USERID;

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_payment_accounts",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#manage_payout_account_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });

                        if(!Modules.isEmpty(id)){
                            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                task: "get_single_payment_account_html_data",
                                data: {
                                    id: id
                                }
                            }, (response) => {
                                console.log(response);
                                try{
                                    Modules.togglePageLoader(false);
                                    if(response.status == Modules.status.OKAY){
                                        $(".table-data-list-item-paymentaccount").each(function(){
                                            if($(this).attr("data-id") == id){
                                                $(this).replaceWith(response.data);
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
                                    console.log(err);
                                }
                            });
                        }
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

        //Edit payout account
        $(document).on("click", ".edit-payout-account-btn", function(){
            let accountId, uid;
            accountId = $(this).attr("data-id");
            uid = $(this).attr("data-user-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_payment_account_details",
                data: {
                    account_id: accountId,
                    uid: uid,
                    admin_id: __GLOBALS__.USERID,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        let n = $(".payout-account-input[name='account_vendor_code']");
                        switch(response.data.account_vendor_type){
                            case "Bank":
                                n.html('<option value="">Select the bank</option>');
                                bank_services.map(function (item) {
                                    n.append('<option value="' + item.code + '">' + item.name + '</option>');
                                });
                                break;
                            case "Mobile Money":
                                n.html('<option value="">Select the MOMO Vendor</option>');
                                momo_services.map(function (item) {
                                    n.append('<option value="' + item.code + '">' + item.name + '</option>');
                                });
                                break;
                        }

                        $(".payout-account-input[name='uid']").val(uid);
                        $(".payout-account-input[name='account_id']").val(response.data.id);
                        $(".payout-account-input[name='account_vendor_type']").val(response.data.account_vendor_type);
                        $(".payout-account-input[name='account_vendor_code']").val(response.data.account_vendor_code);
                        $(".payout-account-input[name='account_name']").val(response.data.account_name);
                        $(".payout-account-input[name='account_number']").val(response.data.account_number_raw);
                        $(".payout-account-input[name='account_type']").val(response.data.account_type);
                        $(".payout-account-input[name='use_as_default_payment']").val(response.data.use_as_default_payment);
                        $(".payout-account-input[name='is_active']").val(response.data.is_active);
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

        //Delete payout account confirm
        let deletePayoutAccountBtn;
        $(document).on("click", ".delete-payout-account-btn", function(){
            deletePayoutAccountBtn = $(this);
            callAlert({
                body: 'Are you sure you want to delete this account?',
                button: '<button class="focused-caution delete-payout-account-btn-confirm" data-id="' + deletePayoutAccountBtn.attr("data-id") +'" data-user-id="' + deletePayoutAccountBtn.attr("data-user-id") +'">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete payout account
        $(document).on("click", ".delete-payout-account-btn-confirm", function(){
            let id, uid;
            id = deletePayoutAccountBtn.attr("data-id");
            uid = deletePayoutAccountBtn.attr("data-user-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "remove_payment_account",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    account_id: id,
                    uid: uid
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".table-data-list-item-paymentaccount").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
                            }
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        $(document).on("click", ".add-new-notice-btn", function(){
            $(".notice-input-item").val("");
        });

        //Save notice confirm
        let saveNoticeBtn;
        $(document).on("click", ".save-notice-btn", function(){
            saveNoticeBtn = $(this);
            callAlert({
                body: 'Are you sure you want to save this notice?',
                button: '<button class="focused-caution save-notice-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete payout account
        $(document).on("click", ".save-notice-btn-confirm", function(){
            let id, data, action;
            id = $(".notice-input-item[name='id']").val();
            data = Modules.serializeForm(".notice-input-item");
            data.admin_id = __GLOBALS__.USERID;
            data.note = $(".notice-input-item-note").html();

            if(Modules.stripTags(data.note) > 140){
                Modules.toggleToastContainer({
                    message: "The length of characters for the note must not exceed 140.",
                    status: Modules.status.OKAY
                });
                return;
            }

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "manage_special_notices",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        id = response.id;
                        action = response.action;
                        dismissModal("#manage_notice_modal");
                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_notice_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".table-data-list-item").removeClass("active");
                                    switch(action){
                                        case "add":
                                            $(".rx-no-data-container").remove();
                                            $(".notice-data-list").prepend(response.data);
                                            break;
                                        case "replace":
                                            $(".rx-no-data-container").remove();
                                            $(".notice-data-list").find(".table-data-list-item").each(function(){
                                                if($(this).attr("data-id") == id){
                                                    $(this).replaceWith(response.data);
                                                }
                                            });
                                            break;
                                    }
                                    
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
                                    message: "Failed to fetch notice info.",
                                    status: Modules.status.FAILED
                                });
                            }
                            catch(err){
                                console.log(err);
                            }
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Edit notice
        $(document).on("click", ".edit-notice-btn", function(){
            let id;
            id = $(this).attr("data-id")
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_special_notice_details",
                data: {
                    id: id,
                    admin_id: __GLOBALS__.USERID,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".notice-input-item[name='id']").val(id);
                        $(".notice-input-item[name='title']").val(response.data.title);
                        $(".notice-input-item-note").html(response.data.note);
                        $(".notice-input-item[name='expiry_date']").val(response.data.expiry_date);
                        $(".notice-input-item[name='country']").val(response.data.country);
                        $(".notice-input-item[name='can_close']").val(response.data.can_close);
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

        //Delete payout account confirm
        let deleteNotice;
        $(document).on("click", ".delete-notice-btn", function(){
            deleteNotice = $(this);
            callAlert({
                body: 'Are you sure you want to delete this notice?',
                button: '<button class="focused delete-notice-btn-confirm" data-id="' + deleteNotice.attr("data-id") +'">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Delete payout account
        $(document).on("click", ".delete-notice-btn-confirm", function(){
            let id;
            id = deleteNotice.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "remove_special_notice",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".table-data-list-item-notice").each(function(){
                            if(id == $(this).attr("data-id")){
                                $(this).remove();
                            }
                        });

                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Change payment transfer status
        let paymentTransfer;
        $(document).on("click", ".change-payment-transfer-status-btn", function(){
            paymentTransfer = $(this);
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused change-payment-transfer-status-btn-confirm">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Change payment transfer status
        $(document).on("click", ".change-payment-transfer-status-btn-confirm", function(){
            let id;
            id = paymentTransfer.attr("data-invoice-id");
            type = paymentTransfer.attr("data-type");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "update_payment_transfer_status",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id,
                    type: type,
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        paymentTransfer.find(".btn-text").text(response.transfer_status == "transferred" ? "Switch to not transferred" : "Switch to transferred");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Save edit mode status
        $(document).on("click", ".save-edit-mode-status", function(){
            let task;
            task = $(".user-portal-edit-mode-input").val();

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: task,
                data: {}
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#edit_user_portal_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Save account status
        $(document).on("click", ".save-account-mode-status", function(){
            let data;
            data = Modules.serializeForm(".user-portal-account-mode-input");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "update_account_status",
                data: data
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        dismissModal("#account_user_portal_modal");
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
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

        //Get account status
        $(document).on("click", ".account-user-portal-modal", function(){
            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_account_status",
                data: {}
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        $(".user-portal-account-mode-input[name='signup_status']").val(response.data.platform_signup_state);
                        $(".user-portal-account-mode-input[name='login_status']").val(response.data.platform_login_state);
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

        //Upload admin background photo
        $(document).on("change", "#admin-background-photo-uploader-input", function(){
            if(!Modules.WEBP_SUPPORTED){
                Modules.toggleToastContainer({
                    message: "Sorry! The browser doesn't support webp image conversion.",
                    status: Modules.status.FAILED
                });
                return;
            }

            $dis = $(this);
            Modules.togglePageLoader(true);
            if($dis[0].files.length > 0 && $dis[0].files.length < 2){
                CustomPhotoProcessor.handlePhotoSelect($dis[0].files, function(data){
                    $dis.val("");
                    if(typeof data === "object"){
                        let name = "cdiphoto" + (new Date().getTime()) + Modules.uuid(), nameJPG = name + ".jpg", nameWebp = name + ".webp", hasWebp, files = [], filename;
                        hasWebp = Modules.WEBP_SUPPORTED === true ? "yes" : "no",
                        filename = nameJPG;

                        files.push({
                            name: Modules.UPLOADPATHS.BACKGROUNDPHOTO + nameJPG,
                            content: CustomPhotoProcessor.dataURItoBlob(data.image)
                        });

                        const uploadMultipleFiles = Modules.uploadFilesToBuckets3DO(files, function(err){
                            Modules.togglePageLoader(false);
                            Modules.toggleToastContainer({
                                message: Modules.status.UNKNOWN_ERROR,
                                status: Modules.status.FAILED
                            });
                        });

                        uploadMultipleFiles.then(function(result){
                            if(typeof result === undefined || typeof result === "undefined"){
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: Modules.status.UNKNOWN_ERROR,
                                    status: Modules.status.FAILED
                                });
                                return;
                            }

                            //Begin::Upload background photo
                            switch(data.status){
                                case "_OK":
                                    //Upload photo
                                    Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                                        task: "upload_admin_background_photo",
                                        data: {
                                            admin_id: __GLOBALS__.USERID,
                                            filename: filename
                                        },
                                    }, (response) => {
                                        Modules.togglePageLoader(false);
                                        Modules.toggleToastContainer({
                                            message: response.data,
                                            status: Modules.status.OKAY
                                        });
                                    });
                                    break;
                                case "_FAILED":
                                    Modules.togglePageLoader(false);
                                    Modules.toggleToastContainer({
                                        message: data.statusText,
                                        status: Modules.status.OKAY
                                    });
                                    break;
                            }
                            //End::Upload background photo
                        });
                    }
                    else{
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: Modules.status.UNKNOWN_ERROR,
                            status: Modules.status.FAILED
                        });
                    }
                },{
                    maxWidthAspectRatio: 1080,
                    maxHeightAspectRatio: 620,
                });
            }
            else{
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: "Please choose one photo to proceed.",
                    status: Modules.status.FAILED
                });
            }
        });

        //Verify payment withdrawal status
        $(document).on("click", ".confirm-withdrawal-payment-verification-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this action?',
                button: '<button class="focused confirm-withdrawal-payment-verification-btn-confirm" data-id="' + $(this).attr("data-id") + '" data-transfer-code="' + $(this).attr("data-transfer-code") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Verify payment withdrawal status confirmation
        $(document).on("click", ".confirm-withdrawal-payment-verification-btn-confirm", function(){
            let btn, id, transferCode;
            btn = $(this);
            id = btn.attr("data-id");
            transferCode = btn.attr("data-transfer-code");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "verify_paystack_transfer",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    transfer_code: transferCode
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        //--
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        
                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_payment_withdrawals_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".table-data-list-item").removeClass("active");
                                    $(".rx-no-data-container").remove();
                                    $(".payment-withdrawals-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                    message: "Failed to refresh withdrawal record.",
                                    status: Modules.status.FAILED
                                });
                            }
                            catch(err){
                                console.log(err);
                            }
                        });
                        //--
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

        //Confirm payment withdrawal
        $(document).on("click", ".confirm-withdrawal-payment-request-btn", function(){
            callAlert({
                body: 'Are you sure you want to continue with this action? It\'s irreversible.',
                button: '<button class="focused confirm-withdrawal-payment-request-btn-confirm" data-id="' + $(this).attr("data-id") + '">Yes, continue.</button>',
                otext: 'No'
            });
        });

        //Confirm payment withdrawal confirmation
        $(document).on("click", ".confirm-withdrawal-payment-request-btn-confirm", function(){
            let btn, id;
            btn = $(this);
            id = btn.attr("data-id");

            Modules.togglePageLoader(true);
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "initiate_payment_transfer",
                data: {
                    admin_id: __GLOBALS__.USERID,
                    id: id
                }
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        //--
                        Modules.toggleToastContainer({
                            message: response.data,
                            status: Modules.status.OKAY
                        });
                        
                        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                            task: "get_single_payment_withdrawals_html_data",
                            data: {
                                id: id
                            }
                        }, (response) => {
                            try{
                                Modules.togglePageLoader(false);
                                if(response.status == Modules.status.OKAY){
                                    $(".table-data-list-item").removeClass("active");
                                    $(".rx-no-data-container").remove();
                                    $(".payment-withdrawals-data-list").find(".table-data-list-item").each(function(){
                                        if($(this).attr("data-id") == id){
                                            $(this).replaceWith(response.data);
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
                                    message: "Failed to refresh withdrawal record.",
                                    status: Modules.status.FAILED
                                });
                            }
                            catch(err){
                                console.log(err);
                            }
                        });
                        //--
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

    });

})();