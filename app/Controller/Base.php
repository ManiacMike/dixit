<?php

class Controller_Base extends Kiss_Controller{

		protected $uid;

    public function before(){
        parent::before();
				session_start();
				if(empty($_SESSION['uid'])){
					$_SESSION['uid'] = uniqid();
				}
				$this->uid = $_SESSION['uid'];
				$this->view->uid = $_SESSION['uid'];
				$this->view->title = '只言片语dixit';
    }

    public function after(){
        parent::after();
    }

		protected function errorPage(){
			echo "error";
			die;
		}

		protected function error404(){
			echo "404";
			die;
		}
}
