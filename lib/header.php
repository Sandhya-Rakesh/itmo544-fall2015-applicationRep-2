<?php
session_start();
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="header.css">
		<title>Gallery Application</title>
	</head>
	<body style="margin:0">
		<!-- Header -->
		<div id="header" style="height:100px;background-color:97B95A">
			<div id="image" style="width:5%">
				<IMG style="height:60px;width:100%;margin-top:20px;float:left" SRC= "http://<?php echo $_SERVER["HTTP_HOST"];?>/galleryapp/content/images/IIT_Scarlet_Hawks.svg.png">
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
			</div>
		</div>
		<!--End of Header -->

