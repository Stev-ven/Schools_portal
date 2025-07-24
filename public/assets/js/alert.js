/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

let azi = 99999999, snackTime;
let allowEnter = $(".alert-script").attr("data-allow-enter") || "no";

var callAlert = function (details, callBack) {
	if (document.activeElement != document.body) document.activeElement.getBoundingClientRect();
	var def = typeof details.def === "undefined" ? false : details.def;
	if ($(window).innerWidth() <= 800 && def == false) {
		callSnack({
			body: typeof details.body != "undefined" ? details.body : "",
			button: typeof details.button != "undefined" ? details.button : "",
			otext: typeof details.otext != "undefined" ? details.otext : "Okay"
		}, callBack);
	}
	else {
		this.body = null;
		this.button = null;
		this.otext = null;
		if (typeof details === "object") {
			try {
				this.body = typeof details.body != "undefined" ? details.body : "";
				this.button = typeof details.button != "undefined" ? details.button : "";
				this.otext = typeof details.otext != "undefined" ? details.otext : "Okay";
				this.disallowAllClosers = typeof details.disallowAllClosers != "undefined" ? "amg" : "";
				this.hideCloseButton = typeof details.hideCloseButton != "undefined" ? "hidden" : "";
				$("body").append(
					'<div class="alert-container cp-hsc stopmove" style="z-index: ' + azi + ';display: none;">' +
					'<div class="alert-container-contents stopmove ' + this.disallowAllClosers + '">' +
					'<div class="alert-main sca minimized">' +
					'<div class="alert-text" style="font-weight: 400 !important;" ontouchstart="elemTouchStartY(this,event);" ontouchmove="elemTouchMoveY(this,event);">' +
					this.body.replace("Failed.COC.Error", "").replace("Login.COC.Error", "") +
					'</div>' +
					'<div class="clearfix alert-buttons">' +
					'<button class="alert-closer" onclick="closeAlertContainer(this, event)" ' + this.hideCloseButton + '>' + this.otext + '</button>' +
					this.button +
					'</div>' +
					'</div>' +
					'</div>' +
					'</div>'
				);
				$(".alert-container").show(0, function () {
					$(".alert-main").removeClass("sca").removeClass("minimized");
				});

				if (typeof callBack === "function") {
					callBack();
				}
			}
			catch (err) {
				//alert(err);
			}
		}
	}
}

$(window).on("load", function () {

	let info = Modules.getQueryString("info", Modules.CURRENT_LOCATION) !== null && Modules.trim(Modules.getQueryString("info", Modules.CURRENT_LOCATION)) !== "" ? Modules.getQueryString("info", Modules.CURRENT_LOCATION) : "";
	if (Modules.trim(info) != "") {
		callAlert({ body: info, def: true });
	}

	$(document).on("click", ".-why-btn-", function () {
		callAlert({ body: $(this).attr("data-why"), def: true });
	});

	$(document).on("click", ".info-btn", function () {
		callAlert({ body: $(this).attr("data-message"), otext: "Thanks, I understand!", def: true });
	});

	$(document).on("click", ".snackbar-content-btns button", function (e) {
		hideSnackBar(e);
	});

	$(document).on("click", ".alert-buttons button", function (event) {
		closeAlertContainer(this, event);
	});

	$(document).on("click", "#toast", function (event) {
		$(this).addClass("inactive");
	});

	$(document).on("click", ".alert-container-contents:not(.amg)", function (event) {
		event.stopPropagation();
		var $this = $(this);
		$this.find(".alert-main").addClass("sca").addClass("minimized");
		setTimeout(function () {
			$this.closest('.alert-container').remove();
		}, 200);
	});

	$(document).on("click", ".alert-main", function (e) {
		e.stopPropagation();
	});

	$(document).on("keydown", function (e) {
		if (e.keyCode == 13 && $(".alert-container").length > 0) {
			$(".alert-container").last().remove();
		}
	});

});

function closeAlertContainer(t, event) {
	event.stopPropagation();
	var $this = $(t);
	$this.closest(".alert-main").addClass("sca").addClass("minimized");
	setTimeout(function () {
		$this.closest('.alert-container').remove();
		$this.closest(".snackbar").addClass("inactive");
	}, 200);
}

function callSnack(details, callBack) {
	if (typeof details === "object") {
		$(".snack-details").html(details.body || "");
		if (details.button != "") {
			$(".snackbar-content-btns").html(details.button + '<button onclick="hideSnackBar(event);">' + details.otext + '</button>');
			$(".snackbar-content-btns").removeClass("non");
		}
		else {
			$(".snackbar-content-btns").empty();
			$(".snackbar-content-btns").addClass("non");
		}
		$(".snackbar").removeClass("inactive");
		clearTimeout(snackTime);
		snackTime = setTimeout(hideSnackBar, 15000);
		if (typeof callBack === "function") {
			callBack();
		}
	}
}

