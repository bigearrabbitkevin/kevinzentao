<?php
$Filter_recentDays ='';
$Filter_periods = $lang->kevinhours->periods;
unset($Filter_periods['all']);
if(is_numeric($type) and $type<1970 )$Filter_recentDays = $type;
$Filter_endList = array("" => "")+ $lang->kevinhours->endList; //第一个必须是空，才可以去除
$Filter_endList['all'] = "All";
unset($monthList['']);

echo '<li>' . html::select('year', $yearList, $currentYear, 'onchange=getNewTodoList() class=form-control') . '</li>';
echo "<li><button class='btn form-control' onclick=\"changeMonth('-1');\"><i class='icon-backward'></i></button></li>";
echo '<li>' . html::select('month', $monthList, $currentMonth, 'onchange=getNewTodoList() class=form-control') . '</li>';
echo "<li><button class='btn form-control' onclick=\"changeMonth('+1');\"><i class='icon-forward'></i></button></li>";
echo "<li><button class='btn btn-default form-control' onclick=\"changeMonth('+0');\">本月</button></li>";
echo "<li>&nbsp;</li>";
if (empty($disablePeriods)) {
	foreach ($Filter_periods as $period => $label) {
		$vars	 = "type=$period";
		if ('project' === $methodName)
			$vars	 = "projectID=$projectID&" . $vars;
		else if ('product' === $methodName)
			$vars	 = "productID=$productID&" . $vars;
		elseif($methodName=='my'||$methodName=='todo')
			$vars.="&account={$this->kevinhours->account}&deptID={$this->kevinhours->accountdept}";
		echo "<li id='$period'>" . html::a($this->createLink('kevinhours', $methodName, $vars), $label) . '</li>';
	}
}
if (empty($disableRecently)){
	echo "<li id='period'>&nbsp;&nbsp;&nbsp;&nbsp; {$lang->kevinhours->recently} </li>";
	echo '<li><div class="w-120px">' . html::select('recentdays', $Filter_endList, $Filter_recentDays, 'onchange=getNewTodoListRecent(this.value) class="form-control chosen"') . '</div></li>';
}
?>
<script>$('#<?php echo $type; ?>').addClass('active')</script>