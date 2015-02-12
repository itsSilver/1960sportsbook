<div id="" class="index">   

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('All Agent List'); ?></h3></th>
	</tr>
    </table><br />

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($agentsData)){ ?>

        <table class="items">

            <tr>				
		<th>ID</th>
		<th>AGENT NAME</th>
		<th>AGENT PERCT</th>
		<th>ACTION</th> 
            </tr>

            <?php foreach ($agentsData as $agentid => $row){ ?>
                <tr>
		    <td><?php echo $row['id']; ?></td>
		    <td><?php echo $row['username']; ?></td>
		    <td><?php echo $row['agent_perct']; ?></td>
		    <td>			
			<?php echo $this->MyHtml->spanLink(__('Modify'), array('action' => 'admin_agentset',$agentid,'edit'), array('class' => 'button-blue')); ?>		
		    </td>
                </tr>  
            <?php } ?>

        </table>

    <?php } else { ?>
	<div class="message closable">
	    <?php echo __('There are no agent list'); ?>       
	</div>
    <?php } ?>
    
</div>