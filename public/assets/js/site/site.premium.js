var Premium = {
    addPremium : function(userData){
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

        //Check age ranges
        if(!('age_ranges' in userData)){
            userData.catchJSError("The age ranges are invalid.");
            return;
        }

        //Check for valid premium ranges
        if(!('premium_ranges' in userData)){
            userData.catchJSError("Premium ranges are invalid.");
            return;
        }

        //Check the object for both age and premium ranges
        if(!('age_premium_combinations' in userData)){
            userData.catchJSError("There is something wrong with the data you have provided. Please fix them and try again.");
            return;
        }

        //Check for company code
        if(!('company_code' in userData)){
            userData.catchJSError("Please make sure the company code is valid before you continue.");
            return;
        }

        //Check whether date object is present
        if(!('date' in userData)){
            userData.catchJSError("A date is needed for this action to proceed.");
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
            age_ranges : userData.age_ranges,
            premium_ranges : userData.premium_ranges,
            age_premium_combinations : userData.age_premium_combinations,
            company_code : userData.company_code,
            date : userData.date,
        });

        Main.submitDataToServer(
            Main._DOMAIN_, 
            "post", 
            "add_premium", 
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

Main.Premium = Premium;