var User = {
    addUser : function(userData){
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
        if(!('title' in userData)){
            userData.catchJSError("The age ranges are invalid.");
            return;
        }

        //Validate the firstname of the user
        if(!('firstname' in userData)){
            userData.catchJSError("Please add your first name.");
            return;
        }

        //Check for the lastname
        if(!('lastname' in userData)){
            userData.catchJSError("Last name does not exist.");
            return;
        }

        //Validate username
        if(!('username' in userData)){
            userData.catchJSError("Username not found.");
            return;
        }

        //Verify user password
        if(!('password' in userData)){
            userData.catchJSError("Password invalid.");
            return;
        }

        //Check for the role
        if(!('role' in userData)){
            userData.catchJSError("Please specify the correct role for this user.");
            return;
        }

        //Consider the company code
        if(!('company_code' in userData)){
            userData.catchJSError("Company code not found.");
            return;
        }

        //Check whether telephone number exists
        if(!('telephone' in userData)){
            userData.catchJSError("Telephone number is required.");
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
            title: userData.title,
            firstname : userData.firstname,
            lastname : userData.lastname,
            username : userData.username,
            password : userData.password,
            role : userData.role,
            company_code : userData.company_code,
            telephone : userData.telephone,
        });

        Main.submitDataToServer(
            Main._DOMAIN_, 
            "post", 
            "add_user", 
            udata,
            userData.catchJSError,
            userData.success,
            userData.beforeSend,
            userData.complete,
            userData.error,
            userData.timeout,
        );
    },
    getUsersForAdmin : function(userData){
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

        //Offset
        if(!('offset' in userData)){
            userData.catchJSError("Please indicate a valid offset.");
            return;
        }

        //Search
        if(!('search' in userData)){
            userData.catchJSError("Seach not found.");
            return;
        }

        //Search text
        if(!('searchText' in userData)){
            userData.catchJSError("Search query not found.");
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
            offset : userData.offset,
            search : userData.search,
            searchText : userData.searchText,
        });

        Main.submitDataToServer(
            Main._DOMAIN_, 
            "post", 
            "get_users_for_admin", 
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

Main.User = User;