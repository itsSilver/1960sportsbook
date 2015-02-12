
<div id="users">
    <div class="">

	<?php echo $this->Session->flash(); ?>

	<h2><?php echo __('Add Credits Amount To Player'); ?></h2>

        <?php echo $this->Form->create(); ?>

        <?php echo $this->Form->input('credits', array('type' => 'text','style' => 'width:350px;')); ?>

	<?php echo $this->Form->input('player_id', array('type' => 'text','id' => 'search_user_input')); ?>

	<?php echo $this->Form->input('availablecredits', array('type' => 'hidden', value => $userdetail['balance'], 'id' => 'availablecredits'));?>

        <?php echo $this->Form->end(__('Send Credits', true)); ?>    


    </div>

    <script type="text/javascript">
    jQuery(document).ready(function(){

	jQuery("#search_user_input").tokenInput(<?php echo $filteruserJson; ?>, {
	preventDuplicates: true		
	});

	jQuery('#UserCredits').change(function(){
	    var enteredcredits = parseFloat(jQuery(this).val());
	    var creditsavailable = parseFloat(jQuery('#availablecredits').val());
	    if(enteredcredits > creditsavailable){
                alert('Credits should be less than available balance of ('+creditsavailable+')');
		jQuery(this).val('');
	    }
	});

	jQuery('#UserAdminAddForm').submit(function(){		
	   if(confirm("Are you sure you want to perform this action ?")){
		var enteredcredits = parseFloat(jQuery(this).val());
		var creditsavailable = parseFloat(jQuery('#availablecredits').val());
		if(enteredcredits > creditsavailable){
		   alert('Credits should be less than available balance of ('+creditsavailable+')');
		   jQuery(this).val('');
		   return false;
		}			
	   } else {
		return true;
	   }
	});

    });
    </script>

</div>