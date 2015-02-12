<?php

class BetsController extends AppController {

    public $name = 'Bets';

	public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_add', 'admin_edit','add'));
    }

    
    
    function admin_add($id) {
        $model = $this->__getModel();
        $this->viewPath = 'Bets';
        if (!empty($this->request->data)) {     
            $this->request->data['Bet']['event_id'] = $id;
            //unset empty dfields
            foreach ($this->request->data['BetPart'] as $key => $betPart) {
                if (($betPart['name'] == null) || ($betPart['odd'] <= 1)) {
                    unset($this->request->data['BetPart'][$key]);
                }
            }            
            if ($this->Bet->saveAll($this->request->data)) {
                $this->__setMessage(__('Bet added', true));
                $this->redirect(array('controller' => 'events', 'action' => 'view', $id));
            } else {
                $this->__setError(__('Error adding bet', true));
            }
        }
    }
    
    function admin_edit($id) {
        $model = $this->__getModel();
        $this->viewPath = 'Bets';
        if (!empty($this->request->data)) {     
            $this->request->data['Bet']['id'] = $id;
            if ($this->Bet->saveAll($this->request->data)) {
                $this->__setMessage(__('Bet added', true));
                $this->redirect(array('controller' => 'events', 'action' => 'view', $this->Bet->getParentId($id)));
            } else {
                $this->__setError(__('Error adding bet', true));
            }
        }
        
        $this->request->data = $this->Bet->getItem($id);
        $this->set('data', $this->request->data);
    }

	 function add($id) {
       $model = $this->__getModel();

        $this->viewPath = 'Bets';
        if (!empty($this->request->data)) {     
            $this->request->data['Bet']['event_id'] = $id;
            //unset empty dfields
            foreach ($this->request->data['BetPart'] as $key => $betPart) {
                if (($betPart['name'] == null) || ($betPart['odd'] <= 1)) {
                    unset($this->request->data['BetPart'][$key]);
                }
            }            
            if ($this->Bet->saveAll($this->request->data)) {
                $this->__setMessage(__('Bet added', true));
                $this->redirect(array('controller' => 'events', 'action' => 'view', $id));
            } else {
                $this->__setError(__('Error adding bet', true));
            }
        }
    }
    
}

?>