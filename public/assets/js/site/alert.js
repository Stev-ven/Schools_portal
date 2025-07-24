var azi = 999, snackTime;
var callAlert = function(details,rich){
	var def = typeof details.def === "undefined" ? false : details.def;
	if($(window).innerWidth() < 600 && def == false){
		callSnack({ body : details.body });
	}
	else{
		this.body = null;
		this.button = null;
		this.otext = null;
		if(typeof details === "object"){
			try{
				this.body = typeof details.body != "undefined" ? details.body : "";
				this.button = typeof details.button != "undefined" ? details.button : "";
				this.otext = typeof details.otext != "undefined" ? details.otext : "Got it!";
				$("body").append(
					'<div class="alert-container cp-hsc stopmove" style="z-index: ' + azi + ';display: none;">' + 
						'<div class="alert-container-contents stopmove">' + 
							'<div class="alert-main sca">' + 
								'<div class="alert-text spscr" style="font-weight: 400 !important;" ontouchstart="elemTouchStartY(this,event);" ontouchmove="elemTouchMoveY(this,event);">' + 
									this.body.replace("Failed.COC.Error","").replace("Login.COC.Error","") + 
								'</div>' + 
								'<div class="clearfix">' + 
									'<button class="alert-closer" onclick="event.stopPropagation();$(this).closest(\'.alert-container\').remove();">' + this.otext + '</button>' + 
									this.button + 
								'</div>' + 
							'</div>' + 
						'</div>' + 
					'</div>'
				);
				$(".alert-container").show(0, function(){
					$(".alert-main").removeClass("sca");
				});
			}
			catch(err){
				alert(err);
			}
		}	
	}
}

$(document).ready(function(){
	$(document).on("click", ".-why-btn-", function(){
		callAlert({ body : $(this).attr("data-why") , def : true});
	});
});

function callSnack(details){
	if(typeof details === "object"){
		$(".snack-details").html(details.body || "");
		$(".snackbar").removeClass("inactive");
		clearTimeout(snackTime);
		snackTime = setTimeout(hideSnackBar, 15000);
	}
}

function hideSnackBar(){
	$(".snack-details").html("");
	$(".snackbar").addClass("inactive");
	clearTimeout(snackTime);
}

var alertCSS = '.alert-container{' + 
                    'width:100%;' + 
                    'position:fixed;' + 
                    'top:0;' + 
                    'left:0;' + 
                    'height:100%;' + 
                    'z-index:999;' + 
                    'background-color:rgba(36,43,56,0.8);' + 
                '}' + 
                '.alert-container > .alert-container-contents{' + 
                    'width:100%;' + 
                    'height:100%;' + 
                    'display:flex;' + 
                    'display:-webkit-flex;' + 
                    'align-items:center;' + 
                '}' + 
                '.alert-container > .alert-container-contents > div{' + 
                    'width:35%;' + 
                    'max-width:500px;' + 
                    'min-width:298px;' + 
                    'background-color:#FFF;' + 
                    'margin:auto;' + 
                    'box-shadow:0 0 3px 2px rgba(0,0,0,0.14);' + 
                    'overflow:hidden;' + 
                    'border-radius:2px;' + 
                '}' + 
                '.alert-container > .alert-container-contents > div > div{' + 
                    'padding:25px;' + 
                    'max-height: 250px;' + 
                    'overflow-y: auto;' + 
                '}' + 
                '.alert-container > .alert-container-contents > div > div:first-child{' + 
                    'font-size: 1.1rem;' + 
					'color: #414141;' + 
                    'lineHeight:24px;' + 
                    'font-weight:500;' + 
                '}' + 
				'.alert-container > .alert-container-contents > div.alert-main, .alert-container > .alert-container-contents > div > div:nth-child(2) button{' + 
                    'transition: all 250ms ease;' + 
                    '-webkit-transition: all 250ms ease;' + 
                '}' + 
                '.alert-container > .alert-container-contents > div > div:nth-child(2){' + 
                    'border-top:1px solid #f7f7f7;' + 
                '}' + 
                '.alert-container > .alert-container-contents > div > div:nth-child(2) button{' + 
                    'float: right;' + 
					'padding: 8px 20px;' + 
					'color: #007aff;' + 
					'border: 1px solid #007aff;' + 
					'font-weight: 500;' + 
					'font-size: 1.2rem;' + 
					'margin: 0.50%;' + 
					'background-color: transparent;' + 
					'border-radius: 60px;' + 
					'/* border-width: 0; */' + 
					'cursor: pointer;' + 
                '}' + 
				'.alert-container > .alert-container-contents > div > div:nth-child(2) button:hover{' + 
					'background-color: #007aff;' +
					'color: #fff;' +
				'}';
				

$("document").ready(function(){
    $("head").append('<style class="alert-css">' + alertCSS + '</style>');
	
	$(document).on("click", ".snackbar", function(e){
		e.stopPropagation();
	});
});
