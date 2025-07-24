		//Admin recover account
		var rx_recover_account_loader = false;
		
		function recoverAccount(event){
			if(rx_recover_account_loader) return;
			rx_recover_account_loader = true;
			var dis = Main.getElementById("rxc_recover_account_btn");
			
				Main.recoverAccount({
					email: Main.getElementById("rxc_user_email").value,
					role: Main.getElementById("rxc_user_role").value,
					type: Main.getElementById("rxc_action_type").value,
					catchJSError: function(err){
						callAlert({body : err});
						Main.unloadItem(dis);
						rx_recover_account_loader = false;
					},
					success: function(data){
						if(Main.isStringifiedJSON(data)){
							var pData = JSON.parse(data);
							var err = Main.isError(pData.status);
							var tmout = 30000;

							if(pData.status == "ok"){
								Main.emptyDataFields(".rx-emp-inp-lg","v");
								tmout = 120000;
							}

							Main.ManageMessageBox({
								target : "#login_log",
								parent : "#login_log",
								message : pData.data,
								state : err,
								input : 'input' in pData ? pData.input : null,
								timeout : tmout,
							});
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
						rx_recover_account_loader = false;
					},
					error: function(x, t, m){
						if(t === "timeout"){
							callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
						}
						else{
							callAlert({body: "An unknown error occurred. Please try again."});
						}
						Main.unloadItem(dis);
						rx_recover_account_loader = false;
					},
				});

		}
		
		/* Recover account */
		if(document.getElementById("rxc_recover_account_btn") !== "undefined" && document.getElementById("rxc_recover_account_btn") !== null){
			var lbtn = document.getElementById("rxc_recover_account_btn"), acc_ebtn = document.getElementsByClassName("rx-emp-inp-lg"), acc_ebtnLength = document.getElementsByClassName("rx-emp-inp-lg").length;
			lbtn.addEventListener("click", recoverAccount, false);
			for(var i = 0; i < acc_ebtnLength; i++){
				acc_ebtn[i].addEventListener("keydown", function(e){
					if(e.keyCode === 13){
						e.stopImmediatePropagation();
						e.preventDefault();
						recoverAccount();
					}
				}, false);
			}
		}
		/* /Recover account */
		
