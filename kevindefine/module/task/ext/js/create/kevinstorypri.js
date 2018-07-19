/* Copy story default as task default. 
Kevin Yang, 2015-1-13*/
function KevinCopyStoryDefault()
{
    var stringSource = $('#story option:selected').text();
	
	//find title
    startPosition = stringSource.indexOf(':') + 1;
    endPosition   = stringSource.lastIndexOf('(');
	if(endPosition>0)
	{
		if($('#name').attr("value") == '')
		{
			stringfind = stringSource.substr(startPosition, endPosition - startPosition);
			$('#name').attr('value', stringfind);
		}
		
		//find pri:
		storyleft = stringSource.substr(endPosition +1);
		startPosition = storyleft.indexOf(':') + 1;
		endPosition   = storyleft.lastIndexOf(',');
		stringfind = storyleft.substr(startPosition, endPosition - startPosition);
		$('#pri').attr('value', stringfind);
	
		//find estimate
		startPosition   = storyleft.lastIndexOf(':')+1;
		endPosition   = storyleft.lastIndexOf(')');
		storyleft = storyleft.substr(startPosition, endPosition - startPosition);
		$('#estimate').attr('value', storyleft);
	}
}