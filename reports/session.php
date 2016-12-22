<?php
		$hRow['col_id'] = 'Session Id';
		$hRow['col_player_uuid'] = 'Player UUID';
		$hRow['col_player_name'] = 'Player Name';
		$hRow['col_ip'] = 'IP Address';
		$hRow['col_login'] = 'Login Time';
		$hRow['col_logout'] = 'Logout Time';
		
		$headerRow[] = $hRow;
		$query = "SELECT 	col_id
					,col_player_uuid
					,col_player_name
					,col_ip
					,col_login
					,col_logout
	 		FROM civex_logging.tbl_session_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_player_name']) && strlen($_GET['col_player_name']) > 0)
		{
			$query = $query . " $joinWord col_player_name LIKE '%" . $mysqli->real_escape_string($_GET['col_player_name']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_ip']) && strlen($_GET['col_ip']) > 0)
		{
			$query = $query . " $joinWord col_ip LIKE '%" . $mysqli->real_escape_string($_GET['col_ip']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_login_from']) && strlen($_GET['col_timestamp_login_from']) > 0)
		{
			$query = $query . " $joinWord col_login >= '" . $mysqli->real_escape_string($_GET['col_timestamp_login_from']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_login_to']) && strlen($_GET['col_timestamp_login_to']) > 0)
		{
			$query = $query . " $joinWord col_login <= '" . $mysqli->real_escape_string($_GET['col_timestamp_login_to']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_logout_from']) && strlen($_GET['col_timestamp_logout_from']) > 0)
		{
			$query = $query . " $joinWord col_logout >= '" . $mysqli->real_escape_string($_GET['col_timestamp_login_from']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_logout_to']) && strlen($_GET['col_timestamp_logout_to']) > 0)
		{
			$query = $query . " $joinWord col_logout <= '" . $mysqli->real_escape_string($_GET['col_timestamp_logout_to']) . "'	";
			$joinWord = "AND";
		}
?>
