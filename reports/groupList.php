<?php
		$hRow['col_id'] = 'Id';
		$hRow['col_group_name'] = 'Group Name';
		$hRow['col_group_createdon'] = 'Created On';
		
		$query = "SELECT 	u.col_id
					,u.col_group_name
					,u.col_group_createdon
			FROM civex_logging.tbl_user_groups AS u 
			 ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_id']) && strlen($_GET['col_id']) > 0)
		{
			$query = $query . " $joinWord r.col_id = " . $mysqli->real_escape_string($_GET['col_id']) . "	";
			$joinWord = "AND";
		}
		
		if (isset($_GET['col_group_name']) && strlen($_GET['col_group_name']) > 0)
		{
			$query = $query . " $joinWord r.col_id = " . $mysqli->real_escape_string($_GET['col_group_name']) . "	";
			$joinWord = "AND";
		}

?>
