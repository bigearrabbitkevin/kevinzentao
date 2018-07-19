<?php

class kevinhourscount extends control {

	public function __construct() {
		parent::__construct();
		$this->app->loadClass('date');
		$this->loadModel('kevincom');
		$this->loadModel('todo');
		$this->loadModel('kevincalendar');
		$this->app->loadClass('kevin'); //加载kevin类
	}

}
