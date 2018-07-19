
<?php

class kevinclassModel extends model {

	public $savePath = '';
	public $webPath	 = '';
	public $now		 = 0;

	/**
	 * Construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->now = time();
	}

	/**
	 * Create a book.
	 *
	 * @access public
	 * @return bool
	 */
	public function createBook() {
		$now	 = helper::now();
		$this->isTitleExsit($this->post->title);
		$book	 = fixer::input('post')
			->add('parent', 0)
			->add('grade', 1)
			->add('type', 'book')
			->add('addedDate', $now)
			->add('editedDate', $now)
			->setForce('keywords', $this->post->keywords)
			->get();

		$this->dao->insert(TABLE_KEVINCLASS_ITEM)
			->data($book)
			->autoCheck()
			->batchCheck($this->config->kevinclass->require->book, 'notempty')
			//->check('alias', 'unique', "`type`='book'")
			->exec();

		if (dao::isError()) return false;

		/* Update the path and order field. */
		$bookID		 = $this->dao->lastInsertID();
		$bookPath	 = ",$bookID,";
		$this->dao->update(TABLE_KEVINCLASS_ITEM)
			->set('path')->eq($bookPath)
			->set('`order`')->eq($bookID)
			->where('id')->eq($bookID)
			->exec();

		if (dao::isError()) return false;

		/* Save keywrods. */
		$this->saveTag($book->keywords);

		/* Return the book id. */
		return $bookID;
	}

	/**
	 * Delete a book.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete($id, $null = null) {
		$book = $this->getNodeByID($id);
		if (!$book) return false;

		$this->dao->delete()->from(TABLE_KEVINCLASS_ITEM)->where('id')->eq($id)->exec();
		return !dao::isError();
	}
	
	/**
	 * Format time.
	 * 
	 * @param  int    $time 
	 * @param  string $format 
	 * @access public
	 * @return void
	 */
	function formatTime($time, $format = '') {
		$time	 = str_replace('0000-00-00', '', $time);
		$time	 = str_replace('00:00:00', '', $time);
		if ($format) return date($format, strtotime($time));
		return trim($time);
	}

	/**
	 * Get a node's book.
	 * 
	 * @param  object    $node 
	 * @access public
	 * @return object
	 */
	public function getBookByNode($node) {
		return $this->getBookByID($this->extractBookID($node->path));
	}

	/**
	 * Get book list.
	 *
	 * @access public
	 * @return array
	 */
	public function getBookList($keywords = '') {
		return $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)
				->where('type')->eq('book')
				->beginIF($keywords != '')->andWhere('title')->like('%' . $keywords . '%')->fi()
				->orderBy('`order`')
				->fetchAll('id');
	}

	/**
	 * Get a book by id 
	 *
	 * @param  string|int $id   the id can be the number id .
	 * @access public
	 * @return object
	 */
	public function getBookByID($id) {
		return $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)->where('id')->eq($id)->fetch();
	}

	/**
	 * Get children nodes of a node.
	 * 
	 * @param  int    $nodeID 
	 * @access public
	 * @return array
	 */
	public function getChildren($id) {
		return $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)
				->where('deleted')->eq('0')
				->andWhere('parent')->eq($id)
				->orderBy("order,id")
				->fetchAll();
	}
	
	/**
	 * Get the first book.
	 * 
	 * @access public
	 * @return object|bool
	 */
	public function getFirstBook() {
		return $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)->where('type')->eq('book')->orderBy('`order`')->limit(1)->fetch();
	}
	
	/**
	 * Get a node of a book.
	 *
	 * @param  int      $nodeID
	 * @param  bool     $replaceTag
	 * @access public
	 * @return object
	 */
	public function getNodeByID($nodeID, $replaceTag = true) {
		$node = $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)
			->where('id')->eq($nodeID)
			->beginIf(defined('RUN_MODE') and RUN_MODE == 'front')
			->andWhere('addedDate')->le(helper::now())
			->fi()
			->fetch();

		if (!$node) return false;

		$node->origins	 = $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)->where('id')->in($node->path)->orderBy('grade')->fetchAll('id');
		$node->book		 = current($node->origins);

		return $node;
	}

	/**
	 * Get node list.
	 *
	 * @access public
	 * @return array
	 */
	public function getNoteListByNode($nodeItem = null, $pager = null, $orderBy = 'grade,`order`') {
		return $this->dao->select('*')->from(TABLE_KEVINCLASS_ITEM)
				->where('deleted')->eq('0')
				->beginIF($nodeItem)->andWhere('path')->like('%,' . $nodeItem->id . ',%')->fi()
				->orderBy($orderBy)
				->page($pager)
				->fetchAll('id');
	}

	/**
	 * Update a node.
	 *
	 * @param int $nodeID
	 * @access public
	 * @return bool
	 */
	public function update($nodeID) {
		$oldNode = $this->getNodeByID($nodeID);

		$node = fixer::input('post')
			->add('id', $nodeID)
			->add('editor', $this->app->user->account)
			->add('editedDate', helper::now())
			->remove('alias')
			->remove('keywords')
			->setForce('type', $oldNode->type)
			->get();

		$this->dao->update(TABLE_KEVINCLASS_ITEM)
			->data($node)
			->autoCheck()
			->batchCheckIF($node->type == 'book', $this->config->kevinclass->require->book, 'notempty')
			->batchCheckIF($node->type != 'book', $this->config->kevinclass->require->edit, 'notempty')
			->checkIF($node->type == 'book', 'alias', 'unique', "`type` = 'book' AND id != '$nodeID'")
			->where('id')->eq($nodeID)
			->exec();

		if (dao::isError()) return false;

		$this->fixPath($oldNode->book->id);
		if (dao::isError()) return false;

		$this->saveTag($node->keywords);
		if (dao::isError()) return false;

		if ($node->type == 'article') {
			$this->updateObjectID($this->post->uid, $nodeID, 'book');
			$this->copyFromContent($this->post->content, $nodeID, 'book');
			if (dao::isError()) return false;
		}

		return true;
	}

}
