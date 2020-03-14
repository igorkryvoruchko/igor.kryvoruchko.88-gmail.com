$("#search_name").focusout(function(){
    window.location.replace("/product/?search_name="+ $(this).val());
});