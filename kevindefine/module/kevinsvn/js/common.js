function SubmitFormByID(FormIID) {
	document.getElementById(FormIID).submit();
}
function onRemoveSearchChoice(key, value)
{
    var deleteKeyObj = document.getElementById(key);//获得要删除的关键词类型
    deleteKeyObj.value = "";//设置变量
      //提交表单
    document.getElementById("searchform").submit();
}