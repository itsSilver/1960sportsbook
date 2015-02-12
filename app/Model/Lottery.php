<?php
/******************************************
* @Created on Nov 18, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class Lottery extends AppModel {

    public $name     = 'Lottery';
	public $useTable = 'lotterys';
	
	function activeLottery() {
        $options['conditions'] = array(
            'Lottery.is_active' => 1
        );
        return $this->find('all', $options);
    }

	function allLottery() {  
       $lottery = $this->find('all');
       return $lottery;
    }

	function getLottery($id) {
	   $options['conditions'] = array(
            'Lottery.id' => $id
       );
      return $this->find('all', $options);
    }

	function saveLotteryData($data,$id){
		$sql ="UPDATE `lotterys` SET name='".$data['name']."', lottery_type='".$data['lottery_type']."', num_lott_ball='".$data['num_lott_ball']."', prize_level='".$data['prize_level']."', lottery_fee='".$data['lottery_fee']."', logo='".$data['logo']."', is_stuff='".$data['is_stuff']."', draw_time='".$data['draw_time']."', is_active='".$data['is_active']."', added_on='".$data['added_on']."' where id='".$id."' ";
		$return = $this->query($sql);
		return true;
	}

	function savelevelprizeData($id, $level_perct_field, $level_perct) {
		$sql ="UPDATE `lotterys` SET `".$level_perct_field."` = '".$level_perct."' where id='".$id."' ";
		$return = $this->query($sql);
		return true;
	}

	function saveGlobalData($table_name,$coloum_field, $coloum_value,$updated_on_field,$updated_on_value) {
		$sql ="UPDATE `".$table_name."` SET `".$coloum_field."` = '".$coloum_value."' where `".$updated_on_field."` = '".$updated_on_value."' ";
		$return = $this->query($sql);
		return true;
	}

	function saveManyGlobalData($table_name=null,$coloum_field_value_str=null,$updated_on_field=null,$updated_on_value=null,$otherfields=null) {
		$sql = "UPDATE `".$table_name."` SET ".$coloum_field_value_str." where `".$updated_on_field."` = '".$updated_on_value."' ".$otherfields." ";
		$return = $this->query($sql);
		return true;
	}
    
}

?>
