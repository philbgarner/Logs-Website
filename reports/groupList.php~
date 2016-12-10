<?php
		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT
				'Id' AS id
				,'Report Name' AS col_report_name
				,'Description' AS col_report_description
				,'Report Groups' AS report_groups
				,'Script Name' AS col_report_script
			UNION SELECT 	r.col_id
					,r.col_report_name
					,r.col_report_description
					,g.report_groups
					,r.col_report_filename
			FROM civex_logging.tbl_reports AS r 
			LEFT JOIN (
					SELECT
						rgl.col_report_id
						,GROUP_CONCAT(DISTINCT ug.col_group_name) AS report_groups
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
		
		if (isset($_GET['col_group_name']) && strlen($_GET['col_group_name']) > 0)
		{
			$query = $query . " $joinWord r.col_id = " . $mysqli->real_escape_string($_GET['col_group_name']) . "	";
			$joinWord = "AND";
		}

		$query = $query . ";";// COMMIT;";
?>
