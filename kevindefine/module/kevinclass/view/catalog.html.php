<?php include '../../kevincom/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('path', $node ? explode(',', $node->path) : array(0));?>
<div class='row'>
<div class='col-md-3'>
    <nav class='booksNav'>
      <ul class='nav nav-primary nav-stacked'>
        <li class='nav-heading'><strong><?php echo $lang->kevinclass->bookList;?></strong></li>
        <?php
        if(!empty($books))
        {
            foreach($books as $menu)
            {
                echo '<li' . (($menu->title == $kevinclass->title) ? " class='active'" : '') . '>' . html::a(inlink('admin', "bookID=$menu->id"), '<i class="icon-book"></i> &nbsp;' . $menu->title) . '</li>';
            }
        }
        ?>
		<li> <?php echo html::a(helper::createLink('kevinclass', 'create', '', '', true)
				, '<i class="icon-plus"></i> &nbsp;' .$this->lang->kevinclass->createBook, '', "data-toggle='modal' data-type='iframe'");?></li>
      </ul>
    </nav>
  </div>
  <div class='col-md-9'>
<form id='ajaxForm' method='post'>
<div class='panel'>
  <div class='panel-heading'><strong><i class='icon-list-ul'></i> <?php echo $node->title . " <i class='icon-angle-right'></i> " . $lang->kevinclass->catalog;?></strong></div>
  <table class='table'>
    <thead>
      <tr class='text-center'>
        <th class='w-p10'><?php echo $lang->kevinclass->type;?></th>
        <th class='w-p10'><?php echo $lang->kevinclass->author;?></th>
        <th><?php echo $lang->kevinclass->title;?></th>
        <th class='w-300px'><?php echo $lang->kevinclass->alias;?></th>
        <th class='w-p10'><?php echo $lang->kevinclass->keywords;?></th>
        <th class='w-180px'><?php echo $lang->kevinclass->addedDate;?></th>
        <th class='w-80px'><?php echo $lang->actions; ?></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($children as $child):?>
      <tr class='text-center text-middle'>
        <td><?php echo html::select("type[$child->id]",     $lang->kevinclass->typeList, $child->type, "class='form-control'");?></td>
        <td><?php echo html::input("author[$child->id]",    $child->author,    "class='form-control'");?></td>
        <td><?php echo html::input("title[$child->id]",     $child->title,     "class='form-control'");?></td>
        <td><?php echo html::input("alias[$child->id]",     $child->alias,     "class='form-control'");?></td>
        <td><?php echo html::input("keywords[$child->id]",  $child->keywords,  "class='form-control'");?></td>
        <td><?php echo html::input("addedDate[$child->id]", $child->addedDate, "class='form-control date'");?></td>
        <td>
          <?php echo html::hidden("order[$child->id]", $child->order, "class='order'");?>
          <?php echo html::hidden("mode[$child->id]", 'update');?>
          <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
        </td>
      </tr>
      <?php endforeach;?>

      <?php for($i = 0; $i < $this->config->kevinclass->newCatalogCount ; $i ++):?>
      <tr class='text-center'>
        <td><?php echo html::select("type[]", $lang->kevinclass->typeList, '', "class='form-control'");?></td>
        <td><?php echo html::input("author[]", $app->user->realname, "class='form-control'");?></td>
        <td><?php echo html::input("title[]", '', "class='form-control'");?></td>
        <td><?php echo html::input("alias[]", '', "class='form-control' placeholder='{$lang->kevinclass->aliasTip}'");?></td>
        <td><?php echo html::input("keywords[]", '', "class='form-control'");?></td>
        <td><?php echo html::input("addedDate[]", helper::now(), "class='form-control date'");?></td>
        <td>
          <?php echo html::hidden("order[]", '', "class='order'");?>
          <?php echo html::hidden("mode[]", 'new');?>
          <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
        </td>
      </tr>
      <?php endfor;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='5' class='a-center'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
</form>
</div>
</div>
<?php include '../../kevincom/view/footer.html.php';?>
