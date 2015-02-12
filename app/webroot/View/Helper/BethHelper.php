<?php

class BethHelper extends AppHelper {

    var $helpers = array('Ajax', 'Session', 'Time');

    function getThemesList() {
        $themes = array(
            'Black' => 'Black',
            'LightBlue' => 'Light blue',
            'Grey' => 'Grey',
            'DarkRed' => 'Dark red',
            'Red' => 'Red',
            'Orange' => 'Orange',
            'Green' => 'Green',
            'Brown' => 'Brown',
            'Blue' => 'Blue',
            'Pink' => 'Pink'
        );
        return $themes;
    }

    function convertTime($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        $format = 'H:i';
        return $this->Time->format($format, $time, null, $timeZone);
    }
    
    function convertDate($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        $format = 'l d\/m\/Y';
        return $this->Time->format($format, $time, null, $timeZone);
    }

        function convertDateTime($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        $format = 'd\/m\/Y H:i';
        return $this->Time->format($format, $time, null, $timeZone);
    }
    
    function getRemainingTime($time) {
        $diference = strtotime($time) - strtotime(gmdate("M d Y H:i:s"));
        $sDays = $sHours = $sMins = '';
        $rDays = date('j', $diference) - 1;
        if ($rDays > 0)
            $sDays = $rDays . ' ' . $this->count($rDays, __('day'), __('days')) . ' ';
        $rHours = date('G', $diference);
        if ($rHours > 0)
            $sHours = $rHours . ' ' . $this->count($rHours, __('h'), __('h')) . ' ';
        $rMins = (int) date('i', $diference);
        if ($rMins > 0)
            $sMins = $rMins . ' ' . $this->count($rMins, __('min'), __('min'));
        $time = $sDays . $sHours . $sMins;
        return $time;
    }

    function count($number, $singular, $plural) {
        if ($number > 1) {
            return $plural;
        }
        return $singular;
    }

    function convertCurrency($amount) {
        return sprintf("%01.2f", round($amount, 2));
    }

    function convertOdd($odd) {
        if ($this->Session->read('Auth.User.odds_type')) {
            $type = $this->Session->read('Auth.User.odds_type');
        } else if ($this->Session->read('odds_type')) {
            $type = $this->Session->read('odds_type');
        } else {
            //TODO default odds type
            $type = 'default';
        }

        switch ($type) {
            case 2:
                return $this->convertToFractional($odd);
                break;
            case 3:
                return $this->convertToAmerican($odd);
                break;
            default:
                return sprintf("%01.2f", round($odd, 2));
                break;
        }
    }

    function convertToFractional($odd) {
        //TODO simplify
        $numerator = ($odd - 1) * 100;
        $denominator = 100;
        return $numerator . '/' . $denominator;
    }

    function convertToAmerican($odd) {
        if ($odd >= 2) {
            return '+' . 100 * ($odd - 1);
        } else {
            return round(-100 / ($odd - 1));
        }
    }

    function getDepositStatus($status) {
        $str = '';
        switch (intval($status)) {
            case 1:
                $str = '<span class="deposit-success">' . __('Successful', true) . '</span>';
                break;
            case 2:
                $str = '<span class="deposit-failed">' . __('Failed', true) . '</span>';
                break;
            case 0:
                $str = '<span class="deposit-unknown">' . __('Unknown', true) . '</span>';
                break;
        }
        return $str;
    }

    function getStatus($status) {
        $str = '';
        switch (intval($status)) {
            case 1:
                $str = '<span class="ticket-won">' . __('Win', true) . '</span>';
                break;
            case 0:
                $str = '<span class="ticket-pending">' . __('Pending', true) . '</span>';
                break;
            case -1:
                $str = '<span class="ticket-lost">' . __('Lost', true) . '</span>';
                break;
            case -2:
                $str = '<span class="ticket-canceled">' . __('Canceled', true) . '</span>';
                break;
        }
        return $str;
    }

    function getOddsType($type) {
        $str = '';
        switch ($type) {
            case 1:
                $str = __('Decimal');
                break;
            case 2:
                $str = __('Fractional');
                break;
            case 3:
                $str = __('American');
                break;
        }
        return $str;
    }

