function showHideHelp(id) {
	fbBolck = 'fb' + id;
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
		document.getElementById(fbBolck).innerHTML = '<a href="javascript:void(0);" onclick="showHideHelp(\''
				+ id + '\')">Hide Help</a>';
	} else {
		document.getElementById(id).style.display = 'none';
		document.getElementById(fbBolck).innerHTML = '<a href="javascript:void(0);" onclick="showHideHelp(\''
				+ id + '\')">Show Help</a>';
	}
};

function submitFromZero(subtype) {
	document.getElementById('from').value = 0;
	if (subtype == 'c') {
		document.getElementById("suppid").selectedIndex = "0";
	}
	if (subtype == 's') {
		document.getElementById("custid").selectedIndex = "0";
	}
	return false;
	//document.getElementById('searchform').submit();
};

function showHide(id) {
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
};