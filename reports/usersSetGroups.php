<?php
		// Delete former group associations for this user
		// and insert the updated list, evaluating each group name
		// one at a ttime.
		
		$col_id = $mysqli->real_escape_string($_GET['col_user_oauth_id']);
		$group_list[] = explode(",", $mysqli->real_escape_string($_GET['col_group_names']));

		$query = "DELETE FROM civex_logging.tbl_user_group_link WHERE col_user_oauth_id = '$col_id' AND col_group_id <> 1;"; // col_group_id = 1 is the Owner group.
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
				
				$query = "INSERT INTO civex_logging.tbl_user_group_link (col_user_oauth_id, col_group_id) 
						SELECT	 '$col_id' AS `col_user_oauth_id`
						  	 ,ug.col_id AS `col_group_id`
						  FROM tbl_user_groups ug
						  	WHERE ug.col_group_name = '$group';";
				$stmt = $mysqli->stmt_init();
				if(!$stmt->prepare($query))
				{
					$mysqli->close();
					echo "[{\"error\":\"Failed to prepare statement\"}]";
					exit;
				}
				else
				{
					$result = $mysqli->query($query);
				}

			}
		}
		$stmt->close();
		
		$mysqli->close();
		echo "[]";
		exit;
?>
