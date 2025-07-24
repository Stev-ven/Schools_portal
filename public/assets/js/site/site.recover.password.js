		/* Sign Up page account creation begins */
		function recoverPassword(event){
			var dis = Main.getElementById("recover_password_btn");
			Main.recoverPassword({
				email: Main.getElementById("user_email").value,
				catchJSError: function(err){
					callAlert({body : err});
					Main.unloadItem(dis);
				},
				success: function(data){
					if(Main.isStringifiedJSON(data)){
						var pData = JSON.parse(data);
						var err = Main.isError(pData.status);
						var tmout = 30000;

						if(pData.status == "ok"){
							Main.emptyDataFields(".emp-inp","v");
							Main.changeText(".btn-text", "Resend");
							tmout = 120000;
						}

						Main.ManageMessageBox({
							target : "#recover_password_log",
							parent : "#recover_password_log",
							message : pData.data,
							state : err,
							input : 'input' in pData ? pData.input : null,
							timeout : tmout,
						});
					}
					else{
						callAlert({body: "Could not process incoming data but you can check your email to activate your new user acount with a verification code we have sent along with your email. If the account is already created yet you have not received any verification code in your email or spam box, head <a>to this link</a> to try and resend the verification code."});
					}
				},
				beforeSend: function(){
					Main.loadItem(dis);
				},
				complete: function(){
					Main.unloadItem(dis);
				},
				error: function(x, t, m){
					if(t === "timeout"){
						callAlert({body: "This action took too long to respond. Please check your internet connection or try again."});
					}
					else{
						callAlert({body: "An unknown error occurred. Please try again."});
					}
					Main.unloadItem(dis);
				},
			});	
		}
		
		var sbtn = document.getElementById("recover_password_btn"), ebtn = document.getElementsByClassName("emp-inp"), ebtnLength = document.getElementsByClassName("emp-inp").length;
		sbtn.addEventListener("click", recoverPassword, false);
		for(var i = 0; i < ebtnLength; i++){
			ebtn[i].addEventListener("keyup", function(e){
				if(e.keyCode === 13){
					recoverPassword();
				}
			}, false);
		}
		/* Sign Up page account creation ends */