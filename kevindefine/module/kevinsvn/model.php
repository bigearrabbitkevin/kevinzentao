<?php

/**
 * The kevinsvnModel
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinsvn
 */
?>
<?php

class kevinsvnModel extends model {

	//$svnUser
	public $svnUser	 = null;
	//$svn Users
	public $svnUsers = null;
	//$svnUser
	public $RepItem	 = null;

	/**
	 * Construct function, load model of kevinsvn.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->svnUser = $this->dao->select('a.*,b.realname')->from(TABLE_KEVIN_SVN_USER)->alias("a")->leftjoin(TABLE_USER)->alias("b")
			->on("a.account = b.account")
			->where('a.account')->eq($this->app->user->account)
			->fetch();
	}

	/**
	 * Provide arrays for account check
	 * 
	 * @access public
	 * @return void
	 */
	public function accountchkarr(&$accounts, &$svnaccs, &$windowsIDs) {
		$accpairs = $this->dao->select('id,windowsID')->from(TABLE_KEVIN_SVN_USER)->where('disable')->eq(0)->fetchAll();
		foreach ($accpairs as $acc) {
//			$accounts[$acc->id]=$acc->account;
//			$svnaccs[$acc->id]=$acc->svnaccount;
			$windowsIDs[$acc->id] = $acc->windowsID;
		}
	}

	/**
	 * Create account.
	 * 
	 * @access public
	 * @return void
	 */
	public function accountcreate(&$citems) {
		$accounts	 = array();
		$svnaccs	 = array();
		$windowsIDs	 = array();
		$this->accountchkarr($accounts, $svnaccs, $windowsIDs);
		$message	 = array();
		for ($i = 0; $i < $this->config->kevinsvn->accreateno; $i++) {
			$cdata = new stdClass();
			foreach ($citems as $cname => $citem) {
				$cdata->$cname = $citem[$i];
			}
			if (isset($cdata->account) && isset($cdata->dept) && $cdata->account && $cdata->dept) {
				if (isset($cdata->windowsID) && $cdata->windowsID) {
					if (in_array($cdata->windowsID, $windowsIDs)) {
						$message[$cdata->account] = "{$cdata->account} windowsID is exist!" . PHP_EOL;
					} else {
						$this->dao->insert(TABLE_KEVIN_SVN_USER)->data($cdata)->exec();
						$cid				 = $this->dao->lastInsertID();
//						$accounts[$cid]=$cdata->account;
//						$svnaccs[$cid]=$cdata->svnaccount;
						$windowsIDs[$cid]	 = $cdata->windowsID;
					}
				} else $message[$cdata->account] = "{$cdata->account} windowsID is illegal!" . PHP_EOL;
			}
		}
		return $message;
	}

	/**
	 * Create and update account.
	 * 
	 * @access public
	 * @return void
	 */
	public function accountcu() {
		$items	 = fixer::input('post')->get();
		$uitems	 = new stdClass();
		$citems	 = new stdClass();
		foreach ($items as $name => $item) {
			if (strpos($name, 'u') === 0) {
				$uname			 = ltrim($name, 'u');
				$uitems->$uname	 = $item;
			} elseif (strpos($name, 'c') === 0) {
				$cname			 = ltrim($name, 'c');
				$citems->$cname	 = $item;
			}
		}
		$warning	 = '';
		$umessages	 = $this->accountupdate($uitems);
		$cmessages	 = $this->accountcreate($citems);
		foreach ($umessages as $umessage)
			$warning.=$umessage;
		foreach ($cmessages as $cmessage)
			$warning.=$cmessage;
		return $warning;
	}

	/**
	 * Update account.
	 * 
	 * @access public
	 * @return void
	 */
	public function accountupdate(&$uitems) {
		$accounts	 = array();
		$svnaccs	 = array();
		$windowsIDs	 = array();
		$this->accountchkarr($accounts, $svnaccs, $windowsIDs);
		$message	 = array();
		$udata		 = new stdClass();
		$ids		 = $uitems->idList;
		unset($uitems->idList);
		foreach ($ids as $uid) {
			foreach ($uitems as $uname => $uitem) {
				$udata->$uname = $uitem[$uid];
			}
//			unset($accounts[$uid]);
//			unset($svnaccs[$uid]);
			unset($windowsIDs[$uid]);
			if (isset($udata->account) && isset($udata->dept) && $udata->account && $udata->dept) {
				if (in_array($udata->account, $accounts)) {
					$message[$udata->account] = "{$udata->account} is exist!" . PHP_EOL;
				} else {
					if (isset($udata->svnaccount) && isset($udata->windowsID) && $udata->windowsID) {
						if (in_array($udata->svnaccount, $svnaccs)) {
							$message[$udata->account] = "{$udata->account} svnaccount is exist!" . PHP_EOL;
						} else {
							if (in_array($udata->windowsID, $windowsIDs)) {
								$message[$udata->account] = "{$udata->account} windowsID is exist!" . PHP_EOL;
							} else {
								$this->dao->update(TABLE_KEVIN_SVN_USER)->data($udata)->where('id')->eq($uid)->exec();
//								$accounts[$uid]=$udata->account;
//								$svnaccs[$uid]=$udata->svnaccount;
								$windowsIDs[$uid] = $udata->windowsID;
							}
						}
					} else $message[$udata->account] = "{$udata->account} windowsID is illegal!" . PHP_EOL;
				}
			}
		}
		return $message;
	}

