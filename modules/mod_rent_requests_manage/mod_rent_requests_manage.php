<?php

if (!defined('_JEXEC'))
	define('_JEXEC', 1);
if (!defined('JPATH_BASE'))
define('JPATH_BASE', '../..' );

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

require_once (JPATH_BASE . '/libraries/myPHPMailer/CorfuByCar-email_configurations.php');	// extra, required this file


// Get a db connection.
$database = JFactory::getDbo();
 
// Create a new query object.
$query = $database->getQuery(true);

//echo "<br>Host: " . $mail->Host. " - mailUsername: " . $mail->Username . " - mailFrom: " . $mail->From . "<br>";

/***********  handle if 'Accept' button is submitted ************/
if(isset($_POST['acceptBtn'])) {
	$rent_request_id =  $_POST["rent_request_id"];
	
	// Create an object for the record we are going to update.
	$rent_price = $_POST['rent_price'];
	//echo "<br>rent_price(post): " . $rent_price ."<br>";
	//echo "<br>rent_price(post[0]): " . $rent_price[0] ."<br>";

	
	// toggle hold the checked rent_request id
	foreach ($_POST['toggle'] as $toggle) {
		$object = new stdClass();
		 
		// Must be a valid primary key value.
		$object->id = $toggle;
		$object->status = 1;		// set status 1 for approve
		$object->rent_price = $rent_price[$toggle];			//$_POST['rent_price'];
		 //echo "<br>rent_price(object): " . $object->rent_price ."<br>";
		 
		// Update the table using id as the primary key
		$result = JFactory::getDbo()->updateObject('#__vehiclemanager_rent_request', $object, 'id');
		
		// query the database to obtain the record for the specific rent request
		//$database = JFactory::getDBO(); 
		$database->setQuery("SELECT l.id, l.rent_request, l.rent_from, l.rent_until, l.user_name, l.user_email, l.user_telephone, l.user_mailing, a.vtitle FROM #__vehiclemanager_rent_request AS l" .
            "\nLEFT JOIN #__vehiclemanager_vehicles AS a" .			
            "\nON l.fk_vehicleid = a.id" . 
			"\nWHERE l.id = " . $toggle);		
		$rent_request_list = $database->loadObjectList();		
		$rent_request = $rent_request_list[0];		//select the only one record

		//send email to each user about the price of the 'rent request'
		$mailSubject = "Price offer of your rent request.";
		
         $mailBody = 'Dear Mrs/Mr ' . $rent_request->user_name. ',' .
                    '<br> <br> according to your submitted rent request:<br><br> ' .
                    '<b>Car model:</b> ' . $rent_request->vtitle . '<br> ' .
                    '<b>from:</b> ' . $rent_request->rent_from . '<br><b>until:</b> ' . $rent_request->rent_until . '<br><br>' .
                    'we are pleased to inform you that it has been processed and the special rent price we offer you is <b>'
					. $rent_price[$toggle] . '</b> euro.<br>
					Please reply us, via email or telephone, if you accept the price so we can
					inform you about the delivery details.<br>
					Please be free to contact us back if you have another request.
					<br><br>
                    Best Regards<br>
                    The Corfu by Car Team';
					
		$mail->addAddress($rent_request->user_email, 'Dear Visitor');     // Add a recipient the user email
		$mail->Subject = $mailSubject;										// set mailSubject
		$mail->Body    = $mailBody;  										//'This is the HTML message body
		$mail->send();													// send the email
		echo ' acceptBtn ';
	}

	// now redirect to prevent duplicate form submission on refresh
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}

