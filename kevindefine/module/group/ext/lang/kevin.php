<?php

//kevin------------------------------------------
$lang->resource->kevincom           = new stdclass();
$lang->resource->kevincom->index    = 'index';
$lang->resource->kevincom->bomcheck = 'bomcheck';
$lang->kevincom->methodOrder[5]     = 'index';
$lang->kevincom->methodOrder[10]    = 'bomcheck';

//kevindefine------------------------------------------
$lang->resource->kevindefine = new stdclass();

//resource
$lang->resource->kevindefine->task             = 'task';
$lang->resource->kevindefine->selectproject    = 'selectproject';
$lang->resource->kevindefine->createtask       = 'createtask';
$lang->resource->kevindefine->setProjectID     = 'setProjectID';
$lang->resource->kevindefine->projectlist      = 'projectlist';
$lang->resource->kevindefine->projectbatchEdit = 'projectbatchEdit';

//methodOrder
$lang->kevindefine->methodOrder[5]  = 'task';
$lang->kevindefine->methodOrder[10] = 'selectproject';
$lang->kevindefine->methodOrder[15] = 'createtask';
$lang->kevindefine->methodOrder[20] = 'setProjectID';
$lang->kevindefine->methodOrder[25] = 'projectlist';
$lang->kevindefine->methodOrder[30] = 'projectbatchEdit';


//kevincalendar------------------------------------------
$lang->resource->kevincalendar               = new stdclass();
$lang->resource->kevincalendar->index        = 'index';
$lang->resource->kevincalendar->create       = 'create';
$lang->resource->kevincalendar->edit         = 'edit';
$lang->resource->kevincalendar->batchcreate  = 'batchcreate';
$lang->resource->kevincalendar->delete       = 'delete';
$lang->resource->kevincalendar->lists        = 'lists';
$lang->resource->kevincalendar->todo         = 'todo';
$lang->resource->kevincalendar->log          = 'log';
$lang->resource->kevincalendar->logdelete    = 'logdelete';
$lang->resource->kevincalendar->logdeleteall = 'logdeleteall';
$lang->resource->kevincalendar->logdeletesql = 'logdeletesql';

$lang->kevincalendar->methodOrder[5]  = 'index';
$lang->kevincalendar->methodOrder[10] = 'create';
$lang->kevincalendar->methodOrder[15] = 'edit';
$lang->kevincalendar->methodOrder[25] = 'delete';
$lang->kevincalendar->methodOrder[30] = 'batchcreate';
$lang->kevincalendar->methodOrder[35] = 'lists';
$lang->kevincalendar->methodOrder[37] = 'todo';
$lang->kevincalendar->methodOrder[40] = 'log';
$lang->kevincalendar->methodOrder[45] = 'logdelete';
$lang->kevincalendar->methodOrder[50] = 'logdeleteall';
$lang->kevincalendar->methodOrder[55] = 'logdeletesql';


//kevinerrcode------------------------------------------
$lang->resource->kevinerrcode         = new stdclass();
$lang->resource->kevinerrcode->index  = 'index';
$lang->resource->kevinerrcode->getList   = 'getList';
$lang->resource->kevinerrcode->create = 'create';
$lang->resource->kevinerrcode->edit   = 'edit';
$lang->resource->kevinerrcode->delete = 'delete';

$lang->kevinerrcode->methodOrder[5]  = 'index';
$lang->kevinerrcode->methodOrder[10] = 'create';
$lang->kevinerrcode->methodOrder[15] = 'edit';
$lang->kevinerrcode->methodOrder[25] = 'delete';
$lang->kevinerrcode->methodOrder[30] = 'getList';

//kevinchart------------------------------------------
$lang->resource->kevinchart = new stdclass();

//resource detail 
$lang->resource->kevinchart->index    = 'index';
$lang->resource->kevinchart->view     = 'view';
$lang->resource->kevinchart->mychart  = 'mychart';
$lang->resource->kevinchart->itemlist = 'itemlist';

//usage
$lang->kevinchart->methodOrder[5]  = 'index';
$lang->kevinchart->methodOrder[15] = 'view';
$lang->kevinchart->methodOrder[25] = 'mychart';
$lang->kevinchart->methodOrder[30] = 'itemlist';


//kevinkendoui------------------------------------------
$lang->resource->kevinkendoui         = new stdclass();
$lang->resource->kevinkendoui->sample = 'sample';

$lang->kevinkendoui->methodOrder[5]    = 'sample';
$lang->resource->kevinkendoui->getlist = 'getlist';
$lang->kevinkendoui->methodOrder[10]   = 'getlist';


//kevindevice------------------------------------------
$lang->resource->kevindevice = new stdclass();

/* Group. */
$lang->resource->kevindevice = new stdclass();

