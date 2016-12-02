<?php 

session_start();

header("Content-Type: application/json");

$mysqli = new mysqli("149.56.191.189", "readonly", "readonly", "civex_logging"); 

if($mysqli->connect_error)
{
    die("$mysqli->connect_errno: $mysqli->connect_error");
}

//TODO: Get the report type passed along with the parameters
// and use that to initiate report type variable.

switch($reportType)
{
	case 'Chat':
		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT 	'Id' AS col_id	, 'Timestamp' AS col_timestamp	,'Channel' AS col_channel	, 'Sender' AS col_sender	,'Receiver' AS col_receiver	,'Message' AS col_message 
			UNION SELECT 	col_id	,col_timestamp	,col_channel	,col_sender	,col_receiver	,col_message 	FROM civex_logging.tbl_chat_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		if (isset($_GET['col_message']) && strlen($_GET['col_message']) > 0)
		{
			$query = $query . " $joinWord col_message LIKE '%" . $_GET['col_message'] . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_receiver']) && strlen($_GET['col_receiver']) > 0)
		{
			$query = $query . " $joinWord col_receiver LIKE '%" . $_GET['col_receiver'] . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_sender']) && strlen($_GET['col_sender']) > 0)
		{
			$query = $query . " $joinWord col_sender LIKE '%" . $_GET['col_sender'] . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_channel']) && strlen($_GET['col_channel']) > 0)
		{
			$query = $query . " $joinWord col_channel LIKE '%" . $_GET['col_channel'] . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_from']) && strlen($_GET['col_timestamp_from']) > 0)
		{
			$query = $query . " $joinWord col_timestamp >= '" . $_GET['col_timestamp_from'] . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_to']) && strlen($_GET['col_timestamp_to']) > 0)
		{
			$query = $query . " $joinWord col_timestamp <= '" . $_GET['col_timestamp_to'] . "'	";
			$joinWord = "AND";
		}

		$query = $query . ";";// COMMIT;";
	break;
	case else:
		$mysqli->close();
		echo "{\"error\":\"Did you forget to pass a reportType parameter?\"}";
		exit;
	break;
}

$stmt = $mysqli->stmt_init();
if(!$stmt->prepare($query))
{
	$mysqli->close();
	echo "{\"error\":\"Failed to prepare statement\"}";
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
		echo "No results.";
	}
	
	$stmt->close();
}
	
$mysqli->close();
?>

