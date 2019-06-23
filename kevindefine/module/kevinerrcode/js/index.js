//本js的公共变量
var m_wnd_create, m_detailsTemplate_create;
var m_dataItem_create = '';//表格行内数据
var m_tr_create;
var kevin_ModelWnd;

//初始化
$(document).ready(function () {
	kevin_base_initialParam();//initial hv param
	$("#grid").kendoGrid();//初步的初始化

	//设定输出Excel的文件名
	kevin_kendogrid_fileName = 'kevinerrcode_Kendo_UI_Grid_Export_' + kevin_today + '.xlsx';

	//统一的工具条设定
	kevin_kendogrid_toolbar =
		[
			{template: kendo.template($("#kevinerrcode_list").html())},
			{template: kendo.template($("#kevinerrcode_create").html())},
			"excel"
		];
	//初始化
	kevinerrcode_details_initial();
	// kevinerrcode_create_initial();

	//默认加载List
	kevinerrcode_list();

});

//----------------------------
//functions for kevinerrcode list
//------------------------

//initial for kevinerrcode_details_initial
function kevinerrcode_details_initial() {
	//初始化模态窗口
	kevin_ModelWnd = $("#details").kendoWindow({
		//窗口的名字
		title: "错误代码",
		//调整window的窗体位置 top为距离顶端距离，left为左边
		position: {
			left: "150px"
		},
		modal: true,
		visible: false,
		resizable: false,
		width: 500
	}).data("kendoWindow");
	kevin_detailsTemplate = kendo.template($("#template").html());
}

//kevinerrcode 编辑界面的保存提交按钮 save
function kevinerrcode_edit_submit(iId) {
	iId = Number(iId)

	//获得输入框内project值
	var dataForm = {};
	dataForm.id = iId;
	dataForm.project = $("input[name='kevinerrcode_project']").val();
	dataForm.code = $("input[name='kevinerrcode_code']").val();
	//获得输入框内name值
	dataForm.name = $("input[name='kevinerrcode_name']").val();
	//获得输入框内nameEn值
	dataForm.nameEn = $("input[name='kevinerrcode_nameEn']").val();
	//获得输入框内status值
	dataForm.status = $("input[name='kevinerrcode_status']").val();
	//获得输入框内description值
	dataForm.description = $("input[name='kevinerrcode_description']").val();

	//ajax请求获取后台数据
	url = createLink("kevinerrcode", "edit", "", 'json');
	$.ajax({
		type: 'POST',
		url: url,
		dataType: "json",
		data: dataForm,
		success: function (errData) {
			//判断msg
			var ret = kevin_CheckJsonReturnData(errData);	//处理判断返回值
			if (ret.isError()) {//弹出错消息提示
				alert("错误:" + ret.errmsg);
				return;
			}
			
			//如果正确，才进行下面的修改	
			var newItem = errData.data.newItem;
			$.each(newItem, function(key, val) {  
				if(kevin_kendogrid_dataItem[key] != val){
					kevin_kendogrid_dataItem.set(key, val);
					console.log("Modify Key:" + key + ", Value:" + val); 
				}
			});  

			//window弹出框隐藏
			$('.k-window').css('display', 'none');
			//模态隐藏
			$('.k-overlay').css('display', 'none');
		},
		error: function (data) {
			alert(data.responseText);//警告提示msg
		}
	})
}

//编辑框点击事件
function kevinerrcode_edit_Details(e) {
	//e就是编辑按钮
	e.preventDefault();

	// dataItem 为按钮所在的当前 行 元素
	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
	//console.log("dataItem:");console.log(dataItem);
	if(dataItem.id <1){
		alert("id = 0 不能编辑");
		return;
	}
	
	if(dataItem.status != 100){
		alert("只有草稿状态的条目可以修改。当前状态=" + dataItem.statusName);
		return;
	}
	//对kevin_dataItem进行赋值
	kevin_kendogrid_dataItem = dataItem;
	kevin_ModelWnd.content(kevin_detailsTemplate(dataItem));
	kevin_ModelWnd.open();

}

//kevinerrcodeList
function kevinerrcode_list() {
	var url = createLink("kevinerrcode", "getList", '', 'json');
	
	//set colums,从g_ztModuleLang拿到字段名的翻译
	kevin_kendogrid_columnsSets = [
		{
			command: {text: "edit", click: kevinerrcode_edit_Details},
			title: g_ztModuleLang["action"], width: "100px"
		},
		{field: "id", title: g_ztModuleLang["id"], width: 80},
		{field: "code", title: g_ztModuleLang["code"]},
		{field: "name", title: g_ztModuleLang["name"]},
		{field: "nameEn", title: g_ztModuleLang["nameEn"]},
		{field: "description", title: g_ztModuleLang["description"]},
		{field: "project", title: g_ztModuleLang["project"], width: 100},
		{field: "statusName", title: g_ztModuleLang["status"], width: 100},
		{field: "createdBy", title: g_ztModuleLang["createdBy"], width: 100},
		{field: "createdDate", title: g_ztModuleLang["createdDate"], width: 150}
	];

	//先清空显示
	kevin_kendogrid_DataArray = [];
	kevin_kendogrid_updateGridData();

	//等待logo的加载
	kendo.ui.progress($("#grid"), true);
	//异步回调
	$.getJSON(url, '', function (data) {
		var ret = kevin_CheckJsonReturnData(data);	//处理判断返回值

		kendo.ui.progress($("#grid"), false);
		if (ret.isError()) {//弹出错消息提示
			alert("错误:" + ret.errmsg);
			return;
		}

		kevin_kendogrid_DataArray = data.data;
		//单独的数据类型等处理
		for (i = 0; i < kevin_kendogrid_DataArray.length; i++) {
			var item = kevin_kendogrid_DataArray[i];
			item.id = Number(item.id);
			item.project = Number(item.project);
			item.status = Number(item.status);
			item.statusName = g_ztModuleLang.statusList[item.status];
		}
		kevin_kendogrid_updateGridData();
		$("#kevinerrcodeList_link").addClass('kevinerrcodeList_link')
	});
}

