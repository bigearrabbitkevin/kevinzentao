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
	var g_ztModuleLang = <?php echo json_encode($lang->kevinerrcode);?>;
	var g_hasPriv = [];
	g_hasPriv['getList'] = <?php echo commonModel::hasPriv('kevinerrcode', 'getList')?1:0; ?>;
	g_hasPriv['create'] = <?php echo commonModel::hasPriv('kevinerrcode', 'create')?1:0; ?>;
	g_hasPriv['edit'] = <?php echo commonModel::hasPriv('kevinerrcode', 'create')?1:0; ?>;
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
					<th><?php echo $lang->kevinerrcode->id; ?></th>
					<td>
						<label for="kevinerrcode_id" >#= id#</label>
					</td>
					<th><?php echo $lang->kevinerrcode->code; ?></th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_code" name="kevinerrcode_code" class="form-control" value="#= code#">
						</div>
					</td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->status; ?></th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_status" name="kevinerrcode_status" class="form-control" value="#= status#">
						</div>
					</td>
					<th><?php echo $lang->kevinerrcode->project; ?></th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_project" name="kevinerrcode_project" class="form-control" value="#= project#">
						</div>
					</td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->createdBy; ?></th>
					<td>
						<label for="kevinerrcode_createdBy" >#= createdBy#</label>
					</td>
					<th><?php echo $lang->kevinerrcode->createdDate; ?></th>
					<td>
						<label for="kevinerrcode_createdDate" >#= createdDate#</label>
					</td>					
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->name; ?></th>
					<td colspan = 3>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_name" name="kevinerrcode_name" class="form-control" value="#= name#">
						</div>
					</td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->nameEn; ?></th>
					<td colspan = 3>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_nameEn" name="kevinerrcode_nameEn" class="form-control" value="#= nameEn#">
						</div>
					</td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->description; ?></th>
					<td colspan = 3>
						<div class=" required-wrapper"></div>
						<div>
							<input type="label" id="kevinerrcode_description" name="kevinerrcode_description" class="form-control" value="#= description#">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="text-center" style="height:70px;">
						<button type="button" id="saveButton" class="btn btn-primary " data-loading="稍候..." return
						        onclick="kevinerrcode_edit_submit(#= id#)">save
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
					<th><?php echo $lang->kevinerrcode->code; ?></th>
					<td>
						<div class=" required-wrapper"></div>
						<div>
							<input type="text" name="kevinerrcode_code" class="form-control"
							       value="#= code#" disabled="disabled">
						</div>
					</td>
				</tr>
				<tr>
					<th><?php echo $lang->kevinerrcode->title; ?></th>
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
