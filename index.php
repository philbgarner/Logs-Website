<?php
include_once("config.php");
include_once("includes/functions.php");

ini_set('display_errors', 1);

//print_r($_GET);die;

if(isset($_REQUEST['code'])){
	$gClient->authenticate();
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location:' . filter_var($redirectUrl, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	$userProfile = $google_oauthV2->userinfo->get();
	//DB Insert
	$gUser = new Users();
	$gUser->checkUser('google',$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email'],$userProfile['gender'],$userProfile['locale'],$userProfile['link'],$userProfile['picture']);
	$_SESSION['google_data'] = $userProfile; // Storing Google User Data in Session
	//header("Location: index.php");
	$_SESSION['token'] = $gClient->getAccessToken();
} else {
	$authUrl = $gClient->createAuthUrl();
}


include_once('dbconfig.php');

$reportList = array();

if(!isset($authUrl))
{

	// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
	// because it's really awkward to do with mysqli on dynamically generated queries.
	// Simply escaping like this should prevent injection attacks.
	
	if (isset($_SESSION['google_data']['id']))
	{
		$user_oauth_uid = $_SESSION['google_data']['id'];
	

		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT 	r.col_id
					,r.col_report_name
					,r.col_report_description
					,r.col_report_image_url
			FROM civex_logging.tbl_reports AS r 
			INNER JOIN (
					SELECT
						rgl.col_report_id
					FROM
						civex_logging.tbl_report_group_link AS rgl
					INNER JOIN
						civex_logging.tbl_user_groups AS ug
					ON
						rgl.col_group_id = ug.col_id
					INNER JOIN
						civex_logging.tbl_user_group_link AS ugl
					ON
						ugl.col_group_id = ug.col_id
					INNER JOIN
						civex_logging.tbl_users_oauth u
					ON
						ugl.col_user_oauth_id = u.oauth_uid
						AND u.oauth_uid = '$user_oauth_uid'
				) AS g
			ON 	g.col_report_id = r.col_id
		";
	
		$query = $query . ";";// COMMIT;";

		$stmt = $mysqli->stmt_init();
		if(!$stmt->prepare($query))
		{
			$mysqli->close();
			exit;
		}
		else
		{
			if ($result = $mysqli->query($query))
			{
				while($row = $result->fetch_assoc())
				{
					$reportList[] = $row;
				}
			}

			$stmt->close();
		}

		$mysqli->close();

	}
	
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<meta name="google-site-verification" content="vfRCO2UBt0e_dFDVupt2po23bF7AIHC9YE5R5aZ1tM0" />

	<title>CivEx3 Logs</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/clean-blog.css" rel="stylesheet">

	<!-- Calendar plugin -->
	<link rel="stylesheet" href="css/flatpickr.css">

	<link rel="stylesheet" href="css/reporting-styles.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!-- jQuery -->
	<script src="js/jquery.js"></script>
	<script src="js/flatpickr.js"></script>

	<!-- Load the Plugin ENUM lists for drop-downs -->	
	<script src="js/materials.js"></script>
	<script src="js/actions.js"></script>
	<script src="js/entities.js"></script>
	<script src="js/ui_parameters.js"></script>

	<script>

		function goHome()
		{
			$("#nav").slideDown("fast");
			$("#reports").slideUp("fast");
			$("#reportParams").empty();
		}
	
		function changeReport(report, reportId)
		{
			// This function is called when the user selects a report from the initial menu.
			// The navigaiton bar is hidden from view and the report parameters panel is 
			// dynamically populated with the DOM elements needed for each page.
		
			$("#nav").slideUp("fast");
			$("#reports").slideUp("fast");
			$("#reportParams").empty();

			// These functions come from js/ui_parameters.js
			// They populate the panel above the results table 
			// with the input filters for that report.
			switch(report)
			{
				case 'Chat':
					
					chatParams(report, reportId);

				break;
				case 'Blocks / Reinforcements':
				
					blockParams(report, reportId);
															
				break;
				case 'Sessions':

					sessionsParams(report, reportId);

				break;
				case 'Commands':

					commandsParams(report, reportId);

				break;
				case 'Entities':
					
					entitiesParams(report, reportId);

				break;
				case 'Users':
					
					usersParams(report, reportId);

				break;
				case 'Report Management':
					
					reportParams(report, reportId);

				break;
				case 'Group Management':
					
					groupParams(report, reportId);

				break;
				case 'Report History':
					
					historyParams(report, reportId);

				break;
			}
		}
		

		
		$(document).ready(function (m) {
	
		
			$(".post-preview a").click(function (m) {
				var report = $(m.currentTarget).find("h2")[0].innerText;
				var reportId = $(m.currentTarget).attr("col_id");
				changeReport(report, reportId);
				loadTableData();
			});
			$("#navmenu a").click(function (m) {
				var report = $(m.currentTarget).find("span")[0].innerText;
				var reportId = $(m.currentTarget).attr("col_id");
				changeReport(report, reportId);
				loadTableData();
			});
						
		});
		
		
	</script>


</head>

<body>

	<!-- Page Header -->
	<!-- Set your background image for this header on the line below. -->
	<header class="intro-header" style="background-image: url('img/home-bg.jpg')" style="height: 2em;">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
					<div style="color: #f1f1f1; text-align: center;">
						<h1>CivEx3 Logs</h1>
					</div>
				</div>
			</div>
		</div>
	</header>
	
	<!-- Drop Down Menu Navigation -->
	<div id="navmenu">
		<div class="row">
		<ul>
		<li><a href="#">Menu</a>
			<ul>
		<?php

		if(!isset($authUrl))
		{

			// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
			// because it's really awkward to do with mysqli on dynamically generated queries.
			// Simply escaping like this should prevent injection attacks.
	
			if (isset($_SESSION['google_data']['id']))
			{

				foreach($reportList as $row)
				{
					echo '<li>
						<a href="#" col_id="' . $row['col_id'] . '">
							<span>
								' . $row['col_report_name'] . '
							</span>
						</a>

					</li>';
				}

				echo '
	
					<li>
						<a href="logout.php?logout"><span>Logout from Google</span></a>
					</li>
				';
			}
	
		}
		?>
			</ul>
		</li>
		<li><a href="index.php">Home</a></li>
		</ul>
		</div>
	</div>
	
	<div id="nav" class="container">
		<div class="row">

<?php

if(isset($authUrl))
{
	echo '
		<div class="post-preview report-tile">
			<a href="'.$authUrl.'">
				<h2 class="post-title">Authentication</h2>
				<h3>Login with Google</h3>
			</a>
		</div>
	';
}
else
{

	// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
	// because it's really awkward to do with mysqli on dynamically generated queries.
	// Simply escaping like this should prevent injection attacks.
	
	if (isset($_SESSION['google_data']['id']))
	{

		foreach($reportList as $row)
		{
			echo '<div class="post-preview report-tile">
				<a href="#" col_id="' . $row['col_id'] . '">
					<img src="' . $row['col_report_image_url'] . '"/>
					<h2 class="post-title">
						' . $row['col_report_name'] . '
					</h2>
					<h3 class="post-subtitle">
						' . $row['col_report_description'] . '
					</h3>
				</a>

			</div>';
		}

		echo '
	
			<div class="post-preview report-tile">
				<a href="logout.php?logout"><h2 class="post-title">Welcome ' . $_SESSION['google_data']['given_name'] . '</h2>
				<h3 class="post-subtitle">Logout from Google</h3></a>
			</div>
		';
	}
	
}

?>

		</div>
		
	</div>

	<div id="reports" class="container">
		<div id="reportParams">
		</div>
		<div id="pageContainer" style="overflow: scroll; padding: 0px; margin: 0px;">
			<p>
				Select a report from the menu above.
			</p>
		</div>
	</div>

	<hr>

	<!-- Footer -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
					<ul id="pageID" class="list-inline text-center">
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
						<li>
							<a href="#">
								<span class="fa-stack fa-lg">
									<i class="fa fa-circle fa-stack-2x"></i>
									<i class="fa fa-github fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</li>
					</ul>
					<p class="copyright text-muted">Copyright &copy; CivEx 2016</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- Contact Form JavaScript -->
	<script src="js/jqBootstrapValidation.js"></script>
	<script src="js/contact_me.js"></script>

	<!-- Theme JavaScript -->
	<script src="js/clean-blog.min.js"></script>
	
	<div class="modal fade" tabindex="-1" role="dialog" id="dlgUserGroups">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">User Groups</h3>
				</div>
				<div class="modal-body">
					<div>
						<h4>Current Groups</h4>
						<p id="userFullName"></p>
						<ul class="grouplist" id="currentGroups">
							<i>Loading...</i>
						</ul>

						<hr/>

						<h4>Available Groups</h4>
						
						<ul class="grouplist" id="availableGroups">
							<i>Loading...</i>
						</ul>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnSaveChanges">Save Changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	
	<div class="modal fade" tabindex="-1" role="dialog" id="dlgReportGroups">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Report Settings</h3>
				</div>
				<div class="modal-body">
					<div>
						<h4>Current Groups</h4>
						<p id="userFullName"></p>
						<ul class="grouplist" id="currentReportGroups">
							<i>Loading...</i>
						</ul>

						<hr/>

						<h4>Available Groups</h4>
						
						<ul class="grouplist" id="availableReportGroups">
							<i>Loading...</i>
						</ul>
					</div>
					
					<div>
						<h4>Report Name</h4>

						<input id="reportName" size=30 value=""/>
					</div>

					<div>
						<h4>Report Description</h4>

						<input id="reportDescription" size=30 value=""/>
					</div>

					<div>
						<h4>Report Script</h4>

						<input id="reportScript" size=30 value=""/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="btnReportRemove" data-dismiss="modal">Remove</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnReportSaveChanges">Save Changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="dlgGroupSettings">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">Group Settings</h3>
				</div>
				<div class="modal-body">			
					<div>
						<h4>Group Name</h4>

						<input id="groupName" size=30 value=""/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="btnGroupRemove" data-dismiss="modal">Remove</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnGroupSaveChanges">Save Changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</body>

</html>
