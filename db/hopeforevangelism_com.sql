/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : hopeforevangelism_com

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 08/12/2023 07:56:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_connection
-- ----------------------------
DROP TABLE IF EXISTS `tb_connection`;
CREATE TABLE `tb_connection`  (
  `usernr1` int NOT NULL,
  `usernr2` int NOT NULL,
  `cdate` date NOT NULL,
  `status` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`usernr1`, `usernr2`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_connection
-- ----------------------------
INSERT INTO `tb_connection` VALUES (1, 1, '2023-12-01', '2');
INSERT INTO `tb_connection` VALUES (1, 3, '2023-12-01', '2');
INSERT INTO `tb_connection` VALUES (1, 6, '2023-12-01', '1');
INSERT INTO `tb_connection` VALUES (3, 1, '2023-12-01', '2');
INSERT INTO `tb_connection` VALUES (5, 1, '2023-12-01', '2');
INSERT INTO `tb_connection` VALUES (5, 3, '2023-12-01', '1');
INSERT INTO `tb_connection` VALUES (6, 1, '2023-12-01', '1');

-- ----------------------------
-- Table structure for tb_default
-- ----------------------------
DROP TABLE IF EXISTS `tb_default`;
CREATE TABLE `tb_default`  (
  `radius` int NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_default
-- ----------------------------
INSERT INTO `tb_default` VALUES (50);

-- ----------------------------
-- Table structure for tb_default_lang
-- ----------------------------
DROP TABLE IF EXISTS `tb_default_lang`;
CREATE TABLE `tb_default_lang`  (
  `langu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `legal_text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`langu`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_default_lang
-- ----------------------------
INSERT INTO `tb_default_lang` VALUES ('english', 'That my data will be stored and processed for the purpose of contacting me and for community work in accordance with the statutory provisions on data protection. You can object to further processing at any time, as well as request correction, deletion and information about your data, insofar as this is legally permissible. Further information (incl. privacy policy) can be found at www.hopeforevangelism.com/privacy-policy');
INSERT INTO `tb_default_lang` VALUES ('german', 'Dass meine Daten zum Zweck der Kontaktaufnahme und der Gemeindearbeit gemäß der gesetzlichen Bestimmungen zum Datenschutz gespeichert und verarbeitet werden. Der weiteren Verarbeitung kannst du jederzeit widersprechen, sowie Berichtigung, Löschung und Auskunft über deine Daten verlangen, soweit dies gesetzlich zulässig ist. Weitere Informationen (inkl. Datenschutzerklärung) unter www.hopeforevangelism.com/privacy-policy');

-- ----------------------------
-- Table structure for tb_email_text
-- ----------------------------
DROP TABLE IF EXISTS `tb_email_text`;
CREATE TABLE `tb_email_text`  (
  `langu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ewtype` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pos` tinyint NOT NULL,
  `html_link` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `txt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`langu`, `type`, `ewtype`, `pos`, `html_link`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_email_text
-- ----------------------------
INSERT INTO `tb_email_text` VALUES ('english', 'connect', 'email', 1, 'html', 'I would like to connect with you, so that in the future, when we have events, you will be automatically invited to them. Please click on the below link to confirm this.');
INSERT INTO `tb_email_text` VALUES ('english', 'newBorn', 'email', 1, 'html', 'Welcome to our portal on www.hopeforevangelism.com');
INSERT INTO `tb_email_text` VALUES ('english', 'newBorn', 'email', 2, 'html', 'Please check out our privacy policy');
INSERT INTO `tb_email_text` VALUES ('english', 'newBorn', 'email', 3, 'link', 'https://www.hopeforevangelism.com/privacy-policy/');
INSERT INTO `tb_email_text` VALUES ('english', 'newBorn', 'email', 4, 'html', 'Please review who can contact you. By clicking on the links below, you will revoke their rights to contact you.');
INSERT INTO `tb_email_text` VALUES ('english', 'register', 'email', 1, 'html', 'Welcome to HopeForEvangelism.com');
INSERT INTO `tb_email_text` VALUES ('english', 'register', 'email', 2, 'html', 'Please check out our privacy policy');
INSERT INTO `tb_email_text` VALUES ('english', 'register', 'email', 3, 'link', 'https://www.hopeforevangelism.com/privacy-policy/');

-- ----------------------------
-- Table structure for tb_evangel
-- ----------------------------
DROP TABLE IF EXISTS `tb_evangel`;
CREATE TABLE `tb_evangel`  (
  `langu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descript` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lnk` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`langu`, `descript`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_evangel
-- ----------------------------
INSERT INTO `tb_evangel` VALUES ('Deutsch', 'Katholiken', 'https://www.hopeforevangelism.com/2023/11/18/katholiken/');
INSERT INTO `tb_evangel` VALUES ('English', 'Catholic', 'https://www.hopeforevangelism.com/2023/11/18/catholics/');
INSERT INTO `tb_evangel` VALUES ('English', 'Muslim', 'https://www.hopeforevangelism.com/2023/11/18/muslims/');

-- ----------------------------
-- Table structure for tb_event
-- ----------------------------
DROP TABLE IF EXISTS `tb_event`;
CREATE TABLE `tb_event`  (
  `eventnr` int NOT NULL AUTO_INCREMENT,
  `usernr` int NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `street` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `zip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dateofevent` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `invitetxt` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `radiuskm` int NOT NULL,
  `web` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `facebook` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `instagram` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sendout` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`eventnr`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_event
-- ----------------------------
INSERT INTO `tb_event` VALUES (1, 1, 'Test Create', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '12/06/2023', 'Test', 1, '', NULL, NULL, 0);
INSERT INTO `tb_event` VALUES (3, 3, 'Test1', 'Промислова вулиця, 3', '98400-98408', 'городское поселение Бахчисарай', 'ukraine', '01/10/2024', 'Test', 1, '', NULL, NULL, 0);
INSERT INTO `tb_event` VALUES (4, 3, 'Test1', '16545 Yonge St', 'L3X 2G8', 'Newmarket', 'canada', '01/10/2024', 'test', 0, '', NULL, NULL, 0);
INSERT INTO `tb_event` VALUES (5, 6, 'Test Create', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '01/18/2024', 'Test', 1, '', NULL, NULL, 0);
INSERT INTO `tb_event` VALUES (6, 1, 'Test Create', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '12/10/2023', 'Test', 1, '', NULL, NULL, 0);

-- ----------------------------
-- Table structure for tb_event_att
-- ----------------------------
DROP TABLE IF EXISTS `tb_event_att`;
CREATE TABLE `tb_event_att`  (
  `eventnr` int NOT NULL,
  `usernr` int NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`eventnr`, `usernr`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_event_att
-- ----------------------------
INSERT INTO `tb_event_att` VALUES (1, 6, '2023-12-01');
INSERT INTO `tb_event_att` VALUES (3, 5, '2023-12-03');

-- ----------------------------
-- Table structure for tb_members
-- ----------------------------
DROP TABLE IF EXISTS `tb_members`;
CREATE TABLE `tb_members`  (
  `usernr` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `organization` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `street` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `zip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cellphone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `instagram` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `facebook` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `whatsappcode` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `active` tinyint NOT NULL,
  `sendout` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`usernr`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_members
-- ----------------------------
INSERT INTO `tb_members` VALUES (1, 'Church,DEUTSCH', 'Test User', 'Upwork', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '', '', '', '', '', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (3, 'Church,ENGLISH', 'Test User1', '', 'Промислова вулиця, 3', '98400-98408', 'городское поселение Бахчисарай', 'ukraine', '', '', '', '', '', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (4, 'Evangelist,DEUTSCH', 'Test User2', '', 'Zimmerpforte 5', '20099', 'Hamburg', 'germany', '', '', '', '', '', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (5, 'Evangelist,ENGLISHE', 'Test Member', 'Upwork', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '1234567890', '1234567890', 'https://www.example.com', 'https://www.example.com', 'https://www.example.com', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (6, 'newBorn,DEUTSCH', 'Member3', 'Upwork', 'Kindiyskoye highway', '73000-73480', 'Kherson', 'ukraine', '123456789', '1234567890', 'https://www.example.com', 'https://www.example.com', 'https://www.example.com', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (7, '', 'yyyy', '', '', '', '', '', '', '', '', '', '', NULL, 0, NULL);
INSERT INTO `tb_members` VALUES (8, '', 'Test', '', '', '', '', '', '', '', '', '', '', NULL, 0, 0);

-- ----------------------------
-- Table structure for tb_types
-- ----------------------------
DROP TABLE IF EXISTS `tb_types`;
CREATE TABLE `tb_types`  (
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `langu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descript` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `generatecode` tinyint NULL DEFAULT NULL,
  PRIMARY KEY (`type`, `langu`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_types
-- ----------------------------
INSERT INTO `tb_types` VALUES ('Church', 'deutsch', 'Gemeinde', 1);
INSERT INTO `tb_types` VALUES ('Church', 'english', 'Church', 1);
INSERT INTO `tb_types` VALUES ('Evangelist', 'deutsch', 'Evangelist', 0);
INSERT INTO `tb_types` VALUES ('Evangelist', 'english', 'Evangelist', 0);
INSERT INTO `tb_types` VALUES ('newBorn', 'deutsch', 'Neugeborener Christ', 0);
INSERT INTO `tb_types` VALUES ('newBorn', 'english', 'New Christian', 0);

-- ----------------------------
-- Table structure for tb_users
-- ----------------------------
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE `tb_users`  (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usernr` int NOT NULL AUTO_INCREMENT,
  `rcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `active` tinyint NOT NULL,
  `admin` tinyint NOT NULL,
  PRIMARY KEY (`email`) USING BTREE,
  INDEX `usernr`(`usernr` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_users
-- ----------------------------
INSERT INTO `tb_users` VALUES ('test11@gmail.com', 'e0cea4bb45e36c115356e46910c354af', 1, '', 1, 1);
INSERT INTO `tb_users` VALUES ('test123@gmail.com', 'e0cea4bb45e36c115356e46910c354af', 5, '', 1, 0);
INSERT INTO `tb_users` VALUES ('test13@gmail.com', '0f6c69dc4b698d8537c378c7fe88ce02', 7, '7a9da8265fb91010104db7d935b81443', 0, 0);
INSERT INTO `tb_users` VALUES ('test1@gmail.com', 'e0cea4bb45e36c115356e46910c354af', 3, '', 1, 0);
INSERT INTO `tb_users` VALUES ('test2@gmail.com', 'e0cea4bb45e36c115356e46910c354af', 4, '', 1, 0);
INSERT INTO `tb_users` VALUES ('test333@gmail.com', 'e0cea4bb45e36c115356e46910c354af', 6, '', 1, 0);
INSERT INTO `tb_users` VALUES ('toflyinsky.dev919@gmail.com', '0f6c69dc4b698d8537c378c7fe88ce02', 8, '', 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
