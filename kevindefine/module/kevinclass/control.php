
<?php

class kevinclass extends control {

	public function __construct() {
		parent::__construct();
		$this->loadModel('kevincom');
	}
	
	/**
	 * Admin a book or a chapter.
	 * 
	 * @params int    $node
	 * @access public
	 * @return void
	 */
	public function admin($node = '') {
		if ($node) ($nodeItem	 = $this->kevinclass->getNodeByID($node)) && $book		 = $nodeItem->book;
		if (!$node or ! $nodeItem) ($nodeItem	 = $book		 = $this->kevinclass->getFirstBook()) && $node		 = $nodeItem->id;
		if (!$nodeItem) $this->locate(inlink('create'));

		$this->view->title		 = $this->lang->kevinclass->common;
		$this->view->kevinclass	 = $book;
		$this->view->books		 = $this->kevinclass->getBookList();
		$this->view->nodeItem	 = $nodeItem;
		$this->view->catalog	 = $this->kevinclass->getAdminCatalog($node, $this->kevinclass->computeSN($book->id));

		$this->display();
	}

	public function book($node = '') {
		if ('' == $node) $nodeItem	 = $this->kevinclass->getFirstBook();
		else $nodeItem	 = $this->kevinclass->getNodeByID($node);
		if ($nodeItem) {
			$this->view->title		 = $nodeItem->title;
			$this->view->keywords	 = trim($nodeItem->keywords);
			$this->view->nodeItem	 = $nodeItem;
			$this->view->books		 = $this->kevinclass->getBookList();
			//$this->view->catalog	 = $this->kevinclass->getFrontCatalog($nodeItem->id, $this->kevinclass->computeSN($nodeItem->id));
			$this->view->itemList	 = $this->kevinclass->getChildren($nodeItem->id);
		}
		
		$thisLang				 = & $this->lang->kevinclass;
		$this->view->title		 = $thisLang->common . $this->lang->colon . $thisLang->book;
		if ($nodeItem) $this->view->title .= $this->lang->colon . $nodeItem->title;
		$this->view->position[]	 = html::a($this->createLink($this->moduleName, 'index'), $thisLang->common);
		$this->view->position[]	 = $thisLang->book;

		$this->display();
	}

	/**
	 * Create a book.
	 *
	 * @access public 
	 * @return void
	 */
	public function create() {
		if ($_POST) {
			$bookID = $this->kevinclass->createBook();
			if ($bookID) die(js::locate(inlink('book', "node=$bookID"), 'parent'));
		}

		$this->display();
	}
	
	/**
	 * Delete a node.
	 *
	 * @param int $node
	 * @retturn void
	 */
	public function delete($node) {
		$this->kevinclass->delete($node);
		if (!dao::isError()) $this->send(array('result' => 'success'));
		$this->send(array('result' => 'fail', 'message' => dao::getError()));
	}
	
	/**
	 * Edit a book, a chapter or an article.
	 *
	 * @param int $node
	 * @access public
	 * @return void
	 */
	public function edit($node) {
		$nodeItem	 = $this->kevinclass->getNodeByID($node, false);
		$book		 = $this->kevinclass->getBookByNode($nodeItem);
		if ($_POST) {

			$this->kevinclass->update($node);
			print_r($_POST);
			die(js::locate(inlink('index'), 'parent'));
		}

		/* Get option menu without this nodeItem's family nodes. */
		$optionMenu	 = $this->kevinclass->getOptionMenu($book->id, $removeRoot	 = true);
		$families	 = $this->kevinclass->getFamilies($nodeItem);
		foreach ($families as $member)
			unset($optionMenu[$member->id]);

		$this->view->title		 = $this->lang->edit . $this->lang->kevinclass->typeList[$nodeItem->type];
		$this->view->nodeItem	 = $nodeItem;
		$this->view->optionMenu	 = $optionMenu;

		$this->display();
	}

	/**
	 * help.
	 *
	 * @access public 
	 * @return void
	 */
	public function help() {

		$this->display();
	}

	public function index($id = '', $orderBy = 'grade,`order`', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		if (!empty($_POST)) {
			if (isset($_POST['node'])) {
				$id		 = $_POST['node'];
				$type		 = $_POST['type'];
				$keywords	 = $_POST['keywords'];
			} else {
				$keywords	 = $_POST['book'];
				$type		 = 'book';
			}
		}

		//获得匹配的书籍列表
		$this->view->books	 = $this->kevinclass->getBookList($keywords);
		//从列表中获取一本书
		if ('' === $id) $nodeItem	 = $this->kevinclass->getFirstBook();
		else $nodeItem			 = $this->kevinclass->getNodeByID($id);

		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$sort = $this->kevincom->appendOrder($orderBy);

		//如果关键子类型为type，获得关键字
		$thisLang				 = & $this->lang->kevinclass;
		$this->view->title		 = $thisLang->common . $this->lang->colon . $thisLang->index;
		if ($nodeItem) $this->view->title .= $this->lang->colon . $nodeItem->title;
		$this->view->position[]	 = html::a($this->createLink($this->moduleName, 'index'), $thisLang->common);
		$this->view->position[]	 = $thisLang->index;
		$this->view->nodeItem	 = $nodeItem;
		$this->view->itemList	 = $this->kevinclass->getNoteListByNode($nodeItem, $pager, $sort);

		$this->view->orderBy	 = $orderBy;
		$this->view->pager = $pager;
		$this->display();
	}


}
