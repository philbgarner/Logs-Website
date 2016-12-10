<?php
		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT
				'Id' AS id
				,'Picture' AS picture
				,'First Name' AS fname
				,'Last Name' AS lname
				,'Current Groups' AS group_list
			UNION SELECT 	u.id
					,u.picture
					,u.fname
					,u.lname
					,g.group_list
			FROM civex_logging.tbl_users_oauth AS u 
			LEFT JOIN (
					SELECT
						ugl.col_user_oauth_id
						,GROUP_CONCAT(DISTINCT ug.col_group_name) AS group_list
					FROM
						civex_logging.tbl_user_group_link AS ugl
					INNER JOIN
						civex_logging.tbl_user_groups AS ug
					ON
						ugl.col_group_id = ug.col_id
					GROUP BY ugl.col_user_oauth_id
				) AS g
			ON 	g.col_user_oauth_id = u.oauth_uid
			 ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_id']) && strlen($_GET['col_id']) > 0)
		{
			$query = $query . " $joinWord u.id = " . $mysqli->real_escape_string($_GET['col_id']) . "	";
			$joinWord = "AND";
		}

		$query = $query . ";";// COMMIT;";
?>
