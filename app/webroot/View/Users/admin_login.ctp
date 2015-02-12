<table class="adminlogo"><center><img src="/app/webroot/img/bet/logo.png"></center></table>

<div id="users">

    <div class="login">
      
        <?php echo $this->Session->flash(); ?>
	
	<?php echo $this->Form->create(); ?>

        <?php echo $this->Form->input('username',array('id' => 'username')); ?>

        <?php echo $this->Form->input('password'); ?>

        <?php echo $this->Form->input('group_id', array('type' => 'select', 'options' => $groups, 'class' => 'dropbox','id' => 'group_id_type')); ?>

        <?php echo $this->Form->end(__('Login', true)); ?>        

	<script type="text/javascript">
	jQuery(document).ready(function(){
	  jQuery('#username').change(function(){
	     var username = jQuery("#username").val();
	     if(username!='') {	
		jQuery.ajax({
		    type: "POST",
		    data: 'username='+username,
		    url : "/admin/users/select_group",	
		    success: function(msg){	
		       if(jQuery.trim(msg)=='0'){
		         jQuery("#username").css('border','2px solid red');	         
		       } else {
			  jQuery("#username").css('border','');	
		          jQuery('#group_id_type').val(msg);
		       }						    
		     }							
		});
	     }
	  });
	});
	</script>

    </div>

</div>