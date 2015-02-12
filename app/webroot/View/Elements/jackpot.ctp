<?php if (Configure::read('Settings.jackpot') == 1): ?>
<?php $pot = $this->requestAction('jackpots/getSize/'); ?> 
<?php if ($pot[0][0]['pot'] > 0): ?>
    <div class="box">
        <div id="jackpot">
            <h3>SPORTS JACKPOT!</h3><!--
            <p>This Month JACKPOT is </p>-->
             <p>  
            </p>
            <?php $pot = round($pot[0][0]['pot'],2) * 1000; $val = $pot;$i=0; ?>
            <div class="counterBox">
                <div style="float: left;font-size: 25px;font-weight: bold;padding-left: 2px;"><?php echo Configure::read('Settings.currency'); ?></div>
                <?php while($val = floor($val / 10)){ $d = $val % 10; $i++; ?>
                <div class="counterItm counter<?php echo $d; ?>"></div>
                <?php                if($i==2){
                ?>
                <div class="counterItm counterDot"></div>
                <?php }
                 } ?>
                
            </div>
            <p>  
            </p>
            <br />
            <p class="centered">
               <?php //echo $this->Html->link(__('This Month Top',true), array('controller' => 'jackpots', 'action'=> 'MonthJackpoters')); ?>
                <a href = "/jackpots/MonthJackpoters" class ="button"> This Month Top</a>
                <br />
                <br />
               <?php //echo $this->Html->link(__('About Jackpot',true), array('controller' => 'pages', 'action'=> 'jackpot')); ?>
               <a href = "/pages/jackpot" class="button">About Jackpot</a>

           </p>
        </div>
    </div>
<?php endif; ?>

<?php endif; ?>

