<div id="" class="index">   

    <h2><?php echo __('Download Details'); ?></h2>

    <?php if (!empty($downloadData)){ ?>

        <table class="items">

            <tr>
		<th>Sport Name</th>
		<th>League Name</th>
		<th>Total Event</th>		
            </tr>

            <?php foreach ($downloadData as $row){ ?>
                <tr>		
		     <td><?php echo $row['sport_name'];?></td>
		     <td><?php echo $row['league_name'];?></td>		   
		     <td><?php echo $row['eventcount'];?></td>		     		     
                </tr>  
            <?php } ?>

        </table> 

	<table class="items">

	<h2 style="padding-top:25px;"><?php echo __('Download Type'); ?></h2>  
	
	<tr>
	     <th>All Event</th>
	     <th>Daily Event</th>
	     <th>Weekly Event</th>	
	</tr>
    
	<tr>		     	    
	     <td>
		<?php if (isset($downloadCountAll) && $downloadCountAll >0){ ?>
		 <?php echo $this->MyHtml->spanLink(__('Download All Event ('.$downloadCountAll.')'), array('controller' => 'admin/agents','action' => 'league_schedulepdf',$row['league_id'],'all'), array('class' => 'button-blue right')); ?>
		 <?php } else {?>
		 No event for download
		 <?php } ?>
	     </td>
	     <td>
		<?php if (isset($downloadDailyCount) && $downloadDailyCount >0){ ?>
		 <?php echo $this->MyHtml->spanLink(__('Download Today Event ('.$downloadDailyCount.')'), array('controller' => 'admin/agents','action' => 'league_schedulepdf',$row['league_id'],'daily'), array('class' => 'button-blue right')); ?>
		 <?php } else {?>
		 No event for download
		 <?php } ?>
	     </td>
	     <td>
		<?php if (isset($downloadweaklyCount) && $downloadweaklyCount >0){ ?>
		 <?php echo $this->MyHtml->spanLink(__('Download Weekly Event ('.$downloadweaklyCount.')'), array('controller' => 'admin/agents','action' => 'league_schedulepdf',$row['league_id'],'weekly'), array('class' => 'button-blue right')); ?>
		 <?php } else {?>
		 No event for download
		 <?php } ?>
	     </td>
	</tr>

        </table> 

    <?php } else { ?>
	<div class="message closable">
	    <?php echo __('There are no event yet'); ?>       
	</div>
    <?php } ?>
    
</div>