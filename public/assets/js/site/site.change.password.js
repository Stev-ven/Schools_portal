		var rx_change_password_loader = false;
		
		function changePassword(event){
			if(rx_change_password_loader) return;
			rx_change_password_loader = true;
			var dis = Main.getElementById("rxc_change_password_btn");
			
				Main.changePassword({
					password: Main.getElementById("rxc_user_password").value,
					password_confirm: Main.getElementById("rxc_user_password_confirm").value,
					email: Main.getElementById("rxc_user_email").value,
					role: Main.getElementById("role").value,
					prc: Main.getElementById("rxc_user_prc").value,
					type: Main.getElementById("rxc_action_type").value,
					catchJSError: function(err){
						callAlert({body : err});
						Main.unloadItem(dis);
						rx_change_password_loader = false;
					},
					success: function(data){
						if(Main.isStringifiedJSON(data)){
							var pData = JSON.parse(data);
							var err = Main.isError(pData.status);
							var tmout = 30000;

							if(pData.status == "ok"){
								setTimeout(function(){
									Main.redirect(Main.getElementById("rxc_login_link").value, "_self");
									Main.emptyDataFields(".rx-emp-inp-lg","v");
								}, 5000);
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
						rx_change_password_loader = false;
					},
					error: function(x, t, m){
						if(t === "timeout"){
							callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
						}
						else{
							callAlert({body: "An unknown error occurred. Please try again."});
						}
						Main.unloadItem(dis);
						rx_change_password_loader = false;
					},
				});

		}
		
		if(document.getElementById("rxc_change_password_btn") !== "undefined" && document.getElementById("rxc_change_password_btn") !== null){
			var lbtn = document.getElementById("rxc_change_password_btn"), acc_ebtn = document.getElementsByClassName("rx-emp-inp-lg"), acc_ebtnLength = document.getElementsByClassName("rx-emp-inp-lg").length;
			lbtn.addEventListener("click", changePassword, false);
			for(var i = 0; i < acc_ebtnLength; i++){
				acc_ebtn[i].addEventListener("keydown", function(e){
					if(e.keyCode === 13){
						e.stopImmediatePropagation();
						e.preventDefault();
						changePassword();
					}
				}, false);
			}
		}
		
