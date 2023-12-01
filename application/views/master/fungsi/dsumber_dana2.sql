/*
Navicat SQL Server Data Transfer

Source Server         : LOKAL
Source Server Version : 105000
Source Host           : DESKTOP-FUB3U53:1433
Source Database       : provinsi_90_2
Source Schema         : dbo

Target Server Type    : SQL Server
Target Server Version : 105000
File Encoding         : 65001

Date: 2020-06-04 23:07:45
*/


-- ----------------------------
-- Table structure for dsumber_dana
-- ----------------------------
DROP TABLE [dbo].[dsumber_dana]
GO
CREATE TABLE [dbo].[dsumber_dana] (
[kd_sumberdana] varchar(100) NULL ,
[kd_skpd] varchar(50) NULL 
)


GO

-- ----------------------------
-- Records of dsumber_dana
-- ----------------------------

-- ----------------------------
-- Table structure for hsumber_dana
-- ----------------------------
DROP TABLE [dbo].[hsumber_dana]
GO
CREATE TABLE [dbo].[hsumber_dana] (
[kd_sumberdana] varchar(100) NULL ,
[nm_sumberdana] varchar(200) NULL ,
[nilai] decimal(38,2) NULL ,
[nilaisilpa] decimal(38,2) NULL 
)


GO

-- ----------------------------
-- Records of hsumber_dana
-- ----------------------------
