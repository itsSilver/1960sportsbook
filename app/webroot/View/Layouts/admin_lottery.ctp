<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8" />
        <meta name="description" content="Admin panel" />

        <title><?php echo $title_for_layout; ?></title>

        <?php echo $this->Html->css(array('admin/reset', 'admin/style', 'admin/jquery-ui','admin/token-input')); ?>     
	
        <?php echo $this->Html->script(array('jquery-1.6.1.min', 'admin/jquery-ui-1.8.14.custom.min', 'admin/jquery-ui-timepicker', 'admin/jquery.jclock', 'tiny_mce/tiny_mce', 'admin/custom', 'admin/jquery.tokeninput')); ?>

        <?php echo $this->element('clock'); ?>
        <?php echo $this->Html->meta('icon', $this->Html->url('/favicon.ico')); ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){    
                jQuery(".flexy_datetimepicker, .flexy_datetimepicker_input").datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    timeFormat: 'h:m',
                    showOn: "button",
                    buttonImage: "<?php echo $this->Html->url('/') . IMAGES_URL . 'calendar.gif'; ?>",  
                    buttonImageOnly: true,
                    yearRange: 'c-80:c+0',
                    dateFormat: 'yy-mm-dd'
                });
                jQuery(".flexy_datepicker, .flexy_datepicker_input").datepicker({
                    changeMonth: true,
                    changeYear: true,    
                    showOn: "button",
                    buttonImage: "<?php echo $this->Html->url('/') . IMAGES_URL . 'calendar.gif'; ?>",  
                    buttonImageOnly: true,    
                    yearRange: 'c-80:c+0',
                    dateFormat: 'yy-mm-dd'
                });

		jQuery("#select_lottery_time").datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    timeFormat: 'h:m',
                    showOn: "button",
                    buttonImage: "<?php echo $this->Html->url('/') . IMAGES_URL . 'calendar.gif'; ?>",  
                    buttonImageOnly: true,
                    yearRange: 'c-80:c+0',
                    dateFormat: 'yy-mm-dd'
                }); 
    
            });
            tinyMCE.init({
                theme : "advanced",
                mode : "specific_textareas",            
                editor_deselector : "mceNoEditor",
                theme_advanced_buttons3_add : "fullpage",
                relative_urls : false,
                remove_script_host : false,
                convert_urls : false
            });
            function showTicket() {
                var ticketId = jQuery('#TicketId').val();
                window.location = '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'view'), true); ?>' + '/' + ticketId;
            }
            function showEvent() {
                var ticketId = jQuery('#EventId').val();
                window.location = '<?php echo $this->Html->url(array('controller' => 'events', 'action' => 'view'), true); ?>' + '/' + ticketId;
            }
        </script>

    </head>

    <body>

        <div id="layout-content" class="group">
	    
	    <?php $this->groupid = $this->Session->read('Auth.User.group_id');?>

            <?php echo $this->element('admin/user_menu'); ?>

            <div id="layout-aside">	        
                <?php echo $this->element('admin/branding'); ?>		
                <?php echo $this->element('admin/sidebar_lottery');?>               
            </div>

            <div id="layout-main">
                <div id="main">

                    <?php echo $this->element('admin/title'); ?>
		    <?php if(isset($this->groupid) && $this->groupid !='8') { ?>
                    <?php echo $this->element('admin/tabs'); ?>
		    <?php } ?>

		    <div id="content">

                        <?php echo $this->Session->flash(); ?>
                        <?php echo $content_for_layout; ?>
                    </div>

                </div>
            </div>

        </div>

    </body>

</html>