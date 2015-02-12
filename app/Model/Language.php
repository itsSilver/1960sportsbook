<?php

class Language extends AppModel {

    public $name = 'Language';

    function get() {
        $data = $this->find('all');
        foreach ($data as $Language)
            $Languages[$Language['Language']['name']] = $Language['Language'];
        return $Languages;
    }

    //return array(Language => Language);
    function getList() {
        $data = $this->get();
        foreach ($data as $key => $value)
            $Languages[$key] = $key;
        return $Languages;
    }

    //return array(Language => Language);
    function getLanguagesList() {
        $data = $this->get();
        foreach ($data as $key => $value)
            $Languages[$key] = $value['language'];
        return $Languages;
    }

    function getIdLangueageList() {
        $options['fields'] = array(
            'Language.id',
            'Language.language'
        );
        $list = $this->find('list', $options);        
        return $list;
    }

    function getIndex() {
        $options['fields'] = array(
            'Language.id',
            'Language.language'
        );
        $options['conditions'] = array(
            'Language.id <>' => 1
        );
        return $options;
    }

    function getAdd() {
        $i18n = I18n::getInstance();
        $l10n = $i18n->l10n;
        $Languages = $l10n->catalog();
        foreach ($Languages as $key => $value) {
            $LanguagesList[$key] = $value['language'];
        }
        $fields = array(
            'Language.name' => array(
                'label' => __('Language', true),
                'type' => 'select',
                'options' => $LanguagesList
            )
        );
        return $fields;
    }

    function getActions() {
        $actions = parent::getActions();
        unset($actions[0]);
        unset($actions[1]);
        return $actions;
    }

    function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['Languagesadmin_search']);
        return $tabs;
    }

}

?>