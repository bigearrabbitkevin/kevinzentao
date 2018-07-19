<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include '../../common/view/tablesorter.html.php'; ?>
<?php include '../../common/view/datepicker.html.php';?>
<?php

$ProjectItem = & $kevinhourscount->ProjectItem;
	
//构造曲线数据
$xData		 = '';
$yData		 = '';
$legendData	 = "'" . $this->lang->kevinhours->product . "',";
$yTitle		 = '小时';
$i			 = 1;
$items = &$ProjectItem->HoursArray;
$totalHours	= 0.0;
//遍历成员
foreach ($items as $item):
	if (1 != $i) {
		$xData .= ",";
		$yData .= ",";
	}
	$item->Showhours = (int)($item->SumOfHours * 10) /10;
	$xData .= "'" . $item->realname . "'"; // "'" . substr($item->month ,-2). "'";
	$yData .= $item->Showhours;
	$i++;
endforeach;
?>
<div id='featurebar'>
    <ul class='nav'>
<?php if ($projectName): ?>
			<li class=""><a id="currentItem" href="javascript:showDropMenu('project', '100', 'kevinhours', 'project', '<?php echo "$type"; ?>', '<?php echo "$isShowDetail"; ?>')"><?php echo $projectName . '&nbsp;'; ?><span class="icon-caret-down"></span></a>
				<div id="dropMenu"><input class="form-control" id="search" value="" placeholder="搜索" type="text"></div>
			</li>
		<?php endif ?>
	<?php include './commontitlebar.html.php'; ?>
    </ul>
	<?php $state			 = '';
	if ('true' == $isShowDetail)
		$state			 = 'checked=checked';
	?>
	<div class='actions'><?php echo html::checkbox('isShowDetail', $lang->kevinhours->isShowDetail, 'checked', "$state onclick='changePageState(this);' 'class='form-control'"); ?></div>
</div>
<div class='main'>
	<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
	<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
	<div id="mainChart" style="height:550px;border:0px solid #ccc;padding:20px;"></div>
	<!-- ECharts单文件引入 -->
	<?php js::import($jsRoot . 'echarts/echarts.min.js');?>
	
  <table class=' w-600px table table-condensed table-hover table-striped tablesorter table-fixed' id='kevinhoursList'>
	<thead>
		<tr class='text-center'>
			<th class = 'w-id'><font size='3'>No.</font></th>
			<th class = 'w-auto'><font size='3'>科室</font></th>
			<th class = 'w-120px'><font size='3'>姓名</font></th>
			<th class = 'w-100px'><font size='3'>工号</font></th>
			<th class = 'w-id'><font size='3'>总计</font></th>
			<th class = 'w-id'><font size='3'>比例</font></th>
		</tr>
	</thead>
	<?php
	
	//遍历科室成员
	$i = 1;
	foreach ($items as $user):
		$user->totalPercent = ((int)($user->SumOfHours * 1000/$ProjectItem->totalHours)/10 ) .'%';
		?>
  	  <tr height='19'>
		<td align='center'><?php echo $i;?></td>
		<td align='center'><font size='3'><?php echo $user->deptName;?></font></td>
		<td class='text-center'><font size='4'><?php echo html::a($this->createLink('kevinhours', 'index', "type=$type&account={$user->account}"), $user->realname, '');?></font></td>
		<td class='text-center'><font size='3'><?php echo $user->code;?></font></td>
		<td class='text-right'><font size='3'><?php echo sprintf("%.1f", $user->SumOfHours);?></font></td>
		<td class='text-center'><font size='3'><?php echo $user->totalPercent ;?></font></td>
	  </tr>
	<?php 
		$i++;
	endforeach;?>
  </table>

  <table class=' w-600px table table-condensed table-fixed'>
	<tr class='text-center'>
		<th><font size='3'></font></th>
		<th><font size='3'>合计</font></th>
		<th></th>
		<th></th>
		<th class='w-id text-right'><font size='3'><?php echo sprintf("%.1f", $ProjectItem->totalHours);?></font></th>
		<th class = 'w-id'></th>
	</tr>
  </table>


