<?php

class Controller_Base extends Kiss_Controller{
	
	/**called before each action**/
    public function before(){
        parent::before();
    }

	/**called after each action**/
    public function after(){
        parent::after();
    }
}