	/**
	 * Check windowsID.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return void
	 */
	public function accwinIDchk($id) {
		$acc		 = $this->dao->select('windowsID')->from(TABLE_KEVIN_SVN_USER)->where('id')->eq($id)->fetch();
		$isrepeat	 = $this->dao->select('windowsID')->from(TABLE_KEVIN_SVN_USER)->where('windowsID')->eq($acc->windowsID)->andwhere('disable')->eq(0)->fetch();
		return $isrepeat;
	}

	/**
	 * Add null in the first element.
	 * 
	 * @param  array $list 
	 * @access public
	 * @return void
	 */
	public function addnull(&$list) {
		$idlist = array_keys($list);
		array_unshift($idlist, '');
		array_unshift($list, '');
		return array_combine($idlist, $list);
	}

	/**
	 * Get authzs by id.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return list
	 */
	public function authzGetByID($id) {
		return $this->dao->select('b.*,c.windowsID,a.*')->from(TABLE_KEVIN_SVN_AUTHZ)->alias('a')
				->leftJoin(TABLE_KEVIN_SVN_REPOSITORIES)->alias('b')->on('a.rep=b.id')
				->leftJoin(TABLE_KEVIN_SVN_USER)->alias('c')->on('a.user=c.id')
				->where('a.id')->eq($id)
				->fetch();
	}

	/**
	 * Get authzs by query.
	 * 
	 * @param  int $id 
	 * @access public
	 * @return list
	 */
	public function authzGetByRep($id) {
		return $this->dao->select('b.*,a.*')->from(TABLE_KEVIN_SVN_AUTHZ)->alias('a')
				->leftJoin(TABLE_KEVIN_SVN_REPOSITORIES)->alias('b')->on('a.rep=b.id')
				->where('b.disable')->eq(0)
				->andwhere('b.id')->eq($id)
				->fetchAll();
	}

	/**
	 * Get authz by query.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return list
	 */
	public function authzGetByQuery($type, $pager, $orderBy = 'a.disable,a.id', $FilterList) {

		if (!$type || $type == 'my') $FilterList->charger = $this->svnUser->id;

		return $this->dao->select('b.*,a.*,b.name,b.title,d.realname')->from(TABLE_KEVIN_SVN_AUTHZ)->alias('a')
				->leftJoin(TABLE_KEVIN_SVN_REPOSITORIES)->alias('b')->on('a.rep=b.id')
				->leftJoin(TABLE_KEVIN_SVN_USER)->alias('c')->on('a.user=c.id')
				->leftJoin(TABLE_USER)->alias('d')->on('c.account=d.account')
				->where('b.disable = "0"')
				->beginIF($FilterList->title)->andwhere('title')->like("%$FilterList->title%")->fi()
				->beginIF($FilterList->name)->andwhere('name')->like("%$FilterList->name%")->fi()
				->beginIF($FilterList->project)->andwhere('project')->eq($FilterList->project)->fi()
				->beginIF($FilterList->charger)->andwhere('a.user')->eq($FilterList->charger)->fi()
				->beginIF($orderBy)->orderBy($orderBy)->fi()
				->beginIF($pager)->page($pager)->fi()
				->fetchAll();
	}

