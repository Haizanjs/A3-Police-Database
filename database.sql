/*
Navicat MySQL Data Transfer

Source Server         : Home
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : police

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-04-27 17:52:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `arrests`
-- ----------------------------
DROP TABLE IF EXISTS `arrests`;
CREATE TABLE `arrests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `copid` int(11) DEFAULT NULL,
  `docid` int(11) DEFAULT NULL,
  `dojid` int(11) DEFAULT '0',
  `date` date DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `bondid` int(11) DEFAULT NULL,
  `proc` int(1) DEFAULT NULL,
  `crimes` varchar(255) DEFAULT NULL,
  `bail` int(11) DEFAULT NULL,
  `exp` int(11) DEFAULT '0',
  `plea` tinyint(20) DEFAULT NULL,
  `evd` varchar(255) DEFAULT NULL,
  `RealDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8106 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of arrests
-- ----------------------------

-- ----------------------------
-- Table structure for `bolo`
-- ----------------------------
DROP TABLE IF EXISTS `bolo`;
CREATE TABLE `bolo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `copid` int(11) DEFAULT NULL,
  `canceled` int(11) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `RealDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=570 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bolo
-- ----------------------------

-- ----------------------------
-- Table structure for `bonds`
-- ----------------------------
DROP TABLE IF EXISTS `bonds`;
CREATE TABLE `bonds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `citid` int(11) DEFAULT NULL,
  `cdate` date DEFAULT NULL,
  `bondamnt` int(11) DEFAULT NULL,
  `resolved` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bonds
-- ----------------------------

-- ----------------------------
-- Table structure for `civs`
-- ----------------------------
DROP TABLE IF EXISTS `civs`;
CREATE TABLE `civs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(36) DEFAULT NULL,
  `scount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5540 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of civs
-- ----------------------------

-- ----------------------------
-- Table structure for `dept`
-- ----------------------------
DROP TABLE IF EXISTS `dept`;
CREATE TABLE `dept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dname` varchar(255) DEFAULT NULL,
  `ranks` varchar(200) DEFAULT NULL,
  `perms` varchar(255) DEFAULT NULL,
  `authority` int(11) DEFAULT NULL,
  `info` varchar(100) NOT NULL DEFAULT '{"repto":[-1]}',
  `section` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of dept
-- ----------------------------
INSERT INTO `dept` VALUES ('1', 'Patrol', '[\"Cadet\",\"Officer\",\"Corporal\",\"Sergeant\", \"Lieutenant\", \"Captain\"]', '[\"officer\"]', '3', '{\"reporters\":[9],\"cmdrank\":4}', '0');
INSERT INTO `dept` VALUES ('2', 'Staff', '[\"Admin\"]', '[\"all\"]', '35', '{\"reporters\":[1,2,3,4,5,6,7,8,9,10],\"cmdrank\":2}', '0');
INSERT INTO `dept` VALUES ('3', 'Department of Justice', '[\"Judge\", \"Justice\", \"Chief Justice\"]', '[\"officer\", \"da\", \"doj\", \"pdcmd\", \"doc\"]', '31', '{\"reporters\":[1,5,6,7,8,9,10],\"cmdrank\":2}', '0');
INSERT INTO `dept` VALUES ('4', 'DTU', '[\"Reserve Detective\", \"Detective\", \"Senior Detective\", \"Sergeant\", \"Inspector\", \"Chief Inspector\"]', '[\"officer\", \"dtu\"]', '34', '{\"reporters\":[9],\"cmdrank\":4}', '0');
INSERT INTO `dept` VALUES ('5', 'SWAT', '[\"Officer\", \"Corporal\", \"Sergeant\", \"Lieutenant\", \"Captain\"]', '[\"officer\"]', '10', '{\"reporters\":[9],\"cmdrank\":3}', '0');
INSERT INTO `dept` VALUES ('6', 'Command', '[\"Deputy Chief\", \"Chief\"]', '[\"officer\", \"doc\",\"pdcmd\"]', '20', '{\"reporters\":[1,5,7,8,9],\"cmdrank\":2}', '0');
INSERT INTO `dept` VALUES ('9', 'Pending Placement', '[\"N/A\"]', '[\"nocmd\"]', '-5', '{\"reporters\":[-1],\"cmdrank\":9999}', '0');
INSERT INTO `dept` VALUES ('8', 'Department of Corrections', '[\"Junior Deputy\", \"Deputy\", \"Corporal\", \"Sergeant\", \"Lieutenant\", \"Captain\", \"Warden\"]', '[\"officer\", \"doc\",\"tc\"]', '0', '{\"reporters\":[9],\"cmdrank\":4}', '0');
INSERT INTO `dept` VALUES ('7', 'Internal Affairs', '[\"Probie Investigator\",\"Investigator\",\"Sr. Investigator\", \"Special Investigator\", \"IA Director\"]', '[\"officer\", \"pdcmd\"]', '22', '{\"reporters\":[1,5,6,8,9],\"cmdrank\":1}', '0');
INSERT INTO `dept` VALUES ('10', 'District Attorney\'s Office', '[\"Prosecutor\",\"Assistant District Attorney\",\"District Attorney\"]', '[\"officer\",\"da\", \"doj\", \"pdcmd\", \"doc\"]', '30', '{\"reporters\":[1,5,6,8,9],\"cmdrank\":1}', '0');

-- ----------------------------
-- Table structure for `info`
-- ----------------------------
DROP TABLE IF EXISTS `info`;
CREATE TABLE `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of info
-- ----------------------------
INSERT INTO `info` VALUES ('1', 'freqs', '{\"tac1\":\"79.5\",\"tac2\":\"75.5\",\"doc1\":\"84.7\",\"emg\":\"77.9\",\"active\":\"tac1\",\"ems\":\"91.1\",\"ch3\":\"69.4\"}');
INSERT INTO `info` VALUES ('2', 'threat', '{\"level\":\"2\"}');
INSERT INTO `info` VALUES ('3', 'lastfreq', '{\"582\":1493329444}');
INSERT INTO `info` VALUES ('7', 'cminfo', '{\"cmn\":\"Community Name\",\"pdn\":\"New York City Police Department\",\"pda\":\"NYPD\",\"cn\":\"Cole\"}');
INSERT INTO `info` VALUES ('6', 'text', '{\"text1\":\"Google\",\"link1\":\"https:\\/\\/www.google.com\\/\",\"text2\":\"Apache\",\"link2\":\"http:\\/\\/apache.org\\/\",\"text3\":\"YouTube\",\"link3\":\"https:\\/\\/www.youtube.com\\/\",\"text4\":\"Amazon\",\"link4\":\"https:\\/\\/www.amazon.com\\/\",\"text5\":\"Starbucks\",\"link5\":\"https:\\/\\/www.starbucks.com\\/\",\"text6\":\"Panera Bread\",\"link6\":\"https:\\/\\/www.panerabread.com\",\"text7\":\"\",\"link7\":\"\",\"text8\":\"\",\"link8\":\"\",\"text9\":\"\",\"link9\":\"\",\"text10\":\"\",\"link10\":\"\"}');
INSERT INTO `info` VALUES ('4', 'kfreq', '{\"ktac1\":\"69\",\"mtac1\":\"80\",\"ktac2\":\"134\",\"ktac3\":\"999\"}');
INSERT INTO `info` VALUES ('5', 'links', '{\"text1\":\"Google\",\"link1\":\"https:\\/\\/www.google.com\\/\",\"text2\":\"Apache\",\"link2\":\"http:\\/\\/apache.org\\/\",\"text3\":\"YouTube\",\"link3\":\"https:\\/\\/www.youtube.com\\/\",\"text4\":\"Amazon\",\"link4\":\"https:\\/\\/www.amazon.com\\/\",\"text5\":\"Starbucks\",\"link5\":\"https:\\/\\/www.starbucks.com\\/\",\"text6\":\"Panera Bread\",\"link6\":\"https:\\/\\/www.panerabread.com\",\"text7\":\"\",\"link7\":\"\",\"text8\":\"\",\"link8\":\"\",\"text9\":\"\",\"link9\":\"\",\"text10\":\"\",\"link10\":\"\"}');

-- ----------------------------
-- Table structure for `intake`
-- ----------------------------
DROP TABLE IF EXISTS `intake`;
CREATE TABLE `intake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `copid` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `crimes` varchar(255) DEFAULT NULL,
  `RealDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2457 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of intake
-- ----------------------------

-- ----------------------------
-- Table structure for `remember`
-- ----------------------------
DROP TABLE IF EXISTS `remember`;
CREATE TABLE `remember` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `hash` varchar(65) DEFAULT NULL,
  `expire` date DEFAULT NULL,
  `ip` varchar(46) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13371 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of remember
-- ----------------------------

-- ----------------------------
-- Table structure for `requests`
-- ----------------------------
DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(64) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1962 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of requests
-- ----------------------------

-- ----------------------------
-- Table structure for `traffic`
-- ----------------------------
DROP TABLE IF EXISTS `traffic`;
CREATE TABLE `traffic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `civid` int(11) DEFAULT NULL,
  `copid` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `realdate` datetime NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `ticket` int(11) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3522 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of traffic
-- ----------------------------
INSERT INTO `traffic` VALUES ('3402', '4955', '606', '2017-03-22', '2017-03-22 02:03:10', 'Excessive Speeding 2 (21-30 km/h over)', '0', '');
INSERT INTO `traffic` VALUES ('3403', '4964', '593', '2017-03-22', '2017-03-22 14:20:49', 'Aggressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3404', '4966', '621', '2017-03-21', '2017-03-22 16:14:53', 'Illegal parking', '5000', 'Mr Boeth Parked next to ATM machine at West metro Garage');
INSERT INTO `traffic` VALUES ('3405', '4941', '589', '2017-03-22', '2017-03-22 18:03:10', 'Trespassing on gov. soil', '20000', 'Plead guilty and paid the citation');
INSERT INTO `traffic` VALUES ('3406', '4971', '625', '2017-03-22', '2017-03-22 20:52:41', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3407', '4991', '617', '2017-03-23', '2017-03-23 02:12:05', 'Excessive Speeding 3 ', '15000', '');
INSERT INTO `traffic` VALUES ('3408', '4992', '619', '2017-03-23', '2017-03-23 02:40:44', 'Excessive Speeding 3 (31-40 km/h over)', '15000', '');
INSERT INTO `traffic` VALUES ('3409', '4960', '620', '2017-03-23', '2017-03-23 04:18:40', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3410', '5005', '595', '2017-03-23', '2017-03-23 19:12:01', 'Off roading in the city', '0', '');
INSERT INTO `traffic` VALUES ('3411', '5007', '595', '2017-03-23', '2017-03-23 21:26:10', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3412', '5034', '632', '2017-03-24', '2017-03-24 20:18:04', 'Reckless Driving (41 km/h over)', '20000', '');
INSERT INTO `traffic` VALUES ('3413', '4985', '617', '2017-03-24', '2017-03-24 20:28:24', 'Excessive Speeding 3 ', '15000', '');
INSERT INTO `traffic` VALUES ('3414', '5071', '617', '2017-03-25', '2017-03-25 21:06:23', 'Driving without Proper Use of Headlights', '5000', '');
INSERT INTO `traffic` VALUES ('3415', '5012', '595', '2017-03-26', '2017-03-26 02:14:14', '1x Operating a Vehicle without a License', '10000', '');
INSERT INTO `traffic` VALUES ('3416', '5104', '620', '2017-03-26', '2017-03-26 22:44:47', 'AGGRESSIVE DRIVING', '10000', '');
INSERT INTO `traffic` VALUES ('3417', '5071', '617', '2017-03-27', '2017-03-27 01:16:04', 'Driving without Proper Use of Headlights', '5000', '');
INSERT INTO `traffic` VALUES ('3418', '5027', '617', '2017-03-27', '2017-03-27 02:49:38', 'Operating a Vehicle without a License', '10000', '');
INSERT INTO `traffic` VALUES ('3419', '5119', '617', '2017-03-27', '2017-03-27 03:30:35', 'Excessive Speeding 2', '10000', '');
INSERT INTO `traffic` VALUES ('3420', '5005', '595', '2017-03-27', '2017-03-27 16:59:41', 'Off roading in the city', '10000', '');
INSERT INTO `traffic` VALUES ('3421', '5012', '633', '2017-03-27', '2017-03-27 20:25:28', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3422', '5119', '620', '2017-03-28', '2017-03-28 03:40:45', 'DRIVING WITHOUT LICENSE', '10000', '');
INSERT INTO `traffic` VALUES ('3423', '5130', '633', '2017-03-28', '2017-03-28 19:29:32', 'Aggressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3424', '5143', '623', '2017-03-29', '2017-03-29 17:49:33', 'Aiding And Abetting', '20000', '');
INSERT INTO `traffic` VALUES ('3425', '4950', '633', '2017-03-29', '2017-03-29 18:23:30', 'Speeding', '2500', '');
INSERT INTO `traffic` VALUES ('3426', '5047', '617', '2017-03-30', '2017-03-30 00:43:32', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3427', '5093', '607', '2017-03-31', '2017-03-31 20:23:26', 'Speeding', '0', 'Warning given');
INSERT INTO `traffic` VALUES ('3428', '5093', '607', '2017-03-31', '2017-03-31 20:31:16', 'Speeding', '5000', '');
INSERT INTO `traffic` VALUES ('3429', '5170', '607', '2017-03-31', '2017-03-31 21:40:39', 'Agressive Driving', '0', '1st Warning');
INSERT INTO `traffic` VALUES ('3430', '5172', '623', '2017-04-01', '2017-04-01 13:08:15', 'Agressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3431', '5052', '623', '2017-04-01', '2017-04-01 20:38:05', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3432', '5181', '607', '2017-04-02', '2017-04-02 14:33:31', 'Speeding', '0', '1st Warning ');
INSERT INTO `traffic` VALUES ('3433', '5199', '608', '2017-04-03', '2017-04-03 17:52:38', '1x aggressive driving', '10000', '');
INSERT INTO `traffic` VALUES ('3434', '5200', '641', '2017-04-03', '2017-04-03 18:15:51', 'Wreckless driving', '0', '');
INSERT INTO `traffic` VALUES ('3435', '5093', '608', '2017-04-03', '2017-04-03 18:35:16', 'excessive speeding 30km+', '15000', '');
INSERT INTO `traffic` VALUES ('3436', '5168', '633', '2017-04-03', '2017-04-03 20:20:47', 'speeding', '0', '');
INSERT INTO `traffic` VALUES ('3437', '5063', '607', '2017-04-03', '2017-04-03 20:47:19', 'Driving without headlights', '0', 'Warning');
INSERT INTO `traffic` VALUES ('3438', '5212', '652', '2017-04-03', '2017-04-03 22:39:27', 'Aggresive Driving', '10000', 'N/A');
INSERT INTO `traffic` VALUES ('3439', '5214', '641', '2017-04-03', '2017-04-03 22:46:14', 'Agressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3440', '5214', '641', '2017-04-03', '2017-04-03 22:55:40', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3441', '5215', '641', '2017-04-03', '2017-04-03 22:57:06', 'Illegal Uturn', '5000', '');
INSERT INTO `traffic` VALUES ('3442', '5216', '633', '2017-04-03', '2017-04-03 23:34:28', 'Speeding', '0', '');
INSERT INTO `traffic` VALUES ('3443', '4941', '622', '2017-04-04', '2017-04-04 17:23:01', 'Trespassing on Restricted Government Soil', '20000', 'Pleaded guilty');
INSERT INTO `traffic` VALUES ('3444', '5027', '623', '2017-04-04', '2017-04-04 18:00:11', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3445', '5231', '623', '2017-04-04', '2017-04-04 19:50:48', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3446', '5233', '654', '2017-04-04', '2017-04-04 20:28:25', 'Reckless Driving', '0', 'Suspect very compliant');
INSERT INTO `traffic` VALUES ('3447', '5261', '641', '2017-04-05', '2017-04-05 17:39:47', 'Fail to yeild', '0', '');
INSERT INTO `traffic` VALUES ('3448', '5093', '641', '2017-04-05', '2017-04-05 18:05:59', 'Reckless driving', '20000', '');
INSERT INTO `traffic` VALUES ('3449', '5263', '658', '2017-04-05', '2017-04-05 19:42:29', 'fast going', '0', 'Warrning, fast driving');
INSERT INTO `traffic` VALUES ('3450', '5201', '608', '2017-04-05', '2017-04-05 20:15:46', 'improper operation of a motor vehicle', '10000', 'https://www.youtube.com/watch?v=0epGBCdohNI&feature=youtu.be');
INSERT INTO `traffic` VALUES ('3451', '5215', '595', '2017-04-05', '2017-04-05 21:57:45', 'Aggressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3452', '5282', '633', '2017-04-06', '2017-04-06 19:06:59', 'speeding', '0', '');
INSERT INTO `traffic` VALUES ('3453', '5298', '662', '2017-04-07', '2017-04-07 03:21:39', 'Wreckless Driving,speeding', '30000', 'Was told to charge him with 30k rather than take to jail by a higher up,');
INSERT INTO `traffic` VALUES ('3454', '5034', '656', '2017-04-07', '2017-04-07 16:17:14', 'Reckless Driving', '10000', 'N/A');
INSERT INTO `traffic` VALUES ('3455', '5217', '607', '2017-04-07', '2017-04-07 19:30:04', 'Speeding | Reckless Driving', '0', 'Warning given.');
INSERT INTO `traffic` VALUES ('3456', '5308', '607', '2017-04-07', '2017-04-07 22:03:42', 'Reckless Driving', '0', 'Warning given.');
INSERT INTO `traffic` VALUES ('3457', '5002', '595', '2017-04-07', '2017-04-07 22:07:17', '1x Speeding, 1x Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3458', '5210', '633', '2017-04-08', '2017-04-08 02:18:44', 'Speeding', '10000', '');
INSERT INTO `traffic` VALUES ('3459', '5233', '656', '2017-04-08', '2017-04-08 05:56:55', 'N/A (Verbal Warning', '0', '');
INSERT INTO `traffic` VALUES ('3460', '5201', '623', '2017-04-08', '2017-04-08 18:14:02', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3461', '5320', '623', '2017-04-08', '2017-04-08 19:02:20', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3462', '5319', '641', '2017-04-08', '2017-04-08 23:23:47', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3463', '5145', '663', '2017-04-09', '2017-04-09 16:22:50', 'Speeding', '5000', 'Was Compliant with orders');
INSERT INTO `traffic` VALUES ('3464', '5334', '595', '2017-04-09', '2017-04-09 18:34:34', '1x Speeding', '0', '');
INSERT INTO `traffic` VALUES ('3465', '5360', '608', '2017-04-12', '2017-04-12 20:02:43', 'improper operation of a motor vehicle', '10000', '');
INSERT INTO `traffic` VALUES ('3466', '5385', '641', '2017-04-12', '2017-04-12 23:53:57', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3467', '5201', '596', '2017-04-14', '2017-04-14 13:55:51', 'No license, Wreckless driving, going 100+ over speedlimit', '25000', '');
INSERT INTO `traffic` VALUES ('3468', '5329', '623', '2017-04-15', '2017-04-15 22:46:33', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3469', '5336', '607', '2017-04-15', '2017-04-15 23:01:48', 'Speeding', '0', '');
INSERT INTO `traffic` VALUES ('3470', '5323', '623', '2017-04-15', '2017-04-15 23:17:40', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3471', '5212', '633', '2017-04-16', '2017-04-16 00:09:37', 'Aggressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3472', '5165', '593', '2017-04-16', '2017-04-16 04:07:58', 'Driving offroad', '0', '');
INSERT INTO `traffic` VALUES ('3473', '5247', '633', '2017-04-16', '2017-04-16 04:24:54', 'Aggresive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3474', '5034', '623', '2017-04-16', '2017-04-16 13:50:19', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3475', '5413', '593', '2017-04-16', '2017-04-16 14:02:33', 'Speeding', '0', '');
INSERT INTO `traffic` VALUES ('3476', '5412', '593', '2017-04-16', '2017-04-16 14:04:42', 'Aggressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3477', '5414', '623', '2017-04-16', '2017-04-16 14:32:37', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3478', '5411', '623', '2017-04-16', '2017-04-16 14:42:34', 'Driving without a license', '10000', '');
INSERT INTO `traffic` VALUES ('3479', '5296', '623', '2017-04-16', '2017-04-16 19:24:55', 'Agressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3480', '5401', '607', '2017-04-16', '2017-04-16 19:36:03', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3481', '5418', '650', '2017-04-16', '2017-04-16 19:39:38', 'Speeding & Offroad', '0', 'Warning');
INSERT INTO `traffic` VALUES ('3482', '5228', '633', '2017-04-16', '2017-04-16 22:28:58', 'aggresive driving', '5000', '');
INSERT INTO `traffic` VALUES ('3483', '5401', '641', '2017-04-17', '2017-04-17 01:56:05', 'Reckless Driving', '20000', '');
INSERT INTO `traffic` VALUES ('3484', '5423', '623', '2017-04-17', '2017-04-17 13:37:37', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3485', '5142', '607', '2017-04-17', '2017-04-17 13:48:40', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3486', '5347', '623', '2017-04-17', '2017-04-17 13:50:18', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3487', '5228', '607', '2017-04-17', '2017-04-17 13:56:58', 'Aggressive Driving', '0', '');
INSERT INTO `traffic` VALUES ('3488', '5347', '650', '2017-04-17', '2017-04-17 14:16:46', 'Reckless Driving', '5000', '');
INSERT INTO `traffic` VALUES ('3489', '5228', '623', '2017-04-17', '2017-04-17 14:28:28', 'Brandishing a firearm', '50000', 'Firearm confiscated (Class 1 with a license)');
INSERT INTO `traffic` VALUES ('3490', '5422', '650', '2017-04-17', '2017-04-17 16:11:42', 'Aggresive driving', '0', '');
INSERT INTO `traffic` VALUES ('3491', '5425', '623', '2017-04-17', '2017-04-17 16:18:59', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3492', '5424', '663', '2017-04-17', '2017-04-17 16:19:48', 'Reckless Driving', '0', 'Verbal Warning');
INSERT INTO `traffic` VALUES ('3493', '5399', '650', '2017-04-17', '2017-04-17 17:04:36', 'Speeding ', '0', '');
INSERT INTO `traffic` VALUES ('3494', '5126', '607', '2017-04-17', '2017-04-17 17:40:30', 'Reckless Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3495', '5439', '641', '2017-04-18', '2017-04-18 00:24:47', 'Brandishing', '50000', '');
INSERT INTO `traffic` VALUES ('3496', '5451', '663', '2017-04-18', '2017-04-18 17:13:55', '1x Aggressive Driving, 1x Illegal U-Turn', '15000', '');
INSERT INTO `traffic` VALUES ('3497', '5126', '663', '2017-04-18', '2017-04-18 17:20:34', 'Illegal U-Turn', '0', 'Warning');
INSERT INTO `traffic` VALUES ('3498', '5454', '623', '2017-04-18', '2017-04-18 19:43:10', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3499', '5041', '607', '2017-04-18', '2017-04-18 19:50:40', 'Excessive Vehicle Noise', '0', '');
INSERT INTO `traffic` VALUES ('3500', '4967', '607', '2017-04-18', '2017-04-18 19:52:53', 'Speeding', '0', '');
INSERT INTO `traffic` VALUES ('3501', '5129', '607', '2017-04-18', '2017-04-18 20:17:18', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3502', '5391', '607', '2017-04-18', '2017-04-18 21:17:08', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3503', '5094', '618', '2017-04-18', '2017-04-18 21:18:13', 'Warning', '0', '');
INSERT INTO `traffic` VALUES ('3504', '4952', '608', '2017-04-19', '2017-04-19 01:55:13', 'hit and run', '30000', '');
INSERT INTO `traffic` VALUES ('3505', '5391', '607', '2017-04-19', '2017-04-19 17:02:47', 'Agressive Driving', '10000', '');
INSERT INTO `traffic` VALUES ('3506', '5279', '663', '2017-04-19', '2017-04-19 19:26:08', 'Aggressive Driving', '0', 'Warning');
INSERT INTO `traffic` VALUES ('3507', '5389', '607', '2017-04-20', '2017-04-20 12:06:00', 'Speeding', '10000', '');
INSERT INTO `traffic` VALUES ('3508', '5478', '607', '2017-04-21', '2017-04-21 19:21:25', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3509', '5482', '623', '2017-04-22', '2017-04-22 11:43:51', 'Reckless Driving', '0', '');
INSERT INTO `traffic` VALUES ('3510', '5380', '641', '2017-04-24', '2017-04-24 20:33:41', '2x Speeding', '10000', '');
INSERT INTO `traffic` VALUES ('3511', '5506', '641', '2017-04-24', '2017-04-24 20:48:46', 'Excessive Speeding 2', '10000', '');
INSERT INTO `traffic` VALUES ('3512', '5451', '641', '2017-04-24', '2017-04-24 22:17:58', 'Criminal Mischief', '10000', 'On duty medic. Got permission from his command to ticket and a verbal complaint was made by me and Sal');
INSERT INTO `traffic` VALUES ('3513', '5520', '699', '2017-04-25', '2017-04-25 21:00:29', 'Wrecklessly Lane Cutting', '0', '');
INSERT INTO `traffic` VALUES ('3514', '5466', '699', '2017-04-26', '2017-04-26 02:06:23', 'Speeding (doing 85 in a 60)', '0', '');
INSERT INTO `traffic` VALUES ('3515', '5509', '697', '2017-04-26', '2017-04-26 02:57:32', 'Brandishing a Firearm', '50000', '');
INSERT INTO `traffic` VALUES ('3516', '5457', '693', '2017-04-27', '2017-04-27 02:01:53', 'Aggressive Driving', '0', 'Kept break checking officers');
INSERT INTO `traffic` VALUES ('3517', '5534', '692', '2017-04-27', '2017-04-27 03:16:51', 'Reckless driving', '0', '');
INSERT INTO `traffic` VALUES ('3518', '5536', '692', '2017-04-27', '2017-04-27 05:53:25', 'Speeding 20/km over', '5000', '');
INSERT INTO `traffic` VALUES ('3519', '5451', '692', '2017-04-27', '2017-04-27 06:34:47', 'Illegal parking ', '0', '');
INSERT INTO `traffic` VALUES ('3520', '5538', '692', '2017-04-27', '2017-04-27 22:03:47', 'Speeding', '2500', '');
INSERT INTO `traffic` VALUES ('3521', '5368', '697', '2017-04-27', '2017-04-27 22:18:05', 'Tresspassing', '10000', '');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `citid` int(11) DEFAULT NULL,
  `RegiDate` date DEFAULT NULL,
  `LastLogin` date DEFAULT NULL,
  `uname` varchar(20) DEFAULT NULL,
  `display` varchar(36) DEFAULT NULL,
  `phash` varchar(100) DEFAULT NULL,
  `salt` varchar(15) DEFAULT NULL,
  `ip` varchar(46) DEFAULT NULL,
  `rank` tinyint(4) DEFAULT '0',
  `dept` tinyint(4) DEFAULT '9',
  `badge` varchar(8) DEFAULT NULL,
  `plevel` text,
  `email` varchar(40) DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `phone` varchar(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=707 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------

-- ----------------------------
-- Table structure for `warrants`
-- ----------------------------
DROP TABLE IF EXISTS `warrants`;
CREATE TABLE `warrants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `copid` int(11) DEFAULT NULL,
  `dojname` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `crimes` varchar(255) DEFAULT NULL,
  `wtype` varchar(255) DEFAULT NULL,
  `wlink` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `RealDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of warrants
-- ----------------------------
