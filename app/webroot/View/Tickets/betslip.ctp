<?php
$selected[1] = '';
$selected[2] = '';
$selected[3] = '';
$selected[$type] = 'selected';
$totalOdds = 0;
?>
<div id="betsliph"></div>

    <div id="betslip-type">
        <?php if($betsCount): ?>
        <ul> 
            <?php if (Configure::read('Settings.allowMultiSingleBets') == 1): ?>
                <li>
                    <a href="#" onClick="setType(1); return false;" class="betType <?php echo $selected[1]; ?>"><?php echo __('Single'); ?></a>
                </li> 
            <?php elseif ($type == 1): ?>
                <li>
                    <a href="#" onClick="setType(1); return false;" class="betType <?php echo $selected[1]; ?>"><?php echo __('Single'); ?></a>
                </li>
            <?php endif; ?>                
            <li>
                <a href="#" onClick="setType(2); return false;" class="betType <?php echo $selected[2]; ?>"><?php echo __('Multibet'); ?></a>
            </li>             
            <?php if ($jackpot == true): ?>
                <li>
                    <a href="#" onClick="setType(3);" class="betType <?php echo $selected[3]; ?>"><?php echo __('Jackpot'); ?></a>
                </li>             
            <?php endif; ?>
        </ul> 
        <?php endif; ?>
    </div>
<div class="clear"></div><br /><br />
    <div class="bets"> 

        <div id="betslip-bets">
            <?php if ($type == 1): ?>
                <?php foreach ($bets as $bet): ?>
                    <table class="bets">
                        <tr>
                            <td class="bet-name"><?php echo $bet['Bet']['name']; ?></td>
                            <td class="last">
                                <div onclick="removeBet(<?php echo $bet['BetPart']['id']; ?>);">
                                    <?php echo $this->Html->image('bet/x.png', array('alt' => 'remove', 'class' => 'removeBet')) ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pick"><?php echo $bet['BetPart']['name']; ?></td>
                            <td class="last"><?php echo $this->Beth->convertOdd($bet['BetPart']['odd']); ?></td>
                        </tr>
                        <tr>
                            <td class="single-stake bottom"><?php echo __('Stake'); ?></td>
                            <td class="last bottom"><?php echo $this->Form->input('stake', array('label' => false, 'div' => false, 'value' => $bet['Bet']['stake'], 'type' => 'text', 'id' => 'stake-' . $bet['BetPart']['id'], 'onBlur' => "setStake({$bet['BetPart']['id']})")); ?></td>
                        </tr>



                    </table>
                <?php endforeach; ?>
        <?php if(!$betsCount): ?>
        <table class="bets">
                      <tr>
                        <td class="bet-name2"><?php echo __('Your Betslip is Empty. Please select events to place it.', true); ?><br /><br /></td>
                        <td class="last"></td>
                      </tr>
        </table>
        <?php endif; ?>
        </div> 
            <div id="betslip-totals">
                <?php if($betsCount): ?>
                <table class="totals">
                    <tr>
                        <th><?php echo __('Total bets:'); ?></th>
                        <td><?php echo $betsCount; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo __('Total stake:'); ?></th>
                        <td><?php echo $totalStake; ?></td>
                    </tr>
                    <tr>
                        <th class="pWinning"><?php echo __('Potential winning:'); ?></th>
                        <td><?php echo $this->Beth->convertCurrency($totalWinning); ?> <?php echo $currency ?></td>
                    </tr>
                <tr>
                    <th class="pWinning"><?php echo __('Jackpot:'); ?></th>
                    <td><?php echo $this->Beth->convertCurrency($totalStake * (float)Configure::read('Settings.jackpotPercent')); ?> <?php echo $currency ?></td>
                </tr>
                </table>
                <?php endif; ?>
            </div>
