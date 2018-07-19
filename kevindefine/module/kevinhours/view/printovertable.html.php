<!DOCTYPE html>

<?php

$accounts = array(); 
$accountNotInTodoArray = array();
//遍历代办	//获得代办所有者数组
foreach($todos as $todo):
	$accounts[$todo->account]=$todo->account;
endforeach;


//查询所有的用户信息
$users		 = $this->dao->select('id,realname,account,dept,code')->from(TABLE_USER)->fetchAll('account');

//查询所有的dept信息
$depts	 = $this->dao->select('*')->from(TABLE_DEPT)->fetchAll('id');	

$userItem = new stdClass();
$userDetails = array();

//遍历代办组织创建主键//代办所有者数组
$accountArrayExistTodo = array(); 
$lastAccount='';
foreach($todos as $todo):
	//获得代办所有者数组
	if($lastAccount==$todo->account)continue;
	$lastAccount = $todo->account;
	$accountArrayExistTodo[$lastAccount]=$lastAccount;
endforeach;	

//部门下的用户数据遍历，查出有todo和没有todo的
foreach($accountArrayInDept as $account):
	if(!$account) continue;
	if(!array_key_exists($account,$users))continue;
	$userItem= $users[$account];
	
	//获得部门和父亲部门信息
	$userItem->deptItem = $depts[$userItem->dept];
	$userItem->deptName = $userItem->deptItem->name;
	$userItem->deptParent= $userItem->deptItem->parent;
	if(array_key_exists($userItem->deptParent,$depts)){
		$userItem->deptParentItem= $depts[$userItem->deptParent];
		$userItem->deptParentName= $depts[$userItem->deptParent]->name;
		$userItem->deptGrade= $userItem->deptParentItem->grade;
		
	} else{
		
		$userItem->deptParentItem= null;
		$userItem->deptParentName= '';
		$userItem->deptGrade= 0;		
	}

	//组合出一个键
	$arrayIndex =  $userItem->deptGrade.$userItem->deptName .'-' . $userItem->code . $account;
	$userItem->existTodo = array_key_exists($account, $accountArrayExistTodo);
//	if(!$userItem->existTodo ) continue;
	$userDetails[$arrayIndex] = $userItem;
endforeach;	
//主键转换为gbk排序
$accountsort=array();
foreach($userDetails as $k=>$v){
	$k=iconv('UTF-8','GBK//IGNORE',$k);
	$accountsort[$k]=$v;
}

//排序
ksort($accountsort);

//排序后转换为UTF8
$userDetails=array();
foreach($accountsort as $k=>$v){
	$k=iconv('GBK','UTF-8//IGNORE',$k);
	$userDetails[$k]=$v;
}

