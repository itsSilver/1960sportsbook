<?php

class SlidesController extends AppController {

    public $name = 'Slides';
    

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getSlides'));
    }

    function admin_getSlides() {
        $this->getSlides();
    }

    function getSlides() {
		$this->layout = $this->Session->read('dashboard_type_user');			
		$this->set('slides', $this->Slide->getSlides($this->layout));
		$this->layout = 'ajax';		
		$this->view = 'get_slides';		
    }

	function admin_add() {

        //handle upload and set data
        if (!empty($this->request->data)) {
            $image = array($this->request->data['Slide']['image']);
            $imagesUrls = $this->__uploadFiles('img/slides', $image);
            if (array_key_exists('urls', $imagesUrls)) {
                $this->request->data['Slide']['image'] = $imagesUrls['urls'][0];
            } else {
                $this->__setError($imagesUrls['errors'][0]);
                $this->request->data['Slide']['image'] = '';
            }
        }
        parent::admin_add();
    }

    function admin_edit($id) {

        //handle upload and set data
        if (!empty($this->request->data)) {
            
            $image = array($this->request->data['Slide']['image']);
            
            if ($image[0]['error'] == 0) {
                $imagesUrls = $this->__uploadFiles('img/slides', $image);
                if (array_key_exists('urls', $imagesUrls)) {
                    $this->request->data['Slide']['image'] = $imagesUrls['urls'][0];
                } else {
                    $this->__setError($imagesUrls['errors'][0]);
                    $this->request->data['Slide']['image'] = '';
                }
            } else {
                $slide = $this->Slide->getItem($id);
                $this->request->data['Slide']['image'] = $slide['Slide']['image'];
            }
        }
        parent::admin_edit($id);
    }

}

?>