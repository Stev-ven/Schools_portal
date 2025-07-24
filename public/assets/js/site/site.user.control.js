var rx_adduser_loader = false;

function addUser(){
    if(rx_adduser_loader) return;
    rx_adduser_loader = true;
    var dis = document.getElementById("add_user_btn");

    Main.User.addUser({
        title : Main.getElementById("ad_title").value,
        firstname : Main.getElementById("ad_firstname").value,
        lastname : Main.getElementById("ad_lastname").value,
        username : Main.getElementById("ad_username").value,
        password : Main.getElementById("ad_password").value,
        role : Main.getElementById("ad_role").value,
        company_code : Main.getElementById("ad_company_code").value,
        telephone : Main.getElementById("ad_telephone").value,
        catchJSError: function(err){
            callAlert({body : err});
            Main.unloadItem(dis);
            rx_adduser_loader = false;
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
                    Main.emptyDataFields(".ad-emp-var-item","v");
                    callAlert({ body : pData.data });
                }
                else{
                    callAlert({ body : pData.data });
                    if(typeof pData.error !== "undefined" && typeof pData.error !== null){
                        for(var x in pData.error){
                            for(var u in pData.error[x]){
                                if(document.getElementById(u) !== "undefined" && document.getElementById(u) !== null){
                                    document.getElementById(u).innerHTML = pData.error[x][u]; 
                                    document.getElementById(u).classList.add("active"); 
                                }
                            }
                        }
                    }
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
            rx_adduser_loader = false;
        },
        error: function(x, t, m){
            if(t === "timeout"){
                callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
            }
            else{
                callAlert({body: "An unknown error occurred. Please try again."});
            }
            Main.unloadItem(dis);
            rx_adduser_loader = false;
        },
    });	
}

if(document.getElementById("add_user_btn") !== "undefined" && document.getElementById("add_user_btn") !== null){
    var adbtn = document.getElementById("add_user_btn");
    adbtn.addEventListener("click", addUser, false);
}

$(document).ready(function(){
    
    Main.User.getUsersForAdmin({
        offset : 0,
        search : "no",
        searchText : "",
        catchJSError: function(err){
            callAlert({body : err});
            Main.unloadItem(dis);
            rx_adduser_loader = false;
        },
        success: function(data){
            if(Main.isStringifiedJSON(data)){
                var pData = JSON.parse(data),
                err = Main.isError(pData.status);

                if(pData.status == "ok"){
                    var d = pData.data;
                    for(var x in d){
                        console.log(d[x]);
                    }
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
            //Main.loadItem(dis);
        },
        complete: function(){
            //Main.unloadItem(dis);
            rx_adduser_loader = false;
        },
        error: function(x, t, m){
            if(t === "timeout"){
                callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
            }
            else{
                callAlert({body: "An unknown error occurred. Please try again."});
            }
            //Main.unloadItem(dis);
            rx_adduser_loader = false;
        },
    });	
});
