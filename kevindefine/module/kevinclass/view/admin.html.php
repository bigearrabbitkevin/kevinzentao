<?php include '../../kevincom/view/header.html.php';?>
<?php 
$path = explode(',', $node->path);
js::set('path', $path);
js::set('confirmDelete', $lang->kevinclass->confirmDelete);
?>
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
  <div class='panel panel-block'>
  <div class='panel-heading'>
    <strong><i class='icon-book'></i> <?php echo $kevinclass->title;?></strong>
	<div class="panel-actions"><strong class='btn'><?php echo html::a(helper::createLink('kevinclass', 'create', '', '', true)
				, '<i class="icon-plus"></i> ' .$this->lang->kevinclass->createBook, '', "data-toggle='modal' data-type='iframe'");?></strong></div>
		</div>
  </div>
  <div class='panel-body'><div class='books' id='itemList'><?php echo $catalog;?></div></div>
  </div>
  </div>
<?php include '../../kevincom/view/footer.html.php';?>
