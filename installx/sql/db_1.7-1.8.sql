-- DATABASE UPGRADE FROM 1.7 to 1.8


ALTER TABLE `tickets` ADD `timespent` INT(10) NOT NULL AFTER `ccs`;
INSERT INTO `config` (`name`, `value`) VALUES ('date_format', 'Y-m-d;yyyy-mm-dd');

UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 1;
UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 2;
UPDATE `notificationtemplates` SET `info` = '{contact}, {email}, {password}, {company}, {appurl}' WHERE `notificationtemplates`.`id` = 3;
UPDATE `notificationtemplates` SET `info` = '{contact}, {resetlink}, {company}, {appurl}' WHERE `notificationtemplates`.`id` = 5;
UPDATE `notificationtemplates` SET `info` = '{hostinfo}, {status}, {contact}, {company}, {appurl}' WHERE `notificationtemplates`.`id` = 6;
UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 7;
UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 8;
UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 9;
UPDATE `notificationtemplates` SET `info` = '{ticketid}, {status}, {subject}, {contact}, {message}, {company}, {appurl}, {client}, {department}' WHERE `notificationtemplates`.`id` = 10;
