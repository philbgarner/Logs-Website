<?php 

header("Content-Type: application/json");
session_start();
include_once('dbconfig.php');

if (isset($_GET['reportType']) && strlen($_GET['reportType']) > 0)
{
	$reportType = $_GET['reportType'];
}
else
{
	//echo "['warning': 'No reportType.'];";
	echo "[]";
	exit(1);
}

if (isset($_SESSION['google_data']['id']) && preg_match('/[0-9]/', $_GET['reportType'], $matches, PREG_OFFSET_CAPTURE))
{
	$user_oauth_uid = $_SESSION['google_data']['id'];


	//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
	$query = "SELECT 	r.col_id
				,r.col_report_filename
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
					AND rgl.col_report_id = $reportType
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
		WHERE r.col_isVisible > 0 
	";

	$query = $query . ";";// COMMIT;";

	$stmt = $mysqli->stmt_init();
	if(!$stmt->prepare($query))
	{
		$mysqli->close();
		echo '["error": "Error in query."]';
		exit;
	}
	else
	{
		if ($result = $mysqli->query($query))
		{
			$row = $result->fetch_assoc();
			
		}
		else
		{
			echo '["error": "Error fetching row."]';
			exit;
		}

		$stmt->close();
	}


}
else if (!isset($_SESSION['google_data']['id']) && preg_match('/[0-9]/', $_GET['reportType'], $matches, PREG_OFFSET_CAPTURE))
{
	echo '["error": "Not authorized."]';
	exit;
}
else
{

	// If it's not an integer, it must be an API call that has
	// a string identifier, implemented via switch() { case }.

	switch($reportType)
	{
		case 'Users_New_Group':
			include('reports/usersNewGroup.php');
		break;
		case 'Users_Groups_List':
			include('reports/userGroupsList.php');
		break;	
		case 'Users_Groups_Available':
			include('reports/userGroupsAvailable.php');
		break;
		case 'Users_Set_Groups':
			include('reports/usersSetGroups.php');
		break;	
		case 'Report_Groups_List':
			include('reports/reportGroupsList.php');
		break;
		case 'Report_Set_Groups':
			include('reports/reportSetGroups.php');
		break;
		case 'Update_Groups':
			include('reports/updateGroup.php');
		break;
		case 'Delete_Group':
			include('reports/deleteGroup.php');
		break;
		default:
			$mysqli->close();
			echo '[{"message":"Set filters and click Search."}]';
		exit;
	}

}


if (isset($row['col_report_filename']))
{
	include($row['col_report_filename']);
}

$stmt = $mysqli->stmt_init();
if(!$stmt->prepare($query))
{
	$mysqli->close();
	echo '[{"error":"Failed to prepare statement"}]';
	exit;
}
else
{
	$jsonArray = array();
	if ($result = $mysqli->query($query))
	{
		while($row = $result->fetch_assoc())
		{
			$jsonArray[] = $row;
		}
		echo json_encode($jsonArray);
	}
	else
	{
		echo "[]";
	}
	
	$stmt->close();
}
	
$mysqli->close();
?>
