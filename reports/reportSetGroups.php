<?php
		// Delete former group associations for this user
		// and insert the updated list, evaluating each group name
		// one at a ttime.
		
		$col_id = $mysqli->real_escape_string($_GET['col_id']);
		$col_report_filename = $mysqli->real_escape_string($_GET['col_report_filename']);
		$group_list[] = explode(",", $mysqli->real_escape_string($_GET['col_group_names']));

		$query = "DELETE FROM civex_logging.tbl_report_group_link WHERE col_report_id = '$col_id' AND col_group_id <> 1;"; // col_group_id = 1 is the Owner group.
//		echo $query;
		$stmt = $mysqli->stmt_init();
		if(!$stmt->prepare($query))
		{
			$mysqli->close();
			echo '[{"error":"Failed to delete user group links: ' . $stmt->error . '"},{"query": "' . $query . '"}]';
			exit;
		}
		else
		{
			if ($result = $mysqli->query($query))
			{
				//echo '["message": "Deleted former groups successfully."]';
			}
			else
			{
				echo "[]";
				$stmt->close();
				$mysqli->close();
				exit;

			}
		}
		foreach($group_list[0] as $group)
		{
			// Hard-coded exception for 'Owner' group.
			// Can only be changed with terminal or ftp access.
			if ($group != "Owner")
			{
				
				$query = "INSERT INTO civex_logging.tbl_report_group_link (col_report_id, col_group_id) 
						SELECT	 '$col_id' AS `col_report_id`
						  	 ,ug.col_id AS `col_group_id`
						  FROM tbl_user_groups ug
						  	WHERE ug.col_group_name = '$group';";
				$stmt = $mysqli->stmt_init();
				if(!$stmt->prepare($query))
				{
					$mysqli->close();
					echo '[{"error":"Failed to prepare statement"}]';
					exit;
				}
				else
				{
					$result = $mysqli->query($query);
				}

			}
		}
		$stmt->close();
		
		// Now update the report information.
		
		$query = "UPDATE civex_logging.tbl_reports
				SET col_report_filename = '$col_report_filename'
		WHERE col_id = '$col_id';";

		$stmt = $mysqli->stmt_init();
		if(!$stmt->prepare($query))
		{
			$mysqli->close();
			echo '[{"error":"Failed to update report information: ' . $stmt->error . '"},{"query": "' . $query . '"}]';
			exit;
		}
		else
		{
			if ($result = $mysqli->query($query))
			{
				//echo '["message": "Deleted former groups successfully."]';
			}
			else
			{
				echo "[]";
				$stmt->close();
				$mysqli->close();
				exit;

			}
		}
		$stmt->close();
		
		$mysqli->close();
		echo "[]";
		exit;
?>
