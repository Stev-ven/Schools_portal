var rx_addrate_loader = false;

function addRate(){
    if(rx_addrate_loader) return;
    rx_addrate_loader = true;
    var dis = document.getElementById("set_rate_btn");

    Main.Rate.addRate({
        commission : Main.getElementById("rate_commission").value,
        security : Main.getElementById("rate_security").value,
        discount : Main.getElementById("rate_discount").value,
        administrative : Main.getElementById("rate_administrative").value,
        profit_loading : Main.getElementById("rate_profile_loading").value,
        company_code : Main.getElementById("rate_company_code").value,
        catchJSError: function(err){
            callAlert({body : err});
            Main.unloadItem(dis);
            rx_addrate_loader = false;
        },
        success: function(data){
            if(Main.isStringifiedJSON(data)){
                var pData = JSON.parse(data),
                err = Main.isError(pData.status),
                errbx = document.querySelectorAll(".ad-tm-item"),
                errbxlen = document.querySelectorAll(".ad-tm-item").length;
                for(var er = 0; er < errbxlen; er++){
                    errbx[er].classList.remove("active");
                }

                if(pData.status == "ok"){
                    Main.emptyDataFields(".rate-sitem-v","v");
                    callAlert({ body : pData.data });
                }
                else{
                    callAlert({ body : pData.data });
                }

            }
            else{
                callAlert({body: "Could not process incoming data. Please try again."});
            }
        },
        beforeSend: function(){
            Main.loadItem(dis);
        },
        complete: function(){
            Main.unloadItem(dis);
            rx_addrate_loader = false;
        },
        error: function(x, t, m){
            if(t === "timeout"){
                callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
            }
            else{
                callAlert({body: "An unknown error occurred. Please try again."});
            }
            Main.unloadItem(dis);
            rx_addrate_loader = false;
        },
    });	
}

if(document.getElementById("set_rate_btn") !== "undefined" && document.getElementById("set_rate_btn") !== null){
    var srbtn = document.getElementById("set_rate_btn");
    srbtn.addEventListener("click", addRate, false);
}
