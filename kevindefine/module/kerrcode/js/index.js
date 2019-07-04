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
	kevin_kendogrid_fileName = 'kerrcode_Kendo_UI_Grid_Export_' + kevin_today + '.xlsx';

	//统一的工具条设定
	kevin_kendogrid_toolbar =
		[
			{template: kendo.template($("#kerrcode_list").html())},
			{template: kendo.template($("#kerrcode_create").html())},
			"excel"
		];
	//初始化
	kerrcode_details_initial();

	//默认加载List
	kerrcode_list();

});

//----------------------------
//functions for kerrcode list
//------------------------

//initial for kerrcode_details_initial
function kerrcode_details_initial() {
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

//kerrcode 编辑界面的保存提交按钮 save
function kerrcode_edit_submit(iId) {
	iId = Number(iId);
	/*editOrApproval为true 就是审批页面过来的
	否则就是编辑页面的*/
	var editOrApproval = $(".form-condensed").find("input[type=label]")[0].disabled;
	//获得输入框内project值
	var dataForm = {};
	dataForm.id = iId;
	dataForm.project = $("input[name='kerrcode_project']").val();
	dataForm.code = $("input[name='kerrcode_code']").val();
	//获得输入框内name值
	dataForm.name = $("input[name='kerrcode_name']").val();
	//获得输入框内nameEn值
	dataForm.nameEn = $("input[name='kerrcode_nameEn']").val();
	//获得输入框内status值
	dataForm.status = $("input[name='kerrcode_status']:checked").val();
	//获得输入框内description值
	dataForm.description = $("input[name='kerrcode_description']").val();

	var dataForm_approval = {};
	dataForm_approval.id = iId;
	dataForm_approval.status = $("input[name='kerrcode_status']:checked").val();

	//对输入的信息进行限制处理
	if (!editOrApproval) {//审批的时候 因为无法编辑  所以不进行长度检测
		if (dataForm.code.trim().length < 5) {
			alert("输入的代码长度少于5，请重新输入");
			return
		} else if (dataForm.name.trim().length < 4) {
			alert("输入的名称长度少于4，请重新输入");
			return
		} else if (dataForm.nameEn.trim().length < 5) {
			alert("输入的英文名长度少于5，请重新输入");
			return
		}
	}

	//ajax请求获取后台数据
	if (editOrApproval) {
		fun = "approve"
	} else {
		fun = iId == '-1' ? 'create' : 'edit';
	}
	url = createLink("kerrcode", fun, "", 'json');
	$.ajax({
		type: 'POST',
		url: url,
		dataType: "json",
		data: editOrApproval ? dataForm_approval : dataForm,
		success: function (errData) {
			//判断msg
			var ret = kevin_CheckJsonReturnData(errData);	//处理判断返回值
			if (ret.isError()) {//弹出错消息提示
				alert("错误:" + ret.errmsg);
				return;
			}

			//如果正确，才进行下面的修改	
			var newItem = errData.data.newItem;
			$.each(newItem, function (key, val) {
				if (kevin_kendogrid_dataItem[key] != val) {
					kevin_kendogrid_dataItem.set(key, val);
					console.log("Modify Key:" + key + ", Value:" + val);
				}
			});

			//window弹出框隐藏
			$('.k-window').css('display', 'none');
			//模态隐藏
			$('.k-overlay').css('display', 'none');
			//新建完成以后，刷新表格
			if (iId == '-1') kerrcode_list();
		},
		error: function (data) {
			alert(data.responseText);//警告提示msg
		}
	})
}


//编辑框点击事件
function kerrcode_edit_Details(e) {
	//e就是编辑按钮
	e.preventDefault();

	// dataItem 为按钮所在的当前 行 元素
	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
	if (dataItem.id < 1) {
		alert("id = 0 不能编辑");
		return;
	}

	if (dataItem.status != 100) {
		alert("只有草稿状态的条目可以修改。当前状态=" + dataItem.statusName);
		return;
	}
	//对kevin_dataItem进行赋值
	kevin_kendogrid_dataItem = dataItem;
	kevin_ModelWnd.content(kevin_detailsTemplate(dataItem));

	kevin_ModelWnd.open();

}

//新建
function kerrcode_create_Details() {

	// dataItem 为按钮所在的当前 行 元素

	var dataItem = {
		"id": -1,
		"project": 0,
		"code": "ERRCODE",
		"name": "",
		"nameEn": "",
		"status": 100,
		"description": "",
		"createdBy": "",
		"createdDate": "",
		"statusName": "草稿"
	}
	// var dataItem = this.dataItem($(e.currentTarget).closest("tr"));

	//对kevin_dataItem进行赋值
	kevin_kendogrid_dataItem = dataItem;
	kevin_ModelWnd.content(kevin_detailsTemplate(dataItem));
	//在新建页面，隐藏日期和创建人tr
	$("#kerrcode_date_hidden").css("display", "none");
	kevin_ModelWnd.open();

}

//审批
function kerrcode_approval_Details(e) {
	console.log(e);
	console.log($(".k-grid-sign"));
	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));

	//对kevin_dataItem进行赋值
	kevin_kendogrid_dataItem = dataItem;
	kevin_ModelWnd.content(kevin_detailsTemplate(dataItem));
	//进入页面时候，直接选中开始的状态（100，200，300）
	$("#kerrcode_status_approval").find("input[value=" + dataItem.status + "]").attr("checked", true);
	//如果状态码为200或者300  那么100不可选
	if (dataItem.status == 200 || dataItem.status == 300) {
		$("#kerrcode_status_approval").find("input[value=" + '100' + "]").attr("disabled", "disabled");
	}
	//隐藏状态
	$("#kerrcode_status_edit").css("display", "none");
	//显示单选按钮选项
	$("#kerrcode_status_approval").removeAttr("style");
	//使所有input框不可编辑
	$(".form-condensed").find("input[type=label]").attr("disabled", "disabled");

	kevin_ModelWnd.open();


}

//kerrcodeList
function kerrcode_list() {
	var url = createLink("kerrcode", "getList", '', 'json');

	//set colums,从g_ztModuleLang拿到字段名的翻译
	kevin_kendogrid_columnsSets = [
		{
			command: [
				{
					name: "edit",
					text: "",
					click: kerrcode_edit_Details
				},
				{
					name: "sign",
					text: "审批",
					click: kerrcode_approval_Details,
					imageClass: "icon-task-finish icon-ok-sign"
				}],
			title: g_ztModuleLang["action"], width: "160px"
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
		$("#kerrcodeList_link").addClass('kerrcodeList_link')
	});
}
