<div id="index">

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('Add Agent Percentage'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('Back'), array('action' => 'admin_agent_list'), array('class' => 'button-blue right')); ?></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->Flash(); ?>

    <?php echo $this->Form->create(); ?>

    <table class="items">

        <tr>
            <td><label><?php echo __('Agent Name'); ?></label></td>
            <td>		
		<?php if(isset($username)){ echo $username; }?>
		<?php echo $this->Form->input('agent_id', array('id'=> 'select_agent_option','type' => 'hidden','value' => $agent_id));?>
	    </td>
        </tr>

	<tr>
            <td><label><?php echo __('Enter Agent Percentage'); ?></label></td>
            <td>
	        <?php echo $this->Form->input('agent_perct', array('label' => false, 'type' => 'text', 'class' => '','id' => 'select_agent_perct','value'=> $agent_perct)); ?>	    
	    </td>
        </tr>

	<tr>
            <td>&nbsp;</td>
            <td><?php echo $this->Form->submit(__('Submit', true), array('class' => 'button')); ?></td>
        </tr>

	<script type="text/javascript">
	   jQuery(document).ready(function(){
	     jQuery('#UserAdminAgentsetForm').submit(function(){
		   var agentPert = jQuery('#select_agent_perct').val();
		  if(agentPert!=''){
		     jQuery('#select_agent_perct').css('border','');		     		         
		     return true;		      
		  } else {
		     jQuery('#select_agent_perct').css('border','1px solid red');
		     return false;
		  }
	     });
	   });
        </script>

    </table>

    <?php echo $this->Form->end(); ?>

</div>