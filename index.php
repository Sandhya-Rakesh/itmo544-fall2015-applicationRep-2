<?php
	$path = dirname(realpath(__FILE__));	
	include_once($path . '/lib/header.php');
?>

<div id="container" style="min-height:600px;padding-left:15%;padding-right:15%;padding-top:2%">
	<?php 
	if(isset($_GET['st']) && $_GET['st'] == 1)
	{
	?>
	<div style="padding:2px;width:100%;background-color:#FFF9F7;color:#DD0A58;text-align:center;float:left;border:1px solid #CD0A0A;">
		The useremail you entered is incorrect. Please sign up if you haven't yet.
	</div>
	<?php
	}
	?>cd ..
	<form name="login" id="login" method="post" action="processLogin.php">
		<div id="loginBlock" style="padding-left:15%;padding-right:15%;padding-top:10%;padding-bottom:10%">
			<label for="useremail" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Enter your email: </label>
			<input type="text" id="useremail" name="useremail" style="width:50%;height:4%;"/><br><br>
			
			<input type="submit" id="btnLogin" name="btnLogin" value="Login" />
			<input type="submit" id="btnSignUp" name="btnSignUp" value="Sign Up" />
		
		</div>
	</form>
</div>

<?php
	include_once($path . '/lib/footer.php');
?>

