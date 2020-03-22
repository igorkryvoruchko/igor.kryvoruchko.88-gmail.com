$(document).ready(function() {
    $("#search_name").focusout(function(){
        if($(this).val()) {
            window.location.replace("/product/?search_name=" + $(this).val());
        }
    });
    $("#search_name").keydown(function(e) {
        if(e.keyCode === 13 && $(this).val()) {
            window.location.replace("/product/?search_name="+ $(this).val());
        }
    });
});