<?php

class MyHtmlHelper extends HtmlHelper {

    var $acos = array();
    var $helpers = array('Html', 'Session');

    function customLink($name, $url) {
        if (preg_match('/^http:/', $url)) {
            return $this->Html->link($name, $url);
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->Html->link($name, array('controller' => $parts[0], 'action' => $parts[1], $parts[2]));
        } else {
            return $this->Html->link($name, array('controller' => 'pages', 'action' => $url));
        }
    }

    function customUrl($url) {
        if (preg_match('/^http:/', $url)) {
            return $url;
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->Html->url(array('controller' => $parts[0], 'action' => $parts[1], $parts[2]), true);
        } else {
            return $this->Html->url(array('controller' => 'pages', 'action' => $url), true);
        }
    }

    function link($title, $url = null, $options = array(), $confirmMessage = false) {


        $acos = $this->checkAcl($url);

        if ($acos) {
            $link = parent::link($title, $url, $options, $confirmMessage);
        } else if (isset($options['returnText'])) {
            $link = $title;
        } else {
            $link = "";
        }

        return $link;
    }

    function spanLink($title, $url = null, $options = array(), $confirmMessage = false) {
        $options['escape'] = false;
        return parent::link('<span>' . $title . '</span>', $url, $options, $confirmMessage);
    }

    function checkAcl($url = NULL) {

        $permissions = $this->Session->read('permissions');
        if (isset($permissions['controllers'])) {
            return true;
        }
        $aco = 'controllers/' . $url['controller'];
        if (isset($permissions[$aco])) {
            return true;
        }
        if (isset($url['action'])) {
            $aco .= '/' . $this->request->params['prefix'] . '_' . $url['action'];
            if (isset($permissions[$aco])) {
                return true;
            }
        }
        

        //prevent old logic
        return false;

        //return true;
        App::uses('AclComponent', 'Controller/Component');
        App::uses('SessionComponent', 'Controller/Component');

        $this->Acl = new AclComponent(new ComponentCollection());
        $this->Session = new SessionComponent(new ComponentCollection());

        $acoExists = true;
        $foreign_key = $this->Session->read('Auth.User.group_id');

        if (isset($url['controller']))
            $controller = $url['controller'];
        else
            $controller = $this->params['controller'];
        $controller = Inflector::camelize($controller);
        $action = $url['action'];
        if (isset($this->params['admin']) && ($this->params['admin'] == 1))
            $action = 'admin_' . $action;

        $acos = $this->Session->read('Auth.Acos');

        if (!isset($acos[$controller][$action])) {
            $controllerAco = $this->Acl->Aco->find('first', array('recursive' => 0, 'conditions' => array('parent_id' => 1, 'alias' => $controller)));
            if ($controllerAco['Aco']['id']) {
                $cid = $controllerAco['Aco']['id'];
                $actionAco = $this->Acl->Aco->find('count', array('recursive' => 0, 'conditions' => array('parent_id' => $cid, 'alias' => $action)));
                if ($actionAco < 1) {
                    $acoExists = false;
                }
            } else
                $acoExists = false;
            if (($acoExists && $this->Acl->check(array('model' => 'Group', 'foreign_key' => $foreign_key), 'controllers/' . $controller . '/' . $action)) || (!$acoExists)) {
                $acos[$controller][$action] = true;
            } else {
                $acos[$controller][$action] = false;
            }
            $this->Session->write('Auth.Acos', $acos);
        }
        return $acos[$controller][$action];
    }

    function checkAcls($urls = array()) {
        foreach ($urls as $url) {
            $u = array('controller' => $url[0], 'action' => $url[1]);
            if ($this->checkAcl($u)) {
                return true;
            }
        }
        return false;
    }

}

?>