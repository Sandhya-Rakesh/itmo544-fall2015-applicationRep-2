<?php
session_start();
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        	<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
        	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.image-gallery/3.1.0/css/bootstrap-image-gallery.css">
		<link rel="stylesheet" type="text/css" href="header.css">
		<title>Gallery Application</title>
	</head>
	<body style="margin:0">
		<!-- Header -->
		<div id="header" style="height:100px;background-color:97B95A">
			<div id="image" style="width:5%">
				<IMG style="height:60px;width:100%;margin-top:20px;float:left" SRC= "http://<?php echo $_SERVER["HTTP_HOST"];?>/content/images/IIT_Scarlet_Hawks.svg.png">
			</div>
			
			<div id="text" style="width=95%">
				<div id="headerText" style="color:FFFFCC;width:75%;padding-left:20%;font-family:calibri;font-size:300%;font-style:oblique;font-weight:bold;float:left">
					Gallery Application
				</div>
				<!-- Tag line-->
				<div id="headerText1" style="color:FFFFCC;padding-left:25%;height:40px;font-family:calibri;font-size:100%;font-style:oblique;font-weight:bold;float:left">
					Make your images beautiful
				</div>
				<!-- End of Tag line -->
				<!-- Menu Tabs -->
				<div id="menu" style="padding-left:65%;">
					<ul style="list-style-type:none;margin:0">
					<?php
					if(isset($_SESSION['useremail']))
					{
					?>
						<li style="display:inline;padding-left:10%"><a href="http://<?php echo $_SERVER["HTTP_HOST"];?>/../logout.php" style="text-decoration:none;text-align:center;color:FFFFCC;font-weight:bold;">Logout</a></li>
					<?php
					}
					?>
					</ul>
				</div>
				<!-- End of Menu Tabs -->
			</div>
		</div>
		<!--End of Header -->

