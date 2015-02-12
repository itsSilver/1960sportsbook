<?php



class MyFormHelper extends FormHelper {
    
    function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = array()) {
        //parent::dateTime($fieldName, $dateFormat, $timeFormat, $selected, $attributes);
        
        if ($timeFormat != NULL)
            return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datetimepicker_input'));
        else
            return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datepicker_input'));
    }
    
}

?>