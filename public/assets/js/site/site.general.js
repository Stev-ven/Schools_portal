var profile_update_loader = false;
$(document).ready(function(){

    $(document).on("click", ".a-link", function(){
        if(Main.hasAttribute($(this)[0], "data-href")){
            Main.redirect($(this).attr("data-href"));
        }
    });

    $(document).on("click", ".closer", function(){
        var $dis = $(this);
        $dis.closest(".closer-parent").hide(0, function(){
            $dis.closest(".modal-main-container").addClass("loading-state");
        });
    });

    $(document).on("click", ".modal-opener-btn", function(){
        var $dis = $(this), target = $dis.attr("data-target");
        if(Main.hasAttribute($dis[0], "data-target")){
            $(target).show(0, function(){
                setTimeout(function(){
                    $(target).find(".modal-main-container").removeClass("loading-state");
                    $(target).find(".first-focus").focus();
					Main.toggleOverflow(true);
                }, 2000);
            });
        }
    });

    $(document).on("click", "#profile_update_btn", function(e){
        if(profile_update_loader === true) return;
        profile_update_loader = true;

        var dis = $(this)[0];
        Main.updateProfile({
            birthday: Main.getElementById("profile_update_birthday").value,
            nationality: Main.getElementById("profile_update_nationality").value,
            title: Main.getElementById("profile_update_title").value,
            description: Main.getElementById("profile_update_description").value,
            catchJSError: function(err){
                callAlert({body : err});
                Main.unloadItem(dis);
                profile_update_loader = false;
            },
            success: function(data){
                if(Main.isStringifiedJSON(data)){
                    var pData = JSON.parse(data);
                    var err = Main.isError(pData.status);
                    $(".d-error").removeClass("active");

                    if(pData.status == "ok"){

                        $("#edit_profile_container").hide(0, function(){
                            $("#edit_profile_container").find(".modal-main-container").removeClass("loading-state");
                        });

                        if('dbind' in pData){
                            Main.__bindData__(pData.dbind);
                        }
                    }
                    else{
                        if('errors' in pData){
                            for(x in pData.errors){
                                $("." + x).addClass("active");
                                $("." + x).find(".ebox").text(pData.errors[x]);
                            }
                        }
                        $(".main_error").addClass("active");
                        $(".main_error").find(".ebox").text(pData.data);
                    }
                }
                else{
                    callAlert({body: "Could not process incoming data but you can check your email to activate your new user acount with a verification code we have sent along with your email. If the account is already created yet you have not received any verification code in your email or spam box, head <a>to this link</a> to try and resend the verification code."});
                }
            },
            beforeSend: function(){
                Main.loadItem(dis);
            },
            complete: function(){
                Main.unloadItem(dis);
                profile_update_loader = false;
            },
            error: function(x, t, m){
                if(t === "timeout"){
                    callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
                }
                else{
                    callAlert({body: "An unknown error occurred. Please try again."});
                }
                profile_update_loader = false;
                Main.unloadItem(dis);
            },
        });	
    });

});