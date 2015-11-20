<?php
	// Start the session
	session_start();
	require 'vendor/autoload.php';
	// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
	// of $_FILES.
	$uploaddir = '/tmp/';
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	echo '<pre>';
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		echo "File is valid, and was successfully uploaded.\n";
	} else {
		echo "Possible file upload attack!\n";
	}
	echo 'Here is some more debugging info:';
	print_r($_FILES);
	print "</pre>";
	
	use Aws\S3\S3Client;
	$s3 = new Aws\S3\S3Client([
		'version' => 'latest',
		'region'  => 'us-west-2'
	]);
	
	$bucket='nankurunaisa-'.rand();
	
	if(!$s3->doesBucketExist($bucket)) {
	// AWS PHP SDK version 3 create bucket
	$result = $s3->createBucket([
		'ACL' => 'public-read',
		'Bucket' => $bucket,
	]);
	
	$s3->waitUntil('BucketExists', array('Bucket' => $bucket));
	echo "$bucket Created";
	}
	
	
	try {
		// Upload data.
		$result = $s3->putObject([
			'ACL' => 'public-read',
			'Bucket' => $bucket,
			'Key' => $uploadfile,
			'SourceFile'   => $uploadfile,
			'ContentType' =>'image/jpeg',
		]); 
	
		// Print the URL to the object.
		$url = $result['ObjectURL'];
		echo $url;
	} catch (S3Exception $e) {
		echo $e->getMessage() . "\n";
	}
	
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
	if (!($stmt = $link->prepare("INSERT INTO usergallerydetails (id,userid,s3rawurl,s3finishedurl,jpgfilename,status) VALUES (NULL,?,?,?,?,?)"))) {
		echo "Prepare failed: (" . $link->errno . ") " . $link->error;
	}
	
	$userid = $_SESSION["userid"];
	$s3rawurl = $url; //  $result['ObjectURL']; from above
	$filename = basename($_FILES['userfile']['name']);
	$s3finishedurl = "none";
	$status =0;
	
	$stmt->bind_param("ssssi",$userid,$s3rawurl,$s3finishedurl,$filename,$status);
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	} else {
		printf("%d Row inserted into userdetails table.\n", $stmt->affected_rows);
		
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
		
		$res = $sns->publish([
			'TopicArn' => $snsarn,
			// Message is required
			'Message' => 'You just uploaded an image successfully. Congratulations!!',
			'Subject' => 'Uploaded image successfully to gallery app'
		]);
		
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
		$extra = 'gallery.php';
		header("Location: http://$host$uri/$extra");
	}
	/* explicit close recommended */
	$stmt->close();
	$link->close();
  
?>
