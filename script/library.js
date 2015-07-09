/*	How To: Deploying Suckerfish Dropdown Menus
	Version 1.0 - 7/20/2006
	http://www.deansabatino.com/2006/07/20/96/

	A slightly modified version of the Suckerfish script. I folded in a check
	for whether the browser supports "getElementByTagName" and then added an additional variable
	for the second menu. I then added the second "for" loop. I'm sure there is a more elegant
	way to write this but that's for another day... */
	
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls = document.getElementById("nav").getElementsByTagName("li");	
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
	// if you only have one main menu - delete the line below //
	var sfEls1 = document.getElementById("secnav").getElementsByTagName("li");
	for (var i=0; i<sfEls1.length; i++) {
		sfEls1[i].onmouseover=function() {
			this.className+=" sfhover1";
		}
		sfEls1[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover1\\b"), "");
		}
	}
	//
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//-->

/*
Function to lookup an AJAX request. Originally posted 
on: http://ajaxtraining.blogspot.com
*/
function lookup(source) {
	var req;
	var url=source;
	req = false;
	// branch for native XMLHttpRequest object
	
	if(window.XMLHttpRequest) {
		try {
			req = new XMLHttpRequest();
		} catch(e) {
			req = false;
		}
	// branch for IE/Windows ActiveX version
	} else if(window.ActiveXObject) {
		try {
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				req = false;
			}
		}
	}
	
	if(req) {
		//req.onreadystatechange = processReqChange;
		req.open("GET", url, false);
		req.send('-');
	}
	return req.responseText;
} 

function lookupArray(url) {
	var ret = lookup(url); 
	var update = new Array();
	
	if(ret.indexOf('|' != -1)) {
		update = ret.split('|'); 
		return update;
	}
}

/* Function to open a sub-window. */
function openSubWindow(url, x, width, height) {
	if (!x || x.closed) { 
		/*var width = 1000;
		var height = 600;*/
		
		//dapatkan posisi kiri atas dari ojok layar
		var left = parseInt((screen.availWidth/2) - (width/2));
		var top = parseInt((screen.availHeight/2) - (height/2));
		
		//fitur2 window
		var fitur = "width="+width+", height="+height+", resizable=0, top="+top+", left="+left+", screenX="+left+", screenY="+top;
		x = window.open(url, "sub", fitur);
	} else {
		x.focus();
	}
}

/* Function to add a thousand separator to numbers */
function addThaousandSeparator(x) {
	var pjg = x.length;
	var hasil = "";
	var i;
	
	for (i=(pjg-3);i>0;i-=3) {
		hasil = '.' + x.substr(i,3) + hasil;
	}
	if (pjg%3 > 0) {
		hasil = x.substr(0,(pjg%3)) + hasil;
	} else {
		hasil = x.substr(0,3) + hasil;
	}
	
	return (hasil);
}

function removeThaousandSeparator(x) {
	var pjg = x.length;
	var hasil = "";
	
	for (var i=0;i<pjg;i++) {
		if (x.charAt(i) != '.') {
			hasil += x.charAt(i);
		}
	}
	
	return parseInt(hasil);
}
