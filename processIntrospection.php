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
		
		if ($_POST['datadump'] == "Y") {
			
			$backupFile = '/tmp/customerrecords_database_backup.sql';
            		$command = "mysqldump --opt -h $endpoint -u sandhyagupta -psandhya987 customerrecords | gzip > $backupFile";
            		exec($command);
			
			$s3 = new Aws\S3\S3Client([
				'version' => 'latest',
				'region'  => 'us-west-2'
			]);
			
			$bucket='nankurunaisa-'.rand().'-datadump';
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
					'Key' => $backupFile,
					'SourceFile'   => $backupFile,
				]);

				// Print the URL to the object.
				$url = $result['ObjectURL'];
				echo $url;
			} catch (S3Exception $e) {
				echo $e->getMessage() . "\n";
			}
		}
		else {
			echo 'Admin did not select to take a datadump';
		}
		
		if ($_POST['disableuser']  == "Y") {
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
			$extra = 'introspection.php';
			header("Location: http://$host$uri/$extra?it=1");
		}
	
		/* explicit close recommended */
		$stmt->close();
		$link->close();
	}
	
?>
