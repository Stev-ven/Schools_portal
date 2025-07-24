/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

(function(){

    //Login func
    const loginFunc = function(){
        let btn, email, password;
        btn = $("#admin-login-btn");
        email = $("#admin-login-email").val();
        password = $("#admin-login-password").val();

        Modules.toggleLoadingBtn(btn[0], true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "sign_in",
            data: {
                account_email: email,
                account_password: password
            }
        }, (response) => {
            Modules.toggleLoadingBtn(btn[0]);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    Modules.redirect(__GLOBALS__.DOMAIN + "index");
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

    //Recover user account password func
    const recoverUserPasswordFunc = function(){
        let btn, email;
        btn = $("#admin-recover-user-password-btn");
        email = $("#admin-email").val();

        Modules.toggleLoadingBtn(btn[0], true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "recover_user_account_password",
            data: {
                account_email: email,
            }
        }, (response) => {
            Modules.toggleLoadingBtn(btn[0]);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    $(".btn-text").text("Resend");
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
    };

    //Change user account password
    const changeUserPasswordFunc = function(){
        let btn, account_token, account_password, account_password_confirm;
        btn = $("#admin-change-user-password-btn");
        account_token = $("#admin-account-token").val();
        account_password = $("#admin-account-password").val();
        account_password_confirm = $("#admin-account-password-confirm").val();

        Modules.toggleLoadingBtn(btn[0], true);
        Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
            task: "change_user_account_password",
            data: {
                account_token: account_token,
                account_password: account_password,
                account_password_confirm: account_password_confirm,
            }
        }, (response) => {
            Modules.toggleLoadingBtn(btn[0]);
            if (Modules.isValidJSON(response)) {
                if (response.status == Modules.status.OKAY) {
                    $("#admin-account-token, #admin-account-password, #admin-account-password-confirm").val("");
                    Modules.toggleToastContainer({
                        message: response.data,
                        status: Modules.status.OKAY
                    }, function(){
                        Modules.redirect(__GLOBALS__.DOMAIN + "login");
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

    $(document).ready(function(){

        //Login 
        $(document).on("click", "#admin-login-btn", loginFunc);
        $(document).on("keydown", ".admin-account-login-input-item", function(e){
            if(e.keyCode === 13){
                loginFunc();
            }
        });

        //Recover user account password
        $(document).on("click", "#admin-recover-user-password-btn", recoverUserPasswordFunc);
        $(document).on("keydown", ".admin-account-recover-password-input-item", function(e){
            if(e.keyCode === 13){
                recoverUserPasswordFunc();
            }
        });

        //Change user account password
        $(document).on("click", "#admin-change-user-password-btn", changeUserPasswordFunc);
        $(document).on("keydown", ".admin-account-change-password-input-item", function(e){
            if(e.keyCode === 13){
                changeUserPasswordFunc();
            }
        });

    });

})();