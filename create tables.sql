-- SessionTable

CREATE TABLE IF NOT EXISTS `tbl_session_log` ( 
                  `col_id` int(11) NOT NULL, 
                  `col_player_uuid` varchar(50) NOT NULL, 
                  `col_player_name` varchar(32) NOT NULL, 
                  `col_ip` varchar(15) NOT NULL, 
                  `col_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  `col_logout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_player_uuid` (`col_player_uuid`), 
                  KEY `col_player_name` (`col_player_name`), 
                  KEY `col_login` (`col_login`), 
                  KEY `col_logout` (`col_logout`), 
  KEY `col_ip` (`col_ip`));
  
 -- EntitySpawnTable
 
 CREATE TABLE IF NOT EXISTS `tbl_entity_spawn_log` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                  `col_entity_type` varchar(255) NOT NULL, 
                  `col_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  `col_jocky` tinyint(1) NOT NULL, 
                  `col_x` int(11) NOT NULL, 
                  `col_y` int(11) NOT NULL, 
                  `col_z` int(11) NOT NULL, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_timestamp` (`col_timestamp`), 
                  KEY `col_entity_type` (`col_entity_type`), 
  KEY `col_jocky` (`col_jocky`));

-- DeathTable

CREATE TABLE IF NOT EXISTS `tbl_death_log` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                  `col_timestamp` timestamp NULL DEFAULT NULL, 
                  `col_world` varchar(45) DEFAULT NULL, 
                  `col_cause` varchar(45) DEFAULT NULL, 
                  `col_victim_name` varchar(45) DEFAULT NULL, 
                  `col_victim_owner` varchar(45) DEFAULT NULL, 
                  `col_victim_left_hand` varchar(45) DEFAULT NULL, 
                  `col_victim_right_hand` varchar(45) DEFAULT NULL, 
                  `col_victim_x` double DEFAULT NULL, 
                  `col_victim_y` double DEFAULT NULL, 
                  `col_victim_z` double DEFAULT NULL, 
                  `col_victim_pitch` float DEFAULT NULL, 
                  `col_victim_yaw` float DEFAULT NULL, 
                  `col_victim_player` tinyint(1) DEFAULT NULL, 
                  `col_killer_name` varchar(45) DEFAULT NULL, 
                  `col_killer_owner` varchar(45) DEFAULT NULL, 
                  `col_killer_left_hand` varchar(45) DEFAULT NULL, 
                  `col_killer_right_hand` varchar(45) DEFAULT NULL, 
                  `col_killer_x` double DEFAULT NULL, 
                  `col_killer_y` double DEFAULT NULL, 
                  `col_killer_z` double DEFAULT NULL, 
                  `col_killer_pitch` float DEFAULT NULL, 
                  `col_killer_yaw` float DEFAULT NULL, 
                  `col_killer_player` tinyint(1) DEFAULT NULL, 
                  `col_xp` int(11) DEFAULT NULL, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_timestamp` (`col_timestamp`), 
                  KEY `col_world` (`col_world`), 
                  KEY `col_cause` (`col_cause`), 
                  KEY `col_victim_name` (`col_victim_name`), 
                  KEY `col_victim_x` (`col_victim_x`), 
                  KEY `col_vitcim_z` (`col_victim_z`), 
                  KEY `col_killer_name` (`col_killer_name`), 
                  KEY `col_killer_x` (`col_killer_x`), 
                  KEY `col_killer_z` (`col_killer_z`), 
                  KEY `col_victim_player` (`col_victim_player`), 
                  KEY `col_killer_player` (`col_killer_player`), 
                  KEY `col_victim_owner` (`col_victim_owner`), 
  KEY `col_killer_owner` (`col_killer_owner`));
  
-- CommandTable

CREATE TABLE IF NOT EXISTS `tbl_command_log` ( 
                  `col_id` int(13) NOT NULL AUTO_INCREMENT, 
                  `col_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  `col_player` varchar(255) NOT NULL, 
                  `col_command` varchar(255) NOT NULL, 
                  `col_arguments` varchar(255) NOT NULL, 
                  `col_cancelled` tinyint(1) NOT NULL, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_timestamp` (`col_timestamp`), 
                  KEY `col_player` (`col_player`), 
                  KEY `col_command` (`col_command`), 
                  KEY `col_argument` (`col_arguments`), 
  KEY `col_cancelled` (`col_cancelled`));

-- ChatTable

CREATE TABLE IF NOT EXISTS tbl_chat_log (
             `col_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
             `col_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             `col_channel` varchar(255) NOT NULL,
             `col_sender` varchar(16) NOT NULL,
             `col_receiver` varchar(16) NOT NULL,
             `col_message` varchar(255) NOT NULL,
 PRIMARY KEY (`col_id`));

-- BlockTable

CREATE TABLE IF NOT EXISTS `tbl_block_log` ( 
                `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                `col_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                `col_world` varchar(255) NOT NULL, 
                `col_action` varchar(255) NOT NULL, 
                `col_player` varchar(255) NOT NULL, 
                `col_item_held_right` int(11) NOT NULL, 
                `col_item_held_left` int(11) NOT NULL, 
                `col_player_x` double NOT NULL, 
                `col_player_y` double NOT NULL, 
                `col_player_z` double NOT NULL, 
                `col_player_pitch` float NOT NULL, 
                `col_player_yaw` float NOT NULL, 
                `col_block_id` int(11) NOT NULL, 
                `col_block_x` int(11) NOT NULL, 
                `col_block_y` int(11) NOT NULL, 
                `col_block_z` int(11) NOT NULL, 
                `col_content` varchar(1000) NOT NULL, 
                `col_reinforcement_health` varchar(255) NOT NULL, 
                PRIMARY KEY (`col_id`), 
                KEY `col_timestamp` (`col_timestamp`), 
                KEY `col_action` (`col_action`), 
                KEY `col_player` (`col_player`), 
                KEY `col_block_x` (`col_block_x`), 
                KEY `col_block_y` (`col_block_y`), 
                KEY `col_block_z` (`col_block_z`), 
                KEY `col_world` (`col_world`), 
                KEY `col_item_held_right` (`col_item_held_right`), 
                KEY `col_item_held_left` (`col_item_held_left`), 
KEY `col_cancelled` (`col_reinforcement_health`));
