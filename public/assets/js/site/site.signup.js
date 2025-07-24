		var rx_signup_loader = false;

		function signUp(event){
			if(rx_signup_loader) return;
			rx_signup_loader = true;
			var dis = Main.getElementById("rxc_signup_btn");
			Main.signUp({
				name: Main.getElementById("rxc_user_fullname_su").value,
				email: Main.getElementById("rxc_user_email_su").value,
				password: Main.getElementById("rxc_user_pw_su").value,
				confirm_password: Main.getElementById("rxc_user_pw_cfm_su").value,
				accept_terms: parseInt(Main.getElementById("rxc_agree_to_terms_su").getAttribute("data-value")),
				catchJSError: function(err){
					callAlert({body : err});
					Main.unloadItem(dis);
					rx_signup_loader = false;
				},
				success: function(data){
					if(Main.isStringifiedJSON(data)){
						var pData = JSON.parse(data);
						var err = Main.isError(pData.status);
						var tmout = 30000;

						if(pData.status == "ok"){
							Main.emptyDataFields(".rx-emp-inp-su","v");
							tmout = 120000;
						}

						Main.ManageMessageBox({
							target : "#signup_log",
							parent : "#signup_log",
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
					rx_signup_loader = false;
				},
				complete: function(){
					Main.unloadItem(dis);
					rx_signup_loader = false;
				},
				error: function(x, t, m){
					if(t === "timeout"){
						callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
					}
					else{
						callAlert({body: "An unknown error occurred. Please try again."});
					}
					Main.unloadItem(dis);
					rx_signup_loader = false;
				},
			});	
		}

		function handleCheckBoxChanges(){
			if(this.checked){
				this.setAttribute("data-value", "1");
			}
			else{
				this.setAttribute("data-value", "0");
			}
		}

		function handleCheckBoxChangesDOMLoad(){
			var cb = document.getElementById("rxc_agree_to_terms_su");
			if(cb.checked){
				cb.setAttribute("data-value", "1");
			}
			else{
				cb.setAttribute("data-value", "0");
			}
		}
		
		/* Sign Up */
		if(document.getElementById("rxc_signup_btn") !== "undefined" && document.getElementById("rxc_signup_btn") !== null){
			var rxc_signup_btn = document.getElementById("rxc_signup_btn"), ebtn = document.getElementsByClassName("rx-emp-inp-su"), ebtnLength = document.getElementsByClassName("rx-emp-inp-su").length;
			rxc_signup_btn.addEventListener("click", signUp, false);
			for(var i = 0; i < ebtnLength; i++){
				ebtn[i].addEventListener("keydown", function(e){
					if(e.keyCode === 13){
						e.stopImmediatePropagation();
						e.preventDefault();
						signUp();
					}
				}, false);
			}
		}
		/* /Sign Up */

		/* Handle user terms agreement */
		if(document.getElementById("rxc_agree_to_terms_su") !== "undefined" && document.getElementById("rxc_agree_to_terms_su") !== null){
			document.getElementById("rxc_agree_to_terms_su").addEventListener("change", handleCheckBoxChanges, false);
			window.addEventListener("DOMContentLoaded", handleCheckBoxChangesDOMLoad, false);
		}
		/* /Handle user terms agreement */