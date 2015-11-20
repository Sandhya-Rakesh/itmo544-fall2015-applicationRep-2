<?php 
	session_start();
	$path = dirname(realpath(__FILE__));
	
	require 'vendor/autoload.php';
	
	if (isset($_POST["btnLogin"])) {
		$email = $_POST['useremail'];
		$_SESSION["useremail"] = $email;
		
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
		
		$link->real_query("SELECT * FROM userdetails where email='$email'");
		$res = $link->use_result();
		echo "Result set order...\n";
		
		if ($res->num_rows != 0) {
			while ($row = $res->fetch_assoc()) {  
					if(!isset($row['email']))
					{
						//If the user has not yet signed up then tell them to sign up
						$host  = $_SERVER['HTTP_HOST'];
						$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
						$extra = 'index.php';
						header("Location: http://$host$uri/$extra?st=1");
						//echo "empty";
					}
					else
					{
						echo $row['email'];
						$_SESSION["userid"] = $row['id'];
						$_SESSION["username"] = $row['uname'];
						$_SESSION["useremail"] = $row['email'];
						$_SESSION["usersubscription"] = $row['subscription'];
						$host  = $_SERVER['HTTP_HOST'];
						$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
						$extra = 'uploadimage.php';
						header("Location: http://$host$uri/$extra");
					}
			}
		}
		else {
			echo "No records to fetch";
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
			$extra = 'signup.php';
			header("Location: http://$host$uri/$extra?su=1");
		}
		
		$link->close();
		
	} else if (isset($_POST["btnSignUp"])) {  
		//Redirect to signup.php
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); 
		$extra = 'signup.php';
		header("Location: http://$host$uri/$extra");
		
	} 

?>
