<?php
		$hRow['col_id'] = 'Id';
		$hRow['col_timestamp'] = 'Timestamp';
		$hRow['col_channel'] = 'World';
		$hRow['col_sender'] = 'Action';
		$hRow['col_receiver'] = 'Player';
		$hRow['col_message'] = 'Item Held Right';
		
		$query = "SELECT 	col_id	,col_timestamp	,col_channel	,col_sender	,col_receiver	,col_message 	FROM civex_logging.tbl_chat_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_message']) && strlen($_GET['col_message']) > 0)
		{
			$query = $query . " $joinWord col_message LIKE '%" . $mysqli->real_escape_string($_GET['col_message']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_receiver']) && strlen($_GET['col_receiver']) > 0)
		{
			$query = $query . " $joinWord col_receiver LIKE '%" . $mysqli->real_escape_string($_GET['col_receiver']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_sender']) && strlen($_GET['col_sender']) > 0)
		{
			$query = $query . " $joinWord col_sender LIKE '%" . $mysqli->real_escape_string($_GET['col_sender']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_channel']) && strlen($_GET['col_channel']) > 0)
		{
			$query = $query . " $joinWord col_channel LIKE '%" . $mysqli->real_escape_string($_GET['col_channel']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_from']) && strlen($_GET['col_timestamp_from']) > 0)
		{
			$query = $query . " $joinWord col_timestamp >= '" . $mysqli->real_escape_string($_GET['col_timestamp_from']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_to']) && strlen($_GET['col_timestamp_to']) > 0)
		{
			$query = $query . " $joinWord col_timestamp <= '" . $mysqli->real_escape_string($_GET['col_timestamp_to']) . "'	";
			$joinWord = "AND";
		}

		$query = $query . " $joinWord col_sender NOT LIKE '%HiImPosey%' AND col_receiver NOT LIKE '%HiImPosey%' "; // This could be moved out to a proper table
														// and inner joined on user id so that exclusion names
														// can be dynamically updated.

?>
