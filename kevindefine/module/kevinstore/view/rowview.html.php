<?php
/**
 * The view file of user file of kevinstore module
 *
 * @copyright   Kevin
 * @author      kevin<3301647@qq.com>
 * @package     kevinstore
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php if (!$rowItem) die("no row exist!"); ?>
<div id='titlebar'>
	<div class='heading'>
		<span class='prefix' title='LIST'><strong><?php echo $title; ?></strong></span>
		<strong><?php echo $rowItem->name; ?> (<small><?php echo $rowItem->id; ?></small>)</strong>
	</div>
</div>
<div class='main'>
	<table class='table ' width = '100%'> 
		<tr>
			<th class='w-80px'><?php echo $lang->kevinstore->number; ?></th>
			<td class='w-auto'><?php echo $rowItem->number; ?></td>
			<th class='w-80px'><?php echo $lang->kevinstore->name; ?></th>
			<td class='w-auto'><?php echo $rowItem->name; ?></td>
		</tr>  
		<tr>
			<th><?php echo $lang->kevinstore->actionType; ?></th>
			<td><?php echo $rowItem->actionType; ?></td>
			<th><?php echo $lang->kevinstore->actionTime; ?></th>
			<td><?php echo $rowItem->actionTime; ?></td>
		</tr>
		<tr>
			<th><?php echo $lang->kevinstore->stdPrice; ?></th>
			<td><?php echo $rowItem->stdPrice; ?></td>
			<th><?php echo $lang->kevinstore->totalPrice; ?></th>
			<td><?php echo $rowItem->totalPrice; ?></td>
		</tr>
		<tr>
			<th><?php echo $lang->kevinstore->count; ?></th>
			<td><?php echo $rowItem->count; ?></td>
			<th><?php echo $lang->kevinstore->usage; ?></th>
			<td><?php echo $rowItem->usage; ?></td>
		</tr>
		<tr>
			<th><?php echo $lang->kevinstore->suplier; ?></th>
			<td><?php echo $rowItem->suplier; ?></td>
			<th><?php echo $lang->kevinstore->actionMonth; ?></th>
			<td><?php echo $rowItem->actionMonth; ?></td>
		</tr>

		<tr>
			<th><?php echo $lang->kevinstore->group; ?></th>
			<td><?php echo $rowItem->group; ?></td>
			<th><?php echo $lang->kevinstore->subType; ?></th>
			<td><?php echo $rowItem->subType; ?></td>
		</tr>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