/****** handle if 'Decline' button is submitted ********/
if(isset($_POST['declineBtn'])) {
	$rent_request_id =  $_POST["rent_request_id"];
	
	// Create an object for the record we are going to update.
	$rent_price = $_POST['rent_price'];
	$idx=-1;
	foreach ($_POST['toggle'] as $toggle) {
		$idx++;
		$object = new stdClass();
		 
		// Must be a valid primary key value.
		$object->id = $toggle;
		$object->status = 2;	// set status 2 for decline 
		 
		// Update the table using id as the primary key
		$result = JFactory::getDbo()->updateObject('#__vehiclemanager_rent_request', $object, 'id');
		
				// query the database to obtain the record for the specific rent request
		//$database = JFactory::getDBO(); 
		$database->setQuery("SELECT l.id, l.rent_request, l.rent_from, l.rent_until, l.user_name, l.user_email, l.user_telephone, l.user_mailing, a.vtitle FROM #__vehiclemanager_rent_request AS l" .
            "\nLEFT JOIN #__vehiclemanager_vehicles AS a" .			
            "\nON l.fk_vehicleid = a.id" . 
			"\nWHERE l.id = " . $toggle);		
		$rent_request_list = $database->loadObjectList();		
		$rent_request = $rent_request_list[0];		//select the only one record

		//send email to each user about the price of the 'rent request'
		$mailSubject = "About your rent request.";
		
         $mailBody = 'Dear Mrs/Mr ' . $rent_request->user_name. ',' .
                    '<br> <br> according to your submitted rent request:<br><br> ' .
                    'Car model: ' . $rent_request->vtitle . '<br> ' .
                    'from: ' . $rent_request->rent_from . '<br>until: ' . $rent_request->rent_until . '<br><br>' .
                    'unfortunately we have to inform you that, because of availability reasons, we cannot satisfy your request.<br><br>
					Please visit our website in order to submit your request booking for a future date or for another car category.<br>
					Please be free to contact us back, via email or telephone, if you have another request.
					<br><br>
                    Best Regards<br>
                    The Corfu by Car Team';
		
		$mail->addAddress($rent_request->user_email, 'Dear Visitor');     // Add a recipient the user email
		$mail->Subject = $mailSubject;										// set mailSubject
		$mail->Body    = $mailBody;  										//'This is the HTML message body
		$mail->send();														// send the email
	}
	// now redirect to prevent duplicate form submission on refresh
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}

	$statusDescr = array("Unread", "Approved", "Declined");
	
	/****** handle if 'select Status' is submitted ********/
	if( isset($_POST['selectRecords']) && is_numeric($_POST['selectRecords']) ) {
		$selStatus = $_POST['selectRecords'];
		//echo "1) selStatus: " . $selStatus . "<br>";
	}
	else {
		$selStatus = 0;
		//echo "2) selStatus: " . $selStatus . "<br>";
		}
	

	// query the database to obtain the all the unreaded (status: 0) records
	//$database = JFactory::getDBO(); 
	$database->setQuery("SELECT a.vtitle, l.id, l.rent_request, l.rent_price, l.rent_from, l.rent_until, l.user_name, l.user_email, l.user_telephone, l.user_mailing, m.title  FROM #__vehiclemanager_vehicles AS a" .
				"\nLEFT JOIN #__vehiclemanager_rent_request AS l" .			
				"\nON l.fk_vehicleid = a.id" .
				"\nLEFT JOIN #__vehiclemanager_categories AS c" .
				"\nON c.iditem = a.id" .
				"\nLEFT JOIN #__vehiclemanager_main_categories AS m" .
				"\nON m.id = c.idcat" .
				"\nWHERE l.status = " . $selStatus .
				"\nORDER BY l.rent_request, l.rent_from, l.rent_until");
    $rent_requests = $database->loadObjectList();
	
//show form
?>


<form action="" method="post" name="adminForm"  id="adminForm" >
			<select name="selectRecords" id="selectRecords" onchange="this.form.submit()">
			  <option value="">Select Records</option>
			  <option value="0" style="color:blue" >Unread</option>
			  <option value="1" style="color:green" >Approved</option>
			  <option value="2" style="color:red" >Declined</option>
			</select>
			
            <table cellpadding="4" cellspacing="0" border="0" id="adminTable" >
                <tr>
                    <th align = "center">#</th>
                    <?php if($selStatus == 0) {	//show column 'Rent Price' only for 'Unreaded' requests
                    echo '<th align = "center">Check</th>';} ?>
                    <th align = "center" nowrap="nowrap">Request Time</th>
                    <th align = "center">Car Model (Cat.)</th>
                    <th align = "center" nowrap="nowrap">Rent From<br>-<br>Rent Until</th>
                    <!--<th align = "center" nowrap="nowrap">Rent Until</th>-->
                    <th align = "center">Rent Days</th>
					<?php if($selStatus == 0 || $selStatus == 1) {	//show column 'Rent Price' only for 'Unreaded' and 'Approved' requests
                    echo '<th align = "center">Rent Price</th>';} ?>
                    <th align = "center">Username</th>
                    <th align = "center">User Email<br>User Tel.</th>
					<!--<th align = "center">User Tel.</th>-->
                    <th align = "center">User Address</th>
					<th align = "center">Status</th>
                </tr>
                <?php
                for ($i = 0, $n = count($rent_requests); $i < $n; $i++) {
                    $row = $rent_requests[$i];
                    ?>
                    <tr>
                        <td align = "center"><?php echo ($i+1); ?></td>
                        <?php if($selStatus == 0) {	//show column 'Rent Price' only for 'Unreaded' requests
						echo '<td align = "center"><input type="checkbox" name="toggle[]" id="toggle_' . ($i+1) . '" value="' . $row->id . '" /></td>';} ?>
						<td align = "center"><?php echo $row->rent_request; ?></td>
                        <td align = "center"><?php echo $row->vtitle . " (" . $row->title .") "; ?></td>
                        <td align = "center"><?php echo $row->rent_from; ?><br>-<br><?php echo $row->rent_until; ?></td>
                        <!--<td align = "center"><?php //echo $row->rent_until; ?></td>-->
                        <td align = "center"><?php
								$rent_from = new DateTime($row->rent_from);
								$rent_until = new DateTime($row->rent_until);
								echo (1 + $difference = $rent_from->diff($rent_until)->d); ?></td>
						<!--<td align = "center"><input type="text" name="rent_price[<?php //echo  $row->id ?>]" value="" id="rent_price_<?php //echo  ($i+1) ?> " class="rent_price" maxlength="4" /></td>-->
						<?php if($selStatus == 0) {	//show column input 'Rent Price' for 'Unreaded' requests
                        echo '<td align = "center"><input type="text" name="rent_price[' . $row->id . ']" value="" id="rent_price_' . ($i+1) . '" class="rent_price" maxlength="5" /></td>'; }
						if($selStatus == 1) {	//show column value (no input) 'Rent Price' for 'Approved' requests
                        echo '<td align = "center">&euro;' . $row->rent_price . '</td>';} ?>
						<td align = "center"><?php echo $row->user_name; ?></td>
                        <td align = "center"><?php echo $row->user_email; ?><br><?php echo $row->user_telephone; ?></td>
                        <!--<td align = "center"><?php //echo $row->user_telephone; ?></td> -->
						<td align = "center"><?php echo $row->user_mailing; ?></td>
						<td align = "center" class="statusDescr_<?php echo $selStatus; ?>" ><?php echo $statusDescr[$selStatus]; ?></td>
                    </tr>				           	
                    <?php
                }
				if($selStatus == 0) {	//show button row only for 'Unreaded' requests
                        echo               
                '<tr class="lastRow">
				<td colspan="9"></td> 
				<td><input type="submit" name="acceptBtn" value="Accept" id="acceptBtn" class="adminFormButton" onclick="return validateRentPrice()" /></td>
				<td><input type="submit" name="declineBtn" value="Decline" id="declineBtn" class="adminFormButton" onclick="return validateDecline()" /></td>
				</tr>';}
				?>
            </table>

        </form>
  

<style>	
#selectRecords {
float: right !important;
height: 25px !important;
}
	
