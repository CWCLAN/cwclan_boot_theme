$(document).ready(function() {
    var Wheight = $('body').prop('scrollHeight');        
+   $('.swipe-sidebar').css( "height" , Wheight + "px");        
+        
    $("[data-toggle]").click(function() {
        var toggle_el = $(this).data("toggle");
        $(toggle_el).toggleClass("open-sidebar");
    });     
}); 
$(".area").swipe({
    swipeStatus:function(event, phase, direction, distance, duration, fingers){
        if (phase =="move" && direction =="right") {
            $(".swipe-sidebar").addClass("open-sidebar");
                return false;
            }
        if (phase =="move" && direction =="left") {
            $(".swipe-sidebar").removeClass("open-sidebar");
                return false;
            }
     }     
});

$(".tp").tooltip({
    placement : "right"
});
$(".tp2").tooltip({
    placement : "right"
});