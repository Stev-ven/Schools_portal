/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

$(document).ready(function () {

    $("head").append('<style>' +
        '.message-room-image-view{' +
			'width: 100%;' +
			'height: 100%;' +
			'position: fixed;' +
			'top: 0;' +
			'left: 0;' +
			'z-index: 999999999999999999;' +
			'display: none;' +
			'background-color: rgba(36, 43, 56, .9);' +
			'overflow: auto;' +
        '}' +
        '.message-room-image-view > span.mriv-closer{' +
			'box-shadow: 2px 2px 4px 1px rgb(0 0 0 / 12%);' +
			'display: block;' +
			'width: 45px;' +
			'height: 45px;' +
			'line-height: 45px !important;' +
			'border-radius: 50%;' +
            'background-color: #fff;' +
            'color: #333;' +
            'font-size: 1.4rem;' +
			'text-align: center;' +
			'cursor: pointer;' +
			'margin-right: 10px;' +
			'position: fixed;' +
			'right: 40px;' +
			'top: 40px; ' +
			'z-index: 10;' +
        '}' +
        '.message-room-image-view > div{' +
			'width: 100%;' +
			'height: 100%;' +
			'position: relative;' +
			'top: 0;' +
			'left: 0;' +
			'overflow: auto;' +
        '}' +
        '.message-room-image-view > div.mriv-loader{' +
			'display: none;' +
			'position: absolute;' +
			'z-index: 2;' +
        '}' +
        '.message-room-image-view > div.mriv-loader.active{' +
			'display: block;' +
        '}' +
        '.message-room-image-view > div.mriv-loader > div{' +
			'width: 100%;' +
			'height: 100%;' +
        '}' +
        '.mriv-image-container.nflex{' +
			'text-align: center !important;' +
			'display: block !important;' +
        '}' +
        '.mriv-view-image-btn{' +
			'pointer-events: auto !important;' +
			'cursor: zoom-in !important;' +
        '}' +
        '.mriv-view-image-btn.abs{' +
            'position: absolute;' +
            'top: 15px;' +
            'right: 10px;' +
        '}' +
        '.message-room-image-view > div > img.zoom-in{' +
			'max-width: 10000px;' +
			'max-height: 100000px;' +
			'/*cursor: zoom-out;*/' +
			'border-radius: 0;' +
			'margin: auto;' +
			'display: block;' +
        '}' +
        '.message-room-image-view > div > img{' +
			'max-width: 1280px;' +
            'max-height: 100000px;' +
			'/*cursor: zoom-in;*/' +
			'border-radius: 0;' +
        '}' +
        'img.mriv-image.minz {' +
			'transform: scale(.8);' +
        '}' +
        '@media screen and (min-width: 900px){' +
			'.message-room-image-view > span.mriv-closer:hover{' +
				'background-color: #000;' +
				'color: #fff;' +
			'}' +
			'.mriv-view-image-btn:not(.mvamg):hover{' +
				'transform: scale(1.1);' +
				'-webkit-transform: scale(1.1);' +
				'-moz-transform: scale(1.1);' +
				'-ms-transform: scale(1.1);' +
			'}' +
        '}' +
        '@media screen and (max-width: 900px){' +
			'.message-room-image-view > div > img{' +
				'border-radius: 0;' +
			'}' +
        '}' +
        '</style>');

    $("body").append('<section class="message-room-image-view">' +
        '<span class="mriv-closer las la-times cur"></span>' +
        '<div class="flex-item flex-item-align-items-center flex-item-justify-content-center mriv-image-container"></div>' +
        '<div class="mriv-loader">' +
        '<div class="flex-item flex-item-align-items-center flex-item-justify-content-center">' +
        '<svg style="vertical-align: middle;" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">' +
        '<path fill="#fff" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">' +
        '<animateTransform attributeType="xml"' +
        'attributeName="transform"' +
        'type="rotate"' +
        'from="0 25 25"' +
        'to="360 25 25"' +
        'dur="0.6s"' +
        'repeatCount="indefinite"/>' +
        '</path>' +
        '</svg>' +
        '</div>' +
        '</div>' +
        '</section>');

    const detectPlatformAndDevice = function () {
        /**
         * @returns object {
         *              platform -> string,
         *              device -> string
         *          }
        */
        const ua = navigator.userAgent;
        let dev = {
            platform: "web",
            device: "unknown"
        };

        if (ua.match(/Linux/i)) {
            dev = {
                platform: "web",
                device: "Linux"
            };
        }
        if (ua.match(/Macintosh/i)) {
            dev = {
                platform: "web",
                device: "Macintosh"
            };
        }
        if (ua.match(/mac os x/i)) {
            dev = {
                platform: "web",
                device: "mac os x"
            };
        }
        if (ua.match(/Windows/i)) {
            dev = {
                platform: "web",
                device: "Windows"
            };
        }
        if (ua.match(/win32/i)) {
            dev = {
                platform: "web",
                device: "win32"
            };
        }
        if (ua.match(/Android/i)) {
            dev = {
                platform: "mobile",
                device: "Android Phone"
            };
        }
        if (ua.match(/Iphone/i)) {
            dev = {
                platform: "mobile",
                device: "Iphone Phone"
            };
        }
        if (ua.match(/Ipad/i)) {
            dev = {
                platform: "mobile",
                device: "Ipad"
            };
        }
        if (ua.match(/iemobile/i)) {
            dev = {
                platform: "mobile",
                device: "Windows Phone"
            };
        }
        if (ua.match(/WPDesktop/i)) {
            dev = {
                platform: "mobile",
                device: "WPDesktop"
            };
        }
        if (ua.match(/Windows Phone/i)) {
            dev = {
                platform: "mobile",
                device: "Windows Phone"
            };
        }
        return dev;
    };


    $(document).on("click", ".mriv-closer, .message-room-image-view", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".mriv-image").addClass("minz");
        if(detectPlatformAndDevice().platform == "mobile"){
            $(".message-room-image-view").hide(0, function () {
                $("html, body").removeClass("hidden-ov");
                $(".mriv-image-container").removeClass("nflex").empty();
            });
        }
        else{
            setTimeout(function () {
                $(".message-room-image-view").hide(0, function () {
                    $("html, body").removeClass("hidden-ov");
                    $(".mriv-image-container").removeClass("nflex").empty();
                });
            }, 350);
        }
    });

    $(document).on("click", ".mriv-image", function (e) {
        e.stopPropagation();
        var $this = $(this);
        if ($this.hasClass("zoom-in")) {
            if(detectPlatformAndDevice().platform == "mobile"){
                $(".message-room-image-view").hide(0, function () {
                    $("html, body").removeClass("hidden-ov");
                    $(".mriv-image-container").removeClass("nflex").empty();
                });
            }
            else{
                $(".mriv-image-container").removeClass("nflex");
                setTimeout(function () {
                    $this.removeClass("zoom-in");
                }, 350);
            }
        }
        else {
            $this.addClass("zoom-in");
            setTimeout(function () {
                $(".mriv-image-container").addClass("nflex");
            }, 350);
        }
    });

    $(document).on("click", ".mriv-view-image-btn", function (e) {
        e.stopPropagation();
        var $this = $(this), src = $this.attr("data-src") || $this.attr("src");
        $(".message-room-image-view").show(0, function () {
            $("html, body").addClass("hidden-ov");
            $(".mriv-loader").addClass("active");
            var im = new Image();
            im.setAttribute("class", "mriv-image minz trans");
            im.onerror = function (e){
                Modules.toggleToastContainer({
                    message: "Sorry! The media was not able to load.",
                    status: Modules.status.FAILED
                });
                $(".mriv-loader").removeClass("active");
                $(".mriv-image").addClass("minz");
                if(detectPlatformAndDevice().platform == "mobile"){
                    $(".message-room-image-view").hide(0, function () {
                        $("html, body").removeClass("hidden-ov");
                        $(".mriv-image-container").removeClass("nflex").empty();
                    });
                }
                else{
                    setTimeout(function () {
                        $(".message-room-image-view").hide(0, function () {
                            $("html, body").removeClass("hidden-ov");
                            $(".mriv-image-container").removeClass("nflex").empty();
                        });
                    }, 350);
                }
            };
            im.onload = function (e) {
                if (this.naturalHeight) {
                    if (this.naturalHeight > 0 && this.naturalWidth > 0) {
                        $(".mriv-image-container")[0].appendChild(im);
                        setTimeout(function () {
                            $(".mriv-image").removeClass("minz");
                            $(".mriv-image-container").addClass("nflex");
                            if(detectPlatformAndDevice().platform == "mobile"){
                                $(".mriv-image").removeClass("zoom-in");
                            }
                        }, 350);
                    }
                    else {
                        if (this.height > 0 && this.width > 0) {
                            $(".mriv-image-container")[0].appendChild(im);
                            setTimeout(function () {
                                $(".mriv-image").removeClass("minz");
                                if(detectPlatformAndDevice().platform == "mobile"){
                                    $(".mriv-image-container").addClass("nflex");
                                    $(".mriv-image").removeClass("zoom-in");
                                    
                                }
                            }, 350);
                        }
                        else {
                            callAlert({
                                body: "Failed to load image"
                            });
                        }
                    }
                }
                $(".mriv-loader").removeClass("active");
            };
            im.src = src;
        });
    });

});