//group
$lang->resource->kevindevice->grouplist   = 'grouplist';
$lang->resource->kevindevice->groupcreate = 'groupcreate';
$lang->resource->kevindevice->groupedit   = 'groupedit';
$lang->resource->kevindevice->groupdelete = 'groupdelete';
$lang->resource->kevindevice->groupview   = 'groupview';

//dev
$lang->resource->kevindevice->devview      = 'devview';
$lang->resource->kevindevice->devlist      = 'devlist';
$lang->resource->kevindevice->devcreate    = 'devcreate';
$lang->resource->kevindevice->devedit      = 'devedit';
$lang->resource->kevindevice->devdelete    = 'devdelete';
$lang->resource->kevindevice->devxlsexport = 'devxlsexport';

//maintain
$lang->resource->kevindevice->maintainlist   = 'maintainlist';
$lang->resource->kevindevice->maintaincreate = 'maintaincreate';
$lang->resource->kevindevice->maintainedit   = 'maintainedit';
$lang->resource->kevindevice->maintaindelete = 'maintaindelete';

//spotcheck
$lang->resource->kevindevice->spotcheck     = 'spotcheck';
$lang->resource->kevindevice->spotchklist   = 'spotchklist';
$lang->resource->kevindevice->spotchkcreate = 'spotchkcreate';
$lang->resource->kevindevice->spotchkedit   = 'spotchkedit';
$lang->resource->kevindevice->spotchkdelete = 'spotchkdelete';

//sendout
$lang->resource->kevindevice->sendoutlist   = 'sendoutlist';
$lang->resource->kevindevice->sendoutcreate = 'sendoutcreate';
$lang->resource->kevindevice->sendoutedit   = 'sendoutedit';
$lang->resource->kevindevice->sendoutdelete = 'sendoutdelete';

//statistic
$lang->resource->kevindevice->statistic = 'statistic';

//group
$lang->kevindevice->methodOrder[5]  = 'grouplist';
$lang->kevindevice->methodOrder[10] = 'groupcreate';
$lang->kevindevice->methodOrder[15] = 'groupedit';
$lang->kevindevice->methodOrder[25] = 'groupdelete';
$lang->kevindevice->methodOrder[35] = 'groupview';

//dev
$lang->kevindevice->methodOrder[50] = 'devview';
$lang->kevindevice->methodOrder[53] = 'devlist';
$lang->kevindevice->methodOrder[55] = 'devcreate';
$lang->kevindevice->methodOrder[60] = 'devedit';
$lang->kevindevice->methodOrder[70] = 'devdelete';

//statistic
$lang->kevindevice->methodOrder[80] = 'statistic';

//kevinsoft------------------------------------------
$lang->resource->kevinsoft = new stdclass();

//index
$lang->resource->kevinsoft->index = 'index';
$lang->kevinsoft->methodOrder[0]  = 'index';

//soft
$lang->resource->kevinsoft->softcreate = 'softcreate';
$lang->resource->kevinsoft->softview   = 'softview';
$lang->resource->kevinsoft->softedit   = 'softedit';
$lang->resource->kevinsoft->softdelete = 'softdelete';
$lang->resource->kevinsoft->softlist   = 'softlist';
$lang->resource->kevinsoft->softFilter = 'softFilter';

$lang->kevinsoft->methodOrder[1] = 'softcreate';
$lang->kevinsoft->methodOrder[2] = 'softview';
$lang->kevinsoft->methodOrder[3] = 'softedit';
$lang->kevinsoft->methodOrder[4] = 'softdelete';
$lang->kevinsoft->methodOrder[5] = 'softlist';
$lang->kevinsoft->methodOrder[6] = 'softFilter';

//version
$lang->resource->kevinsoft->versioncreate = 'versioncreate';
$lang->resource->kevinsoft->versionview   = 'versionview';
$lang->resource->kevinsoft->versionedit   = 'versionedit';
$lang->resource->kevinsoft->versiondelete = 'versiondelete';
$lang->resource->kevinsoft->versionlist   = 'versionlist';

$lang->kevinsoft->methodOrder[10] = 'versioncreate';
$lang->kevinsoft->methodOrder[11] = 'versionview';
$lang->kevinsoft->methodOrder[12] = 'versionedit';
$lang->kevinsoft->methodOrder[13] = 'versiondelete';
$lang->kevinsoft->methodOrder[14] = 'versionlist';

//groupversion
$lang->resource->kevinsoft->groupversioncreate = 'groupversioncreate';
$lang->resource->kevinsoft->groupversionview   = 'groupversionview';
$lang->resource->kevinsoft->groupversionedit   = 'groupversionedit';
$lang->resource->kevinsoft->groupversiondelete = 'groupversiondelete';
$lang->resource->kevinsoft->groupversionlist   = 'groupversionlist';

