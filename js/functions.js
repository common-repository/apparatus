//source: http://www.netlobo.com/div_hiding.html
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) // this is the way the standards work
    elem = document.getElementById( whichLayer );
  else if( document.all ) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if( document.layers ) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}

function doPopUp(e, url)
{
	//set defaults - if nothing in rel attrib, these will be used
	var t = "standard";
	var w = "640";
	var h = "480";

	//call the popup script
	popUpWin(url,t,w,h);

	//cancel the default link action if pop-up activated
	if (window.event) 
	{
		window.event.returnValue = false;
		window.event.cancelBubble = true;
	} 
	else if (e) 
	{
		e.stopPropagation();
		e.preventDefault();
	}
}

function popUpWin(url, type, strWidth, strHeight){
	
//closeWin(); 
//calls function to close pop-up if already open, 
//to ensure it's re-opened every time, retainining focus
	
type = type.toLowerCase();

var tools = "resizable,toolbar=yes,location=yes,scrollbars=yes,menubar=yes, width="+strWidth+",height="+strHeight+",top=0,left=0";

newWindow = window.open(url, 'newWin', tools);
newWindow.focus();
}

function dbSettings()
{
	var box = document.getElementById('examples_mode');
	var vis = (box.checked) ? "block" : "none"; 
	document.getElementById('database-settings').style.display = vis;
}