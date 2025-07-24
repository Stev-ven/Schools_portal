let isTyping = null;
let mediaItemToRemove = null;
let mediaItemToReplace = null;
let mediaItemToReplacePosition = null;
let mediaItemToMove = null;
const textEditor = document.getElementsByClassName("simple-email-editor")[0];
const textEditorJQ = $(".simple-email-editor");

function linkRaw(t) {
    var txt = document.getSelection();

    if (Modules.isEmpty(txt)) {
        Modules.toggleToastContainer({
            message: "Please select a text in the editor first."
        });
        return;
    }

    var url = prompt("Enter the URL");
    document.execCommand("insertHTML", false, '<a style="color: inherit;" href="' + url + '" target="' + t + '">' + txt + '</a>');
}

function link() {
    textEditor.classList.remove("pholder");
    var txt = document.getSelection();

    if (Modules.isEmpty(txt)) {
        Modules.toggleToastContainer({
            message: "Please select a text in the editor first."
        });
        return;
    }

    var url = prompt("Enter the URL");
    document.execCommand("insertHTML", false, '<a style="color: #fe2c52;" href="' + url + '" target="_blank">' + txt + '</a>');
}

function addExternalImage() {
    const imageLink = prompt("Enter image URL");
    if (!Modules.isEmpty(imageLink)) {
        CustomPhotoProcessor.addPhotos(imageLink, null, function (data) {
            if (typeof data === "object") {
                switch (data.status) {
                    case "_OK":
                        Modules.togglePageLoader(true);
                        let image = new Image();
                        image.onload = function (e) {
                            $("#email-sender-body").removeClass("pholder");
                            $("#email-sender-body").append(`<div style="margin-top: 25px; margin-bottom: 25px; text-align: center;"><center><img style="max-width: 100%;" src="${imageLink}" rel="nofollow"/></center></div>`);
                        };
                        image.onerror = function () {
                            Modules.togglePageLoader(false);
                            Modules.toggleToastContainer({
                                message: "Failed to load image.",
                                status: Modules.status.FAILED
                            });
                        };
                        image.src = data.image;
                        break;
                    case "_FAILED":
                        Modules.togglePageLoader(false);
                        Modules.toggleToastContainer({
                            message: data.statusText,
                            status: Modules.status.FAILED
                        });
                        break;
                }
            }
            else {
                Modules.togglePageLoader(false);
                Modules.toggleToastContainer({
                    message: Modules.status.UNKNOWN_ERROR,
                    status: Modules.status.FAILED
                });
            }
        }, {
            maxWidthAspectRatio: 1920,
            maxHeightAspectRatio: 1080,
        });
        return;
    }

    Modules.togglePageLoader(false);
    Modules.toggleToastContainer({
        message: "The URL is invalid."
    });
}

function quote() {
    textEditor.classList.remove("pholder");
    let quote = document.createElement("div");
    let newLine = document.createElement("div");
    let blockquote = document.createElement("blockquote");
    quote.setAttribute("class", "blog-quote ctrl-cur");
    blockquote.setAttribute("class", "ctrl-cur bq");
    newLine.textContent = "...";
    blockquote.textContent = "...";
    quote.appendChild(blockquote);
    document.querySelector(".simple-email-editor").appendChild(quote);
    document.querySelector(".simple-email-editor").appendChild(newLine);
    goBottom();
}

function font() {
    textEditor.classList.remove("pholder");
    var txt = document.getSelection();

    if (Modules.isEmpty(txt)) {
        Modules.toggleToastContainer({
            message: "Please select a text in the editor first."
        });
        return;
    }

    var f = prompt("Enter font size. eg 1, 2, 3, 4 ...");
    document.execCommand("fontSize", false, f);
}

function addHeader() {
    textEditor.classList.remove("pholder");
    const h2 = document.createElement("h3");
    h2.textContent = "...";
    document.querySelector(".simple-email-editor").appendChild(h2);
    goBottom();
}

