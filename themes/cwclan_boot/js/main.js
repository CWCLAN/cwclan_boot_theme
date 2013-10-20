$(document).ready(function() {
    var Wheight = $('body').prop('scrollHeight'), Wwidth = $('body').prop('scrollWidth');
    if (Wwidth > '950') { 
        $('.swipe-sidebar').css("height", Wheight + "px");
    }

    if (!$.cookie_ftw("CW_ToggleStatus")) {
        $.cookie_ftw("CW_ToggleStatus", 0, {
            expires: 14, path: '/'
        });
    };
    var state = $.cookie_ftw("CW_ToggleStatus");
    $(".swipe-toggle").click(function() {
        var toggle_el = $(this).data("toggle");
        $(toggle_el).toggleClass("open-sidebar");
        $.cookie_ftw("CW_ToggleStatus", (state == 1 ? "0" : "1"), {
            expires: 14, path: '/'
        });
        console.log("cookie gesetzt " + state);
    });
    var toggle_el = $(".swipe-toggle").data("toggle");
    if (($.cookie_ftw("CW_ToggleStatus") == 0 && $(toggle_el).hasClass("open-sidebar"))) {
        $(toggle_el).removeClass("open-sidebar");        
    } else {    
        $(toggle_el).addClass("open-sidebar");        
    };
});
// Swipe deaktiviert
$(".area").swipe({
    swipeStatus: function(event, phase, direction, distance, duration, fingers) {
        if (phase == "move" && direction == "right") {
            $(".swipe-sidebar").addClass("open-sidebar");
            return false;
        }
        if (phase == "move" && direction == "left") {
            $(".swipe-sidebar").removeClass("open-sidebar");
            return false;
        }
    }
});

$(".tp").tooltip({
    placement: "right"
});
$(".tp2").tooltip({
    placement: "right"
});
