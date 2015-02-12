<?php

class RisksController extends AppController {

    public $name = 'Risks';
    public $uses = array('Risk', 'Setting', 'Sport', 'League', 'Ticket', 'Deposit', 'Withdraw');

    function admin_index() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }        
        $settings = $this->Setting->getRiskSettings();
        $this->set('settings', $settings);
        $this->set('tabs', $this->Risk->getTabs($this->params));
    }

    function admin_sports() {
        if (!empty($this->request->data)) {
            //save
            if ($this->Sport->updateRisk($this->request->data)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $this->paginate = array(
            'limit' => Configure::read('Settings.itemsPerPage')
        );
        $data = $this->Paginate('Sport');
        $this->set('data', $data);
        $this->set('tabs', $this->Risk->getTabs($this->params));
    }

    function admin_leagues($sportId = null) {
        if (!empty($this->request->data)) {
            //save
            if ($this->League->updateRisk($this->request->data)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $this->paginate = array(
            'limit' => Configure::read('Settings.itemsPerPage')
        );
        if (isset($sportId)) {
            $this->paginate['conditions'] = array(
                'League.sport_id' => $sportId
            );
        }
        $data = $this->Paginate('League');
        $this->set('data', $data);
        $this->set('tabs', $this->Risk->getTabs($this->params));
    }
    
    function admin_warnings() {
        $bigOddTickets = $this->Ticket->getBigOddTickets(Configure::read('Settings.bigOdd'));
        $bigStakeTickets = $this->Ticket->getBigStakeTickets(Configure::read('Settings.bigStake'));
        $bigWinningTickets = $this->Ticket->getBigWinningTickets(Configure::read('Settings.bigWinning'));
        $bigDeposits = $this->Deposit->getBigDeposits(Configure::read('Settings.bigDeposit'));
        $bigWithdraws = $this->Withdraw->getBigWithdraws(Configure::read('Settings.bigWithdraw'));
        
        $this->set(compact('bigOddTickets', 'bigStakeTickets', 'bigWinningTickets', 'bigDeposits', 'bigWithdraws'));
        $this->set('tabs', $this->Risk->getTabs($this->params));
    }

}

?>
