<?php
/******************************************
* @Created on Dec 04, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotteryTicketShell extends AppShell {

	public $uses      = array('Lottery','LotteryTicket','User');
    public $belongsTo = array('Lottery','User');

	public function lotterydrawMethod(){
		$todayTicketDrawnresult = $todayTicketDrawn = $dataInfoslArrayData = $dataInfoslArray = array();
		$options['conditions'] = array(
			'LotteryTicket.status' => 0,
			'LotteryTicket.draw_date <>' => ''
		);
		$options['order'] = array('LotteryTicket.draw_date');
		$lotteryDetail  = $this->LotteryTicket->find('all', $options);
		if(!empty($lotteryDetail)){		
			foreach ($lotteryDetail as $Key => $lottery) {
				if($lottery['LotteryTicket']['stuff_ball']!='') {
					$lottery['LotteryTicket']['lottery_ticket']  = $lottery['LotteryTicket']['ticket_id'].','.$lottery['LotteryTicket']['stuff_ball'];
				} else {
					$lottery['LotteryTicket']['lottery_ticket']  = $lottery['LotteryTicket']['ticket_id'];
				}
				$LotteryTypeDetail     = $this->LotteryType->getLotteryType($lottery['Lottery']['lottery_type']);
				$lottery['Lottery']['lottery_type'] = $LotteryTypeDetail[0]['LotteryType']['lottery_type'];
				$dataInfoslArray[$lottery['LotteryTicket']['lottery_id']][]  = $lottery;				
			}
		}		
		if(!empty($dataInfoslArray)){	
			foreach($dataInfoslArray as $lotteryKey => $dataInfosAll){
				foreach($dataInfosAll as $Key => $dataInfos){
					$today_drawn_time    = date('Y-m-d H');
					$lottery_ticket_time = date('Y-m-d H',strtotime($dataInfos['LotteryTicket']['draw_date']));
					if($today_drawn_time == $lottery_ticket_time){
						if($dataInfos['Lottery']['lottery_system_draw']==1){
							$todayTicketDrawn[$lotteryKey][$dataInfos['LotteryTicket']['id']] = $dataInfos['LotteryTicket']['lottery_ticket'];
							$todayTicketDrawnresult[$lotteryKey]['LotteryTicket_id'] = $dataInfos['LotteryTicket']['id'];
							$todayTicketDrawnresult[$lotteryKey]['today_Lottery_number'] = $todayTicketDrawn[$lotteryKey][array_rand($todayTicketDrawn[$lotteryKey])];
							$todayTicketDrawnresult[$lotteryKey]['lottery_type'] = $dataInfos['Lottery']['lottery_type'];
							$todayTicketDrawnresult[$lotteryKey]['lottery_id'] = $dataInfos['LotteryTicket']['lottery_id'];
							$todayTicketDrawnresult[$lotteryKey]['draw_date']  = $dataInfos['LotteryTicket']['draw_date'];
							$todayTicketDrawnresult[$lotteryKey]['lottery_detail'] = $dataInfos;
						} else if($dataInfos['Lottery']['lottery_system_draw']==2){
							$todayTicketDrawn[$lotteryKey][$dataInfos['LotteryTicket']['id']] = $dataInfos['LotteryTicket']['lottery_ticket'];
							$todayTicketDrawnresult[$lotteryKey]['LotteryTicket_id'] = $dataInfos['LotteryTicket']['id'];
							//Generating Random Ticket
							$range_start		  = 1;
							$range_end			  = 49;
							$random_string_length = $dataInfos['Lottery']['num_lott_ball'];
							$randomLotteryNumber  = parent::__randomNumGenerator($range_start,$range_end,$random_string_length);
							if(!in_array($randomLotteryNumber,$todayTicketDrawn[$lotteryKey])){
								$todayTicketDrawnresult[$lotteryKey]['today_Lottery_number'] = $randomLotteryNumber;
							}
							$todayTicketDrawnresult[$lotteryKey]['lottery_type'] = $dataInfos['Lottery']['lottery_type'];
							$todayTicketDrawnresult[$lotteryKey]['lottery_id'] = $dataInfos['LotteryTicket']['lottery_id'];
							$todayTicketDrawnresult[$lotteryKey]['draw_date']  = $dataInfos['LotteryTicket']['draw_date'];
							$todayTicketDrawnresult[$lotteryKey]['lottery_detail'] = $dataInfos;
						} else if($dataInfos['Lottery']['lottery_system_draw']==0){}						
					}
				}
			}
			//updating winner ticket Status 1
			if(!empty($todayTicketDrawnresult)){	
				foreach($todayTicketDrawnresult as $winnerlotteryticketkey => $winnerlotteryticketAll){
					$coloum_field_value_str = " `status` = '1' , `win_date`= '".$winnerlotteryticketAll['draw_date']."' , `win_ticket`= '".$winnerlotteryticketAll['today_Lottery_number']."' ";
					$this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field ='id', $updated_on_value = $winnerlotteryticketAll['LotteryTicket_id'], $otherfields = ' and `status` = 0 ');
				}
			}
			//updating losser ticket Status 2
			if(!empty($todayTicketDrawn)){	
				foreach($todayTicketDrawn as $lotteryticketkey => $losserlotteryticketAll){
					foreach($losserlotteryticketAll as $losserticketkey => $losserticketAll){
						$coloum_field_value_str = " `status` = '2' ";
						$this->LotteryTicket->saveManyGlobalData($table_name = 'lottery_tickets', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $losserticketkey , $otherfields = ' and `status` = 0 ');			
					}
				}
			}
			//Updatimng Next Lottery Draw Date of all Lottery Game
			if(!empty($todayTicketDrawnresult)){	
				foreach($todayTicketDrawnresult as $lotteryticketkeyone => $winnerlotteryticketone){
					$currentDate = date("Y-m-d H:i:s");
					$draw_time = parent::__getEndDatefromdays($currentDate,6);
					$coloum_field_value_str = " `draw_time` = '".$draw_time."' ";
					$this->LotteryTicket->saveManyGlobalData($table_name = 'lotterys', $coloum_field_value_str, $updated_on_field='id', $updated_on_value = $winnerlotteryticketone['lottery_id'] , $otherfields = '');					
				}
			}
		}
		return true;
	}
}
?>