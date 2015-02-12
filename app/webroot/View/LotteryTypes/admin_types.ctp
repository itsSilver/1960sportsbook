<div id="" class="index">   

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('All Lottery types'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('Add Lottery Type'), array('action' => 'admin_type'), array('class' => 'right')); ?></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($data)){ ?>

        <table class="items">

            <tr>				
		<th><?php echo $this->Paginator->sort('LotteryType.id', __('ID')); ?></th>		
		<th><?php echo $this->Paginator->sort('LotteryType.lottery_type', __('LOTTERY TYPE')); ?></th>		
		<th><?php echo $this->Paginator->sort('LotteryType.is_active', __('STATUS')); ?></th>
		<th><?php echo $this->Paginator->sort('LotteryType.added_on', __('ADDED ON')); ?></th> 
		<th><?php echo __('ACTION'); ?></th>
            </tr>

            <?php foreach ($data as $row){ ?>
                <tr>
		    <td><?php echo $row['LotteryType']['id']; ?></td>		    
		    <td><?php echo $row['LotteryType']['lottery_type']; ?></td>		   
		    <td><?php echo date('d M Y h:i',strtotime($row['LotteryType']['added_on'])); ?></td>
		    <td>
		       <?php if(isset($row['LotteryType']['is_active']) && $row['LotteryType']['is_active']=='1'){ echo 'Active'; } else { echo 'Deactive';}; ?>
		    </td>
		    <td>			
			<?php echo $this->MyHtml->spanLink(__('Edit'), array('action' => 'admin_edit',$row['LotteryType']['id'],'edit'), array('class' => 'button-blue')); ?>
			&nbsp;|&nbsp;
			<?php if(isset($row['LotteryType']['is_active']) && $row['LotteryType']['is_active']=='1'){?>			
			   <?php echo $this->MyHtml->spanLink(__('Deactive'), array('action' => 'admin_action',$row['LotteryType']['id'],'deactive'),'','Do you really want to deactive this Lottery Type.Click OK to continue and CANCEL to return.'); ?>
			<?php } else { ?>
			   <?php echo $this->MyHtml->spanLink(__('Active'), array('action' => 'admin_action',$row['LotteryType']['id'],'active'),'','Do you really want to active this Lottery Type.Click OK to continue and CANCEL to return.'); ?>
			<?php } ?>
			
		    </td>
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