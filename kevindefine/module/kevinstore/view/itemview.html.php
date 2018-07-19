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
<div class='main'>
	<table class='table ' width = '100%'> 
		<tr>
			<th class='w-80px'><?php echo $lang->kevinstore->number; ?></th>
			<td class='w-auto'><?php echo $item->number; ?></td>
			<th class='w-80px'><?php echo $lang->kevinstore->name; ?></th>
			<td class='w-auto'><?php echo $item->name; ?></td>
		</tr>  
		<tr>
			<th><?php echo $lang->kevinstore->group; ?></th>
			<td><?php echo $item->group; ?></td>
			<th><?php echo $lang->kevinstore->subType; ?></th>
			<td><?php echo $item->subType; ?></td>
		</tr>
	</table>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
