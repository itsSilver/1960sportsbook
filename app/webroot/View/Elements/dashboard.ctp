<?php
$activeClassuserlottery = $activeClassdefault = '';
$dashboardType = $this->Session->read('dashboard_type_user');
if(isset($dashboardType) && $dashboardType=='default'){
   $activeClassdefault = "active";
}
if(isset($dashboardType) && $dashboardType=='user_lottery'){
   $activeClassuserlottery = "active";
}
?>

<div class="topheaderNav">
	<ul>        
	    <li class="<?php echo $activeClassdefault;?>">
		<?php echo $this->Html->link('SPORTS', array('controller' => 'pages', 'action' => 'default'),array('style' => '')); ?>
	    </li>        
	    <li class="<?php echo $activeClassuserlottery;?>">
		<?php echo $this->Html->link('LOTTERY', array('controller' => 'pages', 'action' => 'user_lottery'),array('style' => ''));?>
	    </li>
	</ul>
	<br /><br />
</div>