<div><?php echo "&nbsp&nbsp&nbsp&nbsp&nbsp".$this->lang->kevinplan->statisticType[$statisticType]; ?></div>
<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
<div id="mainChart" style="height:600px;border:0px solid #ccc;padding:20px;"></div>
<div>
	<table class='table table-condensed table-hover table-striped tablesorter w-400px' id='kevinplanCountList'>
		<thead>
			<tr class='colhead text-center'>
				<th class='w-80px'>    <?php echo $this->lang->kevinplan->serial; ?></th>
				<th class='w-200px text-left'>    <?php echo $this->lang->kevinplan->$statisticType; ?></th>
				<th class='w-100px'>    <?php echo $lang->kevinplan->yValueName; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$xData		 = '';
			$yData		 = '';
			$legendData	 = "'" . $lang->kevinplan->statisticType[$statisticType] . "',";
			$yTitle		 = $lang->kevinplan->statisticType[$statisticType];
			$i			 = 1;
			foreach ($items as $item):
				if (1 != $i) {
					;
					$xData .= ",";
					$yData .= ",";
				}
				$xData .= "'" . $item->XValue . "'";
				$yData .= $item->YValue;
				?>
				<tr class='text-center'>
					<td><?php echo $i; ?></td>
					<td class='text-left'><?php echo $item->XValue; ?></td>
					<td><?php echo $item->YValue; ?></td>
				</tr>
				<?php
				$i++;
			endforeach;
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//--- 折柱 ---
	var myChart = echarts.init(document.getElementById('mainChart'));
	myChart.setOption({
            	grid:{
                    y2:'20%'
		},
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
				'axisLabel': {'interval': 0<?php if (count($items) > 10) echo ",'rotate':35"; ?>},
				data: [<?php echo $xData; ?>]
			}
		],
		yAxis: [
			{
				type: 'value',
				splitArea: {show: true}
			}
		],
		grid: {
			y2: '30%'
		},
		dataZoom: {
			show: true,
			realtime: true,
			start: 0,
			end: 100,
			y: '94%'
		},		
		series: [
			{
				name: '<?php echo $yTitle; ?>',
				type: 'bar',
				itemStyle: {
					normal: {
						color: 'rgba(51,152,219,1)',
						label: {
							show: true,
							position: 'top',
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