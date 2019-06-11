<?php
include 'header.html.php';
?>
<?php 
//定义首页的列表
$MainItemList[]= array('module'=>'kevinsvn', 'method'=> 'index', 'name'=> 'SVN','desc'=>'SVN管理');
$MainItemList[]= array('module'=>'kevinuser', 'method'=> 'classlist', 'name'=> '用户','desc'=>'用户管理');
$MainItemList[]= array('module'=>'kevinclass', 'method'=> 'index', 'name'=> '分类','desc'=>'数据分类');
$MainItemList[]= array('module'=>'kevinerrcode', 'method'=> 'index', 'name'=> '错误码','desc'=>'错误代码');
$MainItemList[]= array('module'=>'kevincalendar', 'method'=> 'todo', 'name'=> '日历','desc'=>'日历、日志管理');
$MainItemList[]= array('module'=>'kevindevice', 'method'=> 'devlist', 'name'=> '设备','desc'=>'设备管理');
$MainItemList[]= array('module'=>'kevinstore', 'method'=> 'itemlist', 'name'=> '仓库','desc'=>'仓库管理');
$MainItemList[]= array('module'=>'kevinsoft', 'method'=> 'softlist', 'name'=> '软件','desc'=>'软件管理');
$MainItemList[]= array('module'=>'kevinkendoui', 'method'=> 'sample', 'name'=> 'Kendo','desc'=>'Kendo示例');
$MainItemList[]= array('module'=>'kevinchart', 'method'=> 'index', 'name'=> 'Echarts','desc'=>'百度Echarts表格示例');
?>
<div class='main' >
    <div class="cards">
      <?php foreach($MainItemList as $appItem){
          if(!common::hasPriv($appItem['module'],$appItem['method']))continue;//没有权限跳过
          ?>
          <div class="col-md-4 col-sm-6 col-lg-3">
            <a href="<?php echo helper::createLink($appItem['module'],$appItem['method']);?>" class="card" title="<?php echo $appItem['name'];?>">
              <div class="card-heading"><strong><?php echo $appItem['name'];?></strong></div>
              <div class="card-content text-muted"><?php echo $appItem['desc'];?></div>
            </a>
          </div>
      <?php }?>
    </div>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
