<?php
	// Delete former group associations for this user
	// and insert the updated list, evaluating each group name
	// one at a ttime.
	
	$col_group_name = $mysqli->real_escape_string($_GET['col_group_name']);

	$query = "INSERT INTO tbl_user_groups
			(
					  `col_group_name`
			)
			VALUES
			('$col_group_name');";
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

	$stmt->close();
	
	$mysqli->close();
	echo "[]";
	exit(1);
?>
