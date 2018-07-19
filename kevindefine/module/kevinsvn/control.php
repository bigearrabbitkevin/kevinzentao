<?php

/**
 * The control file of kevinsvn module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinsvn
 */
class kevinsvn extends control {

	/**
	 * Construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->loadModel("user");
	}

	/**
	 * Manage account.
	 * 
	 * @param  int $repid 
	 * @access public
	 * @return void
	 */
	public function account($repid = '', $recTotal = 0, $recPerPage = 10, $pageID = 1) {
		$this->CheckAuthzRepType("all");
		if (!empty($_POST)) {
			$message = $this->kevinsvn->accountcu();
			$vars	 = array('$repid' => $repid, '$recTotal' => $recTotal, '$recPerPage' => $recPerPage, '$pageID' => $pageID);
			if ($message !== '') die(js::alert(trim(json_encode($message), '"')) . js::locate($this->createLink('kevinsvn', 'account', $vars), 'parent'));
			die(js::locate($this->createLink('kevinsvn', 'account', $vars), 'parent'));
		}
		/* Load pager. */
		$this->app->loadClass('pager', $static			 = true);
		if ($this->app->getViewType() == 'mhtml') $recPerPage		 = 10;
		$pager			 = pager::init($recTotal, $recPerPage, $pageID);
		$pager->recTotal = 0;

		if ($repid !== '') {
			$winIDs			 = $this->kevinsvn->getfilewinIDs($repid);
			$idarray		 = array();
			for ($i = 0; $i < count($winIDs); $i++)
				$idarray[$i]	 = $i;
			$finalwins		 = array_combine($idarray, $winIDs);
			if (count($finalwins) < $this->config->kevinsvn->accreateno) for ($i = count($finalwins); $i < $this->config->kevinsvn->accreateno; $i++)
					$finalwins[$i]	 = '';
		}else {
			for ($i = 0; $i < $this->config->kevinsvn->accreateno; $i++)
				$finalwins[$i] = '';
		}
		$this->view->winIDs = $finalwins;

		$this->view->users		 = $this->user->getPairs();
		$this->view->accounts	 = $this->kevinsvn->getaccounts($pager);
		$this->view->depts		 = $this->kevinsvn->getdeptpairs();

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->account;
		$this->view->position[]	 = $this->lang->kevinsvn->account;
		$this->view->pager		 = $pager;
		$this->display();
	}

	/**
	 * Delete account.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return void
	 */
	public function accountdelete($id) {
		$flag = $this->dao->update(TABLE_KEVIN_SVN_USER)->set('disable')->eq(1)->where('id')->eq($id)->exec();
		if (!$flag) {
			$isrepeat = $this->kevinsvn->accwinIDchk($id);
			if ($isrepeat) die(js::alert('WindowsID is exist!') . js::locate($this->createLink('kevinsvn', 'account'), 'parent'));
			else $this->dao->update(TABLE_KEVIN_SVN_USER)->set('disable')->eq(0)->where('id')->eq($id)->exec();
		}
		if (isonlybody()) die(js::reload('parent'));
		die(js::locate($this->createLink('kevinsvn', 'account'), 'parent'));
	}

