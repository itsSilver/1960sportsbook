<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="" class="index"> 

    <table class="items">
	<tr>				
	    <th><h3 class="fontbld font16"><?php echo __('All Lottery Game'); ?></h3></th> 
	    <th><?php echo $this->MyHtml->spanLink(__('Add Game'), array('action' => 'admin_create'), array('class' => 'right')); ?></th> 
	</tr>
    </table><br />

    <?php echo $this->Session->flash(); ?>

    <?php if (!empty($data)){ ?>

        <table class="items">

            <tr>
				
		<th><?php echo $this->Paginator->sort('Lottery.id', __('ID')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.lottery_type_name', __('LOTTERY TYPE')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.logo', __('LOGO')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.name', __('NAME')); ?></th>		
		<th><?php echo $this->Paginator->sort('Lottery.num_lott_ball', __('NUMBER OF BALL')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.prize_level', __('PRIZE LEVEL')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.lottery_fee', __('LOTTERY FEE')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.is_stuff', __('STUFF')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.drawn_time', __('DRAWN TIME')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.is_active', __('ACTIVE')); ?></th>
		<th><?php echo $this->Paginator->sort('Lottery.added_on', __('ADDED ON')); ?></th> 
		<th><?php echo __('ACTION'); ?></th> 
            </tr>

            <?php foreach ($data as $row){ ?>
                <tr>
		    <td><?php echo $row['Lottery']['id']; ?></td>
		    <td><?php echo $row['Lottery']['lottery_type_name']; ?></td>
		    <td>
			<?php
			$imagepath= '/img/lottery/'.$row['Lottery']['logo'];
			$imageSrc = $this->MyHtml->image(''.$imagepath.'', array('alt' => ''.$row['Lottery']['logo'].'','class'=>'logomedium'));
			echo $this->MyHtml->spanLink(__(''.$imageSrc.''), array('action' => 'admin_view',$row['Lottery']['id'],'view'), array('class' => 'button-blue right')); ?>
		    </td>
		    <td><?php echo $row['Lottery']['name']; ?></td>		    
		    <td><?php echo $row['Lottery']['num_lott_ball']; ?></td>
		    <td><?php echo $row['Lottery']['prize_level']; ?></td>
		    <td><?php echo $row['Lottery']['lottery_fee'].' '.$currency; ?></td>
		    <td>
		        <?php if(isset($row['Lottery']['is_stuff']) && $row['Lottery']['is_stuff']==1) { echo 'Yes'; } else { echo 'No';} ?>
		    </td>
		    <td><?php echo date('d M Y h:i',strtotime($row['Lottery']['draw_time'])); ?></td>
		    <td><?php echo $row['Lottery']['is_active']; ?></td>
		    <td><?php echo date('d M Y h:i',strtotime($row['Lottery']['added_on'])); ?></td>
		    <td>
			<?php echo $this->MyHtml->spanLink(__('View'), array('action' => 'admin_view',$row['Lottery']['id'],'view'), array('class' => 'button-blue')); ?> | <?php echo $this->MyHtml->spanLink(__('edit'), array('action' => 'admin_edit',$row['Lottery']['id'],'edit'), array('class' => 'button-blue')); ?> | <?php echo $this->MyHtml->spanLink(__('delete'), array('action' => 'admin_delete',$row['Lottery']['id'],'delete'), array('class' => 'button-blue'),'Do you really want to delete.'); ?>				
		    </td>
                </tr>  
            <?php } ?>

	    <?php
	    if(isset($totalitemsPage) && isset($itemsPerPage) && $totalitemsPage > $itemsPerPage){?>
	    <tr>
		<td colspan="12">
		    <?php echo $this->element('paginator'); ?>  
		</td>
	    </tr>  
            <?php } ?>    

        </table> 

    <?php } else { ?>
	<div class="message closable">
	    <?php echo __('There are no records yet'); ?>       
	</div>
    <?php } ?>

    
</div>