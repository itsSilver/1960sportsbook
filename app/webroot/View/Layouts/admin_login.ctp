<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="UTF-8" />
        <?php echo $this->Html->meta('icon', $this->Html->url('/favicon.ico')); ?>
        <title><?php echo $title_for_layout; ?></title>
        
        <?php echo $this->Html->css(array('admin/reset', 'admin/style')); ?>

	<?php echo $this->Html->script(array('jquery-1.6.1.min')); ?>
        
    </head>
    
    <body class="no-background">
        
        <div id="layout-content">
            
            <?php echo $content_for_layout; ?>
            
        </div>
        
    </body>
    
</html>
