$(function()
{
    $('#projectTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i]).attr('data-id') + ',';
        $.post(createLink('project', 'updateOrder'), {'projects' : list, 'orderBy' : orderBy});
    });
});

function byProduct(productID)
{
    location.href = createLink('kevindefine', 'projectlist', 'productID=' + productID);
}
