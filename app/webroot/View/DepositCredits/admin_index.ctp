<div id="" class="index">
    <h2><?php echo __('Credited Amount To Players'); ?></h2>

    <?php echo $this->Session->flash(); ?>
    
    <?php if($userdetail['group_id'] == 8){ ?>
    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Add Credit To Player Account'), array('action' => 'add'), array('class' => 'button-blue')); ?>
    </div>
    <?php } ?>

    <?php if (!empty($data)){ ?>

        <table class="items">

            <tr>
		<th><?php echo $this->Paginator->sort('DepositCredit.id', __('Id')); ?></th>
                <th><?php echo $this->Paginator->sort('DepositCredit.date_added', __('Date')); ?></th>
                <th><?php echo $this->Paginator->sort('DepositCredit.credits', __('Credits Transfered')); ?></th>
		<?php if($userdetail['group_id'] == 2){ ?>

		<th><?php echo $this->Paginator->sort('Agent.username', __('Agent')); ?></th>

		<?php } ?>
                <th><?php echo $this->Paginator->sort('User.username', __('Player')); ?></th>
		<th><?php echo $this->Paginator->sort('DepositCredit.status', __('Status')); ?></th>
		<th><?php echo __('Actions'); ?></th>
            </tr>

            <?php
	    $hourLeftForCreditRefund = '0';
	    foreach ($data as $row):	    
		$currentTime = strtotime(date('Y-m-d h:m:s'));		
		$endTime     = strtotime($row['DepositCredit']['date_added']);
		$hourLeftForCreditRefund = round(($currentTime - $endTime) / 3600);				
		?>
                <tr>
		    <td><?php echo $row['DepositCredit']['id']; ?></td>
                    <td><?php echo date('d M Y, h:m:s',strtotime($row['DepositCredit']['date_added']));?></td>
                    <td><?php echo $row['DepositCredit']['credits']; ?></td>
		    <?php if($userdetail['group_id'] == 2){ ?>
			<td><?php echo $this->Html->link($row['Agent']['username'], array('controller'=>'users', 'action' => 'view', $row['Agent']['id']), array('target' => '_blank')) ?></td>
		    <?php } ?>

		    <td><?php echo $this->Html->link($row['User']['username'], array('controller'=>'users', 'action' => 'view', $row['User']['id']), array('target' => '_blank')) ?></td>
                    <td><?php 
		    if($row['DepositCredit']['status'] == '2'){
			echo "Refund Requested";
		    } else if($row['DepositCredit']['status'] == '3'){
			 echo "Refunded";
		    } else if($row['DepositCredit']['status'] == '4'){
			 echo "Refund Rejected";
		    }else {
			echo "Transfered";
		    }
		    ?></td>

		    <td>
		    <?php
			if($userdetail['group_id'] == 2 && $row['DepositCredit']['status'] == '2'){
				echo $this->Html->link(__('Accept Request'), array('controller'=>'deposit_credits', 'action' => 'action', $row['DepositCredit']['id'], 3))."&nbsp;|&nbsp;";
				echo $this->Html->link(__('Reject Request'), array('controller'=>'deposit_credits', 'action' => 'action', $row['DepositCredit']['id'], 4))."&nbsp;|&nbsp;";
			}

			if(isset($hourLeftForCreditRefund) && ($hourLeftForCreditRefund <= '24') && ($userdetail['group_id'] == 8) && ($row['DepositCredit']['status'] == '1')) {
				echo $this->Html->link(__('Request Refund'), array('controller'=>'deposit_credits', 'action' => 'action', $row['DepositCredit']['id'], 2))."&nbsp;|&nbsp;";
			}
			echo $this->Html->link(__('Delete'), array('controller'=>'deposit_credits', 'action' => 'delete', $row['DepositCredit']['id']),'','Are you sure you want to perform this action ?');
			
		    ?>
		    
		    </td>
                </tr>  
            <?php endforeach; ?>

        </table>

        <?php echo $this->element('paginator'); ?>  

    <?php } else { ?>
	<div class="message closable">
	    <?php echo __('There are no records yet'); ?>       
	</div>
    <?php } ?>

    
</div>