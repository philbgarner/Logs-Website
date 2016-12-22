<?php 

header("Content-Type: application/json");
session_start();
include_once('dbconfig.php');

$maxRows = 20;

function insertHistory($mysqli, $report_id, $report_name, $params, $oauth_id, $user_name)
{
	// Insert record of this request into the history table.

	$query = "INSERT INTO civex_logging.tbl_report_history (`col_report_id`, `col_report_name`, `col_parameters`, `col_user_oauth_id`, `col_user_name`) 
		VALUES (
			'$report_id'
			,'$report_name'
			,'$params'
			,'$oauth_id'
			,'$user_name'
		);
	";

	if(!$mysqli->query($query))
	{
		$mysqli->close();
		echo '["error": "Error inserting record to log history table, cannot proceed."]';
		exit;
	}
	else
	{
		$mysqli->commit();
	}


}

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
				,r.col_report_name
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


	// Insert a record of the API call being made.
	insertHistory($mysqli, $reportType, 'API Call', '{ "params": ' . json_encode($_GET) . '}', $_SESSION['google_data']['id'], $_SESSION['google_data']['given_name'] . ' ' . $_SESSION['google_data']['family_name']);


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
		case 'New_Report':
			include('reports/newReport.php');
		break;
		case 'Delete_Report':
			include('reports/deleteReport.php');
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

if (isset($_GET['page_number']))
{
	$startCount = ($_GET['page_number'] - 1) * $maxRows;
}
else
{
	$startCount = 0;
}

// Insert record of the report being accessed.
insertHistory($mysqli, $_GET['reportType'], $row['col_report_name'], '{ "params": ' . json_encode($_GET) . '}', $_SESSION['google_data']['id'], $_SESSION['google_data']['given_name'] . ' ' . $_SESSION['google_data']['family_name']);

$noLimitQuery = $query . ";";
$query = $query . " LIMIT $startCount, $maxRows;";

$jsonArray = array();

$stmt = $mysqli->stmt_init();
if(!$stmt->prepare($query))
{
	$mysqli->close();
	echo '[{"error":"Failed to prepare statement: ' . $query . '"}]';
	exit;
}
else
{
	if ($result = $mysqli->query($query))
	{
		while($row = $result->fetch_assoc())
		{
			$jsonArray[] = $row;
		}
	}
	else
	{
		$jsonArray = [];
	}
	
	$stmt->close();
}

$rcount = 0;
$stmt = $mysqli->stmt_init();
if(!$stmt->prepare($noLimitQuery))
{
	$mysqli->close();
	echo '[{"error":"Failed to prepare statement: ' . $noLimitQuery . '"}]';
	exit;
}
else
{
	if ($result = $mysqli->query($noLimitQuery))
	{
		$rcount = $result->num_rows;
	}
	else
	{
		$jsonArray = [];
	}
	
	$stmt->close();
}

$headerRow[] = $hRow;
$rows = array_merge($headerRow, $jsonArray);
//$rows[] = $headerRow + $jsonArray;
$rc['row_count'] = $rcount;
$rows[] = $rc;
echo json_encode($rows);
	
$mysqli->close();
?>
