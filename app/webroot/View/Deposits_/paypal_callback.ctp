<h2>
<?php echo __("Confirm your order")?>
</h2>
<div>
<?php echo __("Now the last step to confirm your order to transfer")?> <?php echo $customer_details['AMT'];?> <?php echo __("USD to Your account"); ?> 
<form method="get" action="/demo/deposits/complete_express_checkout/<?php echo $token?>/<?php echo $payerId;?>/<?php echo $customer_details['AMT'];?>">
<input type="submit" name="submit" value="Confirm your payment" class="button" />
</form>

</div>