<?php /* DRY!!!!!!!!!!!!!!!!!!!!!!!! MTF!! */ ?>


        <?php else: $totalOdds = 1; ?>
            <?php foreach ($bets as $bet):
                
                $totalOdds *= $bet['BetPart']['odd'];
                ?>
                <table class="bets">
                    <tr>
                        <td class="bet-name"><?php echo $bet['Bet']['name']; ?></td>
                        <td class="last">
                            <div onclick="removeBet(<?php echo $bet['BetPart']['id']; ?>);">
                                <?php echo $this->Html->image('bet/x.png', array('alt' => 'remove', 'class' => 'removeBet')) ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="pick bottom"><?php echo $bet['BetPart']['name']; ?></td>
                        <td class="last bottom"><?php echo $this->Beth->convertOdd($bet['BetPart']['odd']); ?></td>
                    </tr>
                </table>
            <?php endforeach; ?>
        <?php if(!$betsCount): ?>
        <table class="bets">
                      <tr>
                        <td class="bet-name2"><?php echo __('Your Betslip is Empty. Please select events to place it.', true); ?><br /><br /></td>
                        <td class="last"></td>
                      </tr>
        </table>
        <?php endif; ?>
        </div> 
        <div id="betslip-totals">
             <?php if($betsCount): ?>
            <table class="totals">
                <tr>
                    <th><?php echo __('Total bets:'); ?></th>
                    <td><?php echo $betsCount; ?></td>
                </tr>

                <tr>
                    <th><?php echo __('Total odds:'); ?></th>
                    <td><?php echo $this->Beth->convertOdd($totalOdds); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Stake:'); ?></th>
                    <td>
                        <input id="total-stake" type="text" name="betStake" onkeyup="setReturn()" onblur="setStake()" value="<?php echo $totalStake; ?>" />
                        <div style="display:none" id="decimalOdds"><?php echo $this->Beth->convertOdd($totalOdds); ?></div> 
                    </td>
                </tr>
                <tr>
                    <th class="pWinning"><?php echo __('Possible winning:'); ?></th>
                    <td><?php echo $this->Beth->convertCurrency($totalWinning); ?> <?php echo $currency ?></td>
                </tr>
                <tr>
                    <th class="pWinning"><?php echo __('Jackpot:'); ?></th>
                    <td><?php echo $this->Beth->convertCurrency($totalStake* (float)Configure::read('Settings.jackpotPercent')); ?> <?php echo $currency ?></td>
                </tr>


            </table>
            <?php endif; ?>
        </div>

<?php endif; ?>

    <div class="actions group">

<?php if($betsCount): ?>
        
            <?php if (Configure::read('Settings.ticketPreview') == 1): ?>
	        <?php $balance = $this->Session->read('Auth.User.balance');?>
		<?php $this->groupid = $this->Session->read('Auth.User.group_id');?>
	        <?php $this->userid = $this->Session->read('Auth.User.id'); ?>

		<?php if(isset($this->groupid) && $this->groupid=='8' && $totalStake < $balance){?>
		    
		    <div class="pL10 pT10 pB10">
		        
			<?php echo $this->Form->create('ticket', array('url' => array('controller' => 'tickets', 'action' => 'agent_preview')));?>
		        
			<table class="totals">

			    <tr>
			        <td><h3 style="font-size:8px;"><?php echo __('Generate'); ?></h3></td>
			        <td style="padding:10px;"></td>
			    </tr>
			    
			    <tr>
			        <td style="padding:5px;"><?php echo __('Enter User ID'); ?></td>
			        <td style="padding:5px;"><?php echo $this->Form->input('user_id', array('div' => false,'label' => false,'type' => 'text','placeholder' => 'Enter user ID', 'style' => 'width:100%;border:1px solid #000', 'value'=>'','id'=> 'user_id'));  ?></td>
			    </tr>

			    <tr>
			        <td style="padding:5px;">&nbsp;</td>
			        <td style="padding:5px;">				
				  <?php echo $this->Form->input('Preview',array('id'=>'send_bet_ticket_request','type'=>'submit','label'=>false,'class'=>'button','value'=>'Preview'));?>
				</td>
			    </tr>

			</table>

			<script type="text/javascript">
			    jQuery(document).ready(function(){			     
			     jQuery('#send_bet_ticket_request').click(function(){		     
			       var user_id     = jQuery('#user_id').val();
			       if(user_id == ''){
				  jQuery('#user_id').css('border','1px solid red');
				  return false;		  
			       } else {
				  jQuery('#user_id').css('border','1px solid #000');		  
				  return true;				  
			       }
			     });
			   });
			</script>

			<?php echo $this->Form->end();?>
			
		    </div>

		<?php } else if(!isset($this->groupid) || (isset($this->groupid) && $this->groupid!='8')) {?>
                    <div class="place-ticket">
		        <?php echo $this->Html->link(__('Preview', true), array('action' => 'preview'), array('class' => 'button-blue')); ?>
		    </div>
		<?php } ?>
            <?php else: ?>
                <div class="place-ticket">
		     <?php echo $this->Html->link(__('Place Bet', true), array('action' => 'place', 1), array('class' => 'button-blue')); ?>  
		</div>
            <?php endif; ?>
      
        <?php endif; ?>

    </div>

    <div id="post">
        <?php echo $this->Session->flash(); ?>
    </div>