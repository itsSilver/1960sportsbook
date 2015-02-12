<div id="registration">
            <div id="regTitle"><?php echo __('Open account with Us!', true); ?><div id="regClose"></div></div>
            <div id="regCont">
                <?php
                echo $this->Form->create('User', array(        'inputDefaults' => array(            'label' => false,            'div' => false        )    ));
                ?>
                <!--
                <div id="regWelcome"><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p></div>
                -->
             <div class="regFormBox regFormBoxLeft">
                 <div class="regFormBoxTitle"><?php echo __('Personal Data'); ?></div>
                 <div class="regFormBoxCont">
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('First name', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('first_name'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Last name', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('last_name'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Address', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('address1'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle">&nbsp;</div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('address2'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Zip/Postal code', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('zip_code'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('City', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('city'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Country', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('country'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Date of birth:', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php
    echo $this->Form->day('date_of_birth');

    echo $this->Form->month('date_of_birth');

    echo $this->Form->year('date_of_birth', 1920, date('Y')); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('E-mail', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('email'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Mobile number', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('mobile_number'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <br class="clear" />
                 </div>
             </div>
             <div class="regFormBox regFormBoxRight">
                 <div class="regFormBoxTitle"><?php echo __('Account Data'); ?></div>
                 <div class="regFormBoxCont">
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Username', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('username'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Password', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('password'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Confirm password', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php //echo $this->Form->input('password_confirm', array('type' => 'password')); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Personal question', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('personal_question'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Personal answer', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('personal_answer'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Referal ID', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('referal_id', array('type' => 'text') ); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <br class="clear" />
                 </div>
             </div>
                
              <div class="regFormBox regFormBoxRight">
                 <div class="regFormBoxTitle"><?php echo __('Bank data'); ?></div>
                 <div class="regFormBoxCont">
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Bank name', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('bank_name'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Bank code', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('bank_code'); ?> </div>
                         <div class="regFormLineStatus"></div>
                </div> 
                     <div class="regFormLine">
                         <div class="regFormLineTitle"><?php echo __('Bank account number', true) ?></div>
                         <div class="regFormLineHelp"></div>
                         <div class="regFormLineInput"><?php echo $this->Form->input('account_number'); ?></div>
                         <div class="regFormLineStatus"></div>
                     </div>
                 </div>
              </div>
 
              <div class="regFormBox regFormBoxLeft">
                 <div class="regFormBoxTitle"><?php echo __('Validation'); ?></div>
                 <div class="regFormBoxCont">
                     <div class="regFormLine">
                	<?php if (isset($captcha_error)):?>
	<div class="error required">
		<?php echo $this->Recaptcha->display(); ?>
		<?php echo __('Please enter valid captcha code');?>
	</div>
	<?php else: ?>
		<?php echo $this->Recaptcha->display(); ?>
	<?php endif;?>
                     </div>
                 </div>
              </div>
   

    <div class="regFormSpacer"></div>
    <div class="regFormBottomBox">
        <div class="regFormBottomBoxAgree">
        <?php echo __('I am over 18 years of age and have read and accepted the general terms and conditions.', true); ?>
        <?php echo $this->Form->input('agree', array('label' => false, 'type' => 'checkbox', 'div' => false)); ?>
        </div>

        <?php echo $this->MyHtml->spanLink(__('Register', true), '#', array('class' => 'openAccount')); ?>
     <br class="clear" />          
    </div>
    <div class="regFormSpacer"></div>
            <?php echo $this->Form->end(); ?>
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
    
                jQuery(".openAccount").click(
                        function(event){
                            
                        jQuery.ajax({
                           type: "POST",
                           url: '<?php echo $this->Html->url(array(    "controller" => "users",    "action" => "register")); ?>',
                           data: jQuery("#UserRegisterForm").serialize(), // serializes the form's elements.
                           success: function(html)
                           {
                                jQuery("#registration").remove();
                                jQuery("#fade").remove();
                               jQuery(document.body).append(html);
                           }
                         });
                        event.preventDefault();

                        }
                );

</script>