	/**
	 * Get authz config in file.
	 * 
	 * @param  string  $confpath 
	 * @access public
	 * @return void
	 */
	public function authzgetconf($confpath, &$authzs, &$winpairs, &$accounts) {
		$lines = file($confpath, FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			$line = trim($line);
			if (strpos($line, '[') === 0) {
				$line	 = str_replace('[', '', $line);
				$line	 = str_replace(']', '', $line);
				$dir	 = trim($line);
			}
			if (substr_count($line, '=') == 1 && isset($dir)) {
				$authzinfo								 = explode('=', $line);
				if (isset($winpairs[$authzinfo[0]])) $authzs[$dir][$winpairs[$authzinfo[0]]]	 = $authzinfo[1];
				else {
					$authzs[$dir][$authzinfo[0]] = $authzinfo[1];
					$accounts[$authzinfo[0]]	 = $authzinfo[0];
				}
			}
		}
	}

	/**
	 * Parse authz in file.
	 * 
	 * @param  string  $name 
	 * @access public
	 * @return void
	 */
	public function authzparse($name, &$winpairs, $global = 1) {
		$confpath	 = $this->config->kevinsvn->REP_PATH . $name . CONF_PATH;
		if (!file_exists($confpath)) return false;
		$authzs		 = array();
		$accounts	 = array();
		if (common::hasPriv('kevinsvn', 'authzglobal') && $global) {
			$gpath = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
			if (file_exists($gpath)) {
				$this->authzgetconf($gpath, $authzs, $winpairs, $accounts);
			}
		}
		$this->authzgetconf($confpath, $authzs, $winpairs, $accounts);
		return $authzs;
	}

	/**
	 * Update authzs in file.
	 * 
	 * @access public
	 * @return void
	 */
	public function authzsupdate($repid) {
		$items										 = fixer::input('post')->get();
		$authzs										 = $items->authzs;
		$ids										 = $items->authzids;
		$addacc										 = $items->addacc;
		$addir										 = $items->addir;
		if ($addir !== '' && $addacc !== '') $authzs[$items->repname][$addir][$addacc]	 = $authzs[$items->repname]['input']['select'];

		$accids		 = $this->dao->select('id,windowsID')->from(TABLE_KEVIN_SVN_USER)->fetchPairs();
		$globalarray = array();
		$messeages	 = array();
		foreach ($authzs as $repname => $dirs) {
			if ($repname !== 'global') {
				$confile = $this->config->kevinsvn->REP_PATH . $repname . CONF_PATH;
				if (!file_exists($confile)) {
					$messeages[$repname] = "$repname config file is not exist!" . PHP_EOL;
					continue;
				}
				$copyright = $this->filestrprocess($confile);
			}
			$authzstr = '';
			foreach ($dirs as $dir => $accauthz) {
				if ($dir === 'input') continue;
				$authzstr.='[' . $dir . ']' . PHP_EOL;
				foreach ($accauthz as $account => $authz) {
					$uniqstr = $repname . $dir . $account;
					if ($repname === 'global') {
						if (common::hasPriv('kevinsvn', 'authzglobal')) {
							$globalarray[$account]	 = new stdClass();
							$gobj					 = &$globalarray[$account];
							$gobj->uniqstr			 = $uniqstr;
							$gobj->id				 = $ids[$uniqstr];
							$gobj->authz			 = $authz;
						} else $messeages['gpriv'] = 'You have no priviliage to config global authz!' . PHP_EOL;
						continue;
					}
					if (isset($ids[$uniqstr])) $this->dao->update(TABLE_KEVIN_SVN_AUTHZ)->set('authz')->eq($authz)->where('id')->eq($ids[$uniqstr])->exec();
					else $this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->set('authz')->eq($authz)->set('rep')->eq($repid)
							->set('folder')->eq($dir)->set('user')->eq($account)->exec();
					if (isset($accids[$account])) {
						$authzstr.=$accids[$account] . '=' . $authz . PHP_EOL;
					} else {
						$messeages[$account] = "$account windowsID is not exist!" . PHP_EOL;
						continue;
					}
				}
			}
			if ($repname !== 'global') {
				$wflag				 = file_put_contents($confile, $copyright . $authzstr, LOCK_EX);
				if (!$wflag) $messeages[$repname] = "Writing $repname authz file failed!" . PHP_EOL;
			}
		}
		if (common::hasPriv('kevinsvn', 'authzglobal') && $globalarray) {
			$confile			 = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
			if (!file_exists($confile)) $messeages['gconf']	 = "Global config file is not exist!" . PHP_EOL;
			else {
				$copyright	 = $this->filestrprocess($confile);
				$gauthzstr	 = '[global]' . PHP_EOL;
				foreach ($globalarray as $acc => $accinfo) {
					if (isset($ids[$accinfo->uniqstr])) $this->dao->update(TABLE_KEVIN_SVN_AUTHZ)->set('authz')->eq($accinfo->authz)->where('id')->eq($accinfo->id)->exec();
					else $this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->set('authz')->eq($accinfo->authz)->set('rep')->eq($repid)
							->set('user')->eq($acc)->exec();
					if (isset($accids[$acc])) {
						$gauthzstr.=$accids[$acc] . '=' . $accinfo->authz . PHP_EOL;
					} else {
						$messeages[$acc] = "$acc windowsID is not exist!" . PHP_EOL;
						continue;
					}
				}
				$wflag				 = file_put_contents($confile, $copyright . $gauthzstr, LOCK_EX);
				if (!$wflag) $messeages['gfile']	 = "Writing global authz file failed!" . PHP_EOL;
			}
		}
		$warnings = '';
		foreach ($messeages as $messeage)
			$warnings.=$messeage;
		return $warnings;
	}

