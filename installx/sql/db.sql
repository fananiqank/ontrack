-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2018 at 04:42 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ontrackt`
--

-- --------------------------------------------------------

--
-- Table structure for table `assetcategories`
--

CREATE TABLE IF NOT EXISTS `assetcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assetcategories`
--

INSERT INTO `assetcategories` (`id`, `name`, `color`) VALUES
(1, 'Desktops', '#1e3fda'),
(2, 'Laptops', '#058e29'),
(3, 'Servers', '#ff0000'),
(4, 'Printers', '#99ac14'),
(5, 'Routers', '#0b7c36');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `manufacturerid` int(11) NOT NULL,
  `modelid` int(11) NOT NULL,
  `supplierid` int(11) NOT NULL,
  `statusid` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty_months` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `serial` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `locationid` int(11) NOT NULL,
  `customfields` text NOT NULL,
  `qrvalue` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `categoryid`, `adminid`, `clientid`, `userid`, `manufacturerid`, `modelid`, `supplierid`, `statusid`, `purchase_date`, `warranty_months`, `tag`, `name`, `serial`, `notes`, `locationid`, `customfields`, `qrvalue`) VALUES
(1, 1, 0, 1, 0, 0, 4, 1, 3, '2016-02-01', 24, 'IT-1', 'Desktop 1', 'QWERT12345', '', 0, '', ''),
(2, 3, 0, 2, 0, 0, 3, 1, 3, '2016-02-01', 24, 'IT-2', 'DC Server', 'ASDFG12345', '', 0, '', ''),
(3, 2, 0, 2, 0, 0, 1, 3, 3, '2016-02-01', 24, 'IT-3', 'Laptop 1', 'BNMHJK98765', '', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `asset_tag_prefix` varchar(255) NOT NULL,
  `license_tag_prefix` varchar(255) NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `asset_tag_prefix`, `license_tag_prefix`, `notes`) VALUES
(1, 'Client Inc.', 'IT-', 'ITL-', ''),
(2, 'Client 2 Inc.', 'IT-', 'ITL-', '');

-- --------------------------------------------------------

--
-- Table structure for table `clients_admins`
--

CREATE TABLE IF NOT EXISTS `clients_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients_admins`
--

