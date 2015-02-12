<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="" class="index">   

    <h2><?php echo __('All Credit Request'); ?></h2>

    <?php echo $this->Session->flash(); ?>
    
    <?php if(isset($this->groupid) && $this->groupid == 8){ ?>
    <div class="right">
        <?php echo $this->MyHtml->spanLink(__('Send Credit Request'), array('action' => 'request'), array('class' => 'button-blue')); ?>
    </div><br />
    <?php } ?>

    <?php if (!empty($data)){ ?>

        <table class="items">

            <tr>
		<th><?php echo $this->Paginator->sort('Agent.id', __('Id')); ?></th>
		<?php if(isset($this->groupid) && $this->groupid == 8){ ?>
		<th><?php echo $this->Paginator->sort('Agent.recevier_id', __('Receiver')); ?></th>
		<?php } else { ?>
		<th><?php echo $this->Paginator->sort('Agent.sender_id', __('Sender')); ?></th>
		<?php } ?>		
		<th><?php echo $this->Paginator->sort('Agent.amount', __('Amount')); ?></th>
		<th><?php echo $this->Paginator->sort('Agent.status', __('Status')); ?></th>
		<th><?php echo $this->Paginator->sort('Agent.date', __('Date')); ?></th> 
		<th><?php echo __('Actions'); ?></th>
            </tr>

            <?php foreach ($data as $row){ ?>
                <tr>
		    <td><?php echo $row['Agent']['id']; ?></td>
		    <?php if(isset($this->groupid) && $this->groupid == 8){ ?>
			<td><?php echo $row['Agent']['receiver_name']; ?></td>
		    <?php } else { ?>
			<td><?php echo $row['Agent']['sender_name']; ?></td>
		    <?php } ?>
		     <td><?php echo $row['Agent']['amount']; ?></td>
		    <td>
		       <?php if(isset($row['Agent']['status']) && $row['Agent']['status'] == '0'){
			     echo '<font color="blue">Pending</font>';
		       } else if(isset($row['Agent']['status']) && $row['Agent']['status'] == '1'){
			     echo '<font color="green">Accepted</font>';
		       } else {
			     echo '<font color="red">Rejected</font>';
		       }		       
		       ?>
		    </td>
		   
		    <td><?php echo date('d M Y h:m:s',strtotime($row['Agent']['date'])); ?></td>

		    <?php if(isset($this->groupid) && $this->groupid == 8){ ?>
			<td>
			    <?php echo $this->MyHtml->spanLink(__('Delete'), array('action' => 'admin_action',$row['Agent']['id'],'delete'), array('class' => 'button-blue right'),'Are you sure?'); ?>
			</td>
		    <?php } else { ?>
			
			<td>
			   <?php if(isset($row['Agent']['status']) && $row['Agent']['status'] == '0'){ ?>
				
				<?php echo $this->MyHtml->spanLink(__('Accept'), array('action' => 'admin_action',$row['Agent']['id'],'accept'), array('class' => 'button-blue right'),'Are you sure?'); ?> |
				<?php echo $this->MyHtml->spanLink(__('Reject'), array('action' => 'admin_action',$row['Agent']['id'],'reject'), array('class' => 'button-blue right'),'Are you sure?'); ?> |
				<?php echo $this->MyHtml->spanLink(__('Delete'), array('action' => 'admin_action',$row['Agent']['id'],'delete'), array('class' => 'button-blue right'),'Are you sure?'); ?>

			  <?php } else { ?>
				
				<?php echo $this->MyHtml->spanLink(__('Delete'), array('action' => 'admin_action',$row['Agent']['id'],'delete'), array('class' => 'button-blue right'),'Are you sure?'); ?>
			   			   
			   <?php } ?>			   
			   
			</td>
		    <?php } ?>    
                </tr>  
            <?php } ?>

        </table>

        <?php echo $this->element('paginator'); ?>  

    <?php } else { ?>
	<div class="message closable">
	    <?php echo __('There are no records yet'); ?>       
	</div>
    <?php } ?>

    
</div>