
var g_module = '';//选中的module
//初始化
$(document).ready(function () {
	g_module = document.getElementById('module').value;
	setModuleActions(g_module);
});

function setModuleActions(module) {
	g_module = module;
	g_method = '';
	$('#actionBox select').addClass('hidden');          // Hide all select first.
	$('#actionBox select').val('');                     // Unselect all select.
	$('.' + module + 'Actions').removeClass('hidden');  // Show the action control for current module.
}

function getMethodPrivs(method) {
	g_method = method;
	g_module = document.getElementById('module').value;

	if( module == '') return;
	link = createLink('kevinlogin', 'ajaxGetGroupPrivsByMethod', 'module=' + g_module + '&method=' + method);
	$('.' + module + 'Actions').find("option[value='" + method + "']").attr('selected', true);
	$('#groupsBox').load(link, function () {
		$('#groups').chosen(defaultChosenOptions);
	});
}

//-----------------------------------
//-组筛选--------------------
//-----------------------------------
function managepriv_SelectGroup() {
	var byGroup = document.getElementById('byGroup').value;
	alert('正在开发：' + byGroup);
	//1、检查选中的组
	//2、对列表module进行隐藏显示
	//3、如果选中的module不是显示的，切换为第一个显示项
}

//-----------------------------------
//-后台递交--------------------
//-----------------------------------
function managepriv_submit() {
	if ( g_module == '') {
		alert("Error: Please select a module");
		return;//error
	}
	else if ( g_method == '') {
		alert("Error: Please select a method");
		return;//error
	}
	
	//1、后台递交
	$("#form_managepriv").submit();
	intload = 0;
	//$.ajax({cache:false});//关闭缓存，解决load重复多次加载的问题
	$("#hiddenwin").load(function(){ 
		if(intload>0) return;//跳过多次加载，解决load重复多次加载的问题
		intload++;
		var text = $(this).contents().find("body").text();
		data = $.parseJSON(text);
		var ret = kevin_CheckJsonReturnData(data);	//处理判断返回值

		//2弹出错消息提示
		if (ret.isError()) {
			alert("Error:" + ret.errmsg);
			return;
		}

		//3、刷新，就是调用getMethodPrivs（method）
		//getMethodPrivs(g_method);//刷新
		alert("Success!");
	});
}