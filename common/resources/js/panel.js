var $_open = false;
var $_full = false;

var $_mouseX = 0;
var $_mouseY = 0;
var $_rightClick = false;
var $_window = false;

var $_ctrlDown = false;
var $_altDown = false;

function _togglePanel()
{
	if(!$_open){
		$("body").css("overflow", "hidden");
		$("#_panelProject").css("right", "5%");
		$("#_panelMenu").css("right", "5%");
		$("#_panelMask").fadeIn(1000);
		$("#_togglePanel").html("<span>Lilith<br><i class=\"fa fa-arrow-circle-right\"></i></span>");
	}else{
		$("body").css("overflow", "auto");
		$("#_panelProject").css("right", "-100%");
		$("#_panelMenu").css("right", "-100%");
		$("#_panelMask").fadeOut(1000);
		$("#_togglePanel").html("<span>Lilith<br><i class=\"fa fa-arrow-circle-left\"></i></span>");
	}
	$_open = !$_open;
}


function _toggleFullScreen()
{
	var docElm = document.documentElement;
	if(!$_full){
		if (docElm.requestFullscreen)
			docElm.requestFullscreen();
		else if (docElm.mozRequestFullScreen)
			docElm.mozRequestFullScreen();
		else if (docElm.webkitRequestFullScreen)
			docElm.webkitRequestFullScreen();
	}
	else
	{
		if (document.exitFullscreen)
			document.exitFullscreen();
		else if (document.mozCancelFullScreen)
			document.mozCancelFullScreen();
		else if (document.webkitCancelFullScreen)
			document.webkitCancelFullScreen();
	}
	$_full = !$_full;
}

$( "._headDeploy" ).click(function (event){
	$(this).siblings("._contentDeploy").slideToggle(200);
})

$( "#_panelProject" ).contextmenu(function (event) {return false;})
$( "._panelCase" ).contextmenu(function (event) {return false;})

$( "._panelWindow" ).mousedown(function (event) {
	$( "._panelWindow" ).css("z-index", "1");
	$(this).css("z-index", "5");
	if(event.button == 2)
	{
		$_mouseX = event.clientX;
		$_mouseY = event.clientY;
		$("#_panelRightClic").css("left", ($_mouseX-82)+"px");
		$("#_panelRightClic").css("top", ($_mouseY-82)+"px");
		$_rightClick = true;
		$_window = $(this);
		return false;
	}
})

$( "._WOpener" ).mousedown(function (event) {
	var $_for = $(this).attr("for");
	if(event.button == 0)
	{
		$( "#"+$_for ).show("drop", {}, 250, function(){
			$( "._panelWindow" ).css("z-index", "1");
			$( "#"+$_for ).css("z-index", "20");
		});
		return false;
	}
	if(event.button == 2)
	{
		$( "#"+$_for ).hide( "drop", {}, 250, function(){});
		return false;
	}
})

$(document).keydown(function (event)
{
	event = event || window.event;
	var code = event.keyCode || event.wihch;
	if(code == 17)$_ctrlDown = true;
	if(code == 18)$_altDown = true;
	if($_altDown == true)
	{
		if(code == 49)$( "#_menuIcons").toggleClass("_iconsUp");
	}
	//alert('Vous avez appuyé sur la touche n°'+code);
})

$(document).keyup(function (event)
{
	event = event || window.event;
	var code = event.keyCode || event.wihch;
	if(code == 17)$_ctrlDown = false;
	if(code == 18)$_altDown = false;
})

$(document).mouseup(function (event) {
	if(event.button == 2 && $_rightClick) // si ce n'est pas un clic droit
	{
		$_rightClick = false;
		var $_newX = event.clientX;
		var $_newY = event.clientY;
		$_newX -= $_mouseX;
		$_newY -= $_mouseY;
		//alert('' + $_newX + ' : ' + $_newY)
		if(($_newX > 30 || $_newX < -30) || ($_newY > 30 || $_newY < -30))
		{
			if(Math.abs($_newX) > Math.abs($_newY)) // G / D
			{
				if($_newX >= 0) // Droite
				{
					$_window.css("width","50%").css("height","100%").css("top","0px").css("left","50%");
				}
				else // Gauche
				{
					$_window.css("width","50%").css("height","100%").css("top","0px").css("left","0px");
				}
			}
			else // H / B
			{
				if($_newY >= 0) // Bas
				{
					$_window.hide("drop", {}, 250 );
				}
				else // Haut
				{
					console.log($_window.css("width") + ' : ' + $("#_panelProject").css("width"));
					if($_window.css("width") == $("#_panelProject").css("width"))
						$_window.css("width","800px").css("height","500px");
					else
						$_window.css("width","100%").css("height","100%").css("top","0px").css("left","0px");
				}
			}
		}
		$("#_panelRightClic").css("left", "-500px");
		$("#_panelRightClic").css("top", "-500px");
		return false;
	}
})

$( function() {
	$( "._panelWindow" ).draggable({stack:"._panelWindow", handle: "._windowHead"});
	$( "#_menuIcons" ).draggable({handle: "#_menuHandle"});
	$( "._panelWindow" ).resizable({ minHeight:60, minWidth:200});
})