	/**
	 * Manage authz.
	 * 
	 * @param  int $repid 
	 * @access public
	 * @return void
	 */
	public function authz($repid = '') {
		if ($repid == '') $repid = isset($_SESSION['KevinSVN_RepId']) ? $_SESSION['KevinSVN_RepId'] : "";
		$this->view->repid	 = $repid;
		if (!$repid) {
			$this->display();
			die();
		}
		$this->CheckAuthzRep($repid); //get and check auth
		$this->session->set("KevinSVN_RepId", $repid);

		$repitem = $this->kevinsvn->RepItem;
		if (!empty($_POST)) {
			$message = $this->kevinsvn->authzsupdate($repid);
			if (dao::isError()) die(js::error(dao::getError()));
			if ($message !== '') die(js::alert(trim(json_encode($message), '"')) . (isonlybody() ? js::reload('parent.parent') : js::locate($this->createLink('kevinsvn', 'authz', "repid=$repid"), 'parent')));

			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'authz', "repid=$repid"), 'parent'));
		}

		$authzList	 = $this->kevinsvn->authzGetByRep($repid);
		$reprivs	 = array();
		foreach ($authzList as $authzItem) {
			$reprivs[$authzItem->name][$authzItem->folder][$authzItem->user] = new stdClass();

			$repauthzs			 = &$reprivs[$authzItem->name][$authzItem->folder][$authzItem->user];
			$repauthzs->authzid	 = $authzItem->id;
			$repauthzs->authz	 = $authzItem->authz;
		}

		if ($repitem) {
			$reprivs[$repitem->name]['input']['select']			 = new stdClass();
			$reprivs[$repitem->name]['input']['select']->authz	 = '';
		}
		$this->view->repitem = $repitem;
		$this->view->depts	 = $this->kevinsvn->getdeptpairs();

		$this->view->reprivs = $reprivs;
		$this->view->users	 = $this->kevinsvn->getAccountPairs();

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->index;
		$this->view->position[]	 = $this->lang->kevinsvn->common;

		$this->display();
	}

	/**
	 * edit authorizes.
	 * 
	 * @param  int    $authid 
	 * @access public
	 * @return void
	 */
	public function authzedit($authid) {
		if (!empty($_POST)) {
			$changes = $this->kevinsvn->authzupdate($authid);
			if (dao::isError()) die(js::error(dao::getError()));

			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'authz'), 'parent'));
		}
		$userlist = $this->user->getPairs();

		$this->view->userlist	 = $userlist;
		$this->view->authzItem	 = $this->kevinsvn->authzGetByID($authid);
		//网页位置
		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->authzedit;
		$this->view->position[]	 = $this->lang->kevinsvn->authzedit;
		$this->display();
	}

	/**
	 * Delete authorizes.
	 * 
	 * @param  int    $authzid 
	 * @access public
	 * @return void
	 */
	public function authzdelete($authzid) {
		$oldItem=$this->kevinsvn->authzGetByID($authzid);

		if ($oldItem->folder === 'global') $confile = $this->config->kevinsvn->REP_PATH . $oldItem->name . GLOBAL_CONF_PATH;
		else $confile = $this->config->kevinsvn->REP_PATH . $oldItem->name . CONF_PATH;
		if (file_exists($confile)) {
			$filestr	 = file_get_contents($confile);
			$folderflag	 = '[' . trim($oldItem->folder) . ']';
			$folderpos	 = strpos($filestr, $folderflag);
			if ($folderpos !== false) {
				$endpos	 = strpos($filestr, '[', $folderpos + 3);
				if ($endpos === false) $endpos	 = 2*strlen($filestr);
				$midstr	 = substr($filestr, $folderpos, -(strlen($filestr) - $endpos));
				$headstr = substr($filestr, 0, -(strlen($filestr) - $folderpos));
				$tailstr = substr($filestr, -(strlen($filestr) - $endpos));

				if (strpos($midstr, trim($oldItem->windowsID)) !== false) {
					$searchstr	 = trim($oldItem->windowsID) . '=' . trim($oldItem->authz);
					$midstr		 = str_replace($searchstr, '', $midstr);
				}
				$wflag = file_put_contents($confile, $headstr . $midstr . (string)$tailstr, LOCK_EX);
			}
			if (!$wflag) {
				file_put_contents($confile, $filestr);
				die(js::alert('Writing the authz to file fail!'));
			}
		}
		$this->dao->delete()->from(TABLE_KEVIN_SVN_AUTHZ)->where('id')->eq($authzid)->exec();
		if (isonlybody()) die(js::reload('parent'));
		die(js::locate($this->createLink('kevinsvn', 'authz', "authzid=$authzid"), 'parent'));
	}

	/**
	 * Manage authzlist.
	 * 
	 * @param  string $type 
	 * @access public
	 * @return void
	 */
	public function authzlist($type = 'my', $orderBy = 'a.id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);
		$FilterList				 = new stdClass();
		$this->repfilter($FilterList);
		$this->view->FilterList	 = $FilterList;
		
		if ($type != 'my' && $this->kevinsvn->svnUser->type != 'admin') $type = 'my';

		if (0 && !empty($_POST)) {
			$message = $this->kevinsvn->authzsupdate($repid);
			if (dao::isError()) die(js::error(dao::getError()));
			if ($message !== '') die(js::alert(trim(json_encode($message), '"')) . (isonlybody() ? js::reload('parent.parent') : js::locate($this->createLink('kevinsvn', 'authz', "repid=$repid"), 'parent')));

			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'authz', "repid=$repid"), 'parent'));
		}

		$authzList	 = $this->kevinsvn->authzGetByQuery($type, $pager, $orderBy, $FilterList);

		$this->view->depts	 = $this->kevinsvn->getdeptpairs();

		$this->view->authzList = $authzList;
		$this->view->users	 = $this->kevinsvn->getAccountPairs();
		$this->view->pager	 = $pager;
		$this->view->orderBy	 = $orderBy;
		$this->view->type	 = $type;

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->authzlist;
		$this->view->position[]	 = $this->lang->kevinsvn->common;

		$this->display();
	}

	/**
	 * Parse authorizes in files.
	 * 
	 * @param  int    $repid 
	 * @access public
	 * @return void
	 */
	public function authzparse($repid) {
		$repitem	 = $this->kevinsvn->repgetbyid($repid);
		if (!$repitem) die(js::alert('The repository is not exist in db!') . js::locate('back'));
		$winpairs	 = $this->kevinsvn->getwinpairs();
		$dirs		 = $this->kevinsvn->authzparse($repitem->name, $winpairs);
		if (!$dirs) die(js::alert('Authz config is empty!') . js::locate('back'));

		$this->view->dirs	 = $dirs;
		$this->view->depts	 = $this->kevinsvn->getdeptpairs();
		$this->view->users	 = $this->user->getPairs();
		$this->view->repitem = $repitem;
		$this->view->repid	 = $repid;

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->authzparse;
		$this->view->position[]	 = $this->lang->kevinsvn->index;

		$this->display();
	}

	public function CheckAuthzRepType($type) {
		if (!$this->kevinsvn->svnUser) {
			dao::$errors['authz'][] = "你不是SVN用户!";
			die(js::error(dao::getError()));
		}
		if ($this->kevinsvn->svnUser->type == 'admin') return true;
		if ($type == 'my') return true;
		if ($this->kevinsvn->svnUser->type != 'admin') {
			dao::$errors['authz'][] = "你没有权限访问!";
			die(js::error(dao::getError()));
		}
		return true;
	}

	/**
	 * get or die repository.
	 * 
	 * @param  int    $repid 
	 * @access private
	 * @return void
	 */
	private function repGetorDie($id) {
		$item = $this->kevinsvn->getrep($id);
		if (!$item) {
			dao::$errors['repdelete'][] = "Can not find repository with id = $id";
			die(js::error(dao::getError()));
		}

		return $item;
	}

	public function CheckAuthzRep($id = 0) {
		$item	 = & $this->kevinsvn->RepItem;
		if ($id) $item	 = $this->kevinsvn->repgetbyid($id);

		if (!$item) {
			dao::$errors['authz'][]	 = "没有发现指定的库!";
			if ($id) dao::$errors['authz'][]	 = "Eepository id = $id";
			die(js::error(dao::getError()));
		}

		if (!$this->kevinsvn->svnUser) {
			dao::$errors['authz'][] = "你不是SVN用户!";
			die(js::error(dao::getError()));
		}
		if ($this->kevinsvn->svnUser->type == 'admin') return true;
		if ($item->charger == $this->kevinsvn->svnUser->id) return true;
		if ($type != 'my' && $this->kevinsvn->svnUser->type != 'admin') {
			dao::$errors['authz'][] = "你没有权限访问库 id = $item->id";
			die(js::error(dao::getError()));
		}
	}

	/**
	 * index.
	 *      
	 * @access public
	 * @return void
	 */
	public function index($reptype = 'my', $orderBy = 'a.disable,a.id', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		/* Set the pager. */
		$this->app->loadClass('pager', $static	 = true);
		$pager	 = pager::init($recTotal, $recPerPage, $pageID);

		if ($reptype != 'my' && $this->kevinsvn->svnUser->type != 'admin') $type = 'my';

		$FilterList				 = new stdClass();
		$this->repfilter($FilterList);
		$this->view->FilterList	 = $FilterList;

		$this->view->replist = $this->kevinsvn->repGetByQuery($reptype, $pager, $orderBy, $FilterList);
		if (dao::isError()) die(js::error(dao::getError()));

		$this->view->reptype	 = $reptype;
		$this->view->chargers	 = $this->kevinsvn->getChargerPairs();
		$this->view->orderBy	 = $orderBy;
		$this->view->pager		 = $pager;

		//网页位置
		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->index;
		$this->view->position[]	 = $this->lang->kevinsvn->index;
		$this->display();
	}

	/**
	 * Parse rep in FS.
	 * 
	 * @param  int    $repid 
	 * @access public
	 * @return void
	 */
	public function reparse($repid) {
		if (!empty($_POST)) {
			$message = $this->kevinsvn->repaccreate($repid);

			if (dao::isError()) die(js::error(dao::getError()));
			if ($message !== '') die(js::alert(trim(json_encode($message), '"')));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'index'), 'parent'));
		}

		$this->view->dirs		 = $this->kevinsvn->reparse($repid);
		$this->view->accounts	 = $this->kevinsvn->getAccountPairs();
		$this->view->repid		 = $repid;

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->index;
		$this->view->position[]	 = $this->lang->kevinsvn->common;
		$this->view->subMenu	 = "index";

		$this->display();
	}

	/**
	 * Create rep.
	 * 
	 * @access public
	 * @return void
	 */
	public function repcreate() {
		if (!empty($_POST)) {
			$this->kevinsvn->repcreate();

			if (dao::isError()) die(js::error(dao::getError()));
//			if($message!=='')die(js::alert(trim(json_encode($message),'"')));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'index'), 'parent'));
		}

		$this->view->users	 = $this->kevinsvn->getAccountPairs();
		$this->view->depts	 = $this->kevinsvn->getdeptpairs();

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->index;
		$this->view->position[]	 = $this->lang->kevinsvn->common;
		$this->view->subMenu	 = "index";

		$this->display();
	}

	/**
	 * Delete repository.
	 * 
	 * @param  int    $repid 
	 * @access public
	 * @return void
	 */
	public function repdelete($repid, $confirm = 'no') {
		$this->CheckAuthzRep($repid); //get and check auth
		$item	 = $this->kevinsvn->RepItem;
		$msg	 = " repository with id = $repid";
		if ($confirm == 'no') {
			$msg .= " and folder = \'$item->name\'.";
			echo js::confirm($this->lang->kevinsvn->confirmrepdelete . " $msg Only Sign in database!", $this->createLink('kevinsvn', 'repdelete', "repid=$repid&confirm=yes"));
		} else {
			$this->kevinsvn->repdelete($item);
			if (dao::isError()) die(js::error(dao::getError()));
		}
		if (isonlybody()) die(js::reload('parent'));
	}

	/**
	 * Edit repository.
	 * 
	 * @param  int    $repid 
	 * @access public
	 * @return void
	 */
	public function repedit($repid) {
		$this->CheckAuthzRep($repid); //get and check auth
		$item = $this->kevinsvn->RepItem;

		if (!empty($_POST)) {
			$this->kevinsvn->repupdate($item);
			if (dao::isError()) die(js::error(dao::getError()));
			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'authz', "repid=$repid"), 'parent'));
		}

		$this->view->users	 = $this->kevinsvn->getAccountPairs();
		$this->view->depts	 = $this->kevinsvn->getdeptpairs();

		$this->view->repinfo	 = $item;
		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->index;
		$this->view->position[]	 = $this->lang->kevinsvn->common;
		$this->display();
	}

	/**
	 * Repository filter.
	 * 
	 * @access private
	 * @return void
	 */
	private function repfilter(&$FilterList) {
		$keywordsArray = isset($_SESSION['KevinSVNfilterKeywords']) ? $_SESSION['KevinSVNfilterKeywords'] : array(); //获得session中保存的筛选关键词

		if (!empty($_POST)) {
			$postType = $this->post->postType;
			if ("searchform" == $postType) {
				$keywordsArray['name']		 = $this->post->name;
				$keywordsArray['title']		 = $this->post->title;
				$keywordsArray['project']	 = $this->post->project;
				$keywordsArray['charger']	 = $this->post->charger;
				$this->session->set("KevinSVNfilterKeywords", $keywordsArray);
			}
		}

		$FilterList->id		 = array_key_exists('id', $keywordsArray) ? $keywordsArray['id'] : '';
		$FilterList->title	 = array_key_exists('title', $keywordsArray) ? $keywordsArray['title'] : '';
		$FilterList->name	 = array_key_exists('name', $keywordsArray) ? $keywordsArray['name'] : '';
		$FilterList->project = array_key_exists('project', $keywordsArray) ? $keywordsArray['project'] : '';
		$FilterList->charger = array_key_exists('charger', $keywordsArray) ? $keywordsArray['charger'] : '';
		$FilterList->project = (int) $FilterList->project;
		if (!$FilterList->project) $FilterList->project = "";
	}

	/**
	 * Repository sync.
	 * 
	 * @access public
	 * @return void
	 */
	public function repsync() {
		if (!empty($_POST)) {
			$this->kevinsvn->repsync();

			if (isonlybody()) die(js::reload('parent.parent'));
			die(js::locate($this->createLink('kevinsvn', 'index'), 'parent'));
		}

		$this->view->reps = $this->kevinsvn->replocal();

		$this->view->users	 = $this->kevinsvn->getAccountPairs();
		$this->view->depts	 = $this->kevinsvn->getdeptpairs();

		$this->view->title		 = $this->lang->kevinsvn->common . $this->lang->colon . $this->lang->kevinsvn->repsync;
		$this->view->position[]	 = $this->lang->kevinsvn->index;

		$this->display();
	}

}
