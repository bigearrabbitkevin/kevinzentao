<?php
include '../../../common/view/m.header.lite.html.php';
?>
<div data-role="content">
  <form class='form-condensed' method='post' target='hiddenwin'>
    <table align='center'>
	  <tr>
        <th colspan='2'><?php echo $lang->my->changePassword; ?></th>
	  </tr> 
      <tr>
        <th><?php echo $lang->user->account; ?></th>
        <td><?php echo $user->account . html::hidden('account', $user->account); ?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password; ?></th>
        <td><?php echo html::password('password1', '', "class='form-control'"); ?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->user->password2; ?></th>
        <td><?php echo html::password('password2', '', "class='form-control'"); ?></td>
      </tr>
      <tr>
        <td></td>
        <td><?php echo html::submitButton('', '', 'btn-primary') . html::backButton(); ?></td>
      </tr>
    </table>
  </form>  
</div>
<?php include '../../../common/view/m.footer.lite.html.php'; ?>