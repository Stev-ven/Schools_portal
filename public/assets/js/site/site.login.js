var rx_login_loader = false;

function logIn(event) {
	if (rx_login_loader) return;
	rx_login_loader = true;
	var dis = Main.getElementById("rxc_login_btn");

	Main.logIn({
		email: Main.getElementById("rxc_user_lg").value,
		password: Main.getElementById("rxc_user_pw_lg").value,
		type: Main.getElementById("rxc_action_type").value,
		catchJSError: function (err) {
			callAlert({ body: err });
			Main.unloadItem(dis);
			rx_login_loader = false;
		},
		success: function (data) {
			if (Main.isStringifiedJSON(data)) {
				var pData = JSON.parse(data);
				var err = Main.isError(pData.status);
				var tmout = 30000;

				if (pData.status == "ok") {
					Main.emptyDataFields(".rx-emp-inp-lg", "v");
					Main.redirect("index", "_self");
					tmout = 120000;
				}

				Main.ManageMessageBox({
					target: "#login_log",
					parent: "#login_log",
					message: pData.data,
					state: err,
					input: 'input' in pData ? pData.input : null,
					timeout: tmout,
				});
			}
			else {
				callAlert({ body: "Could not process incoming data. Please try again." });
			}
		},
		beforeSend: function () {
			Main.loadItem(dis);
		},
		complete: function () {
			Main.unloadItem(dis);
			rx_login_loader = false;
		},
		error: function (x, t, m) {
			if (t === "timeout") {
				callAlert({ body: "This action took too long to respond. Please check your internet connection or try again." });
			}
			else {
				callAlert({ body: "An unknown error occurred. Please try again." });
			}
			Main.unloadItem(dis);
			rx_login_loader = false;
		},
	});
}

if (document.getElementById("rxc_login_btn") !== "undefined" && document.getElementById("rxc_login_btn") !== null) {
	var lbtn = document.getElementById("rxc_login_btn"), 
		login_ebtn = document.getElementsByClassName("rx-emp-inp-lg"), 
		login_ebtnLength = document.getElementsByClassName("rx-emp-inp-lg").length;

	lbtn.addEventListener("click", logIn, false);

	for (var i = 0; i < login_ebtnLength; i++) {
		login_ebtn[i].addEventListener("keydown", function (e) {
			if (e.keyCode === 13) {
				e.stopImmediatePropagation();
				e.preventDefault();
				logIn();
			}
		}, false);
	}
	
}


