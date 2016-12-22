<?php

		$hRow['col_id'] = 'Id';
		$hRow['col_id'] = 'Id';
		$hRow['col_timestamp'] = 'Timestamp';
		$hRow['col_world'] = 'World';
		$hRow['col_action'] = 'Action';
		$hRow['col_player'] = 'Player';
		$hRow['col_item_held_right'] = 'Item Held Right';
		$hRow['col_item_held_left'] = 'Item Held Left';
		$hRow['col_player_x'] = 'PlayerX';
		$hRow['col_player_y'] = 'PlayerY';
		$hRow['col_player_z'] = 'PlayerZ';
		$hRow['col_block_type'] = 'Block Type';
		$hRow['col_block_x'] = 'BlockX';
		$hRow['col_block_y'] = 'BlockY';
		$hRow['col_block_z'] = 'BlockZ';
		$hRow['col_content'] = 'Content';
		$hRow['col_reinforcement_health'] = 'Reinforcement Health';
		$hRow['col_group_name'] = 'Group Name';

		
		$query = "SELECT 	col_id
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

?>