?>
<html lang='zh-cn'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title><?php echo '加班表/出勤表－打印';?></title>
</head>
<body>
<div class='container mw-1400px'>
<body link="blue" vlink="purple">
	<table width="1400" border="1" cellpadding="0" cellspacing="0">
		<tr height="15">
			<td align="center" colspan='41'><font size='3'><?php echo $company->name;echo $dateArray['year']?>年<?php echo $dateArray['month']?>月 -- 加班表</font></td>
		</tr>
		<tr height="10">
			<td align="center" rowspan="3" width='80'><font size='2'>部门</font></td>
			<td align="center" rowspan="3" width='30'><font size='2'>序号</font></td>
			<td align="center" rowspan="3" width='120'><font size='2'>科室</font></td>
			<td align="center" rowspan="3" width='50'><font size='2'>工号</font></td>
			<td align="center" width='60'><font size='2'>类型</font></td>
			<?php
				//获得记录的日历
				$legalHoliDays = array();
				$normalDays= array();
				$weekendArray= array();
				$tempDay  = '';
				foreach($calendarArray as $currentDate => $calendar)
				{
					$tempDay = explode('-',$currentDate)[2];
					if($lang->kevinhours->hoursTypeLaw == $calendar->status) $legalHoliDays[] = (int)$tempDay;
					else if($lang->kevinhours->hoursTypeHol == $calendar->status) $weekendArray[] = (int)$tempDay;
					else $normalDays[] = (int)$tempDay;
				}
				//获得一个月有几天
				$days = cal_days_in_month(CAL_GREGORIAN,$dateArray['month'],$dateArray['year']);
				$monthEnd = 31;
				for($i=1;$i<=$days;$i++)
				{
					$date = $dateArray['year'] . '-' . $dateArray['month'] . '-' . $i;//日期
					$date = date('Y-m-d',strtotime($date));
					//是否存在于记录的日历中
					if(!array_key_exists($date, $calendarArray))
					{
						$unix=strtotime($date);//获得日期的 Unix 时间戳
						$week = date("w",$unix);//获得是周几,周末为0,周一为1
						if($week > 5 || $week < 1) $weekendArray[] = $i;
						else $normalDays[] = $i;
						
					}
					if(in_array($i, $normalDays)) echo "<td align='center'><font size='2'>W</font></td>";	
					else if(in_array($i, $legalHoliDays)) echo "<td align='center' style='background-color:#00ffff'><font size='2'>H</font></td>";
					else echo "<td align='center' style='background-color:#00ffff'><font size='2'>S</font></td>";
					
				}
				$actualDays = count($normalDays);
				if($days < $monthEnd)
				{
					for($i=$days+1; $i<=$monthEnd;$i++)
					{
						echo "<td align='center'><font size='2'>&nbsp;</font></td>";
					}
				}
			?>
			<td align="center"><font size='2'>W</font></td>
			<td align="center"><font size='2'>S</font></td>
			<td align="center"><font size='2'>H</font></td>
			<td align="center" colspan='2'><font size='2'>&nbsp;</font></td>
		</tr>
		<tr height="10">
			<td align="center" width="54"><font size='2'>日期</font></td>
			<?php 
				for($i=1;$i<=$days;$i++)
				{
					$backgroundColor = (in_array($i, $normalDays)) ? '' : "style='background-color:#00ffff'";
					echo "<td align='center' $backgroundColor><font size='2'>$i</font></td>";
				}
				if($days < $monthEnd)
				{
					for($i=$days+1; $i<=$monthEnd;$i++)
					{
						echo "<td align='center'><font size='2'>&nbsp;</font></td>";
					}
				}
			?>
			<td align="center" rowspan="2"><font size='2'>平时</font></td>
			<td align="center" rowspan="2"><font size='2'>周末</font></td>
			<td align="center" rowspan="2"><font size='2'>法定</font></td>
			<td align="center"><font size='2'>加班就餐</font></td>
			<td align="center"><font size='2'>中班</font></td>
			<td align="center"><font size='2'>日8进8出</font></td>
		</tr>
		<tr height="10">
			<td align="center" width="54"><font size='2'>星期&姓名</font></td>
			<?php
				$weekarray=array('日','一','二','三','四','五','六');
				for($i=1;$i<=$days;$i++)
				{
					$date = $dateArray['year'] . '-' . $dateArray['month'] . '-' . $i;//日期
					$unix=strtotime($date);//获得日期的 Unix 时间戳
					$week = date("w",$unix);//获得是周几,周末为0,周一为1
					$backgroundColor = (in_array($i, $normalDays)) ? '' : "style='background-color:#00ffff'";
					echo "<td align='center' $backgroundColor><font size='3'>$weekarray[$week]</font></td>";
				}
				if($days < $monthEnd)
				{
					for($i=$days+1; $i<=$monthEnd;$i++)
					{
						echo "<td align='center'><font size='2'>&nbsp;</font></td>";
					}
				}
			?>
			<td align="center" >次</td>
			<td align="center" >次</td>
			<td align="center" >次</td>
		</tr>

		<?php
			$id = 0;
			//遍历科室成员
			foreach($userDetails as $subuser):
				$account = $subuser->account;
				if('' == $subuser->account) continue;
					
				$realname = $subuser->realname;
				$code = $subuser->code;
				if('' == $code) $code = "&nbsp;";
				$deptParentName = $subuser->deptParentName;
				$deptName = $subuser->deptName;;
				$item = new stdClass();
				$id += 1;
				$item->id = $id;
				
				
				echo "<tr height='19'>";
				echo "<td align='center'><font size='1'>$deptParentName</font></td>";
				echo "<td align='center'>$id</td>";
				echo "<td align='center'><font size='1'>$deptName</font></td>";
				echo "<td align='center'>$code</td>";
				echo "<td align='center'><font size='2'>$realname</font></td>";
				
				if(!$subuser->existTodo)
				{//不存在todo时，直接打印空单元格
					for($tempIndex=1;$tempIndex<=34;$tempIndex++)
					{
						if($tempIndex <= $days)	$backgroundColor = (in_array($tempIndex, $normalDays)) ? '' : "style='background-color:#00ffff'";
						else $backgroundColor = '';
						echo "<td align='center' $backgroundColor>&nbsp;</td>";
					}
					echo "<td >&nbsp;</td><td >&nbsp;</td><td >&nbsp;</td>";
					echo "</tr>";
					continue;
				}
				
				$item->holidayOveHours = 0;
				$item->weekdayOveHours = 0;
				$item->weekendOveHours = 0;
				$item->oveEatingTimes = 0;
				$item->oveMidTimes = 0;//中班
				$item->oveNightTimes = 0;//夜班
				$item->oveDay8In8OutTimes = 0;//日8进8出
				$item->oveNight8In8OutTimes = 0;//夜8进8出
				
				$firstDay = 1;//从1号开始遍历
				$oveHoursArray = array();

				//遍历考勤
				foreach($todos as $todo):
					if($todo->account != $account)continue;//不是当前用户的
					$tempDateArray = array();
					$tempDateArray = explode("-",$todo->date);
					$tempDay = (int)$tempDateArray[2];//获得几号
					$hours = $this->kevinhours->showHours($todo->minutes);
					//获得加班日期数组对应的当天加班时间
					if(array_key_exists($tempDay, $oveHoursArray)) $oveHoursArray[$tempDay] += $hours;
					else $oveHoursArray[$tempDay] = $hours;
					//计算平时加班和周末加班时间
					if(in_array($tempDay, $weekendArray)) $item->weekendOveHours += $hours;
					else if(in_array($tempDay, $legalHoliDays)) $item->holidayOveHours += $hours;
					else $item->weekdayOveHours += $hours;
				endforeach;
				//打印加班时间
				for($j=$firstDay;$j<=$monthEnd;$j++)	{
					$isWeekDay = (in_array($j, $normalDays)) ;
					$backgroundColor = $isWeekDay ? '' : "style='background-color:#00ffff'";
					if(array_key_exists($j, $oveHoursArray)){
						//打印
						$hoursToShow = $oveHoursArray[$j];
						if($hoursToShow >= 4) $item->oveEatingTimes+=1;
						echo "<td align='center' $backgroundColor>$hoursToShow</td>";
						if($isWeekDay){
							if($hoursToShow>=5.5) $item->oveMidTimes+=1;//中班
							else if($hoursToShow>=3.5) $item->oveDay8In8OutTimes+=1;//日8进8出
						}
						else {
							if($hoursToShow>=12) $item->oveDay8In8OutTimes+=1;//日8进8出
						}
							
						//夜班和				$item->oveNightTimes = 0;//夜班
						//夜8进8出				$item->oveNight8In8OutTimes = 0;//夜8进8出
					}
					else	{
						echo "<td align='center' $backgroundColor>&nbsp;</td>";
					}
				}
				echo "<td align='center'>$item->weekdayOveHours</td>";
				echo "<td align='center'>$item->weekendOveHours</td>";
				echo "<td align='center'>$item->holidayOveHours</td>";
				echo "<td align='center'>$item->oveEatingTimes</td>";
				echo "<td align='center'>$item->oveMidTimes</td>";
				echo "<td align='center'>$item->oveDay8In8OutTimes</td>";
				echo "</tr>";
			endforeach
		?>
<?php include "./attendancestatistic.html.php";?>
</body>
</html>