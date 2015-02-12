<div id="users" class="account">
    <h3><?php echo __('Account information'); ?></h3>
    <h4><?php echo __('Please provide full personal information to confirm your identity. Owing to regulations, your access to some products, markets and commission discounts may be restricted until you confirm your identity. You can provide personal documents using our secure email service.'); ?></h4>

    <?php echo $this->Session->Flash(); ?>
    <?php
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'regi',
            # define error defaults for the form    
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>
    <table class="default-table">		
        <tr>
            <td><label><?php echo __('Username'); ?></label></td>
            <td><?php echo $this->Form->input('username', array('value' => $user['username'], 'disabled' => true)); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('First name'); ?></label></td>
            <td><?php echo $this->Form->input('first_name', array('value' => $user['first_name'], 'disabled' => true)); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Last name'); ?></label></td>
            <td><?php echo $this->Form->input('last_name', array('value' => $user['last_name'], 'disabled' => true)); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Date of birth'); ?></label></td>
            <td><?php echo $this->Form->input('date_of_birth', array('value' => $user['date_of_birth'], 'type' => 'text', 'disabled' => true)); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Address'); ?></label></td>
            <td>
                <?php echo $this->Form->input('address1', array('value' => $user['address1'])); ?>
                <?php echo $this->Form->input('address2', array('value' => $user['address2'])); ?>
            </td>
        </tr>


        <tr>
            <td><label><?php echo __('Zip/Postal code'); ?></label></td>
            <td><?php echo $this->Form->input('zip_code', array('value' => $user['zip_code'])); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('City'); ?></label></td>
            <td><?php echo $this->Form->input('city', array('value' => $user['city'])); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Country'); ?></label></td>
            <td><?php echo $this->Form->input('country', array('value' => $user['country'])); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Mobile number'); ?></label></td>
            <td><?php echo $this->Form->input('mobile_number', array('value' => $user['mobile_number'])); ?></td>
        </tr>
         <tr>
            <td><label><?php echo __('Bank name'); ?></label></td>
            <td><?php echo $this->Form->input('bank_name', array('value' => $user['bank_name'])); ?></td>
        </tr>
        <tr>
            <td><label><?php echo __('Bank sortcode'); ?></label></td>
            <td><?php echo $this->Form->input('bank_code', array('value' => $user['bank_code'])); ?></td>
        </tr>
         <tr>
            <td><label><?php echo __('Account number'); ?></label></td>
            <td><?php echo $this->Form->input('account_number', array('value' => $user['account_number'])); ?></td>
        </tr>
	<tr>
	    <td><label><?php echo __('Referral Code'); ?></label></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;<h4><?php echo $user['id']?></h4></td>
	</tr>
    </table>
    <div class="centered">
        <?php echo $this->MyHtml->spanLink(__('Confirm changes', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserAccountForm').submit()")); ?>
        <?php echo $this->MyHtml->spanLink(__('Change Password', true), array('action' => 'password'), array('class' => 'button-blue')); ?>
        <?php echo $this->Form->end(); ?>
    </div>

</div>