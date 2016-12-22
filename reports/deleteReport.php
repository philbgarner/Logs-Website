<?php
	// Delete former group associations for this user
	// and insert the updated list, evaluating each group name
	// one at a ttime.
	
	$col_id = $mysqli->real_escape_string($_GET['col_id']);

	$query = "DELETE FROM tbl_reports WHERE col_id = $col_id;";
	$stmt = $mysqli->stmt_init();
	if(!$stmt->prepare($query))
	{
		$mysqli->close();
		echo '[{"error":"Failed to prepare statement."}]';
		exit;
	}
	else
	{
		$result = $mysqli->query($query);
	}

	$stmt->close();

	$query = "DELETE FROM tbl_report_group_link WHERE col_report_id = $col_id;";
	$stmt = $mysqli->stmt_init();
	if(!$stmt->prepare($query))
	{
		$mysqli->close();
		echo '[{"error":"Failed to prepare statement."}]';
		exit;
	}
	else
	{
		$result = $mysqli->query($query);
	}

	$stmt->close();
		
	$mysqli->close();
	echo "[]";
	exit(1);
?>
