<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include '../../common/view/tablesorter.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>

<?php
$xData		 = '';
$yData		 = '';
$legendData	 = "'" . $this->lang->kevinhours->product . "',";
$yTitle		 = $this->lang->kevinhours->count;
$i			 = 1;

$ProductItem = & $this->kevinhours->ProductItem;
if(!$ProductItem || !$ProductItem->id) die(json_encode("Can not get your product with your id "));
$items = $ProductItem->projects;
$todos		 = & $ProductItem->todos;

//构造曲线数据
foreach ($items as $item):
	if (1 != $i) {
		$xData .= ",";
		$yData .= ",";
	}
	$item->hours = (int)($item->totalHours * 100) /100;
	$xData .= "'" . $item->name . "'"; // "'" . substr($item->month ,-2). "'";
	$yData .= $item->hours;
	$i++;
endforeach;
//die(json_encode($items));
?>
<div id='featurebar'>
	<ul class='nav'>
		<li class=""><a><?php echo $products[$productID] . '&nbsp;';?><span class='icon-caret-left'></span></a></li>
		<?php include './commontitlebar.html.php'; ?>
	</ul>
	<?php
	$state	 = '';
	if ('true' == $isShowDetail)
		$state	 = 'checked=checked';
	?>
	<div class='actions'><?php echo html::checkbox('isShowDetail', $lang->kevinhours->isShowDetail, 'checked', "$state onclick='changePageState(this);' 'class='form-control'"); ?></div>
</div>
<div class='side' id='treebox'>
	<a class='side-handle' data-id='productTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['product']); ?> <strong><?php echo '产品列表'; ?></strong></div>
			<div class='panel-body'>
				<ul class='tree'>
					<?php
					foreach ($products as $currentProductID => $currentProduct) {
						echo '<li>' . html::a(helper::createLink('kevinhours', 'product', "productID=$currentProductID&type=$type"), $currentProduct, '', "class='link'") . '</li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main'>
	<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
	<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
	<div id="mainChart" style="height:600px;border:0px solid #ccc;padding:20px;"></div>

	<!-- ECharts单文件引入 -->
	<?php js::import($jsRoot . 'echarts/echarts.min.js');?>

		<table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='kevinhoursList'>
			<thead>
				<tr class='text-center'>
					<th>项目名称</th>
					<th>项目代号</th>
					<th>正式</th>
					<th>外协</th>
					<th>总计</th>
					<th>比例</th>
					<th>比例图</th>
				</tr>
			</thead>
			<?php
			//遍历项目数组
			foreach ($items as $project) {
				?>
				<tr class='text-center'>
					<?php
					if ($this->loadModel('project')->checkPriv($project)) {
						echo "<td class='text-left'>" . html::a($this->createLink('kevinhours', 'project', "projectID={$project->id}&type=$type", '', true)
							, '<i class="icon icon-comment-line"></i>', '', "data-toggle='modal' data-type='iframe' title='$lang->modalTip'") . ' '
						. html::a($this->createLink('kevinhours', 'project', "projectID={$project->id}&type=$type"), $project->name) . "</td>";
					}
					else {
						echo "<td class='text-left text-muted'><i title=\"{$lang->kevinhours->accessDenied}\" class=\"icon-ban-circle\"></i> {$project->name}</td>";
					}
				
					?>
					<td><?php echo $project->id;?></td>
					<td><?php echo $project->formalHours*60;?></td>
					<td><?php echo $project->informalHours*60;?></td>
					<td><?php echo $project->totalHoursShow;?></td>
					<td><?php echo $project->totalPercent;?></td>
					<td class='text-left'><svg width=<?php echo $project->totalPercent;?> height='20' version='1.1'><rect x='0' y='0' width=100% height=100% style='fill:green'/></svg>
					</td>
				</tr>
			<?php	}		?>
		</table>
		<table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='kevinhoursList'>
			<tr class='text-center'>
				<th><font size='3'>合计</font></th>
				<th></th>
				<th><font size='3'>
					<?php echo $this->kevinhours->showHours($ProductItem->productforHours*60, 2); ?>
					</font></th>
				<th><font size='3'>
					<?php echo $this->kevinhours->showHours($ProductItem->productinfHours*60, 2); ?>
					</font></th>
				<th><font size='3'>
					<?php
					$projectTotalHours = $this->kevinhours->showHours($ProductItem->productHours*60, 2);
					echo $projectTotalHours;
					?>
					</font></th>
				<th></th>
				<th></th>
			</tr>
		</table>
		<?php if ($pager != null) { ?>
			<br>
			<table class='table table-condensed table-hover table-striped table-fixed' id='todoList'>
				<thead><tr><td colspan='8'><?php $pager->show(); ?></td></tr></thead>
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
					<?php
					$id = 0;
					foreach ($todos as $todo):$id+=1;
						?>
						<tr class='text-center'>
							<td class='text-left'><?php echo $id; ?></td>
							<td class='text-left'><?php echo $todo->realname; ?></td>
							<td><?php echo $todo->date == '2030-01-01' ? $lang->kevinhours->periods['future'] : $todo->date; ?></td>
							<td class='text-left'><?php echo html::a($this->createLink('todo', 'view', "id=$todo->id&from=my", '', true), $todo->name, '', "data-toggle='modal' data-type='iframe' data-title='" . $lang->kevinhours->view . "' data-icon='check'"); ?></td>
							<td style="background-color:<?php echo $config->kevinhours->fontColor[$todo->hourstype]; ?>"><?php echo $lang->kevinhours->hoursTypeList[$todo->hourstype]; ?></td>
							<td><?php echo $this->kevinhours->showWorkHours($todo->minutes); ?></td>
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
	var currentProductID = '<?php echo $productID; ?>';
	var nextMonth = '<?php echo $nextMonth; ?>';
	var lastMonth = '<?php echo $lastMonth; ?>';
	var thisMonth = '<?php echo $thisMonth;?>';
	var methodName = '<?php echo 'product'; ?>';
	function changeDate(date)
	{
		var productID = <?php echo $productID; ?>;
		date = date.replace(/\-/g, '');
		link = createLink('kevinhours', 'product', 'productID=' + productID + '&type=' + date);
		location.href = link;
	}
	function changePageState(switcher)
	{
		var productID = <?php echo $productID; ?>;
		var type = "<?php echo $type; ?>";
		var link;
		if (switcher.checked)
		{
			link = createLink('kevinhours', 'product', 'productID=' + productID + '&type=' + type + '&isShowDetail=true');
		}
		else
		{
			link = createLink('kevinhours', 'product', 'productID=' + productID + '&type=' + type + '&isShowDetail=false');
		}
		location.href = link;
	}
</script>


<script language='Javascript'>
	var currentAccount = '<?php echo $this->kevinhours->account; ?>';
	var nextMonth = '<?php echo $nextMonth; ?>';
	var lastMonth = '<?php echo $lastMonth; ?>';
	var thisMonth = '<?php echo $thisMonth; ?>';
	var currentdeptID='<?php echo $this->session->currentdeptID;?>';
	var type='<?php echo $type;?>';
	var methodName = 'over';
</script>
<script type="text/javascript">
	//--- 折柱 ---
	var myChart = echarts.init(document.getElementById('mainChart'));

	myChart.setOption({
		title: {
		text: '<?php echo $ProductItem->name; ?>',
				subtext: '工时统计',
				x: 'center'
		},
		tooltip: {
			trigger: 'axis'
		},
       	grid:{
            y2:'25%'
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