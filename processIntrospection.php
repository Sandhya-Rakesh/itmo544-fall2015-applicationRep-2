<?php 

	$path = dirname(realpath(__FILE__));
	require 'vendor/autoload.php';
	
	if (isset($_POST["btnAdmin"])) {
		
		$rds = new Aws\Rds\RdsClient([
			'version' => 'latest',
			'region'  => 'us-west-2'
		]);
		
		$result = $rds->describeDBInstances([
			'DBInstanceIdentifier' => 'mp1-sg',
		]);
		
		$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
		
		//echo "begin database";
		$link = mysqli_connect($endpoint,"sandhyagupta","sandhya987","customerrecords") or die("Error " . mysqli_error($link));
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		if (isset($_POST['datadump'])) {
			$datadumpfilename='database_backup_'.date('G_a_m_d_y').'.sql';	
			$res = $link->query("SHOW tables from customerrecords");
			$res = $link->use_result();
			echo "Result set order...\n";
			while ($row = $res->fetch_assoc()) { 
				$backupFile='/tmp/Database_Backups_Full/'.$res[0].'.sql';
				$sql2="SELECT * INTO OUTFILE '$backupFile' from '".$res[0]."' ";
				$res2 = $link->query($sql2);
				echo $res[0].' '.$backupFile.'<br><br>';
			}
			
		}
		else {
			echo 'Admin did not select to take a datadump';
		}
		
		if (isset($_POST['disableuser'])) {
			$disableuser = 'Y';
		}
		else {
			$disableuser = 'N';
		}
		
		
		/* Prepared statement, stage 1: prepare */
		//Inserting data into userdetails table
		if (!($stmt = $link->prepare("INSERT INTO introspection (introid,introspectionavailable) VALUES (NULL,?)"))) {
			echo "Prepare failed: (" . $link->errno . ") " . $link->error;
		}
	
		$stmt->bind_param("s",$disableuser);
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		} else {
			printf("%d Row inserted into introspection table.\n", $stmt->affected_rows);
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
			$extra = 'uploadimage.php';
			header("Location: http://$host$uri/$extra?up=1");
		}
	
		/* explicit close recommended */
		$stmt->close();
		$link->close();
	}
	
?>
