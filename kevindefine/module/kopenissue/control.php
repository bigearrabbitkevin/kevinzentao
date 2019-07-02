<?php

class kopenissue extends control {

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * create.
	 *
	 * @access public
	 * @return errcode Object
	 */
	public function create() {
		$data = fixer::input('post')->get();
		$ret = $this->kopenissue->create($data);
		die(json_encode($ret));
	}
	
	/**
	 * edit.
	 *
	 * @access public
	 * @return errcode Object
	 */
	public function edit() {
		$data =fixer::input('post')->get();
		$ret = $this->kopenissue->update($data, true);//成功检出新数据
		die(json_encode($ret));
	}
	
	/**
	 * approve.
	 *
	 * @access public
	 * @return errcode Object
	 */
	public function approve() {
		$data = fixer::input('post')->get();
		$ret = $this->kopenissue->approve($data);
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
		$this->view->title      = $this->lang->kopenissue->common.$this->lang->colon.$this->lang->kopenissue->index;
		$this->view->position[] = $this->lang->kopenissue->index;

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
		$ret->data = $this->kopenissue->getList();
		if (dao::isError()) {
			$ret->errcode = 15;
			$ret->errmsg  = dao::getError(true);
		}
		die(json_encode($ret));
	}
}
