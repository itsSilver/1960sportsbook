
<div id="users">
    <div class="">
	<?php echo $this->Session->flash(); ?>
	
	<h2><?php echo __('Select Sport Name'); ?></h2>

	<?php if (!empty($sportOption)){ ?>
	
		<?php echo $this->Form->create(); ?>

		<?php echo $this->Form->input('sport_id', array('label' => _('Select Sport'), 'type' => 'select', 'options' => $sportOption, 'class' => 'dropbox','id' => 'select_sport','style'=>'width:30%;')); ?>

		<div id="leaguename" class="" style="display:none;padding-top:15px;"></div>      

		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#select_sport').change(function(){
				var sport_id = jQuery(this).val();			
				if(sport_id !=''){
					jQuery("#leaguename").html('Loading...please wait.').show();
					jQuery("#event_detail_div_name").hide();
					jQuery("#league_name_submit").hide();
					jQuery.ajax({
						type: "POST",
						data: 'sport_id='+sport_id,
						url : "/agents/get_leagues_ajax",			
						success: function(msg){		
						  jQuery("#leaguename").html(msg).show();
						  jQuery("#league_name_submit").show();
						}							
					});
				}
			});	
		});	
		</script>
		<?php echo $this->Form->end(__(array('value'=>'Submit','id'=>'league_name_submit','style'=>'display:none;'))); ?>

		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#AgentAdminPrintLeaguesForm').submit(function(e){
				e.preventDefault();
				jQuery("#event_detail_div_name").html('Loading....Please Wait.').show();
				jQuery.ajax({
					type: "POST",
					data: jQuery("#AgentAdminPrintLeaguesForm").serialize(),
					url : "/agents/get_leagues_event_ajax",			
					success: function(msg){		
					  jQuery("#event_detail_div_name").html(msg).show();
					  jQuery("#league_name_submit").show();
					}							
				});		
			});	
		});
		</script>

		<div id="event_detail_div_name" class="" style="padding-top:25px;display:none;"></div> 
	<?php } else { ?>

	<?php echo __('No Sport added yet'); ?></h4>

	<?php } ?>

    </div>
</div>

