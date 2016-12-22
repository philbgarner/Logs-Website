<?php
	// Delete former group associations for this user
	// and insert the updated list, evaluating each group name
	// one at a ttime.
	
	$col_group_name = $mysqli->real_escape_string($_GET['col_report_name']);

	$query = "INSERT INTO tbl_reports
			(
				`col_report_name`
				,`col_report_description`
				,`col_report_filename`
				,`col_report_image_url`
				,`col_isVisible`
			)
			VALUES
			('$col_group_name', '$col_group_name', '', 'img/Head.png', 1);";
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
