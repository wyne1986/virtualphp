/*
Navicat MariaDB Data Transfer

Source Server         : ubuntu-server-vb
Source Server Version : 100031
Source Host           : 192.168.1.195:3306
Source Database       : ftp

Target Server Type    : MariaDB
Target Server Version : 100031
File Encoding         : 65001

Date: 2017-08-30 17:06:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for guest
-- ----------------------------
DROP TABLE IF EXISTS `guest`;
CREATE TABLE `guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '',
  `randoms` varchar(6) NOT NULL DEFAULT '123456',
  `password` varchar(32) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `regtime` int(11) NOT NULL DEFAULT '0',
  `lastlogin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `qq` (`qq`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `uid` int(11) NOT NULL DEFAULT '33',
  `gid` int(11) NOT NULL DEFAULT '33',
  `dir` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '200',
  `maxul` int(11) NOT NULL DEFAULT '5242880' COMMENT '上传单文件最大限制B',
  `maxdl` int(11) NOT NULL DEFAULT '5242880' COMMENT '下载单文件最大限制B',
  `bandul` int(11) NOT NULL DEFAULT '200' COMMENT '上传带宽限制KB',
  `banddl` int(11) NOT NULL DEFAULT '200' COMMENT '下载带宽限制KB',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
