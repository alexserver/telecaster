<?php

class DefaultController extends BaseController {
	
	public function actionIndex() {
		$this->render('index');
	}

	public function actionThanks() {
		$this->render('thanks');
	}

}