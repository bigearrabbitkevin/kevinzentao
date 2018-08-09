<?php

if (!isset($lang->kevincom)) {
	$lang->kevincom				 = new stdclass();
	$lang->kevincom->menu		 = new stdclass();
	$lang->kevincom->menuOrder	 = array();
	$lang->kevin				 = new stdclass();
	$lang->kevin->menu			 = new stdclass();
	$lang->kevin->menuOrder		 = array();
}
//--First Level menu------------------------------
$lang->menu->kevincom	 = '<i class="icon-sitemap"></i> KV|kevincom|index';
$lang->menu->kevinhours	 = '<i class="icon-sitemap"></i> 工时|kevinhours|index';

$lang->menuOrder[7]	 = 'kevinhours';
$lang->menuOrder[78] = 'kevincom';
$lang->modalTip		 = "模态窗口";

//--Second Level menu--kevin menu------------------------------
$lang->kevin->menu->index			 = 'KEVIN|kevincom|index';
$lang->kevin->menu->kevincalendar	 = array('link' => '日历|kevincalendar|todo', 'alias' => 'index,log,todo,lists');
$lang->kevin->menu->kevinchart		 = array('link' => 'Echarts|kevinchart|index', 'alias' => 'index,itemlist,mychart');
$lang->kevin->menu->kevinsoft		 = array('link' => '软件|kevinsoft|softlist', 'alias' => 'softlist,versionlist,groupversionlist,index,modulelist,filelist,statistic');
$lang->kevin->menu->kevindevice		 = array('link' => '设备|kevindevice|devlist', 'alias' => 'devlist,grouplist,groupview,devedit,devview,statistic,maintainlist,spotchklist,sendoutlist');
$lang->kevin->menu->kevinkendoui	 = array('link' => 'Kendo|kevinkendoui|sample');
$lang->kevin->menu->kevinstore		 = array('link' => '仓库|kevinstore|itemlist', 'alias' => 'index,itemlist,grouplist,groupview,itemedit,itemview,statistic,rowlist');
$lang->kevin->menu->kevinsvn		 = array('link' => 'SVN|kevinsvn|index', 'alias' => 'index,authz,account,statistic');
$lang->kevin->menu->kevinclass		 = array('link' => '分类|kevinclass|index', 'alias' => 'book,help');
$lang->kevin->menu->kevinuser		 = array('link' => '用户|kevinuser|classlist', 'alias' => 'index,classlist,recordlist,deptlist,deptset,defaultpwd,managepriv,domainaccount');

$lang->kevin->menuOrder[5]	 = 'index';
$lang->kevin->menuOrder[20]	 = 'kevindevice';
$lang->kevin->menuOrder[30]	 = 'kevinsoft';
$lang->kevin->menuOrder[40]	 = 'kevinstore';
$lang->kevin->menuOrder[50]	 = 'kevincalendar';
$lang->kevin->menuOrder[60]	 = 'kevinkendoui';
$lang->kevin->menuOrder[70]	 = 'kevinchart';
$lang->kevin->menuOrder[80]	 = 'kevinsvn';
$lang->kevin->menuOrder[90]	 = 'kevinclass';
$lang->kevin->menuOrder[100] = 'kevinuser';

//--Second Level menu--kevinhoursTop---------------------------------------
$lang->kevinhoursTop		 = new stdclass();
$lang->kevinhoursTop->menu	 = new stdclass();

$lang->kevinhoursTop->menu->kevinhours	 = array('link' => '工时|kevinhours|index', 'alias' => 'service,todo,browse');

$lang->kevinhoursTop->menu->kevinplan	 = array('link' => '计划|kevinplan|memberlist'
	, 'alias' => 'projectlist,planlist,grouplist,memberlist,memberlist,index,statistic,projectview,groupcreate,memberview');
$lang->kevinhoursTop->menu->kevinhoursb	 = array('link' => '考勤B|kevinhoursb|index'
	, 'alias' => 'itemlist');

$lang->kevinhoursTop->menuOrder[5]	 = 'kevinhours';
$lang->kevinhoursTop->menuOrder[50]	 = 'kevinplan';

//menugroup menu for kevincom----------------
$lang->menugroup->kevinchart	 = 'kevincom';
$lang->menugroup->kevindevice	 = 'kevincom';
$lang->menugroup->kevinsoft		 = 'kevincom';
$lang->menugroup->kevincalendar	 = 'kevincom';
$lang->menugroup->kevinkendoui	 = 'kevincom';
$lang->menugroup->kevinsvn		 = 'kevincom';
$lang->menugroup->kevinclass	 = 'kevincom';
$lang->menugroup->kevinstore	 = 'kevincom';
$lang->menugroup->kevinuser		 = 'kevincom';
$lang->menugroup->kevinlogin	 = 'kevincom';

