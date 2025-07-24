/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

let toastTimeout = null,
    searcherTimeout = null,
    loadingState = false;

const Modules = {
    WEBP_SUPPORTED: false,
    PAGE_TITLE: document.title,
    APPNAME: __GLOBALS__.APPNAME,
    EMAIL: __GLOBALS__.EMAIL,
    SUPPORT_EMAIL: __GLOBALS__.SUPPORT_EMAIL,
    MOBILE: __GLOBALS__.MOBILE,
    CURRENCY: __GLOBALS__.CURRENCY,
    DOMAIN: __GLOBALS__.DOMAIN,
    API_KEY: __GLOBALS__.API_KEY,
    GOOGLE_DOCS_URL: "https://docs.google.com/viewer?url=",
    CURRENT_LOCATION: location.href,
    CURRENT_LOCATION_NQS: location.href.split("?")[0],
    REFERER: (document.querySelector("#document-referer") !== null && document.querySelector("#document-referer") !== undefined) ? document.querySelector("#document-referer").value : location.href,
    URL: "./resources/server/site/controller/controller",
    ERROR_IMAGE: "./media/img/default/image_error.png",
    IMAGE_PLACEHOLDER: "./media/img/default/transparent_img.jpg",
    REQUEST_METHOD: {
        POST: "POST",
        GET: "GET",
        REQUEST: "REQUEST",
    },
    status: {
        OKAY: "ok",
        FAILED: "_failed",
        UNKNOWN_ERROR: "An unknown error occurred."
    },
    defaultDialCode: null,
    emptyData: [],
    totalDashboardNotifications: 0,
    notificationToastWaitTime: 1,
    notificationToastMessages: [],
    __INIT__: function(){
        Modules.supportFormatWebp();
    },
    maintainScrollPosition: function (n) {
        const ScrollPosition = function (node) {
            this.node = node;
            this.previousScrollHeightMinusTop = 0;
            this.readyFor = 'up';
        }
        ScrollPosition.prototype.restore = function () {
            if (this.readyFor === 'up') {
                this.node.scrollTop = (this.node.scrollHeight - this.previousScrollHeightMinusTop) - 100;
            }
        }
        ScrollPosition.prototype.prepareFor = function (direction) {
            this.readyFor = direction || 'up';
            this.previousScrollHeightMinusTop = this.node.scrollHeight - this.node.scrollTop;
        }
        return new ScrollPosition(n);
    },
    scrollTo: function (item, pos, callBack) {
        $("html, body").animate({
            scrollTop: isNaN(pos) ? ($(item).offset().top - 150) : pos
        }, function () {
            if (typeof callBack === "function") {
                callBack();
            }
        });
    },
    scrollToTopZero: function () {
        $("html, body").animate({
            scrollTop: 0
        });
    },
    stripTags: function (str) {
        return str.toString().replace(/(<([^>]+)>)/gi, "");
    },
    inArray: function (d, s) {
        return d.lastIndexOf(s) === -1 ? false : true;
    },
    removeVisibilitySate: function (el) {
        el.removeAttribute("data-invisible-state");
    },
    encodeURI: function (s) {
        return (s !== null) && typeof s === "string" ? encodeURIComponent(s) : s;
    },
    decodeURI: function (s) {
        return (s !== null) && typeof s === "string" ? decodeURIComponent(s) : s;
    },
    getEncodedCurrentPageURL: function () {
        return Modules.encodeURI(location.href);
    },
    reloadCurrentPage: function () {
        window.location.reload();
    },
    joinSkillFeatures: function(features){
        let featureList = [];

        features.map(function(feature){
            featureList.push(feature.feature_name);
        });

        return featureList.join(" ||| ");
    },
    serializeJSON: (data) => {
        let serialized = "";
        for (let x in data) {
            serialized += x + "=" + data[x] + "&";
        }
        return Modules.isEmpty(serialized) ? "" : "&" + serialized;
    },
    serializeForm: (form, extra, formArray) => {
        let data = {};
        document.querySelectorAll(form).forEach(function (item) {
            data[item.getAttribute("name")] = item.value;
        });
        if (extra !== null && extra !== undefined) {
            extra.map(function (item) {
                data[item.name] = item.value;
            });
        }
        if (formArray !== null && formArray !== undefined) {
            formArray.map(function (fA) {
                let fL = [], d = {};
                document.querySelectorAll(fA.formElements).forEach(function (item) {
                    if (('useAttr' in fA)) {
                        d[item.getAttribute("data-name")] = item.getAttribute("data-value");
                        fL.push(d);
                    }
                    if (('useAttrArr' in fA)) {
                        fL.push(item.getAttribute(fA.getAttr));
                    }
                    if (('useVal' in fA)) {
                        d[item.getAttribute("name")] = item.value;
                        fL.push(d);
                    }
                    if (('useValArr' in fA)) {
                        fL.push(item.value);
                    }
                    if (('useText' in fA)) {
                        d[item.getAttribute("name")] = item.textContent;
                        fL.push(d);
                    }
                    if (('useTextArr' in fA)) {
                        fL.push(item.textContent);
                    }
                });
                data[fA.formKey] = fL;
            });
        }
        return data;
    },
    emptyForm: (form) => {
        document.querySelectorAll(form).forEach(function (item) {
            item.value = "";
        });
    },
    convertDatetime: function (strtotime, time, format) {
        let time_elapsed, seconds, minutes, hours, days, weeks, months, years;
        time_elapsed = time - strtotime;
        seconds = time_elapsed;
        minutes = Math.round(time_elapsed / 60);
        hours = Math.round(time_elapsed / 3600);
        days = Math.round(time_elapsed / 86400);
        weeks = Math.round(time_elapsed / 604800);
        months = Math.round(time_elapsed / 2600640);
        years = Math.round(time_elapsed / 31207680);
        format = format !== null ? format : 0;

        // Seconds
        if (seconds <= 60) {
            if (seconds == 0) {
                return (format == 1) ? "5s" : "A while ago";
            }
            return (format == 1) ? "5s" : "A while ago";
        }

        // Minutes
        else if (minutes <= 60) {
            if (minutes == 1) {
                return (format == 1) ? "1min" : "A minute ago";
            }
            return (format == 1) ? minutes + "min" : minutes + " minutes ago";
        }

        // Hours
        else if (hours <= 24) {
            if (hours == 1) {
                return (format == 1) ? "1h" : "An hour ago";
            }
            return (format == 1) ? hours + "h" : hours + " hours ago";
        }

        // Days
        else if (days <= 7) {
            if (days == 1) {
                return (format == 1) ? "1d" : "Yesterday";
            }
            return (format == 1) ? days + "d" : days + " days ago";
        }

        // Weeks
        else if (weeks <= 4.3) {
            if (weeks == 1) {
                return (format == 1) ? "1w" : "Last week";
            }
            return (format == 1) ? weeks + "w" : weeks + " weeks ago";
        }

        // Months
        else if (months <= 12) {
            if (months == 1) {
                return (format == 1) ? "1mon" : "Last month";
            }
            return (format == 1) ? months + "mon" : months + " months ago";
        }

        // Years
        else {
            if (years == 1) {
                return (format == 1) ? "1y" : "A year ago";
            }
            return (format == 1) ? years + "y" : years + " years ago";
        }
    },
    getTimeText: function () {
        const today = new Date();
        const curHr = today.getHours();

        if (curHr < 12) {
            return 'Good morning,';
        } else if (curHr < 18) {
            return 'Good afternoon,';
        } else {
            return 'Good evening,';
        }
    },
    truncateNumber2dp: function(num) {
        if(num === null) return 0.00;
        return num.toString().match(/^-?\d+(?:\.\d{0,2})?/)[0];
    },
    reload: function(){
        window.location.reload();
    },
    toggleLoadingBtn: (b, s) => {
        switch (s) {
            case true:
                b.classList.add("loading");
                break;
            default:
                b.classList.remove("loading");
        }
    },
    togglePageLoader: (s) => {
        switch (s) {
            case true:
                document.querySelector("#loading_container").classList.add("loading");
                break;
            default:
                document.querySelector("#loading_container").classList.remove("loading");
        }
    },
    toggleToastContainer: (data, callBack) => {
        if (document.querySelector("#toast") !== null && document.querySelector("#toast") !== undefined) {
            if (typeof data.message === "string" || typeof data.message === "number") {
                clearTimeout(toastTimeout);
                document.querySelector("#toast").classList.remove("fail");
                document.querySelector("#toast").classList.remove("success");
                document.querySelector("#toast-message-box").innerHTML = data.message.length >= 200 ? data.message.substring(0, 200) : data.message;
                document.querySelector("#toast").classList.remove("inactive");

                if (('status' in data)) {
                    switch (data.status) {
                        case Modules.status.OKAY:
                            //document.querySelector("#toast").classList.add("success");
                            break;
                        case Modules.status.FAILED:
                            document.querySelector("#toast").classList.add("fail");
                            break;
                    }
                }

                toastTimeout = setTimeout(() => {
                    document.querySelector("#toast").classList.add("inactive");
                    if (typeof callBack === "function") {
                        callBack();
                    }
                }, (data.timeout || (Modules.calculateReadingTime(data.message) * 1000)));
            }
        }
    },
    redirect: function (u, t, timeout) {
        /**
         * @param string u
         * @param string t [null, undefined, _blank, _top, _parent, _self, ...custom]
        */
        t = typeof t === "undefined" || typeof t === null ? "_self" : t;
        if (typeof timeout === "number") {
            setTimeout(function () {
                window.open(u, t);
            }, 3000);
            return;
        }

        window.open(u, t);
    },
    dataURItoBlob: function(dataURI, options) {
        try {
            var byteString = atob(dataURI.split(',')[1]);
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            
            var blob;
            if(typeof options === "object"){
                blob = new Blob([ab], {
                    type: mimeString
                }, options.name);
            }
            else{
                blob = new Blob([ab], {
                    type: mimeString
                });
            }
            
            return blob;
        } 
        catch (err) {
            //err
        }
    },
    uuid: function() {
        // return `${mask}`.replace('/[xy]/g', function(c) {
        //     let r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        //     return v.toString(16);
        // });
        var d = new Date().getTime();//Timestamp
        var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16;//random number between 0 and 16
            if(d > 0){//Use timestamp until depleted
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {//Use microseconds since page-load if supported
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    },
    UPLOADPATHS: {
        PROFILEPHOTO: "media/img/profile/",
        PROFILEPHOTOLARGE: "media/img/profile_large/",
        BACKGROUNDPHOTO: "assets/media/bg/",
        VERIFICATIONPHOTO: "media/img/verifications/",
        COMPANYDOCUMENT: "media/document/company_docs/",
        VIDEOCOVERSMALL: "media/img/videos/cover/small/",
        VIDEOCOVERLARGE: "media/img/videos/cover/large/",
    },
    s3DO: function(){
        const sCrypto = new SimpleCrypto("ad92ec00be2cfb8a406c3aa3c051f9518fbd01305f63d3c1987aa9f1b2dff325wfsAwf1WAeuuMN0uRNouqg==0c3f83f7368ef973f0604954bf63396da8ad6f1aafd441573d79d2a18112d38b");

        const options = {
            bucket: "cdi-media",
            ACL: "public-read"
        };

        const s3 = new AWS.S3({
            forcePathStyle: false,
            endpoint: "https://sfo3.digitaloceanspaces.com",
            region: "us-west",
            credentials: {
              accessKeyId: sCrypto.decrypt("d0f124dc439052ff30113edb6549fa1195ff1a869f53732bc5763e48bbb7eaecMYnlblIwBSi8HS2e82CzSO9YejFTlieIjhqWS5+EGTQ=79775f9914a2dc87d54c28846e764c3d95b6f59139a93fbe3ecd89bd75e24444"),
              secretAccessKey: sCrypto.decrypt("64a177b4ba2ecd224501f8e9a29751cebe5a9d6bc24e0597a01ddf7ba166d123TDL6GA3dqq9HeqiFxn+LkrAnrgzY6rJqZLU34ffO4UArc4K3dry3dOcikSTfxQ81ad23a60c0407feed5de1374703127b56adcd06b5a1709876f6c116570f4dc2ed")
            }
        });

        return {
            options: options,
            s3: s3
        };
    },
    uploadFilesToBuckets3DO: async function(files, callback){
        try{
            if(typeof callback !== "function") callback = function(){};
            const AWS = Modules.s3DO();
            const responses = await Promise.all(files.map(file => {
                return AWS.s3.upload({Bucket: AWS.options.bucket, Key: file.name, Body: file.content, ACL: AWS.options.ACL}).promise().then(data => Promise.resolve(data)).catch(err => Promise.reject(err));
            })).catch(err => {
                callback({
                    error: true
                });
            });
            return responses;
        }
        catch(err){
            callback({
                error: true
            });
        }
    },
    deleteFilesFromBuckets3DO: async function(files){
        const AWS = Modules.s3DO();
        const responses = await Promise.all(files.map(file => {
            return AWS.s3.deleteObject({Bucket: AWS.options.bucket, Key: file.name}).promise().then(data => Promise.resolve(data));
        }));
        return responses;
    },
    switchImages: function(other, webp){
        if(Modules.WEBP_SUPPORTED) return webp;
        return other;
    },
    supportFormatWebp: function () {
        var elem = document.createElement('canvas');

        if (!!(elem.getContext && elem.getContext('2d'))) {
            if(elem.toDataURL('image/webp').indexOf('data:image/webp') == 0) Modules.WEBP_SUPPORTED = true;
        }
        else {
            return false;
        }
    },
    getBase: function (s) {
        /**
         * @param string s
        */
        let b = new String(s).substring(s.lastIndexOf('/') + 1);
        if (b.lastIndexOf(".") != -1) {
            b = b.substring(0, b.lastIndexOf("."));
        }
        return b;
    },
    getURLProps: function (U) {
        /**
         * @param string U
         * @returns object {
         *              protocol -> string,
         *              hostname -> string,
         *              port -> number,
         *              pathname -> string,
         *              search -> string,
         *              hash -> string,
         *              host -> string
         *          }
         * #Rewrite URL with .htaccess
         * RewriteEngine on
         * RewriteRule ^index(.*)?$ index.html  [NC]
        */
        let p = document.createElement('a');
        p.href = U;
        return p;
    },
    getDateFormatted: function () {
        const date = new Date();
        let hours = date.getHours();
        let minutes = date.getMinutes();
        let ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        return hours + ':' + minutes + '' + ampm;
    },
    getSearcherInputType: function () {
        return Modules.detectPlatformAndDevice().platform == "web" ? "text" : "search";
    },
    isPageVisible: function () {
        const body = document.documentElement || document.body;
        if (body.classList.contains("stop-all-intervals")) return false;
        return true;
    },
    shortenCounts: function (num) {
        return !isNaN(num) ? (parseInt(num) >= 10 ? '9+' : num) : num;
    },
    handlePageVisibility: function () {
        const onVisibilityChange = function () {
            const body = document.documentElement || document.body;
            if (document.hidden) {
                body.classList.add("stop-all-intervals");
            }
            else {
                body.classList.remove("stop-all-intervals");
            }
        }
        document.addEventListener("visibilitychange", onVisibilityChange);
    },
    getInnerHeight: function (elm) {
        if (document.querySelector(elm) === null || document.querySelector(elm) === undefined) return;
        const computed = getComputedStyle(elm), padding = parseInt(computed.paddingTop) + parseInt(computed.paddingBottom);
        return elm.clientHeight - padding;
    },
    ucFirst: function (str) {
        return typeof str === "string" ? (str.length > 0) ? (str[0].toUpperCase() + str.substring(1)) : str : str;
    },
    isEmpty: function (str) {
        if (str === null) return true;
        return typeof str === "string" ? Modules.trim(str) == "" ? true : false : false;
    },
    getQueryString: function (f, u) {
        var h = u ? u : window.location.href;
        var r = new RegExp('[?&]' + f + '=([^&#]*)', 'i');
        var s = r.exec(h);
        return s ? decodeURIComponent(s[1]) : null;
    },
    removeURLVars: function (vars, url) {
        var rtn = url.split("?")[0],
            param,
            params_arr = [],
            queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";

        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (Modules.inArray(vars, param)) {
                    params_arr.splice(i, 1);
                }
            }
            if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
        }
        return rtn;
    },
    getURLVar: function (varName, varAlt) {
        let l = Modules.getCurrentURL();
        return Modules.getQueryString(varName, l) !== null && Modules.trim(Modules.getQueryString(varName, l)) !== "" ? Modules.getQueryString(varName, l) : varAlt;
    },
    replaceURLState: function (url) {
        window.history.replaceState(null, null, url);
    },
    calculateReadingTime: function (text) {
        const wpm = 2;
        const words = text.trim().split(/\s+/).length;
        const time = Math.ceil(words / wpm);
        return time;
    },
     topUpDate: function (days) {
        let someDate, numberOfDaysToAdd, dd, mm, y;
        someDate = new Date();
        numberOfDaysToAdd = days || 0;
        someDate.setDate(someDate.getDate() + numberOfDaysToAdd);

        dd = someDate.getDate();
        mm = someDate.getMonth() + 1;
        y = someDate.getFullYear();

        return y + '-' + mm + '-' + dd;
    },
    convertSize: function (bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
    useTimeout: function (func, time) {
        setTimeout(func, typeof time !== "number" ? 2000 : time);
    },
    trim: function (str) {
        return typeof str === "string" ? str.replace(/^\s+/, "").replace(/\s+$/, "") : str;
    },
    scrollTop: (elem) => {
        elem.scrollTop(0);
    },
    buildQuery: function (data, exclude) {
        let query = "";
        for (let x in data) {
            if (!Modules.inArray(exclude !== null ? exclude : [], x)) {
                query += (x + "=" + data[x] + "&");
            }
        }
        return query.replace(/&{1}$/, "");
    },
    getCurrentQuerystring: function () {
        return window.location.search;
    },
    getCurrentURL: function (encode) {
        return encode ? Modules.encodeURI(window.location.href) : window.location.href;
    },
    getCurrentURLWQ: function (encode) {
        let path = window.location.href.split('?')[0];
        return encode ? Modules.encodeURI(path) : path;
    },
    getFileEXT: function (f) {
        const spl = (f !== null && f !== undefined ? f : "").split(".");
        if (spl.length > 0) return spl[spl.length - 1].toLowerCase();
        return "";
    },
    sendFetchData: function (url, method, payload, callBack, timeout) {
        /**
         * @param string url 
         * @param string method 
         * @param object payload 
         * @param function callBack 
         * @param number timeout 
         * @returns null
        */

        const _failedPayload = {
            status: "_failed",
            data: "An error occurred. Might be that the content was not found, request took too long, an internet connection problem, an unreacheable URL, or a request failed to reach the server. Try again.",
        };

        timeout = (typeof (timeout) === "undefined" || timeout === null) ? 120000 : timeout;
        if (('fetch' in window) && !('files' in payload)) {
            //Using the fetch API
            const controller = new AbortController(),
                tout = setTimeout(function () {
                    callBack(_failedPayload);
                    controller.abort();
                }, timeout);

            let body, qs = "";

            switch (method.toLowerCase()) {
                case "request":
                    url += Object.keys(payload).length > 0 && payload.task !== null && payload.data !== null ? "?aid="+ __GLOBALS__.USERID +"&task=" + payload.task + Modules.serializeJSON(payload.data) : "";
                    body = {
                        signal: controller.signal,
                        method: "GET",
                        mode: "cors"
                    };
                    break;
                case "get":
                    url += Object.keys(payload).length > 0 && payload.task !== null && payload.data !== null ? "?aid="+ __GLOBALS__.USERID +"&task=" + payload.task + Modules.serializeJSON(payload.data) : "";
                    body = {
                        signal: controller.signal,
                        method: method,
                        mode: "cors",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "Api-Key": Modules.API_KEY,
                        }
                    };
                    break;
                case "post":
                    body = {
                        signal: controller.signal,
                        method: method,
                        mode: "cors",
                        cache: "no-cache",
                        referrer: "no-referrer",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "Api-Call": "JSON-Encoded",
                            "Api-Key": Modules.API_KEY,
                        },
                        body: JSON.stringify({
                            aid: __GLOBALS__.USERID,
                            task: payload.task,
                            data: payload.data
                        })
                    };
                    break;
                default:
                    callBack(_failedPayload);
                    return;
            }

            fetch(url, body)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    clearTimeout(tout);
                    callBack(data);
                })
                .catch(err => {
                    clearTimeout(tout);
                    callBack(_failedPayload);
                });
        }
        else {
            //Using XMLHttpRequest
            let xhr = new XMLHttpRequest();

            xhr.responseType = 'json';
            xhr.timeout = timeout;
            xhr.onerror = function (error) {
                callBack(_failedPayload);
            };
            xhr.ontimeout = function (error) {
                callBack(_failedPayload);
            };
            xhr.onabort = function (error) {
                callBack(_failedPayload);
            };
            xhr.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        callBack(this.response);
                        return null;
                    }
                    callBack(_failedPayload);
                }
            }
            
            var fda = new FormData();
            fda.append("aid", __GLOBALS__.USERID);
            fda.append("task", payload.task);
            fda.append("data", payload.data);

            if (('files' in payload) && payload.files.length > 0) {
                if ('fileUploadProgress' in payload) {
                    xhr.upload.onprogress = function (e) {
                        payload.fileUploadProgress(e.loaded, e.total);
                    };
                }
                payload.files.map(function (item) {
                    if (Object.prototype.toString.call(item.data) === '[object Array]') {
                        item.data.map(function (subItem) {
                            if ('filename' in item) {
                                fda.append(item.name + "[]", subItem, item.filename);
                            }
                            else {
                                fda.append(item.name + "[]", subItem);
                            }
                        });
                    }
                    else {
                        fda.append(item.name, item.data);
                    }
                });
            }

            xhr.open(method, url, true);
            xhr.setRequestHeader("Api-Key", Modules.API_KEY);
            xhr.send(fda);
        }
        return null;
    },
    isValidJSON: (d) => {
        try {
            if (typeof d === "object") {
                return true;
            }
            return false;
        }
        catch (err) {
            return false;
        }
    },
    encodeJSON: (d) => {
        return JSON.stringify(d);
    },
    decodeJSON: (d) => {
        return JSON.parse(d);
    },
    parseJSON: (d) => {
        return JSON.parse(d);
    },
    replaceNewLines: function (s) {
        return s.toString().replace(/(?:\r\n|\r|\n)/g, '<br>');
    },
    render: {
        renderSelectedPhoto: function(data, options){
            options.container.html(`<div class="create-new-user-account-item-profile-photo-item ${options.additionalClass}">
                <img class="mriv-view-image-btn mvamg" src="${data.photo}"/>
                <i class="las la-times create-new-user-account-item-profile-photo-item-remove-btn"></i>
            </div>`);
        },
        renderProfilePortfolio: function(data, options, callback){
            data.map(function(item){
                options.container.append(`<!-- Portfolio item -->
                <div class="portfolio-item profile-portfolio-item mriv-view-image-btn mvamg" data-photo-id="${item.photo_id}" data-src="${item.portfolio_photo_large}">
                    <div>
                        <img src="${item.portfolio_photo_small}" alt=""/>
                        <i class="las la-trash profile-photo-remover" data-portfolio-id="${item.id}" data-photo-id="${item.photo_id}"></i>
                    </div>
                </div>
                <!-- Portfolio item -->`);
            });

            callback();
        },
        renderReviewRating: function(rate){
            switch(parseInt(rate)){
                case 1:
                    return `<i class="las la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>`;
                case 2:
                    return `<i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>`;
                case 3:
                    return `<i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="lar la-star"></i>
                            <i class="lar la-star"></i>`;
                case 4:
                    return `<i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="lar la-star"></i>`;
                case 5:
                    return `<i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>`;
            }
        },
        renderReviews: function(data, options, callback){
            data.map(function(item){
                options.container.append(`<!-- Review -->
                <div class="review-container-item ${options.class}">
                    <div class="flex-item">
                        <div>
                            <img src="${item.user.profile_photo_small_webp}" alt="">
                        </div>
                        <div>
                            <div><a class="${parseInt(item.user.is_verified) > 0 ? 'verified-user mini' : ''}">${item.user.full_name}</a></div>
                            <div>
                                <span class="review-rating">
                                    ${Modules.render.renderReviewRating(item.rating)}
                                </span>
                            </div>
                            <div>${item.datetime} &#0149; ${item.category}</div>
                        </div>
                    </div>
                    <div>
                        ${item.review}
                    </div>
                    ${!Modules.isEmpty(item.reply) ? `<!-- review reply container -->
                    <div class="review-reply-container">
                        <div class="review-container-item profile-review-reply-item">
                            <div class="flex-item">
                                <div>
                                    <img src="${item.reviewed.profile_photo_small_webp}" alt="">
                                </div>
                                <div>
                                    <div><a class="${parseInt(item.reviewed.is_verified) > 0 ? 'verified-user mini' : ''}">${item.reviewed.full_name}</a></div>
                                    <div></div>
                                    <div>REPLY</div>
                                </div>
                            </div>
                            <div>
                                ${item.reply}
                            </div>
                            <div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- review reply container -->` : ``}
                </div>
                <!-- /Review -->`);
            });

            callback();
        },
        renderProfileSkills: function(data, options, callback){
            data.map(function(item){
                options.container.append(`<a class="profile-skill-item skill-item">${item.skill_name}</a>`);
            });

            callback();
        },
        renderNotes: function(data, options, callback){
            data.map(function(item){
                const elem = `<!-- note item -->
                <div class="${options.class} note-item ${item.is_admin === "yes" ? "" : "full"} flex-item" data-note-id="${item.id}">
                    <div>
                        <div><h5 class="note-item-title">${item.title}</h5></div>
                        <div class="note-item-description">${item.note}</div>
                        <div class="note-item-date">Added ${item.datetime_added}</div>
                    </div>
                    ${item.is_admin === "yes" ? `<div>
                    <div class="pos-rel dropdown-menu-2 drop-btn">
                        <i class="las la-ellipsis-v drop-btn-icon"></i>
                        <ul class="drop-child-item hiddible exc">
                            <li>
                                <div class="cur pdrop-item delete-note-item-btn" data-note-id="${item.id}">
                                    <div></div>
                                    <div>Delete</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>` : ``}
                </div>
                <!-- note item -->`;

                if(('action' in options)){
                    switch(options.action){
                        case "prepend":
                            options.container.prepend(elem);
                            break;
                        case "append":
                            options.container.append(elem);
                            break;
                    }
                }
                else{
                    options.container.append(elem);
                }
            });

            callback();
        },
        renderProfileServicesSelling: function(data, options, callback){
            data.map(function(item){
                options.container.append(`<!-- service item -->
                <div class="services-selling-container-item profile-services-selling-item flex-item">
                    <div>
                        <img src="${item.first_photo[0].photo}" alt=""/>
                    </div>
                    <div>
                        <div><a class="services-link" href="${item.link}" target="_blank">${item.title}</a></div>
                        <div>${Modules.CURRENCY + item.packages[0].price}</div>
                        <div>${item.datetime} &#0149; ${item.number_of_sales} ${parseInt(item.number_of_sales) === 1 ? "sale" : "sales"}</div>
                    </div>
                </div>
                <!-- /service item -->`);
            });

            callback();
        },
        renderProfileServicesPurchased: function(data, options, callback){
            data.map(function(item){
                options.container.append(`<!-- service item -->
                <div class="services-selling-container-item profile-services-purchased-item flex-item">
                    <div>
                        <img src="${item.first_photo[0].photo}" alt=""/>
                    </div>
                    <div>
                        <div><a class="services-link" href="${item.link}" target="_blank">${item.title}</a></div>
                        <div>${Modules.CURRENCY + item.package_price}</div>
                        <div>${item.datetime}</div>
                    </div>
                </div>
                <!-- /service item -->`);
            });

            callback();
        },
        renderSkillSearch: function(data, options, callBack){
            if(data.length === 0){
                options.container.html(`<!-- search results item -->
                <div class="search-skills-container-result-item disabled-element">
                    <div>No results found</div>
                    <div></div>
                </div>
                <!-- /search results item -->`);
            }

            data.map(function(item){
                options.container.append(`<!-- search results item -->
                <div class="search-skills-container-result-item search-skills-container-result-item-add-btn" data-class="${options.class}" data-container="${options.cnt}" data-skill-name="${item.skill_name}" data-skill-id="${item.skill_id}">
                    <div>${item.skill_name}</div>
                    <div><i class="las la-plus"></i></div>
                </div>
                <!-- /search results item -->`);
            });

            callBack();
        },
        renderAddedSkill: function(data, options){
            if(options.container.hasClass("one")) options.container.empty();
            options.container.append(`<span class="search-skills-container-footer-item ${options.class}" data-skill-id="${data.skill_id}">
                <span>${data.skill_name}</span>
                <i class="las la-times search-skills-container-footer-item-remove-btn"></i>
            </span>`);
        },
        renderProfileSummary: function(data, options, callback){
            options.cardContainer.html(`<!-- user profile card -->
            <div class="user-profile-card flex-item">
                <div>
                    <img class="user-profile-view-image-item" src="${data.profile_photo_small_webp}" alt="${data.full_name}"/>
                </div>
                <div>
                    <div><a target="_blank"  href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.user_id}" class="link-default ${parseInt(data.is_verified) > 0 ? "verified-user mini" : ""}">${data.full_name}</a></div>
                    <div>@${data.user_name}</div>
                    <div><br /></div>
                    <div>
                        <a data-target="#manage_user_blocks_modal" data-toggle="modal" data-user-id="${data.id}" style="cursor: pointer; color: #fff;" class="btn btn-brand btn-bold btn-sm cur pdrop-item manage-user-blocks-btn">
                            <i class="la la-cog"></i> Manage blocks
                        </a>
                    </div>
                    <div><br /></div>
                    <div>
                        <a data-user-id="${data.id}" style="cursor: pointer; color: #fff; background-color: #e74032; border-color: #e74032;" class="btn btn-brand btn-bold btn-sm cur get-user-account-details-btn">
                            <i class="la la-edit"></i> Edit profile
                        </a>
                    </div>
                </div>
            </div>
            <!-- /user profile card -->`);

            options.container.html(`<!-- profile view photo -->
            <div class="profile-view-photo flex-item-centered">
                <!-- image container -->
                <div class="simple-image-container-item">
                    <img class="user-profile-view-image-item" src="${data.profile_photo_large_webp}" alt="${data.full_name}"/>
                    <label for="user-profile-photo-uploader">
                        <i class="las la-camera"></i>
                        <input type="file" id="user-profile-photo-uploader" hidden/>
                    </label>
                </div>
                <!-- image container -->
            </div>
            <!-- /profile view photo -->

            <!-- name and bio container -->
            <div class="simple-name-bio-container">
                <div><a target="_blank" href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.user_id}" class="link-default ${parseInt(data.is_verified) > 0 ? "verified-user mini" : ""}">${data.full_name}</a></div>
                <div>@${data.user_name}</div>
                <div>${data.short_biography}</div>
                <div>${data.biography}</div>
            </div>
            <!-- /name and bio container -->

            <!-- basic details container -->
            <div class="basic-details-container">
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>USER ID</div>
                    <div>${data.user_id}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>First name</div>
                    <div>${data.first_name}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Last name</div>
                    <div>${data.last_name}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Account type</div>
                    <div>${data.account_type}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Aavailability</div>
                    <div>${data.availability > 0 ? "Not available" : "Available"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Word usage warnings</div>
                    <div>${data.word_usage_warnings}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Contact usage warnings</div>
                    <div>${data.contact_entry_warnings}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Activated</div>
                    <div>${parseInt(data.is_activated) > 0 ? "Yes" : "No"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Blocked</div>
                    <div>${parseInt(data.is_blocked) > 0 ? "Yes" : "No"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Is worker at ${__GLOBALS__.APPNAME}</div>
                    <div>${parseInt(data.is_worker_at_cedijob) > 0 ? "Yes" : "No"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Joined</div>
                    <div>${data.datetime_joined}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Joined via</div>
                    <div>${data.joined_via}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Email address</div>
                    <div>${data.email_address}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Mobile number</div>
                    <div>${data.mobile_number_country_number + data.mobile_number}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Gender</div>
                    <div>${data.gender}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Sign Ins</div>
                    <div>${data.number_of_user_signins}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Total amount earned</div>
                    <div>${__GLOBALS__.CURRENCY + data.amount_earned}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Amount earned from services</div>
                    <div>${__GLOBALS__.CURRENCY + data.order_amount_earned}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Total amount paid</div>
                    <div>${__GLOBALS__.CURRENCY + data.amount_paid}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Amount paid for services</div>
                    <div>${__GLOBALS__.CURRENCY + data.order_amount_paid}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Ratings</div>
                    <div>${parseInt(data.ratings) >= 0 ? parseInt(data.ratings).toFixed(0) : "N/A"}</div>
                </div>
                <!-- /basic details container item -->
            </div>
            <!-- /basic details container -->`);

            callback();
        },
        renderProjectStatus: function(status){
            switch(status.toString().toLowerCase()){
                case "accepting_applicants":
                    return "Accepting applicants";
                case "completed":
                    return "Completed";
                case "cancelled":
                    return "Cancelled";
                case "in_review":
                    return "In-review"
                case "awarded_to_applicant":
                    return "Awarded to applicant";
                case "deleted":
                    return "Deleted";
                case "delivered":
                    return "Delivered";
            }
        },
        renderProjectPaymentType: function(type){
            switch(type.toString().toLowerCase()){
                case "hourly":
                    return "Hourly payment";
                case "hour_no_overpayment":
                    return "Hourly (no overpayment)";
                case "fixed":
                    return "Fixed payment";
                case "flexible":
                    return "Flexible payment";
                default:
                    return "N/A";
            }
        },
        renderProjectSkills: function(skills){
            let allSkills = "";

            skills.map(function(skill){
                allSkills += `<a class="project-preview-skill-item" data-skill-id="${skill.skill_id}">${skill.skill_name}</a>`;
            });

            return allSkills;
        },
        renderJobAttachments: function (data, options) {
            try{
                let fileList = '';
            
            for (let x in data) {
                let cdata = document.createElement("A"),
                    ft = Modules.getFileEXT(data[x].document_url);

                cdata.classList.add("file-attachment-item");
                cdata.classList.add("flex-item");
                cdata.classList.add("flex-item-centered");

                if (Modules.inArray(["zip"], ft)) {
                    cdata.setAttribute("href", data[x].document_url);
                    cdata.setAttribute("download", "download");
                }

                if (Modules.inArray(["jpeg", "jpg", "png"], ft)) {
                    cdata.setAttribute("data-src", data[x].document_url);
                    cdata.classList.add("mvamg");
                    cdata.classList.add("mriv-view-image-btn");
                }

                if (!Modules.inArray(["jpeg", "jpg", "png", "zip"], ft)) {
                    cdata.setAttribute("data-src", Modules.GOOGLE_DOCS_URL + "https://kwamelal.com/fakomame.docx" + "&embedded=true");
                    //cdata.setAttribute("data-src", Modules.GOOGLE_DOCS_URL + data[x] + "&embedded=true");
                    cdata.classList.add("preview-document-btn");
                }

                cdata.innerHTML = '<div>' + Modules.render.renderJobFileType(ft) + '<span>' + (Modules.inArray(["zip"], ft) ? 'Download' : 'Preview') + '</span><span class="desc-title">(' + (Modules.inArray(["jpeg", "jpg", "png"], ft) ? "PICTURE" : ft.toUpperCase()) + ')</span></div>';
                fileList += cdata.outerHTML;
            }

            return fileList;
            }
            catch(err){
                console.log(err);
            }
        },
        renderJobFileType: function (type) {
            switch (type) {
                case "jpg":
                case "jpeg":
                case "png":
                    return '<i class="las la-file-image"></i>';
                case "pdf":
                    return '<i class="las la-file-pdf"></i>';
                case "pptx":
                    return '<i class="las la-file-powerpoint"></i>';
                case "docx":
                case "doc":
                    return '<i class="las la-file-word"></i>';
                case "zip":
                    return '<i class="las la-file-archive"></i>';
                default:
                    return '<i class="las la-file"></i>';
            }
        },
        renderProjectDescription: function(data, options, callback){
            options.cardContainer.html(`<!-- user profile card -->
            <div class="user-profile-card flex-item">
                <div>
                    <img class="user-profile-view-image-item" src="${data.client_details.profile_photo_small_webp}" alt="${data.client_details.full_name}"/>
                </div>
                <div>
                    <div><a target="_blank"  href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.client_details.user_id}" class="link-default ${parseInt(data.client_details.is_verified) > 0 ? "verified-user mini" : ""}">${data.client_details.full_name}</a></div>
                    <div>@${data.client_details.user_name}</div>
                    <div><span class="special-indicator view-user-profile-btn maintain-opener" data-user-id="${data.client_details.id}">VIEW CLIENT PROFILE</span></div>
                    ${data.status == "in_review" ? 
                        `<div><br /></div>
                        <div>
                            <a data-target="#edit_job_modal" data-toggle="modal" data-project-id="${data.id}" style="cursor: pointer; color: #fff;" class="btn btn-brand btn-bold btn-sm cur pdrop-item get-project-edit-details-btn">
                                <i class="la la-edit"></i> Edit details
                            </a>
                        </div>` : ``
                    }
                    <br />
                    <div><b><a target="_blank" href="${__GLOBALS__.USER_PORTAL_DOMAIN + (data.project_type == "freelance" ? "freelance-jobs/apply/" : "artisan-jobs/apply/") + data.title_id}">Open in new tab</a></b></div>
                </div>
            </div>
            
            <!-- /user profile card -->`);

            options.container.html(`<div class="project-preview-division">
                <h3 class="project-preview-title">${data.title}</h3>
            </div>
            <div class="project-preview-division project-preview-division-two">
                <div><span class="project-preview-progress-status">Project has started</span></div>
                <div>
                    <span class="project-preview-category">${data.project_category}</span>
                    <span>&nbsp; &#0149; &nbsp;</span>
                    <span class="project-preview-datetime">Posted ${data.datetime_parsed}</span>
                </div>
                <div><span class="project-preview-status">${Modules.render.renderProjectStatus(data.status)}</span></div>
            </div>
            <div class="project-preview-division"><span class="project-simple-key">Specifics:</span> <span class="project-simple-value">${data.specifics}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Location:</span> <span class="project-simple-value">${data.location}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Payment type:</span> <span class="project-simple-value">${Modules.render.renderProjectPaymentType(data.payment_type)}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Status:</span> <span class="project-simple-value">${Modules.render.renderProjectStatus(data.status).toUpperCase()}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Files attached:</span> <span class="project-simple-value">${data.attachments.length}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Experience level:</span> <span class="project-simple-value">${data.expirience_level}</span></div>
            <div class="project-preview-division"><span class="project-simple-key">Last viewed by client:</span> <span class="project-simple-value">${data.last_viewed_by_client}</span></div>
            ${data.project_type = 'artisan' ? `<div class="project-preview-division"><span class="project-simple-key">Client availability:</span> <span class="project-simple-value">${data.datetime_available}</span></div>` : ``}
            <div class="project-preview-division"><span class="project-preview-price">${parseInt(data.maximum_payment) > 0 ? (__GLOBALS__.CURRENCY + data.minimum_payment + " - " + __GLOBALS__.CURRENCY + data.maximum_payment) : "Amount not stated"}</span></div>
            <div class="project-preview-division project-preview-description">${data.description}</div>
            <div class="project-preview-division">
                <div class="project-preview-simple-heading">Skills:</div>
                <div>${Modules.render.renderProjectSkills(data.skills)}</div>
            </div>
            <div class="project-preview-division">
                <div class="project-preview-simple-heading">Timeline:</div>
                <div>
                    <div><span class="project-simple-key">Expiry date:</span> <span class="project-simple-value">${data.expiry_date}</span></div>
                    <div><span class="project-simple-key">Start date:</span> <span class="project-simple-value">${data.duration_starting_date}</span></div>
                    <div><span class="project-simple-key">End date:</span> <span class="project-simple-value">${data.duration_ending_date}</span></div>
                </div>
            </div>
            <div class="project-preview-division">
                <div class="project-preview-simple-heading">Contact & Location:</div>
                <div>
                    <div><span class="project-simple-key">Phone number:</span> <span class="project-simple-value">${data.phone_number}</span></div>
                    <div><span class="project-simple-key">Email:</span> <span class="project-simple-value">${data.email}</span></div>
                    <div><span class="project-simple-key">Location:</span> <span class="project-simple-value">${(Modules.isEmpty(data.location) ? "N/A" : (data.location + ", " + data.country))}</span></div>
                    <div><span class="project-simple-key">GPS address:</span> <span class="project-simple-value">${(Modules.isEmpty(data.latitude) ? "N/A" : (data.latitude + ", " + data.longitude))}</span></div>
                </div>
            </div>
            <div class="project-preview-division">
                <div class="project-preview-simple-heading">${data.project_type == "artisan" ? "Artisan hired" : "Total number of applicants"}:</div>
                <div>
                    <div class="project-preview-description">${data.number_of_applicants + (parseInt(data.number_of_applicants) == 1 ? (data.project_type == "artisan" ? " artisan hired" : " applicant") : (data.project_type == "artisan" ? " artisans hired" : " applicants"))}</div>
                </div>
            </div>`);

            options.attachmentsContainer.html(Modules.render.renderJobAttachments(data.attachments));

            callback();
        },
        renderReviewStars: function (stars) {
            switch (parseInt(stars)) {
                case 1:
                    return '<span class="review-stars review-1-star"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
                case 2:
                    return '<span class="review-stars review-2-star"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
                case 3:
                    return '<span class="review-stars review-3-star"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
                case 4:
                    return '<span class="review-stars review-4-star"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
                case 5:
                    return '<span class="review-stars review-5-star"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
                default:
                    return '<span class="review-stars"><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i></span> &nbsp; ';
            }
        },
        renderApplicantContractColor: function (contract) {
            if (contract.response_status !== undefined && contract.response_status !== null) {
                switch (contract.response_status) {
                    case "accepted":
                        return ' style="background-color: #7fffd4;" title="' + contract.response_status + '" ';
                    case "pending":
                        return ' style="background-color: #fff;" title="' + contract.response_status + '" ';
                    case "declined":
                    case "cancelled":
                        return ' style="background-color: #fff;" title="' + contract.response_status + '" ';
                    default:
                        return ' style="background-color: #fff;" title="' + contract.response_status + '" ';
                }
            }
            return ' style="#fff" title="' + contract.response_status + '" ';
        },
        renderProjectApplicant: function(data, options, callback){
            data.map(function (applicant) {
                let cdata = document.createElement("DIV");
                cdata.classList.add("applicant-item");
                cdata.classList.add("project-applicant-item");
                cdata.classList.add("flex-item");
                cdata.setAttribute("data-id", applicant.user.id);

                cdata.innerHTML = '<!-- applicant -->' + 
                    '<div>' + 
                    '<img rel="nofollow" src="' + applicant.user.profile_photo_large_webp + '" alt=""/>' + 
                    '</div>' +
                    '<div>' +
                    '<div class="flex-item">' + 
                    '<div><a class="tdu" target="_profileUser" href="' + Modules.USER_PORTAL_DOMAIN + 'profile/' + applicant.user.user_id_raw + '?profile_state=viewing&profile_state_account_type=' + (options.project_type == "freelance" ? "freelancer" : "artisan") + '"><span class="' + (parseInt(applicant.user.is_verified) > 0 ? "verified-user mini levelled" : "") + '">' + applicant.user.full_name + '</span></a></div>' + 
                    '<div>' + applicant.datetime_parsed + '</div>' +
                    '<div>' + Modules.render.renderReviewStars(applicant.user.ratings) + '</div>' + 
                    '</div>' + 
                    '<div>' + 
                    ((parseInt(applicant.chosen_by_default) > 0 || applicant.is_invitation == 'yes') && parseInt(applicant.is_user_selected) <= 0 ? '<p>' + (parseInt(applicant.chosen_by_default) > 0 ? '<span class="appl-indicator cbd"><i class="las la-star"></i> chosen by default</span>' : applicant.is_invitation == 'yes' ? '<span class="appl-indicator"><i class="las la-envelope"></i> invited</span>' : '') + '</p>' : parseInt(applicant.is_user_selected) > 0 ? '<span class="appl-indicator cbd"><i class="las la-star"></i> awarded to this worker</span>' : '') + 
                    '<p><span class="desc-title">Charging:</span> <b>' + (Modules.CURRENCY + applicant.charge) + '</b></p>' + 
                    '<p>' + (Modules.trim(applicant.cover_letter) !== "" ? applicant.cover_letter : "No cover letter written") + '</p>' + 
                    '<p><b class="-cursor-pointer- inb view-user-profile-btn maintain-opener" data-user-id="' + applicant.user.id + '"><u>See profile</u></b></p>' + 
                    '</div>' + 
                    '<div class="flex-item flex-item-justify-content-space-between flex-item-align-items-center">' +
                    (options.project_type == "freelance" ? '<div><div class="sbs-btn flex-item-important flex-item-align-items-center"><button class="view-worker-contract load-contracts-between-client-worker-btn initial" data-owner="' + applicant.is_owner + '" data-project-status="' + options.project_status + '" data-user-id="' + applicant.user.id + '" data-user-name="' + applicant.user.full_name + '" data-project-id="' + options.project_id + '" data-payment-type="' + options.payment_type + '" data-payment-price="' + options.payment_price + '" data-worker-price="' + Modules.CURRENCY + applicant.charge + '" data-worker-price-actual="' + applicant.charge + '" data-worker-id="' + applicant.user.id + '" data-client-id="' + applicant.owner_id + '" data-number-of-contracts="' + applicant.number_of_contracts + '" data-project-id="' + applicant.project_id + '" data-subtitle="Between you and ' + (applicant.user.full_name.length > 20 ? applicant.user.full_name.substring(0, 17) + "..." : applicant.user.full_name) + '" data-worker-heading="Applicant" data-client-heading="Your response" ' + Modules.render.renderApplicantContractColor(applicant.last_contract) + '>' + (options.project_status == 'accepting_applicants' ? 'All' : 'Contracts') + ' (<span class="worker-contract-count">' + applicant.number_of_contracts + '</span>)</button><button class="custom-a-link" data-target="_profileUser" data-href="' + __GLOBALS__.USER_PORTAL_DOMAIN + 'profile/' + applicant.user.user_id_raw + '?profile_state=viewing&profile_state_account_type=' + (options.project_type == "freelance" ? "freelancer" : "artisan") + '"> <i class="las la-user"></i></button></div></div>' : '<div><div class="sbs-btn flex-item-important flex-item-align-items-center"><button class="custom-a-link" data-target="_profileUser" data-href="' + __GLOBALS__.USER_PORTAL_DOMAIN + 'profile/' + applicant.user.user_id_raw + '?profile_state=viewing&profile_state_account_type=' + (options.project_type == "freelance" ? "freelancer" : "artisan") + '"> <i class="las la-user"></i></button></div></div>') + 
                    '</div>' + 
                    '</div>' + 
                    '<!-- applicant -->';
                
                options.container.append(cdata);
            });
            callback();
        },
        renderProjectInvoiceList: function(data, options, callback){
            data.map(function (item) {
                options.container.append(`<!-- Invoice item -->
                <div class="project-preview-invoice-item clickable-project-preview-invoice-item flex-item" data-invoice-id="${item.id}">
                    <div>
                        <span>${item.invoice_number}</span>
                    </div>
                    <div>
                        <span>${item.date_issued}</span>
                    </div>
                    <div>
                        <span>${item.charge}</span>
                    </div>
                    <div>
                        <span class="project-preview-invoice-item-status">${item.status.toUpperCase()}</span>
                    </div>
                </div>
                <!-- /Invoice item -->`);
            });
            callback();
        },
        renderProjectSharedFiles: function(data, options, callback){
            data.map(function (item) {
                const file = document.createElement("DIV");
                file.setAttribute("data-id", item.id);
                file.setAttribute("class", "shared-file-item " + (options.class) + " project-shared-file-item flex-item");
                file.innerHTML = `<div>
                                        <i class="las la-file"></i>
                                    </div>
                                    <div>
                                        <p class="shared-file-name">${item.actual_attached_document_name}<br /><span class="dashboard-grey">${item.description}</span></p>
                                        <p class="shared-file-date-size"><small>${Modules.convertSize(item.attached_document_size)} &nbsp;&#0149;&nbsp; uploaded ${item.datetime}</small></p>
                                        <p><a class="shared-file-download-file" href="${item.document_url}" download="${item.actual_attached_document_name}"><i class="las la-download"></i> Download file</a></p>
                                    </div>
                                    <div></div>
                                    </div>`;
                options.container.append(file);
            });
            callback();
        },
        renderProjectWorkerClientContractList: function(data, options, callback){
            data.map(function (item) {
                options.container.append(`<!-- contract list item -->
                <div class="preview-component-list-item clickable-preview-component-list-item flex-item" data-contract-id="${item.id}" data-user-id="${item.user_id}">
                    <div>
                        <span>${item.response_status.toString().capitalize()}</span>
                    </div>
                    <div>
                        <span>${item.client_response_status.toString().capitalize()}</span>
                    </div>
                    <div>
                        <span>${Modules.CURRENCY + item.contract_charge}</span>
                    </div>
                    <div>
                        <span>${item.expiration_date}</span>
                    </div>
                </div>
                <!-- contract list item -->`);
            });
            callback();
        },
        renderProjectWorkerClientContractDetails: function(data, options, callback){
            options.container.html(`<!-- contract list item details -->
            <div class="preview-component-list-item-details">

                <div class="preview-component-header flex-item flex-item-justify-content-space-between">
                    <div>
                        <div>CONTRACT STATUS</div>
                        <div>${data.contract_status.capitalize()}</div>
                    </div>
                    <div>
                        <div>DUE DATE</div>
                        <div>${data.expiration_date}</div>
                    </div>
                    <div>
                        <div>AMOUNT</div>
                        <div>${Modules.CURRENCY + data.contract_charge}</div>
                    </div>
                </div>

                <div class="preview-component-simple-key-item">
                    <div>Time sent</div>
                    <div>${data.datetime_parsed}</div>
                </div>

                <div class="preview-component-simple-key-item">
                    <div>Client's clause</div>
                    <div>${data.clause}</div>
                </div>

                <div class="preview-component-simple-key-item">
                    <div>Client's contract details</div>
                    <div>${data.contract_description}</div>
                </div>

                <div class="preview-component-simple-key-item">
                    <div>Applicant's response</div>
                    <div>${data.response_description}</div>
                </div>

            </div>
            <!-- /contract list item details -->`);
            callback();
        },
        renderProjectInvoiceListDetails: function(data, options, callback){
            try{
                options.container.html( 
                `
                ${data.status == "paid" && parseInt(data.payment_transfer_status) > 0 ? '<div class="preview-invoice-transfer-status"><i class="las la-check"></i>&nbsp; Transferred to worker</div>' : ''}
                
                ${
                    data.status.toLowerCase() == "paid" ? `<div class="invoice-payment-status-container flex-item flex-item-align-items-center">
                        <div><i class="las la-credit-card"></i>&nbsp;<i class="las la-check"></i></div>
                        <div>Payment fully made on ${data.date_paid}</div>
                    </div>` : ``
                }
    
                <div class="invoice-payment-reference">
                    Reference ID: <b>#${data.invoice_number}</b>
                </div>
    
                <div class="invoice-payment-dates flex-item flex-item-align-items-center flex-item-justify-content-space-between">
                    <div>
                        <div>Issued on</div>
                        <div>${data.date_issued}</div>
                    </div>
                    <div>
                        <div>Due on</div>
                        <div>${data.date_due}</div>
                    </div>
                </div>
    
                <div class="invoice-payment-info">
                    <div>Invoice for</div>
                    <div>Client's name: ${data.client_name}</div>
                    <div>Title: ${data.project_title}</div>
                </div>
    
                <div class="invoice-payment-info-two">
                    <div>Billing summary</div>
                    <div class="invoice-payment-info-table">
                        <div class="flex-item flex-item-justify-content-space-between">
                            <div><h4>DESCRIPTION</h4></div>
                            <div><h4>AMOUNT (${Modules.CURRENCY})</h4></div>
                        </div>
                        <div class="flex-item flex-item-justify-content-space-between">
                            <div>Final payment</div>
                            <div>${data.charge}</h4></div>
                        </div>
                        <div class="flex-item flex-item-justify-content-space-between">
                            <div><h4>TOTAL</h4></div>
                            <div><h4>${Modules.CURRENCY + data.total_charge}</h4></div>
                        </div>
                    </div>
                </div>`);
                callback();
            }
            catch(err){
                console.log(err);
            }
        },
        renderProjectMessages: function(data, options, callback){
            data.reverse().map(function (item) {
                options.container.append(`<!-- project message item -->
                <div class="project-message-item ${options.class} ${item.owner ? 'project-message-item-two' : ''} flex-item">
                    <div class="flex-item">
                        <div>
                            <img src="${item.user.profile_photo_small_webp}"/>
                        </div>
                        <div>
                            <div><a class="${parseInt(item.user.is_verified) > 0 ? 'verified-user mini levelled' : ''}">${item.user.full_name}</a></div>
                            <div>${item.message}</div>
                            <div>${item.datetime}</div>
                        </div>
                    </div>
                </div>
                <!-- project message item -->`);
            });
            callback();
        },
        renderServiceStatus: function(status){
            switch(status.toString().toLowerCase()){
                case "active":
                    return "ACTIVE";
                case "cancelled":
                    return "CANCELLED";
                case "in_review":
                    return "IN-REVIEW";
            }
        },
        renderServicePhotos: function(data){
            let photos = '';
            data.map(function(photo){
                photos += `<!-- Portfolio item -->
                <div class="portfolio-item service-portfolio-item mriv-view-image-btn mvamg" data-photo-id="hRE-" data-src="${photo.photo_webp}">
                    <div>
                        <img src="${photo.photo_small_webp}" alt="">
                    </div>
                </div>
                <!-- Portfolio item -->`;
            });
            return photos;
        },
        renderServiceFaqs: function(data){
            let faqs = '';
            data.map(function(faq){
                faqs += `<!-- service faq item -->
                <div class="preview-faq-item">
                    <div>
                        <h4>${faq.question}</h4>
                    </div>
                    <div>
                        <p>${faq.answer}</p>
                    </div>
                </div>
                <!-- /service faq item -->`;
            });
            return faqs;
        },
        renderCustomPackageFields: function(custom_package_fields){
            let fields = "";
            custom_package_fields.map(function (field) {
                fields += `<tr>
                                <td>${field.title}</td>
                                <td>${field.basic}</td>
                                <td>${field.standard}</td>
                                <td>${field.premium}</td>
                            </tr>`;
            });
            return fields;
        },
        renderMarketServiceFeatures: function(features){
            let feat = [];
            features.map(function(f){
                feat.push(f.feature_name);
            });
            return feat.join(", ");
        },
        renderServiceItemDescription: function(data, options, callback){
            options.reviewContainer.html(``);
            Modules.render.renderReviews(data.reviews, {
                container: options.reviewContainer,
                class: "service-review-item"
            }, function(){
                if(data.reviews.length === 0 && $(".service-review-item").length === 0){
                    options.reviewContainer.html(`<h6 class="no-data-indicator">No reviews available</h6>`);
                }
                else{
                    options.reviewContainer.find(".no-data-indicator").remove();
                }

                if($(".load-service-reviews-btn").hasClass("single-load")){
                    $(".load-service-reviews-btn").attr("data-loaded", "yes");
                }
            });

            options.portfolioContainer.html(Modules.render.renderServicePhotos(data.photos));
            
            options.faqContainer.html(Modules.render.renderServiceFaqs(data.faqs));

            options.cardContainer.html(`<!-- user profile card -->
            <div class="user-profile-card flex-item">
                <div>
                    <img class="user-profile-view-image-item" src="${data.user.profile_photo_small_webp}" alt="${data.user.full_name}"/>
                </div>
                <div>
                    <div><a target="_blank"  href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.user.user_id}" class="link-default ${parseInt(data.user.is_verified) > 0 ? "verified-user mini" : ""}">${data.user.full_name}</a></div>
                    <div>@${data.user.user_name}</div>
                    <div><span class="special-indicator view-user-profile-btn maintain-opener" data-user-id="${data.user.id}">VIEW SELLER PROFILE</span></div>
                    <div><br /></div>
                    <div>
                        <a data-service-id="${data.id}" data-toggle="modal" data-target="#edit_service_modal" style="cursor: pointer; color: #fff; background-color: #e74032; border-color: #e74032;" class="btn btn-brand btn-bold btn-sm cur get-service-edit-details-btn">
                            <i class="la la-edit"></i> Edit service
                        </a>
                    </div>
                    <br />
                    <div><b><a target="_blank" href="${__GLOBALS__.USER_PORTAL_DOMAIN + "services/product/" + data.title_id}">Open in new tab</a></b></div>
                </div>
            </div>
            <!-- /user profile card -->`);

            options.container.html(`<div class="service-preview-title"><h4>${data.title}</h4></div>
            <div class="service-preview-date">
                <p>Posted ${data.datetime}</p>
            </div>
            <div class="service-preview-status">
                <p>Status: <b>${Modules.render.renderServiceStatus(data.status)}</b></p>
            </div>
            <div class="service-preview-basic-details">
                <p>
                    <span>${data.total_orders + (parseInt(data.total_orders) == 1 ? " order" : " orders")}</span>
                    <span>&nbsp; &#0149; &nbsp;</span>
                    <span>${data.orders_in_queue + (parseInt(data.orders_in_queue) == 1 ? " order" : " orders")} in queue</span>
                </p>
            </div>
            <div class="service-preview-skill-category">
                <p>
                    <span>${data.service_category}</span>
                    <span><i class="fas fa-caret-right"></i></span>
                    <span>${data.skills[0].skill_name}</span>
                </p>
                <p><b>Features:</b> <span class="service-preview-skill-features">${Modules.render.renderMarketServiceFeatures(data.features)}</span></p>
            </div>
            <div class="service-preview-description">
                <p>${data.description}</p>
            </div>
            <div class="service-preview-package-header"><h4>PACKAGES</h4></div>
            <div class="service-preview-package-details">

                <!-- package description table -->
                <div class="service-package-description-table">
                    <table>
                        <tr>
                            <th></th>
                            <th><b>Basic</b></th>
                            <th><b>Standard</b></th>
                            <th><b>Advanced</b></th>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>${data.packages[0].title}</td>
                            <td>${data.packages[1].title}</td>
                            <td>${data.packages[2].title}</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>${data.packages[0].description}</td>
                            <td>${data.packages[1].description}</td>
                            <td>${data.packages[2].description}</td>
                        </tr>
                        <tr>
                            <td>Delivery Time</td>
                            <td>${data.packages[0].duration + (parseInt(data.packages[0].duration) == 1 ? " day" : " days")}</td>
                            <td>${data.packages[1].duration + (parseInt(data.packages[1].duration) == 1 ? " day" : " days")}</td>
                            <td>${data.packages[2].duration + (parseInt(data.packages[2].duration) == 1 ? " day" : " days")}</td>
                        </tr>
                        <tr>
                            <td>Price(${Modules.CURRENCY})</td>
                            <td>${data.packages[0].price}</td>
                            <td>${data.packages[1].price}</td>
                            <td>${data.packages[2].price}</td>
                        </tr>
                        <tr>
                            <td>Revisions</td>
                            <td>${data.packages[0].revisions}</td>
                            <td>${data.packages[1].revisions}</td>
                            <td>${data.packages[2].revisions}</td>
                        </tr>
                        ${Modules.render.renderCustomPackageFields(data.custom_package_fields)}
                    </table>
                </div>
                <!-- /package description table -->

            </div>`);
            callback();
        },
        renderPurchaseItemRequirements: function(data){
            let requirements = "";
            data.map(function(req){
                if(req.item_type == "text"){
                    requirements += `<div class="preview-faq-item">
                                        <div>
                                            <h4>${req.item_data}</h4>
                                        </div>
                                        <div>
                                            <p>${req.item_value}</p>
                                        </div>
                                    </div>`;
                }
                else{
                    requirements += `<div class="preview-faq-item">
                                        <div>
                                            <h4>${req.item_data}</h4>
                                        </div>
                                        <div>
                                            <p><a href="${req.download_link}" download="${req.item_value}">Download file</a></p>
                                        </div>
                                    </div>`;
                }
            });
            return requirements;
        },
        renderPurchaseItemDetails: function(data, options, callback){
            options.cardContainer.html(`
                <!-- user profile card -->
                <div class="user-profile-card flex-item flex-item-align-items-center">
                    <div>
                        <img class="user-profile-view-image-item" src="${data.client_details.profile_photo_small_webp}" alt="${data.full_name}"/>
                    </div>
                    <div>
                        <div><a target="_blank"  href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.client_details.user_id}" class="link-default ${parseInt(data.client_details.is_verified) > 0 ? "verified-user mini" : ""}">${data.client_details.full_name}</a></div>
                        <div>@${data.client_details.user_name}</div>
                        <div><span class="special-indicator view-user-profile-btn maintain-opener" data-user-id="${data.client_details.id}">VIEW CLIENT PROFILE</span></div>
                    </div>
                </div>
                <!-- /user profile card -->
                <br />
                <!-- user profile card -->
                <div class="user-profile-card flex-item flex-item-align-items-center">
                    <div>
                        <img class="user-profile-view-image-item" src="${data.seller_details.profile_photo_small_webp}" alt="${data.seller_details.full_name}"/>
                    </div>
                    <div>
                        <div><a target="_blank"  href="${__GLOBALS__.USER_PORTAL_DOMAIN + "profile/" + data.seller_details.user_id}" class="link-default ${parseInt(data.seller_details.is_verified) > 0 ? "verified-user mini" : ""}">${data.seller_details.full_name}</a></div>
                        <div>@${data.seller_details.user_name}</div>
                        <div><span class="special-indicator view-user-profile-btn maintain-opener" data-user-id="${data.seller_details.id}">VIEW SELLER PROFILE</span></div>
                    </div>
                </div>
                <!-- /user profile card -->
            `);

            options.requirementsContainer.html(Modules.render.renderPurchaseItemRequirements(data.order_requirements));

            options.reviewContainer.html(``);
            Modules.render.renderReviews(data.review, {
                container: options.reviewContainer,
                class: "purchase-review-item"
            }, function(){
                
            });

            options.container.html(`
            <!-- head -->
            <div class="basic-details-header-container">
                <h4>${data.main_title}</h4>
                <div>${data.main_description}</div>
            </div>
            <!-- /head -->

            <!-- basic details container -->
            <div class="basic-details-container wide">
                
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Date purchased</div>
                    <div>${data.datetime_parsed}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Order ID</div>
                    <div>${data.order_id}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Service Item Id</div>
                    <div>${data.market_item_special_id}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Package Type</div>
                    <div>${data.package_type.toUpperCase()}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Duration</div>
                    <div>${parseInt(data.duration) == 1 ? data.duration + " day" : data.duration + " days"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Requirements</div>
                    <div>${parseInt(data.is_requirement_submitted) > 0 ? "Submitted" : "Pending"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Quantity</div>
                    <div>${data.quantity}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Revision</div>
                    <div>${data.revisions}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Total amount</div>
                    <div>${Modules.CURRENCY + data.total_amount}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Amount</div>
                    <div>${Modules.CURRENCY + data.package_price}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Service fee</div>
                    <div>${Modules.CURRENCY + data.service_fee}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Started</div>
                    <div>${parseInt(data.has_order_started) > 0 ? "YES" : "NO"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Ended</div>
                    <div>${parseInt(data.has_order_ended) > 0 ? "YES" : "NO"}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Shared files</div>
                    <div>${data.number_of_files_shared}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Messages</div>
                    <div>${data.number_of_messages}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Agreement status</div>
                    <div>${data.order_agreement_status}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Refundable</div>
                    <div>${data.refundable}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Freelancer refund amount</div>
                    <div>${Modules.CURRENCY + data.worker_refund_amount}</div>
                </div>
                <!-- /basic details container item -->
                <!-- basic details container item -->
                <div class="basic-details-container-item flex-item flex-item-justify-content-space-between">
                    <div>Client refund amount</div>
                    <div>${Modules.CURRENCY + data.client_refund_amount}</div>
                </div>
                <!-- /basic details container item -->
                `);

            callback();
        },
        renderAdminDashboardNotifications: function(data, options){
            const totalNotifications = parseInt(data.artisan_jobs) + parseInt(data.freelance_jobs) + parseInt(data.services) + parseInt(data.verifications) + parseInt(data.reports) + parseInt(data.direct_messages) + parseInt(data.withdrawal_requests);
            options.artisanJobsNotification.text(parseInt(data.artisan_jobs) >= 10 ? "9+" : data.artisan_jobs);
            options.freelanceJobsNotification.text(parseInt(data.freelance_jobs) >= 10 ? "9+" : data.freelance_jobs);
            options.serviceNotification.text(parseInt(data.services) >= 10 ? "9+" : data.services);
            options.verificationNotification.text(parseInt(data.verifications) >= 10 ? "9+" : data.verifications);
            options.reportNotification.text(parseInt(data.reports) >= 10 ? "9+" : data.reports);
            options.directMessagesNotification.text(parseInt(data.direct_messages) >= 10 ? "9+" : data.direct_messages);
            options.withdrawalNotifications.text(parseInt(data.withdrawal_requests) >= 10 ? "9+" : data.withdrawal_requests);
            options.totalNotifications.text(parseInt(totalNotifications) >= 10 ? "9+" : totalNotifications);
            if(totalNotifications < 1){
                options.totalNotifications.addClass("inactive");
            }
            else{
                options.totalNotifications.removeClass("inactive");
            }

            document.title = (totalNotifications > 0) ? "(" + totalNotifications + ") " + Modules.PAGE_TITLE : Modules.PAGE_TITLE;
        },
        renderSkillsFeatures: function(data, options){
            data.map(function(feature){
                options.container.append(`<div class="col-sm-6">
                    <div class="row mb-2">
                        <div class="col-sm-1"><input style="vertical-align: middle;" ${Modules.inArray(options.selected, feature.id) ? "checked" : ""} data-feature-id="${feature.id}" type="checkbox" class="cur ${options.class}"/></div>
                        <div class="col-sm-11">${feature.feature_name}</div>
                    </div>
                </div>`);
            });
        },
        renderManageSkillFeatureItem: function(data, options, callback){
            data.map(function(feature){
                options.container.prepend(`<!-- feature item -->
                <div class="row mb-3 manage-skill-feature-item" data-id="${feature.id}" data-hidden="${feature.is_hidden}">
                    <div class="col-sm-9"><input type="text" value="${feature.feature_name}" class="form-control manage-skill-feature-item-feature-name-input" style="width: 100%; display: block;"/></div>
                    <div class="col-sm-3" style="text-align: right;">
                        <i title="${parseInt(feature.is_hidden) > 0 ? 'Show' : 'Hide'}" class="las ${parseInt(feature.is_hidden) > 0 ? 'la-eye' : 'la-eye-slash'} skill-feature-hide-btn"></i>
                        ${feature.is_new === true ? `<i class="las la-times skill-feature-remove-btn"></i>` : ``}
                    </div>
                </div>
                <!-- feature item -->`);
            });

            callback();
        },
        renderOtherArtisans: function(data, options, callback){
            data.map(function(dataItem){
                options.container.append(`<!-- Artisan item -->
                <div class="row other-artisan-item">
                    <div class="col-sm-9">
                        <div class="flex-item other-artisan-item-content">
                            <div>
                                <img class="other-artisan-image" src="${dataItem.profile_photo_small_webp}"/>
                            </div>
                            <div>
                                <div><a class="cur ${parseInt(dataItem.is_verified) > 0 ? " verified-user mini levelled " : ""}" data-user-id="${dataItem.id}">${dataItem.full_name}</a></div>
                                <div><span>@${dataItem.user_name}</span></div>
                                <div><span>${dataItem.email_address}</span> &#0149; <span>${dataItem.mobile_number_country_number + dataItem.mobile_number}</span></div>
                                <div><span>${dataItem.artisan_jobs_done} ${parseInt(dataItem.artisan_jobs_done) == 1 ? " job" : " jobs"}</span> &#0149; <span>${dataItem.artisan_jobs_ongoing} ${parseInt(dataItem.artisan_jobs_ongoing) == 1 ? " job ongoing" : " jobs ongoing"}</span> &#0149; <span>${dataItem.reviews_received} ${parseInt(dataItem.reviews_received) == 1 ? " review" : " reviews"}</span></div>
                                <div><span><b>Location: </b>${dataItem.last_location}</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3" style="text-align: right;">
                        <button type="button" class="btn btn-success btn-sm select-other-artisan-btn cur will-load" data-id="${dataItem.id}" data-name="${dataItem.full_name}" style="background-color: #fe2c52; border: 1px solid #fe2c52;"><span class="loading-spinner white min"></span> Select</button>
                    </div>
                </div>
                <!-- Artisan item -->`);
            });

            callback();
        },
        renderSearchUserItem: function(data, options, callback){
            data.map(function(dataItem){
                options.container.append(`<div class="general-search-container-results-item general-search-container-results-item-adder ${options.class} cur" data-container="${options.searchContainer}" data-selected-container="${options.selectedContainer}" data-id="${dataItem.id}" data-name="${dataItem.full_name}" data-email="${dataItem.email_address}" data-mobile-number="${dataItem.phone_number}"><div class="general-search-container-results-item-image"><img src="${dataItem.profile_photo_small_webp}"></div><div class="general-search-container-results-item-content"><div>${dataItem.full_name}</div><div><small>${dataItem.email_address} &nbsp; • &nbsp; ${dataItem.phone_number}</small></div></div><div class="general-search-container-results-item-icon"><i class="la la-plus"></i></div></div>`);
            });
            callback();
        },
        renderWarningItems: function(data, options, callback){
            data.map(function(dataItem){
                options.container.append(`<!-- begin:: warning list item -->
                <div class="row warning-list-container-item">
                    <div class="col-sm-12" style="border-bottom: 1px solid #dbdbdb; padding-bottom: 10px; padding-top: 10px; max-width: 96%; margin: auto;">
                        <label class="mb-1" style="font-weight: 500;"><b>${dataItem.type}</b></label>
                        <p class="mb-1" style="font-weight: 500;">${dataItem.text}</p>
                        <p class="mb-1" style="font-weight: 500; color: #828282;">${dataItem.datetime_added}</p>
                    </div>
                </div>
                <!-- end:: warning list item -->`);
            });
            callback();
        },
        renderRecipientItem: function(data, options){
            options.container.append(`<span class="recipient-item" data-id="${data.id}" data-name="${data.name}" data-email="${data.email}">
				<span>${data.name}</span>
				<span><i class="las la-times remove-recipient-item"></i></span>
			</span>`);
        }
    }
};

Modules.__INIT__();