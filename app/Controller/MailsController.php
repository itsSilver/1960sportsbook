<?php

class MailsController extends AppController {

    public $name = 'Mails';
    public $uses = array('Mail', 'User');
    // recaptcha plugin
    public $components = array('Recaptcha.Recaptcha');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('contact'));
    }

    function contact() {
        if (!empty($this->request->data)) {
            //send mail;                        
            $this->Mail->set($this->request->data);
            if ($this->Mail->validates()) {
                $to = Configure::read('Settings.contactMail');
                $subject = $this->request->data['Mail']['subject'];
                $content = $this->request->data['Mail']['content'];
                $name = $this->request->data['Mail']['name'];
                $email = $this->request->data['Mail']['email'];

                $this->Email->to = $to;
                $this->Email->subject = $subject;
                //TODO get from config
                $this->Email->replyTo = $email;
                //$this->Email->from = Configure::read('Settings.websiteName') . '' . __('contact form', true);
                $this->Email->from = Configure::read('Settings.websiteName') . '<' . $to . '>';
                $this->Email->template = 'contact';
                $this->Email->sendAs = 'both';

                $this->set('message', $content);
                $this->set('subject', $subject);
                $this->set('name', $name);
                $this->set('email', $email);

                if ($this->Recaptcha->verify()) {
                    if ($this->Email->send()) {
                        $this->__setMessage(__('Email successfully sent', true));
                    } else {
                        $this->__setError(__('can\'t  send email', true));
                    }
                } else {
                    $this->__setError($this->Recaptcha->error);
                }
            }
            $this->request->data = array();
        }
    }

    function admin_index() {
        if (!empty($this->request->data)) {
            //send mail
            $to = $this->request->data['Mail']['to'];
            $bcc = preg_split('/[;,]/', $to);
            $subject = $this->request->data['Mail']['subject'];
            $content = $this->request->data['Mail']['content'];
            $this->__send('example@example.com', $subject, $content, $bcc);
            $this->__setMessage(__('Emails successfully sent', true));
            $this->request->data = array();
        }
        $this->set('tabs', $this->Mail->getTabs($this->params));
    }

    function admin_all() {
        if (!empty($this->request->data)) {
            //get all mails
            $bcc = $this->User->getAllEmails();
            App::uses('Validation', 'Utility');
            foreach ($bcc as $key => $to) {
                if (!Validation::email($to)) {
                    unset($bcc[$key]);
                }
            }
            $subject = $this->request->data['Mail']['subject'];
            $content = $this->request->data['Mail']['content'];
            $this->__send('example@example.com', $subject, $content, $bcc);
            $this->__setMessage(__('Emails successfully sent'));
            $this->request->data = array();
        }
        $this->set('tabs', $this->Mail->getTabs($this->params));
    }

}

?>
