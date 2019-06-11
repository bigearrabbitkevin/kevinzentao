//本js的公共变量
var m_wnd_create, m_detailsTemplate_create;
var m_dataItem_create = '';//表格行内数据
var m_tr_create;

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
	//kevinerrcode_details_initial();
	//kevinerrcode_create_initial();

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
		title: "Change Filter",
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
	kevin_detailsTemplate = kendo.template($("#template").html());
}

//kevinerrcode 编辑界面的保存提交按钮 save
function kevinerrcode_edit_submit() {

	//获得输入框内id值
	inputId = $("input[name='kevinerrcode_id']").val();
	//获取radio选中值对应的filter值
	checkFilter = $('input:radio[name="filter"]:checked').val();

	//window弹出框隐藏
	$('.k-window').css('display', 'none');
	//模态隐藏
	$('.k-overlay').css('display', 'none');
	url = createLink("kevinerrcode", "deletefilter", "id=" + inputId + "&filter=" + checkFilter + "&confirm=yes", 'json');

	//ajax请求获取后台数据
	$.ajax({
		type: 'GET',
		url: url,
		dataType: "json",
		data: {"filter": checkFilter},
		success: function (data) {
			//判断msg
			var ret = kevin_CheckJsonReturnData(data);	//处理判断返回值
			if (ret.isError()) {//弹出错消息提示
				alert("错误:" + ret.errmsg);
				return;
			}

			//如果正确，才进行下面的修改
			kevin_kendogrid_dataItem.set("filter", (1 == kevin_kendogrid_dataItem.filter) ? 2 : 1)
		},
		error: function (data) {
			//警告提示msg
		}
	})
}

//编辑框点击事件
function kevinerrcode_edit_Details(e) {
	//e就是编辑按钮
	e.preventDefault();

	// var deleteDate = "tr:eq("+code+")";
	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));

	//对kevin_dataItem进行赋值，为按钮所在的当前 行 元素
	kevin_kendogrid_dataItem = dataItem;
	kevin_ModelWnd.content(kevin_detailsTemplate(dataItem));
	kevin_ModelWnd.open();
	//单选按钮  与过滤状态的绑定
	if (dataItem.filter == 1) {
		$("input:radio[value='1']").attr('checked', true)
	} else {
		$("input:radio[value='2']").attr('checked', true)
	}
	//当filter下radio未发生改变时，save按钮不可点
	$('#saveButton').attr('disabled', true);
	//通过监听radio按钮的改变来使得保存按钮可用
	$("input:radio[name='filter']").click(function () {
		var checkValue = $('input:radio[name="filter"]:checked').val();
		if (checkValue == dataItem.filter) {
			$('#saveButton').attr('disabled', true)
		} else {
			$('#saveButton').attr('disabled', false)
		}
	});

}

//kevinerrcodeList
function kevinerrcode_list() {
	var url = createLink("kevinerrcode", "getList", '', 'json');

	//set colums
	kevin_kendogrid_columnsSets = [
		{
			command: {text: "edit", click: kevinerrcode_edit_Details},
			title: " ", width: "100px"
		},
		{field: "id", title: "code", width: 80},
		{field: "name", title: "name"},
		{field: "nameEn", title: "name En"},
		{field: "description", title: "description"},
		{field: "project", title: "project", width: 100},
		{field: "status", title: "status", width: 100},
		{field: "createdBy", title: "createdBy", width: 100},
		{field: "createdDate", title: "createdDate", width: 150}
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
			item.filter = Number(item.filter);
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

