<div id="users">
    <?php if (!empty($leagueOption)){ ?>
	    <div class="">  
		<?php echo $this->Form->input('league_id', array('label' => _('Select League'), 'type' => 'select', 'options' => $leagueOption, 'class' => 'dropbox','id' => 'click_league','style'=>'width:30%;')); ?> 
		
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#click_league').change(function(e){
				e.preventDefault();
				jQuery("#event_detail_div_name").html('Loading....Please Wait.').show();
				jQuery("#league_name_submit").hide();
				jQuery.ajax({
					type: "POST",
					data: jQuery("#AgentAdminPrintLeaguesForm").serialize(),
					url : "/agents/get_leagues_event_ajax",			
					success: function(msg){		
					  jQuery("#event_detail_div_name").html(msg).show();
					  //jQuery("#league_name_submit").hide();
					}							
				});		
			});	
		});
		</script>
	    </div>
    <?php } else { ?>
	<?php echo __('No League added yet'); ?></h4>
    <?php } ?>
</div>