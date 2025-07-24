/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

$(document).ready(function () {
    
    $(document).on("click", ".drop-btn", function (e) {
        e.stopPropagation();

        if ($(this).closest(".parent-drop").length > 0) {
            $(".hiddible").not($(this).closest(".parent-drop")).hide();
        }
        else {
            $(".hiddible").hide();
        }

        var $dis = $(this);
        if ($dis.hasClass("dnext")) {
            if ($dis.hasClass("dfx")) {
                $dis.next(".drop-child-item").addClass("marto").css({ "display": "flex", "display": "-webkit-flex" });
                setTimeout(function () {
                    $dis.next(".drop-child-item").removeClass("marto");
                }, 150);
            }
            else {
                $dis.next(".drop-child-item").addClass("marto").show(0, function () {
                    $dis.next(".drop-child-item").removeClass("marto");
                });
            }
        }
        else {
            if ($dis.hasClass("dfx")) {
                $dis.find(".drop-child-item").first().addClass("marto").css({ "display": "flex", "display": "-webkit-flex" });
                setTimeout(function () {
                    $dis.find(".drop-child-item").removeClass("marto");
                }, 150);
            }
            else {
                $dis.find(".drop-child-item").first().addClass("marto").show(0, function () {
                    $dis.find(".drop-child-item").removeClass("marto");
                });
            }
        }
    });

    $(document).click(function () {
        $(".hiddible").hide();
    });

    $(document).on("click", ".pdrop-item", function () {
        $(".hiddible").not($(this).closest(".parent-drop")).hide();
    });

    $(document).on("click", ".drop-child-item", function (e) {
        e.stopPropagation();
    });

});