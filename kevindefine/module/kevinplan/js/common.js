
function groupcreate(plan)
{
    $('#form1').attr('action', createLink('kevinplan', 'groupcreate', 'plan=' + plan));
}
function updateplanhours(plan){
    link = createLink('kevinplan', 'updateplanhours','id='+plan);
    location.href = link;
}
function updateprohours(project){
    link = createLink('kevinplan', 'updateprohours', 'id='+project);
    location.href = link;
}