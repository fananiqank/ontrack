-- DATABASE UPGRADE FROM 1.10 to 1.11

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;




ALTER TABLE `issues` ADD `milestoneid` INT(11) NOT NULL AFTER `adminid`;
ALTER TABLE `tickets` ADD `projectid` INT(11) NOT NULL AFTER `assetid`;

ALTER TABLE `clients` ADD `notes` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `license_tag_prefix`;


ALTER TABLE `contacts` ADD `phone` VARCHAR(32) NOT NULL AFTER `email`, ADD `address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `phone`, ADD `webaddress` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `address`, ADD `notes` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER `webaddress`;

ALTER TABLE `tickets_departments` ADD `email` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name`;


INSERT INTO `config` (`name`, `value`) VALUES ('tickets_publicform', 'false');
INSERT INTO `config` (`name`, `value`) VALUES ('recaptcha', 'false');
INSERT INTO `config` (`name`, `value`) VALUES ('recaptcha_sitekey', '');
INSERT INTO `config` (`name`, `value`) VALUES ('recaptcha_secretkey', '');
INSERT INTO `config` (`name`, `value`) VALUES ('tickets_publictext', '');


UPDATE `roles` SET `perms` = 'a:99:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:12:"addMilestone";i:26;s:13:"editMilestone";i:27;s:15:"deleteMilestone";i:28;s:16:"releaseMilestone";i:29;s:14:"viewMilestones";i:30;s:7:"addTime";i:31;s:8:"editTime";i:32;s:10:"deleteTime";i:33;s:8:"viewTime";i:34;s:9:"addTicket";i:35;s:10:"editTicket";i:36;s:12:"deleteTicket";i:37;s:12:"manageTicket";i:38;s:17:"manageTicketRules";i:39;s:17:"manageTicketNotes";i:40;s:22:"manageTicketAssignment";i:41;s:11:"viewTickets";i:42;s:8:"addIssue";i:43;s:9:"editIssue";i:44;s:11:"deleteIssue";i:45;s:11:"manageIssue";i:46;s:10:"viewIssues";i:47;s:10:"addComment";i:48;s:11:"editComment";i:49;s:13:"deleteComment";i:50;s:13:"assignComment";i:51;s:12:"viewComments";i:52;s:13:"addCredential";i:53;s:14:"editCredential";i:54;s:16:"deleteCredential";i:55;s:14:"viewCredential";i:56;s:15:"viewCredentials";i:57;s:5:"addKB";i:58;s:6:"editKB";i:59;s:8:"deleteKB";i:60;s:6:"viewKB";i:61;s:9:"addPReply";i:62;s:10:"editPReply";i:63;s:12:"deletePReply";i:64;s:12:"viewPReplies";i:65;s:10:"uploadFile";i:66;s:12:"downloadFile";i:67;s:10:"deleteFile";i:68;s:9:"viewFiles";i:69;s:7:"addHost";i:70;s:8:"editHost";i:71;s:10:"deleteHost";i:72;s:10:"manageHost";i:73;s:14:"viewMonitoring";i:74;s:7:"addUser";i:75;s:8:"editUser";i:76;s:10:"deleteUser";i:77;s:9:"viewUsers";i:78;s:8:"addStaff";i:79;s:9:"editStaff";i:80;s:11:"deleteStaff";i:81;s:9:"viewStaff";i:82;s:7:"addRole";i:83;s:8:"editRole";i:84;s:10:"deleteRole";i:85;s:9:"viewRoles";i:86;s:10:"addContact";i:87;s:11:"editContact";i:88;s:13:"deleteContact";i:89;s:12:"viewContacts";i:90;s:10:"manageData";i:91;s:14:"manageSettings";i:92;s:8:"viewLogs";i:93;s:10:"viewSystem";i:94;s:10:"viewPeople";i:95;s:11:"viewReports";i:96;s:11:"autorefresh";i:97;s:6:"search";i:98;s:4:"Null";}' WHERE `roles`.`id` = 1;