    function getOddsTypes() {
        $types[1] = 'Decimal';
        $types[2] = 'Fractional';
        $types[3] = 'American';
        return $types;
    }

    
    function makeNiceBet( $event){
        $str = '';
     if(count($event['Bet'])){   
          $str = 
        '<div class="itemListingDate">'.$this->convertDate($event['Event']['date']).'</div>'.
        '<div class="itemListingBlock">
                <div class="itemListingBTitle">
                '.$event['Event']['name'].'
                </div>
        ';
          
        foreach($event['Bet'] AS $bet){
          //print_r($bet);
         switch ($bet['type']) {
            case 'Versus':
            case 'Versus (with Draw)':

                   $str .= '<div class="itemListingBLine">
                        <div class="itemListingBLDate">'.$this->convertTime($event['Event']['date']).'</div>
                        <div class="itemListingBLEvent">'.$bet['name'].'</div>
                        ';
                
                  $str .= '<div class="itemListingBLOdd">
                    <a href="#" title="'.$bet['BetPart'][count($bet['BetPart'])-1]['BetPart']['id'].'" class="OddsButton">'.$this->convertOdd($bet['BetPart'][count($bet['BetPart'])-1]['BetPart']['odd']).'</a></div>';
                
                
                $str .= '
                        <div class="itemListingBLOdd">';
                if(count($bet['BetPart'])==3)
                    $str .=' <a href="#" title="'.$bet['BetPart'][1]['BetPart']['id'].'" class="OddsButton">'.$this->convertOdd($bet['BetPart'][1]['BetPart']['odd']).'</a>';
                $str .= '</div>';
                

                $str .= '<div class="itemListingBLOdd"><a href="#" title="'.$bet['BetPart'][0]['BetPart']['id'].'" class="OddsButton">'.$this->convertOdd($bet['BetPart'][0]['BetPart']['odd']).'</a></div>';
                
                
                $str .= '
                <br class="clear" />
                </div>';
                break;
            default:
                
                
                $str .= '
  <div class="itemListingBLine">
            <div class="itemListingBLDate">'.$this->convertTime($event['Event']['date']).'</div>
            <div class="itemListingBLEventWiner">'.$bet['name'].'</div>
            <div class="itemListingBLWinner">
             ';

            foreach ($bet['BetPart'] as $betPart) {
            $betPart = $betPart['BetPart'];
            if ($betPart['odd'] > 1&&$betPart['name']) {
                
                $str .= 
                '   <div class="itemListingBLWLine">
                            <div class="itemListingBLWTitle">' . $betPart['name'] . '</div>
                            <div class="itemListingBLOdd"><a href="#" title="'.$betPart['id'].'" class="OddsButton">' . $this->convertOdd($betPart['odd']) . '</a></div>
                    </div>
                    ';
            }
            }
                
                
               $str .= '
                   </div>
    <br class="clear" />
    </div>    ';
                break;
        }
        

            
        }
          
        
        $str.= '    <br class="clear" /></div>';
     }
        return $str;

        
    }
    
    function makeBet($bet) {
        if (!isset($bet['BetPart']))
            return '';
        $str = '';
        switch ($bet['type']) {
            case 'Outright':
                $str = $this->__outright($bet);
                break;
            case 'Versus':
            case 'Versus (with Draw)':
                $str = '';
                break;
            default:
                $str = $this->__default($bet);
                break;
        }
        return $str;
    }

    function makeMainBet($event) {
        $str = '';
        if (isset($event['Bet']['Outright']))
            $str = $this->__outrightMain($event);
        else if (isset($event['Bet']['Versus']))
            $str = $this->__versusMain($event);
        else if (isset($event['Bet']['Versus (with Draw)']))
            $str = $this->__versusDrawMain($event);
        else {
            //TODO handle other cases
            $str = $this->__outrightMain($event);
        }
        return $str;
    }

    function __versusMain($event) {
        $betParts = $event['Bet']['Versus']['BetPart'];
        $str = '';
        $str .= '<div class="event-id">' . __('ID: ') . $event['Bet']['Versus']['id'] . '</div>';
        $str .= '<div class="event-holder">';
        $str .= '<div class="event-date">' . $this->convertTime($event['Event']['date']) . '</div>';
        $str .= '<div class="event-more">' . __('more', true) . '</div>';
        $str .= '<div class="event-title">';

        $str .= '<div>';
        $str .= '<div class="right-bet">';
        $str .= '<div class="bet-odd" title="' . $betParts[1]['BetPart']['id'] . '">' . $this->convertOdd($betParts[1]['BetPart']['odd']) . '</div>';
        $str .= '<div class="bet-outright">' . $betParts[1]['BetPart']['name'] . '</div>';
        $str .= '</div>';

        $str .= '<div class="left-bet">';
        $str .= '<div class="bet-odd" title="' . $betParts[0]['BetPart']['id'] . '">' . $this->convertOdd($betParts[0]['BetPart']['odd']) . '</div>';
        $str .= '<div class="bet-outright">' . $betParts[0]['BetPart']['name'] . '</div>';
        $str .= '</div>';
        $str .= '</div>'; //div

        $str .= '</div>'; //event-title
        $str .= '</div>l'; //event-holder


        return $str;
    }

