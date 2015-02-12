<?php
$plugins = $this->requestAction('plugins/getPlugins/' . $position);
if (!empty($plugins)) {
    foreach ($plugins as $plugin) {
        ?>
        <div class="sidebar-box">
        <?php echo $plugin['Plugin']['content']; ?>
        </div>
        <?php
    }
}
?>