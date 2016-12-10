<?php
		//$query = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
		$query = "SELECT
				'Id' AS col_id
				,'Timestamp' AS col_timestamp
				,'World' AS col_world
				,'Action' AS col_action
				,'Player Name' AS col_player
				,'Item Held Right' AS col_item_held_right
				,'Item Held Left' AS col_item_held_left
				,'PlayerX' AS col_player_x
				,'PlayerY' AS col_player_y
				,'PlayerZ' AS col_player_z
				,'Block Type' AS col_block_type
				,'BlockX' AS col_block_x
				,'BlockY' AS col_block_y
				,'BlockZ' AS col_block_z
				,'Content' AS col_content
				,'Reinforcement Health' AS col_reinforcement_health
				,'Group Name' AS col_group_name
			UNION SELECT 	col_id
					,col_timestamp
					,col_world
					,col_action
					,col_player
					,col_item_held_right
					,col_item_held_left
					,col_player_x
					,col_player_y
					,col_player_z
					,col_block_type
					,col_block_x
					,col_block_y
					,col_block_z
					,col_content
					,col_reinforcement_health
					,col_group_name
	 	FROM civex_logging.tbl_block_log ";

		// Only append WHERE if it's the first condition in the list of parameters.
		$joinWord = "WHERE";

		// Using $mysqli->real_escape_string instead of the canonical way to prepare statements
		// because it's really awkward to do with mysqli on dynamically generated queries.
		// Simply escaping like this should prevent injection attacks.
		
		if (isset($_GET['col_player']) && strlen($_GET['col_player']) > 0)
		{
			$query = $query . " $joinWord col_player LIKE '%" . $mysqli->real_escape_string($_GET['col_player']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_item_held_left']) && strlen($_GET['col_item_held_left']) > 0)
		{
			$query = $query . " $joinWord col_item_held_left LIKE '%" . $mysqli->real_escape_string($_GET['col_item_held_left']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_item_held_right']) && strlen($_GET['col_item_held_right']) > 0)
		{
			$query = $query . " $joinWord col_item_held_right LIKE '%" . $mysqli->real_escape_string($_GET['col_item_held_right']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_block_type']) && strlen($_GET['col_block_type']) > 0)
		{
			$query = $query . " $joinWord col_block_type LIKE '%" . $mysqli->real_escape_string($_GET['col_block_type']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_action']) && strlen($_GET['col_action']) > 0)
		{
			$query = $query . " $joinWord col_action LIKE '%" . $mysqli->real_escape_string($_GET['col_action']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_content']) && strlen($_GET['col_content']) > 0)
		{
			$query = $query . " $joinWord col_content LIKE '%" . $mysqli->real_escape_string($_GET['col_content']) . "%'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_from']) && strlen($_GET['col_timestamp_from']) > 0)
		{
			$query = $query . " $joinWord col_timestamp >= '" . $mysqli->real_escape_string($_GET['col_timestamp_from']) . "'	";
			$joinWord = "AND";
		}
		if (isset($_GET['col_timestamp_to']) && strlen($_GET['col_timestamp_to']) > 0)
		{
			$query = $query . " $joinWord col_timestamp <= '" . $mysqli->real_escape_string($_GET['col_timestamp_to']) . "'	";
			$joinWord = "AND";
		}

		$query = $query . ";";// COMMIT;";
?>
