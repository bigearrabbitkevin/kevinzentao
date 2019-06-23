<?php

class kevinerrcode extends control {

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * create.
	 *
	 * @access public
	 * @return errcode Object
	 */
	public function create($date = '') {
		$ret = kevin_create_new_errcode();
		if (!empty($_POST)) {
			$ret->data = fixer::input('post')->get();
		}

		if (empty($ret->data)) {
			$ret->errcode = 11;
			$ret->errmsg  = 'Post parameters are not enough';
			die(json_encode($ret));
		}
		$id = $this->kevinerrcode->create($ret->data);
		if (dao::isError()) {
			$ret->errcode = 13;
			$ret->errmsg  = dao::getError(true);
		} else {
			$ret->data->id = $id;
		}

		die(json_encode($ret));
	}
	
	/**
	 * edit.
	 *
	 * @access public
	 * @return errcode Object
	 */
	public function edit() {
		$ret = kevin_create_new_errcode();
		if (!empty($_POST)) $ret->data = fixer::input('post')->get();
		if (empty($ret->data)) {
			$ret->errcode = 11;
			$ret->errmsg  = 'Post parameters are not enough';
			die(json_encode($ret));
		}
		$data = fixer::input('post')->get();
		$ret = $this->kevinerrcode->update($data);
		if (0 == $ret->errcode) {//成功后检出新数据
			$ret->data->newItem = $this->kevinerrcode->getById($ret->data->id);;
		}

		die(json_encode($ret));
	}

	/**
	 * calendar.
	 *
	 * @access public
	 * @return void
	 */
	public function index() {

		/* The title and position. */
		$this->view->title      = $this->lang->kevinerrcode->common.$this->lang->colon.$this->lang->kevinerrcode->index;
		$this->view->position[] = $this->lang->kevinerrcode->index;

		$this->display();
	}

	/**
	 * index.
	 *
	 * @access public
	 * @return void
	 */
	public function getList() {
		$ret       = kevin_create_new_errcode();
		$ret->data = $this->kevinerrcode->getList();
		if (dao::isError()) {
			$ret->errcode = 15;
			$ret->errmsg  = dao::getError(true);
		}
		die(json_encode($ret));
	}
}