//menugroup menu for kevinhours----------------
$lang->menugroup->kevindefine	 = 'kevincom';
$lang->menugroup->kevinplan		 = 'kevinhours';
$lang->menugroup->kevinhours	 = 'kevinhours';
$lang->menugroup->kevinhoursb	 = 'kevinhours';

//--Plugin------kevincom------------------------------
$lang->kevincom->menu->index	 = 'KEVIN插件|kevincom|index';
$lang->kevincom->menu->bomcheck	 = 'Bom检查|kevincom|bomcheck';

$lang->kevincom->menuOrder[10]	 = 'index';


//kevinhours menu ---------------------------------------
$lang->kevinhours		 = new stdclass();
$lang->kevinhours->menu	 = new stdclass();
//等待翻译

$lang->kevinhours->menu->index	 = '日历|kevinhours|index';
$lang->kevinhours->menu->todo	 = '列表|kevinhours|todo';
$lang->kevinhours->menu->my		 = '个人|kevinhours|my';
$lang->kevinhours->menu->over		 = array('link' => '加班统计|kevinhours|over', 'alias' => 'deptover');
$lang->kevinhours->menu->count		 = '统计|kevinhours|count';
$lang->kevinhours->menu->project		 = '项目|kevinhours|project';
$lang->kevinhours->menu->product		 = '产品|kevinhours|product';
$lang->kevinhours->menu->clock	 = '打卡|kevinhours|clock';

$lang->kevinhours->menu->browse	 = array('link' => '组织用户.K|kevinhours|browse', 'float' => 'right');
$lang->kevinhours->menu->all	 = array('link' => '本人待办|kevinhours|index|type=&account=0', 'float' => 'right');

$lang->kevinhours->menuOrder[5]	 = 'index';
$lang->kevinhours->menuOrder[10] = 'todo';
$lang->kevinhours->menuOrder[15] = 'my';
$lang->kevinhours->menuOrder[20] = 'over';
$lang->kevinhours->menuOrder[25] = 'count';
$lang->kevinhours->menuOrder[30] = 'project';
$lang->kevinhours->menuOrder[35] = 'product';
$lang->kevinhours->menuOrder[60] = 'clock';

//--Plugin------kevinhourscount------------------------------
$lang->kevinhourscount	 = new stdclass();
//--Plugin------kevinhoursb------------------------------
$lang->kevinhoursb		 = new stdclass();
$lang->kevinhoursb->menu = new stdclass();

//menu list
$lang->kevinhoursb->menu->index		 = '首页|kevinhoursb|index';
$lang->kevinhoursb->menu->itemlist	 = '列表|kevinhoursb|itemlist';
$lang->kevinhoursb->menuOrder[10]	 = 'index';
;
$lang->kevinhoursb->menuOrder[20]	 = 'itemlist';
//--Plugin------kevinkendoui------------------------------
$lang->kevinkendoui					 = new stdclass();
$lang->kevinkendoui->menu			 = new stdclass();

//menu list
$lang->kevinkendoui->menu->sample	 = 'Kendo示例|kevinkendoui|sample';
$lang->kevinkendoui->menuOrder[10]	 = 'sample';

//--Plugin------kevincalendar------------------------------
$lang->kevincalendar		 = new stdclass();
$lang->kevincalendar->menu	 = new stdclass();

//menu list
$lang->kevincalendar->menu->index	 = '日历|kevincalendar|index';
$lang->kevincalendar->menu->log		 = '日志|kevincalendar|log';
$lang->kevincalendar->menu->todo	 = '代办|kevincalendar|todo';
$lang->kevincalendar->menu->lists	 = '列表|kevincalendar|lists';

$lang->kevincalendar->menuOrder[10]	 = 'index';
$lang->kevincalendar->menuOrder[20]	 = 'log';
$lang->kevincalendar->menuOrder[30]	 = 'todo';
$lang->kevincalendar->menuOrder[40]	 = 'lists';

//--Plugin------kevinchart------------------------------
$lang->kevinchart		 = new stdclass();
$lang->kevinchart->menu	 = new stdclass();

//menu list
$lang->kevinchart->menu->index		 = '百度示例|kevinchart|index';
$lang->kevinchart->menu->itemlist	 = '我的报表|kevinchart|itemlist';
$lang->kevinchart->menu->mychart	 = '<i class="icon-common-report icon-bar-chart"></i>使用曲线|kevinchart|mychart';
$lang->kevinchart->menuOrder[10]	 = 'index';
$lang->kevinchart->menuOrder[20]	 = 'itemlist';
$lang->kevinchart->menuOrder[30]	 = 'mychart';

