<?php

class SportsController extends AppController {

    public $name = 'Sports';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('display', 'getSports', 'getLastMinutebets', 'search', 'today', 'tomorow'));
    }

    function admin_view($id = NULL) {
        $model = $this->Sport->getItem($id);
        if ($model != NULL) {
            $this->paginate['conditions'] = array(
                'League.sport_id' => $id
            );
            $this->paginate['recursive'] = -1;
            $data = $this->paginate('League');
            $this->set('data', $data);
            $this->set('model', $model);
        }
    }

    function admin_delete($id) {
        $model = $this->__getModel();
        $this->Sport->League->deleteLeagues($id);
    }

    function search() {
        $this->display();
    }

    public function today($sportId = null, $betType = null) {

        if ($sportId == null) {
            $this->redirect('/');
        }

        $betTypes = $this->Sport->League->Event->Bet->getBetTypes(null, $sportId);
        $this->set('betTypes', $betTypes);

        $start = $this->BetApi->getSqlDate();
        $nextDay = strtotime('tomorrow', strtotime($this->BetApi->getLocalTime()));
        $nextDay = $this->BetApi->localToGMT($nextDay);
        $events = $this->Sport->League->Event->getUpcomingEventsTill($sportId, $start, $nextDay, $betType);

        $url = Router::url(array('controller' => 'sports', 'action' => 'today', $sportId));
        $this->set(compact('events', 'url'));
        $this->view = 'display';
    }

    public function tomorow($sportId = null, $betType = null) {

        if ($sportId == null) {
            $this->redirect('/');
        }

        $betTypes = $this->Sport->League->Event->Bet->getBetTypes(null, $sportId);
        $this->set('betTypes', $betTypes);

        $start = strtotime('tomorrow', strtotime($this->BetApi->getLocalTime()));
        $start = $this->BetApi->localToGMT($start);
        $nextDay = strtotime('tomorrow +1 day', strtotime($this->BetApi->getLocalTime()));
        $nextDay = $this->BetApi->localToGMT($nextDay);
        $events = $this->Sport->League->Event->getUpcomingEventsTill($sportId, $start, $nextDay, $betType);
       
        $url = Router::url(array('controller' => 'sports', 'action' => 'today', $sportId));
        $this->set(compact('events', 'url'));
        $this->view = 'display';
    }

    function display($leagueId = null, $betType = null) {

        if ($leagueId == null) {
            $this->redirect('/');
        }

        $betTypes = $this->Sport->League->Event->Bet->getBetTypes($leagueId);
        $this->set('betTypes', $betTypes);

        $league = $this->Sport->League->getItem($leagueId, 0);
        $sports[]['Sport'] = $league['Sport'];

        $events = $this->Sport->League->Event->getUpcomingEvents($leagueId, $betType);

        $sports[0]['League'][] = $events;
        $events = $sports;
        
        $url = Router::url(array('controller' => 'sports', $leagueId));
        $this->set(compact('events', 'url'));
    }

    function admin_getSports() {
        $this->getSports();
    }

    function getSports() {
        $this->set('sports', $this->Sport->getSports());
        $this->layout = 'ajax';
        $this->view = 'get_sports';
        //$this->render('get_sports');
    }

    function getlastminutebets() {
        return $this->Sport->League->getLastMinuteBets();
    }

}

?>