<?php 
	$path = dirname(realpath(__FILE__));	
	include_once($path . '/lib/header.php');
?>

<div id="container" style="min-height:600px;padding-left:15%;padding-right:15%;padding-top:2%">
	<form name="signupform" id="signupform" method="post" action="processSignUp.php">
		<div id="signUpBlock" style="padding-left:15%;padding-right:15%;padding-top:5%;padding-bottom:10%">
			<h3>Enter User Details</h3>
			<br/>
			<label for="username" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Enter your name: </label>
			<input type="text" id="username" name="username" style="width:50%;height:4%;"/><br><br>
			
			<label for="useremail" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Enter your email: </label>
			<input type="text" id="useremail" name="useremail" style="width:50%;height:4%;"/><br><br>
			
			<label for="userphone" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Enter your phone(1XXXXXXXXXX): </label>
			<input type="text" id="userphone" name="userphone" style="width:50%;height:4%;"/><br><br>
			
			<label for="subscription" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Do you want to subscribe for email notifications: </label>
			<select name="usersubscription" id="usersubscription">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select><br><br>
			
			<input type="submit" id="btnSignComplete" name="btnSignComplete" value="Sign Up" />
		</div>
	</form>
</div>

<?php
	include_once($path . '/lib/footer.php');
?>
