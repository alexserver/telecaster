<?php

/**
 * Just a simple BaseController, much similar as Yii Controllers works...
 * But it doesn't need a module nor and app
 */
class BaseController {
	
	protected $Id;
	protected $app;
	protected $layout;

	public function __construct($id, $app) {
		$this->Id = $id;
		$this->app = $app;
		if (!$this->layout) $this->layout = 'main';
	}

	public function render($view, $params = array()) {
		//get the view and layout filename
		$_view_ = $this->getViewFile($view);
		$_layout_ = $this->getLayoutFile();
		//first get the view, second, get the layout
		$content = $this->renderFile($_view_, $params, true);
		//now get the final output with layout
		$this->renderFile($_layout_, array('content'=>$content));
	}

	private function renderFile($_filename_, $params = array(), $_return_ = false) {
		//extract the params within the funciton scope
		extract($params, EXTR_PREFIX_SAME,'data');
		if($_return_) {
			ob_start();
			ob_implicit_flush(false);
			require($_filename_);
			return ob_get_clean();
		}
		else {
			require($_filename_);
		}
	}

	public function getLayoutFile() {
		//returns the filename of the layout for this controller
		return $this->app->getLayoutDir() . '/' . $this->layout . '.view.php';
	}

	public function getViewFile($view) {
		//this is a convention, the view must be named as xxx.view.php in order to work.
		return $this->app->getViewDir() . '/' . strtolower($this->Id) . '/' . $view . '.view.php';
	}

	private function cleanPath($path) {
		//just remove the last slash if exists
		return $this->app->cleanPath($path);
	}

}