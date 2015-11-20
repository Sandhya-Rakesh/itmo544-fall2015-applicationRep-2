<?php 
	$path = dirname(realpath(__FILE__));	
	include_once($path . '/lib/header.php');
?>

<div id="container" style="min-height:600px;padding-left:15%;padding-right:15%;padding-top:2%">

	<form enctype="multipart/form-data" id="uploadImage" action="submit.php" method="POST">
		<div id="uploadBlock" style="padding-left:15%;padding-right:15%;padding-top:10%;padding-bottom:10%">
			<?php 
				if(isset($_GET['up']) && $_GET['up'] == 1)
				{
			?>
			<div style="padding:2px;width:100%;background-color:#FFF9F7;color:#DD0A58;text-align:center;float:left;border:1px solid #CD0A0A;">
				You have successfully signed up. Please check your email if you have subscribed for email notifications.
			</div>
			<?php
				}
			?>
			<!-- MAX_FILE_SIZE must precede the file input field -->
			<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
			<!-- Name of input element determines name in $_FILES array -->
			<label for="sendfile" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Send this file: </label>
			<input name="userfile" type="file" /> <br><br>
			
			<input type="submit" id="btnSendFile" name="btnSendFile" value="Send File" />
		</div>
	</form>
</div>

<?php
	include_once($path . '/lib/footer.php');
?>
