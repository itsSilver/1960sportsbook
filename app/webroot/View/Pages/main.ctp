<div id="pages" class="main">
    <?php
    //For Sports Dashboard**********************************************
    if ($showSlider == 1){
        echo $this->element('slider');
    }

    if ($showEvents == 1){
	echo $this->element('events-get_eventupcomming');
    }

    if ($showMostPlayed == 1){
        echo $this->element('events-get_mostplayedmatch');
    }

    if ($showlatestResult == 1){
        echo $this->element('events-get_latestresult');
    }
    if ($showlatestWins == 1){
        //echo $this->element('events-get_latestwins');
    }

    if ($showLastMinuteBets == 1){
       //echo $this->element('last_minute_bets');
    }

    if ($showNews == 1){
        echo $this->element('news');
    }
    

    //For Lottery Dashboard*********************************************
    if ($showlottterySlider == 1){
        echo $this->element('slider');
	echo '<br><br>';
    }
    if ($showLotteryResult == 1){
        echo $this->element('lotterys-get_lotteryresult');
    }
    if ($showPastLotteryResult == 1){
        echo $this->element('lotterys-get_pastlotteryresult');
    }
    ?>

</div>