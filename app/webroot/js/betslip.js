var BetSlip = {
    bets:[],
    odd:1,
    amount:10,
    type:2,
    Init:function(){

        jQuery('.bet1title').each(function(){
            jQuery(this).parent().click(function(ob){
                if(BetSlip.bets[jQuery(this).attr('betid')]==undefined){
                jQuery(this).addClass('activeBet');
                BetSlip.add(jQuery(this).attr('eventid'), jQuery(this).attr('betid'), '1')
                BetSlip.bets[ jQuery(this).attr('betid') ] = true;
                }
            })});
        
        jQuery('#betStake').change(function(){BetStlip.amount = jQuery(this).val();BetSlip.recalculate()});


        jQuery('.betx').each(function(){
            jQuery(this).click(function(){
                if(BetSlip.bets[jQuery(this).attr('betid')]==undefined){
                jQuery(this).addClass('activeBet');
                BetSlip.add(jQuery(this).attr('eventid'), jQuery(this).attr('betid'), '0')
                BetSlip.bets[ jQuery(this).attr('betid') ] = true;
                }
            })});

        jQuery('.bet2title').each(function(){
            jQuery(this).parent().click(function(){
                if(BetSlip.bets[jQuery(this).attr('betid')]==undefined){
                jQuery(this).addClass('activeBet');
                BetSlip.add(jQuery(this).attr('eventid'), jQuery(this).attr('betid'), '2')
                BetSlip.bets[ jQuery(this).attr('betid') ] = true;
                }
            })});

    jQuery('#betSlipTypeSingle').click(BetSlip.typeSingle);
    jQuery('#betSlipTypeMultibet').click(BetSlip.typeMulti);
    jQuery('#betSlipTypeSystem').click(BetSlip.typeSystem);


    for(i in BetSlip.bets){
        //Mark active bets here
        //alert(BetSlip.bets[i])
       if(BetSlip.bets[i][1]!=0)
            jQuery('.betListBox[betid='+i+']').has('.bet'+BetSlip.bets[i][1]).addClass('activeBet');
       else
            jQuery('.betx[betid='+i+']').addClass('activeBet');

    }

    },
    add:function( evid, betid, selection){
        jQuery.ajax({
    url: "/sports/apiadd/"+evid+"/"+betid+"/"+selection,
    success:BetSlip.complete
  });
    },
   complete:function(data){

        jQuery("#betSlipBets").html(jQuery("#betSlipBets").html()+data);
        jQuery(".betsTicketBox").each(function(){jQuery(this).change(function(){
            BetSlip.changeBet(jQuery(this).parent().parent().attr('betid'), jQuery(this).attr('checked')?1:0);

        })});
        jQuery('.removeBet').each(function(){
            jQuery(this).click(function(){
               BetSlip.removeBet( jQuery(this).parent().parent().attr('betid') );
               jQuery(this).parent().parent().remove();
            })
        })
    },
    
    changeBet:function( betid, status ){

       BetSlip.bets[betid][2] = status;
       BetSlip.recalculate()
       jQuery.ajax('/sports/apichange/'+betid+'/'+status);
   },


   removeBet:function( betid ){
       BetSlip.bets[betid] = undefined;
        if(jQuery('div[betid="'+betid+'"]').hasClass('activeBet'))
        jQuery('div[betid="'+betid+'"]').removeClass('activeBet')
        else jQuery('div[betid="'+betid+'"]').parent().removeClass('activeBet')
       BetSlip.recalculate()
       jQuery.ajax('/sports/apiremove/'+betid);
   },

   recalculate:function(){
       if(BetSlip.type == 2){
       //Multi
       jQuery('#BetSlipOdsBox').show();
       BetSlip.odd = 1;
       jQuery('#betOdds').html(BetSlip.odd);
       for( a in BetSlip.bets){
           if(BetSlip.bets[a]!=undefined)
           if(BetSlip.bets[a][2])
                BetSlip.odd = BetSlip.odd* BetSlip.bets[a][0]
       }

        jQuery('#BetSlipSystemBox').html('')

       jQuery('#betOdds').html(Math.round(BetSlip.odd*100)/100);
       jQuery('#betOutcome').html( Math.round(BetSlip.odd* BetSlip.amount*100)/100+" EUR");
       }
       else if(BetSlip.type == 1){
       //single
       jQuery('#BetSlipOdsBox').hide();
       BetSlip.odd = 0;
       var cnt = 0;
       for( a in BetSlip.bets){
           if(BetSlip.bets[a]!=undefined){
           if(BetSlip.bets[a][2])
                BetSlip.odd += BetSlip.bets[a][0];
                cnt++;
           }
       }

        jQuery('#BetSlipSystemBox').html('')

       jQuery('#betOdds').html(Math.round(BetSlip.odd*100)/100);
       jQuery('#betOutcome').html( Math.round(BetSlip.odd* BetSlip.amount*100)/100+" EUR");
       }
       else{
       //System
       var cnt = 0;
       for( a in BetSlip.bets){
           if(BetSlip.bets[a]!=undefined)
           if(BetSlip.bets[a][2])
           {
                BetSlip.odd += BetSlip.bets[a][0];
                cnt++;
           }
       }
       
       jQuery('#BetSlipOdsBox').hide();
         var htm = '';
       jQuery('#BetSlipMakeBet').show();
       if(cnt<9&&cnt>2)
        for(var i=2;i<cnt;i++){
        htm+=
        '<div class="inf">&nbsp;System ' + i + '/' + cnt + '</div>'+
        '<div class="cur"><input type="radio" name="system" /></div>'+
        '<div class="clear"></div>';
        }
       else
           jQuery('#BetSlipMakeBet').hide();

            jQuery('#BetSlipSystemBox').html(htm)
       }
   },
   
/* DRY????? */
/* nope :( Extreme Programing prefer here */
   typeSingle:function( ){

    jQuery('#betSlipType .betType').each(function(){
        if(jQuery(this).hasClass('selected'))
            jQuery(this).removeClass('selected');
    })

    jQuery('#betSlipTypeSingle').addClass('selected')
    BetSlip.type = 1;
    BetSlip.recalculate();
    jQuery.ajax('/sports/apisetstate/'+BetSlip.type);

   },

   typeMulti:function( ){

    jQuery('#betSlipType .betType').each(function(){
        if(jQuery(this).hasClass('selected'))
            jQuery(this).removeClass('selected');
    })

    jQuery('#betSlipTypeMultibet').addClass('selected')
    BetSlip.type = 2;
    jQuery.ajax('/sports/apisetstate/'+BetSlip.type);

    BetSlip.recalculate();

   },

   typeSystem:function( ){

    jQuery('#betSlipType .betType').each(function(){
        if(jQuery(this).hasClass('selected'))
            jQuery(this).removeClass('selected');
    })

    jQuery('#betSlipTypeSystem').addClass('selected')
    BetSlip.type = 3;
    jQuery.ajax('/sports/apisetstate/'+BetSlip.type);

    BetSlip.recalculate();

   },


   template:function( par ){



   }

}