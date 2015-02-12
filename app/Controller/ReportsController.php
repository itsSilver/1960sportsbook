<?php

class ReportsController extends AppController {

    public $name = 'Reports';
    public $uses = array('Report', 'User', 'Ticket', 'Deposit', 'Withdraw','JackpotWinning','Jackpot');
    public $components = array('BetApi');

    public function admin_userReport($userId = 0) {
        //debug($this->Session->read('permissions'));
        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }
        list($from, $to) = $this->_getDataRange();
        if (isset($from)) {
            $report[0] = array();
            $tickets = $this->Ticket->getReport($from, $to, $userId);
            unset($tickets['header']);
            $report[0]['header'] = array('User ID', 'Username', 'Ticket count', 'Total', 'Total payout', 'Profit');
            $user = $this->User->getItem($userId);
            $data['userId'] = $userId;
            $data['username'] = $user['User']['username'];
            $data['ticketsCount'] = count($tickets);
            $data['total'] = 0;
            $data['won'] = 0;
            $data['pending'] = 0;
            foreach ($tickets as $ticket) {
                if ($ticket['Ticket']['status'] != -2) { //nott canceled
                    $data['total'] += $ticket['Ticket']['amount'];
                }
                if ($ticket['Ticket']['status'] == 0) {
                    $data['pending'] += $ticket['Ticket']['amount'];
                } else if ($ticket['Ticket']['status'] == 1) {
                    $data['won'] += $ticket['Ticket']['return'];
                    //$data['lost'] += $ticket['Ticket']['amount'];
                } else if ($ticket['Ticket']['status'] == -1) {
                    //$data['lost'] += $ticket['Ticket']['amount'];
                }
            }
            $data['profit'] = $data['total'] - $data['pending'] - $data['won'];
            unset($data['pending']);

            $this->set('data', $data);

            //save if needed
            $report[0]['data'][] = $data;
            if (isset($this->request->data['Download']['download'])) {
                $this->_exportAsCSV($report, 'user-' . $userId, $from, $to);
            }
        }
    }

    public function admin_operatorsReport() {
        $this->admin_groupReport(6);
    }

    public function admin_usersReport() {
        $this->admin_groupReport(1);
    }

    public function admin_adminsReport() {
        $this->admin_groupReport(2);
    }

    public function admin_groupReport($groupId = 1) {        
        list($from, $to) = $this->_getDataRange();
        if (isset($from)) {
            $data[] = $this->Ticket->getReportByGroupId($from, $to, $groupId);
            if (isset($this->request->data['Download']['download'])) {
                $this->_exportAsCSV($data, 'report', $from, $to);
            }
            $this->set('data', $data);
        }        
        
        $this->view = 'admin_report';
    }

    private function _getProfitReport() {
        
    }

    private function _getDataRange() {
        $from = $to = null;
        if (!empty($this->request->data['Report'])) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
            $this->request->data['Download']['from'] = $from;
            $this->request->data['Download']['to'] = $to;
        }
        if (isset($this->request->data['Download']['download'])) {
            $from = $this->request->data['Download']['from'];
            $to = $this->request->data['Download']['to'];
        }
        return array($from, $to);
    }

    function admin_users() {
        $userId = null;
        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }
        $this->__report('User', $userId);
    }

    function admin_tickets() {
        $userId = null;
        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }
        $this->__report('Ticket', $userId);
    }

    function admin_deposits() {
        $userId = null;
        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }
        $this->__report('Deposit', $userId);
    }

    
    function admin_jackpot_winning(){
    	$userId = null;
    	if ($this->Auth->user('group_id') != 2) { //this is not admin
    		$userId = $this->Auth->user('id');
    	}
    	$this->__report('JackpotWinning', $userId);
    }
    function admin_withdraws() {
        $userId = null;
        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }
        $this->__report('Withdraw', $userId);
    }

    function admin_jackpot_size(){
    	$userId = null;
    	if ($this->Auth->user('group_id') != 2) { //this is not admin
    		$userId = $this->Auth->user('id');
    	}
    	
    	$this->__report('Jackpot', $userId);
    }
    function __report($model, $userId = null) {
        if (!empty($this->request->data['Report'])) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
            $data = $this->$model->getReport($from, $to, $userId, 10);
            $this->set('header', $data['header']);
            unset($data['header']);
            $this->set('data', $data);
            $this->request->data['Download']['from'] = $from;
            $this->request->data['Download']['to'] = $to;
        }
        if (isset($this->request->data['Download']['download'])) {
            $from = $this->request->data['Download']['from'];
            $to = $this->request->data['Download']['to'];
            $data = $this->$model->getReport($from, $to, $userId);
            $this->__export($data, $model, $from, $to);
        }
        $this->set('tabs', $this->Report->getTabs($this->params));
    }

    private

    function _exportAsCSV($data, $title, $from, $to) {
        $filename = $title . "_" . $from . '-' . $to . '.csv';
        $csvFile = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($csvFile, array($from, $to), ',', '"');
        foreach ($data as $report) {
            fputcsv($csvFile, $report['header'], ',', '"');
            foreach ($report['data'] as $dataRow) {
                fputcsv($csvFile, $dataRow, ',', '"');
            }
        }
        fclose($csvFile);
        die;
    }

    function __export($data, $model, $from, $to) {

        $filename = $model . "_" . $from . '-' . $to . '.csv';
        $csv_file = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');


        fputcsv($csv_file, array($from, $to), ',', '"');
        $header_row = $data['header'];
        fputcsv($csv_file, $header_row, ',', '"');

        foreach ($data as $key => $dataRow) {
            if ($key !== 'header') {
                $row = $this->__getRow($dataRow, $model);
                fputcsv($csv_file, $row, ',', '"');
            }
        }
        fclose($csv_file);
        die;
    }

    function __getRow($row, $model) {
        switch ($model) {
            case 'User':
                return $this->__getUserRow($row);
                break;
            case 'Ticket':
                return $this->__getTicketRow($row);
                break;
            case 'Deposit':
                return $this->__getDepositRow($row);
                break;
            case 'Withdraw':
                return $this->__getWithdrawRow($row);
                break;
            default:
                break;
        }
    }

    function __getUserRow($row) {
        $data = array(
            $row['User']['id'],
            $row['User']['registration_date'],
            $row['User']['username'],
            $row['User']['balance'],
            $row['User']['first_name'],
            $row['User']['last_name'],
            $row['User']['address1'],
            $row['User']['address2'],
            $row['User']['zip_code'],
            $row['User']['city'],
            $row['User']['country'],
            $row['User']['email'],
            $row['User']['mobile_number'],
            $row['User']['date_of_birth']
        );
        return $data;
    }

    function __getTicketRow($row) {
        $data = array(
            $row['Ticket']['id'],
            $row['Ticket']['user_id'],
            $row['Ticket']['date'],
            $row['Ticket']['type'],
            $row['Ticket']['events_count'],
            $row['Ticket']['amount'],
            $row['Ticket']['odd'],
            $row['Ticket']['return'],
            $this->BetApi->getStatus($row['Ticket']['status'])
        );
        return $data;
    }

    function __getDepositRow($row) {
        $data = array(
            $row['Deposit']['id'],
            $row['Deposit']['user_id'],
            $row['User']['username'],
            $row['Deposit']['date'],
            $row['Deposit']['type'],
            $row['Deposit']['amount']
        );
        return $data;
    }

    function __getWithdrawRow($row) {
        $data = array(
            $row['Withdraw']['id'],
            $row['Withdraw']['user_id'],
            $row['User']['username'],
            $row['User']['first_name'] . ' ' . $row['User']['last_name'],
            $row['User']['bank_name'],
            $row['User']['bank_code'],
            $row['User']['account_number'],
            $row['Withdraw']['date'],
            $row['Withdraw']['type'],
            $row['Withdraw']['amount']
        );
        return $data;
    }

}

?>