<?php include '../../kevinhours/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php?>
<div class='side' id='treebox'>
	<a class='side-handle' data-id='kevinplanTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);
echo $this->lang->kevinplan->options;?> <strong>
			</div>	
			<div class='panel-body'>
				<ul class='tree'>
					<?php
					?>
				</ul>
			</div>
		</div>
	</div>
</div>	
<div class='main'>
	set up
</div>

<?php include '../../kevincom/view/footer.html.php';?>