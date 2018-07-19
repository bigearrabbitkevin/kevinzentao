<div><?php echo "&nbsp&nbsp&nbsp&nbsp&nbsp".$this->lang->kevinstore->statisticType[$statisticType]; ?></div>
<!--Step:1 Prepare a dom for ECharts which (must) has size (width & hight)-->
<!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->
<div id="mainChart" style="height:500px;border:0px solid #ccc;padding:0px;"></div>
<div>
	<table class='table table-condensed table-hover table-striped tablesorter w-400px' id='kevinstoreCountList'>
		<thead>
			<tr class='colhead text-center'>
				<th class='w-80px'>    <?php echo $this->lang->kevinstore->serial; ?></th>
				<th class='w-200px text-left'>    <?php echo $this->lang->kevinstore->$statisticType; ?></th>
				<th class='w-100px'>    <?php echo $this->lang->kevinstore->storeCount; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$xData		 = '';
			$yData		 = '';
			$legendData	 = "'" . $this->lang->kevinstore->storeCount . "',";
			$yTitle		 = $this->lang->kevinstore->storeCount;
			$i			 = 1;
			foreach ($items as $item):
				if (1 != $i) {
					;
					$xData .= ",";
					$yData .= ",";
				}
				$xData .= "'" . $item->groupName . "'";
				$yData .= $item->storeCount;
				?>
				<tr class='text-center'>
					<td><?php echo $i; ?></td>
					<td class='text-left'><?php echo $item->groupName; ?></td>
					<td><?php echo $item->storeCount; ?></td>
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
			}
		]
	});
	$("#mainChart").resize(function(){
		$(myChart).resize();
	});
</script>