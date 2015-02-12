<?php

class PagesController extends AppController {

    public $name = 'Pages';
	
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('display', 'main','agents','pages','jackpot'));
    }

    function main() {
		
		//For Sports Dashboard
		$showEvents			= 1;
		$showMostPlayed		= 1;
		$showlatestResult	= 1;
		$showlatestWins 	= 1;
		$showSlider			= 1;
        $showLastMinuteBets = 1;
        $showNews			= 1;		
        $this->loadModel('News');        
        $news = $this->News->getNews();

		//For Lottery Dashboard
		$showlottterySlider		= 1;
		$showLotteryResult		= 1;
		$showPastLotteryResult	= 1;

		$dashboardType = $this->Session->read('dashboard_type_user');
		if(isset($dashboardType) && $dashboardType=='default'){        
			$this->set(compact('showEvents','showMostPlayed','showlatestResult','showlatestWins','showNews', 'showSlider', 'showLastMinuteBets', 'news'));
		} else {
			$this->set(compact('showlottterySlider','showLotteryResult','showPastLotteryResult'));
		}
    }   

    function display($url = 'main') {
        $show_slider = 0;
        $showLastMinuteBets = 0;
        $showNews = 0;
        if ($url == 'main') {
            $show_slider = 1;
            $showLastMinuteBets = 1;
            $showNews = 1;
        }        

        $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
        if (empty($page)) {
            $this->Page->locale = 'en_us';
            $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
        }

        //fallback to main page
        if (empty($page)) {
            $show_slider = 1;
            $url = 'main';
            $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
            if (empty($page)) {
                $this->Page->locale = 'en_us';
                $page = $this->Page->find('first', array('conditions' => array('url' => $url, 'active' => '1')));
            }
        }


        $title = $page['Page']['title'];
        $title_for_layout = $title;
        $content = $page['Page']['content'];
        $keywords = $page['Page']['keywords'];
        $description = $page['Page']['description'];

        $this->set(compact('showNews', 'description', 'keywords', 'title', 'content', 'title_for_layout', 'show_slider', 'showLastMinuteBets'));
    }

}
