jQuery(document).ready(function($) {
    
    $('#menu h3').each(function() {
        $(this).parent().children('ul').hide();
        $(this).click(function() {
            $(this).parent().children('ul').toggle('slow');
        });    
    });
    
});