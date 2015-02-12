<?php
$numbered_matched_array = $prize_level_array = $prize_perct_array =array();
$num_lott_ball = $level_perct = $prize_level = $lottery_type_name = '';

if(!empty($data)){
  $lottery_type_name = $data['Lottery']['lottery_type_name'];
  $num_lott_ball     = $data['Lottery']['num_lott_ball'];
  $prize_level       = $data['Lottery']['prize_level'];
  $level_perct	     = $data['Lottery']['level_perct'];
  $prize_perct_array = explode(',',$level_perct);

  //creating prize level
  $prize_level_array = array('First Prize','Second Prize','Third Prize','Fourth Prize','Fifth Prize','Sixth Prize','Seventh Prize','Eighth Prize','Nineth Prize','Tenth Prize');
  
  //creating prize numbered matched
  for($key=1;$key<$num_lott_ball;$key++){
    if($key == 1){
      $numbered_matched_array[] =array(''.$num_lott_ball.' out of '.$num_lott_ball.'');
    }    
    $numbered_matched_array[] =array(''.($num_lott_ball-$key).' out of '.$num_lott_ball.'');
  }
}
?>

<div id="account">
    <?php echo $this->Session->Flash(); ?>
    
    <?php echo $this->element('lottery_tab'); ?>

    <?php echo $this->Form->create();?>

    <div style="background:#E4E5E6;">
	
       <div style="padding:10px;font-family: Arial,Helvetica,sans-serif;font-size: 15px;font-weight:normal;">
	    Hey You Never Know 
       </div>

       <div style="padding:10px;font-size:13px;margin-bottom:10px;">
	    If your <?php echo $num_lott_ball-1;?> numbers plus the <?php if(isset($lottery_type_name)) { $stuffballArray = explode(' ',$lottery_type_name);if(isset($stuffballArray[0])){ echo $stuffballArray[0];} }?> ball match the winning six numbers drawn,then you win or share the First Prize. If the jackpot is not won in any drawing, the First Prize Pool Money is carried forward and is added to the next Powerball Jackpot. 
       </div>
			
       <table class="default-table">
	     <thead>
	        <tr style="background:#000;color:#FFF;">
		   <th style="padding:10px;">PRIZE LEVEL</th>
		   <th style="text-align:center;padding:10px;">NUMBERS MATCHED</th>
		   <th style="text-align:center;padding:10px;">PRIZE PERCENTAGE</th>
		 </tr>
	     </thead>

	     <tbody>
	         <?php foreach($prize_perct_array as $key => $levels){?>
		 <tr>
		    <td style="font-size:11px;padding:10px;"><?php echo $prize_level_array[$key];?></td>
		    <td style="font-size:11px;text-align:center;padding:10px;"><?php echo $numbered_matched_array[$key][0];?></td>
		    <td style="font-size:11px;text-align:center;padding:10px;"><?php echo $levels.'%';?></td>
	         </tr>
		 <?php } ?>
	     </tbody>
        </table>
	
    </div>
    
    <?php echo $this->Form->end(); ?>

</div>