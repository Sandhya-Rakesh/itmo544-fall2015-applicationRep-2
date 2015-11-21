<?php 
	session_start();
	$path = dirname(realpath(__FILE__));
	
	require 'vendor/autoload.php';
	
	$uname = $_POST['username'];
	$email = $_POST['useremail'];
	$phone = $_POST['userphone'];
	$subscription = $_POST['usersubscription'];
	
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
	/* Prepared statement, stage 1: prepare */
	//Inserting data into userdetails table
	if (!($stmt = $link->prepare("INSERT INTO userdetails (id,uname,email,phone,subscription) VALUES (NULL,?,?,?,?)"))) {
		echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}
	
	$stmt->bind_param("ssss",$uname,$email,$phone,$subscription);
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	} else {
		printf("%d Row inserted into userdetails table.\n", $stmt->affected_rows);
		$_SESSION["userid"] = mysqli_insert_id($link);
		$_SESSION["username"] = $uname;
		$_SESSION["useremail"] = $email;
		$_SESSION["usersubscription"] = $subscription;	
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
		$extra = 'uploadimage.php';
		header("Location: http://$host$uri/$extra?up=1");
	}
	
	/* explicit close recommended */
	$stmt->close();
	
	
	if ($subscription == "Y")
	{
		$link->real_query("SELECT snsarn FROM snsdetails where snsdisplayname='mp2UploadImages-sg'");
		$res = $link->use_result();
		echo "Result set order...\n";
		while ($row = $res->fetch_assoc()) {  
			$snsarn = $row['snsarn'];
		}
		
		$sns = new Aws\Sns\SnsClient([
			'version' => 'latest',
			'region'  => 'us-west-2'
		]);
		
		$snsresult = $sns->subscribe([
			// TopicArn is required
			'TopicArn' => $snsarn,
			// Protocol is required
			'Protocol' => 'email',
			'Endpoint' => $email,
		]);
	}
	
	$link->close();
		
	
?>
