-- DATABASE UPGRADE FROM 1.12 to 1.13


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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assets_customfields`
--

INSERT INTO `assets_customfields` (`id`, `type`, `name`, `description`, `options`) VALUES
(1, 'Text Box', 'Purchase Order Number', '', ''),
(2, 'Text Box', 'Value', '', ''),
(3, 'Text Box', 'Condition', '', ''),
(4, 'Text Box', 'Removal Date', '', '');


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




ALTER TABLE `assets` ADD `customfields` TEXT NOT NULL AFTER `locationid`;
ALTER TABLE `assets` ADD `qrvalue` TEXT NOT NULL AFTER `customfields`;



ALTER TABLE `licenses` ADD `customfields` TEXT NOT NULL AFTER `notes`;
ALTER TABLE `licenses` ADD `qrvalue` TEXT NOT NULL AFTER `customfields`;
ALTER TABLE `licenses` ADD `seats` VARCHAR(5) NOT NULL AFTER `supplierid`;


INSERT INTO `config` (`name`, `value`) VALUES ('label_width', '70');
INSERT INTO `config` (`name`, `value`) VALUES ('label_height', '60');
INSERT INTO `config` (`name`, `value`) VALUES ('label_qrsize', '1.3');

INSERT INTO `config` (`name`, `value`) VALUES ('ldap_enable', 'false');
INSERT INTO `config` (`name`, `value`) VALUES ('ldap_host', '');
INSERT INTO `config` (`name`, `value`) VALUES ('ldap_port', '389');
INSERT INTO `config` (`name`, `value`) VALUES ('ldap_dn', '');

INSERT INTO `config` (`name`, `value`) VALUES ('manual_qrvalue', 'false');


ALTER TABLE `people` ADD `ldap_user` VARCHAR(128) NOT NULL AFTER `email`;





UPDATE `roles` SET `perms` = 'a:104:{i:0;s:9:"addClient";i:1;s:10:"editClient";i:2;s:12:"deleteClient";i:3;s:12:"manageClient";i:4;s:12:"adminsClient";i:5;s:11:"viewClients";i:6;s:8:"addAsset";i:7;s:9:"editAsset";i:8;s:11:"deleteAsset";i:9;s:11:"manageAsset";i:10;s:12:"licenseAsset";i:11;s:10:"viewAssets";i:12;s:10:"addLicense";i:13;s:11:"editLicense";i:14;s:13:"deleteLicense";i:15;s:13:"manageLicense";i:16;s:12:"assetLicense";i:17;s:12:"viewLicenses";i:18;s:10:"addProject";i:19;s:11:"editProject";i:20;s:13:"deleteProject";i:21;s:13:"manageProject";i:22;s:18:"manageProjectNotes";i:23;s:13:"adminsProject";i:24;s:12:"viewProjects";i:25;s:12:"addMilestone";i:26;s:13:"editMilestone";i:27;s:15:"deleteMilestone";i:28;s:16:"releaseMilestone";i:29;s:14:"viewMilestones";i:30;s:7:"addTime";i:31;s:8:"editTime";i:32;s:10:"deleteTime";i:33;s:8:"viewTime";i:34;s:9:"addTicket";i:35;s:10:"editTicket";i:36;s:12:"deleteTicket";i:37;s:12:"manageTicket";i:38;s:17:"manageTicketRules";i:39;s:17:"manageTicketNotes";i:40;s:22:"manageTicketAssignment";i:41;s:11:"viewTickets";i:42;s:8:"addIssue";i:43;s:9:"editIssue";i:44;s:11:"deleteIssue";i:45;s:11:"manageIssue";i:46;s:10:"viewIssues";i:47;s:10:"addComment";i:48;s:11:"editComment";i:49;s:13:"deleteComment";i:50;s:13:"assignComment";i:51;s:12:"viewComments";i:52;s:13:"addCredential";i:53;s:14:"editCredential";i:54;s:16:"deleteCredential";i:55;s:14:"viewCredential";i:56;s:15:"viewCredentials";i:57;s:5:"addKB";i:58;s:6:"editKB";i:59;s:8:"deleteKB";i:60;s:6:"viewKB";i:61;s:9:"addPReply";i:62;s:10:"editPReply";i:63;s:12:"deletePReply";i:64;s:12:"viewPReplies";i:65;s:10:"uploadFile";i:66;s:12:"downloadFile";i:67;s:10:"deleteFile";i:68;s:9:"viewFiles";i:69;s:7:"addHost";i:70;s:8:"editHost";i:71;s:10:"deleteHost";i:72;s:10:"manageHost";i:73;s:14:"viewMonitoring";i:74;s:7:"addUser";i:75;s:8:"editUser";i:76;s:10:"deleteUser";i:77;s:9:"viewUsers";i:78;s:8:"addStaff";i:79;s:9:"editStaff";i:80;s:11:"deleteStaff";i:81;s:9:"viewStaff";i:82;s:7:"addRole";i:83;s:8:"editRole";i:84;s:10:"deleteRole";i:85;s:9:"viewRoles";i:86;s:10:"addContact";i:87;s:11:"editContact";i:88;s:13:"deleteContact";i:89;s:12:"viewContacts";i:90;s:14:"addCustomField";i:91;s:15:"editCustomField";i:92;s:17:"deleteCustomField";i:93;s:16:"viewCustomFields";i:94;s:10:"manageData";i:95;s:14:"manageSettings";i:96;s:13:"manageApiKeys";i:97;s:8:"viewLogs";i:98;s:10:"viewSystem";i:99;s:10:"viewPeople";i:100;s:11:"viewReports";i:101;s:11:"autorefresh";i:102;s:6:"search";i:103;s:4:"Null";}' WHERE `roles`.`id` = 1;
