<div id="deposits">
    
    <h3><?php echo __('Deposit'); ?></h3>
    
    <?php
    if (Configure::read('Settings.D_Manual')) {
        echo '<p>' . $this->Html->link(__('Manual'), array('action' => 'deposit')) . '</p>';
    }

    if (Configure::read('Settings.D_Vtn')) {
        echo '<p>' . $this->Html->link(__('VTN'), array('action' => 'vtn')). '</p>';
    }

    if (Configure::read('Settings.D_Umf')) {
        echo '<p>' . $this->Html->link(__('UMF'), array('action' => 'umf')). '</p>';
    }

    if (Configure::read('Settings.D_Eyowo')) {
        echo '<p>' . $this->Html->link(__('Eyowo'), array('action' => 'eyowo')). '</p>';
    }

    if (Configure::read('Settings.D_Bardo')) {
        echo '<p>' . $this->Html->link(__('Bardo'), array('action' => 'bardo')). '</p>';
    }
    if (Configure::read('Settings.lr_merchantEmail')) {
    	echo '<p>' . $this->Html->link(__('Liberty Reserve'), array('action' => 'libertyr')). '</p>';
    }
    if (Configure::read('Settings.wm_pursue')) {
    	echo '<p>' . $this->Html->link(__('Webmoney'), array('action' => 'webmoney')). '</p>';
    }
    if (Configure::read('Settings.paypal_email')) {
    	echo '<p>' . $this->Html->link(__('Paypal'), array('action' => 'paypal')). '</p>';
    }
    ?>  

</div>