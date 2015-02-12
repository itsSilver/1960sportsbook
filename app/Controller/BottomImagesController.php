<?php

class BottomImagesController extends AppController {

    public $name = 'BottomImages';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getImages'));
    }

    function admin_getImages() {
        $this->getSlides();
    }

    function getImages() {
        return $this->BottomImage->getImages();
    }

    function admin_add() {

        //handle upload and set data
        if (!empty($this->request->data)) {
            $image = array($this->request->data['BottomImage']['image']);
            $imagesUrls = $this->__uploadFiles('img/bottom', $image);
            if (array_key_exists('urls', $imagesUrls)) {
                $this->request->data['BottomImage']['image'] = $imagesUrls['urls'][0];
            } else {
                $this->__setError($imagesUrls['errors'][0]);
                $this->request->data['BottomImage']['image'] = '';
            }
        }
        parent::admin_add();
    }
    
    function admin_edit($id) {

        //handle upload and set data
        if (!empty($this->request->data)) {
            
            $image = array($this->request->data['BottomImage']['image']);
            
            if ($image[0]['error'] == 0) {
                $imagesUrls = $this->__uploadFiles('img/bottom', $image);
                if (array_key_exists('urls', $imagesUrls)) {
                    $this->request->data['BottomImage']['image'] = $imagesUrls['urls'][0];
                } else {
                    $this->__setError($imagesUrls['errors'][0]);
                    $this->request->data['BottomImage']['image'] = '';
                }
            } else {
                $slide = $this->BottomImage->getItem($id);
                $this->request->data['BottomImage']['image'] = $slide['BottomImage']['image'];
            }
        }
        parent::admin_edit($id);
    }

}

?>