//--Plugin------kevindefine------------------------------

$lang->kevindefine		 = new stdclass();
$lang->kevindefine->menu = $lang->product->menu;

$lang->company->menuOrder[7]		 = 'kevinbrowse';
$lang->company->menu->kevinbrowse	 = '用户.K|kevinhours|browse';

$lang->product->menuOrder[4]			 = 'task';
$lang->product->menuOrder[]				 = 'projectlist';
$lang->product->menu->task				 = array('link' => '任务.K|kevindefine|task|productID=%s');
$lang->product->menu->projectlist		 = array('link' => '项目.K|kevindefine|projectlist|productID=%s');
$lang->project->menu->projectlist		 = array('link' => '项目.K|kevindefine|projectlist');
$lang->project->menu->kevinprojectcode	 = array('link' => '计划.K|kevinplan|projectcode|code=%s');


//--Plugin------kevindevice------------------------------
$lang->kevindevice		 = new stdclass();
$lang->kevindevice->menu = new stdclass();

//menu list
$lang->kevindevice->menu->devlist		 = '设备列表|kevindevice|devlist';
$lang->kevindevice->menu->grouplist		 = '浏览分组|kevindevice|grouplist';
$lang->kevindevice->menu->maintainlist	 = '维护列表|kevindevice|maintainlist';
$lang->kevindevice->menu->sendoutlist	 = '数据发送列表|kevindevice|sendoutlist';
$lang->kevindevice->menu->spotchklist	 = '点检项列表|kevindevice|spotchklist';
$lang->kevindevice->menu->statistic		 = '统计|kevindevice|statistic';

$lang->kevindevice->menuOrder[10]	 = 'index';
$lang->kevindevice->menuOrder[20]	 = 'grouplist';
$lang->kevindevice->menuOrder[30]	 = 'statistic';


//--Plugin------kevinsoft------------------------------
$lang->kevinsoft		 = new stdclass();
$lang->kevinsoft->menu	 = new stdclass();

//menu list
$lang->kevinsoft->menu->softlist		 = '软件列表|kevinsoft|softlist';
$lang->kevinsoft->menu->versionlist		 = '版本列表|kevinsoft|versionlist';
$lang->kevinsoft->menu->groupversionlist = '组版本列表|kevinsoft|groupversionlist';
$lang->kevinsoft->menu->filelist		 = '文件列表|kevinsoft|filelist';
$lang->kevinsoft->menu->modulelist		 = '模块列表|kevinsoft|modulelist';
$lang->kevinsoft->menu->statistic		 = '统计|kevinsoft|statistic';
$lang->kevinsoft->menu->notes			 = '说明|kevinsoft|index';

$lang->kevinsoft->menuOrder[10]	 = 'softlist';
$lang->kevinsoft->menuOrder[20]	 = 'versionlist';
$lang->kevinsoft->menuOrder[30]	 = 'groupversionlist';
$lang->kevinsoft->menuOrder[40]	 = 'filelist';
$lang->kevinsoft->menuOrder[50]	 = 'modulelist';
$lang->kevinsoft->menuOrder[60]	 = 'statistic';
$lang->kevinsoft->menuOrder[70]	 = 'notes';

//--Plugin------kevinplan------------------------------
$lang->kevinplan		 = new stdclass();
$lang->kevinplan->menu	 = new stdclass();

//menu list
$lang->kevinplan->menu->memberlist	 = array('link' => '成员|kevinplan|memberlist', 'alias' => 'memberview');
$lang->kevinplan->menu->planlist	 = array('link' => '科室|kevinplan|planlist', 'alias' => 'planview');
$lang->kevinplan->menu->projectlist	 = array('link' => '项目|kevinplan|projectlist', 'alias' => 'projectview,groupcreate,projectcode');
$lang->kevinplan->menu->statistic	 = '统计|kevinplan|statistic';
$lang->kevinplan->menu->notes		 = '说明|kevinplan|index';
$lang->kevinplan->menu->options		 = '设定|kevinplan|options';
$lang->kevinplan->menu->myplan		 = array('link' => '我的计划|kevinplan|memberlist|plan=&project=&member=0', 'float' => 'right');

$lang->kevinplan->menuOrder[10]	 = 'memberlist';
$lang->kevinplan->menuOrder[20]	 = 'grouplist';
$lang->kevinplan->menuOrder[30]	 = 'planlist';
$lang->kevinplan->menuOrder[60]	 = 'statistic';
$lang->kevinplan->menuOrder[70]	 = 'notes';

