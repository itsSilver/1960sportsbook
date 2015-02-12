<div id="mails" class="contact">
    <h3><?php echo __('Contact support team'); ?></h3>

    <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-size: 8.0pt; font-family: 'Verdana','sans-serif';">If you have any problem, do not hesitate to contact the customer service using any of these listed media:</span></p>
    <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-family: Verdana, sans-serif; font-size: 8pt;">Customer Service Number: Coming Soon!.</span><br />
    <span style="font-family: Verdana, sans-serif; font-size: 8pt;">Online Ticket Support System: </span><a style="color: green;" href="http://www.support.1960sportsbook.com/" target="_blank">www.support.1960sportsbook.com</a></p>
    <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-family: Verdana, sans-serif; font-size: 8pt;">Please like us on <img src="http://www.chalkpro.com/images/socialnetworks/FaceBook.png" alt="" width="16" height="16" /><a style="color: green;" href="http://www.facebook.com/1960sportsbook" target="_blank">Facebook</a> and follow us on <img src="http://www.chalkpro.com/images/socialnetworks/Twitter.png" alt="" width="16" height="16" /><a style="color: green;" href="http://twitter.com/1960sportsbook" target="_blank">Twitter</a>.</span></p>
       
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->Form->create('Mail', array('inputDefaults' => array('div' => false, 'label' => false))); ?>
    <table class="default-table contact">
        <tr>
            <td><?php echo __('Subject'); ?></td>
            <td><?php echo $this->Form->input('subject', array('class' => 'regi')); ?></td>
        </tr>
        <tr>
            <td><?php echo __('Name'); ?></td>
            <td>
                <?php echo $this->Form->input('name', array('class' => 'regi')); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Email'); ?></td>
            <td><?php echo $this->Form->input('email', array('class' => 'regi')); ?></td>
        </tr>
        <tr>
            <td><?php echo __('Message'); ?></td>
            <td><?php echo $this->Form->input('content', array('class' => 'regi', 'type' => 'textarea')); ?></td>
        </tr>
        <tr>
        	<td><?php echo __('Captcha'); ?></td>
        	<td><?php echo $this->Recaptcha->display(); ?></td>
        </tr>        
    </table>
    <div class="centered">
        <?php echo $this->Form->submit(__('Send', true), array('class' => 'button')); ?>
    </div>
    <?php echo $this->Form->end(); ?>	
</div>