<?php
/**
 * kevin calendar
 * @copyright   kevin
 */
?>
<?php include '../../kevinhours/view/header.html.php'; ?>
<?php include '../../common/view/datepicker.html.php'; ?>
<script language='Javascript'>var account = '<?php echo $this->kevinhours->account; ?>'</script>
<?php
include 'titlebar.html.php';
echo $overtreetitle;
if ($CalendarTableString == "") {
	$warningString = '<h1>Get calendar array error!</h1>';
	echo $warningString;
} else {
	?>
	<div class="main" >
	  <table width = 100%>
		<tr style="vertical-align: top;"><td><?php echo $oveHoursTableString . $CalendarTableString; ?></td></tr>
	  </table>	
	  <?php include 'todolockwarning.html.php'; ?>
	</div>
	<?php
}
include '../../kevincom/view/footer.html.php';
?>