#adminTable {
	width: 900px;
	margin-bottom: 30px;
	font-size: 13px !important;
}

#adminTable th, td {
	border: 2px solid burlywood;
}

#adminTable th {
	font-weight: bold;
	text-align: center;
	vertical-align: middle;
}

#adminTable td {
	line-height: 20px;
	text-align: center;
	vertical-align: middle;
}

#adminTable input {
	width:30px;
	height:20px;
	margin-top: 10px;
}
	
#adminTable > tbody > tr.lastRow,
#adminTable > tbody > tr.lastRow > td:nth-child(1),
#adminTable > tbody > tr.lastRow > td:nth-child(2)
{
	border-left: 3px solid #eda !important;
	border-right: 3px solid #eda !important;
	border-bottom: 3px solid #eda !important;
	}	

.adminFormButton {
	width:80px !important;
	color: whitesmoke;
	background-color: darkgray;
}

.rent_price {
	width: 25px;
}

#user_tel {
	width: 25px;
}
</style>

<script>

$(".adminFormButton").hover(function(){
  $(this).css("background-color","tan");
  },
  function(){
  $(this).css("background-color","darkgray");
});

// set different text color for each description status {Unreaded: blue, Approved: green, Declined: red}
$(".statusDescr_0").css("color","blue");
$(".statusDescr_1").css("color","green");
$(".statusDescr_2").css("color","red");


/* 
* validate if the price value
* typed in the 'text' field is
* a checked, valid, not-empty and positive value
*/
function validateRentPrice() {
    var togglelCnt = document.getElementById("adminTable").rows.length - 2;
	var cnt = 0;
		
	for (i = 1; i <= togglelCnt; i++) {
		var t = document.getElementById('toggle_'+i).checked;
		var rp = document.getElementById('rent_price_'+i).value;
		if(t == true && (isNaN(rp) || parseInt(rp) <= 0 || rp == "")) {
			alert("The price is invalid " + rp);
			return false;
			}
		if(t == false) {
			cnt = cnt + 1;
			}
	}
		if(cnt == togglelCnt) {
			alert("Please check first a rent request.");
			return false;
			}
			
	return true;
}


/* 
* validate if at least one
* rent request checkbox is
* selected
*/
function validateDecline() {
    var togglelCnt = document.getElementById("adminTable").rows.length - 2;
	var cnt = 0;
		
	for (i = 1; i <= togglelCnt; i++) {
		var t = document.getElementById('toggle_'+i).checked;
		//var rp = document.getElementById('rent_price_'+i).value;
		if(t == false) {
			cnt = cnt + 1;
			}
	}
		if(cnt == togglelCnt) {
			alert("Please check first a rent request.");
			return false;
			}
			
	return true;
}

</script>