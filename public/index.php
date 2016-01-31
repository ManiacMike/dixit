<?php
define('APP_PATH', realpath(dirname(__FILE__) . '/../'));

//set include path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APP_PATH),
	realpath(APP_PATH . '/app'),
	realpath(APP_PATH . '/app/Model'),
	realpath(APP_PATH . '/app/Library'),
	realpath(APP_PATH . '/app/View'),
	get_include_path(),
)));
// echo APP_PATH . '/app/Library/Kiss';die;

$appEnv = getenv('APP_ENV') ? getenv('APP_ENV') : 'production';

//define config path
define('CONFIG_PATH', APP_PATH . '/app/Config/' .$appEnv);

//initial application
require APP_PATH."/app/Library/Kiss/App.php";
Kiss_App::init($appEnv, CONFIG_PATH.'/app.ini');
Kiss_App::bootstrap();

//store config to Kiss_Registry
$config = Kiss_App::getConfig();
Kiss_Registry::set('config', $config);
//Resources::setConfig($config);

//create router
$router = new Kiss_Router();

//map "/hello/*" to Controller/Home.php::hiAction()
$router->addRoute('/room/(.*)', 'room/index', array(1 => 'room_id'));

//run
$router->dispatch();
