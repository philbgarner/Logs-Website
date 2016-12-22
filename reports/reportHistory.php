<?php
		$hRow['col_id'] = 'Id';
		$hRow['col_report_id'] = 'Report Id';
		$hRow['col_report_name'] = 'Report Name';
		$hRow['col_parameters'] = 'Parameters';
		$hRow['col_user_oauth_id'] = 'User OAuth Id';
		$hRow['col_user_name'] = 'User Name';
		$hRow['col_createdon'] = 'Create Date';
		
		$query = "SELECT 
				`col_id`
				,r.col_report_id
				,`col_report_name`
				,`col_parameters`
				,`col_user_oauth_id`
				,`col_user_name`
				,`col_createdon`
			FROM civex_logging.tbl_report_history AS r 
			LEFT JOIN (
					SELECT
						rgl.col_report_id
					FROM
						civex_logging.tbl_report_group_link AS rgl
					INNER JOIN
						civex_logging.tbl_user_groups AS ug
					ON
						rgl.col_group_id = ug.col_id
					GROUP BY rgl.col_report_id
				) AS g 
			ON  	g.col_report_id = r.col_report_id 
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

?>
