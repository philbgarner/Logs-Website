-- Drop tables that we have insert test data statements for below (resets auto_increment).

DROP TABLE tbl_session_log;
DROP TABLE tbl_command_log;
DROP TABLE tbl_entity_spawn_log;
DROP TABLE tbl_user_groups;
DROP TABLE tbl_user_group_link;
DROP TABLE tbl_reports;
DROP TABLE tbl_report_group_link;
DROP TABLE tbl_report_history;

-- End Portal Table

CREATE TABLE IF NOT EXISTS tbl_end_portals (
	col_id int(11) NOT NULL AUTO_INCREMENT,
	col_created_timestamp timestamp NULL DEFAULT NULL,
	col_created_by varchar(255) NOT NULL,
	col_destroyed_timestamp timestamp NULL DEFAULT NULL,
	col_destroyed_by varchar(255) DEFAULT NULL,
	col_citadel_group varchar(255) DEFAULT NULL,
	col_world varchar(255) NOT NULL,
	col_block_x int(11) NOT NULL,
	col_block_y int(11) NOT NULL,
	col_block_z int(11) NOT NULL,
PRIMARY KEY (col_id),
KEY col_created_by (col_created_by),
KEY col_destroyed_by (col_destroyed_by),
KEY col_citadel_group (col_citadel_group),
KEY col_world (col_world),
KEY col_block_x (col_block_x),
KEY col_block_y (col_block_y),
KEY col_block_z (col_block_z));

-- Report History Table

CREATE TABLE IF NOT EXISTS `tbl_report_history` (
	`col_id` int(11) NOT NULL AUTO_INCREMENT,
	`col_report_id` varchar(255) NOT NULL DEFAULT '',
	`col_report_name` varchar(255) NOT NULL DEFAULT '',
	`col_parameters` varchar(1000) NOT NULL DEFAULT '',
	`col_user_oauth_id` varchar(255) NOT NULL DEFAULT '',
	`col_user_name` varchar(255) NOT NULL DEFAULT '',
	`col_createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
	PRIMARY KEY (`col_id`),
	KEY `col_user_oauth_id` (`col_user_oauth_id`),
	KEY `col_report_id` (`col_report_id`));

  
-- User Group Link Table

CREATE TABLE IF NOT EXISTS `tbl_report_group_link` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                  `col_report_id` int NOT NULL, 
                  `col_group_id` int NOT NULL, 
                  `col_link_createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_id` (`col_id`),
                  KEY `col_report_id` (`col_report_id`),
                  KEY `col_group_id` (`col_group_id`));

-- User Groups Table

CREATE TABLE IF NOT EXISTS `tbl_user_groups` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                  `col_group_name` varchar(32) NOT NULL, 
                  `col_group_createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_group_createdon` (`col_group_createdon`),
                  KEY `col_group_name` (`col_group_name`),
                  UNIQUE (`col_group_name`));

-- Reports Table

CREATE TABLE IF NOT EXISTS `tbl_reports` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                 `col_report_name` varchar(32) NOT NULL, 
                 `col_report_description` varchar(255) NOT NULL,
                 `col_report_filename` varchar(255) NOT NULL,
                 `col_report_image_url` varchar(255) NOT NULL,
                  `col_createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  `col_isVisible` tinyint(1) DEFAULT 0,
                  PRIMARY KEY (`col_id`), 
                  KEY `col_createdon` (`col_createdon`),
                  KEY `col_report_name` (`col_report_name`));

-- User Group Link Table

CREATE TABLE IF NOT EXISTS `tbl_user_group_link` ( 
                  `col_id` int(11) NOT NULL AUTO_INCREMENT, 
                  `col_user_oauth_id` varchar(32) NOT NULL, 
                  `col_group_id` int NOT NULL, 
                  `col_link_createdon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                  PRIMARY KEY (`col_id`), 
                  KEY `col_user_oauth_id` (`col_user_oauth_id`),
                  KEY `col_group_id` (`col_group_id`));

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

CREATE TABLE IF NOT EXISTS `tbl_chat_log` (
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


-- Refresh test data in session table.

INSERT INTO `tbl_session_log`
(
	col_id
	,col_player_uuid
	,col_player_name
	,col_ip
	,col_login
	,col_logout
)
VALUES
(
	0
	,'ffd78cb6-4467-4d95-af52-4bb66386a607'
	,'ryan00793'
	,'127.0.0.2'
	,'2016-11-24 03:35:19'
	,'2016-11-24 05:05:09'
)
,(
	1
	,'ffd78cb6-4467-4d95-af52-4bbd434h2344'
	,'da3da1u5'
	,'127.0.0.1'
	,'2016-11-24 02:25:19'
	,'2016-11-24 06:05:09'
);

-- Refresh test data in command log

INSERT INTO `tbl_command_log`
(
	col_id
	,col_timestamp
	,col_player
	,col_command
	,col_arguments
	,col_cancelled
)
VALUES 
(0, '2016-12-01 03:09:33', 'da3da1u5', 'ctcreate', 'test', 0)
,(1, '2016-12-01 04:54:03', 'da3da1u5', 'ctcreate', 'test_again', 1);


-- Refresh data in Entity log.
INSERT INTO `tbl_entity_spawn_log`
(
	col_entity_type
	,col_timestamp
	,col_jocky
	,col_x
	,col_y
	,col_z
)
VALUES
('ZOMBIE', '2016-12-01 06:32:01', 0, 25.5656, 15.1234, 8.5456)
,('SKELETON', '2016-12-01 06:32:01', 1, 25.3434, 334.4341, 8.1235);

-- Create default user groups.

INSERT INTO `tbl_user_groups`
(
                  `col_group_name`
)
VALUES
('Owner'), ('Admin'), ('User');

-- Create test data, oauth ids pre-added

INSERT INTO `tbl_user_group_link` ( 
          `col_user_oauth_id`
          ,`col_group_id`)
VALUES ('110636005117259337708', 2), ('106974236239021394922', 3), ('106974236239021394922', 2), ('117111221811058560552', 1);

-- Create Detault Report Definitions

INSERT INTO `tbl_reports` (`col_report_name`, `col_report_description`, `col_report_filename`, `col_report_image_url`, `col_isVisible`) VALUES 
	('Blocks / Reinforcements', 'Reinforcement logs.', 'reports/blocks.php', 'img/Diamond_Ore.png', 1)
	,('Chat', 'Chat history.', 'reports/chat.php', 'img/VillagerHead.png', 1)
	,('Sessions', 'Sessions logs.', 'reports/session.php', 'img/Jukebox.png', 1)
	,('Commands', 'Commands logs.', 'reports/commands.php', 'img/Command_Block.png', 1)
	,('Entities', 'Entity logs.', 'reports/entities.php', 'img/Skeleton_Skull.png', 1)
	,('Users', 'User Management.', 'reports/users.php', 'img/Head.png', 1)
	,('Report Management', 'Defining reports and groups.', 'reports/reportManagement.php', 'img/Head.png', 1)
	,('Group Management', 'Creating and editing groups.', 'reports/groupList.php', 'img/Head.png', 1);

	
-- Create Default Setup for Reports
	
INSERT INTO `tbl_report_group_link` (`col_report_id` , `col_group_id`) VALUES 
	 (1, 1)
	,(2, 1)
	,(3, 1)
	,(4, 1)
	,(5, 1)
	,(6, 1)
	,(7, 1)
	,(8, 1)

	,(1, 3)
	,(2, 3)
	,(3, 3)
	,(4, 3)
	,(5, 3)
	,(6, 2)
	,(7, 2)
	,(8, 2);
	