/*
//----------------------------
functions for create initial
//------------------------
*/

//initial for create
function kevinerrcode_create_initial() {
	//初始化create的模态窗口
	m_wnd_create = $("#details_create").kendoWindow({
		//窗口的名字
		title: "Create Filter",
		//调整window的窗体位置 top为距离顶端距离，left为左边
		position: {
			top: "45%",
			left: "150px"
		},
		modal: true,
		visible: false,
		resizable: false,
		width: 300
	}).data("kendoWindow");
	m_detailsTemplate_create = kendo.template($("#template_create").html());
}

//kevinerrcode_create
function kevinerrcode_create() {
	var url = createLink("kevinerrcode", "create");
	//按钮高亮
	$("#kevinerrcode_create_link").addClass('kevinerrcode_create_link')
	//set colums
	kevin_kendogrid_columnsSets = [
		{field: "code", title: "code", width: 80},
		{
			field: "filter", title: "filter", width: 80,
			template:
			// 模板语法 可在template中直接进行判断
			"# if (1 == filter ) { #" +
			"<span  class=\"label label-success\">YES</span>" +
			"# } #" +
			"# if ( 1 != filter) { #" +
			"<span  class=\"label label-danger\">NO</span>" +
			"# } #"
		},
		{command: {text: "CREATE", click: kevinerrcode_item_Details}, title: "Filter", width: "150px"},
		{field: "title", title: "title"}
	];

	//先清空显示
	kevin_kendogrid_DataArray = [];
	kevin_kendogrid_updateGridData();

	//异步回调
	$.getJSON(url, '', function (data) {
		var ret = kevin_CheckJsonReturnData(data);	//处理判断返回值
		if (ret.isError()) {//弹出错消息提示
			alert("错误:" + ret.errmsg);
			return;
		}

		kevin_kendogrid_DataArray = data.data;//get array
		//自定义按照code排序
		kevin_kendogrid_DataArray.sort(function (data1, data2) {
			return data1.code > data2.code
		});

		for (i = 0; i < kevin_kendogrid_DataArray.length; i++) {
			kevin_kendogrid_DataArray[i].filter = 2;//set 2 default
		}

		kevin_kendogrid_updateGridData();

		$("#kevinerrcode_create_link").addClass('kevinerrcode_create_link')
	});
}

//create按钮
function kevinerrcode_item_Details(e) {
	m_tr_create = $(e.currentTarget);
	e.preventDefault();
	var dataItem2 = this.dataItem($(e.currentTarget).closest("tr"));
	//对kevin_dataItem进行赋值，为按钮所在的当前 行 元素
	m_dataItem_create = dataItem2;
	m_wnd_create.content(m_detailsTemplate_create(dataItem2));
	m_wnd_create.open();
	if (1 == m_dataItem_create.filter) {
		$("#createButton").addClass('display_none');
	}
}

//create编辑页面的保存提交按钮 save
function kevinerrcode_item_submit() {
	//获取输入框内code值
	inputCode = m_dataItem_create.code;

	//获取输入框内title值
	inputTitle = m_dataItem_create.title;

	//window弹出框隐藏
	$('.k-window').css('display', 'none');
	//模态隐藏
	$('.k-overlay').css('display', 'none');

	if (1 == m_dataItem_create.filter) return;//不处理
	url = createLink("kevinerrcode", "createfilter", "code=" + inputCode, 'json');

	//ajax请求获取后台数据
	$.ajax({
		type: 'POST',
		url: url,
		dataType: "json",
		data: {"code": inputCode, "title": inputTitle},
		success: function (data) {
			//判断msg
			var ret = kevin_CheckJsonReturnData(data);	//处理判断返回值
			if (ret.isError()) {//弹出错消息提示
				alert("错误:" + ret.errmsg);
				return;
			}

			//如果正确，才进行下面的修改
			var grid = $("#grid").data("kendoGrid");
			var list = grid.dataSource.data();
			for (i = 0; i < list.length; i++) {
				if (inputCode != list[i].code) continue;//not same
				list[i].filter = 1;//set 1
			}
			grid.refresh();
		},
		error: function (data) {
			//警告提示msg
		}
	})
}

