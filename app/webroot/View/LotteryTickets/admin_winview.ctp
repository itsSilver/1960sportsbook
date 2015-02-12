<?php $user_id = $first_name = $last_name = $email = $username = ''; ?>

<div id="items">

     <?php echo $this->Form->create();?>

	<?php if(!empty($data)) { ?>
	      
	<table class="marginTable default-table">
	    
		    <tr>						
			<th><?php echo __('USER ID'); ?></th>
			<th><?php echo __('FIRST NAME'); ?></th>	
			<th><?php echo __('LAST NAME'); ?></th>	
			<th><?php echo __('EMAIL'); ?></th>
			<th><?php echo __('USERNAME'); ?></th>
		    </tr>

	      <?php foreach ($data as $useridkey => $users) {      
	            $user_id    = $users['id'];
		    $first_name = $users['first_name'];
		    $last_name  = $users['last_name'];
		    $email      = $users['email'];
		    $username   = $users['username'];		
	           ?>
		   
		   <tr>
		       <td><?php echo $user_id; ?></td>
		       <td><?php echo $first_name; ?></td>
		       <td><?php echo $last_name; ?></td>
		       <td><?php echo $email; ?></td>
		       <td><?php echo $username; ?></td>
		   </tr> 

	      <?php } ?>

	          <tr style="background:#E4E5E6;">
		       <td>&nbsp;</td>
		       <td>&nbsp;</td>
		       <td>&nbsp;</td>
		       <td>&nbsp;</td>
		       <td id="">
		           <a class="button-blue" id="buttonblue<?php echo $keydata;?>" href="javascript:;">Hide</a>			   
		       </td>
		   </tr> 
		   <script type="text/javascript">
		     jQuery(document).ready(function(){
			jQuery('#buttonblue<?php echo $keydata;?>').click(function(){
			  jQuery("#loader<?php echo $keydata;?>").hide();
			});
		     });
		   </script>

	     </table>

	<?php } ?>

      <?php echo $this->Form->end(); ?>

</div>