<?php if ($pager != null) { ?>
	<br><br>
	<table class="table table-condensed table-hover" id='todoList'>
		<tr class='text-center'>
			<th class='w-id'>    <?php echo $lang->idAB; ?></th>
			<th class='w-date'>  <?php echo $lang->kevinhours->realname; ?></th>
			<th class='w-date'>  <?php echo $lang->kevinhours->date; ?></th>
			<th>                 <?php echo $lang->kevinhours->name; ?></th>
			<th class='w-100px'> <?php echo $lang->kevinhours->hourstype; ?></th>
			<th class='w-80px'>  <?php echo $lang->kevinhours->hours; ?></th>
			<th class='w-hour'>  <?php echo $lang->kevinhours->beginAB; ?></th>
			<th class='w-hour'>  <?php echo $lang->kevinhours->endAB; ?></th>
		</tr>
		<tbody>
	<?php $id = 0;
	
	foreach ($kevinhourscount->TodoList as $todo):$id+=1;
		?>
				<tr class='text-center'>
					<td class='text-left'><?php echo $id; ?></td>
					<td class='text-left'><?php echo $todo->realname; ?></td>
					<td><?php echo $todo->date == '2030-01-01' ? $lang->kevinhours->periods['future'] : $todo->date; ?></td>
					<td class='text-left'><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id&from=my", '', true), $todo->name, '', "data-toggle='modal' data-type='iframe' data-title='" . $lang->kevinhours->view . "' data-icon='check'"); ?></td>
					<td style="background-color:<?php echo $config->kevinhours->fontColor[$todo->hourstype]; ?>"><?php echo $lang->kevinhours->hoursTypeList[$todo->hourstype]; ?></td>
					<td><?php echo kevin::showWorkHours($todo->minutes); ?></td>
					<td><?php echo $todo->begin; ?></td>
					<td><?php echo $todo->end; ?></td>
				</tr>
	<?php endforeach; ?>
		</tbody>
		<tfoot><tr><td colspan='8'><?php $pager->show(); ?></td></tr></tfoot>
	</table>
<?php } ?>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script>
	var currentProjectID = '<?php echo $projectID; ?>';
	var nextMonth = '<?php echo $nextMonth; ?>';
	var lastMonth = '<?php echo $lastMonth; ?>';
	var thisMonth = '<?php echo $thisMonth; ?>';
	var methodName = '<?php echo 'project'; ?>';
	function changeDate(date)
	{
		var projectID = <?php echo $projectID; ?>;
		date = date.replace(/\-/g, '');
		link = createLink('kevinhours', 'project', 'projectID=' + projectID + '&type=' + date);
		location.href = link;
	}
	function changePageState(switcher)
	{
		var projectID = <?php echo $projectID; ?>;
		var type = "<?php echo $type; ?>";
		var link;
		if (switcher.checked)
		{
			link = createLink('kevinhours', 'project', 'projectID=' + projectID + '&type=' + type + '&isShowDetail=true');
		}
		else
		{
			link = createLink('kevinhours', 'project', 'projectID=' + projectID + '&type=' + type + '&isShowDetail=false');
		}
		location.href = link;
	}
</script>

<script type="text/javascript">
	//--- 折柱 ---
	var myChart = echarts.init(document.getElementById('mainChart'));
	myChart.setOption({
		title: {
		text: '<?php echo $ProjectItem->name; ?>',
				subtext: '工时统计',
				x: 'center'
		},
		tooltip: {
			trigger: 'axis'
		},
       	grid:{
            y2:'18%'
		},		
		legend: {
			data: [<?php echo $legendData; ?>]
		},
		toolbox: {
			show: true,
			feature: {
				mark: {show: true},
				dataView: {show: true, readOnly: false},
				magicType: {show: true, type: ['line', 'bar']},
				restore: {show: true},
				saveAsImage: {show: true}
			}
		},
		dataZoom: {
			show: true,
				realtime: true,
				start: 0,
				end: 100,
				y:'94%'
		},
		calculable: true,
		xAxis: [
			{
				type: 'category',
				'axisLabel': {'interval': 0<?php if (count($items) > 5 && count($items) < 10) echo ",'rotate':15";else if (count($items) > 10) echo ",'rotate':30"; ?>},
				data: [<?php echo $xData; ?>]
			}
		],
		yAxis: [
			{
				type: 'value',
				splitArea: {show: true}
			}
		],
		series: [
			{
				name: '<?php echo $yTitle; ?>',
				type: 'bar',
				itemStyle: {
					normal: {
						color:['#3398DB'],
						label: {
							show: false,
							position: 'top'
						}
					}
				},
				data: [<?php echo $yData; ?>]
			}
		]
	});
	$("#mainChart").resize(function(){
		$(myChart).resize();
	});
</script>