$lang->kevinsoft->methodOrder[21] = 'groupversioncreate';
$lang->kevinsoft->methodOrder[22] = 'groupversionview';
$lang->kevinsoft->methodOrder[23] = 'groupversionedit';
$lang->kevinsoft->methodOrder[24] = 'groupversiondelete';
$lang->kevinsoft->methodOrder[25] = 'groupversionlist';

//file
$lang->resource->kevinsoft->fileedit     = 'fileedit';
$lang->resource->kevinsoft->filedelete   = 'filedelete';
$lang->resource->kevinsoft->filedownload = 'filedownload';
$lang->resource->kevinsoft->filelist     = 'filelist';

$lang->kevinsoft->methodOrder[31] = 'fileedit';
$lang->kevinsoft->methodOrder[32] = 'filedelete';
$lang->kevinsoft->methodOrder[33] = 'filedownload';
$lang->kevinsoft->methodOrder[34] = 'filelist';

$lang->resource->kevinsoft->modulecreate = 'modulecreate';
$lang->resource->kevinsoft->moduleview   = 'moduleview';
$lang->resource->kevinsoft->moduledit    = 'moduledit';
$lang->resource->kevinsoft->moduledelete = 'moduledelete';
$lang->resource->kevinsoft->modulelist   = 'modulelist';
$lang->resource->kevinsoft->moduleFilter = 'moduleFilter';
$lang->resource->kevinsoft->statistic    = 'statistic';

$lang->kevinsoft->methodOrder[35] = 'modulecreate';
$lang->kevinsoft->methodOrder[36] = 'moduleview';
$lang->kevinsoft->methodOrder[37] = 'moduledit';
$lang->kevinsoft->methodOrder[38] = 'moduledelete';
$lang->kevinsoft->methodOrder[39] = 'modulelist';
$lang->kevinsoft->methodOrder[40] = 'moduleFilter';
$lang->kevinsoft->methodOrder[41] = 'statistic';

//ajax 
$lang->resource->kevinsoft->ajaxGetSoftlist = 'ajaxGetSoftlist';
$lang->resource->kevinsoft->ajaxGetSoft     = 'ajaxGetSoft';
$lang->resource->kevinsoft->ajaxGetVersion  = 'ajaxGetVersion';

$lang->kevinsoft->methodOrder[100] = 'ajaxGetSoftlist';
$lang->kevinsoft->methodOrder[101] = 'ajaxGetSoft';
$lang->kevinsoft->methodOrder[102] = 'ajaxGetVersion';


//kevinsvn------------------------------------------
$lang->resource->kevinsvn = new stdclass();

//index
$lang->resource->kevinsvn->index         = 'index';
$lang->resource->kevinsvn->replist       = 'replist';
$lang->resource->kevinsvn->repcreate     = 'repcreate';
$lang->resource->kevinsvn->repedit       = 'repedit';
$lang->resource->kevinsvn->reparse       = 'reparse';
$lang->resource->kevinsvn->repsync       = 'repsync';
$lang->resource->kevinsvn->repdelete     = 'repdelete';
$lang->resource->kevinsvn->repfilter     = 'repfilter';
$lang->resource->kevinsvn->authz         = 'authz';
$lang->resource->kevinsvn->authzlist     = 'authzlist';
$lang->resource->kevinsvn->authzedit     = 'authzedit';
$lang->resource->kevinsvn->authzdelete   = 'authzdelete';
$lang->resource->kevinsvn->authzglobal   = 'authzglobal';
$lang->resource->kevinsvn->authzparse    = 'authzparse';
$lang->resource->kevinsvn->account       = 'account';
$lang->resource->kevinsvn->accountdelete = 'accountdelete';

$lang->kevinsvn->methodOrder[0] = 'index';
$lang->kevinsvn->methodOrder[1] = 'replist';
$lang->kevinsvn->methodOrder[2] = 'authz';
$lang->kevinsvn->methodOrder[3] = 'account';

//kevinclass menu list
$lang->resource->kevinclass        = new stdclass();
$lang->resource->kevinclass->help  = 'help';
$lang->resource->kevinclass->book  = 'book';
$lang->resource->kevinclass->index = 'index';

$lang->kevinclass->methodOrder[5]  = 'index';
$lang->kevinclass->methodOrder[10] = 'book';
$lang->kevinclass->methodOrder[20] = 'help';

//item
$lang->resource->kevinuser              = new stdclass();
$lang->resource->kevinuser->index       = 'index';
$lang->resource->kevinuser->classcreate = 'classcreate';
$lang->resource->kevinuser->classview   = 'classview';
$lang->resource->kevinuser->classedit   = 'classedit';
$lang->resource->kevinuser->classdelete = 'classdelete';
$lang->resource->kevinuser->classlist   = 'classlist';

