<?php

	$query = "SELECT
			GROUP_CONCAT(DISTINCT ug.col_group_name) AS group_list
		FROM
			civex_logging.tbl_user_groups AS ug
	 ";

		?>
