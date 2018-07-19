<?php

class kevindefine extends control {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * create task.
	 *    
	 * @param  int    $storyID 
	 * @access public
	 * @return void
	 */
	public function createtask($storyID) {
		if (!empty($_POST)) {
			$projectID = $_POST['project'];
			$this->kevindefine->linkProject($storyID, $projectID);
			die(js::locate($this->createLink('kevindefine', 'selectproject', "storyID=$storyID"), 'parent'));
		}
		$projectArray	 = $this->kevindefine->getProjectsByStory($storyID);
		if (empty($projectArray))
			$projectArray	 = $this->kevindefine->getProjectProductIdbyStory($storyID);
		else {
			if (count($projectArray) == 1) {
				$project	 = array_keys($projectArray)[0];
				$moduleID	 = $this->kevindefine->getStoryByID($storyID)->module;
				die(js::locate($this->createLink('task', 'create', "projectID=$project&storyID=$storyID&moduleID=$moduleID"), 'parent'));
			} else
				die(js::locate($this->createLink('kevindefine', 'selectproject', "storyID=$storyID"), 'this'));
		}
		$this->view->projects	 = $projectArray;
		$this->view->project	 = array_keys($projectArray)[0];
		$this->display();
	}

	/**
	 * load Product 
	 * 
	 * @param  string $status 
	 * @param  int    $productID 
	 * @access public
	 * @return void
	 */
	public function LoadProduct( & $productID = 0) {
		/* Save session. */
		$uri = $this->app->getURI(true);
		$this->session->set('productList', $uri);
		$this->session->set('productPlanList', $uri);
		$this->session->set('releaseList', $uri);
		$this->session->set('storyList', $uri);
		$this->session->set('projectList', $uri);
		$this->session->set('taskList', $uri);
		$this->session->set('buildList', $uri);
		$this->session->set('bugList', $uri);
		$this->session->set('caseList', $uri);
		$this->session->set('testtaskList', $uri);
		
		/* Load need modules. */
        $this->loadModel('tree');
        $this->loadModel('user');
		$this->loadModel("product");
		/* Get all products, if no, goto the create page. */
		$this->products			 = $this->product->getPairs('nocode');
		if (empty($this->products) and strpos('create', $this->methodName) === false and $this->app->getViewType() != 'mhtml')
			$this->locate($this->createLink('product', 'create'));
        
        /* Set product, module and query. */
        $productID = $this->product->saveState($productID, $this->products);
		$this->view->products	 = &$this->products;
		$this->view->productID	 = $productID;
		$this->product->setMenu($this->products, $productID);
	}
    
