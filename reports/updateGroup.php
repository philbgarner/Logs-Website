<?php
		// Delete former group associations for this user
		// and insert the updated list, evaluating each group name
		// one at a ttime.
		
		$col_id = $mysqli->real_escape_string($_GET['col_id']);
		$col_group_name = $mysqli->real_escape_string($_GET['col_group_name']);

		$query = "UPDATE civex_logging.tbl_user_groups SET col_group_name = '$col_group_name' WHERE col_id = '$col_id' AND col_id <> 1;"; // col_group_id = 1 is the Owner group.
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
		$stmt->close();
		
		$mysqli->close();
		echo "[]";
		exit;
?>
