<?php
//------------------1. code-------------
 if(isset($user->code)):?>
<script>
var newTr = document.createElement("tr");
var newtd =  document.createElement("th");
newtd.innerHTML = <?php echo json_encode($lang->user->code);?>;
newTr.appendChild(newtd);
newtd =  document.createElement("td");
newtd.innerHTML = <?php echo json_encode($user->code);?>;
newTr.appendChild(newtd);
document.getElementsByTagName('table')[0].appendChild(newTr);</script>
<?php endif;

//------------------2 cardId-------------
if(isset($user->cardId)):?>
<script>
var newTr = document.createElement("tr");
var newtd =  document.createElement("th");
newtd.innerHTML = <?php echo json_encode('Card ID');?>;
newTr.appendChild(newtd);
newtd =  document.createElement("td");
newtd.innerHTML = <?php echo json_encode($user->cardId);?>;
newTr.appendChild(newtd);
document.getElementsByTagName('table')[0].appendChild(newTr);
</script>
<?php
endif;

//------------------3 deptdispatch-------------
if(isset($user->deptdispatch)):
	$deptdispatchPath   = $this->dept->getParents($user->deptdispatch);
	$showDeptDispatchPath = '';
	if(empty($deptdispatchPath))
	{
	  $showDeptDispatchPath = "/";
	}
	else
	{
	  foreach($deptdispatchPath as $key => $dept)
	  {
		  if($dept->name) $showDeptDispatchPath .= $dept->name;
		  if(isset($deptdispatchPath[$key + 1])) $showDeptDispatchPath .= $lang->arrow;
	  }
	}
?>
<script>
var newTr = document.createElement("tr");
var newtd =  document.createElement("th");
newtd.innerHTML = <?php echo json_encode($lang->user->deptdispatch);?>;
newTr.appendChild(newtd);
newtd =  document.createElement("td");
newtd.innerHTML = <?php echo json_encode($showDeptDispatchPath);?>;
newTr.appendChild(newtd);
document.getElementsByTagName('table')[0].appendChild(newTr);
</script>
<?php endif;

//------------------4 domainFullAccount-------------
if(isset($user->domainFullAccount)):?>
<script>
var newTr = document.createElement("tr");
var newtd =  document.createElement("th");
newtd.innerHTML = "Domain Account";
newTr.appendChild(newtd);
newtd =  document.createElement("td");
newtd.innerHTML = <?php echo json_encode($user->domainFullAccount);?>;
newTr.appendChild(newtd);
document.getElementsByTagName('table')[0].appendChild(newTr);
</script>
<?php endif;?>