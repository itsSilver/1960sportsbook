<div id="deposits">

    <h3><?php echo __('Deposit'); ?></h3>

    <?php
    if (Configure::read('Settings.D_Manual')) {
        // echo '<p>' . $this->Html->link(__('Manual'), array('action' => 'deposit')) . '</p>';
        echo '<div class="depositTypeBox">';
        echo $this->Html->image('bank.gif', array('width' => '103px', 'url' => array('controller' => 'deposits', 'action' => 'deposit')));
        echo '<span style="padding-top: 20px;" class="depositTypeTitle">' . __('Bank Transfer Payment') . '</span>';
        echo '<br class="clear" />';
        echo '</div>';
    }
    
    if (Configure::read('Settings.D_VoguePay')) {
        echo '<div class="depositTypeBox">';
        echo $this->Html->image('voguepay.png', array('width' => '103px', 'url' => array('controller' => 'deposits', 'action' => 'voguepay')));
        echo '<span style="padding-top: 20px;" class="depositTypeTitle">' . __('VoguePay Credit or Debit Payment') . '</span>';
        echo '<br class="clear" />';
        echo '</div>';
    }

    if (Configure::read('Settings.D_Vtn')) {
        //  echo '<p>' . $this->Html->link(__('VTN'), array('action' => 'vtn')). '</p>';
        echo '<div class="depositTypeBox">';
        echo $this->Html->image('vtn.png', array('width' => '103px', 'url' => array('controller' => 'deposits', 'action' => 'vtn')));
        echo '<span style="padding-top: 8px;" class="depositTypeTitle">' . __('VTN') . '</span>';
        echo '<br class="clear" />';
        echo '</div>';
    }

    if (Configure::read('Settings.D_Umf')) {
        echo
        '<div class="depositTypeBox">
            <a href="' . $this->Html->url(array('action' => 'umf')) . '">
            <img src="/img/admin/payments/UMF.png" alt="umf" />
            <span class="depositTypeTitle">' . __('UMF Payment', true) . '</span></a></div>';
    }

    if (Configure::read('Settings.D_Eyowo')) {
        //echo '<p>' . $this->Html->link(__('Eyowo'), array('action' => 'eyowo')). '</p>';
        echo '<div class="depositTypeBox">';
        echo $this->Html->image('eyowo.png', array('width' => '103px', 'url' => array('controller' => 'deposits', 'action' => 'eyowo')));
        echo '<span style="padding-top: 8px;" class="depositTypeTitle">' . __('Eyowo Credit or Debit Card Payment') . '</span>';
        echo '<br class="clear" />';
        echo '</div>';
    }

    if (Configure::read('Settings.D_Bardo')) {

        echo
        '<div class="depositTypeBox">
            <a href="' . $this->Html->url(array('action' => 'bardo')) . '">
            <img src="/img/admin/payments/BARDO.gif" alt="bardo" />
            <span class="depositTypeTitle">' . __('Credit Card Payment', true) . '</span></a></div>';
    }
    ?>  
    <br class="clear" />
</div>