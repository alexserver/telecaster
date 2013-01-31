<?php

class BaseApplication {

	protected $appDir;		//convention this must be the dir where index.php is located
	protected $viewDir;		//convention this must be a subdir of $appDir
	protected $layoutDir;	//convention this must be a subdir of $viewDir
	
	protected $controllerDir;
	protected $route;
	
	public function __construct($appDir) {
		$this->appDir = $appDir;
		if (!$this->viewDir) $this->viewDir = 'views';
		if (!$this->layoutDir) $this->layoutDir = 'layout';
		if (!$this->controllerDir) $this->controllerDir = 'controllers';
	}

	public function run() {
		//getting the controller and the action from the route
		$r = isset($_REQUEST['r'])? $_REQUEST['r'] : '';
		$items = explode('/', $r);
		$controller = (isset($items[0]) && $items[0])? strtolower($items[0]) : 'default';
		$action = (isset($items[1]) && $items[1])? strtolower($items[1]) : 'index';
		//build controller class name
		$controllerClassName = ucwords($controller) . 'Controller';
		//require the controller
		$controllerFileName = $this->getControllerDir() . '/' . $controllerClassName . '.php';
		require_once($controllerFileName);
		//instantiate the controller
		$instance = new $controllerClassName($controller, $this);
		//build action function name
		$actionFunctionName = 'action' . ucwords($action);
		//execute the action function
		$instance->{$actionFunctionName}();
	}

	public function getViewDir() {
		return $this->getAppDir() . '/' . $this->cleanPath($this->viewDir) ;
	}

	public function getLayoutDir() {
		return $this->getViewDir() . '/' . $this->cleanPath($this->layoutDir);
	}

	public function getControllerDir() {
		return $this->getAppDir() . '/' . $this->cleanPath($this->controllerDir);
	}

	public function getAppDir() {
		return $this->cleanPath($this->appDir);
	}

	public function cleanPath($path) {
		//just remove the last slash if exists
		return preg_replace("/\/*$/", "", $path);
	}

}