<?php

class LeaguesController extends AppController {

    public $name = 'Leagues';
    

    function admin_addLeague($sportId = NULL) {
        if (!empty($this->request->data)) {
            //add more data
            if ($sportId != NULL)
                $this->request->data['League']['sport_id'] = $sportId;
        }
        $this->admin_add();
        $this->request->data['League']['sport_id'] = $sportId;
        $this->view = 'admin_add';
        //$this->render('admin_add');
    }

    function admin_view($id = NULL) {
        $model = $this->League->getItem($id);
        if ($model != NULL) {
            $this->paginate['conditions'] = array(
                'Event.league_id' => $id
            );
            $this->paginate['recursive'] = -1;
            $data = $this->paginate('Event');            
            $this->set('data', $data);
            $this->set('model', $model);
        }            
    }
    
}

?>
