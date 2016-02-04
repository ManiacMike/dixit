<?php

class Controller_BaseApi extends Kiss_Controller{

    protected $isAjax = false;

    public function before(){
        parent::before();
        if($this->isAjax == true)
          $this->checkAjax();
    }

    protected function checkAjax(){
      if(!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])!="xmlhttprequest"){
        $this->echoJson(false,"invaid");
      };
      session_start();
    }

    public function after(){
        parent::after();
    }

		protected function echoJson($result,$data){
			if($result == true){
				$print = array(
					"status"=> "success",
					"data"=>$data
				);
			}else{
				$print = array(
					"status"=> "fail",
					"message"=>$data
				);
			}
			echo json_encode($print);
			die;
		}

		protected function errorPage(){
			echo "error";
			die;
		}
}
