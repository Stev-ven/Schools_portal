var rx_premium_loader = false;

function addPremium(){
    if(rx_premium_loader) return;
    rx_premium_loader = true;
    var dis = document.getElementById("add_premium_btn"),
    premiumItems = document.getElementsByClassName("premium-item"),
    premiumItemsLength = premiumItems.length,
    age_premium_combinations = {},
    age_ranges = [],
    premium_ranges = [];

    for(var p = 0; p < premiumItemsLength; p++){
        age_ranges.push(premiumItems[p].children[0].children[0].value); 
        premium_ranges.push(premiumItems[p].children[1].children[0].children[1].value); 
        age_premium_combinations[premiumItems[p].children[0].children[0].value] = premiumItems[p].children[1].children[0].children[1].value;
    }

    Main.Premium.addPremium({
        age_ranges : age_ranges,
        premium_ranges : premium_ranges,
        age_premium_combinations : age_premium_combinations,
        company_code : Main.getElementById("premium_company").value,
        date : Main.getElementById("premium_date").value,
        catchJSError: function(err){
            callAlert({body : err});
            Main.unloadItem(dis);
            rx_premium_loader = false;
        },
        success: function(data){
            if(Main.isStringifiedJSON(data)){
                var pData = JSON.parse(data);
                var err = Main.isError(pData.status);

                if(pData.status == "ok"){
                    Main.emptyDataFields(".premium-fsub-item","v");
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
            rx_premium_loader = false;
        },
        error: function(x, t, m){
            if(t === "timeout"){
                callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
            }
            else{
                callAlert({body: "An unknown error occurred. Please try again."});
            }
            Main.unloadItem(dis);
            rx_premium_loader = false;
        },
    });	
}

if(document.getElementById("add_premium_btn") !== "undefined" && document.getElementById("add_premium_btn") !== null){
    var pbtn = document.getElementById("add_premium_btn");
    pbtn.addEventListener("click", addPremium, false);
}
