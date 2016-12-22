<?php
		// Delete this group and the associated group links
		
		$col_id = $mysqli->real_escape_string($_GET['col_id']);

		$query = "DELETE FROM civex_logging.tbl_user_groups WHERE col_id = '$col_id' AND col_id <> 1;"; // col_group_id = 1 is the Owner group.

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
		
		$query = "DELETE FROM civex_logging.tbl_user_group_link WHERE col_group_id = '$col_id';";

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

		$query = "DELETE FROM civex_logging.tbl_report_group_link WHERE col_group_id = '$col_id';";

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
