<?php

class Controller_BaseAjax extends Kiss_Controller{

    public function before(){
        parent::before();
				session_start();
				if(!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])!="xmlhttprequest"){
					$this->echoJson(false,"invaid");
				};
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