	/**
	 * Update single authz config in file.
	 * 
	 * @param  int  $authid
	 * @access public
	 * @return void
	 */
	public function authzupdate($authid) {
		$oldItem = $this->authzGetByID($authid);
		if (!$oldItem) return false;
		$item	 = fixer::input('post')->get();
		if ($oldItem->folder === 'global') $confile = $this->config->kevinsvn->REP_PATH . $oldItem->name . GLOBAL_CONF_PATH;
		else $confile = $this->config->kevinsvn->REP_PATH . $oldItem->name . CONF_PATH;
		if (file_exists($confile)) {
			$flag = $this->dao->update(TABLE_KEVIN_SVN_AUTHZ)->set('authz')->eq($item->authz)->where('id')->eq($authid)->exec();
			if ($flag) {
				$filestr	 = file_get_contents($confile);
				$folderflag	 = '[' . trim($oldItem->folder) . ']';
				$folderpos	 = strpos($filestr, $folderflag);
				if ($folderpos === false) {
					$appendstr	 = '\r\n' . $folderflag . '\r\n' . trim($oldItem->windowsID) . '=' . trim($item->authz);
					$wflag		 = file_put_contents($confile, $appendstr, FILE_APPEND);
				} else {
					$endpos	 = strpos($filestr, '[', $folderpos + 3);
					if ($endpos === false) $endpos	 = strlen($filestr);
					$midstr	 = substr($filestr, $folderpos, -(strlen($filestr) - $endpos));
					$headstr = substr($filestr, 0, -(strlen($filestr) - $folderpos - 1));
					$tailstr = substr($filestr, -(strlen($filestr) - $endpos - 1));
					if (strpos($midstr, trim($oldItem->windowsID)) === false) {
						$midstr.=trim($oldItem->windowsID) . '=' . trim($item->authz) . '\r\n';
					} else {
						$searchstr	 = trim($oldItem->windowsID) . '=' . trim($oldItem->authz);
						$replacestr	 = trim($oldItem->windowsID) . '=' . trim($item->authz);
						$midstr		 = str_replace($searchstr, $replacestr, $midstr);
					}
					$wflag = file_put_contents($confile, $headstr . $midstr . $tailstr, LOCK_EX);
				}
				if (!$wflag) {
					file_put_contents($confile, $filestr);
					$message = 'Writing the authz to file fail!';
				}
			} else return false;
		} else $message = 'The config file is not exist!';
		if (isset($message)) die(js::alert($message));
		else return true;
	}