    function __versusDrawMain($event) {
        $betParts = $event['Bet']['Versus (with Draw)']['BetPart'];
        $str = '';
        $str .= '<div class="event-id">' . __('ID: ') . $event['Bet']['Versus (with Draw)']['id'] . '</div>';
        $str .= '<div class="event-holder">';

        $str .= '<div class="event-date">' . $this->convertTime($event['Event']['date']) . '</div>';
        $str .= '<div class="event-more">' . __('more', true) . '</div>';
        $str .= '<div class="event-title event-versus-draw">';
        $str .= '<div>';

        $str .= '<div class="right-bet">';
        $str .= '<div class="bet-odd" title="' . $betParts[2]['BetPart']['id'] . '">' . $this->convertOdd($betParts[2]['BetPart']['odd']) . '</div>';
        $str .= '<div class="bet-outright">' . $betParts[2]['BetPart']['name'] . '</div>';
        $str .= '</div>';

        $str .= '<div class="middle-bet">';
        $str .= '<div class="bet-odd" title="' . $betParts[1]['BetPart']['id'] . '">' . $this->convertOdd($betParts[1]['BetPart']['odd']) . '</div>';
        $str .= '<div class="bet-outright">' . $betParts[1]['BetPart']['name'] . '</div>';
        $str .= '</div>';

        $str .= '<div class="left-bet">';
        $str .= '<div class="bet-odd" title="' . $betParts[0]['BetPart']['id'] . '">' . $this->convertOdd($betParts[0]['BetPart']['odd']) . '</div>';
        $str .= '<div class="bet-outright">' . $betParts[0]['BetPart']['name'] . '</div>';
        $str .= '</div>';

        $str .= '</div>';
        $str .= '</div>';
        $str .= '</div>d';


        return $str;
    }

    function __outrightMain($event) {        
        $bet = array_values($event['Bet']);
        $betId = $bet[0]['id'];
        $str = '';
        $str .= '<div class="event-id">' . __('ID: ') . $betId . '</div>';
        $str .= '<div class="event-holder">';
        $str .= '<div class="event-date">' . $this->convertTime($event['Event']['date']) . '</div>';
        $str .= '<div class="event-more">' . __('more', true) . '</div>';
        $str .= '<div class="event-title">' . $event['Event']['name'] . '</div>';
        $str .= '</div>';
//WTF IS HERE!
        $str = '
       <div class="itemListingBLine">
            <div class="itemListingBLDate">' . $this->convertTime($event['Event']['date']) . '</div>
            <div class="itemListingBLEvent">' . $event['Event']['name'] . '</div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">2.45</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">5.1</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">1.3</a></div>
        <br class="clear" />
        </div>
';
        
        
        return $str;
    }

    function __outright($bet) {
        $str = '';
        //$str .= '<div class="bet_name">' . $bet['name'] . '</div>';
        foreach ($bet['BetPart'] as $betPart) {
            $betPart = $betPart['BetPart'];
            if ($betPart['odd'] > 1) {
                $str .= '<div class="bet-holder">';
                $str .= '<div class="bet-odd" title="' . $betPart['id'] . '">' . $this->convertOdd($betPart['odd']) . '</div>';
                $str .= '<div class="bet-outright">' . $betPart['name'] . '</div>';
                $str .= '</div>';
            }
        }
        
                $str = 'a
       <div class="itemListingBLine">
            <div class="itemListingBLDate">' . $this->convertTime($event['Event']['date']) . '</div>
            <div class="itemListingBLEvent">' . $event['Event']['name'] . '</div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">2.45</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">5.1</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">1.3</a></div>
        <br class="clear" />
        </div>
';
        
        return $str;
    }

    function __default($bet) {
        $str = '';

$str = 'b
       <div class="itemListingBLine">
            <div class="itemListingBLDate">00:15</div>
            <div class="itemListingBLEvent">' . $bet['name'] . '</div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">2.45</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">5.1</a></div>
            <div class="itemListingBLOdd"><a href="#" class="OddsButton">1.3</a></div>
        <br class="clear" />
        </div>
';
/*
        $str .= '<br class="clear" /><div class="bet_name"><h4>' . __('ID: ') . $bet['id'] . ' - ' . $bet['name'] . '</h4></div>';
        foreach ($bet['BetPart'] as $betPart) {
            $betPart = $betPart['BetPart'];
            $str .= '<div class="bet-holder">';
            $str .= '<div class="bet-odd" title="' . $betPart['id'] . '">' . $this->convertOdd($betPart['odd']) . '</div>';
            $str .= '<div class="bet-outright">' . $betPart['name'] . '</div>';
            $str .= '</div>';
        }
        */
        return $str;
    }

}

?>
