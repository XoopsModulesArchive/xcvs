CREATE TABLE `cvs_documents` (
  `document_id` bigint(20) NOT NULL auto_increment,
  `document_name` longtext NOT NULL,
  `document_version` varchar(20) NOT NULL default '',
  `document_timestamp` longtext NOT NULL,
  `document_uploader` longtext NOT NULL,
  `document_size` bigint(20) NOT NULL default '0',
  `document_cat` longtext NOT NULL,
  `document_desc` longtext NOT NULL,
  `document_data` longblob NOT NULL,
  `document_out` char(1) NOT NULL default '',
  `document_out_with` longtext NOT NULL,
  `document_location` longtext NOT NULL,
  `document_project_id` int(11) NOT NULL default '0',
  `document_file_name` longtext NOT NULL,
  PRIMARY KEY  (`document_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE `cvs_history` (
  `document_id` bigint(20) NOT NULL default '0',
  `hist_action` varchar(50) NOT NULL default '',
  `hist_person` varchar(100) NOT NULL default '',
  `hist_timestamp` longtext NOT NULL
) TYPE=MyISAM;

CREATE TABLE `cvs_phases` (
  `phase` varchar(100) NOT NULL default ''
) TYPE=MyISAM;

INSERT INTO `cvs_phases` VALUES ('Domain Analysis');
INSERT INTO `cvs_phases` VALUES ('Requirments');
INSERT INTO `cvs_phases` VALUES ('Design');
INSERT INTO `cvs_phases` VALUES ('Implementation');
INSERT INTO `cvs_phases` VALUES ('Testing');
INSERT INTO `cvs_phases` VALUES ('Marketing');
INSERT INTO `cvs_phases` VALUES ('Maintenance');

CREATE TABLE `cvs_prev_versions` (
  `document_id` bigint(20) NOT NULL default '0',
  `document_version` longtext NOT NULL,
  `document_desc` longtext NOT NULL,
  `document_data` longblob NOT NULL,
  `document_size` bigint(20) NOT NULL default '0',
  `document_file_name` longtext NOT NULL
) TYPE=MyISAM;

CREATE TABLE `cvs_projects` (
  `project_id` int(11) NOT NULL auto_increment,
  `project_name` text NOT NULL,
  `project_short_desc` text NOT NULL,
  `project_desc` text NOT NULL,
  `project_dist_name` text NOT NULL,
  PRIMARY KEY  (`project_id`)
) TYPE=MyISAM; 

CREATE TABLE `cvs_temp` (
  `user_id` varchar(100) NOT NULL default '',
  `curr_project` varchar(100) NOT NULL default ''
) TYPE=MyISAM; 