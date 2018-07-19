<?php //如果有设定ID的权限，则显示id控件
if(common::hasPriv('kevindefine', 'setProjectID')):
$checkboxStr = html::checkbox('isEdit', '', 'checked', "onclick='setProjectDisabled(this);' 'class='form-control'");
$codeStr = html::input('id', '', "disabled='disabled' class='form-control'");
$tr = <<<EOT
<table class="table table-form"><tr>
  <th>Project ID</th>
  <td>$codeStr</td>
</tr></table>
EOT;
$checkString = <<<EOT
    <div class = 'heading'>
     {$checkboxStr} Set Project ID
    </div>
EOT;
?>
<script>
var appendHTML = <?php echo json_encode($tr);?>;
var appendcheckString = <?php echo json_encode($checkString);?>;
$('#name').parent().next().prepend(appendHTML);
$('#copyProjectModal').parent().prepend(appendcheckString);
function setProjectDisabled(switcher)
{
    if(switcher.checked)
    {
        $('#id').removeAttr('disabled');
    }
    else
    {
        $('#id').attr('disabled','disabled');
    }
}
</script>
<?php 
endif;