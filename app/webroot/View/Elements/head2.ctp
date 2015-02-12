
<meta name="verify-webtopay" content="90d888a7029def4d923a93aaec715262">
<title><?php echo Configure::read('Settings.defaultTitle'); ?></title>

<meta charset="<?php echo Configure::read('Settings.charset'); ?>"> 
<?php echo $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
<?php echo $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
<?php echo $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
<?php echo $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
<?php echo $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
<?php echo $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
<?php echo $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>

<?php echo $this->Html->meta('icon', $this->Html->url('/favicon.ico')); ?>

<?php echo $this->Html->css(array('reset', 'style', 'jquery-ui-1.8.23.custom')); ?>

<?php echo $this->Html->script(array('jquery-1.8.0.min', 'jquery-ui-1.8.23.custom.min', 'jquery.cycle.all.min', 'admin/jquery.jclock',)); ?>
<style>
    .ui-tabs-hide {
        display: none;
    }
</style>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {                
        $('#UserUsername').blur(function() {
            if ($('#UserUsername').val() == '')
                $('#UserUsername').val('<?php echo __('Username'); ?>');
        });
        $('#UserUsername').focus(function () {                    
            if ($('#UserUsername').val() == '<?php echo __('Username'); ?>')
            $('#UserUsername').val('');
        });
        $('#UserPassword').blur(function() {
            if ($('#UserPassword').val() == '')
                $('#UserPassword').val('<?php echo __('Password'); ?>');
        });
        $('#UserPassword').focus(function() {
            if ($('#UserPassword').val() == '<?php echo __('Password'); ?>')
            $('#UserPassword').val('');
        });          

        getBets();
        
        
            $( "#tabs" ).tabs();
        

    }
    );
    function getBets() {
        showLoading();                
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'getBets')); ?>', function() {
            hideLoading();                    
        });
    }
    function addBet(betPartId) {                
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'addBet')); ?>/' + betPartId, function() {
            hideLoading();
        });
    }
    function removeBet(betPartId) {
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'removeBet')); ?>/' + betPartId, function() {
            hideLoading();
        });
    }
    function removeAll() {
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'removeBets')); ?>', function() {
            hideLoading();
        });
    }
    function place() {                
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'place')); ?>', function() {
            hideLoading();
        });                
    }
    function setStake() {
        var stake = jQuery('#total-stake').val();                
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'setStake')); ?>/' + stake, function() {
            hideLoading();
        });
    }
    function setStake(stakeId) {
        if (stakeId == undefined){
            var stake = jQuery('#total-stake').val();    
            stakeId = 0;
        } else {
            var stake = jQuery('#stake-' + stakeId).val();
        }                
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'setStake')); ?>/' + stake + '/' + stakeId, function() {
            hideLoading();
        });
    }
    function setType(type) {                               
        showLoading();
        jQuery('#betslip').load('<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'setType')); ?>/' + type, function() {
            hideLoading();
        });
    }
    function setReturn() {
        var winning = jQuery('#total-stake').val() * jQuery('#decimalOdds').html();                
        jQuery('#betOutcome').html(winning + ' <?php echo Configure::read('Settings.currency'); ?>');
    }
    showLoading = function() {
        jQuery('.load').show();
    }
    hideLoading = function() {
        jQuery('.load').hide();
    }
    
    function registrationForm(){
        jQuery.ajax({
  url: "<?php echo $this->Html->url(array(    "controller" => "users",    "action" => "register")); ?>",
  success: function(html){
    jQuery(document.body).append(html);
  }
});
    }
    
</script>
