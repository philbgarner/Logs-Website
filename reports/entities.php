<?php
		$hRow['col_id'] = 'Id';
		$hRow['col_entity_type'] = 'Entity Type';
		$hRow['col_timestamp'] = 'Timestamp';
		$hRow['col_jocky'] = 'Jocky';
		$hRow['col_x'] = 'EntityX';
		$hRow['col_y'] = 'EntityY';
		$hRow['col_z'] = 'EntityZ';
		
		$query = "SELECT 	col_id
					,col_entity_type
					,col_timestamp
					,col_jocky
					,col_x
					,col_y
					,col_z
	 		FROM civex_logging.tbl_entity_spawn_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_entity_type']) && strlen($_GET['col_entity_type']) > 0)
		{
			$query = $query . " $joinWord col_entity_type LIKE '%" . $mysqli->real_escape_string($_GET['col_entity_type']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_jocky']) && strlen($_GET['col_jocky']) > 0)
		{
			$query = $query . " $joinWord col_jocky =" . $mysqli->real_escape_string($_GET['col_jocky']) . "	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_x']) && strlen($_GET['col_x']) > 0)
		{
			$query = $query . " $joinWord col_x = " . $mysqli->real_escape_string($_GET['col_x']) . "	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_y']) && strlen($_GET['col_y']) > 0)
		{
			$query = $query . " $joinWord col_y = " . $mysqli->real_escape_string($_GET['col_y']) . "	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_z']) && strlen($_GET['col_z']) > 0)
		{
			$query = $query . " $joinWord col_z = " . $mysqli->real_escape_string($_GET['col_z']) . "	";
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

?>