	/**
	 * Delete rep dir.
	 * 
	 * @param  string  $dir
	 * @access private
	 * @return void
	 */
	private function deldir($dir) {
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (false !== ($file = readdir($dh))) {
					if ($file != "." && $file != "..") {
						$fullpath = $dir . "/" . $file;
						if (!is_dir($fullpath)) {
							unlink($fullpath);
						} else {
							$this->deldir($fullpath);
							rmdir($fullpath);
						}
					}
				}
				closedir($dh);
			}
			rmdir($dir);
		}
	}

	/**
	 * Process file content.
	 * 
	 * @param  string  $confile
	 * @access public
	 * @return void
	 */
	public function filestrprocess($confile) {
		$filestr = file_get_contents($confile);
		if (strpos($filestr, '[') === false) {
			$copyright = $filestr;
		} else {
			$info		 = explode('[', $filestr);
			$copyright	 = $info[0];
		}
		return $copyright;
	}

	/**
	 * Get accounts.
	 * 
	 * @param  page  $pager
	 * @access public
	 * @return void
	 */
	public function getaccounts($pager) {
		return $this->dao->select('*')->from(TABLE_KEVIN_SVN_USER)
				->orderBy('disable , id')
				->page($pager)
				->fetchAll();
	}

	/**
	 * Get acc pairs.
	 * 
	 * @access public
	 * @return void
	 */
	public function getAccountPairs($showDisable = true) {

		$this->svnUsers				 = new stdClass();
		$this->svnUsers->accounts	 = $this->dao->select("a.*,b.realname,b.deleted")->from(TABLE_KEVIN_SVN_USER)->alias('a')
			->leftJoin(TABLE_USER)->alias('b')->on('a.account=b.account')
			->orderBy('a.id')
			->fetchAll();

		$this->svnUsers->PairsActive = array();
		$this->svnUsers->PairsAll	 = array();
		$PairsActive				 = & $this->svnUsers->PairsActive;
		$PairsAll					 = & $this->svnUsers->PairsAll;
		$PairsActive[""]			 = "";
		$PairsAll[""]				 = "";
		foreach ($this->svnUsers->accounts as $item) {
			$name = ($item->account) ? "$item->realname($item->account)" : "[$item->id]$item->svnaccount";

			$PairsAll[$item->id]	 = $name;
			if ($item->disable) continue;
			$PairsActive[$item->id]	 = $name;
		}

		return $PairsActive;
	}

	/**
	 * Get acc pairs.
	 * 
	 * @access public
	 * @return void
	 */
	public function getChargerPairs() {

		$accounts		 = $this->dao->select('distinct a.charger,b.account as account,b.realname,c.svnaccount')->from(TABLE_KEVIN_SVN_REPOSITORIES)->alias('a')
			->leftJoin(TABLE_KEVIN_SVN_USER)->alias('c')->on('a.charger=c.id')
			->leftJoin(TABLE_USER)->alias('b')->on('c.account=b.account')
			->orderBy("a.charger")
			->fetchAll();
		$PairsActive[""] = "";
		foreach ($accounts as $item) {
			$PairsActive[$item->charger] = ($item->account) ? "$item->realname($item->account)" : "[$item->charger]$item->svnaccount";
		}

		return $PairsActive;
	}

	/**
	 * Get pairs for rep check.
	 * 
	 * @access public
	 * @return void
	 */
	public function getchkpairs() {
		return $this->dao->select('id,name')->from(TABLE_KEVIN_SVN_REPOSITORIES)->fetchPairs();
	}

	/**
	 * Get depts.
	 * 
	 * @access public
	 * @return void
	 */
	public function getdeptpairs() {
		$deptlist = $this->dao->select('id,name')
			->from(TABLE_DEPT)
			->where('deleted')->eq('0')
			->orderBy('name')
			->fetchPairs();
		return $this->addnull($deptlist);
	}

	/**
	 * Get windowsID in file.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function getfilewinIDs($repid) {
		if ($repid == 0) {
			if (!file_exists($this->config->kevinsvn->REP_PATH) || !is_dir($this->config->kevinsvn->REP_PATH)) return array();
			$winpairs	 = $this->getwinpairs();
			$authzs		 = array();
			$accounts	 = array();
			$handle		 = dir($this->config->kevinsvn->REP_PATH);
			if (common::hasPriv('kevinsvn', 'authzglobal')) {
				$gpath = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
				if (file_exists($gpath)) {
					$this->authzgetconf($gpath, $authzs, $winpairs, $accounts);
				}
			}
			while (false !== ($entry = $handle->read())) {
				if (($entry != ".") && ($entry != "..")) {
					if (is_dir($this->config->kevinsvn->REP_PATH . $entry)) {
						$confpath = $this->config->kevinsvn->REP_PATH . $entry . CONF_PATH;
						if (file_exists($confpath) && is_file($confpath)) {
							$this->authzgetconf($confpath, $authzs, $winpairs, $accounts);
						}
					}
				}
			}
		} else {
			$repitem	 = $this->repgetbyid($repid);
			if (!$repitem) return array();
			$confpath	 = $this->config->kevinsvn->REP_PATH . $repitem->name . CONF_PATH;

			if (!file_exists($confpath)) return array();
			$winpairs	 = $this->getwinpairs();
			$authzs		 = array();
			$accounts	 = array();
			if (common::hasPriv('kevinsvn', 'authzglobal')) {
				$gpath = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
				if (file_exists($gpath)) {
					$this->authzgetconf($gpath, $authzs, $winpairs, $accounts);
				}
			}
			$this->authzgetconf($confpath, $authzs, $winpairs, $accounts);
		}
		return $accounts;
	}

	/**
	 * Get rep name.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function getnamebyid($repid) {
		$repname = $this->dao->select('name')->from(TABLE_KEVIN_SVN_REPOSITORIES)->where('id')->eq($repid)->andwhere('disable')->eq(0)->fetch();
		if ($repname) return $repname->name;
		else return false;
	}

	/**
	 * Get project name pairs.
	 * 
	 * @access public
	 * @return void
	 */
	public function getproname() {
		$prolist = $this->dao->select("id,concat('[',id,']',name)")->from(TABLE_PROJECT)->where('deleted')->eq(0)->fetchPairs();
		$idlist	 = array_keys($prolist);
		array_unshift($idlist, '');
		array_unshift($prolist, '');
		return array_combine($idlist, $prolist);
	}

	/**
	 * Get rep info.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function getrep($repid) {
		return $this->dao->select('*')->from(TABLE_KEVIN_SVN_REPOSITORIES)->where('id')->eq($repid)->fetch();
	}

	/**
	 * Get winID pairs.
	 * 
	 * @access public
	 * @return void
	 */
	public function getwinpairs() {
		return $this->dao->select('windowsID,id')->from(TABLE_KEVIN_SVN_USER)->where('disable')->eq(0)->fetchPairs();
	}

	/**
	 * Create accounts of rep.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function repaccreate($repid) {
		$repinfo	 = $this->repgetbyid($repid);
		if (!$repinfo || !file_exists($this->config->kevinsvn->REP_PATH . $repinfo->name)) return 'Rep is not exist!';
		$items		 = fixer::input('post')->get();
		$repaccs	 = $items->authzacc;
		$chkpairs	 = $this->dao->select("id,concat(rep,folder,account)")->from(TABLE_KEVIN_SVN_AUTHZ)->fetchPairs();
		foreach ($repaccs as $dir => $accounts) {
			foreach ($accounts as $account) {
				if ($account) {
					$unique = $repid . $dir . $account;
					if (!in_array($unique, $chkpairs)) {
						$data									 = new stdClass();
						$data->rep								 = $repid;
						$data->folder							 = $dir;
						$data->account							 = $account;
						$this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->data($data)->exec();
						$chkpairs[$this->dao->lastInsertId()]	 = $unique;
					}
				}
			}
		}
		return '';
	}

	/**
	 * Parse rep.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function reparse($repid) {
		$repname = $this->getnamebyid($repid);
		$dirs	 = array();
		if ($repname) {
			$revpath = $this->config->kevinsvn->REP_PATH . $repname . PARSE_PATH;
			if (file_exists($revpath)) {
				$dirs['/'] = '';
				for ($i = 0;; $i++) {
					if (!file_exists($revpath . $i)) break;
					$lines = file($revpath . $i, FILE_SKIP_EMPTY_LINES);
					foreach ($lines as $line) {
						if (substr_count($line, 'add-dir false false /') == 1) {
							$dirinfo	 = explode('add-dir false false', $line);
							$dir		 = trim($dirinfo[1]);
							$dirs[$dir]	 = '';
						} elseif (substr_count($line, 'delete-dir false false /') == 1) {
							$dirinfo = explode('delete-dir false false', $line);
							$dir	 = trim($dirinfo[1]);
							if (isset($dirs[$dir])) unset($dirs[$dir]);
						}
					}
				}
			}
		}
		$folders									 = array_keys($dirs);
		$accinfos									 = $this->dao->select('folder,account')->from(TABLE_KEVIN_SVN_AUTHZ)->where('rep')->eq($repid)->andwhere('folder')->in($folders)->fetchAll();
		foreach ($accinfos as $accinfo)
			$dirs[$accinfo->folder][$accinfo->account]	 = $accinfo->account;
		return $dirs;
	}

	/**
	 * Create empty rep by cmd.
	 * 
	 * @access public
	 * @return void
	 */
	public function repcreate() {
		$items = fixer::input('post')->get();
		if (!$items || !$items->name) {
			dao::$errors['RepCreate'][] = "Please input Name!";
			return false;
		}
		$chkpairs = $this->getchkpairs();
		if (in_array($items->name, $chkpairs)) {
			dao::$errors['RepCreate'][] = "Name is exist!";
			return false;
		}
		$dirnamechk	 = &$this->config->kevinsvn->dirnamechk;
		$strchunks	 = str_split($items->name);
		$strleng	 = count($strchunks);
		$final		 = array_diff($strchunks, $dirnamechk);
		if (count($final) != $strleng || is_numeric($items->name)) {
			dao::$errors['RepCreate'][] = "Dir name contain special char or is Numeric!";
			return false;
		}

		$cmd	 = '"' . $this->config->kevinsvn->CMD_PATH . 'svnadmin" create ' . $this->config->kevinsvn->REP_PATH . $items->name;
		$output	 = array();
		exec($cmd, $output, $flag);
		if ($flag) {
			dao::$errors['RepCreate'][] = "Create dir failed!";
			return false;
		}

		if (!file_exists($this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH)) file_put_contents($this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH, '');

		if (!file_exists($this->config->kevinsvn->REP_PATH . $items->name . CONF_PATH)) file_put_contents($this->config->kevinsvn->REP_PATH . $items->name . CONF_PATH, '');

		$this->dao->insert(TABLE_KEVIN_SVN_REPOSITORIES)->data($items)->exec();
		$repid = $this->dao->lastInsertID();
	}

	/**
	 * Delete rep.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function repdelete($item) {
		if (!$item) {
			dao::$errors['repdelete'][] = "Can not input empty objects";
			return false;
		}
		if ($item->name == '') {
			dao::$errors['repdelete'][] = "Can not delete root repository!";
			return false;
		}
		$this->dao->update(TABLE_KEVIN_SVN_REPOSITORIES)->set('disable')->eq($item->disable ? 0 : 1)->where('id')->eq($item->id)->exec();
		/* do not delete dir now!
		  if($repname&&file_exists($this->config->kevinsvn->REP_PATH.$repname)){
		  $this->deldir($this->config->kevinsvn->REP_PATH.$repname);
		  }elseif($repname)$message='REPOSITORY NOT EXIST!';
		 */

		return true;
	}

	/**
	 * Get rep.
	 * 
	 * @param  int  $repid
	 * @access public
	 * @return void
	 */
	public function repgetbyid($repid) {
		if (!$repid) return null;
		return $this->dao->select('a.*,a.project as proid,b.name as project')->from(TABLE_KEVIN_SVN_REPOSITORIES)->alias('a')
				->leftJoin(TABLE_PROJECT)->alias('b')->on('a.project=b.id')
				->where('a.id')->eq($repid)
				->fetch();
	}

	/**
	 * Get rep by query.
	 * 
	 * @param  int    $query 
	 * @param  int    $pager 
	 * @access public
	 * @return void
	 */
	public function repGetByQuery($type, $pager = null, $orderBy = 'a.disable,a.id', $FilterList) {
		if (!$this->svnUser) {
			dao::$errors['repGetByQuery'][] = "You are not a user of SVN manager.";
			return null;
		}
		if (!$type || $type == 'my') $FilterList->charger = $this->svnUser->id;
		return $this->dao->select('a.*,d.name as DeptName,b.realname,e.name as ProjectName')->from(TABLE_KEVIN_SVN_REPOSITORIES)->alias('a')
				->leftJoin(TABLE_KEVIN_SVN_USER)->alias('c')->on('a.charger=c.id')
				->leftJoin(TABLE_USER)->alias('b')->on('c.account=b.account')
				->leftJoin(TABLE_DEPT)->alias('d')->on('a.dept=d.id')
				->leftJoin(TABLE_PROJECT)->alias('e')->on('e.id=a.project')
				->where('true')
				->beginIF($FilterList->title)->andwhere('a.title')->like("%$FilterList->title%")->fi()
				->beginIF($FilterList->name)->andwhere('a.name')->like("%$FilterList->name%")->fi()
				->beginIF($FilterList->project)->andwhere('a.project')->eq($FilterList->project)->fi()
				->beginIF($FilterList->charger)->andwhere('a.charger')->eq($FilterList->charger)->fi()
				->beginIF($orderBy)->orderBy($orderBy)->fi()
				->beginIF($pager)->page($pager)->fi()
				->fetchAll();
	}

	/**
	 * Get rep in FS.
	 * 
	 * @access public
	 * @return void
	 */
	public function replocal() {
		if (!file_exists($this->config->kevinsvn->REP_PATH) || !is_dir($this->config->kevinsvn->REP_PATH)) return false;
		$dbreps	 = $this->dao->select('id,name')->from(TABLE_KEVIN_SVN_REPOSITORIES)->fetchPairs();
		$reps	 = array();
		$i		 = 0;
		$handle	 = dir($this->config->kevinsvn->REP_PATH);
		while (false !== ($entry	 = $handle->read())) {
			if (!in_array($entry, $dbreps) && ($entry != ".") && ($entry != "..")) {
				if (is_dir($this->config->kevinsvn->REP_PATH . $entry)) {
					$confpath = $this->config->kevinsvn->REP_PATH . $entry . '/conf';
					if (file_exists($confpath) && is_dir($confpath)) {
						$reps[$i] = $entry;
						$i++;
					}
				}
			}
		}
		return $reps;
	}

	/**
	 * Sync rep in FS and DB.
	 * 
	 * @access public
	 * @return void
	 */
	public function repsync() {
		$items			 = fixer::input('post')->get();
		$choices		 = $items->choices;
		$names			 = &$items->name;
		unset($items->choices);
		$winpairs		 = $this->getwinpairs();
		$globalrepeat	 = $this->dao->select('*')->from(TABLE_KEVIN_SVN_REPOSITORIES)->where('name')->eq('')->fetch();
		if (!$globalrepeat) {
			$gpath		 = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
			$authzs		 = array();
			$accounts	 = array();
			if (file_exists($gpath)) {
				$this->dao->insert(TABLE_KEVIN_SVN_REPOSITORIES)->set('name')->eq('')->exec();
				$grepid = $this->dao->lastInsertID();
				$this->authzgetconf($gpath, $authzs, $winpairs, $accounts);
				if (isset($authzs['global']) && $authzs['global']) {
					foreach ($authzs['global'] as $gacc => $gauthz) {
						$gauthzdata			 = new stdClass();
						$gauthzdata->rep	 = $grepid;
						$gauthzdata->user	 = $gacc;
						$gauthzdata->authz	 = $gauthz;
						$this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->data($gauthzdata)->exec();
					}
				}
			} else {
				file_put_contents($gpath, '');
				$this->dao->insert(TABLE_KEVIN_SVN_REPOSITORIES)->set('name')->eq('')->exec();
			}
		} elseif ($globalrepeat->disable == 1) {
			$this->dao->update(TABLE_KEVIN_SVN_REPOSITORIES)->set('disable')->eq(0)->where('id')->eq($globalrepeat->id)->exec();
			$gpath		 = $this->config->kevinsvn->REP_PATH . GLOBAL_CONF_PATH;
			$authzs		 = array();
			$accounts	 = array();
			if (file_exists($gpath)) {
				$this->authzgetconf($gpath, $authzs, $winpairs, $accounts);
				if (isset($authzs['global']) && $authzs['global']) {
					foreach ($authzs['global'] as $gacc => $gauthz) {
						$gauthzdata			 = new stdClass();
						$gauthzdata->rep	 = $globalrepeat->id;
						$gauthzdata->user	 = $gacc;
						$gauthzdata->authz	 = $gauthz;
						$this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->data($gauthzdata)->exec();
					}
				}
			} else file_put_contents($gpath, '');
		}
		foreach ($choices as $i) {
			$data		 = new stdClass();
			foreach ($items as $prop => $item)
				$data->$prop = $item[$i];
			$this->dao->insert(TABLE_KEVIN_SVN_REPOSITORIES)->data($data)->exec();
			$repid		 = $this->dao->lastInsertID();
			$dirs		 = array();
			$dirs		 = $this->authzparse($names[$i], $winpairs, 0);
			foreach ($dirs as $dir => $accounts) {
				foreach ($accounts as $account => $authz) {
					$authzdata			 = new stdClass();
					$authzdata->rep		 = $repid;
					$authzdata->folder	 = $dir;
					$authzdata->user	 = $account;
					$authzdata->authz	 = $authz;
					$this->dao->insert(TABLE_KEVIN_SVN_AUTHZ)->data($authzdata)->exec();
				}
			}
		}
	}

	/**
	 * Update rep.
	 * 
	 * @param int $item
	 * @access public
	 * @return void
	 */
	public function repupdate($item) {
		$data = fixer::input('post')->remove("name,id")->get();
		$this->dao->update(TABLE_KEVIN_SVN_REPOSITORIES)->data($data)->where('id')->eq($item->id)->exec();
		return true;
	}

	/**
	 * Copy dir.
	 * 
	 * @access private
	 * @return void
	 */
	private function xcopy($source, $destination, $child) {
		if (!is_dir($source)) return false;

		if (!file_exists($destination) || (file_exists($destination) && !is_dir($destination))) mkdir($destination);
		$handle	 = dir($source);
		while (false !== ($entry	 = $handle->read())) {
			if (($entry != ".") && ($entry != "..")) {
				if (is_dir($source . "/" . $entry)) {
					if ($child) $this->xcopy($source . "/" . $entry, $destination . "/" . $entry, $child);
				} else copy($source . "/" . $entry, $destination . "/" . $entry);
			}
		}
		return true;
	}

}
