<?php
	$path = dirname(realpath(__FILE__));	
	include_once($path . '/lib/header.php');
	// Start the session
	session_start();
	require 'vendor/autoload.php';
	
	$userid = $_SESSION["userid"];
	
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
	
	$link->real_query("SELECT * FROM usergallerydetails where userid='$userid'");
	$res = $link->use_result();

?>

<div id="container" style="min-height:600px;padding-left:15%;padding-right:15%;padding-top:2%">
	<div id="signUpBlock" style="padding-left:15%;padding-right:15%;padding-top:5%;padding-bottom:10%">
		<h3>Image Gallery</h3>
		<br/>
		<h4> Raw Images </h4>
		<br/>
		<div id="imagelinks">
			<?php
				//echo "Result set order...\n";
				while ($row = $res->fetch_assoc())
                		{
					echo '<a href="' . $row["s3rawurl"] . '" title="' . $row["jpgfilename"] . '"data-gallery><img src="' . $row["s3rawurl"] . '"width="75" height="75"></a>';
				}
                		//$link->close();
			?>
		</div>
		<br/>
		<h4> Reflection Images (Finished Images) </h4>
		<br/>
		<div id="finishedimagelinks">
			<?php
				//echo "Result set order...\n";
				while ($row = $res->fetch_assoc())
                		{
					echo '<a href="' . $row["s3finishedurl"] . '" title="' . $row["jpgfilename"] . '"data-gallery><img src="' . $row["s3rawurl"] . '"width="75" height="75"></a>';
				}
                		$link->close();
			?>
		</div>
		<br/>
		 <div id="blueimp-gallery" class="blueimp-gallery">
                <!-- The container for the modal slides -->
                <div class="slides"></div>
                <!-- Controls for the borderless lightbox -->
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
				<!-- The modal dialog, which will be used to wrap the lightbox content -->
				<div class="modal fade">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                        <div class="modal-header">
                                                <button type="button" class="close" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body next"></div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-default pull-left prev">
                                                        <i class="glyphicon glyphicon-chevron-left"></i>
                                                        Previous
                                                </button>
                                                <button type="button" class="btn btn-primary next">
                                                        Next
                                                        <i class="glyphicon glyphicon-chevron-right"></i>
                                                </button>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Bootstrap JS is not required, but included for the responsive demo navigation and button states -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/bootstrap.image-gallery/3.1.0/js/bootstrap-image-gallery.js"></script>
		<script src="content/js/demo.js"></script>		
	</div>

</div>

<?php
	include_once($path . '/lib/footer.php');
?>
