function onRemoveSearchChoice(key, value)
{
    var deleteKeyObj = document.getElementById('deleteKey');//获得要删除的关键词类型
    deleteKeyObj.value = key;//设置变量
    var deleteValueObj = document.getElementById('deleteValue');//获得要删除的关键词类型
    deleteValueObj.value = value;//设置变量
    //提交表单
    document.getElementById("deleteKeyForm").submit();
}