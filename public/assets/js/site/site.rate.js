var Rate = {
    addRate : function(userData){
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

        //Check for the title of the user
        if(!('commission' in userData)){
            userData.catchJSError("Commission rate is invalid.");
            return;
        }

        //Validate the firstname of the user
        if(!('security' in userData)){
            userData.catchJSError("Security rate is invalid.");
            return;
        }

        //Check for the lastname
        if(!('discount' in userData)){
            userData.catchJSError("Discount rate is invalid.");
            return;
        }

        //Validate username
        if(!('administrative' in userData)){
            userData.catchJSError("Administrative rate is invalid.");
            return;
        }

        //Verify user password
        if(!('profit_loading' in userData)){
            userData.catchJSError("Profit loading is invalid.");
            return;
        }

        //Consider the company code
        if(!('company_code' in userData)){
            userData.catchJSError("Company code not found.");
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

        var udata = JSON.stringify({
            commission: userData.commission,
            security : userData.security,
            discount : userData.discount,
            administrative : userData.administrative,
            profit_loading : userData.profit_loading,
            company_code : userData.company_code,
        });

        Main.submitDataToServer(
            Main._DOMAIN_, 
            "post", 
            "add_rate", 
            udata,
            userData.catchJSError,
            userData.success,
            userData.beforeSend,
            userData.complete,
            userData.error,
            userData.timeout,
        );
    },
};

Main.Rate = Rate;