//--Plugin------kevinstore------------------------------
$lang->kevinstore		 = new stdclass();
$lang->kevinstore->menu	 = new stdclass();

//menu list
$lang->kevinstore->menu->itemlist	 = '物料列表|kevinstore|itemlist';
$lang->kevinstore->menu->rowlist	 = '进出列表|kevinstore|rowlist';
$lang->kevinstore->menu->grouplist	 = '浏览分组|kevinstore|grouplist';
$lang->kevinstore->menu->statistic	 = '统计|kevinstore|statistic';
$lang->kevinstore->menu->devlist	 = '说明|kevinstore|index';

$lang->kevinstore->menuOrder[10] = 'itemlist';
$lang->kevinstore->menuOrder[15] = 'rowlist';
$lang->kevinstore->menuOrder[20] = 'grouplist';
$lang->kevinstore->menuOrder[30] = 'statistic';
$lang->kevinstore->menuOrder[40] = 'index';

//kevinsvn menu list
$lang->kevinsvn			 = new stdclass();
$lang->kevinsvn->menu	 = new stdclass();

//$lang->kevinsvn->menu->replist	 = array('link' => '库列表|kevinsvn|replist', 'alias' => 'repsync');
$lang->kevinsvn->menu->authz	 = '权限|kevinsvn|authz';
$lang->kevinsvn->menu->account	 = '用户|kevinsvn|account';
//$lang->kevinsvn->menu->statistic = '统计|kevinsvn|statistic';
$lang->kevinsvn->menu->index	 = array('link' => '库列表|kevinsvn|index', 'alias' => 'repsync');

$lang->kevinsvn->menuOrder[5]	 = 'replist';
$lang->kevinsvn->menuOrder[15]	 = 'authz';
$lang->kevinsvn->menuOrder[20]	 = 'account';
//$lang->kevinsvn->menuOrder[30]	 = 'statistic';
$lang->kevinsvn->menuOrder[40]	 = 'index';

//kevinclass menu list
$lang->kevinclass				 = new stdclass();
$lang->kevinclass->menu			 = new stdclass();
$lang->kevinclass->menu->book	 = '手册|kevinclass|book';
$lang->kevinclass->menu->index	 = '列表|kevinclass|index';
$lang->kevinclass->menu->help	 = '帮助|kevinclass|help';

$lang->kevinclass->menuOrder[10] = 'index';
$lang->kevinclass->menuOrder[20] = 'book';
$lang->kevinclass->menuOrder[30] = 'help';

//kevinuser menu list
$lang->kevinuser					 = new stdclass();
$lang->kevinuser->menu				 = new stdclass();
$lang->kevinuser->menu->index		 = '首页|kevinuser|index';
$lang->kevinuser->menu->kevinbrowse	 = '用户.K|kevinuser|browse';
$lang->kevinuser->menu->classlist	 = '级别列表|kevinuser|classlist';
$lang->kevinuser->menu->recordlist	 = '人员履历列表|kevinuser|recordlist';
$lang->kevinuser->menu->deptlist	 = '部门列表|kevinuser|deptlist';
$lang->kevinuser->menu->deptset	 = '部门指定|kevinuser|deptset';
$lang->kevinuser->menu->batchAddUser = array('link' => '<i class="icon-plus-sign"></i>&nbsp;批量添加|kevinuser|batchCreate|dept=%s', 'subModule' => 'kevinuser', 'float' => 'right');
$lang->kevinuser->menu->addUser      = array('link' => '<i class="icon-plus"></i>&nbsp;添加用户|kevinuser|create|dept=%s', 'subModule' => 'kevinuser', 'float' => 'right');


//menu list
$lang->kevinlogin					 = new stdclass();
$lang->kevinlogin->menu				 = &$lang->kevinuser->menu;
$lang->kevinlogin->menu->defaultpwd = '默认密码|kevinlogin|defaultpwd';
$lang->kevinlogin->menu->managepriv = '权限管理|kevinlogin|managepriv';
$lang->kevinlogin->menu->domainaccount = '域用户管理|kevinlogin|domainaccount';

$lang->kevinuser->menuOrder[0]	 = 'index';
$lang->kevinuser->menuOrder[10]	 = 'kevinbrowse';
$lang->kevinuser->menuOrder[20]	 = 'classlist';
$lang->kevinuser->menuOrder[30]	 = 'recordlist';
$lang->kevinuser->menuOrder[40]	 = 'deptlist';
$lang->kevinuser->menuOrder[50]	 = 'deptset';

$lang->kevinuser->menuOrder[70]	 = 'defaultpwd';
$lang->kevinuser->menuOrder[75]	 = 'managepriv';
$lang->kevinuser->menuOrder[80]	 = 'domainaccount';