function newLine() {
    textEditor.classList.remove("pholder");
    const d = document.createElement("div");
    d.innerHTML = "...";
    d.classList.add("new-line");
    document.querySelector(".simple-email-editor").appendChild(d);
    goBottom();
}

function list(t) {
    textEditor.classList.remove("pholder");
    var nli = prompt("How many list items do you want?");

    if ($.isNumeric(nli) == false) {
        Modules.toggleToastContainer({
            message: "Please input a valid figure."
        });
        return;
    }

    const l = t == "UL" ? document.createElement("ul") : document.createElement("ol");
    l.classList.add("exc");
    for (var i = 0; i < nli; i++) {
        const li = document.createElement("li");
        li.textContent = "... " + i;
        li.classList.add("exc");
        l.appendChild(li);
    }
    document.querySelector(".simple-email-editor").appendChild(l);
    goBottom();
}

function copy() {
    textEditor.classList.remove("pholder");
    document.execCommand("copy", false, "");
}

function changeColor() {
    textEditor.classList.remove("pholder");
    var color = prompt("Enter your color in hex example: #f1f233");
    document.execCommand("foreColor", false, color);
}

function getSelectionStartElAct() {
    var node = document.getSelection().anchorNode;
    return (node.nodeType == 3 ? node.parentNode : node);
}

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();


            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(),
                node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    }
    else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();


            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(),
                node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    }
    else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

function getCaretPosition(editableDiv) {
    try {
        var caretPos = 0, sel, range;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.rangeCount) {
                range = sel.getRangeAt(0);
                if (range.commonAncestorContainer.parentNode == editableDiv) {
                    caretPos = range.endOffset;
                }
            }
        } else if (document.selection && document.selection.createRange) {
            range = document.selection.createRange();
            if (range.parentElement() == editableDiv) {
                var tempEl = document.createElement("span");
                editableDiv.insertBefore(tempEl, editableDiv.firstChild);
                var tempRange = range.duplicate();
                tempRange.moveToElementText(tempEl);
                tempRange.setEndPoint("EndToEnd", range);
                caretPos = tempRange.text.length;
            }
        }
        return caretPos;
    }
    catch (err) {
        //err
    }
}

function restorePlaceHolder() {
    const editorContainer = $(".simple-email-editor");

    if (editorContainer.find("br").length > 0 && editorContainer.text().replace(/^\s+/i, "").replace(/\s+$/i, "").length < 1) {
        editorContainer.empty();
        editorContainer.addClass("pholder");
        return;
    }

    if (editorContainer.text().replace(/^\s+/i, "").replace(/\s+$/i, "").length > 0 || editorContainer.html().replace(/^\s+/i, "").replace(/\s+$/i, "") !== "") {
        editorContainer.removeClass("pholder");
    }
    else {
        editorContainer.addClass("pholder");
    }
}

function goBottom() {
    setTimeout(function () {
        textEditor.scrollTop = textEditor.scrollHeight * 2;
    }, 250);
}

function clearEditor() {
    textEditor.innerHTML = "";
    textEditor.classList.add("pholder");
    textEditor.focus();
}

function clearEverything() {
    textEditor.innerHTML = "";
    textEditor.classList.add("pholder");
    document.querySelector("#blog-post-category").value = "";
    document.querySelector("#blog-post-title").value = "";
    document.querySelector("#blog-post-headline").value = "";
    document.querySelector("#enter-tag-input").value = "";
    document.querySelector(".tag-list-container").innerHTML = "";
}

function removeMediaItem() {
    if (mediaItemToRemove !== null) {
        mediaItemToRemove.remove();
        mediaItemToRemove = null;
    }
}

function emptyMediaItemToRemove() {
    mediaItemToRemove = null;
}

function wrapAroundTag(elem){
    $(elem).contents().filter(function() { return this.nodeType === 3 && $.trim(this.textContent).length }).wrap('</p>');
}

function removeEmptyTags(elem){
    elem.querySelectorAll("div, p").forEach(function(item){
        if(Modules.isEmpty(Modules.trim(item.textContent))){
            item.parentElement.removeChild(item);
        }
    });
}

