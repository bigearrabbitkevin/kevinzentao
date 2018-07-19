<div><?php echo "&nbsp&nbsp&nbsp&nbsp&nbsp".$this->lang->kevindevice->statisticType[$statisticType]; ?></div>
<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
<div id="mainChart" style="height:500px;border:0px solid #ccc;padding:0px;"></div>
<div>
	<table class='table table-condensed table-hover table-striped tablesorter w-400px' id='kevindeviceCountList'>
		<thead>
			<tr class='colhead text-center'>
				<th class='w-80px'>    <?php echo $lang->kevindevice->serial; ?></th>
				<th class='w-100px'>    <?php echo ($year)?$lang->kevindevice->month:$lang->kevindevice->year;?></th>
				<th class='w-100px'>    <?php echo $lang->kevindevice->totalCount; ?></th>
				<th class='w-100px'>    <?php echo $lang->kevindevice->sendoutCount; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$xData		 = '';
			$yData		 = '';
			$yData1		 = '';
			$yTitle		 = $lang->kevindevice->totalCount;
			$yTitle1	 = $lang->kevindevice->sendoutCount;
			$legendData	 = "'" . $yTitle . "','".$yTitle1."'";
			$i			 = 1;
			$sum=$sendsum=0;
			foreach ($items as $item):
				if (1 != $i) {
					$xData .= ",";
					$yData .= ",";
					$yData1 .= ",";
				}
				$xData .= "'" . $item->groupName . "'";
				$yData .= $item->totalCount;
				$yData1 .= $item->sendoutCount;
				?>
				<tr class='text-center'>
					<td><?php echo $i; ?></td>
					<td><?php echo $item->groupName; ?></td>
					<td><?php echo $item->totalCount;$sum+=$item->totalCount; ?></td>
					<td><?php echo $item->sendoutCount;$sendsum+=$item->sendoutCount; ?></td>
				</tr>
				<?php
				$i++;
			endforeach;
			?>
			<tr class='text-center'>
				<td></td>
				<td>SUM</td>
				<td><?php echo $sum; ?></td>
				<td><?php echo $sendsum; ?></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//--- 折柱 ---
	var myChart = echarts.init(document.getElementById('mainChart'));
	myChart.setOption({
		tooltip: {
			trigger: 'axis'
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
		calculable: true,
		xAxis: [
			{
				type: 'category',
                'axisLabel':{'interval':0<?php if(count($items)>15) echo ",'rotate':60";?>},
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
						color: 'rgba(110,139,61,1)',
						label: {
							show: true,
							position:'top'
						}
					}
				},
				data: [<?php echo $yData; ?>]
			},
			{
				name: '<?php echo $yTitle1; ?>',
				type: 'bar',
				itemStyle: {
					normal: {
						color: 'rgba(10,92,90,1)',
						label: {
							show: true,
							position:'top'
						}
					}
				},
				data: [<?php echo $yData1; ?>]
			}
		]
	});
	$("#mainChart").resize(function(){
		$(myChart).resize();
	});
</script>