<?php
		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT
				'Id' AS id
				,'Report Id' AS col_report_id
				,'Report Name' AS col_report_name
				,'Parameters' AS col_report_description
				,'User OAuth Id' AS col_user_oauth_id
				,'User Name' AS col_user_name
				,'Create Date' AS col_createdon
			UNION SELECT 
				`col_id`
				,`col_report_id`
				,`col_report_name`
				,`col_parameters`
				,`col_user_oauth_id`
				,'' AS `col_user_name`
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
			ON 	g.col_report_id = r.col_id
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

		$query = $query . ";";// COMMIT;";
?>