function hideSnackBar(event) {
	event.stopPropagation();
	$(".snack-details").html("");
	$(".snackbar").addClass("inactive");
	$(".snackbar-content-btns").addClass("non");
	clearTimeout(snackTime);
}

var alertCSS = '.snackbar{' +
	'position: fixed;' +
	'bottom: -1%;' +
	'width: 101%;' +
	'left: -0.5%;' +
	'z-index: 999;' +
	'background-color: #191919;' +
	'transition: 550ms all ease;' +
	'}' +
	'.snackbar .snackbar-content{' +
	'width: 100%;' +
	'display: flex;' +
	'display: -webkit-flex;' +
	'justify-content: space-between;' +
	'}' +
	'.snackbar.inactive{' +
	'bottom: -300px;' +
	'}' +
	'.snackbar .snackbar-content > div{' +
	'padding: 15px 20px;' +
	'padding-bottom: 20px;' +
	'}' +
	'.snackbar .snackbar-content > div:first-child{' +
	'align-self: stretch;' +
	'flex-grow: 1;' +
	'color: #fff;' +
	'font-weight: 400;' +
	'font-size: 0.90rem;' +
	'word-break: break-word;' +
	'}' +
	'.snackbar .snackbar-content > div:nth-child(2){' +
	'width: 60px;' +
	'color: #fe2c52;' +
	'}' +
	'.snackbar-content-btns {' +
	'padding: 15px 20px;' +
	'padding-bottom: 25px;' +
	'padding-top: 0px;' +
	'}' +
	'.snackbar-content-btns button {' +
	'background: transparent !important;' +
	'border-width: 0 !important;' +
	'font-weight: 600 !important;' +
	'margin-right: 35px !important;' +
	'color: #fe2c52 !important;' +
	'font-size: 0.90rem !important;' +
	'padding: 0 !important;' +
	'}' +
	'.snackbar-content-btns button.focused {' +
	'color: #fe2c52 !important;' +
	'}' +
	'.snackbar-content-btns button.focused-caution {' +
	'color: #dd5145 !important;' +
	'}' +
	'.alert-main.sca {' +
	'transform: scale(0.9) !important;' +
	'-webkit-transform: scale(0.9) !important;' +
	'-moz-transform: scale(0.9) !important;' +
	'-ms-transform: scale(0.9) !important;' +
	'-o-transform: scale(0.9) !important;' +
	'}' +
	'.snackbar-content-btns.non{' +
	'display: none;' +
	'}' +
	'.snackbar .snackbar-content > div > small {' +
	'font-size: 0.90rem;' +
	'}' +
	'i.lnr.lnr-cross.cur.snack-closer {' +
	'font-size: 1.5rem;' +
	'}' +
	'.toast-action-icon {' +
	'fill: #fff;' +
	'width: 1.45rem;' +
	'}' +
	'.flex-item-centered {' +
	'display: flex !important;' +
	'display: -webkit-flex !important;' +
	'align-items: center;' +
	'justify-content: center;' +
	'}' +
	'.alert-container{' +
	'width:100%;' +
	'position:fixed;' +
	'top:0;' +
	'left:0;' +
	'height:100%;' +
	'z-index:999;' +
	'background-color:rgba(0,0,0,0.4);' +
	'}' +
	'.alert-container > .alert-container-contents{' +
	'width:100%;' +
	'height:100%;' +
	'display:flex;' +
	'display:-webkit-flex;' +
	'align-items:center;' +
	'}' +
	'.alert-container > .alert-container-contents > div{' +
	'width:80%;' +
	'max-width:600px;' +
	'min-width:298px;' +
	'background-color:#FFF;' +
	'margin:auto;' +
	'box-shadow: rgba(0, 0, 0, 0.14) 0px 24px 38px 3px, rgba(0, 0, 0, 0.12) 0px 9px 46px 8px, rgba(0, 0, 0, 0.2) 0px 11px 15px -7px;' +
	'-webkit-box-shadow: rgba(0, 0, 0, 0.14) 0px 24px 38px 3px, rgba(0, 0, 0, 0.12) 0px 9px 46px 8px, rgba(0, 0, 0, 0.2) 0px 11px 15px -7px;' +
	'overflow:hidden;' +
	'border-radius:3px;' +
	'}' +
	'.alert-main.minimized {' +
	'transform: scale(.75);' +
	'}' +
	'.alert-container > .alert-container-contents > div > div{' +
	'padding: 25px;' +
	'max-height: 250px;' +
	'overflow-y: auto;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:first-child b{' +
	'font-weight: 500;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:first-child{' +
	'font-size:1.1rem;' +
	'line-height:1.65rem;' +
	'word-break: break-word;' +
	'/*font-family: \'Roboto\', \'Segoe UI\' !important;*/' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:first-child a{' +
	'color: #007aff;' +
	'}' +
	'.alert-container > .alert-container-contents > div.alert-main{' +
	'-webkit-transition: all .25s cubic-bezier(.694,0,.335,1);' +
	'-moz-transition: all .25s cubic-bezier(.694,0,.335,1);' +
	'-ms-transition: all .25s cubic-bezier(.694,0,.335,1);' +
	'transition: all .25s cubic-bezier(.694,0,.335,1);' +
	'-webkit-transform: translateZ(0);' +
	'-moz-transform: translateZ(0);' +
	'-ms-transform: translateZ(0);' +
	'-o-transform: translateZ(0);' +
	'transform: translateZ(0);' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2){' +
	'background: #fff;' +
	'padding: 20px 25px;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button{' +
	'float: right;' +
	'padding: 6px 24px;' +
	'color: #1dbf73;' +
	'font-weight: 600;' +
	'font-size: 1rem;' +
	'margin: 0.50%;' +
	'margin-left: 10px;' +
	'background-color: #e8faf4;' +
	'border-radius: 3px;' +
	'border-width: 0;' +
	'cursor: pointer;' +
	'user-select: none;' +
	'border: 1px solid #e8faf4;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button.focused{' +
	'background: #fe2c52;' +
	'color: #fff;' +
	'border-color: #fe2c52;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button.focused-caution{' +
	'background: #dd5145;' +
	'color: #fff;' +
	'border-color: #dd5145;' +
	'}' +
	'@media screen and (min-width: 900px){' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button:hover{' +
	'background-color: #f7faff;' +
	'border: 1px solid #d2e3fc;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button.focused-caution:hover{' +
	'background: #ff0000;' +
	'color: #fff;' +
	'border-color: #ff0000;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button.focused:hover{' +
	'background: #3bb75e;' +
	'color: #fff;' +
	'border-color: #3bb75e;' +
	'}' +
	'}' +
	'@media screen and (max-width: 900px){' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2) button{' +
	'padding: 5px 15px;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:first-child{' +
	'border-bottom: 1px solid #eee;' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:nth-child(2){' +
	'/*box-shadow: 0 0 10px 5px rgb(0 0 0 / 6%);*/' +
	'}' +
	'.alert-container > .alert-container-contents > div > div:first-child{' +
	'font-size: 0.85rem;' +
	'line-height: 1.4rem;' +
	'}' +
	'.alert-container > .alert-container-contents > div.alert-main{' +
	'overflow: hidden;' +
	'padding-bottom: 0;' +
	'width: 100%;' +
	'align-self: flex-end;' +
	'margin: 0;' +
	'border-radius: 0;' +
	'border-top-left-radius: 10px;' +
	'border-top-right-radius: 10px;' +
	'}' +
	'}' +
	'/* toast */' +
	'#toast{' +
	'position: fixed;' +
	'bottom: 140px;' +
	'right: calc((100% - 450px) / 2);' +
	'border-radius: 2px;' +
	'background: #fff;' +
	'color: #000;' +
	'padding: 15px 20px;' +
	'transform: translate3d(0,100%,150px) scale(1);' +
	'transition: all .4s ease;' +
	'z-index: 999999;' +
	'font-size: 14px;' +
	'max-width: 450px;' +
	'min-width: 450px;' +
	'box-shadow: rgba(0, 0, 0, 0.24) 0px 24px 38px 3px, rgba(0, 0, 0, 0.12) 0px 9px 46px 8px, rgba(0, 0, 0, 0.2) 0px 11px 15px -7px;' +
	'-webkit-box-shadow: rgba(0, 0, 0, 0.24) 0px 24px 38px 3px, rgba(0, 0, 0, 0.12) 0px 9px 46px 8px, rgba(0, 0, 0, 0.2) 0px 11px 15px -7px;' +
	'cursor: auto;' +
	'-webkit-user-select: none;' +
	'-moz-user-select: none;' +
	'-ms-user-select: none;' +
	'user-select: none;' +
	'}' +
	'#toast.dark{' +
	'background-color: #191919;' +
	'color: #fff;' +
	'}' +
	'.toast-icon-item::before {' +
	'content: "" !important;' +
	'}' +
	'#toast-content{' +
	'display: -webkit-flex;' +
	'display: flex;' +
	'align-items: center;' +
	'}' +
	'#toast-content > div:first-child{' +
	'display: none;' +
	'}' +
	'#toast-content > div:nth-child(2){' +
	'width: 100%;' +
	'display: -webkit-box;' +
	'-webkit-line-clamp: 4;' +
	'line-clamp: 4;' +
	'line-height: 1.35;' +
	'font-size: 1.1rem;' +
	'font-family: \'Roboto\', Inter, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif; !important' +
	'overflow: hidden;' +
	'line-height: 1.4rem;' +
	'overflow: hidden;' +
	'font-weight: 400;' +
	'-webkit-box-orient: vertical;' +
	'}' +
	'#toast-message-box p{' +
	'line-height: 1.3rem;' +
	'}' +
	'#toast.success #toast-content > div:first-child, #toast.fail #toast-content > div:first-child{' +
	'width: 50px;' +
	'min-width: 50px;' +
	'display: block;' +
	'}' +
	'#toast.success .toast-icon-item, #toast.fail .toast-icon-item {' +
	'display: block;' +
	'width: 35px;' +
	'height: 35px;' +
	'line-height: 35px;' +
	'border-radius: 50%;' +
	'background: #333;' +
	'text-align: center;' +
	'font-size: 1.25rem;' +
	'}' +
	'#toast.success .toast-icon-item.toast-icon-fail, #toast.fail .toast-icon-item.toast-icon-fail {' +
	'background: #333;' +
	'cursor: pointer;' +
	'}' +
	'#toast.success #toast-content > div:nth-child(2), #toast.fail #toast-content > div:nth-child(2){' +
	'width: calc(100% - 50px);' +
	'}' +
	'#toast.success #toast-content > div:nth-child(2) a, #toast.success #toast-content > div:nth-child(2) strong, #toast.fail #toast-content > div:nth-child(2) a, #toast.fail #toast-content > div:nth-child(2) strong{' +
	'color: #fff !important;' +
	'}' +
	'#toast.success .toast-icon-item.toast-icon-fail {' +
	'display: none !important;' +
	'}' +
	'#toast.fail .toast-icon-item.toast-icon-success {' +
	'display: none !important;' +

	'}' +
	'#toast.inactive{' +
	'bottom: -200px;' +
	'}' +
	'@media screen and (max-width: 969px){' +
	'#toast-content > div:nth-child(2){' +
	'font-size: .95rem;' +
	'}' +
	'#toast{' +
	'width: 90%;' +
	'left: 5vw;' +
	'min-width: 90%;' +
	'background-color: #000;' +
	'color: #fff;' +
	'bottom: initial !important;' +
	'top: 0 !important;' +
	'}' +
	'#toast.inactive {' +
	'top: -1000px !important;' +
	'bottom: initial !important;;' +
	'}' +
	'}' +
	'/* /toast */';

$("document").ready(function () {

	$("head").append('<style class="alert-css">' + alertCSS + '</style>');

	if ($(".snackbar").length == 0) {
		$("body").append('<section class="snackbar inactive">' +
			'<div class="snackbar-content">' +
			'<div>' +
			'<small class="snack-details"></small>' +
			'</div>' +
			'<div><i class="icon-cross2 cur snack-closer" onclick="hideSnackBar(event);"></i></div>' +
			'</div>' +
			'<div class="snackbar-content-btns">' +
			'</div>' +
			'</section>');
	}

	if ($("#toast").length == 0) {
		$("body").append('<!-- Toast -->' +
			'<div id="toast" class="trans dark inactive">' +
			'<div id="toast-content">' +
			'<div id="toast-status-box"><i class="toast-icon-item flex-item-centered toast-icon-success las la-check"><svg class="toast-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M 28.28125 6.28125 L 11 23.5625 L 3.71875 16.28125 L 2.28125 17.71875 L 10.28125 25.71875 L 11 26.40625 L 11.71875 25.71875 L 29.71875 7.71875 Z"/></svg></i><i class="toast-icon-item flex-item-centered toast-icon-fail las la-times"><svg class="toast-action-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M 7.21875 5.78125 L 5.78125 7.21875 L 14.5625 16 L 5.78125 24.78125 L 7.21875 26.21875 L 16 17.4375 L 24.78125 26.21875 L 26.21875 24.78125 L 17.4375 16 L 26.21875 7.21875 L 24.78125 5.78125 L 16 14.5625 Z"/></svg></i></div>' +
			'<div id="toast-message-box"></div>' +
			'</div>' +
			'</div>' +
			'<!-- /Toast -->');
	}

	$(document).on("click", ".snackbar", function (e) {
		e.stopPropagation();
	});

});
