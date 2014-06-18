/*******Code for login page********/
function changeBu(buId)
{
	var ajaxPath = document.getElementById('ajaxPath').value;
	
	var result = $.ajax({
					type: "POST",
					url: ajaxPath,
					data: 'act=changeBu&buId='+buId,
					async: false
				}).responseText;
	
	if(result=='success')
		window.location.reload();
}

function refreshPerTable(bu,week_val)
{
	var ajaxPath = document.getElementById('ajaxPath').value;
	
	document.getElementById('refreshPerLink').innerHTML = '<img src="images/loading.gif" /> Refreshing';
	
	setTimeout(function () {
		var result = $.ajax({
						type: "POST",
						url: ajaxPath,
						data: 'ajaxaction=refreshPerTable&bu='+bu+'&week_val='+week_val,
						async: false
					}).responseText;
	
		document.getElementById('perTableContent').innerHTML = '<tbody>'+result+'</tbody>';
	}, 1000);	
}

/*******Code for daily page********/
function changeWeekVal(val)
{
	var week_val = document.getElementById('week_val').value;
	
	if(val=='previous')
		week_val = parseInt(week_val)-1;
	else
		week_val = parseInt(week_val)+1;
		
	document.getElementById('week_val').value = week_val;

	document.getElementById('frmDailySearch').submit();
}

/*******Code for login page********/
function submitLoginForm()
{
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	
	var txtError = '';
	if(username == '')
		txtError += '<li>Please specify username</li>';
	
	if(password == '')
		txtError += '<li>Please specify password</li>';
	
	if(txtError != '')	
	{
		document.getElementById('loginErrDiv').innerHTML = txtError;
		document.getElementById('loginErrDiv').style.display = 'block';
		setTimeout(function () {
			$('#loginErrDiv').hide('slow');
		}, 5000);
		return false;
	}
	
	return true;
}