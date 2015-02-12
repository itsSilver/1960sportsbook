<h3><?php echo __('Make Deposit'); ?></h3>

<?php echo $this->element('deposits/preview'); ?>

<form method="get" action="https://payment.bardo-secured.com/bps/process_tx.asp"> 
    <input type="text" name="SHOP_ID" value="<?php echo Configure::read('Settings.D_BardoShopId'); ?>" /> 
    <input type="text" name="SHOP_NUMBER" value="asdf234asdf"> 
    <input type="text" name="CUSTOMER_LAST_NAME" value="LASTNAME"> 
    <input type="text" name="CUSTOMER_FIRST_NAME" value="FISRTNAME"> 
    <input type="text" name="CUSTOMER_EMAIL" value="email@email.com"> 
    <input type="text" name="CUSTOMER_ADRESS" value="ADDRESS"> 
    <input type="text" name="CUSTOMER_CITY" value="CITY"> 
    <input type="text" name="CUSTOMER_ZIP_CODE" value="ZIPCODE"> 
    <input type="text" name="CUSTOMER_STATE" value="UT"> 
    <input type="text" name="CUSTOMER_COUNTRY" value="US"> 
    <input type="text" name="CUSTOMER_PHONE" value="1234567890"> 
    <input type="text" name="CUSTOMER_IP" value="123.45.67.9"> 
    <input type="text" name="PRODUCT_NAME" value="PRODUCTNAME"> 
    <input type="text" name="TRANSAC_AMOUNT" value="100"> 
    <input type="text" name="CURRENCY_CODE" value="EUR"> 
    <input type="text" name="CB_TYPE" value="V"> 
    <input type="text" name="CB_NUMBER" value="4015504397328242"> 
    <input type="text" name="CB_MONTH" value="12"> 
    <input type="text" name="CB_YEAR" value="05"> 
    <input type="text" name="CB_CVC" value="123"> 
    <input type="text" name="LANGUAGE_CODE" value="ENG"> 
    
    <div class="lefted">      
        <?php echo $this->Form->submit(__('Continue'), array('class' => 'button')); ?>
    </div>
    
</form>