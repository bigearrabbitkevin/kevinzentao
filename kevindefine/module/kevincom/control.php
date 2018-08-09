<?php
class kevincom extends control {

	public function __construct() {
		parent::__construct();
	}

	
	/**
	 * index.
	 *
	 * @access public
	 * @return void
	 */

	public function bomcheck() {

		$this->view->title = $this->lang->kevincom->common . $this->lang->colon . $this->lang->kevincom->index;
		$this->view->position[] = $this->lang->kevincom->index;

		$this->display();
	}
	
	/**
	 * index.
	 *      
	 * @access public
	 * @return void
	 */
	
	public function index() {
		
		$this->view->title = $this->lang->kevincom->common . $this->lang->colon . $this->lang->kevincom->index;
		$this->view->position[] = $this->lang->kevincom->index;

		$this->display();
	}
}
