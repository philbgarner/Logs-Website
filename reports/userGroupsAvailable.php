<?php
	// Simply outputs all of the possible group names.

	//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
	$query = "SELECT
			GROUP_CONCAT(DISTINCT ug.col_group_name) AS group_list
		FROM
			civex_logging.tbl_user_groups AS ug;
	 ";
	// COMMIT;";
		?>