<?php 
	$path = dirname(realpath(__FILE__));	
	include_once($path . '/lib/header.php');
?>

<div id="container" style="min-height:600px;padding-left:15%;padding-right:15%;padding-top:2%">
	<?php 
	if(isset($_GET['it']) && $_GET['it'] == 1)
	{
	?>
	<div style="padding:2px;width:100%;background-color:#FFF9F7;color:#DD0A58;text-align:center;float:left;border:1px solid #CD0A0A;">
		Successful.
	</div>
	<?php
	}
	?>
	<form name="adminform" id="adminform" method="post" action="processIntrospection.php">
		<div id="dataBlock" style="padding-left:15%;padding-right:15%;padding-top:5%;padding-bottom:10%">
		
			<label for="databackup" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Do you want to take a data export: </label>
			<select name="datadump" id="datadump">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select><br><br>
		
			<label for="disableuser" style="text-align:center;font-family:calibri;font-weight:bold;font-size:110%"]>Do you want to disable user uploads: </label>
			<select name="disableuser" id="disableuser">
				<option value="Y">Yes</option>
				<option value="N">No</option>
			</select><br><br>
			<input type="submit" id="btnAdmin" name="btnAdmin" value="Submit" />
		</div>
	</form>
</div>

<?php
	include_once($path . '/lib/footer.php');
?>
