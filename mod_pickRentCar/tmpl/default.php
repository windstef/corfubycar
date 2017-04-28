<?php 
// No direct access
defined('_JEXEC') or die; ?>

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
<script>
function showCategCars(selVal)
{
	if (selVal.length==0) { 
		document.getElementById("carSelect").innerHTML = "";		//remove the car select and submit button 
		$("#sidebarleft .well").css("min-height","50px");
		return;
	}
	if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("carSelect").innerHTML=xmlhttp.responseText;
			document.getElementById("bookCarForm").action = "";	//remove previous action to avoid wrong submit path
			//document.getElementById("carGoBtn").disabled = true;	//disable, at first, the submit button no-refresh
			showCarGoBtn(0);	// hide the car 'Go' button
		}
    }
    xmlhttp.open("GET","modules/mod_pickRentCar/helper.php?q="+selVal,true);
	xmlhttp.send();
	$("#sidebarleft .well").css("min-height","150px");
}

function changeFormAction(targetUrl) {
    //document.getElementById("bookCarForm").action = "http://localhost/CMS_frameworks/corfubycar/";
	if (targetUrl.length==0) { 
		//document.getElementById("carGoBtn").disabled = true;	//disable the submit button no-refresh
		showCarGoBtn(0);	// hide the car 'Go' button
		return;
	}
    document.getElementById("bookCarForm").action = targetUrl;
	//document.getElementById("carGoBtn").disabled = false;		//enable submit button
	showCarGoBtn(1);	// hide the car 'Go' button
}

/*
 * show(x=1) or hide(x=0) 
 * the car 'Go' button
 * whether a car type is selected
*/

function showCarGoBtn(x) {
	if(x == 1) {
		$( "#carGoBtn" ).show();
		//$( "#carGoBtn" ).prop( "disabled", false );
		$('#carGoBtn').hover(function(){
			//alert("You entered p1!");
		  $(this).css("background-color","tan");
		  $(this).css( 'cursor', 'pointer' );
		  },
		  function(){
			//alert("Bye! You now leave p1!");
		  $(this).css("background-color","darkgray");
		  $(this).css( 'cursor', 'pointer' );
		});
	}
	else {
		//$("#carGoBtn").css( 'cursor', 'default' );
		//$( "#carGoBtn" ).prop( "disabled", true );
		$( "#carGoBtn" ).hide();
}
}

/*
function changeColor(x) {
	if (typeof x != 'undefined') {
    x.style.style.color = "tan";
	}
}

function resetColor(x) {
	if (typeof x != 'undefined') {
    x.style.style.color = "darkgray";
	}
}
*/
</script>

<style>
#bookCarForm {
float: right;
}
#categCarList, #carList {
height: 28px;
width: 150px;
margin-top: 10px;
}
#carGoBtn {
width: 65px;
height: 30px;
float: right;
margin-top: 10px;
}
.selectPrompt {
font-size: 14px;
font-weight: bold;
}
.selectPrompt {
color: #5e57bb; 
 }
</style>

<?php echo $carForm; ?>