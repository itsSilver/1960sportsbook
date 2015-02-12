<?php
/******************************************
* @Created on Dec 04, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotteryTicket extends AppModel {

    public $name      = 'LotteryTicket';
    public $belongsTo = array('Lottery','User');

	function getTicket($id=NULL) {
        $options['conditions'] = array(
            'LotteryTicket.id' => $id
        );
        return $this->find('all', $options);
    }

	function saveGlobalData($table_name=null,$coloum_field=null, $coloum_value=null,$updated_on_field=null,$updated_on_value=null,$otherfields=null) {
		$sql ="UPDATE `".$table_name."` SET `".$coloum_field."` = '".$coloum_value."' where `".$updated_on_field."` = '".$updated_on_value."' ".$otherfields." ";
		$return = $this->query($sql);
		return true;
	}

	function saveManyGlobalData($table_name=null,$coloum_field_value_str=null,$updated_on_field=null,$updated_on_value=null,$otherfields=null) {
		$sql = "UPDATE `".$table_name."` SET ".$coloum_field_value_str." where `".$updated_on_field."` = '".$updated_on_value."' ".$otherfields." ";
		$return = $this->query($sql);
		return true;
	}

	function approve_action($DMLTtype,$tablename,$coloumStr,$updated_field,$updated_value){		
		$sql = " ".$DMLTtype."  `".$tablename."`  ".$coloumStr."  WHERE  `".$updated_field."` = '".$updated_value."' ";
		$result = $this->query($sql);	
		return true;		
	}

	function getalllottteryDraw() {
		$sql = "select * from lotterys l JOIN lottery_tickets lt on l.id=lt.lottery_id where l.is_active = 1 and lt.status=0 order by lt.lottery_id ";
		return $return = $this->query($sql);
	}

	function getalllottteryDrawResultToday($status=0) {
		$winnercondtion = '';
		if($status == '0'){ $winnercondtion = " and ltk.win_date= '".date('Y-m-d')."' "; }
		$sql = "select lt.*,lty.*,ltk.* from lotterys lt ,lottery_types lty,lottery_tickets ltk where lt.lottery_type =lty.id and ltk.lottery_id =lt.id and lt.is_active = 1 and lty.is_active = 1 and ltk.status=1 and ltk.win_ticket!='' ".$winnercondtion." order by ltk.win_date ";
		return $return = $this->query($sql);
	}

	function getlottteryDrawResult($status=0,$lotteryid=NULL) {
		$winnercondtion = '';
		if($status == '0'){ $winnercondtion .= " and ltk.win_date= '".date('Y-m-d')."' "; }
		if($status == '1'){ $winnercondtion .= " and ltk.lottery_id= '".$lotteryid."' "; }
		$sql = "select lt.*,lty.*,ltk.* from lotterys lt ,lottery_types lty,lottery_tickets ltk where lt.lottery_type =lty.id and ltk.lottery_id =lt.id and lt.is_active = 1 and lty.is_active = 1 and ltk.status=1 and ltk.win_ticket!='' ".$winnercondtion." order by ltk.win_date ";
		return $return = $this->query($sql);
	}

	function getlottteryDrawResultCondition($lotteryid=NULL,$stisfiedCondition=NULL) {
		$sql = "select lt.*,lty.*,ltk.* from lotterys lt ,lottery_types lty,lottery_tickets ltk where lt.lottery_type =lty.id and ltk.lottery_id =lt.id and lt.is_active = 1 and lty.is_active = 1 and ltk.status=1 and ltk.win_ticket!='' and ltk.lottery_id= '".$lotteryid."' ".$stisfiedCondition." order by ltk.win_date ";
		return $return = $this->query($sql);
	}

	function getLottteryTicketWinnerCount($lottery_id=NULL,$ticketStr=NULL,$draw_date=null) {
		$sql = "select COUNT(user_id) as ticket_count, user_id from lottery_tickets where lottery_id = '".$lottery_id."' and draw_date = '".$draw_date."' and ticket_id LIKE '%".$ticketStr."%' group by user_id ";		
		return $return = $this->query($sql);
	}
}
?>