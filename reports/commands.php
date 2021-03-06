<?php
		$hRow['col_id'] = 'Id';
		$hRow['col_timestamp'] = 'Timestamp';
		$hRow['col_player'] = 'Player';
		$hRow['col_command'] = 'Command';
		$hRow['col_arguments'] = 'Arguments';
		$hRow['col_cancelled'] = 'Cancelled';
		
		$query = "SELECT 	col_id
					,col_timestamp
					,col_player
					,col_command
					,col_arguments
					,col_cancelled
	 		FROM civex_logging.tbl_command_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_player']) && strlen($_GET['col_player']) > 0)
		{
			$query = $query . " $joinWord col_player LIKE '%" . $mysqli->real_escape_string($_GET['col_player']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_command']) && strlen($_GET['col_command']) > 0)
		{
			$query = $query . " $joinWord col_command LIKE '%" . $mysqli->real_escape_string($_GET['col_command']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_arguments']) && strlen($_GET['col_arguments']) > 0)
		{
			$query = $query . " $joinWord col_arguments LIKE '%" . $mysqli->real_escape_string($_GET['col_arguments']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_cancelled']) && strlen($_GET['col_cancelled']) > 0)
		{
			$query = $query . " $joinWord col_cancelled = " . $mysqli->real_escape_string($_GET['col_cancelled']) . "	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_from']) && strlen($_GET['col_timestamp_from']) > 0)
		{
			$query = $query . " $joinWord col_timestamp <= '" . $mysqli->real_escape_string($_GET['col_timestamp_from']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_to']) && strlen($_GET['col_timestamp_to']) > 0)
		{
			$query = $query . " $joinWord col_timestamp >= '" . $mysqli->real_escape_string($_GET['col_timestamp_to']) . "'	";
			$joinWord = "AND";
		}

?>
