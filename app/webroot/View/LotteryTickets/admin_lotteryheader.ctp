<?php
if(!empty($data['Lottery'])) {
   $name		= $data['Lottery']['name'];
   $lottery_type_name   = $data['Lottery']['lottery_type_name'];
}

if($lottery_type_name!='' && $name!='') {?>
   <h3 class="fontbld" style="background-color:#003366;color: #FEFEFE;margin-bottom:10px;padding:0px 0px 0px 5px;;"><?php echo __($lottery_type_name); ?>&nbsp;//&nbsp;<?php echo __($name); ?></h3>
<?php } ?>	