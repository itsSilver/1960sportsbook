<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

<div id="branding">
     
     <h1>
        <?php echo $this->MyHtml->spanLink(__(Configure::read('Settings.websiteName')), array('controller' => '/'), array('title' => 'Dashboard','style' => 'color:#FFF;')); ?>    
     </h1>
     <h2><?php echo __('Administration Panel'); ?></h2>  
     <h2>&nbsp;</h2>

     <?php if(isset($this->groupid) && ($this->groupid !='6' && $this->groupid !='7')) { ?>     
     <!-- Added by Praveen Singh on 18/11/2013 -->
     <form name="frmChangedashbaordToBeUsed" action = "" method="POST" id="frmChangedashbaordToBeUsed">
	<h2><?php echo __('Select dashbaord'); ?></h2>
	<select  style="color:#FFFFFF" id="dashbaordAdmin" name="dashbaordAdmin">
	    <option value="admin" <?php if(isset($_SESSION['dashboard_type']) && $_SESSION['dashboard_type']=='admin'){ echo "selected='selected'"; } ?>><?php echo __('Sport'); ?> </option>
            <option value="admin_lottery" <?php if(isset($_SESSION['dashboard_type']) && $_SESSION['dashboard_type']=='admin_lottery'){ echo "selected='selected'"; } ?>> <?php echo __('Lottery'); ?></option>
	</select>
     </form>
     
     <script type="text/javascript">
	jQuery(document).ready(function(){
	   jQuery('#dashbaordAdmin').change(function(){
	     jQuery('#frmChangedashbaordToBeUsed').submit();
	   });
	});
     </script> 
     <?php } ?>
</div>