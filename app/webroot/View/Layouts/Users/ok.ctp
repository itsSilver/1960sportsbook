<div id="registration">
            <div id="regTitle"><?php echo __('Welcome!', true); ?><div id="regClose"></div></div>
            <div id="regCont">
                
                <div id="regWelcome"><p><?php echo __('Your account is created successfully. Please follow instructions sent to your email to <b>activate</b> it. Please check your junk mail if not in inbox',true);?></p></div>
               
  
            </div>
        </div>
        <div id="fade"></div>

<script type="text/javascript">
    jQuery("#regClose").click(
    function(){
        jQuery("#registration").remove();
        jQuery("#fade").remove();
    }
);
</script>