INSERT INTO `clients_admins` (`id`, `adminid`, `clientid`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peopleid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `comment` longtext CHARACTER SET utf8mb4 NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`name`, `value`) VALUES
('email_from_address', 'ontrack@example.com'),
('email_from_name', 'OnTrack'),
('email_smtp_enable', 'false'),
('email_smtp_host', ''),
('email_smtp_port', ''),
('email_smtp_username', ''),
('email_smtp_password', ''),
('email_smtp_security', ''),
('email_smtp_domain', ''),
('email_smtp_auth', 'false'),
('week_start', '1'),
('log_retention', '365'),
('tickets_encrypton', ''),
('tickets_password', ''),
('tickets_username', ''),
('tickets_server', ''),
('license_tag_prefix', 'ITL-'),
('asset_tag_prefix', 'IT-'),
('company_details', ''),
('company_name', 'onTrack Company'),
('tickets_notification', 'false'),
('sms_provider', 'clickatell'),
('sms_user', ''),
('sms_password', ''),
('sms_api_id', ''),
('sms_from', ''),
('app_name', '<b>on</b>Track'),
('app_url', ''),
('table_records', '50'),
('db_version', '1.16'),
('project_tag_prefix', 'P-'),
('password_generator_length', '8'),
('default_lang', 'en'),
('auto_close_tickets', '0'),
('timezone', 'UTC'),
('auto_close_tickets_notify', 'false'),
('tickets_defaultdepartment', '0'),
('date_format', 'Y-m-d;yyyy-mm-dd'),
('tickets_publicform', 'false'),
('recaptcha', 'false'),
('recaptcha_sitekey', ''),
('recaptcha_secretkey', ''),
('tickets_publictext', ''),
('label_width', '70'),
('label_height', '60'),
('label_qrsize', '1.3'),
('ldap_enable', 'false'),
('ldap_host', ''),
('ldap_port', '389'),
('ldap_dn', ''),
('manual_qrvalue', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `address` text NOT NULL,
  `webaddress` text NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE IF NOT EXISTS `credentials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emaillog`
--

CREATE TABLE IF NOT EXISTS `emaillog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peopleid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `to` varchar(128) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `ticketreplyid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE IF NOT EXISTS `hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hosts`
--

INSERT INTO `hosts` (`id`, `clientid`, `name`, `address`, `status`) VALUES
(1, 1, 'Google', 'www.google.com', ''),
(2, 2, 'DC Server', '10.0.0.25', ''),
(3, 2, 'Router', '10.0.0.1', '');

-- --------------------------------------------------------

--
-- Table structure for table `hosts_checks`
--

CREATE TABLE IF NOT EXISTS `hosts_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(60) NOT NULL,
  `port` varchar(60) NOT NULL,
  `monitoring` int(1) NOT NULL,
  `email` int(1) NOT NULL,
  `sms` int(1) NOT NULL,
  `status` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hosts_checks`
--

INSERT INTO `hosts_checks` (`id`, `hostid`, `name`, `type`, `port`, `monitoring`, `email`, `sms`, `status`) VALUES
(1, 1, 'HTTP', 'Service', '80', 1, 1, 1, ''),
(2, 3, 'HTTP admin', 'Service', '80', 1, 1, 0, ''),
(3, 2, 'MySQL Database', 'Service', '3306', 1, 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `hosts_history`
--

CREATE TABLE IF NOT EXISTS `hosts_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `checkid` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `latency` varchar(10) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hosts_people`
--

CREATE TABLE IF NOT EXISTS `hosts_people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostid` int(11) NOT NULL,
  `peopleid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `milestoneid` int(11) NOT NULL,
  `issuetype` varchar(15) NOT NULL,
  `priority` varchar(60) NOT NULL,
  `status` varchar(60) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 NOT NULL,
  `duedate` varchar(20) NOT NULL,
  `timespent` int(10) NOT NULL,
  `dateadded` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`id`, `clientid`, `assetid`, `projectid`, `adminid`, `milestoneid`, `issuetype`, `priority`, `status`, `name`, `description`, `duedate`, `timespent`, `dateadded`) VALUES
(1, 2, 2, 0, 0, 0, 'Task', 'High', 'To Do', 'Configure Active Directory', '', '', 180, '2016-02-03 00:00:00'),
(2, 2, 2, 0, 0, 0, 'Task', 'Low', 'In Progress', 'Reconfigure DNS server', '', '2016-03-27', 25, '2016-02-01 00:00:00'),
(3, 1, 1, 0, 0, 0, 'Task', 'Normal', 'Done', 'Install Office Suite', '', '2016-08-03', 45, '2016-02-03 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `kb_articles`
--

CREATE TABLE IF NOT EXISTS `kb_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) NOT NULL,
  `clients` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kb_articles`
--

INSERT INTO `kb_articles` (`id`, `categoryid`, `clients`, `name`, `content`) VALUES
(1, 1, 'a:1:{i:0;s:1:"0";}', 'Test Article', '<p>Article body.<br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `kb_categories`
--

CREATE TABLE IF NOT EXISTS `kb_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients` text NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kb_categories`
--

INSERT INTO `kb_categories` (`id`, `clients`, `name`) VALUES
(1, 'a:1:{i:0;s:1:"0";}', 'Test Category');

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `labels`
--

INSERT INTO `labels` (`id`, `name`, `color`) VALUES
(1, 'Requested', '#1ecbbd'),
(2, 'Pending', '#1ccd2b'),
(3, 'Deployed', '#3479da'),
(4, 'Archived', '#959d1c'),
(5, 'In Repair', '#da2727'),
(6, 'Broken', '#776e6e');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`) VALUES
(1, 'en', 'English (System)');

-- --------------------------------------------------------

--
-- Table structure for table `licensecategories`
--

CREATE TABLE IF NOT EXISTS `licensecategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `licensecategories`
--

INSERT INTO `licensecategories` (`id`, `name`, `color`) VALUES
(1, 'Operating Systems', '#355ea7'),
(2, 'Office Suite', '#e4d811'),
(3, 'Graphics Editor', '#c62121'),
(4, 'Other', '#370b0b');

-- --------------------------------------------------------

--
-- Table structure for table `licenses`
--

CREATE TABLE IF NOT EXISTS `licenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `statusid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `supplierid` int(11) NOT NULL,
  `seats` varchar(5) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `serial` text NOT NULL,
  `notes` text NOT NULL,
  `customfields` text NOT NULL,
  `qrvalue` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `licenses`
--

INSERT INTO `licenses` (`id`, `clientid`, `statusid`, `categoryid`, `supplierid`, `seats`, `tag`, `name`, `serial`, `notes`, `customfields`, `qrvalue`) VALUES
(1, 1, 3, 1, 1, '1', 'ITL-1', 'Windows 10 Pro', '', '', '', ''),
(2, 1, 3, 1, 0, '5', 'ITL-2', 'Office Home & Business 2016', '', '', '', ''),
(3, 2, 3, 1, 3, '1', 'ITL-3', 'Windows Server 2012 R2 Essentials', '', '', '', ''),
(4, 2, 3, 3, 1, '', 'ITL-4', 'Corel Draw x5', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `licenses_assets`
--

CREATE TABLE IF NOT EXISTS `licenses_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licenseid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `licenses_assets`
--

INSERT INTO `licenses_assets` (`id`, `licenseid`, `assetid`) VALUES
(1, 3, 1),
(2, 2, 1),
(3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `clientid`, `name`) VALUES
(1, 1, 'Test Location');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE IF NOT EXISTS `manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `name`) VALUES
(1, 'Apple'),
(2, 'Dell'),
(3, 'Microsoft'),
(4, 'HP'),
(5, 'Samsung'),
(6, 'ASUS'),
(7, 'Canon'),
(8, 'Cisco'),
(9, 'Lenovo'),
(10, 'Acer'),
(11, 'Epson');

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE IF NOT EXISTS `milestones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `duedate` varchar(20) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `name`) VALUES
(1, 'MacBook Pro'),
(2, 'MacBook Air'),
(3, 'PowerEdge R220'),
(4, 'Optiplex 3020 MT');

-- --------------------------------------------------------

--
-- Table structure for table `notificationtemplates`
--

CREATE TABLE IF NOT EXISTS `notificationtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 NOT NULL,
  `info` text NOT NULL,
  `sms` varchar(254) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notificationtemplates`
--

INSERT INTO `notificationtemplates` (`id`, `name`, `subject`, `message`, `info`, `sms`) VALUES
(1, 'New Ticket', 'Ticket #{ticketid} created', '<p>Hello {contact},<br><br>A new ticket has been created for your request.<br>Ticket ID:<b> #{ticketid}</b><br><br>{message}<br><br>You can reply to this email to add additional information.<br>Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>{company}</p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', ''),
(2, 'New Ticket Reply', '#{ticketid} New Reply', '<p>Hello {contact},<br><br>A new reply has been added to your ticket.<br><br>Ticket ID: #{ticketid}<br><br>{message}<br><br>You can reply to this email to add additional information.<br>Please do not remove the ticket number from the subject line.<br><br>Best regards,<br>{company}<br></p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', ''),
(3, 'New User', 'New User', '<p>Hello {contact},<br><br>Your account has been successfully created.</p><p><br>Email Address: {email}<br>Password: {password}<br>{appurl}<br><br>Best regards,<br>{company}<br></p>', '{contact}, {email}, {password}, {company}, {appurl}', ''),
(5, 'Password Reset', 'Password Reset', '<p>Hello {contact},<br><br>Please follow the link below to reset your password.<br>{resetlink}<br><br>Best regards,<br>{company}<br></p>', '{contact}, {resetlink}, {company}, {appurl}', ''),
(6, 'Monitoring Notification', '{hostinfo} is now {status}', '<p>{hostinfo} status has changed to {status}.<br><br>Best regards,<br>{company}<br></p>', '{hostinfo}, {status}, {contact}, {company}', '{hostinfo} is now {status}'),
(7, 'New Ticket (Admin)', 'New Support Ticket #{ticketid}', '<p>A new support ticket has been opened.</p>\r\n<p>Ticket ID:<b> #{ticketid}</b><br>Subject: {subject}</p>\r\n<p><br>{message}</p><br>\r\n<p>Best regards,<br>{company}</p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', ''),
(8, 'New Ticket Reply (Admin)', 'New Reply to Ticket #{ticketid}', '<p>A new reply has been added to ticket #{ticketid}.<br>Subject: {subject}<br></p><p><br>{message}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', ''),
(9, 'Ticket Escalation (Admin)', 'Escalation Rule Processed #{ticketid}', '<p>Escalation rule processed for ticket #{ticketid}.<br>Subject: {subject}<br></p><p><br>{message}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', ''),
(10, 'Ticket Auto Close (User)', 'Support Ticket #{ticketid} Auto Closed', '<p>This is a notification to let you know that we are changing the status of your ticket #{ticketid}  to Closed as we have not received a response from you lately.<br></p><p><br>Ticket Subject: {subject}<br></p><p><br>Best regards,<br>{company}<br></p><p><br></p><p></p>', '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}', '');

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `roleid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `ldap_user` VARCHAR(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `theme` varchar(64) NOT NULL,
  `sidebar` varchar(64) NOT NULL,
  `layout` varchar(64) NOT NULL,
  `notes` text NOT NULL,
  `signature` text NOT NULL,
  `sessionid` varchar(255) NOT NULL,
  `resetkey` varchar(255) NOT NULL,
  `autorefresh` int(11) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `ticketsnotification` int(1) NOT NULL,
  `avatar` mediumblob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 NOT NULL,
  `description` text NOT NULL,
  `startdate` varchar(20) NOT NULL,
  `deadline` varchar(20) NOT NULL,
  `progress` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `clientid`, `tag`, `name`, `notes`, `description`, `startdate`, `deadline`, `progress`) VALUES
(1, 1, 'P-1', 'Test Project', '<p></p>', '', '', '', 70);

-- --------------------------------------------------------

--
-- Table structure for table `projects_admins`
--

CREATE TABLE IF NOT EXISTS `projects_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects_admins`
--

INSERT INTO `projects_admins` (`id`, `projectid`, `adminid`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `perms` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `type`, `name`, `perms`) VALUES
(1, 'admin', 'Super Administrator', 'a:104:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:12:"addMilestone";i:26;s:13:"editMilestone";i:27;s:15:"deleteMilestone";i:28;s:16:"releaseMilestone";i:29;s:14:"viewMilestones";i:30;s:7:"addTime";i:31;s:8:"editTime";i:32;s:10:"deleteTime";i:33;s:8:"viewTime";i:34;s:9:"addTicket";i:35;s:10:"editTicket";i:36;s:12:"deleteTicket";i:37;s:12:"manageTicket";i:38;s:17:"manageTicketRules";i:39;s:17:"manageTicketNotes";i:40;s:22:"manageTicketAssignment";i:41;s:11:"viewTickets";i:42;s:8:"addIssue";i:43;s:9:"editIssue";i:44;s:11:"deleteIssue";i:45;s:11:"manageIssue";i:46;s:10:"viewIssues";i:47;s:10:"addComment";i:48;s:11:"editComment";i:49;s:13:"deleteComment";i:50;s:13:"assignComment";i:51;s:12:"viewComments";i:52;s:13:"addCredential";i:53;s:14:"editCredential";i:54;s:16:"deleteCredential";i:55;s:14:"viewCredential";i:56;s:15:"viewCredentials";i:57;s:5:"addKB";i:58;s:6:"editKB";i:59;s:8:"deleteKB";i:60;s:6:"viewKB";i:61;s:9:"addPReply";i:62;s:10:"editPReply";i:63;s:12:"deletePReply";i:64;s:12:"viewPReplies";i:65;s:10:"uploadFile";i:66;s:12:"downloadFile";i:67;s:10:"deleteFile";i:68;s:9:"viewFiles";i:69;s:7:"addHost";i:70;s:8:"editHost";i:71;s:10:"deleteHost";i:72;s:10:"manageHost";i:73;s:14:"viewMonitoring";i:74;s:7:"addUser";i:75;s:8:"editUser";i:76;s:10:"deleteUser";i:77;s:9:"viewUsers";i:78;s:8:"addStaff";i:79;s:9:"editStaff";i:80;s:11:"deleteStaff";i:81;s:9:"viewStaff";i:82;s:7:"addRole";i:83;s:8:"editRole";i:84;s:10:"deleteRole";i:85;s:9:"viewRoles";i:86;s:10:"addContact";i:87;s:11:"editContact";i:88;s:13:"deleteContact";i:89;s:12:"viewContacts";i:90;s:14:"addCustomField";i:91;s:15:"editCustomField";i:92;s:17:"deleteCustomField";i:93;s:16:"viewCustomFields";i:94;s:10:"manageData";i:95;s:14:"manageSettings";i:96;s:13:"manageApiKeys";i:97;s:8:"viewLogs";i:98;s:10:"viewSystem";i:99;s:10:"viewPeople";i:100;s:11:"viewReports";i:101;s:11:"autorefresh";i:102;s:6:"search";i:103;s:4:"Null";}'),
(2, 'user', 'Standard User', 'a:19:{i:0;s:11:"manageAsset";i:1;s:10:"viewAssets";i:2;s:13:"manageLicense";i:3;s:12:"viewLicenses";i:4;s:12:"viewProjects";i:5;s:9:"addTicket";i:6;s:10:"editTicket";i:7;s:12:"manageTicket";i:8;s:11:"viewTickets";i:9;s:8:"addIssue";i:10;s:10:"viewIssues";i:11;s:10:"addComment";i:12;s:12:"viewComments";i:13;s:6:"viewKB";i:14;s:14:"viewMonitoring";i:15;s:9:"viewUsers";i:16;s:10:"viewPeople";i:17;s:11:"viewReports";i:18;s:4:"Null";}'),
(3, 'admin', 'Administrator', 'a:70:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"manageClient";i:3;s:12:"adminsClient";i:4;s:11:"viewClients";i:5;s:8:"addAsset";i:6;s:9:"editAsset";i:7;s:11:"manageAsset";i:8;s:12:"licenseAsset";i:9;s:10:"viewAssets";i:10;s:10:"addLicense";i:11;s:11:"editLicense";i:12;s:13:"manageLicense";i:13;s:12:"assetLicense";i:14;s:12:"viewLicenses";i:15;s:10:"addProject";i:16;s:11:"editProject";i:17;s:13:"manageProject";i:18;s:18:"manageProjectNotes";i:19;s:13:"adminsProject";i:20;s:12:"viewProjects";i:21;s:9:"addTicket";i:22;s:10:"editTicket";i:23;s:12:"manageTicket";i:24;s:17:"manageTicketRules";i:25;s:17:"manageTicketNotes";i:26;s:11:"viewTickets";i:27;s:8:"addIssue";i:28;s:9:"editIssue";i:29;s:11:"manageIssue";i:30;s:10:"viewIssues";i:31;s:10:"addComment";i:32;s:11:"editComment";i:33;s:13:"assignComment";i:34;s:12:"viewComments";i:35;s:13:"addCredential";i:36;s:14:"editCredential";i:37;s:14:"viewCredential";i:38;s:15:"viewCredentials";i:39;s:5:"addKB";i:40;s:6:"viewKB";i:41;s:9:"addPReply";i:42;s:12:"viewPReplies";i:43;s:10:"uploadFile";i:44;s:12:"downloadFile";i:45;s:9:"viewFiles";i:46;s:7:"addHost";i:47;s:8:"editHost";i:48;s:10:"manageHost";i:49;s:14:"viewMonitoring";i:50;s:7:"addUser";i:51;s:8:"editUser";i:52;s:9:"viewUsers";i:53;s:8:"addStaff";i:54;s:9:"editStaff";i:55;s:9:"viewStaff";i:56;s:7:"addRole";i:57;s:8:"editRole";i:58;s:9:"viewRoles";i:59;s:10:"addContact";i:60;s:11:"editContact";i:61;s:12:"viewContacts";i:62;s:10:"manageData";i:63;s:8:"viewLogs";i:64;s:10:"viewSystem";i:65;s:10:"viewPeople";i:66;s:11:"viewReports";i:67;s:11:"autorefresh";i:68;s:6:"search";i:69;s:4:"Null";}'),
(4, 'admin', 'Technician', 'a:48:{i:0;s:9:"addClient";i:1;s:12:"manageClient";i:2;s:11:"viewClients";i:3;s:8:"addAsset";i:4;s:11:"manageAsset";i:5;s:10:"viewAssets";i:6;s:10:"addLicense";i:7;s:13:"manageLicense";i:8;s:12:"viewLicenses";i:9;s:10:"addProject";i:10;s:13:"manageProject";i:11;s:18:"manageProjectNotes";i:12;s:12:"viewProjects";i:13;s:9:"addTicket";i:14;s:12:"manageTicket";i:15;s:17:"manageTicketRules";i:16;s:17:"manageTicketNotes";i:17;s:11:"viewTickets";i:18;s:8:"addIssue";i:19;s:11:"manageIssue";i:20;s:10:"viewIssues";i:21;s:10:"addComment";i:22;s:12:"viewComments";i:23;s:13:"addCredential";i:24;s:14:"viewCredential";i:25;s:15:"viewCredentials";i:26;s:5:"addKB";i:27;s:6:"viewKB";i:28;s:9:"addPReply";i:29;s:12:"viewPReplies";i:30;s:10:"uploadFile";i:31;s:12:"downloadFile";i:32;s:9:"viewFiles";i:33;s:7:"addHost";i:34;s:10:"manageHost";i:35;s:14:"viewMonitoring";i:36;s:7:"addUser";i:37;s:9:"viewUsers";i:38;s:10:"addContact";i:39;s:11:"editContact";i:40;s:12:"viewContacts";i:41;s:10:"manageData";i:42;s:10:"viewSystem";i:43;s:10:"viewPeople";i:44;s:11:"viewReports";i:45;s:11:"autorefresh";i:46;s:6:"search";i:47;s:4:"Null";}');

-- --------------------------------------------------------

--
-- Table structure for table `smslog`
--

CREATE TABLE IF NOT EXISTS `smslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peopleid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `mobile` varchar(128) NOT NULL,
  `sms` varchar(256) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `statuscodes`
--

CREATE TABLE IF NOT EXISTS `statuscodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statuscodes`
--

INSERT INTO `statuscodes` (`id`, `code`, `type`, `message`) VALUES
(48, 11, 'danger', 'Error! Cannot add item.'),
(49, 21, 'danger', 'Error! Cannot save item.'),
(50, 31, 'danger', 'Error! Cannot delete item.'),
(47, 30, 'success', 'Item has been deleted successfully!'),
(46, 20, 'success', 'Item has been saved successfully!'),
(45, 10, 'success', 'Item has been added successfully!'),
(51, 40, 'success', 'Settings updated successfully!'),
(52, 1200, 'danger', 'Authentication Failed!'),
(53, 1300, 'success', 'Please check your email for a password reset link.'),
(54, 1400, 'danger', 'Email address was not found.'),
(55, 1500, 'danger', 'Invalid reset key!'),
(56, 1600, 'success', 'Success. Please log in with your new password! '),
(57, 1, 'danger', 'Unauthorized Access');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contactname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `web` varchar(255) NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `contactname`, `phone`, `email`, `web`, `notes`) VALUES
(1, 'Amazon', '', '', '', '', '', ''),
(2, 'Best Buy', '', '', '', '', '', ''),
(3, 'Newegg', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `systemlog`
--

CREATE TABLE IF NOT EXISTS `systemlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `peopleid` int(11) NOT NULL,
  `ipaddress` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket` int(11) NOT NULL,
  `departmentid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `status` varchar(50) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `notes` longtext CHARACTER SET utf8mb4 NOT NULL,
  `ccs` varchar(255) NOT NULL,
  `timespent` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_departments`
--

CREATE TABLE IF NOT EXISTS `tickets_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets_departments`
--

INSERT INTO `tickets_departments` (`id`, `name`, `email`) VALUES
(1, 'Default Department', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets_pr`
--

CREATE TABLE IF NOT EXISTS `tickets_pr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets_pr`
--

INSERT INTO `tickets_pr` (`id`, `name`, `content`) VALUES
(1, 'Demo Predefined Reply', '<div><p>Predefined reply body.<br></p></div>');

-- --------------------------------------------------------

--
-- Table structure for table `tickets_replies`
--

CREATE TABLE IF NOT EXISTS `tickets_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `peopleid` int(11) NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_rules`
--

CREATE TABLE IF NOT EXISTS `tickets_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticketid` int(11) NOT NULL,
  `executed` int(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cond_status` varchar(255) NOT NULL,
  `cond_priority` varchar(255) NOT NULL,
  `cond_timeelapsed` varchar(20) NOT NULL,
  `cond_datetime` datetime NOT NULL,
  `act_status` varchar(255) NOT NULL,
  `act_priority` varchar(255) NOT NULL,
  `act_assignto` int(11) NOT NULL,
  `act_notifyadmins` int(1) NOT NULL,
  `act_addreply` int(1) NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timelog`
--

CREATE TABLE IF NOT EXISTS `timelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staffid` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `projectid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `issues` text NOT NULL,
  `tickets` text NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `timelog`
--

INSERT INTO `timelog` (`id`, `staffid`, `clientid`, `projectid`, `assetid`, `issues`, `tickets`, `description`, `date`, `start`, `end`) VALUES
(1, 0, 2, 0, 2, 'a:1:{i:0;s:1:"1";}', 'a:0:{}', 'Coverted from issue''s time spent during 1.11 version upgrade.', '2016-02-03', '00:00:00', '03:00:00'),
(2, 0, 2, 0, 2, 'a:1:{i:0;s:1:"2";}', 'a:0:{}', 'Coverted from issue''s time spent during 1.11 version upgrade.', '2016-02-01', '00:00:00', '00:25:00'),
(3, 0, 1, 0, 1, 'a:1:{i:0;s:1:"3";}', 'a:0:{}', 'Coverted from issue''s time spent during 1.11 version upgrade.', '2016-02-03', '00:00:00', '00:45:00');






--
-- Table structure for table `api_keys`
--
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL,
  `name` varchar(127) NOT NULL,
  `secretkey` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



--
-- Table structure for table `assets_customfields`
--

CREATE TABLE IF NOT EXISTS `assets_customfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



--
-- Table structure for table `licenses_customfields`
--

CREATE TABLE IF NOT EXISTS `licenses_customfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;








/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
