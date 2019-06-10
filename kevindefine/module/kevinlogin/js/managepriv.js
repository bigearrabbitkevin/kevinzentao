//初始化
$(document).ready(function () {
	var chosenmodule = document.getElementById('module').value;
	setModuleActions(chosenmodule);
	getMethodPrivs(chosenmethod);
});

function setModuleActions(module)
{
	$('#actionBox select').addClass('hidden');          // Hide all select first.
	$('#actionBox select').val('');                     // Unselect all select.
	$('.' + module + 'Actions').removeClass('hidden');  // Show the action control for current module.
}
function getMethodPrivs(method) {
	var module = document.getElementById('module').value;
	link = createLink('kevinlogin', 'ajaxGetGroupPrivsByMethod', 'module=' + module + '&method=' + method);
	$('.' + module + 'Actions').find("option[value='"+method+"']").attr('selected',true);
	$('#groupsBox').load(link, function() {
		$('groups[]').chosen(defaultChosenOptions);
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
	link = createLink('kevinlogin', 'managepriv', '','json');
	var module = document.getElementById('module').value;
	//1、后台递交
	//2、提示错误
	//3、刷新，就是调用getMethodPrivs（method）

}