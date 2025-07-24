function callAndBindEvents(){
    if(document.getElementById("add_user_btn") !== "undefined" && document.getElementById("add_user_btn") !== null){
        document.getElementById("add_user_btn").addEventListener("click", addUser, false);
    }
    if(document.getElementById("add_premium_btn") !== "undefined" && document.getElementById("add_premium_btn") !== null){
        document.getElementById("add_premium_btn").addEventListener("click", addPremium, false);
    }
    if(document.getElementById("set_rate_btn") !== "undefined" && document.getElementById("set_rate_btn") !== null){
        document.getElementById("set_rate_btn").addEventListener("click", addRate, false);
    }
}