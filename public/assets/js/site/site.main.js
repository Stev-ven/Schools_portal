var startpos = 0, lastY, lastX;
var Main = {
    URL : SURL,
    _DOMAIN_ : SURL,
    _RAW_DOMAIN_ : DOMAIN,
    defaultRequestTimeout : 30000,
    getElementById : function(elem){
        return document.getElementById(elem);
    },
	isEmail : function(email){
		var regx = /@/i, regxt = /[.][A-Za-z]{1,}$/i;
		if(regx.test(email) && regxt.test(email)){
			return true;
		}
		else{
			return false;
		}
	},
	trimStr : function(str){
		return str.replace(/^\s+/,"").replace(/\s+$/, "");
	},
    logIn : function(userData){ //Admin login
        //Check whether error catcher exists
        if(!('catchJSError' in userData)){
            alert("Error catching callback function is not defined.");
            throw new Error("Error catching callback function is not defined.");
        }

        //Check for valid incoming object
        if(typeof userData === "undefined" || typeof userData === null){
            userData.catchJSError("There is something wrong with the data you submitted. Please try again.");
            return;
        }
        
        //Check for email
        if(!('email' in userData)){
            userData.catchJSError("Please provide an email address.");
            return;
        }

        //Check for password
        if(!('password' in userData)){
            userData.catchJSError("No password provided. Please provide your password to continue.");
            return;
        }

        //Check action type
        if(!('type' in userData)){
            userData.catchJSError("User type not recognized.");
            return;
        }

        if(!('success' in userData)){
            userData.success = function(){};
        }

        if(!('beforeSend' in userData)){
            userData.beforeSend = function(){};
        }

        if(!('complete' in userData)){
            userData.complete = function(){};
        }

        if(!('error' in userData)){
            userData.error = function(x,t,m){};
        }

        if(!('timeout' in userData)){
            userData.timeout = Main.defaultRequestTimeout;
        }

        var udata = {
            email : userData.email,
            password : userData.password,
        };

        Main.submitDataToServer(
            Main._DOMAIN_,
			"post", 
            userData.type, 
            udata,
            userData.catchJSError,
            userData.success,
            userData.beforeSend,
            userData.complete,
            userData.error,
            userData.timeout,
        );
    },
    recoverAccount : function(userData){ //Admin login
        //Check whether error catcher exists
        if(!('catchJSError' in userData)){
            alert("Error catching callback function is not defined.");
            throw new Error("Error catching callback function is not defined.");
        }

        //Check for valid incoming object
        if(typeof userData === "undefined" || typeof userData === null){
            userData.catchJSError("There is something wrong with the data you submitted. Please try again.");
            return;
        }

        //Check for username
        if(!('email' in userData)){
            userData.catchJSError("Please enter a valid email address.");
            return;
        }

        //Check user type
        if(!('role' in userData)){
            userData.catchJSError("Role not recognized.");
            return;
        }

        //Check action type
        if(!('type' in userData)){
            userData.catchJSError("Action not recognized.");
            return;
        }

        if(!('success' in userData)){
            userData.success = function(){};
        }

        if(!('beforeSend' in userData)){
            userData.beforeSend = function(){};
        }

        if(!('complete' in userData)){
            userData.complete = function(){};
        }

        if(!('error' in userData)){
            userData.error = function(x,t,m){};
        }

        if(!('timeout' in userData)){
            userData.timeout = Main.defaultRequestTimeout;
        }

        var udata = {
            email : userData.email,
            role : userData.role,
        };

        Main.submitDataToServer(
            Main._DOMAIN_,
			"post", 
            userData.type, 
            udata,
            userData.catchJSError,
            userData.success,
            userData.beforeSend,
            userData.complete,
            userData.error,
            userData.timeout,
        );
    },
    changePassword : function(userData){ //Admin login
        //Check whether error catcher exists
        if(!('catchJSError' in userData)){
            alert("Error catching callback function is not defined.");
            throw new Error("Error catching callback function is not defined.");
        }

        //Check for valid incoming object
        if(typeof userData === "undefined" || typeof userData === null){
            userData.catchJSError("There is something wrong with the data you submitted. Please try again.");
            return;
        }

        //Check password
        if(!('password' in userData)){
            userData.catchJSError("Password not present.");
            return;
        }

        //Check for confirmed password
        if(!('password_confirm' in userData)){
            userData.catchJSError("Confirmed password not present.");
            return;
        }

        //Check for username
        if(!('email' in userData)){
            userData.catchJSError("Please enter a valid email address.");
            return;
        }

        //Check recovery code
        if(!('prc' in userData)){
            userData.catchJSError("Recovery code not recognized.");
            return;
        }

        //Check user type
        if(!('role' in userData)){
            userData.catchJSError("Role not recognized.");
            return;
        }

        //Check action type
        if(!('type' in userData)){
            userData.catchJSError("Action not recognized.");
            return;
        }

        if(!('success' in userData)){
            userData.success = function(){};
        }

        if(!('beforeSend' in userData)){
            userData.beforeSend = function(){};
        }

        if(!('complete' in userData)){
            userData.complete = function(){};
        }

        if(!('error' in userData)){
            userData.error = function(x,t,m){};
        }

        if(!('timeout' in userData)){
            userData.timeout = Main.defaultRequestTimeout;
        }

        var udata = {
            email : userData.email,
            role : userData.role,
            prc : userData.prc,
            password : userData.password,
            password_confirm : userData.password_confirm,
        };

        Main.submitDataToServer(
            Main._DOMAIN_,
			"post", 
            userData.type, 
            udata,
            userData.catchJSError,
            userData.success,
            userData.beforeSend,
            userData.complete,
            userData.error,
            userData.timeout,
        );
    },
    submitDataToServer : function(url, type, dataType, data, CatchJSErrorCallBack, SuccessCallBack, beforeSendCallBack, completeCallBack, ErrorCallBack, timeout){
        var request_methods = ["post", "get"];
        
        if(arguments.length < 6){
            alert("Number of arguments provided is not valid. Six(6) or more is needed.");
            throw new Error("Number of arguments provided is not valid. Six(6) or more is needed.");
        }

        if(typeof timeout === "undefined" || typeof timeout === null){
            timeout = Main.defaultRequestTimeout;
        }
        if(typeof(timeout) !== "number"){
            CatchJSErrorCallBack("Timeout value is invalid.");
            return;
        }
        if(timeout <= 5000){
            CatchJSErrorCallBack("Timeout value must not be less than 5000 milliseconds (5 seconds).");
            return;
        }
        if(typeof url === "undefined" || typeof url === null || typeof(url) !== "string"){
            CatchJSErrorCallBack("URL found for this action is not valid. URL must be of string data type.");
            return;
        }
        if(typeof type === "undefined" || typeof type === null || typeof(type) !== "string"){
            CatchJSErrorCallBack("Request method for data transfer not specified. Request method must be of string data type.");
            return;
        }
        if(request_methods.indexOf(type.toLowerCase()) === -1){
            CatchJSErrorCallBack("Please provide a valid request method for this action.");
            return;
        }
        if(typeof dataType === "undefined" || typeof dataType === null || typeof(dataType) !== "string"){
            CatchJSErrorCallBack("Action type for this action not given. Action type must be of string data type.");
            return;
        }
        if(typeof data === "undefined" || typeof data === null){
            CatchJSErrorCallBack("Data to be sent with request not found.");
            return;
        }
        if((typeof SuccessCallBack === "undefined" || typeof SuccessCallBack === null) && typeof(SuccessCallBack) == "funtion"){
            var SuccessCallBack = function(data){};
        }
        if((typeof beforeSendCallBack === "undefined" || typeof beforeSendCallBack === null) && typeof(beforeSendCallBack) == "funtion"){
            var beforeSendCallBack = function(){};
        }
        if((typeof completeCallBack === "undefined" || typeof completeCallBack === null) && typeof(completeCallBack) == "funtion"){
            var completeCallBack = function(){};
        }
        if((typeof ErrorCallBack === "undefined" || typeof ErrorCallBack === null) && typeof(ErrorCallBack) == "funtion"){
            var ErrorCallBack = function(){};
        }
        
        $.ajax({
            url : url,
            type : type,
            headers : {
                "Accept" : "application/json",
                "Content-Type" : "application/json",
                "X-From" : "application/api-call"
            },
            data : JSON.stringify({
                action: dataType,
                data: data,
            }),
            success: SuccessCallBack,
            beforeSend: beforeSendCallBack,
            complete: completeCallBack,
            error: ErrorCallBack,
            timeout : timeout,
        });
    },
	submitDataToServerPure : function(url, type, dataType, data, files, CatchJSErrorCallBack, ProgressCallBack, SuccessCallBack, beforeSendCallBack, completeCallBack, ErrorCallBack, TimeoutCallBack, timeout){
        var request_methods = ["post", "get"];
        
        if(arguments.length < 6){
            alert("Number of arguments provided is not valid. Six(6) or more is needed.");
            throw new Error("Number of arguments provided is not valid. Six(6) or more is needed.");
        }

        if(typeof timeout === "undefined" || typeof timeout === null){
            timeout = Main.defaultRequestTimeout;
        }
        if(typeof(timeout) !== "number"){
            CatchJSErrorCallBack("Timeout value is invalid.");
            return;
        }
        if(timeout <= 5000){
            CatchJSErrorCallBack("Timeout value must not be less than 5000 milliseconds (5 seconds).");
            return;
        }
        if(typeof url === "undefined" || typeof url === null || typeof(url) !== "string"){
            CatchJSErrorCallBack("URL found for this action is not valid. URL must be of string data type.");
            return;
        }
        if(typeof type === "undefined" || typeof type=== null || typeof(type) !== "string"){
            CatchJSErrorCallBack("Request method for data transfer not specified. Request method must be of string data type.");
            return;
        }
        if(request_methods.indexOf(type.toLowerCase()) === -1){
            CatchJSErrorCallBack("Please provide a valid request method for this action.");
            return;
        }
        if(typeof dataType === "undefined" || typeof dataType === null || typeof(dataType) !== "string"){
            CatchJSErrorCallBack("Action type for this action not given. Action type must be of string data type.");
            return;
        }
        if(typeof data === "undefined" || typeof data === null){
            CatchJSErrorCallBack("Data to be sent with request not found.");
            return;
        }
		if((typeof ProgressCallBack === "undefined" || typeof ProgressCallBack === null) && typeof(ProgressCallBack) == "funtion"){
            var ProgressCallBack = function(data){};
        }
        if((typeof SuccessCallBack === "undefined" || typeof SuccessCallBack === null) && typeof(SuccessCallBack) == "funtion"){
            var SuccessCallBack = function(data){};
        }
        if((typeof beforeSendCallBack === "undefined" || typeof beforeSendCallBack === null) && typeof(beforeSendCallBack) == "funtion"){
            var beforeSendCallBack = function(){};
        }
        if((typeof completeCallBack === "undefined" || typeof completeCallBack === null) && typeof(completeCallBack) == "funtion"){
            var completeCallBack = function(){};
        }
        if((typeof ErrorCallBack === "undefined" || typeof ErrorCallBack === null) && typeof(ErrorCallBack) == "funtion"){
            var ErrorCallBack = function(){};
        }
		if((typeof TimeoutCallBack === "undefined" || typeof TimeoutCallBack === null) && typeof(TimeoutCallBack) == "funtion"){
            var TimeoutCallBack = function(){};
        }
        
        var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", ProgressCallBack, false);
		xhr.addEventListener("load", SuccessCallBack, false);
		xhr.addEventListener("loadstart", beforeSendCallBack, false);
		xhr.addEventListener("loadend", completeCallBack, false);
		xhr.addEventListener("error", ErrorCallBack, false);
		xhr.addEventListener("timeout", TimeoutCallBack, false);
		xhr.timeout = timeout;
		
		var Formdata = new FormData();
		Formdata.append("task", dataType);
		Formdata.append("data", data);
		Formdata.append("file", files);
		
		xhr.open(type.toUpperCase(), url, true);
		xhr.send(Formdata);
    },
    isStringifiedJSON : function(d){
        try{
            var J = JSON.parse(d);
            if(typeof J === "object"){
                return true;
            }
            else{
                return false;
            }
        }
        catch(err){
            return false;
        }
    },
    loadItem : function(el){
        if(typeof el !== "undefined" && typeof el !== null){
            el.classList.add("loading-item");
        }
    },
    unloadItem : function(el){
        if(typeof el !== "undefined" && typeof el !== null){
            el.classList.remove("loading-item");
        }
    },
    ManageMessageBox: function(detail){
        if(typeof detail === "object"){
            if('target' in detail && 'message' in detail && 'parent' in detail){
                if(!('state' in detail)){
                    detail.state = false;
                }

                if(!('timeout' in detail)){
                    detail.timeout = 10000;
                }

                switch(detail.state){
                    case true:
                        $(detail.target).addClass("err");
                        break;
                    default:
                        $(detail.target).removeClass("err");
                }

                $(detail.target).html(detail.message).show(0, function(){
                    if('input' in detail){
                        if($(detail.input).length > 0){
                            $(detail.input).focus();
                        }
                    }
                    var tm = setTimeout(function(){
                        $(detail.parent).hide(0, function(){
                            clearTimeout(tm);
                        });
                    }, detail.timeout);
                });
            }
        }
    },
    fetch : function(url, method, udata, callBack, time){
		let timeout = (typeof time === "undefined" || typeof time === null) ? 30000 : time;
        let _failed = JSON.stringify({
            status : "_failed",
            data : "An unknown error occurred. Could be that the request took too long, an internet connection problem, a faulty URL, or a fault with the server. Please try again.",
        });

        if('fetch' in window){
            let jdata = {
                action : udata.task,
                data : udata.data
            };
			
			let controller = new AbortController();
			
			let tout = setTimeout(function(){
				callBack(_failed);
				controller.abort();
			}, timeout);
			
            fetch(url,{
				signal : controller.signal,
                method : method,
                mode : "cors",
                cache : "no-cache",
                referrer : "no-referrer",
                headers : {
                    "Accept" : "application/json",
                    "Content-Type" : "application/json",
                    "X-From" : "application/api-call"
                },
                body : JSON.stringify(jdata)
            })
            .then(response => response.text())
            .then(data => {
				clearTimeout(tout);
                callBack(data);
            })
            .catch(err => {
				clearTimeout(tout);
                callBack(_failed);
            });
        }
        else{
            //Fallback
			let jdata = {
                action : udata.task,
                data : udata.data
            };
            $.ajax({
                url : url,
                type : method,
				headers : {
                    "Accept" : "application/json",
                    "Content-Type" : "application/json",
                    "X-From" : "application/api-call"
                },
                data : JSON.stringify(jdata),
                success: callBack,
                error: callBack(_failed),
                timeout : timeout,
            });
        }
    },
    isError : function(err){
        var er = false;
        switch(err){
            case "_login_failed":
            case "_connection_failed":
            case "_failed":
                er = true;
                break;
            default:
                er = false;
        }
        return er;
    },
    emptyDataFields : function(el,t){
        switch(t){
            case "v":
                $(el).val("");
                break;
            case "h":
            case "t":
                $(el).text("");
                break;
        }
    },
	changeText : function(el, txt){
		$(el).html(txt);
    },
    redirect: function(url, target){
        target = typeof target === "undefined" || typeof target === null ? "_self" : target;
        window.open(url, target);
    },
	__init__: function(){
		
		var alertData = Main.getQueryString("info", document.URL);
		if(alertData != "" && alertData != null && alertData != "undefined"){
			callAlert({ body : alertData });
		}
		
		if(window.location.protocol.toLowerCase() === "file:"){
			callAlert({ body : "Please run this page in <b>HTTP</b> or <b>SSL</b> protocol mode.", button : "<button onclick='window.open(\"" + Main._RAW_DOMAIN_ + "\",\"_self\");'>Use HTTP</button>" });
		}
		
	},
	getQueryString : function(field, url){
		var href =  url ? url : window.location.href;
		var reg = new RegExp('[?&]' + field + '=([^&#]*)', 'i');
		var string = reg.exec(href);
		return string ? decodeURIComponent(string[1]) : null;
    },
    hasAttribute: function(elem, attr){
        if(elem !== null && elem !== "undefined"){
            if(elem.hasAttribute(attr)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    },
    __bindData__: function(data){
        if(typeof data === "object"){
            for(x in data){
                let elem = document.getElementsByClassName("data-bind-item"), len = elem.length;
                for(let i = 0; i < len; i++){
                    switch(elem[i].getAttribute("data-bind-type")){
                        case "text":
                            if(x === elem[i].getAttribute("data-bind-name")){
                                elem[i].textContent = data[x];
                            }
                            break;
                        case "html":
                            if(x === elem[i].getAttribute("data-bind-name")){
                                elem[i].innerHTML = data[x];
                            }
                            break;
                        case "image":
                            if(x === elem[i].getAttribute("data-bind-name")){
                                var img = new Image();
                                img.onload = function(e){
                                    elem[i].setAttribute("src", img.src);
                                };
                                img.src = data[x];
                            }
                            break;
                        case "value":
                            if(x === elem[i].getAttribute("data-bind-name")){
                                elem[i].value = data[x];
                            }
                            break;
                        default:
                            //
                    }
                }
            }
        }
		return [UploadPhoto, Main];
    },
    storeUserDetails : function(data){
        if(typeof data === "object"){
            var pData = JSON.stringify(data);
            window.sessionStorage.setItem("userDetails", pData);
        }
    },
    getUserDetails : function(data){
        if(window.sessionStorage.getItem("userDetails") !== null || window.sessionStorage.getItem("userDetails") !== "undefined"){
            if(typeof data === "string"){
                var UD = JSON.parse(window.sessionStorage.getItem("userDetails"));
                if(data in UD){
                    return UD[data];
                }
                else{
                    return "";
                }
            }
        }
    },
    updateUserDetails : function(data, newData){
        if(window.sessionStorage.getItem("userDetails") !== null || window.sessionStorage.getItem("userDetails") !== "undefined"){
            if(typeof data === "string"){
                var UD = JSON.parse(window.sessionStorage.getItem("userDetails"));
                if(data in UD){
                    UD[data] = newData;
                    window.sessionStorage.setItem("userDetails", JSON.stringify(UD));
                }
            }
        }
    },
};
Main.__init__();