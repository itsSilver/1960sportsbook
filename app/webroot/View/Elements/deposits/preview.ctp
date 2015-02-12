<table class="default-table">
    <tr>
        <td><?php echo __('User'); ?></td>
        <td><?php echo $this->Session->read('Auth.User.username'); ?></td>
    </tr>

    <tr>
        <td><?php echo __('Deposit type'); ?></td>
        <td><?php echo $type; ?></td>
    </tr>

    <tr>
        <td><?php echo __('Amount'); ?></td>
        <td><?php echo $amount; ?> <?php echo Configure::read('Settings.currency'); ?></td>
    </tr>
    
    <?php if (isset($DepositBonusUsed)):?>
    <tr>
        <td><?php echo __('Bonus type'); ?></td>
        <td><?php echo $DepositBonusName ?></td>
    </tr>
    
    <tr>
        <td><?php echo __('Bonus valid until'); ?></td>
        <td><?php echo $DepositBonuxValitUntil;?></td>
    </tr>
    <tr>
       <td><?php echo __('Bonus used');?></td>
       <td><?php echo $DepositBonusUsed."/".$DepositBonusMax ?></td>
       
    </tr>
    
    <tr>
       <td><?php echo __('Bonus amount to your account');?></td>
       <td><?php echo $DepositBonusPriemium ?> <?php echo Configure::read('Settings.currency'); ?></td>
       
    </tr>
    
    
    <?php endif;?>
</table>