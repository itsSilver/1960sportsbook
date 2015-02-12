<?php
class LogsController extends AppController {
    
    public $name = 'Logs';
    
    public function admin_index() {
        $paginate = array(
            'order' => 'Log.id DESC'
        );
        parent::admin_index($paginate);
    }
    
}
?>
