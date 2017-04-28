/* 
* validate all the
* required fields of
* the form
*/
function validateForm() {
	var a = document.forms["contactForm"]["user_name"].value;
    var c = document.forms["contactForm"]["user_email"].value;
	    var atpos = c.indexOf("@");
		var dotpos = c.lastIndexOf(".");
    var d = document.forms["contactForm"]["telephone"].value;
    var e = document.forms["contactForm"]["message"].value;
	var capAns = document.getElementById("userCaptchaAnswer").value;
    
	if (a==null || a=="") {
		document.forms["contactForm"]["user_name"].focus();
        alert("First name must be filled out");
        return false;
    }
	else if (c==null || c=="" || atpos<1 || dotpos<atpos+2 || dotpos+2>=c.length) {
		document.forms["contactForm"]["user_email"].focus();
        alert("Empty or not a valid e-mail address");
        return false;	
	}
	else if ((d!=null || d !="") && isNaN(d)) {		// not required field, but must be numeric
		document.forms["contactForm"]["telephone"].focus();
        alert("The typed telephone must be a valid number");
        return false;	
	}
	else if (e==null || e=="") {
		document.forms["contactForm"]["message"].focus();
        alert("Comments must be filled out");
        return false;	
	}
	else if (capAns != capRes) {
		document.forms["contactForm"]["userCaptchaAnswer"].focus();
        alert("The answer is not valid!");
        return false;
    }
	else {
		return true;
		}
}	

/* 
* go Back to main contact form
* page triggered by button
*/
function goBack() {
    window.history.back();
	//document.getElementById("contactForm").reset();
}

( function($) {
     // rely on $ within safety of "bodyguard" function
     //   $(document).ready( function() { alert("jquery functioning");  } );
	
$(document).ready(function(){
// change color effect on button
$(".btn").hover(function(){
  $(this).css("background-color","tan");
  },
  function(){
  $(this).css("background-color","darkgray");
});
//reset the form, useful for 'go Back' button 
  document.getElementById("contactForm").reset();
});

// set Captcha
$( document ).ready(function() { 
	var x = Math.floor((Math.random() * 20) + 1);
    var y = Math.floor((Math.random() * 20) + 1);
	capRes = 0; 	//global scope (not declared with keyword 'var') for form validation
	
	var randOp = Math.random();
	//var opStr = '<span id="captchaOperIm"></span>';
	var opStr = ''; //array("plus", "minus");;
		
	if(randOp <= 0.5 && x >= y) {
		capRes = x - y;
		//opStr = ' minus ';
		opStr = ('subtract ').concat(y, ' from ', x);
		//$("#captchaOperIm").css("background-position" , "0 -13px");		//set sprite icon pos to show minus
		}
	else {
		capRes = x + y;
		//opStr = ' plus ';
		opStr = ('add ').concat(x, ' to ', y);
		//$("#captchaOperIm").css("background-position" , "0 3px");		//set sprite icon pos to show plus
		}	
		
	document.getElementById("captchaQuestion").innerHTML = ("").concat(opStr);

	});

	
} ) ( jQuery );
