function SubmitFormByID(FormIID) {
	document.getElementById(FormIID).submit();
}
function statisticbyear(year,type) {
    link = createLink('kevindevice', 'statistic',  'type='+type+'&year=' + year);
    location.href = link;
}
