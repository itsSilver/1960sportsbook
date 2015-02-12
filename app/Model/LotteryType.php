<?php
/******************************************
* @Created on Nov 18, 2013.
* @Package: Sportsbook
* @Developer: Praveen Singh
* @URL : www.1960sportsbook.com
********************************************/

class LotteryType extends AppModel {

    public $name = 'LotteryType';
	
	function activeLotteryType() {
        $options['conditions'] = array(
            'LotteryType.is_active' => 1
        );
        return $this->find('all', $options);
    }

	function getLotteryType($id=null) {
        $options['conditions'] = array(
            'LotteryType.id' => $id,
			'LotteryType.is_active' => 1
        );
        return $this->find('all', $options);
    }

	function saveManyGlobalData($table_name=null,$coloum_field_value_str=null,$updated_on_field=null,$updated_on_value=null,$otherfields=null) {
		$sql = "UPDATE `".$table_name."` SET ".$coloum_field_value_str." where `".$updated_on_field."` = '".$updated_on_value."' ".$otherfields." ";
		$return = $this->query($sql);
		return true;
	}

	function global_action($DMLTtype,$tablename,$coloumStr,$updated_field,$updated_value){		
		$sql = " ".$DMLTtype."  `".$tablename."`  ".$coloumStr."  WHERE  `".$updated_field."` = '".$updated_value."' ";
		$result = $this->query($sql);	
		return true;		
	}	
}

?>
