<?php

/**
 * The control file of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
class kevinstore extends control {

	/**
	 * Construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->app->loadClass('date');
		$this->loadModel('dept');
		$this->loadModel('kevincom');
	}

	/**
	 * Copy a kevinstore.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return void
	 */
	public function groupcopy($group) {
		if (!empty($_POST)) {
			$this->kevinstore->groupcopy($group);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'browse'), 'parent'));
		}

		$this->view->title		 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->groupcopy;
		$this->view->position[]	 = $this->lang->kevinstore->groupcopy;
		$this->view->group		 = $this->kevinstore->groupGetById($group);
		$this->view->subMenu	 = "grouplist";
		$this->display();
	}

	/**
	 * Create a kevinstore.
	 * 
	 * @access public
	 * @return void
	 */
	public function groupcreate() {
		if (!empty($_POST)) {
			$this->kevinstore->groupcreate();
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'grouplist'), 'parent'));
		}

		$this->view->title		 = $this->lang->kevinstore->title . $this->lang->colon . $this->lang->kevinstore->groupcreate;
		$this->view->position[]	 = $this->lang->kevinstore->groupcreate;
		$this->view->subMenu	 = "grouplist";
		$this->display();
	}

	/**
	 * Delete a kevinstore.
	 * 
	 * @param  int    $group 
	 * @param  string $confirm  yes|no
	 * @access public
	 * @return void
	 */
	public function groupdelete($group, $confirm = 'no') {
		if ($confirm == 'no') {
			die(js::confirm($this->lang->kevinstore->confirmDelete . $this->lang->kevinstore->group . "?", $this->createLink('kevinstore', 'groupdelete', "group=$group&confirm=yes"))
				. js::closeModal('parent.parent', 'this'));
		} else {
			$this->kevinstore->groupdelete($group);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'grouplist'), 'parent'));
		}
	}

	/**
	 * Edit a kevinstore.
	 * 
	 * @param  int    $group 
	 * @access public
	 * @return void
	 */
	public function groupedit($group) {
		if (!empty($_POST)) {
			$this->kevinstore->groupUpdate($group);
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'browse'), 'parent'));
		}

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->groupedit;
		$position[]				 = $this->lang->kevinstore->groupedit;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->group		 = $this->kevinstore->groupGetById($group);
		$this->view->subMenu	 = "grouplist";

		$this->display();
	}

	/**
	 * Browse groups.
	 * 
	 * @param  int    $companyID 
	 * @access public
	 * @return void
	 */
	public function grouplist() {

		$this->view->title	 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->grouplist;
		$position[]			 = $this->lang->kevinstore->grouplist;

		$this->view->position	 = $position;
		$this->view->groupList	 = $this->kevinstore->groupGetList();
		$this->display();
	}

	/**
	 * view a group.
	 * 
	 * @param  int $group   the int group id
	 * @access public
	 * @return void
	 */
	public function groupview($group) {
		if (!$group) {
			die(js::error("Input group ID wrong, must a right number!"));
		}
		$this->view->group = $this->kevinstore->groupGetById($group);

		if (!$this->view->group) {
			die(js::error("Can not find the group by this id = " . $group));
		}

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->groupedit;
		$position[]				 = $this->lang->kevinstore->grouplist;
		$this->view->title		 = $title;
		$this->view->itemList	 = $this->kevinstore->itemGetByGroup($group);
		$this->view->position	 = $position;

		$this->view->subMenu = "grouplist";
		$this->display();
	}

	/**
	 * index .
	 *      
	 * @access public
	 * @return void
	 */
	public function index() {
		$this->view->position[]		 = $this->lang->kevinstore->common;
		$this->view->position[]		 = $this->lang->kevinstore->itemlist;
		$this->view->title			 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->index;
		$this->display();
	}

	/**
	 * Create a suer.
	 * 
	 * @param  int    $deptID 
	 * @access public
	 * @return void
	 */
	public function itemcreate($deptID = 0) {
		if (!empty($_POST)) {
			$this->kevinstore->itemcreate();

			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'itemlist'), 'parent'));
		}
		$groups		 = $this->dao->select('id, name, type')->from(TABLE_KEVINSTROE_GROUP)->fetchAll();
		$groupList	 = array('' => '');
		$typeGroup	 = array();
		foreach ($groups as $group) {
			$groupList[$group->id]	 = $group->name;
			if ($group->type) $typeGroup[$group->type] = $group->id;
		}

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->itemlist;
		$position[]				 = $this->lang->kevinstore->common;
		$position[]				 = $this->lang->kevinstore->itemlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;

		$this->view->depts		 = $this->dept->getOptionMenu();
		$this->view->groupList	 = $groupList;
		$this->view->typeGroup	 = $typeGroup;
		$this->view->deptID		 = $deptID;
		$this->view->subMenu	 = "itemlist";

		$this->display();
	}

	/**
	 * Delete a item.
	 * 
	 * @param  string    $number 
	 * @param  string $confirm  yes|no
	 * @access public
	 * @return void
	 */
	public function itemdelete($number, $confirm = 'no') {
		if (!$number) return;
		if ($confirm != 'yes') {
			die(js::confirm($this->lang->kevinstore->confirmDelete . $this->lang->kevinstore->item
					, $this->createLink('kevinstore', 'itemdelete', "number=$number&confirm=yes"))
				. js::closeModal('parent.parent', 'this'));
		}

		$this->kevinstore->itemdelete($number);
		if (dao::isError()) die(js::error(dao::getError()));
		if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
		die(js::locate($this->createLink('kevinstore', 'itemlist'), 'parent'));
	}

	/**
	 * Edit a item.
	 * 
	 * @param  int $number   the int item id
	 * @access public
	 * @return void
	 */
	public function itemedit($number) {
		$item = $this->kevinstore->itemGet($number);
		if (!$item) {
			dao::$errors["itemedit"][] = "can not find the item with number:$number.";
			die(js::error(dao::getError()));
			;
		}
		if (!empty($_POST)) {
			$this->kevinstore->itemupdate($item);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'itemlist'), 'parent'));
		}
		$this->loadModel("user");

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->itemedit;
		$position[]				 = $this->lang->kevinstore->itemlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->item		 = $item;
		$this->view->groupList	 = $this->kevinstore->groupGetPairs();
		$this->view->subTypeList = $this->kevinstore->subTypeGetPairs();
		$this->view->subMenu	 = "itemlist";

		$this->display();
	}

	/**
	 * Browse group and itemList.
	 * 
	 * @param  int    $param 
	 * @param  string $type 
	 * @param  string $orderBy 
	 * @param  int    $recTotal 
	 * @param  int    $recPerPage 
	 * @param  int    $pageID 
	 * @access public
	 * @return void
	 */
	public function itemlist($group = '', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$sort = $this->kevincom->appendOrder($orderBy);

		$kevin_showStyle = 1; //默认简单
		//if post ,delete filter key word
		if (!empty($_POST)) {
			$kevin_showStyle = $this->post->showStyle;
			if ($kevin_showStyle) {
				$this->session->set("kevin_showStyle", $kevin_showStyle); //save sesison
			}
		} else {
			if (isset($_SESSION['kevin_showStyle'])) {
				$kevin_showStyle = $_SESSION['kevin_showStyle']; //获得session中保存的筛选关键词
			}
		}

		$this->view->showStyle	 = $kevin_showStyle;
		$this->view->itemList	 = $this->kevinstore->itemGetByQuery($group, $pager, $sort);
		$this->view->groupItem	 = null; //($group) ? $this->kevinstore->groupGetById($group) : '';

		$this->view->groups		 = $this->kevinstore->groupGetList();
		$this->view->title		 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->itemlist;
		$this->view->position[]	 = $this->lang->kevinstore->common;

		$this->view->orderBy = $orderBy;
		$this->view->group	 = $group;
		$this->view->pager	 = $pager;
		$this->display();
	}

	/**
	 * view a item.
	 * 
	 * @param  int $number   the int item id
	 * @access public
	 * @return void
	 */
	public function itemview($number) {
		if (!$number) die(js::error("Input item number wrong, must a right number!"));

		$item = $this->kevinstore->itemGet($number);
		if (!$item) die(js::error("Can not find the item with id = " . $number));

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->itemview;
		$position[]				 = $this->lang->kevinstore->itemlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->item		 = $item;
		$this->display();
	}

	/**
	 * Create a suer.
	 * 
	 * @param  int    $deptID 
	 * @access public
	 * @return void
	 */
	public function rowcreate($deptID = 0) {
		if (!empty($_POST)) {
			$this->kevinstore->rowcreate();

			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'rowlist'), 'parent'));
		}
		$groups		 = $this->dao->select('id, name, type')->from(TABLE_KEVINSTROE_GROUP)->fetchAll();
		$groupList	 = array('' => '');
		$typeGroup	 = array();
		foreach ($groups as $group) {
			$groupList[$group->id]	 = $group->name;
			if ($group->type) $typeGroup[$group->type] = $group->id;
		}

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->rowlist;
		$position[]				 = $this->lang->kevinstore->common;
		$position[]				 = $this->lang->kevinstore->rowlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;

		$this->view->depts		 = $this->dept->getOptionMenu();
		$this->view->groupList	 = $groupList;
		$this->view->typeGroup	 = $typeGroup;
		$this->view->deptID		 = $deptID;
		$this->view->subMenu	 = "rowlist";

		$this->display();
	}

	/**
	 * Delete a row.
	 * 
	 * @param  string    $id 
	 * @param  string $confirm  yes|no
	 * @access public
	 * @return void
	 */
	public function rowdelete($id, $confirm = 'no') {
		if (!$id) return;
		if ($confirm != 'yes') {
			die(js::confirm($this->lang->kevinstore->confirmDelete . $this->lang->kevinstore->row
					, $this->createLink('kevinstore', 'rowdelete', "id=$id&confirm=yes"))
				. js::closeModal('parent.parent', 'this'));
		}

		$this->kevinstore->rowdelete($id);
		if (dao::isError()) die(js::error(dao::getError()));
		if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
		die(js::locate($this->createLink('kevinstore', 'rowlist'), 'parent'));
	}

	/**
	 * Edit a row.
	 * 
	 * @param  int $id   the int row id
	 * @access public
	 * @return void
	 */
	public function rowedit($id) {
		$rowItem = $this->kevinstore->rowGet($id);
		if (!$rowItem) {
			dao::$errors["rowedit"][] = "can not find the row with id:$id.";
			die(js::error(dao::getError()));
			;
		}
		if (!empty($_POST)) {
			$this->kevinstore->rowupdate($rowItem);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::closeModal('parent.parent', 'this'));
			die(js::locate($this->createLink('kevinstore', 'rowlist'), 'parent'));
		}
		$this->loadModel("user");

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->rowedit;
		$position[]				 = $this->lang->kevinstore->rowlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->rowItem	 = $rowItem;
		$this->view->groupList	 = $this->kevinstore->groupGetPairs();
		$this->view->subTypeList = $this->kevinstore->subTypeGetPairs();
		$this->view->subMenu	 = "rowlist";

		$this->display();
	}

	/**
	 * Browse group and rowList.
	 * 
	 * @param  int    $param 
	 * @param  string $type 
	 * @param  string $orderBy 
	 * @param  int    $recTotal 
	 * @param  int    $recPerPage 
	 * @param  int    $pageID 
	 * @access public
	 * @return void
	 */
	public function rowlist($group = '', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		$sort = $this->kevincom->appendOrder($orderBy);

		$this->view->rowList	 = $this->kevinstore->rowGetByQuery($group, $pager, $sort);
		$this->view->groupItem	 = null; //($group) ? $this->kevinstore->groupGetById($group) : '';

		$this->view->groups		 = $this->kevinstore->groupGetList();
		$this->view->title		 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->rowlist;
		$this->view->position[]	 = $this->lang->kevinstore->common;

		$this->view->orderBy = $orderBy;
		$this->view->group	 = $group;
		$this->view->pager	 = $pager;
		$this->display();
	}

	/**
	 * view a row.
	 * 
	 * @param  int $id   the int row id
	 * @access public
	 * @return void
	 */
	public function rowview($id) {
		if (!$id) die(js::error("Input row id wrong, must a right id!"));

		$rowItem = $this->kevinstore->rowGet($id);
		if (!$rowItem) die(js::error("Can not find the row with id = " . $id));

		$title					 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->rowview;
		$position[]				 = $this->lang->kevinstore->rowlist;
		$this->view->title		 = $title;
		$this->view->position	 = $position;
		$this->view->rowItem	 = $rowItem;
		$this->display();
	}

	/**
	 * Statistic kevinstores. 
	 * @param  string    $date 
	 * @param  string    $type 
	 * @access public
	 * @return void
	 */
	public function statistic($type = 'group') {
		//网页位置
		$this->view->title		 = $this->lang->kevinstore->common . $this->lang->colon . $this->lang->kevinstore->statistic;
		$this->view->position[]	 = $this->lang->kevinstore->statistic;

		//绘制图表
		if ('group' == $type) {
			$this->view->items = $this->kevinstore->statisticByGroup();
		} else if ('dept' == $type) {
			$this->view->items = $this->kevinstore->statisticByDept();
		} else if ('type' == $type) {
			$this->view->items = $this->kevinstore->statisticByType();
		} else if ('yearlimit' == $type) {
			$this->view->items = $this->kevinstore->statisticByYear();
		} else {
			$this->view->ErrorMsg = 'input type is wrong. no suche type of "' . $type . '"';
		}
		//页面参数
		$this->view->statisticType	 = $type;
		$this->view->subMenu		 = "statistic";
		$this->display();
	}

}