function stripTags(html)
{
   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function addPhotoToBlog() {
    mediaItemToReplace = null;
    mediaItemToRemove = null;
    mediaItemToReplacePosition = null;
    mediaItemToMove = null;
    $(".blog-photo-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
    $(".blog-media-photo-container").html('');
    $("#blog-media-link, #blog-media-selector, .blog-media-upload-container-photo-caption").val("");
    $(".blog-photo-modal-container").addClass("active");
    $(".blog-photo-modal-container").scrollTop(0);
}

$(window).load(function () {

    $(document).on("click", ".blog-left-menu-toggle-btn", function () {
        let leftMenu = $(".blog-writer-main-content");
        if (leftMenu.hasClass("hidden-left-menu-mode")) {
            $(this).removeClass("hidden-left-menu-mode");
            leftMenu.removeClass("hidden-left-menu-mode");
        }
        else {
            $(this).addClass("hidden-left-menu-mode");
            leftMenu.addClass("hidden-left-menu-mode");
        }
    });

    $(document).on("click", ".clear-editor-btn", function () {
        callAlert({
            body: 'Are you sure you want to clear every content you have written? It cannot be undone once it\'s cleared.',
            button: '<button class="focused-caution" onclick="clearEditor();">Clear</button>',
            otext: 'No'
        });
    });

    $(document).on("keyup", "#enter-tag-input", function (e) {
        let tag = $(this).val(), exists = false;
        if (e.keyCode === 13) {
            if (!Modules.isEmpty(tag)) {
                document.querySelectorAll(".blog-tag-input").forEach(function(t){
                    if(t.value == tag){
                        exists = true;
                    }
                });

                if(exists){
                    Modules.toggleToastContainer({
                        message: "This tag is already in the list.",
                        status: Modules.status.FAILED
                    });
                    return;
                }

                $(".tag-list-container").append('<span class="blog-tag"><span>' + tag + '</span><span><i class="las la-times remove-tag-btn"></i></span><input class="blog-tag-input" type="hidden" value="' + tag + '"/></span>');
                $(this).val("");
                $(this).focus();
            }
        }
    });

    $(document).on("keyup keydown", ".blockquote", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $(document).on("click", ".remove-tag-btn", function () {
        $(this).closest(".blog-tag").remove();
    });

    $(document).on("click", ".remove-blog-image-btn", function () {
        mediaItemToRemove = $(this).closest("figure");
        callAlert({
            body: 'Are you sure you want to remove this item?',
            button: '<button class="focused" onclick="removeMediaItem();">Yes</button>',
            otext: 'No'
        });
    });

    $(document).on("click", ".remove-post-btn", function () {
        mediaItemToRemove = $(this).closest("figure");
        callAlert({
            body: 'Are you sure you want to remove this post?',
            button: '<button class="focused" onclick="removeMediaItem();">Yes</button>',
            otext: 'No'
        });
    });

    $(document).on("click", ".edit-blog-media-btn", function () {
        mediaItemToReplacePosition = null;
        mediaItemToMove = null;
        mediaItemToReplace = $(this).closest("figure")[0];
        let caption = $(this).closest("figure").find("figcaption").text() || "";
        if ($(this).hasClass("is-photo")) {
            $(".blog-media-upload-container-photo-caption").val(caption);
            $(".blog-photo-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
            $(".blog-media-photo-container").html('');
            $("#blog-media-link, #blog-media-selector").val("");
            $(".blog-photo-modal-container").addClass("active");
            $(".blog-photo-modal-container").scrollTop(0);
        }
        else {
            $(".blog-media-upload-container-video-caption").val(caption);
            $(".blog-video-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
            $(".blog-media-video-container").html('');
            $("#blog-media-video-link").val("");
            $(".blog-video-modal-container").addClass("active");
            $(".blog-video-modal-container").scrollTop(0);
        }
    });

    $(document).on("click", ".insert-blog-media-btn", function () {
        mediaItemToReplace = null;
        mediaItemToMove = $(this).closest("figure")[0];
        mediaItemToReplacePosition = $(this).hasClass("is-top") ? "top" : "bottom";
        let caption = "";
        if ($(this).hasClass("is-photo")) {
            $(".blog-media-upload-container-photo-caption").val(caption);
            $(".blog-photo-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
            $(".blog-media-photo-container").html('');
            $("#blog-media-link, #blog-media-selector").val("");
            $(".blog-photo-modal-container").addClass("active");
            $(".blog-photo-modal-container").scrollTop(0);
        }
        else {
            $(".blog-media-upload-container-video-caption").val(caption);
            $(".blog-video-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
            $(".blog-media-video-container").html('');
            $("#blog-media-video-link").val("");
            $(".blog-video-modal-container").addClass("active");
            $(".blog-video-modal-container").scrollTop(0);
        }
    });

    $(document).on("click", ".add-image-to-blog-btn", function () {
        textEditor.classList.remove("pholder");
        const figure = document.createElement("figure");
        const figCaption = document.createElement("figcaption");
        const img = document.createElement("img");
        const div = document.createElement("div");
        const options = document.createElement("span");
        options.setAttribute("class", "figure-options");
        options.innerHTML = "<span title='Insert new photo on top' class='insert-blog-media-btn is-photo is-top -cursor-pointer-'><i class='las la-angle-double-up'></i></span> <span title='Insert new photo below' class='insert-blog-media-btn is-photo is-bottom -cursor-pointer-'><i class='las la-angle-double-down'></i></span> <span title='Replace photo' class='edit-blog-media-btn is-photo -cursor-pointer-'><i class='las la-edit'></i></span> <span title='Replace with video' class='edit-blog-media-btn is-video -cursor-pointer-'><i class='lab la-youtube'></i></span> <span title='Remove photo' class='remove-blog-image-btn -cursor-pointer-'><i class='las la-times'></i></span>";
        figure.contentEditable = false;
        figCaption.contentEditable = false;
        img.contentEditable = false;
        img.setAttribute("class", document.querySelector(".blog-media-photo-container-image").classList.contains("external-image") ? "external-image" : "uploaded-image");
        img.draggable = false;
        img.alt = document.querySelector(".blog-media-upload-container-photo-caption").value;
        img.src = document.querySelector(".blog-media-photo-container-image").src;
        figCaption.innerHTML = document.querySelector(".blog-media-upload-container-photo-caption").value;
        div.innerHTML = "...";
        figure.appendChild(img);
        figure.appendChild(figCaption);
        figure.appendChild(options);

        if (mediaItemToReplace !== null) {
            document.querySelector(".simple-email-editor").replaceChild(figure, mediaItemToReplace);
        }
        else {
            if (mediaItemToMove !== null) {
                if (mediaItemToReplacePosition == "top") {
                    document.querySelector(".simple-email-editor").insertBefore(figure, mediaItemToMove);
                    // document.querySelector(".simple-email-editor").insertBefore(div, mediaItemToMove);
                }
                else if (mediaItemToReplacePosition == "bottom") {
                    document.querySelector(".simple-email-editor").insertBefore(figure, mediaItemToMove.nextElementSibling);
                    // document.querySelector(".simple-email-editor").insertBefore(div, mediaItemToMove.nextElementSibling);
                }
            }
            else {
                document.querySelector(".simple-email-editor").appendChild(figure);
                // document.querySelector(".simple-email-editor").appendChild(div);
                goBottom();
            }
        }

        $(".blog-photo-modal-container").removeClass("active");
        $(".blog-media-photo-container").empty();
    });

    $(document).on("click", ".add-video-to-blog-btn", function () {
        textEditor.classList.remove("pholder");
        const figure = document.createElement("figure");
        const figCaption = document.createElement("figcaption");
        const frame = document.createElement("div");
        const div = document.createElement("div");
        const options = document.createElement("span");
        options.setAttribute("class", "figure-options");
        options.innerHTML = "<span title='Insert new video on top' class='insert-blog-media-btn is-video is-top -cursor-pointer-'><i class='las la-angle-double-up'></i></span> <span title='Insert new video below' class='insert-blog-media-btn is-video is-bottom -cursor-pointer-'><i class='las la-angle-double-down'></i></span> <span title='Replace video' class='edit-blog-media-btn is-video -cursor-pointer-'><i class='las la-edit'></i></span> <span title='Replace with photo' class='edit-blog-media-btn is-photo -cursor-pointer-'><i class='las la-camera'></i></span> <span title='Remove video' class='remove-blog-image-btn -cursor-pointer-'><i class='las la-times'></i></span>";
        figure.contentEditable = false;
        figCaption.contentEditable = false;
        frame.contentEditable = false;
        frame.innerHTML = document.querySelector("#blog-media-video-link").value;
        frame.querySelectorAll("*").forEach(function (item) {
            if (item.nodeName.toLowerCase() !== "iframe") {
                item.parentElement.removeChild(item);
            }
        });
        frame.draggable = false;
        figCaption.innerHTML = document.querySelector(".blog-media-upload-container-video-caption").value;
        div.innerHTML = "...";
        figure.appendChild(frame);
        figure.appendChild(figCaption);
        figure.appendChild(options);

        if (mediaItemToReplace !== null) {
            document.querySelector(".simple-email-editor").replaceChild(figure, mediaItemToReplace);
        }
        else {
            if (mediaItemToMove !== null) {
                if (mediaItemToReplacePosition == "top") {
                    document.querySelector(".simple-email-editor").insertBefore(figure, mediaItemToMove);
                    // document.querySelector(".simple-email-editor").insertBefore(div, mediaItemToMove);
                }
                else if (mediaItemToReplacePosition == "bottom") {
                    document.querySelector(".simple-email-editor").insertBefore(figure, mediaItemToMove.nextElementSibling);
                    // document.querySelector(".simple-email-editor").insertBefore(div, mediaItemToMove.nextElementSibling);
                }
            }
            else {
                document.querySelector(".simple-email-editor").appendChild(figure);
                // document.querySelector(".simple-email-editor").appendChild(div);
                goBottom();
            }
        }

        $(".blog-media-video-container").children(":not(iframe)").remove();
        $(".blog-video-modal-container").removeClass("active");
        $(".blog-media-video-container").empty();
    });

    $(document).on("click", ".add-post-to-blog-btn", function () {
        textEditor.classList.remove("pholder");
        const figure = document.createElement("figure");
        const frame = document.createElement("div");
        const div = document.createElement("div");
        const options = document.createElement("span");
        options.setAttribute("class", "figure-options");
        figure.setAttribute("class", "social-media-post");
        options.innerHTML = "<span title='Remove post' class='remove-post-btn -cursor-pointer-'><i class='las la-times'></i></span>";
        figure.contentEditable = false;
        frame.contentEditable = false;
        frame.innerHTML = document.querySelector("#blog-media-post-link").value;
        frame.draggable = false;
        div.innerHTML = "...";
        figure.appendChild(frame);
        figure.appendChild(options);

        document.querySelector(".simple-email-editor").appendChild(figure);
        goBottom();

        $(".blog-post-modal-container").removeClass("active");
        $(".blog-media-post-container").empty();
    });

    $(document).on("click", ".add-blog-photo-btn", addPhotoToBlog);

    $(document).on("click", ".add-blog-video-btn", function () {
        mediaItemToReplace = null;
        mediaItemToRemove = null;
        mediaItemToReplacePosition = null;
        mediaItemToMove = null;
        $(".blog-video-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
        $(".blog-media-video-container").html('');
        $("#blog-media-video-link, .blog-media-upload-container-video-caption").val("");
        $(".blog-video-modal-container").addClass("active");
        $(".blog-video-modal-container").scrollTop(0);
    });

    $(document).on("click", ".add-blog-post-btn", function () {
        mediaItemToRemove = null;
        $(".blog-post-modal-container").removeClass("bmc-add-mode").addClass("bmc-upload-mode");
        $(".blog-media-post-container").html('');
        $("#blog-media-post-link").val("");
        $(".blog-post-modal-container").addClass("active");
        $(".blog-post-modal-container").scrollTop(0);
    });

    $(document).on("click", "#blog-media-link-btn", function () {
        const imageLink = $("#blog-media-link").val();
        if (!Modules.isEmpty(imageLink)) {
            CustomPhotoProcessor.addPhotos(imageLink, null, function (data) {
                if (typeof data === "object") {
                    switch (data.status) {
                        case "_OK":
                            Modules.togglePageLoader(true);
                            let image = new Image();
                            image.onload = function (e) {
                                Modules.togglePageLoader(false);
                                $(".blog-media-photo-container").html('<img class="blog-media-photo-container-image external-image" src="' + data.image + '" alt="" rel="nofollow"/>');
                                $(".blog-photo-modal-container").removeClass("bmc-upload-mode").addClass("bmc-add-mode");
                                $(".blog-media-upload-container-photo-caption").focus();
                                $(".blog-photo-modal-container").scrollTop(0);
                            };
                            image.onerror = function () {
                                Modules.togglePageLoader(false);
                                Modules.toggleToastContainer({
                                    message: "Failed to load image.",
                                    status: Modules.status.FAILED
                                });
                            };
                            image.src = data.image;
                            break;
                        case "_FAILED":
                            Modules.toggleToastContainer({
                                message: data.statusText,
                                status: Modules.status.FAILED
                            });
                            break;
                    }
                }
                else {
                    Modules.toggleToastContainer({
                        message: Modules.status.UNKNOWN_ERROR,
                        status: Modules.status.FAILED
                    });
                }
            }, {
                maxWidthAspectRatio: 1920,
                maxHeightAspectRatio: 1080,
            });
            // Modules.togglePageLoader(true);
            // let image = new Image();
            // image.crossOrigin = "anonymous";
            // image.onload = function (e) {
            //     Modules.togglePageLoader(false);
            //     $(".blog-media-photo-container").html('<img class="blog-media-photo-container-image external-image" src="' + imageLink + '" alt="" rel="nofollow"/>');
            //     $(".blog-photo-modal-container").removeClass("bmc-upload-mode").addClass("bmc-add-mode");
            //     $(".blog-media-upload-container-photo-caption").focus();
            //     $(".blog-photo-modal-container").scrollTop(0);
            // };
            // image.onerror = function (e) {
            //     Modules.togglePageLoader(false);
            //     Modules.toggleToastContainer({
            //         message: "Failed to load image."
            //     });
            // };
            // image.src = imageLink;
            return;
        }

        Modules.toggleToastContainer({
            message: "The URL is invalid."
        });
    });

    $(document).on("click", "#blog-media-video-link-btn", function () {
        const videoLink = $("#blog-media-video-link").val();
        if (!Modules.isEmpty(videoLink)) {
            $(".blog-media-video-container").html(videoLink);
            $(".blog-media-video-container").children(":not(iframe)").remove();
            $(".blog-video-modal-container").removeClass("bmc-upload-mode").addClass("bmc-add-mode");
            $(".blog-media-upload-container-video-caption").focus();
            $(".blog-video-modal-container").scrollTop(0);
            return;
        }

        Modules.toggleToastContainer({
            message: "The embed code is invalid."
        });
    });

    $(document).on("click", "#blog-media-post-link-btn", function () {
        const postLink = $("#blog-media-post-link").val();
        if (!Modules.isEmpty(postLink)) {
            $(".blog-media-post-container").html(postLink);
            $(".blog-post-modal-container").removeClass("bmc-upload-mode").addClass("bmc-add-mode");
            $(".blog-post-modal-container").scrollTop(0);
            return;
        }

        Modules.toggleToastContainer({
            message: "The embed code is invalid."
        });
    });

    $(document).on("click", ".blog-modal-container-closer-btn", function () {
        $(this).closest(".blog-modal-container").removeClass("active");
    });

    $(document).on("change", "#blog-media-selector", function () {
        var $this = $(this);
        var file = $this[0].files;
        Modules.togglePageLoader(true);
        CustomPhotoProcessor.handlePhotoSelect(file, function (data) {
            if (typeof data === "object") {
                $this.val("");
                switch (data.status) {
                    case "_OK":
                        Modules.togglePageLoader(true);
                        let image = new Image();
                        image.onload = function (e) {
                            Modules.togglePageLoader(false);
                            $(".blog-media-photo-container").html('<img class="blog-media-photo-container-image uploaded-image" src="' + data.image + '" alt="" rel="nofollow"/>');
                            $(".blog-photo-modal-container").removeClass("bmc-upload-mode").addClass("bmc-add-mode");
                            $(".blog-media-upload-container-photo-caption").focus();
                            $(".blog-photo-modal-container").scrollTop(0);
                        };
                        image.onerror = function () {
                            Modules.togglePageLoader(false);
                            Modules.toggleToastContainer({
                                message: "Failed to load image."
                            });
                        };
                        image.src = data.image;
                        break;
                    case "_FAILED":
                        Modules.toggleToastContainer({
                            message: data.statusText
                        });
                        break;
                }
            }
            else {
                Modules.toggleToastContainer({
                    message: Modules.status.UNKNOWN_ERROR,
                });
            }
        }, {
            maxWidthAspectRatio: 1920,
            maxHeightAspectRatio: 1080,
        });
    });

    $(document).on("mousedown", ".tool-items", function (e) {
        e.preventDefault();
    });

    $(document).on("input click keypress paste keydown keyup mousedown moouseup", ".simple-edt, .simple-email-editor", function (e) {
        wrapAroundTag("#blog-editor");
        
        if ($(this).find("br").length > 0 && $(this).text().replace(/^\s+/i, "").replace(/\s+$/i, "").length < 1) {
            $(this).empty();
            $(this).addClass("pholder");
            return;
        }

        if ($(this).text().replace(/^\s+/i, "").replace(/\s+$/i, "").length > 0 || $(this).html().replace(/^\s+/i, "").replace(/\s+$/i, "") !== "") {
            $(this).removeClass("pholder");
        }
        else {
            $(this).addClass("pholder");
        }
    });

    $(document).on("keydown", ".simple-edt, .simple-email-editor", function (e) {
        var sctelem = getSelectionStartElAct();
        if (sctelem.className.match(/ctrl-cur/i) && e.keyCode == 13) {
            e.preventDefault();
        }
    });

    $(document).on("paste", ".simple-edt, .simple-email-editor", function (e) {
        e.preventDefault();
        var $dis = $(this), car = getCaretPosition($dis[0]), len = 0;
        if (window.clipboardData) {
            content = window.clipboardData.getData('Text');
            content = content;
            len = content.length;
            if (window.getSelection) {
                var selObj = window.getSelection();
                var selRange = selObj.getRangeAt(0);
                selRange.deleteContents();
                selRange.insertNode(document.createTextNode(stripTags(content)));
                setTimeout(function(){
                    removeEmptyTags(textEditor);
                }, 10);
            }
        } else if (e.originalEvent.clipboardData) {
            content = (e.originalEvent || e).clipboardData.getData('text/plain');
            content = content;
            len = content.length;
            document.execCommand('insertText', false, stripTags(content));
            setTimeout(function(){
                removeEmptyTags(textEditor);
            }, 10);
        }
    });

    window.addEventListener("beforeunload", function (event) {
        if ($(".simple-email-editor").text().replace(/^\s+/i, "").replace(/\s+$/i, "").length > 0 || $(".simple-email-editor").html().replace(/^\s+/i, "").replace(/\s+$/i, "") !== "") {
            event.returnValue = "Unsaved changes may be lost...";
        }
    });

});