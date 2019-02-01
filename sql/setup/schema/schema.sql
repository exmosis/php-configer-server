/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 5.0.81-community-nt : Database - gl_php_configer
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gl_php_configer` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `gl_php_configer`;

/*Table structure for table `config_output_files` */

CREATE TABLE `config_output_files` (
  `config_output_file_id` int(10) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) NOT NULL,
  `file_path` text NOT NULL,
  `file_type` enum('php','htaccess') NOT NULL,
  PRIMARY KEY  (`config_output_file_id`),
  UNIQUE KEY `file_name_file_path_unique` (`file_name`,`file_path`(128))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `config_vars` */

CREATE TABLE `config_vars` (
  `config_var_id` int(10) unsigned NOT NULL auto_increment,
  `config_section_name` varchar(255) default NULL,
  `config_output_file_id` int(10) unsigned NOT NULL,
  `config_var_name` varchar(255) NOT NULL,
  `add_quotes` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `allow_client_override` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`config_var_id`),
  UNIQUE KEY `file_id_var_name_unique` (`config_output_file_id`,`config_var_name`),
  CONSTRAINT `config_output_file_fk` FOREIGN KEY (`config_output_file_id`) REFERENCES `config_output_files` (`config_output_file_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `host_config_values` */

CREATE TABLE `host_config_values` (
  `host_config_value_id` int(10) unsigned NOT NULL auto_increment,
  `host_id` int(10) unsigned NOT NULL,
  `config_var_id` int(10) unsigned NOT NULL,
  `config_var_value` text,
  PRIMARY KEY  (`host_config_value_id`),
  UNIQUE KEY `host_id_config_value_unique` (`host_id`,`config_var_id`),
  KEY `host_id` (`host_id`),
  KEY `config_var_id_fk` (`config_var_id`),
  CONSTRAINT `host_id_fk` FOREIGN KEY (`host_id`) REFERENCES `hosts` (`host_id`) ON UPDATE CASCADE,
  CONSTRAINT `config_var_id_fk` FOREIGN KEY (`config_var_id`) REFERENCES `config_vars` (`config_var_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `hosts` */

CREATE TABLE `hosts` (
  `host_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `host_domain` varchar(255) NOT NULL,
  `host_token` varchar(255) NOT NULL,
  `parent_host_domain` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`host_id`),
  UNIQUE KEY `host_domain_unique` (`host_domain`),
  KEY `parent_host_id_fk` (`parent_host_domain`),
  CONSTRAINT `hosts_parent_host_domain_fk` FOREIGN KEY (`parent_host_domain`) REFERENCES `hosts` (`host_domain`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
