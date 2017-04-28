<?php
/**
* @package    mycontactformular
* @author     StefKour
* @copyright  
* @license    
**/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*********** Attention: uncomment send function, lines:36, 58  ******************/	

if( isset($_POST['submitBtn']) ) {
		//echo '2) submit: ' . $_POST['submitBtn'] . '<br>';

		$user_tel = 'not set';
		if(isset($_POST['telephone'])) { $user_tel = $_POST['telephone']; }
			
		$mailSubject = "Contact form message.";
		
         $mailBody = 'Dear ' . $mail->FromName . ',' .
                    '<br> <br> another contact form message was added as it follows:<br><br> ' .
                     '<b>Visitor name: </b>' . $_POST['user_name'] . '<br> ' .
					 '<b>Email Address: </b>' . $_POST['user_email'] . '<br> ' .
					 '<b>Telephone Number: </b>' . $user_tel . '<br> ' .
					 '<b>Message: </b>' . $_POST['message'] . '<br><br>' .
					'Please contact to the user with email or telephone, if it is set..
					<br><br>
                    Best Regards<br>
                    The Corfu by Car Admin';
		
		$mail->addAddress($mail->From, $mail->FromName);     			// set recipient's address the admin email (self-email)
		$mail->Subject = $mailSubject;										// set mailSubject
		$mail->Body    = $mailBody;  										//'This is the HTML message body
		//$mail->send();														// send the email
		
		echo "<p>Your message has been submitted successfully.<br>We will contact you very soon.</p>";
		
	// send a copy email to the visitor if he checked the 'SendCopy2you' checkbox button
	if(isset($_POST['SendCopy2you'])) {
	
		$mailSubject = "Contact form message.";
		
         $mailBody = 'Dear Mrs/Mr ' . $_POST['user_name'] . ',
                    <br> <br> we send you a copy of your message as it follows:<br><br> ' .
                     $_POST['message'] . '<br><br>' .
                    'We will examine it carefully and inform you very soon.<br>
					Please be free to contact us back if you have another request.
					<br><br>
                    Best Regards<br>
                    The Corfu by Car Team';
					
		$mail->clearAddresses(); 										// clear the previous (admin's ) email address
		$mail->addAddress($_POST['user_email'], 'Dear Visitor');     			// Add a recipient the visitor email
		$mail->Subject = $mailSubject;										// set mailSubject
		$mail->Body    = $mailBody;  										//'This is the HTML message body
		//$mail->send();														// send the email
		
		echo "<p>Additionally, your message has been sent to your email.</p>";
	}
	
	// unset the var
	unset($_POST['submitBtn']);
	echo "<br><br>";
?>
<button class="btn" onclick="goBack()">Go Back</button>
<?php	
}

else {
?>


<p>Contact us if you have any request, complaint or comment by filling the following form.</p>
<p><?php echo $required_fields_notice ?></p> <!-- All fields with an * are required. -->

<form name="contactForm" id="contactForm" method="post" action="">
<table width="450px">

<tr>
 <td valign="top">
  <label for="first_name">Name *</label>
 </td>
 <td valign="top">
  <input  type="text" name="user_name" maxlength="50" size="30">
 </td>
</tr>
 
<tr>
 <td valign="top">
  <label for="email">Email Address *</label>
 </td>
 <td valign="top">
  <input  type="text" name="user_email" maxlength="80" size="30">
 </td>
</tr>

<tr>
 <td valign="top">
  <label for="telephone">Telephone Number</label>
 </td>
 <td valign="top">
  <input  type="text" name="telephone" maxlength="30" size="30">
 </td>
</tr>

<tr>
 <td id="message">
  <label for="message">Message *</label>
 </td>
 <td valign="top">
  <textarea  name="message" maxlength="1000" cols="25" rows="6"></textarea>
 </td>
</tr>

<tr>
 <td>
  <label for="SendCopy">Send a copy to yourself</label>
 </td>
<td align = "center"><input type="checkbox" name="SendCopy2you" id="SendCopy2you" value="" /></td>
</tr>

<tr>
<td id="captcha">
<span><u>Please answer:</u></span>
<br/> 
<span id="captchaQuestion"></span>
</td>

<td>
<input type="text" name="userCaptchaAnswer" id="userCaptchaAnswer" />
<!--<input type="hidden" id="validCaptchaAnswer" name="validCaptchaAnswer" value="">-->

<!--<p><img src="captcha.php" width="120" height="30" border="1" alt="CAPTCHA"></p>
<p><input type="text" size="6" maxlength="5" name="captcha" value=""><br>
<small>copy the digits from the image into this box</small></p>-->
</td>
</tr>

<tr>
<td style="text-align:center" colspan="2">
  <input type="submit" name="submitBtn" value="Submit" class="btn" onclick="return validateForm();">
 </td>
</tr>

</table>
</form>
<?php } //eof else clause
?>