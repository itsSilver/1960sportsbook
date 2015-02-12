
jQuery().ready(function(){
    
    //BetSlip.Init();
    
    jQuery('.eventTitle').each(function(){
        jQuery(this).click(function(){
            //FIXME: add multi language
            var el = jQuery(this).parent().children('.eventBets');
            if(el.is(':hidden')){
                el.parent().children('.betsMoreNLess').html('less');
            }
            else{
                el.parent().children('.betsMoreNLess').html('more');
            }
            el.animate({
                height: 'toggle'
            });
        })
    });

});

var menuid = false;

function showMenu( id ){
 
    menuid = id;

    jQuery('#backButton').show();

    jQuery('.menuMarker' + id).each(function (index, domEle) {
        jQuery(domEle).animate({
            height: 'toggle'
        })
    })

    jQuery('.menuMarkerParent').each(function(){
        jQuery(this).hide()
    });

}


function showMenuFast( id ){

    menuid = id;

    jQuery('#backButton').show();

    jQuery('.menuMarker' + id).each(function (index, domEle) {
        jQuery(domEle).show()
    })

    jQuery('.menuMarkerParent').each(function(){
        jQuery(this).hide()
    });

}

function backToSports( ){
    jQuery('#backButton').hide();
    jQuery('.menuMarker' + menuid).each(function (index, domEle) {
        jQuery(domEle).hide()
    })
    jQuery('.menuMarkerParent').each(function(){
        jQuery(this).animate({
            height: 'toggle'
        })
    });

}