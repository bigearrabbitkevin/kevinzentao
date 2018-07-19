<?php
/**
 * The browse user view file of kevindevice module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevindevice
 */
?>
<?php include '../../kevincom/view/header.html.php';?>
<?php include 'kevindevicebar.html.php';
$canedit=common::hasPriv('kevindevice', 'sendoutedit');
$candelete=common::hasPriv('kevindevice', 'sendoutdelete');
$cancreate=common::hasPriv('kevindevice', 'sendoutcreate');?>
<div class='side'>
	<a class='side-handle' data-id='companyTree'><i class='icon-caret-left'></i></a>
	<div class='side-body'>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?>
			<strong><?php common::printLink('kevindevice', 'maintainlist', "", $lang->kevindevice->filetoparse);$url=helper::createLink('kevindevice', 'spotcheck');?></strong></div>
			<div class='panel-body'>
				<!--<form id='searchform' method="post" name="data" <?php // echo 'action=' . $url; ?> >-->
					<form class='form-condensed' method='post' enctype='multipart/form-data'  <?php echo 'action=' . $url; ?>>
					<table class='table table-form table-condensed '>				
						<tr>			
							<th class='text-left w-150px'><?php echo $lang->kevindevice->spotchkfile; ?></th>
						</tr>			
						<tr id='fileBox'>
							<?php js::set('dangerFiles', $this->config->file->dangers);$percent=0.9;
 /* Define the html code of a file row. */
  $fileRow = <<<EOT
  <table class='fileBox' id='fileBox\$i'>
    <tr>
      <td><div class='form-control file-wrapper'><input type='file' name='files\$i[]' class='fileControl w-150px' multiple tabindex='-1' onchange='checkSizeAndType(this)'/></div></td>
    </tr>
  </table>
EOT;
							for($i = 1; $i <= 5; $i ++) echo str_replace('$i', $i, $fileRow);?>
						</tr>	
						<tr style='height: 50px'><th></th></tr>
						<tr>			
							<th class='text-left w-150px'><b><?php echo $lang->kevindevice->spotchkitem; ?></b></th>
						</tr>
						<tr>
							<td class='w-600px' style="word-wrap:break-word;"><?php echo html::input('spotchkitem','', "class=form-control");?></td>
						</tr>
						<tr><th class='text-left w-150px'></th></tr>
						<tr>
							<td class='text-left' colspan="8"><button type="submit" id="submit" class="btn btn-primary" data-loading="稍候..."><?php echo $lang->kevindevice->spotcheck;?></button></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div class='panel panel-sm'>
			<div class='panel-heading nobr'><?php echo html::icon($lang->icons['company']);?>
			<strong><?php common::printLink('kevindevice', 'sendoutlist', "", $lang->kevindevice->year);?></strong></div>
			<div class='panel-body'>
				<ul class="tree treeview">
					<?php foreach ($years as $year):
						?>
						<li><?php common::printLink('kevindevice', 'sendoutlist', "year=$year", $year);?>
						<?php if($selectyear==$year)echo "<i class='icon-lightbulb'></i>";?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class='main' style="overflow:auto; ">
<table class='table table-condensed table-hover tablesorter ' id='DeviceList'>
	<thead>
		<tr class='colhead'>
			<?php $vars	 = "year=$selectyear&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
			<th><?php common::printOrderLink('time', $orderBy, $vars, $lang->kevindevice->time);?></th>
			<th><?php common::printOrderLink('sendout', $orderBy, $vars, $lang->kevindevice->sendoutCount);?></th>
			<th><?php common::printOrderLink('total', $orderBy, $vars, $lang->kevindevice->totalCount);?></th>
			<th><?php echo $lang->actions;?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($sendouts as $sendout):?>
			<tr class='text-center'>
				<td><?php echo $sendout->time;?></td>
				<td><?php echo $sendout->sendout;?></td>
				<td><?php echo $sendout->total;?></td>
				<td class='text-center'>
					<?php
					if ($canedit)common::printIcon('kevindevice', 'sendoutedit', "sendoutID={$sendout->id}", '', '', 'pencil','','iframe',true);
					if ($candelete)common::printIcon('kevindevice', 'sendoutdelete', "sendoutID={$sendout->id}", '', '', 'remove', 'hiddenwin');
					?>
				</td>
			</tr>
<?php endforeach;?>
			<tr class='text-center'>
				<td><?php echo $lang->kevindevice->subtotal;?></td>
				<td><?php echo $sumsendout;?></td>
				<td><?php echo $sumtotal;?></td>
				<td></td>
			</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan='12'>
				<?php echo $pager->show();?>
			</td>
		</tr>
	</tfoot>
</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script language='Javascript'>
$('.fileControl').attr('multiple','multiple');
$('.btn-block').click(function(){
	$('.fileControl').attr('multiple','multiple');
});
$(function()
{
    var maxUploadInfo = maxFilesize();
    parentTag = $('#fileform').parent();
    if(parentTag.get(0).tagName == 'TD')
    {
        parentTag.parent().find('th').append(maxUploadInfo); 
    }
    if(parentTag.get(0).tagName == 'FIELDSET')
    {
        parentTag.find('legend').append(maxUploadInfo);
    }
});
function reload(){
	link=creatLink('sklawreport','filelist','');
	window.location.href=link;
}
/**
 * Check file size and type.
 * 
 * @param  obj $obj 
 * @access public
 * @return void
 */
function checkSizeAndType(obj)
{
    if(typeof($(obj)[0].files) != 'undefined')
    {
        var maxUploadInfo = '<?php echo strtoupper(ini_get('upload_max_filesize'));?>';
        var sizeType = {'K': 1024, 'M': 1024 * 1024, 'G': 1024 * 1024 * 1024};
        var unit = maxUploadInfo.replace(/\d+/, '');
        var maxUploadSize = maxUploadInfo.replace(unit,'') * sizeType[unit];
        var fileSize = 0;
        $(obj).closest('#fileform').find(':file').each(function()
        {
            /* Check file type. */
            fileName = $(this)[0].files[0].name;
            dotPos   = fileName.lastIndexOf('.');
            fileType = fileName.substring(dotPos + 1);
            if((',' + dangerFiles + ',').indexOf((',' + fileType + ',')) != -1) alert('<?php echo $lang->kevindevice->dangerFile?>');

            if($(this).val()) fileSize += $(this)[0].files[0].size;
        })
        if(fileSize > maxUploadSize) alert('<?php echo $lang->kevindevice->errorFileSize?>');//Check file size.
    }
}

/**
 * Show the upload max filesize of config.  
 */
function maxFilesize(){return "(<?php printf($lang->kevindevice->maxUploadSize, ini_get('upload_max_filesize'));?>)";}

/**
 * Set the width of the file form.
 * 
 * @param  float  $percent 
 * @access public
 * @return void
 */
function setFileFormWidth(percent)
{
    totalWidth = Math.round($('#fileform').parent().width() * percent);
    titleWidth = totalWidth - $('.fileControl').width() - $('.fileLabel').width() - $('.icon').width();
    if($.browser.mozilla) titleWidth  -= 8;
    if(!$.browser.mozilla) titleWidth -= 12;
    $('#fileform .text-3').css('width', titleWidth + 'px');
};

/**
 * Update the file id labels.
 * 
 * @access public
 * @return void
 */
function updateID()
{
    i = 1;
    $('.fileID').each(function(){$(this).html(i ++)});
}

$(function(){setFileFormWidth(<?php echo $percent;?>)});
</script>