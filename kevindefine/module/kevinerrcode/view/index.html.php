<?php include '../../kevincom/view/header.html.php'; ?>

<div class="main" xmlns="http://www.w3.org/1999/html">
	<div id="grid"></div>
	<div id="details"></div>
	<div id="details_create"></div>
</div>

<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.common.min.css"/>
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.rtl.min.css"/>
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.silver.min.css"/>
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.mobile.all.min.css"/>

<script src="<?php echo $jsRoot; ?>kendoui/js/jquery.min.js"></script>
<script src="<?php echo $jsRoot; ?>kendoui/js/jszip.min.js"></script>
<script src="<?php echo $jsRoot; ?>kendoui/js/kendo.all.min.js"></script>
<script src="<?php echo $jsRoot; ?>kevin/kevin.js"></script>

<script language='Javascript'>

	var hasPriv = [];
	//hasPriv['list'] = <?php echo commonModel::hasPriv('kevinerrcode', 'getList'); ?>;
	hasPriv['create'] = <?php echo commonModel::hasPriv('kevinerrcode', 'create'); ?>;
</script>
<!--过按钮-->
<script id="kevinerrcode_list" type="text/x-kendo-template">
	<?php if (commonModel::hasPriv('kevinerrcode', 'getList')) { ?>
		<a class="k-button" id="kevinerrcode_list_link" href="\#" onclick="return kevinerrcode_list('all')"><?php echo $lang->kevinerrcode->refreash; ?></a>
	<?php } ?>
</script>
<!--获得-->
<script id="kevinerrcode_create" type="text/x-kendo-template">
	<?php if (commonModel::hasPriv('kevinerrcode', 'create')) { ?>
		<a class="k-button" id="kevinerrcode_create_link" href="\#" onclick="return kevinerrcode_create()"><?php echo $lang->kevinerrcode->create; ?></a>
	<?php } ?>
</script>

<!--编辑页面弹出的界面-->
<script type="text/x-kendo-template" id="template">
	<div id="details-container">
		<form class='form-condensed' method='post' target='_self' id='dataform'>
			<table class=' table-form' width='90%'>
				<tr>
					<th>id</th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_id" name="kevinerrcode_id" class="form-control" value="#= id#"
							       disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<th>code</th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="text" name="kevinerrcode_code" name="kevinerrcode_code" class="form-control"
							       value="#= code#" disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<th>title</th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="text" name="kevinerrcode_title" class="form-control" value="#= title#"
							       disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<th>filter</th>
					<td>
						<div class=" required-wrapper"></div>
						<div class="filterRadio">
							<label class="radio-inline"><input class="radioItem" type="radio" name="filter" value="1">YES</label>
							<label class="radio-inline"><input class="radioItem" type="radio" name="filter" value="2">NO</label>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-center" style="height:70px;">
						<button type="button" id="saveButton" class="btn btn-primary " data-loading="稍候..." return
						        onclick="kevinerrcode_edit_submit()">save
						</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</script>

<!--create弹出的界面-->
<script type="text/x-kendo-template" id="template_pdm">
	<div id="details-container">
		<form class='form-condensed' method='post' target='_self' id='dataform'>
			<table class=' table-form' width='90%'>
				<tr>
					<th>code</th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="text" name="kevinerrcode_code" class="form-control"
							       value="#= code#" disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<th>title</th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="text" name="kevinerrcode_title" class="form-control" value="#= title#"
							       disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-center" style="height:70px;">
						<button type="button" id="createButton" class="btn btn-primary " data-loading="稍候..." return
						        onclick="kevinerrcode_create_submit()">create
						</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</script>
<?php include '../../common/view/footer.html.php'; ?>
