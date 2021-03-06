<?php

// Used for all communication between the controller,
// the model and the view (template class).
// It creates an object for the model class and an object for template class.
// The object for model class has the same name as the model itself,
// so that we can call it something like $this->Item->selectAll();
// from our controller.


class Controller {
	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;

	function __construct($model, $controller, $action){
		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;

		$this->$model = new $model;
		$this->_template = new Template($controller, $action);
	}

	function set($name, $value){
		$this->_template->set($name, $value);
	}

	function __destruct(){
		$this->_template->render();
	}
}