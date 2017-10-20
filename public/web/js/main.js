function centerForm(f){
	var clientWidth = 0, clientHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
		clientWidth = window.innerWidth;
		clientHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		clientWidth = document.documentElement.clientWidth;
		clientHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		clientWidth = document.body.clientWidth;
		clientHeight = document.body.clientHeight;
	}

	document.getElementById(f).style.left = ((clientWidth/2 -300)) + 'px';
	document.getElementById(f).style.top = ((clientHeight/2 -300)) + 'px';
}

function showForm(f){
	document.getElementById(f).style.display = 'block';
	document.getElementById('modal').style.display = 'block';
}

function hideForm(f){
	document.getElementById(f).style.display = 'none';
	document.getElementById('modal').style.display = 'none';
}

function init(){
	if (document.getElementById('new-store')) centerForm('new-store');
	if (document.getElementById('file-upload')) centerForm('file-upload');
	if (document.getElementById('new-offer')) centerForm('new-offer');
	if (document.getElementById('new-signin')) centerForm('new-signin');
	if (document.getElementById('new-signup')) centerForm('new-signup');
}

function showLoading(e){
	document.getElementById(e).innerHTML = '<img src=\"/img/loading.gif\" style=\"border:0\" />';
	document.getElementById(e).style.paddingLeft = '100px';
	document.getElementById(e).style.paddingTop = '100px';
	return true;
}
