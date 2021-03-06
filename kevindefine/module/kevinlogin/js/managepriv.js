
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
	$('.' + g_module + 'Actions').removeClass('hidden');  // Show the action control for current module.
}

function getMethodPrivs(method) {
	g_method = method;

	if( g_module == '') return;
	link = createLink('kevinlogin', 'ajaxGetGroupPrivsByMethod', 'module=' + g_module + '&method=' + g_method);
	$('.' + g_module + 'Actions').find("option[value='" + g_method + "']").attr('selected', true);
	$('#groupsBox').load(link, function () {
		$('#groups').chosen(defaultChosenOptions);
	});
}

//-----------------------------------
//-组筛选--------------------
//-----------------------------------
function managepriv_SelectGroup() {
	//1、检查选中的组
	var byGroup = document.getElementById('byGroup').value;
	//console.log('filter By ' + byGroup);

	var objModule = $("#module");
	$("option", $("#module")).remove();  

	//2、对列表module进行隐藏显示
	var selecedModule = '';

	//模块列表筛选添加
	$.each(g_modules,function(key,value){
		isShow = false;
		if(g_menugroup.hasOwnProperty(key)){
			group = g_menugroup[key];
			if(byGroup == '' || byGroup == group)	isShow = true;
		}else {//no group
			if(byGroup == 'other')isShow = true;
		}
		//添加显示
		if(isShow){
			//console.log("Add:"+key+"，"+"对应值："+value );
			if(g_module == key) selecedModule = key;
			objModule.append("<option value='" + key + "'" + (g_module == key ? " selected" : "") + ">" + value + "</option>");
		}	

	})
	//3、更新当前的module
	g_module = selecedModule;
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