    /**
     * Batch edit.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function projectbatchEdit($projectID = 0){
        if(!$this->ProjectCommonAction(0)){
            if(dao::isError()) die(js::error("Initial project Action error!"));
        }
        if($this->post->names)
        {
            $allChanges = $this->project->batchUpdate();
            if(!empty($allChanges))
            {
                foreach($allChanges as $projectID => $changes)
                {
                    if(empty($changes)) continue;

                    $actionID = $this->loadModel('action')->create('project', $projectID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            die(js::locate($this->session->projectList, 'parent'));
        }

        $this->project->setMenu($this->projects, $projectID);

        $projectIDList = $this->post->projectIDList ? $this->post->projectIDList : die(js::locate($this->session->projectList, 'parent'));

        $this->view->title         = $this->lang->kevindefine->projectbatchEdit;
        $this->view->position[]    = $this->lang->kevindefine->projectlist;
        $this->view->position[]    = $this->lang->kevindefine->projectbatchEdit;
        $this->view->projectIDList = $projectIDList;
        $this->view->projects      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->fetchAll('id');
        $this->view->pmUsers       = $this->loadModel('user')->getPairs('noclosed,nodeleted,pmfirst');
        $this->display();
        $this->lang->kevindefine->menu = $this->lang->project->menu;
        $degug1 = 1;
    }

    /**
     * Common actions.
     * 
     * @param  int    $projectID 
     * @access public
     * @return object current object
     */
    public function ProjectCommonAction($projectID = 0, $extra = '')
    {
        $this->loadModel('project');
        $this->projects = $this->project->getPairs('nocode');
        if(!$this->projects) return false;
         
        $this->loadModel('product');

        /* Get projects and products info. */
        $projectID     = $this->project->saveState($projectID, array_keys($this->projects));
        $project       = $this->project->getById($projectID);
        //$products      = $this->project->getProducts($project->id);
        $childProjects = $this->project->getChildProjects($project->id);
        $teamMembers   = $this->project->getTeamMembers($project->id);
        $actions       = $this->loadModel('action')->getList('project', $project->id);

        /* Set menu. */
        $this->project->setMenu($this->projects, $project->id, $extra);

        /* Assign. */
        $this->view->projects      = $this->projects;
        $this->view->project       = $project;
        $this->view->childProjects = $childProjects;
        //$this->view->products      = $products;
        $this->view->teamMembers   = $teamMembers;
        $this->view->actions       = $actions;

        return $project;
    }
	/**
	 * project 
	 * 
	 * @param  string $status 
	 * @param  int    $productID 
	 * @access public
	 * @return void
	 */
	public function projectlist($productID = 0, $type = 'undone', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
		
		$this->LoadProduct($productID);

		$this->view->title		 = $this->lang->product->common . $this->lang->colon . $this->lang->kevindefine->projectlist;
		$this->view->position[]	 = html::a($this->createLink("product", 'browse'), $this->products[$productID]);
		$this->view->position[]	 = $this->lang->product->common;
		$this->view->position[]	 = $this->lang->kevindefine->projectlist;
		
		// Set the pager
		$this->app->loadClass('pager', $static					 = true);
		$pager					 = pager::init($recTotal, $recPerPage, $pageID);
		$this->view->projectStats = $this->loadModel('project')->getProjectStats($type, $productID,0,20,$orderBy,$pager);
		$this->view->pager		 = $pager;
		$this->view->recTotal	 = $recTotal;
		$this->view->recPerPage	 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->view->orderBy	 = $orderBy;
		$this->view->type		 = $type;
		$this->display();
	}

	/**
	 * select project
	 *    
	 * @param  int    $storyId 
	 * @access public
	 * @return void
	 */
	public function selectproject($storyId) {
		$moduleID = $this->kevindefine->getStoryByID($storyId)->module;
		if (!empty($_POST)) {
			$project = $this->post->projects;
			die(js::locate($this->createLink('task', 'create', "projectID=$project&storyID=$storyId&moduleID=$moduleID"), 'parent.parent'));
		}
		$projectArray			 = $this->kevindefine->getProjectsByStory($storyId);
		$this->view->projects	 = $projectArray;
		$this->view->project	 = array_keys($projectArray)[0];
		$this->display();
	}

	public function task($productID = 0, $type = 'unclosed', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {

		$this->LoadProduct($productID);

		$this->view->title		 = $this->lang->product->common . $this->lang->colon . $this->lang->kevindefine->task;
		$this->view->position[]	 = html::a($this->createLink("product", 'browse'), $this->products[$productID]);
		$this->view->position[]	 = $this->lang->product->common;
		$this->view->position[]	 = $this->lang->kevindefine->task;

		/* Set the pager. */
		$this->app->loadClass('pager', $static					 = true);
		$pager					 = pager::init($recTotal, $recPerPage, $pageID);
		$this->view->tasks		 = $this->kevindefine->getProductTask($productID, $pager, $orderBy, $type);
		$this->view->pager		 = $pager;
		$this->view->recTotal	 = $recTotal;
		$this->view->recPerPage	 = $recPerPage;
		$this->view->pageID		 = $pageID;
		$this->view->orderBy	 = $orderBy;
		$this->view->moduleName	 = "product";
		$this->view->type		 = $type;

		$this->display();
	}
}