$lang->resource->kevinuser->recordcreate = 'recordcreate';
$lang->resource->kevinuser->recordview   = 'recordview';
$lang->resource->kevinuser->recordedit   = 'recordedit';
$lang->resource->kevinuser->recorddelete = 'recorddelete';
$lang->resource->kevinuser->recordlist   = 'recordlist';

$lang->resource->kevinuser->classBatchDelete  = 'classBatchDelete';
$lang->resource->kevinuser->classBatchEdit    = 'classBatchEdit';
$lang->resource->kevinuser->recordBatchDelete = 'recordBatchDelete';
$lang->resource->kevinuser->recordBatchEdit   = 'recordBatchEdit';

$lang->resource->kevinuser->deptcreate      = 'deptcreate';
$lang->resource->kevinuser->deptedit        = 'deptedit';
$lang->resource->kevinuser->deptdelete      = 'deptdelete';
$lang->resource->kevinuser->deptview        = 'deptview';
$lang->resource->kevinuser->deptBatchEdit   = 'deptBatchEdit';
$lang->resource->kevinuser->deptBatchDelete = 'deptBatchDelete';

$lang->resource->kevinuser->deptlist       = 'deptlist';
$lang->resource->kevinuser->browse         = 'browse';
$lang->resource->kevinuser->userbatchedit  = 'userbatchedit';
$lang->resource->kevinuser->manageContacts = 'manageContacts';

$lang->resource->kevinuser->create      = 'create';
$lang->resource->kevinuser->edit        = 'edit';
$lang->resource->kevinuser->batchcreate = 'batchcreate';


$lang->kevinuser->methodOrder[9]  = 'index';
$lang->kevinuser->methodOrder[10] = 'classcreate';
$lang->kevinuser->methodOrder[11] = 'classview';
$lang->kevinuser->methodOrder[12] = 'classedit';
$lang->kevinuser->methodOrder[13] = 'classdelete';
$lang->kevinuser->methodOrder[14] = 'classlist';
$lang->kevinuser->methodOrder[15] = 'recordcreate';
$lang->kevinuser->methodOrder[16] = 'recordview';
$lang->kevinuser->methodOrder[17] = 'recordedit';
$lang->kevinuser->methodOrder[18] = 'recorddelete';
$lang->kevinuser->methodOrder[19] = 'recordlist';

$lang->kevinuser->methodOrder[20] = 'classBatchDelete';
$lang->kevinuser->methodOrder[21] = 'classBatchEdit';
$lang->kevinuser->methodOrder[22] = 'recordBatchDelete';
$lang->kevinuser->methodOrder[23] = 'recordBatchEdit';

$lang->kevinuser->methodOrder[24] = 'classrecyclelist';
$lang->kevinuser->methodOrder[25] = 'classundelete';
$lang->kevinuser->methodOrder[26] = 'classrecycledelete';
$lang->kevinuser->methodOrder[27] = 'recordrecyclelist';
$lang->kevinuser->methodOrder[28] = 'recordundelete';
$lang->kevinuser->methodOrder[29] = 'recordrecycledelete';
$lang->kevinuser->methodOrder[30] = 'classrecyclebatchdelete';
$lang->kevinuser->methodOrder[31] = 'deptlist';
$lang->kevinuser->methodOrder[32] = 'deptBatchDelete';
$lang->kevinuser->methodOrder[33] = 'deptBatchEdit';
$lang->kevinuser->methodOrder[34] = 'deptcreate';
$lang->kevinuser->methodOrder[35] = 'deptedit';
$lang->kevinuser->methodOrder[36] = 'deptview';
$lang->kevinuser->methodOrder[37] = 'deptdelete';
$lang->kevinuser->methodOrder[39] = 'deletedeptuser';

//kevinlogin
$lang->resource->kevinlogin                 = new stdclass();
$lang->resource->kevinlogin->defaultpwd     = 'defaultpwd';
$lang->resource->kevinlogin->delete         = 'delete';
$lang->resource->kevinlogin->userLock       = 'userLock';
$lang->resource->kevinlogin->unlock         = 'unlock';
$lang->resource->kevinlogin->managepriv     = 'managepriv';
$lang->resource->kevinlogin->domainaccount  = 'domainaccount';
$lang->resource->kevinlogin->deleteldapuser = 'deleteldapuser';

$lang->kevinlogin->methodOrder[5]  = 'defaultpwd';
$lang->kevinlogin->methodOrder[10] = 'delete';
$lang->kevinlogin->methodOrder[15] = 'userLock';
$lang->kevinlogin->methodOrder[20] = 'unlock';
$lang->kevinlogin->methodOrder[25] = 'managepriv';
$lang->kevinlogin->methodOrder[30] = 'domainaccount';
$lang->kevinlogin->methodOrder[35] = 'deleteldapuser';
