<?php

class LanguagesController extends AppController {

    public $name = 'Languages';
    public $uses = array('Language', 'MyI18n');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getLanguages', 'setLanguage'));
    }

    function getLanguages() {
        return $this->Language->get();
    }

    function setLanguage($LanguageId) {
        $Language = $this->Language->findById($LanguageId);
        if (isset($Language)) {
            if ($this->Session->read('Auth.User.id')) {
                $this->Session->write('Auth.User.Language_id', $Language['Language']['id']);
            } else {
                $this->Cookie->write('language', $Language['Language']['name'], $encrypt = false, $expires = null);
            }
        }
        $this->redirect($this->referer());
    }

    function admin_add() {

        $i18n = I18n::getInstance();
        $l10n = $i18n->l10n;
        $Languages = $l10n->catalog();

        foreach ($Languages as $key => $value) {
            $LanguagesList[$key] = $value['language'];
        }

        if (!empty($this->request->data)) {
            if (1 == 2) {
                //add new Language
                $LanguageId = $this->request->data['Language']['name'];

                if (!$this->Language->findByName($Languages[$LanguageId]['locale'])) {

                    $this->request->data['Language']['name'] = $Languages[$LanguageId]['locale'];
                    $this->request->data['Language']['language'] = $Languages[$LanguageId]['language'];
                    $this->request->data['Language']['LanguageFallback'] = $Languages[$LanguageId]['localeFallback'];
                } else {
                    $this->request->data = array();
                    $this->__setError(__('Language already exist', true));
                }
            } else {
                $this->__setError('Please contact ChalkPro technical support team for additional languages');
                $this->request->data = array();
            }
        }
        parent::admin_add();
    }

    function admin_delete($id) {
        $Language = $this->Language->getItem($id);
        $this->MyI18n->deleteAll($Language['Language']['name']);

        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->__setMessage(__('Item deleted', true));
            //$this->redirect(array('action' => 'index'));
        } else {
            $this->__setError(__('can\'t delete item', true));
        }
        $this->redirect($this->referer(array('action' => 'index')));
    }

}

?>
