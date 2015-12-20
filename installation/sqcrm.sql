-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: sqrelease
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `block`
--

DROP TABLE IF EXISTS `block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block` (
  `idblock` int(19) NOT NULL AUTO_INCREMENT,
  `idmodule` int(19) NOT NULL,
  `block_label` varchar(200) NOT NULL,
  `sequence` int(10) NOT NULL,
  PRIMARY KEY (`idblock`),
  KEY `block_idblock_idx` (`idblock`),
  KEY `block_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block`
--

LOCK TABLES `block` WRITE;
/*!40000 ALTER TABLE `block` DISABLE KEYS */;
INSERT INTO `block` VALUES (1,7,'User Information',1),(2,7,'Other Information',2),(3,7,'Address Information',3),(4,3,'Lead Information',1),(5,3,'Address Information',2),(6,3,'Custom Information',3),(7,6,'Organization Information',1),(8,6,'Address Information',2),(9,6,'Custom Information',3),(10,4,'Contact Information',1),(11,4,'Portal Information',2),(12,4,'Address Information',3),(13,4,'Custom Information',4),(14,5,'Prospect Information',1),(15,5,'Custom Information',2),(16,2,'Event information',1),(17,2,'Custom information',2),(18,11,'Vendor Information',1),(19,11,'Address Information',2),(20,11,'Custom Information',3),(21,12,'Product Information',1),(22,12,'Product Pricing',2),(23,12,'Stock Information',3),(24,12,'Custom Information',4),(25,13,'Quote Information',1),(26,13,'Address Information',2),(27,14,'Sales Order Information',1),(28,14,'Address Information',2),(29,13,'Custom Information',3),(30,14,'Custom Information',3),(31,15,'Invoice Information',1),(32,15,'Address Information',2),(33,15,'Custom Information',3),(34,16,'Purchase Order Information',1),(35,16,'Address Information',2),(36,16,'Custom Information',3);
/*!40000 ALTER TABLE `block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_to_grp_rel`
--

DROP TABLE IF EXISTS `cnt_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_to_grp_rel` (
  `idcnt_to_grp_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idcontacts` int(19) NOT NULL,
  `idgroup` int(20) DEFAULT NULL,
  KEY `cnttogrprel_idcnt_to_grp_rel_idx` (`idcnt_to_grp_rel`),
  KEY `cnttogrprel_idcontacts_idx` (`idcontacts`),
  KEY `cnttogrprel_idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_to_grp_rel`
--

LOCK TABLES `cnt_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `cnt_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combo_values`
--

DROP TABLE IF EXISTS `combo_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combo_values` (
  `idcombo_values` int(19) NOT NULL AUTO_INCREMENT,
  `idfields` int(19) NOT NULL,
  `combo_option` varchar(200) NOT NULL,
  `combo_value` varchar(200) NOT NULL,
  `sequence` int(10) NOT NULL,
  PRIMARY KEY (`idcombo_values`),
  KEY `combo_idfields_idx` (`idfields`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combo_values`
--

LOCK TABLES `combo_values` WRITE;
/*!40000 ALTER TABLE `combo_values` DISABLE KEYS */;
INSERT INTO `combo_values` VALUES (1,19,'mm-dd-yyyy','mm-dd-yyyy',1),(2,19,'mm/dd/yyyy','mm/dd/yyyy',2),(3,19,'dd-mm-yyyy','dd-mm-yyyy',3),(4,19,'dd/mm/yyyy','dd/mm/yyyy',4),(5,19,'yyyy-mm-dd','yyyy-mm-dd',5),(6,19,'yyyy/mm/dd','yyyy/mm/dd',6),(7,25,'Yes','Yes',1),(8,25,'No','No',1),(9,33,'Pick One','Pick One',1),(10,33,'Advertisement,','Advertisement',2),(11,33,'Channel Partner','Channel Partner',3),(12,33,'Cold Call','Cold Call',4),(13,33,'Existing Customer','Existing Customer',5),(14,33,'Marketing Event','Marketing Event',6),(15,33,'Other','Other',7),(16,33,'Public Relation','Public Relation',8),(17,33,'Self Generated','Self Generated',9),(18,33,'Webiners','Webiners',10),(19,34,'Pick One','Pick One',1),(20,34,'Apparel','Apparel',2),(21,34,'Banking','Banking',3),(22,34,'Biotechnology','Biotechnology',4),(23,34,'Chemicals','Chemicals',5),(24,34,'Comunications','Comunications',6),(25,34,'Consulting','Consulting',7),(26,34,'Education','Education',8),(27,34,'Electronics','Electronics',9),(28,34,'Energy','Energy',10),(29,34,'Engineering','Engineering',11),(30,34,'Entiretainment','Entiretainment',12),(31,34,'Finance','Finance',13),(32,34,'Government','Government',14),(33,34,'Healthcare','Healthcare',15),(34,34,'Hospitality','Hospitality',16),(35,34,'Insurance','Insurance',17),(36,34,'Media','Media',18),(37,34,'Technology','Technology',19),(38,34,'Transpotation','Transpotation',20),(39,34,'Other','Other',21),(40,37,'Pick One','Pick One',1),(41,37,'Archived-No Marketing','Archived-No Marketing',2),(42,37,'Cold','Cold',3),(43,37,'Hot','Hot',4),(44,37,'Future Interest','Future Interest',5),(45,37,'Lost Lead','Lost Lead',6),(46,37,'No Response','No Response',7),(47,37,'Qualified','Qualified',8),(48,39,'Pick One','Pick One',1),(49,39,'Acquired','Acquired',2),(50,39,'Active','Active',3),(51,39,'Market Failed','Market Failed',4),(52,39,'Project Cancelled','Project Cancelled',5),(53,39,'Other','Other',6),(54,56,'Pick One','Pick One',1),(55,56,'Apparel','Apparel',2),(56,56,'Banking','Banking',3),(57,56,'Biotechnology','Biotechnology',4),(58,56,'Chemicals','Chemicals',5),(59,56,'Comunications','Comunications',6),(60,56,'Consulting','Consulting',7),(61,56,'Education','Education',8),(62,56,'Electronics','Electronics',9),(63,56,'Energy','Energy',10),(64,56,'Engineering','Engineering',11),(65,56,'Entiretainment','Entiretainment',12),(66,56,'Finance','Finance',13),(67,56,'Government','Government',14),(68,56,'Healthcare','Healthcare',15),(69,56,'Hospitality','Hospitality',16),(70,56,'Insurance','Insurance',17),(71,56,'Media','Media',18),(72,56,'Technology','Technology',19),(73,56,'Transpotation','Transpotation',20),(74,56,'Other','Other',21),(75,57,'Pick One','Pick One',1),(76,57,'Acquired','Acquired',2),(77,57,'Active','Active',3),(78,57,'Market Failed','Market Failed',4),(79,57,'Project Cancelled','Project Cancelled',5),(80,57,'Other','Other',6),(81,60,'Pick One','Pick One',1),(82,60,'Analyst','Analyst',2),(83,60,'Competitor','Competitor',3),(84,60,'Customer','Customer',4),(85,60,'Distributor','Distributor',5),(86,60,'Investor','Investor',6),(87,60,'OEM','OEM',7),(88,60,'Prospect','Prospect',8),(89,60,'Reseller','Reseller',9),(90,60,'Other','Other',10),(91,80,'Pick One','Pick One',1),(92,80,'Advertisement,','Advertisement',2),(93,80,'Channel Partner','Channel Partner',3),(94,80,'Cold Call','Cold Call',4),(95,80,'Existing Customer','Existing Customer',5),(96,80,'Marketing Event','Marketing Event',6),(97,80,'Other','Other',7),(98,80,'Public Relation','Public Relation',8),(99,80,'Self Generated','Self Generated',9),(100,80,'Webiners','Webiners',10),(101,116,'Pick One','Pick One',1),(102,116,'Advertisement,','Advertisement',2),(103,116,'Channel Partner','Channel Partner',3),(104,116,'Cold Call','Cold Call',4),(105,116,'Existing Customer','Existing Customer',5),(106,116,'Marketing Event','Marketing Event',6),(107,116,'Other','Other',7),(108,116,'Public Relation','Public Relation',8),(109,116,'Self Generated','Self Generated',9),(110,116,'Webiners','Webiners',10),(111,113,'Pick One','Pick One',1),(112,113,'Analyst','Analyst',2),(113,113,'Competitor','Competitor',3),(114,113,'Customer','Customer',4),(115,113,'Distributor','Distributor',5),(116,113,'Investor','Investor',6),(117,113,'OEM','OEM',7),(118,113,'Prospect','Prospect',8),(119,113,'Reseller','Reseller',9),(120,113,'Other','Other',10),(121,117,'Pick One','Pick One',1),(122,117,'Close Lost','Close Lost',2),(123,117,'Close Win','Close Win',3),(124,117,'Need Analysis','Need Analysis',4),(125,117,'Negotiating','Negotiating',5),(126,117,'Prospecting','Prospecting',6),(127,117,'Qualification','Qualification',7),(128,117,'Verval','Verval',8),(129,122,'Call','Call',1),(130,122,'Meeting','Meeting',2),(131,122,'Non Marketing','Non Marketing',3),(132,131,'Planned','Planned',1),(133,131,'Held','Held',2),(134,131,'Cancelled','Cancelled',3),(135,126,'High','High',1),(136,126,'Medium','Medium',2),(137,126,'Low','Low',3),(138,167,'Pick One','Pick One',1),(139,167,'Software','Software',2),(140,167,'Hardware','Hardware',3),(141,167,'CRM Application','CRM Application',4),(142,168,'Pick One','Pick One',1),(143,168,'sQcrm','sQcrm',2),(144,168,'Microsoft','Microsoft',3),(145,168,'IBM','IBM',4),(146,183,'Created','Created',2),(147,183,'Sent','Sent',3),(148,183,'Delivered','Delivered',4),(149,183,'Accepted','Accepted',5),(150,183,'Rejected','Rejected',6),(151,217,'Created','Created',2),(152,217,'Sent','Sent',3),(153,217,'Delivered','Delivered',4),(154,217,'Accepted','Accepted',5),(155,217,'Rejected','Rejected',6),(156,253,'Created','Created',1),(157,253,'Sent','Sent',2),(158,253,'Partial','Partial',3),(159,253,'Paid','Paid',4),(160,253,'Overdue','Overdue',5),(161,253,'Rejected','Rejected',6),(162,289,'Created','Created',1),(163,289,'Sent','Sent',2),(164,289,'Approved','Approved',3),(165,289,'Cancelled','Cancelled',4);
/*!40000 ALTER TABLE `combo_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `idcontacts` int(19) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) DEFAULT NULL,
  `lastname` varchar(200) DEFAULT NULL,
  `office_phone` varchar(20) DEFAULT NULL,
  `idorganization` int(19) DEFAULT NULL,
  `mobile_num` varchar(50) DEFAULT NULL,
  `leadsource` varchar(100) DEFAULT NULL,
  `home_phone` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `other_phone` varchar(20) DEFAULT NULL,
  `department` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `assistant` varchar(200) DEFAULT NULL,
  `assistant_phone` varchar(20) DEFAULT NULL,
  `reports_to` int(19) NOT NULL DEFAULT '0',
  `secondary_email` varchar(200) DEFAULT NULL,
  `email_opt_out` int(3) NOT NULL DEFAULT '0',
  `do_not_call` int(3) NOT NULL DEFAULT '0',
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `description` text,
  `contact_avatar` varchar(200) DEFAULT NULL,
  `portal_user` int(3) NOT NULL DEFAULT '0',
  `support_start_date` date DEFAULT NULL,
  `support_end_date` date DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(19) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`idcontacts`),
  KEY `cnt_idcontacts_idx` (`idcontacts`),
  KEY `cnt_iduser_idx` (`iduser`),
  KEY `cnt_firstname_idx` (`firstname`),
  KEY `cnt_lastname_idx` (`lastname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts_address`
--

DROP TABLE IF EXISTS `contacts_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts_address` (
  `idcontacts_address` int(19) NOT NULL AUTO_INCREMENT,
  `idcontacts` int(19) NOT NULL,
  `cnt_mail_street` text,
  `cnt_other_street` text,
  `cnt_mail_pobox` varchar(50) DEFAULT NULL,
  `cnt_other_pobox` varchar(50) DEFAULT NULL,
  `cnt_mailing_city` varchar(50) DEFAULT NULL,
  `cnt_other_city` varchar(50) DEFAULT NULL,
  `cnt_mailing_state` varchar(100) DEFAULT NULL,
  `cnt_other_state` varchar(100) DEFAULT NULL,
  `cnt_mailing_postalcode` varchar(100) DEFAULT NULL,
  `cnt_other_postalcode` varchar(100) DEFAULT NULL,
  `cnt_mailing_country` varchar(50) DEFAULT NULL,
  `cnt_other_country` varchar(50) DEFAULT NULL,
  KEY `cntaddr_idcontacts_address_idx` (`idcontacts_address`),
  KEY `cntaddr_idcontacts_idx` (`idcontacts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts_address`
--

LOCK TABLES `contacts_address` WRITE;
/*!40000 ALTER TABLE `contacts_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts_custom_fld`
--

DROP TABLE IF EXISTS `contacts_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts_custom_fld` (
  `idcontacts_custom_fld` int(19) NOT NULL AUTO_INCREMENT,
  `idcontacts` int(19) NOT NULL,
  KEY `cntcf_idcontacts_custom_fld_idx` (`idcontacts_custom_fld`),
  KEY `cntcf_idcontacts_idx` (`idcontacts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts_custom_fld`
--

LOCK TABLES `contacts_custom_fld` WRITE;
/*!40000 ALTER TABLE `contacts_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_global_settings`
--

DROP TABLE IF EXISTS `crm_global_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_global_settings` (
  `idcrm_global_settings` int(19) NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(100) DEFAULT NULL,
  `setting_data` text,
  PRIMARY KEY (`idcrm_global_settings`),
  KEY `globalsetting_idcrm_global_settings_idx` (`idcrm_global_settings`),
  KEY `globalsetting_setting_name_idx` (`setting_name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_global_settings`
--

LOCK TABLES `crm_global_settings` WRITE;
/*!40000 ALTER TABLE `crm_global_settings` DISABLE KEYS */;
INSERT INTO `crm_global_settings` VALUES (1,'currency_setting','{\"currency_iso_code\":\"USD\",\"currency_sysmbol\":\"$\",\"currency_symbol_position\":\"left\",\"decimal_point\":\"2\",\"decimal_symbol\":\".\",\"thousand_seperator\":\",\"}'),(2,'quote_num_prefix','QUO000000'),(3,'invoice_num_prefix','INV000000'),(4,'salesorder_num_prefix','SO000000'),(5,'purchaseorder_num_prefix','PO000000'),(6,'quote_terms_condition','Quotations provided by the Seller will only remain valid for the period of time as indicated in the quotation after which the Seller reserves the rights to re-tender if requested by the Purchaser to proceed further.'),(7,'invoice_terms_condition','A finance charge of 1.5% will be made on unpaid balances after 30 days.'),(8,'salesorder_terms_condition','All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.'),(9,'purchaseorder_terms_condition','All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.'),(10,'inventory_logo','comp_logo.jpg'),(11,'company_address','sQcrm Pvt. Ltd.\n2nd B Main Road, Koramangala\nBangalore,Karnataka,India\n PIN-560095');
/*!40000 ALTER TABLE `crm_global_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_owner`
--

DROP TABLE IF EXISTS `crm_owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crm_owner` (
  `idcrm_owner` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  PRIMARY KEY (`idcrm_owner`),
  KEY `owner_crm_owner_idx` (`idcrm_owner`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_owner`
--

LOCK TABLES `crm_owner` WRITE;
/*!40000 ALTER TABLE `crm_owner` DISABLE KEYS */;
INSERT INTO `crm_owner` VALUES (1,'Sqlfusion',1);
/*!40000 ALTER TABLE `crm_owner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_field_mapping`
--

DROP TABLE IF EXISTS `custom_field_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_field_mapping` (
  `idcustom_field_mapping` int(19) NOT NULL AUTO_INCREMENT,
  `mapping_field_id` int(19) DEFAULT NULL,
  `organization_mapped_to` int(19) DEFAULT NULL,
  `contacts_mapped_to` int(19) DEFAULT NULL,
  `potentials_mapped_to` int(19) DEFAULT NULL,
  PRIMARY KEY (`idcustom_field_mapping`),
  KEY `custfldmap_mapping_field_id` (`mapping_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_field_mapping`
--

LOCK TABLES `custom_field_mapping` WRITE;
/*!40000 ALTER TABLE `custom_field_mapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_field_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_view`
--

DROP TABLE IF EXISTS `custom_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_view` (
  `idcustom_view` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `idmodule` int(11) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  `is_public` tinyint(1) DEFAULT '0',
  `is_editable` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idcustom_view`),
  KEY `iduser_idx` (`iduser`),
  KEY `deleted_idx` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_view`
--

LOCK TABLES `custom_view` WRITE;
/*!40000 ALTER TABLE `custom_view` DISABLE KEYS */;
INSERT INTO `custom_view` VALUES (1,'All',1,2,1,0,1,0),(2,'All',1,3,1,0,1,0),(3,'All',1,4,1,0,1,0),(4,'All',1,5,1,0,1,0),(5,'All',1,6,1,0,1,0),(6,'All',1,11,1,0,1,0),(7,'All',1,12,1,0,1,0),(8,'All',1,13,1,0,1,0),(9,'All',1,14,1,0,1,0),(10,'All',1,15,1,0,1,0),(11,'All',1,16,1,0,1,0);
/*!40000 ALTER TABLE `custom_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_view_date_filter`
--

DROP TABLE IF EXISTS `custom_view_date_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_view_date_filter` (
  `idcustom_view_date_filter` int(11) NOT NULL AUTO_INCREMENT,
  `idcustom_view` int(11) DEFAULT NULL,
  `idfield` int(11) DEFAULT NULL,
  `filter_type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`idcustom_view_date_filter`),
  KEY `idcustom_view_idx` (`idcustom_view`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_view_date_filter`
--

LOCK TABLES `custom_view_date_filter` WRITE;
/*!40000 ALTER TABLE `custom_view_date_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_view_date_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_view_fields`
--

DROP TABLE IF EXISTS `custom_view_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_view_fields` (
  `idcustom_view_fields` int(11) NOT NULL AUTO_INCREMENT,
  `idcustom_view` int(11) DEFAULT NULL,
  `custom_view_fields` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idcustom_view_fields`),
  KEY `idcustom_view_idx` (`idcustom_view`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_view_fields`
--

LOCK TABLES `custom_view_fields` WRITE;
/*!40000 ALTER TABLE `custom_view_fields` DISABLE KEYS */;
INSERT INTO `custom_view_fields` VALUES (1,1,'123::122::127::129::131::133::132'),(2,2,'26::27::28::37::41'),(3,3,'75::76::85::82::78::94'),(4,4,'112::115::116::117::114::119'),(5,5,'48::49::50::52::59'),(6,6,'150::151::152::153::155'),(7,7,'165::167::169::172'),(8,8,'190::192::191::195'),(9,9,'226::225::231'),(10,10,'260::262::261::267'),(11,11,'296::297::299::298::301');
/*!40000 ALTER TABLE `custom_view_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_view_filter`
--

DROP TABLE IF EXISTS `custom_view_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_view_filter` (
  `idcustom_view_filter` int(11) NOT NULL AUTO_INCREMENT,
  `idcustom_view` int(11) DEFAULT NULL,
  `filter_type` int(11) DEFAULT NULL,
  `filter_field` int(11) DEFAULT NULL,
  `filter_value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idcustom_view_filter`),
  KEY `idcustom_view_idx` (`idcustom_view`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_view_filter`
--

LOCK TABLES `custom_view_filter` WRITE;
/*!40000 ALTER TABLE `custom_view_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_view_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_view_module_rel`
--

DROP TABLE IF EXISTS `custom_view_module_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_view_module_rel` (
  `idcustom_view_module_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idmodule` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcustom_view_module_rel`),
  KEY `idmodule_idx` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_view_module_rel`
--

LOCK TABLES `custom_view_module_rel` WRITE;
/*!40000 ALTER TABLE `custom_view_module_rel` DISABLE KEYS */;
INSERT INTO `custom_view_module_rel` VALUES (1,2),(2,3),(3,4),(4,5),(5,6),(6,11),(7,12),(8,13),(9,14),(10,15),(11,16);
/*!40000 ALTER TABLE `custom_view_module_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customfield_module_map`
--

DROP TABLE IF EXISTS `customfield_module_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customfield_module_map` (
  `idcustomfield_module_map` int(11) NOT NULL AUTO_INCREMENT,
  `idmodule` int(11) DEFAULT NULL,
  `table_name` varchar(200) DEFAULT NULL,
  `idblock` int(11) DEFAULT NULL,
  PRIMARY KEY (`idcustomfield_module_map`),
  KEY `idmodule_idx` (`idmodule`),
  KEY `idblock_idx` (`idblock`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customfield_module_map`
--

LOCK TABLES `customfield_module_map` WRITE;
/*!40000 ALTER TABLE `customfield_module_map` DISABLE KEYS */;
INSERT INTO `customfield_module_map` VALUES (1,2,'events_custom_fld',17),(2,3,'leads_custom_fld',6),(3,4,'contacts_custom_fld',13),(4,5,'potentials_custom_fld',15),(5,6,'organization_custom_fld',9),(6,11,'vendor_custom_fld',20),(7,12,'products_custom_fld',24),(8,13,'quotes_custom_fld',29),(9,14,'sales_order_custom_fld',30),(10,15,'invoice_custom_fld',33),(11,16,'purchase_order_custom_fld',36);
/*!40000 ALTER TABLE `customfield_module_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_history`
--

DROP TABLE IF EXISTS `data_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_history` (
  `iddata_history` int(19) NOT NULL AUTO_INCREMENT,
  `id_referrer` int(19) NOT NULL,
  `idmodule` int(19) NOT NULL,
  `iduser` int(19) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `idfields` int(19) DEFAULT NULL,
  `old_value` varchar(200) DEFAULT NULL,
  `new_value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`iddata_history`),
  KEY `datahty_iddata_history_idx` (`iddata_history`),
  KEY `datahty_idmodule_idx` (`idmodule`),
  KEY `datahty_iduser_idx` (`iduser`),
  KEY `datahty_idfields_idx` (`idfields`),
  KEY `datahty_id_referrer_idx` (`id_referrer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_history`
--

LOCK TABLES `data_history` WRITE;
/*!40000 ALTER TABLE `data_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_history_field_opt`
--

DROP TABLE IF EXISTS `data_history_field_opt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_history_field_opt` (
  `idmodule` int(11) NOT NULL DEFAULT '0',
  `idfields` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmodule`,`idfields`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_history_field_opt`
--

LOCK TABLES `data_history_field_opt` WRITE;
/*!40000 ALTER TABLE `data_history_field_opt` DISABLE KEYS */;
INSERT INTO `data_history_field_opt` VALUES (2,122),(2,123),(2,124),(2,125),(2,126),(2,127),(2,128),(2,129),(2,130),(2,131),(2,132),(3,26),(3,27),(3,28),(3,33),(3,34),(3,35),(3,37),(3,38),(3,39),(3,41),(5,112),(5,113),(5,114),(5,115),(5,116),(5,117),(5,118),(5,119),(5,121),(6,48),(6,49),(6,50),(6,52),(6,53),(6,56),(6,57),(6,58),(6,59),(6,60),(11,150),(11,151),(11,152),(11,153),(11,155),(12,165),(12,166),(12,167),(12,168),(12,169),(12,170),(12,172),(12,176),(13,182),(13,183),(13,184),(13,185),(13,186),(13,187),(14,216),(14,217),(14,218),(14,219),(14,220),(14,221),(14,222),(14,223),(15,252),(15,253),(15,254),(15,255),(15,256),(15,257),(15,258),(15,259),(16,288),(16,289),(16,290),(16,291),(16,292),(16,293);
/*!40000 ALTER TABLE `data_history_field_opt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datashare_standard_permission`
--

DROP TABLE IF EXISTS `datashare_standard_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datashare_standard_permission` (
  `iddatashare_standard_permission` int(19) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`iddatashare_standard_permission`),
  KEY `dsp_iddatashare_standard_permission_idx` (`iddatashare_standard_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datashare_standard_permission`
--

LOCK TABLES `datashare_standard_permission` WRITE;
/*!40000 ALTER TABLE `datashare_standard_permission` DISABLE KEYS */;
INSERT INTO `datashare_standard_permission` VALUES (1,'Public : Read Only','Users can Read other users'),(2,'Public : Read/Edit','Users can Read/Edit other users'),(3,'Public : Read/Edit/Delete','Users can Read/Edit/Delete other users'),(4,'Private','Users can not Read other users');
/*!40000 ALTER TABLE `datashare_standard_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emailtemplate`
--

DROP TABLE IF EXISTS `emailtemplate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emailtemplate` (
  `idemailtemplate` int(19) NOT NULL AUTO_INCREMENT,
  `subject` varchar(150) NOT NULL DEFAULT '',
  `bodytext` text NOT NULL,
  `bodyhtml` text NOT NULL,
  `name` varchar(254) NOT NULL DEFAULT '',
  `sendername` varchar(254) NOT NULL DEFAULT '',
  `senderemail` varchar(254) NOT NULL DEFAULT '',
  `thumbnail` varchar(70) NOT NULL DEFAULT '',
  `internal` varchar(10) NOT NULL DEFAULT '',
  `language` varchar(30) NOT NULL,
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`idemailtemplate`),
  KEY `emailt_idemailtemplate_idx` (`idemailtemplate`),
  KEY `emailt_iduser_idx` (`iduser`),
  KEY `emailt_name_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emailtemplate`
--

LOCK TABLES `emailtemplate` WRITE;
/*!40000 ALTER TABLE `emailtemplate` DISABLE KEYS */;
INSERT INTO `emailtemplate` VALUES (1,'[event_type] reminder :: [subject]','','[firstname],<br />\nYou have a [event_type] at [start_time] on [start_date] <br />\nPlease <a href=\"[event_url]\">click here</a> to see more details.<br />\nIf the above link does not work please copy and paste the following url on browser.<br />\n[event_url]<br /><br />\n[CRM_NAME]\n','event_reminder','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0),(2,'Quote [quote_num] from [company_name]','','Dear [firstname] [lastname],<br>\nPlease find the attached quote [quote_num] from [company_name]\n<br><br>\n[company_address]\n','send_quote_email','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0),(3,'Sales order [sales_order_num] from [company_name]','','Dear [firstname] [lastname],<br>\nPlease find the attached sales order [sales_order_num] from [company_name]\n<br><br>\n[company_address]\n','send_sales_order_email','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0),(4,'Invoice [invoice_number] from [company_name]','','Dear [firstname] [lastname],<br>\nPlease find the attached invoice [invoice_number] from [company_name]\n<br><br>\n[company_address]\n','send_invoice_email','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0),(5,'Purchase order [po_number] from [company_name]','','Dear [firstname] [lastname],<br>\nPlease find the attached purchase order [po_number] from [company_name]\n<br><br>\n[company_address]\n','send_purchase_order_email','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0),(6,'You have been mentioned on [module_name] note','','[firstname],<br />\nYou have been mentioned in the following note by [user_name] on a [module_name] <br /><br />\n[notes_content] <br /><br />\n[view_url]\n','send_notes_user_mentioned_email','sQcrm.com','donot_reply@sQcrm.com','','','en_US',1,0);
/*!40000 ALTER TABLE `emailtemplate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `idevents` int(19) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(100) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `description` text,
  `location` varchar(200) DEFAULT NULL,
  `priority` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` varchar(10) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `event_status` varchar(100) DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `parent_recurrent_event_id` int(19) DEFAULT '0',
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(19) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`idevents`),
  KEY `events_idevents_idx` (`idevents`),
  KEY `deleted_idx` (`deleted`),
  KEY `event_type_idx` (`event_type`),
  KEY `subject_idx` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_custom_fld`
--

DROP TABLE IF EXISTS `events_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_custom_fld` (
  `idevents_custom_fld` int(19) NOT NULL AUTO_INCREMENT,
  `idevents` int(19) NOT NULL,
  KEY `eventcf_idevents_custom_fld_idx` (`idevents_custom_fld`),
  KEY `eventcf_idevents_idx` (`idevents`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_custom_fld`
--

LOCK TABLES `events_custom_fld` WRITE;
/*!40000 ALTER TABLE `events_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_related_to`
--

DROP TABLE IF EXISTS `events_related_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_related_to` (
  `idevents_related_to` int(19) NOT NULL AUTO_INCREMENT,
  `idevents` int(19) NOT NULL,
  `related_to` int(19) NOT NULL,
  `idmodule` int(19) NOT NULL,
  PRIMARY KEY (`idevents_related_to`),
  KEY `evrelto_idevents_related_to_idx` (`idevents_related_to`),
  KEY `evrelto_idevents_idx` (`idevents`),
  KEY `evrelto_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_related_to`
--

LOCK TABLES `events_related_to` WRITE;
/*!40000 ALTER TABLE `events_related_to` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_related_to` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_reminder`
--

DROP TABLE IF EXISTS `events_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_reminder` (
  `idevents_reminder` int(19) NOT NULL AUTO_INCREMENT,
  `idevents` int(19) DEFAULT NULL,
  `days` int(2) DEFAULT NULL,
  `hours` int(2) DEFAULT NULL,
  `minutes` int(2) DEFAULT NULL,
  `email_ids` varchar(200) DEFAULT NULL,
  `reminder_send` int(1) DEFAULT '0',
  KEY `eremind_idevents_reminder_idx` (`idevents_reminder`),
  KEY `eremind_idevents_idx` (`idevents`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_reminder`
--

LOCK TABLES `events_reminder` WRITE;
/*!40000 ALTER TABLE `events_reminder` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_reminder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_to_grp_rel`
--

DROP TABLE IF EXISTS `events_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_to_grp_rel` (
  `idevents_to_grp_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idevents` int(19) NOT NULL,
  `idgroup` int(20) DEFAULT NULL,
  KEY `eventgrprel_idevents_to_grp_rel_idx` (`idevents_to_grp_rel`),
  KEY `eventgrprel_idevents_idx` (`idevents`),
  KEY `eventgrprel_idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_to_grp_rel`
--

LOCK TABLES `events_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `events_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feed_queue`
--

DROP TABLE IF EXISTS `feed_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed_queue` (
  `idfeed_queue` int(19) NOT NULL AUTO_INCREMENT,
  `idrecord` int(19) DEFAULT NULL,
  `idmodule` int(19) DEFAULT NULL,
  `identifier` varchar(200) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `iduser_for` int(19) DEFAULT NULL,
  `viewed` int(1) DEFAULT '0',
  `related_identifier` varchar(200) DEFAULT NULL,
  `related_identifier_idrecord` int(19) DEFAULT '0',
  `related_identifier_idmodule` int(19) DEFAULT '0',
  PRIMARY KEY (`idfeed_queue`),
  KEY `feed_queue_idnotes_idx` (`idfeed_queue`),
  KEY `feed_queue_iduser_idx` (`iduser`),
  KEY `feed_queue_iduser_for_idx` (`iduser_for`),
  KEY `feed_queue_viewed_idx` (`viewed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feed_queue`
--

LOCK TABLES `feed_queue` WRITE;
/*!40000 ALTER TABLE `feed_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `feed_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fields` (
  `idfields` int(19) NOT NULL AUTO_INCREMENT,
  `field_name` char(40) NOT NULL DEFAULT '',
  `field_label` varchar(200) DEFAULT NULL,
  `field_sequence` int(20) NOT NULL,
  `idblock` int(20) NOT NULL,
  `idmodule` int(20) NOT NULL,
  `table_name` varchar(200) NOT NULL DEFAULT '',
  `field_type` int(10) DEFAULT NULL,
  `field_validation` varchar(200) DEFAULT NULL,
  `is_editable` int(1) DEFAULT '0',
  `display` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idfields`),
  KEY `fields_idmodule_idx` (`idmodule`),
  KEY `fields_field_name_idx` (`field_name`),
  KEY `fields_idblock_idx` (`idblock`),
  KEY `fields_field_validation_idx` (`field_validation`)
) ENGINE=InnoDB AUTO_INCREMENT=322 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fields`
--

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
INSERT INTO `fields` VALUES (1,'user_name','User Name',1,1,7,'user',100,'{\"required\":true,\"minlength\":3,\"maxlength\":15,\"alphaNumericUnderscore\":true,\"unique\":true}',0,1),(2,'password','Password',2,1,7,'user',11,'{\"required\":true,\"minlength\":8}',0,1),(3,'firstname','Firstname',3,1,7,'user',1,'{\"required\":true}',0,1),(4,'lastname','Lastname',4,1,7,'user',1,'{\"required\":true}',0,1),(5,'email','Email Id',5,1,7,'user',7,'{\"required\":true}',0,1),(6,'is_admin','Is Admin',6,1,7,'user',3,'',0,1),(7,'idrole','Role',7,1,7,'user',103,'{\"required\":true}',0,1),(8,'reports_to','Reports To',8,1,7,'user',102,'',0,1),(9,'title','Title',1,2,7,'user',1,'',0,1),(10,'department','Department',2,2,7,'user',1,'',0,1),(11,'fax','Fax',3,2,7,'user',1,'',0,1),(12,'office_phone','Office Phone',4,2,7,'user',1,'',0,1),(13,'other_email','Other Email',5,2,7,'user',7,'',0,1),(14,'mobile_num','Mobile Number',6,2,7,'user',1,'',0,1),(15,'signature','Signature',7,2,7,'user',2,'',0,1),(16,'note','Note',8,2,7,'user',2,'',0,1),(17,'user_avatar','Avatar',9,2,7,'user',12,'',0,1),(18,'user_timezone','Time Zone',10,2,7,'user',104,'',0,1),(19,'date_view','Date View',11,2,7,'user',5,'',0,1),(20,'address','Address',1,3,7,'user',2,'',0,1),(21,'city','City',2,3,7,'user',1,'',0,1),(22,'state','State',3,3,7,'user',1,'',0,1),(23,'country','Country',4,3,7,'user',1,'',0,1),(24,'po','Post Office',5,3,7,'user',1,'',0,1),(25,'is_active','Is active',9,1,7,'user',5,'',0,1),(26,'firstname','First Name',1,4,3,'leads',1,'{\"required\":true}',0,1),(27,'lastname','Last Name',2,4,3,'leads',1,'{\"required\":true}',0,1),(28,'email','Email Id',3,4,3,'leads',7,'',0,1),(29,'phone','Phone',4,4,3,'leads',1,'',0,1),(30,'mobile','Mobile',5,4,3,'leads',1,'',0,1),(31,'title','Title',6,4,3,'leads',1,'',0,1),(32,'fax','Fax',7,4,3,'leads',1,'',0,1),(33,'leadsource','Lead Source',8,4,3,'leads',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(34,'industry','Industry',9,4,3,'leads',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(35,'organization','Organization',10,4,3,'leads',1,'{\"required\":true}',0,1),(36,'website','Website',11,4,3,'leads',8,'',0,1),(37,'lead_status','Lead Status',12,4,3,'leads',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(38,'anual_revenue','Anual Revenue',13,4,3,'leads',30,'',0,1),(39,'rating','Rating',14,4,3,'leads',5,'',0,1),(40,'description','Description',15,4,3,'leads',2,'',0,1),(41,'assigned_to','Assigned To',16,4,3,'leads',15,'',0,1),(42,'street','Street',1,5,3,'leads_address',2,'',0,1),(43,'po_box','PO Box',2,5,3,'leads_address',1,'',0,1),(44,'postal_code','Postal Code',3,5,3,'leads_address',1,'',0,1),(45,'country','Country',4,5,3,'leads_address',1,'',0,1),(46,'city','City',5,5,3,'leads_address',1,'',0,1),(47,'state','State',6,5,3,'leads_address',1,'',0,1),(48,'organization_name','Organization Name',1,7,6,'organization',1,'{\"required\":true}',0,1),(49,'website','Website',2,7,6,'organization',8,'',0,1),(50,'phone','Phone',3,7,6,'organization',1,'',0,1),(51,'fax','Fax',4,7,6,'organization',1,'',0,1),(52,'member_of','Member Of',5,7,6,'organization',131,'',0,1),(53,'num_employes','Number of Employees',6,7,6,'organization',1,'',0,1),(54,'sis_code','SIC Code',7,7,6,'organization',1,'',0,1),(55,'ticker_symbol','Ticker Symbol',8,7,6,'organization',1,'',0,1),(56,'industry','Industry',9,7,6,'organization',5,'',0,1),(57,'rating','Rating',10,7,6,'organization',5,'',0,1),(58,'annual_revenue','Annual Revenue',11,7,6,'organization',30,'',0,1),(59,'assigned_to','Assigned To',12,7,6,'organization',15,'',0,1),(60,'industry_type','Type',13,7,6,'organization',5,'',0,1),(61,'email_opt_out','Email Opt Out',14,7,6,'organization',3,'',0,1),(62,'description','Description',15,7,6,'organization',2,'',0,1),(63,'org_bill_address','Billing Address',1,8,6,'organization_address',2,'',0,1),(64,'org_ship_address','Shipping Address',2,8,6,'organization_address',2,'',0,1),(65,'org_bill_pobox','Billing PO Box',3,8,6,'organization_address',1,'',0,1),(66,'org_ship_pobox','Shipping PO Box',4,8,6,'organization_address',1,'',0,1),(67,'org_bill_city','Billing City',5,8,6,'organization_address',1,'',0,1),(68,'org_ship_city','Shipping City',6,8,6,'organization_address',1,'',0,1),(69,'org_bill_state','Billing State',7,8,6,'organization_address',1,'',0,1),(70,'org_ship_state','Shipping State',8,8,6,'organization_address',1,'',0,1),(71,'org_bill_postalcode','Billing Postal Code',9,8,6,'organization_address',1,'',0,1),(72,'org_ship_postalcode','Shipping Postal Code',10,8,6,'organization_address',1,'',0,1),(73,'org_bill_country','Billing Country',11,8,6,'organization_address',1,'',0,1),(74,'org_ship_country','Shipping Country',12,8,6,'organization_address',1,'',0,1),(75,'firstname','First Name',1,10,4,'contacts',1,'{\"required\":true}',0,1),(76,'lastname','Last Name',2,10,4,'contacts',1,'{\"required\":true}',0,1),(77,'office_phone','Office Phone',3,10,4,'contacts',1,'',0,1),(78,'idorganization','Organization Name',4,10,4,'contacts',131,'',0,1),(79,'mobile_num','Mobile Number',5,10,4,'contacts',1,'',0,1),(80,'leadsource','Lead Source',6,10,4,'contacts',5,'',0,1),(81,'home_phone','Home Phone',7,10,4,'contacts',1,'',0,1),(82,'title','Title',8,10,4,'contacts',1,'',0,1),(83,'other_phone','Other Phone',9,10,4,'contacts',1,'',0,1),(84,'department','Department',10,10,4,'contacts',1,'',0,1),(85,'email','Email',11,10,4,'contacts',7,'',0,1),(86,'fax','Fax',12,10,4,'contacts',1,'',0,1),(87,'date_of_birth','Date of birth',13,10,4,'contacts',9,'',0,1),(88,'assistant','Assistant',14,10,4,'contacts',1,'',0,1),(89,'assistant_phone','Assistant Phone',15,10,4,'contacts',1,'',0,1),(90,'reports_to','Reports To',16,10,4,'contacts',130,'',0,1),(91,'secondary_email','Secondary Email',17,10,4,'contacts',7,'',0,1),(92,'email_opt_out','Email Opt Out',18,10,4,'contacts',3,'',0,1),(93,'do_not_call','Do not call',19,10,4,'contacts',3,'',0,1),(94,'assigned_to','Assigned To',20,10,4,'contacts',15,'',0,1),(95,'description','Description',21,10,4,'contacts',2,'',0,1),(96,'contact_avatar','Avatar',22,10,4,'contacts',12,'',0,1),(97,'portal_user','Portal User',1,11,4,'contacts',3,'',0,1),(98,'support_start_date','Support Start Date',2,11,4,'contacts',9,'',0,1),(99,'support_end_date','Support End Date',3,11,4,'contacts',9,'',0,1),(100,'cnt_mail_street','Mailing Street',1,12,4,'contacts_address',2,'',0,1),(101,'cnt_other_street','Other Street',2,12,4,'contacts_address',2,'',0,1),(102,'cnt_mail_pobox','Mailing PO Box',3,12,4,'contacts_address',1,'',0,1),(103,'cnt_other_pobox','Other PO Box',4,12,4,'contacts_address',1,'',0,1),(104,'cnt_mailing_city','Mailing City',5,12,4,'contacts_address',1,'',0,1),(105,'cnt_other_city','Other City',6,12,4,'contacts_address',1,'',0,1),(106,'cnt_mailing_state','Mailing State',7,12,4,'contacts_address',1,'',0,1),(107,'cnt_other_state','Other State',8,12,4,'contacts_address',1,'',0,1),(108,'cnt_mailing_postalcode','Mailing Postal Code',9,12,4,'contacts_address',1,'',0,1),(109,'cnt_other_postalcode','Other Postal Code',10,12,4,'contacts_address',1,'',0,1),(110,'cnt_mailing_country','Mailing Country',11,12,4,'contacts_address',1,'',0,1),(111,'cnt_other_country','Other Country',11,12,4,'contacts_address',1,'',0,1),(112,'potential_name','Prospect Name',1,14,5,'potentials',1,'{\"required\":true}',0,1),(113,'potential_type','Type',2,14,5,'potentials',5,'',0,1),(114,'related_to','Related To',3,14,5,'potentials_related_to',150,'{\"required\":true}',0,1),(115,'expected_closing_date','Expected Closing Date',4,14,5,'potentials',9,'{\"required\":true}',0,1),(116,'leadsource','Lead Source',5,14,5,'potentials',5,'',0,1),(117,'sales_stage','Sales Stage',6,14,5,'potentials',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(118,'probability','Probability',7,14,5,'potentials',1,'',0,1),(119,'assigned_to','Assigned To',9,14,5,'potentials',15,'',0,1),(120,'description','Description',10,14,5,'potentials',2,'',0,1),(121,'amount','Amount',8,14,5,'potentials',30,'{\"required\":true}',0,1),(122,'event_type','Event Type',1,16,2,'events',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(123,'subject','Subject',2,16,2,'events',1,'{\"required\":true}',0,1),(124,'description','Description',3,16,2,'events',2,'',0,1),(125,'location','Location',4,16,2,'events',1,'',0,1),(126,'priority','Priority',9,16,2,'events',5,'',0,1),(127,'start_date','Start Date',5,16,2,'events',9,'{\"required\":true}',0,1),(128,'start_time','Start Time',6,16,2,'events',10,'',0,1),(129,'end_date','End Date',7,16,2,'events',9,'{\"required\":true}',0,1),(130,'end_time','End Time',8,16,2,'events',10,'',0,1),(131,'event_status','Event Status',10,16,2,'events',5,'{\"required\":true,\"notEqual\":\"Pick One\"}',0,1),(132,'assigned_to','Assigned To',11,16,2,'events',15,'',0,1),(133,'related_to','Related To',12,16,2,'events_related_to',151,'',0,1),(134,'added_on','Lead Date Added',0,0,3,'leads',9,NULL,0,0),(135,'last_modified','Lead Last Modified Date',0,0,3,'leads',9,NULL,0,0),(136,'converted','Converted',0,0,3,'leads',1,NULL,0,0),(137,'idleads','Lead Id',0,4,3,'leads',1,NULL,0,0),(138,'added_on','Contact Date Added',0,0,4,'contacts',9,NULL,0,0),(139,'last_modified','Contact Last Modified Date',0,0,4,'contacts',9,NULL,0,0),(140,'idcontacts','Contact Id',0,10,4,'contacts',1,NULL,0,0),(141,'added_on','Event Date Added',0,0,2,'events',9,NULL,0,0),(142,'last_modified','Event Last Modified Date',0,0,2,'events',9,NULL,0,0),(143,'idevents','Event Id',0,16,2,'events',1,NULL,0,0),(144,'added_on','Prospect Date Added',0,0,5,'potentials',9,NULL,0,0),(145,'last_modified','Prospect Last Modified Date',0,0,5,'potentials',9,NULL,0,0),(146,'idpotentials','Prospect Id',0,14,5,'potentials',1,NULL,0,0),(147,'added_on','Organization Date Added',0,0,6,'organization',9,NULL,0,0),(148,'last_modified','Organization Last Modified Date',0,0,6,'organization',9,NULL,0,0),(149,'idorganization','Oranization Id',0,7,6,'organization',1,NULL,0,0),(150,'vendor_name','Vendor Name',1,18,11,'vendor',1,'{\"required\":true}',0,1),(151,'email','Email Id',2,18,11,'vendor',7,'',0,1),(152,'phone','Phone number',3,18,11,'vendor',1,'',0,1),(153,'website','Website',4,18,11,'vendor',8,'',0,1),(154,'description','Description',5,18,11,'vendor',2,'',0,1),(155,'assigned_to','Assigned To',6,18,11,'vendor',15,'',0,1),(156,'added_on','Vendor Date Added',0,0,11,'vendor',9,NULL,0,0),(157,'last_modified','Vendor Last Modified Date',0,0,11,'vendor',9,NULL,0,0),(158,'idvendor','Vendor Id',0,18,11,'vendor',1,NULL,0,0),(159,'vendor_street','Street',1,19,11,'vendor_address',2,'',0,1),(160,'vendor_city','City',2,19,11,'vendor_address',1,'',0,1),(161,'vendor_postal_code','Postal Code',3,19,11,'vendor_address',1,'',0,1),(162,'vendor_po_box','PO Box',4,19,11,'vendor_address',1,'',0,1),(163,'vendor_state','State',5,19,11,'vendor_address',1,'',0,1),(164,'vendor_country','Country',6,19,11,'vendor_address',1,'',0,1),(165,'product_name','Product Name',1,21,12,'products',1,'{\"required\":true}',0,1),(166,'is_active','Active',2,21,12,'products',3,'',0,1),(167,'product_category','Category',3,21,12,'products',5,'',0,1),(168,'manufacturer','Manufacturer',4,21,12,'products',5,'',0,1),(169,'idvendor','Vendor',5,21,12,'products',160,'',0,1),(170,'website','Website',6,21,12,'products',8,'',0,1),(171,'description','Description',7,21,12,'products',2,'',0,1),(172,'assigned_to','Assigned To',8,21,12,'products',15,'',0,1),(173,'added_on','Product Date Added',0,0,12,'products',9,NULL,0,0),(174,'last_modified','Product Last Modified Date',0,0,12,'products',9,NULL,0,0),(175,'idproducts','Product Id',0,21,12,'products',1,NULL,0,0),(176,'product_price','Price',1,22,12,'products_pricing',30,'',0,1),(177,'commission_rate','Commision Rate (%)',2,22,12,'products_pricing',16,'',0,1),(178,'tax_value','Tax Information',3,22,12,'products_tax',165,'',0,1),(179,'unit_quantity','Unit Quantity',1,23,12,'products_stock',16,'',0,1),(180,'quantity_in_stock','Quantity in stock',2,23,12,'products_stock',16,'',0,1),(181,'quantity_in_demand','Quantity in demand',3,23,12,'products_stock',16,'',0,1),(182,'subject','Subject',1,25,13,'quotes',1,'{\"required\":true}',0,1),(183,'quote_stage','Quotes Stage',2,25,13,'quotes',5,'{\"required\":true}',0,1),(184,'idorganization','Organization',3,25,13,'quotes',141,'{\"required\":true}',0,1),(185,'idpotentials','Potential Name',4,25,13,'quotes',133,'',0,1),(186,'valid_till','Valid Till',5,25,13,'quotes',9,'',0,1),(187,'assigned_to','Assigned To',6,25,13,'quotes',15,'',0,1),(188,'description','Description',7,25,13,'quotes',2,'',0,1),(189,'added_on','Quotes Date Added',0,0,13,'quotes',9,NULL,0,0),(190,'last_modified','Quotes Last Modified Date',0,0,13,'quotes',9,NULL,0,0),(191,'idquotes','Quotes Id',0,25,13,'quotes',1,NULL,0,0),(192,'net_total','Net Total',0,25,13,'quotes',30,NULL,0,0),(193,'discount_type','Discount Type',0,25,13,'quotes',1,NULL,0,0),(194,'discount_value','Discount Value',0,25,13,'quotes',30,NULL,0,0),(195,'discounted_amount','Discounted Amount',0,25,13,'quotes',30,NULL,0,0),(196,'tax_values','Tax Values',0,25,13,'quotes',1,NULL,0,0),(197,'taxed_amount','Taxed Amount',0,25,13,'quotes',30,NULL,0,0),(198,'shipping_handling_charge','Shipping and Handling Charges',0,25,13,'quotes',30,NULL,0,0),(199,'shipping_handling_tax_values','Shipping/Handling Tax Values',0,25,13,'quotes',1,NULL,0,0),(200,'shipping_handling_taxed_amount','Shipping/Handling Taxed Amount',0,25,13,'quotes',30,NULL,0,0),(201,'final_adjustment_type','Final Adjustment Type',0,25,13,'quotes',1,NULL,0,0),(202,'final_adjustment_amount','Final Adjustment Amount',0,25,13,'quotes',30,NULL,0,0),(203,'grand_total','Grand Total',0,25,13,'quotes',30,NULL,0,0),(204,'q_billing_address','Billing Address',1,26,13,'quotes_address',2,'',0,1),(205,'q_shipping_address','Shipping Address',2,26,13,'quotes_address',2,'',0,1),(206,'q_billing_po_box','Billing PO Box',3,26,13,'quotes_address',1,'',0,1),(207,'q_shipping_po_box','Shipping PO Box',4,26,13,'quotes_address',1,'',0,1),(208,'q_billing_po_code','Billing Code',5,26,13,'quotes_address',1,'',0,1),(209,'q_shipping_po_code','Shipping Code',6,26,13,'quotes_address',1,'',0,1),(210,'q_billing_city','Billing City',7,26,13,'quotes_address',1,'',0,1),(211,'q_shipping_city','Shipping City',8,26,13,'quotes_address',1,'',0,1),(212,'q_billing_state','Billing State',9,26,13,'quotes_address',1,'',0,1),(213,'q_shipping_state','Shipping State',10,26,13,'quotes_address',1,'',0,1),(214,'q_billing_country','Billing Country',11,26,13,'quotes_address',1,'',0,1),(215,'q_shipping_country','Shipping Country',12,26,13,'quotes_address',1,'',0,1),(216,'subject','Subject',1,27,14,'sales_order',1,'{\"required\":true}',0,1),(217,'sales_order_status','Status',2,27,14,'sales_order',5,'{\"required\":true}',0,1),(218,'idorganization','Organization',3,27,14,'sales_order',141,'{\"required\":true}',0,1),(219,'idpotentials','Potential Name',4,27,14,'sales_order',133,'',0,1),(220,'idcontacts','Contact Name',5,27,14,'sales_order',142,'',0,1),(221,'idquotes','Quote Name',6,27,14,'sales_order',170,'',0,1),(222,'due_date','Due Date',7,27,14,'sales_order',9,'',0,1),(223,'assigned_to','Assigned To',8,27,14,'sales_order',15,'',0,1),(224,'description','Description',9,27,14,'sales_order',2,'',0,1),(225,'added_on','Sales Order Date Added',0,0,14,'sales_order',9,NULL,0,0),(226,'last_modified','Sales Order Last Modified Date',0,0,14,'sales_order',9,NULL,0,0),(227,'idsales_order','Sales Order Id',0,27,14,'sales_order',1,NULL,0,0),(228,'net_total','Net Total',0,27,14,'sales_order',30,NULL,0,0),(229,'discount_type','Discount Type',0,27,14,'sales_order',1,NULL,0,0),(230,'discount_value','Discount Value',0,27,14,'sales_order',30,NULL,0,0),(231,'discounted_amount','Discounted Amount',0,27,14,'sales_order',30,NULL,0,0),(232,'tax_values','Tax Values',0,27,14,'sales_order',1,NULL,0,0),(233,'taxed_amount','Taxed Amount',0,27,14,'sales_order',30,NULL,0,0),(234,'shipping_handling_charge','Shipping and Handling Charges',0,27,14,'sales_order',30,NULL,0,0),(235,'shipping_handling_tax_values','Shipping/Handling Tax Values',0,27,14,'sales_order',1,NULL,0,0),(236,'shipping_handling_taxed_amount','Shipping/Handling Taxed Amount',0,27,14,'sales_order',30,NULL,0,0),(237,'final_adjustment_type','Final Adjustment Type',0,27,14,'sales_order',1,NULL,0,0),(238,'final_adjustment_amount','Final Adjustment Amount',0,27,14,'sales_order',30,NULL,0,0),(239,'grand_total','Grand Total',0,27,14,'sales_order',30,NULL,0,0),(240,'so_billing_address','Billing Address',1,28,14,'sales_order_address',2,'',0,1),(241,'so_shipping_address','Shipping Address',2,28,14,'sales_order_address',2,'',0,1),(242,'so_billing_po_box','Billing PO Box',3,28,14,'sales_order_address',1,'',0,1),(243,'so_shipping_po_box','Shipping PO Box',4,28,14,'sales_order_address',1,'',0,1),(244,'so_billing_po_code','Billing Code',5,28,14,'sales_order_address',1,'',0,1),(245,'so_shipping_po_code','Shipping Code',6,28,14,'sales_order_address',1,'',0,1),(246,'so_billing_city','Billing City',7,28,14,'sales_order_address',1,'',0,1),(247,'so_shipping_city','Shipping City',8,28,14,'sales_order_address',1,'',0,1),(248,'so_billing_state','Billing State',9,28,14,'sales_order_address',1,'',0,1),(249,'so_shipping_state','Shipping State',10,28,14,'sales_order_address',1,'',0,1),(250,'so_billing_country','Billing Country',11,28,14,'sales_order_address',1,'',0,1),(251,'so_shipping_country','Shipping Country',12,28,14,'sales_order_address',1,'',0,1),(252,'subject','Subject',1,31,15,'invoice',1,'{\"required\":true}',0,1),(253,'invoice_status','Invoice Status',2,31,15,'invoice',5,'{\"required\":true}',0,1),(254,'idorganization','Organization',3,31,15,'invoice',141,'{\"required\":true}',0,1),(255,'idpotentials','Potential Name',4,31,15,'invoice',133,'',0,1),(256,'idcontacts','Contact Name',5,31,15,'invoice',142,'',0,1),(257,'idsales_order','Sales Order',6,31,15,'invoice',180,'',0,1),(258,'due_date','Due Date',7,31,15,'invoice',9,'',0,1),(259,'assigned_to','Assigned To',8,31,15,'invoice',15,'',0,1),(260,'description','Description',9,31,15,'invoice',2,'',0,1),(261,'added_on','Invoice Date Added',0,0,15,'invoice',9,NULL,0,0),(262,'last_modified','Invoice Last Modified Date',0,0,15,'invoice',9,NULL,0,0),(263,'idinvoice','Invoice Id',0,31,15,'invoice',1,NULL,0,0),(264,'net_total','Net Total',0,31,15,'invoice',30,NULL,0,0),(265,'discount_type','Discount Type',0,31,15,'invoice',1,NULL,0,0),(266,'discount_value','Discount Value',0,31,15,'invoice',30,NULL,0,0),(267,'discounted_amount','Discounted Amount',0,31,15,'invoice',30,NULL,0,0),(268,'tax_values','Tax Values',0,31,15,'invoice',1,NULL,0,0),(269,'taxed_amount','Taxed Amount',0,31,15,'invoice',30,NULL,0,0),(270,'shipping_handling_charge','Shipping and Handling Charges',0,31,15,'invoice',30,NULL,0,0),(271,'shipping_handling_tax_values','Shipping/Handling Tax Values',0,31,15,'invoice',1,NULL,0,0),(272,'shipping_handling_taxed_amount','Shipping/Handling Taxed Amount',0,31,15,'invoice',30,NULL,0,0),(273,'final_adjustment_type','Final Adjustment Type',0,31,15,'invoice',1,NULL,0,0),(274,'final_adjustment_amount','Final Adjustment Amount',0,31,15,'invoice',30,NULL,0,0),(275,'grand_total','Grand Total',0,31,15,'invoice',30,NULL,0,0),(276,'inv_billing_address','Billing Address',1,32,15,'invoice_address',2,'',0,1),(277,'inv_shipping_address','Shipping Address',2,32,15,'invoice_address',2,'',0,1),(278,'inv_billing_po_box','Billing PO Box',3,32,15,'invoice_address',1,'',0,1),(279,'inv_shipping_po_box','Shipping PO Box',4,32,15,'invoice_address',1,'',0,1),(280,'inv_billing_po_code','Billing Code',5,32,15,'invoice_address',1,'',0,1),(281,'inv_shipping_po_code','Shipping Code',6,32,15,'invoice_address',1,'',0,1),(282,'inv_billing_city','Billing City',7,32,15,'invoice_address',1,'',0,1),(283,'inv_shipping_city','Shipping City',8,32,15,'invoice_address',1,'',0,1),(284,'inv_billing_state','Billing State',9,32,15,'invoice_address',1,'',0,1),(285,'inv_shipping_state','Shipping State',10,32,15,'invoice_address',1,'',0,1),(286,'inv_billing_country','Billing Country',11,32,15,'invoice_address',1,'',0,1),(287,'inv_shipping_country','Shipping Country',12,32,15,'invoice_address',1,'',0,1),(288,'po_subject','Subject',1,34,16,'purchase_order',1,'{\"required\":true}',0,1),(289,'po_status','Purchase Order Status',2,34,16,'purchase_order',5,'{\"required\":true}',0,1),(290,'idvendor','Vendor',3,34,16,'purchase_order',160,'',0,1),(291,'idcontacts','Contact',4,34,16,'purchase_order',143,'',0,1),(292,'due_date','Due Date',5,34,16,'purchase_order',9,'',0,1),(293,'assigned_to','Assigned To',6,34,16,'purchase_order',15,'',0,1),(294,'description','Description',7,34,16,'purchase_order',2,'',0,1),(295,'added_on','Purchase Order Date Added',0,0,16,'purchase_order',9,NULL,0,0),(296,'last_modified','Purchase Order Last Modified Date',0,0,16,'purchase_order',9,NULL,0,0),(297,'idpurchase_order','Purchase Order Id',0,34,16,'purchase_order',1,NULL,0,0),(298,'net_total','Net Total',0,34,16,'purchase_order',30,NULL,0,0),(299,'discount_type','Discount Type',0,34,16,'purchase_order',1,NULL,0,0),(300,'discount_value','Discount Value',0,34,16,'purchase_order',30,NULL,0,0),(301,'discounted_amount','Discounted Amount',0,34,16,'purchase_order',30,NULL,0,0),(302,'tax_values','Tax Values',0,34,16,'purchase_order',1,NULL,0,0),(303,'taxed_amount','Taxed Amount',0,34,16,'purchase_order',30,NULL,0,0),(304,'shipping_handling_charge','Shipping and Handling Charges',0,34,16,'purchase_order',30,NULL,0,0),(305,'shipping_handling_tax_values','Shipping/Handling Tax Values',0,34,16,'purchase_order',1,NULL,0,0),(306,'shipping_handling_taxed_amount','Shipping/Handling Taxed Amount',0,34,16,'purchase_order',30,NULL,0,0),(307,'final_adjustment_type','Final Adjustment Type',0,34,16,'purchase_order',1,NULL,0,0),(308,'final_adjustment_amount','Final Adjustment Amount',0,34,16,'purchase_order',30,NULL,0,0),(309,'grand_total','Grand Total',0,34,16,'purchase_order',30,NULL,0,0),(310,'po_billing_address','Billing Address',1,35,16,'purchase_order_address',2,'',0,1),(311,'po_shipping_address','Shipping Address',2,35,16,'purchase_order_address',2,'',0,1),(312,'po_billing_po_box','Billing PO Box',3,35,16,'purchase_order_address',1,'',0,1),(313,'po_shipping_po_box','Shipping PO Box',4,35,16,'purchase_order_address',1,'',0,1),(314,'po_billing_po_code','Billing Code',5,35,16,'purchase_order_address',1,'',0,1),(315,'po_shipping_po_code','Shipping Code',6,35,16,'purchase_order_address',1,'',0,1),(316,'po_billing_city','Billing City',7,35,16,'purchase_order_address',1,'',0,1),(317,'po_shipping_city','Shipping City',8,35,16,'purchase_order_address',1,'',0,1),(318,'po_billing_state','Billing State',9,35,16,'purchase_order_address',1,'',0,1),(319,'po_shipping_state','Shipping State',10,35,16,'purchase_order_address',1,'',0,1),(320,'po_billing_country','Billing Country',11,35,16,'purchase_order_address',1,'',0,1),(321,'po_shipping_country','Shipping Country',12,35,16,'purchase_order_address',1,'',0,1);
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fields_mapping`
--

DROP TABLE IF EXISTS `fields_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fields_mapping` (
  `idfields_mapping` int(19) NOT NULL AUTO_INCREMENT,
  `idfields` int(19) NOT NULL,
  `mapped_to` int(19) NOT NULL,
  PRIMARY KEY (`idfields_mapping`),
  KEY `fldmap_idfields_mapping_idx` (`idfields_mapping`),
  KEY `fldmap_idfields_idx` (`idfields`),
  KEY `fldmap_mapped_to_idx` (`idfields`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fields_mapping`
--

LOCK TABLES `fields_mapping` WRITE;
/*!40000 ALTER TABLE `fields_mapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `fields_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_uploads`
--

DROP TABLE IF EXISTS `file_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_uploads` (
  `idfile_uploads` int(19) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(200) NOT NULL,
  `file_mime` varchar(50) NOT NULL,
  `file_size` int(10) NOT NULL,
  `file_extension` varchar(10) NOT NULL,
  `idmodule` int(19) NOT NULL,
  `id_referrer` int(19) NOT NULL,
  `iduser` int(19) NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `file_description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idfile_uploads`),
  KEY `file_idmodule_idx` (`idmodule`),
  KEY `file_file_name_idx` (`file_name`),
  KEY `file_idreferrer_idx` (`id_referrer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_uploads`
--

LOCK TABLES `file_uploads` WRITE;
/*!40000 ALTER TABLE `file_uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `global_permission`
--

DROP TABLE IF EXISTS `global_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_permission` (
  `idglobal_permission` int(19) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(100) NOT NULL,
  PRIMARY KEY (`idglobal_permission`),
  KEY `global_permission_idglobal_permission_idx` (`idglobal_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `global_permission`
--

LOCK TABLES `global_permission` WRITE;
/*!40000 ALTER TABLE `global_permission` DISABLE KEYS */;
INSERT INTO `global_permission` VALUES (1,'View All'),(2,'Edit All');
/*!40000 ALTER TABLE `global_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `idgroup` int(19) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) NOT NULL,
  `description` text,
  PRIMARY KEY (`idgroup`),
  KEY `grp_idgroup_idx` (`idgroup`),
  KEY `grp_group_name_idx` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,'Test Group','This is a test group for the CRM.');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_user_rel`
--

DROP TABLE IF EXISTS `group_user_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_user_rel` (
  `idgroup_user_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idgroup` int(19) NOT NULL,
  `iduser` int(19) NOT NULL,
  PRIMARY KEY (`idgroup_user_rel`),
  KEY `grpurel_idgroup_user_rel_idx` (`idgroup_user_rel`),
  KEY `grpurel_idgroup_idx` (`idgroup`),
  KEY `grpurel_iduser_idx` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_user_rel`
--

LOCK TABLES `group_user_rel` WRITE;
/*!40000 ALTER TABLE `group_user_rel` DISABLE KEYS */;
INSERT INTO `group_user_rel` VALUES (2,1,2);
/*!40000 ALTER TABLE `group_user_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homepage_component`
--

DROP TABLE IF EXISTS `homepage_component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homepage_component` (
  `idhomepage_component` int(19) NOT NULL AUTO_INCREMENT,
  `position` varchar(20) DEFAULT NULL,
  `component_name` varchar(200) DEFAULT NULL,
  `sequence` int(3) DEFAULT NULL,
  PRIMARY KEY (`idhomepage_component`),
  KEY `hmpage_component_idhomepage_component_idx` (`idhomepage_component`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homepage_component`
--

LOCK TABLES `homepage_component` WRITE;
/*!40000 ALTER TABLE `homepage_component` DISABLE KEYS */;
INSERT INTO `homepage_component` VALUES (1,'right','Live Work Feed',1),(2,'right','Calls and Meetings',2),(3,'left','Prospect Pipeline By Sales Stage',1),(4,'left','Leads By Leads Status',2),(5,'left','Prospects By Sales Stage',3);
/*!40000 ALTER TABLE `homepage_component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import`
--

DROP TABLE IF EXISTS `import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import` (
  `idimport` int(19) NOT NULL AUTO_INCREMENT,
  `idmodule` int(19) DEFAULT NULL,
  `idrecord` int(19) DEFAULT NULL,
  `date_imported` date DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  PRIMARY KEY (`idimport`),
  KEY `import_idimport_idx` (`idimport`),
  KEY `import_idmodule_idx` (`idmodule`),
  KEY `import_idrecord_idx` (`idrecord`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import`
--

LOCK TABLES `import` WRITE;
/*!40000 ALTER TABLE `import` DISABLE KEYS */;
/*!40000 ALTER TABLE `import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_map`
--

DROP TABLE IF EXISTS `import_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `import_map` (
  `idimport_map` int(19) NOT NULL AUTO_INCREMENT,
  `map_name` varchar(200) DEFAULT NULL,
  `idmodule` int(19) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `map_data` text,
  PRIMARY KEY (`idimport_map`),
  KEY `importmap_idimport_map_idx` (`idimport_map`),
  KEY `importmap_idmodule_map_idx` (`idmodule`),
  KEY `importmap_iduser_map_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_map`
--

LOCK TABLES `import_map` WRITE;
/*!40000 ALTER TABLE `import_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `idinvoice` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) DEFAULT NULL,
  `idorganization` int(11) DEFAULT NULL,
  `idcontacts` int(11) DEFAULT NULL,
  `idpotentials` int(11) DEFAULT NULL,
  `idsales_order` int(11) DEFAULT NULL,
  `description` text,
  `terms_condition` text,
  `invoice_status` varchar(200) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `invoice_number` int(11) DEFAULT NULL,
  `invoice_key` varchar(40) DEFAULT NULL,
  `net_total` float(10,3) DEFAULT NULL,
  `discount_type` varchar(100) DEFAULT NULL,
  `discount_value` float(10,3) DEFAULT NULL,
  `discounted_amount` float(10,3) DEFAULT NULL,
  `tax_values` varchar(200) DEFAULT NULL,
  `taxed_amount` float(10,3) DEFAULT NULL,
  `shipping_handling_charge` float(10,3) DEFAULT NULL,
  `shipping_handling_tax_values` varchar(200) DEFAULT NULL,
  `shipping_handling_taxed_amount` float(10,3) DEFAULT NULL,
  `final_adjustment_type` varchar(100) DEFAULT NULL,
  `final_adjustment_amount` float(10,3) DEFAULT NULL,
  `grand_total` float(10,3) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idinvoice`),
  KEY `deleted_idx` (`deleted`),
  KEY `invoice_key_idx` (`invoice_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_address`
--

DROP TABLE IF EXISTS `invoice_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_address` (
  `idinvoice_address` int(11) NOT NULL AUTO_INCREMENT,
  `idinvoice` int(11) DEFAULT NULL,
  `inv_billing_address` text,
  `inv_billing_po_box` varchar(200) DEFAULT NULL,
  `inv_billing_po_code` varchar(200) DEFAULT NULL,
  `inv_billing_city` varchar(200) DEFAULT NULL,
  `inv_billing_state` varchar(200) DEFAULT NULL,
  `inv_billing_country` varchar(200) DEFAULT NULL,
  `inv_shipping_address` text,
  `inv_shipping_po_box` varchar(200) DEFAULT NULL,
  `inv_shipping_po_code` varchar(200) DEFAULT NULL,
  `inv_shipping_city` varchar(200) DEFAULT NULL,
  `inv_shipping_state` varchar(200) DEFAULT NULL,
  `inv_shipping_country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idinvoice_address`),
  KEY `idinvoice_idx` (`idinvoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_address`
--

LOCK TABLES `invoice_address` WRITE;
/*!40000 ALTER TABLE `invoice_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_custom_fld`
--

DROP TABLE IF EXISTS `invoice_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_custom_fld` (
  `idinvoice_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idinvoice` int(11) NOT NULL,
  PRIMARY KEY (`idinvoice_custom_fld`),
  KEY `idinvoice_idx` (`idinvoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_custom_fld`
--

LOCK TABLES `invoice_custom_fld` WRITE;
/*!40000 ALTER TABLE `invoice_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_to_grp_rel`
--

DROP TABLE IF EXISTS `invoice_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_to_grp_rel` (
  `idinvoice_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idinvoice` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idinvoice_to_grp_rel`),
  KEY `idinvoice_idx` (`idinvoice`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_to_grp_rel`
--

LOCK TABLES `invoice_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `invoice_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `idleads` int(19) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `leadsource` varchar(50) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `lead_status` varchar(200) DEFAULT NULL,
  `anual_revenue` float(10,3) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `description` text,
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `converted` int(1) DEFAULT '0',
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(19) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`idleads`),
  KEY `leads_idleads_idx` (`idleads`),
  KEY `leads_iduser_idx` (`iduser`),
  KEY `leads_firstname_idx` (`firstname`),
  KEY `leads_lastname_idx` (`lastname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads_address`
--

DROP TABLE IF EXISTS `leads_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_address` (
  `idleads_address` int(19) NOT NULL AUTO_INCREMENT,
  `idleads` int(19) NOT NULL,
  `street` text,
  `po_box` varchar(50) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  KEY `leadsaddr_idleads_address_idx` (`idleads_address`),
  KEY `leads_idleads_idx` (`idleads`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads_address`
--

LOCK TABLES `leads_address` WRITE;
/*!40000 ALTER TABLE `leads_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads_conversion_matrix`
--

DROP TABLE IF EXISTS `leads_conversion_matrix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_conversion_matrix` (
  `idleads_conversion_matrix` int(19) NOT NULL AUTO_INCREMENT,
  `idpotentials` int(19) DEFAULT '0',
  `idcontacts` int(19) DEFAULT '0',
  `idorganization` int(19) DEFAULT '0',
  `idleads` int(19) DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `conversion_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idleads_conversion_matrix`),
  KEY `leadsconv_idleads_conversion_matrix_idx` (`idleads_conversion_matrix`),
  KEY `leadsconv_idpotentials_idx` (`idpotentials`),
  KEY `leadsconv_idcontacts_idx` (`idcontacts`),
  KEY `leadsconv_idorganization_idx` (`idorganization`),
  KEY `leadsconv_idleads_idx` (`idleads`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads_conversion_matrix`
--

LOCK TABLES `leads_conversion_matrix` WRITE;
/*!40000 ALTER TABLE `leads_conversion_matrix` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads_conversion_matrix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads_custom_fld`
--

DROP TABLE IF EXISTS `leads_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_custom_fld` (
  `idleads_custom_fld` int(19) NOT NULL AUTO_INCREMENT,
  `idleads` int(19) NOT NULL,
  KEY `leadcf_idleads_custom_fld_idx` (`idleads_custom_fld`),
  KEY `leads_idleads_idx` (`idleads`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads_custom_fld`
--

LOCK TABLES `leads_custom_fld` WRITE;
/*!40000 ALTER TABLE `leads_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads_to_grp_rel`
--

DROP TABLE IF EXISTS `leads_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_to_grp_rel` (
  `idleads_to_grp_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idleads` int(19) NOT NULL,
  `idgroup` varchar(200) DEFAULT NULL,
  KEY `leadstogrprel_idleads_to_grp_rel_idx` (`idleads_to_grp_rel`),
  KEY `leadstogrprel_idleads_idx` (`idleads`),
  KEY `leadstogrprel_idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads_to_grp_rel`
--

LOCK TABLES `leads_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `leads_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lineitems`
--

DROP TABLE IF EXISTS `lineitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lineitems` (
  `idlineitems` int(11) NOT NULL AUTO_INCREMENT,
  `idmodule` int(11) DEFAULT NULL,
  `recordid` int(11) DEFAULT NULL,
  `item_type` varchar(100) DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_value` int(11) DEFAULT '0',
  `item_description` text,
  `item_quantity` float(10,3) DEFAULT NULL,
  `item_price` float(10,3) DEFAULT NULL,
  `discount_type` varchar(100) DEFAULT NULL,
  `discount_value` float(10,3) DEFAULT NULL,
  `discounted_amount` float(10,3) DEFAULT NULL,
  `tax_values` varchar(200) DEFAULT NULL,
  `taxed_amount` float(10,3) DEFAULT NULL,
  `total_after_discount` float(10,3) DEFAULT NULL,
  `total_after_tax` float(10,3) DEFAULT NULL,
  `net_total` float(10,3) DEFAULT NULL,
  PRIMARY KEY (`idlineitems`),
  KEY `idmodule_idx` (`idmodule`),
  KEY `recordid_idx` (`recordid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lineitems`
--

LOCK TABLES `lineitems` WRITE;
/*!40000 ALTER TABLE `lineitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `lineitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_audit`
--

DROP TABLE IF EXISTS `login_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_audit` (
  `idlogin_audit` int(19) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(200) DEFAULT NULL,
  `action` varchar(20) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  PRIMARY KEY (`idlogin_audit`),
  KEY `login_audit_idlogin_audit_idx` (`idlogin_audit`),
  KEY `login_audit_iduser_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_audit`
--

LOCK TABLES `login_audit` WRITE;
/*!40000 ALTER TABLE `login_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `idmodule` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `active` int(1) NOT NULL,
  `module_label` varchar(200) NOT NULL,
  `module_sequence` int(10) NOT NULL,
  `menu_item` int(1) DEFAULT '1',
  PRIMARY KEY (`idmodule`),
  KEY `module_idmodule_idx` (`idmodule`),
  KEY `module_module_label_idx` (`module_label`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Home',1,'Home',1,1),(2,'Calendar',1,'Calendar',2,1),(3,'Leads',1,'Leads',3,1),(4,'Contacts',1,'Contacts',4,1),(5,'Potentials',1,'Prospects',5,1),(6,'Organization',1,'Organization',6,1),(7,'User',1,'User',7,1),(8,'Notes',1,'Notes',8,0),(9,'Import',1,'Import',9,0),(10,'Report',1,'Report',10,1),(11,'Vendor',1,'Vendor',11,1),(12,'Products',1,'Products',12,1),(13,'Quotes',1,'Quotes',13,1),(14,'SalesOrder',1,'Sales Order',14,1),(15,'Invoice',1,'Invoice',15,1),(16,'PurchaseOrder',1,'Purchase Order',16,1),(17,'CustomView',1,'Custom View',17,0);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_datashare_rel`
--

DROP TABLE IF EXISTS `module_datashare_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_datashare_rel` (
  `idmodule_datashare_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idmodule` int(19) NOT NULL,
  `permission_flag` int(19) NOT NULL,
  PRIMARY KEY (`idmodule_datashare_rel`),
  KEY `mdr_idmodule_datashare_rel_idx` (`idmodule_datashare_rel`),
  KEY `mdr_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_datashare_rel`
--

LOCK TABLES `module_datashare_rel` WRITE;
/*!40000 ALTER TABLE `module_datashare_rel` DISABLE KEYS */;
INSERT INTO `module_datashare_rel` VALUES (1,2,4),(2,3,4),(3,4,4),(4,5,4),(5,6,4),(6,8,1),(7,9,4),(8,10,1),(9,11,1),(10,12,4),(11,13,4),(12,14,4),(13,15,4),(14,16,4),(15,17,4);
/*!40000 ALTER TABLE `module_datashare_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_standard_permission`
--

DROP TABLE IF EXISTS `module_standard_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_standard_permission` (
  `idmodule_standard_permission` int(19) NOT NULL AUTO_INCREMENT,
  `idmodule` int(19) NOT NULL,
  `idstandard_permission` int(19) NOT NULL,
  PRIMARY KEY (`idmodule_standard_permission`),
  KEY `mstdperm_idmodule_standard_permission_idx` (`idmodule_standard_permission`),
  KEY `mstdperm_idmodule_idx` (`idmodule`),
  KEY `mstdperm_idstandard_permission_idx` (`idstandard_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_standard_permission`
--

LOCK TABLES `module_standard_permission` WRITE;
/*!40000 ALTER TABLE `module_standard_permission` DISABLE KEYS */;
INSERT INTO `module_standard_permission` VALUES (1,2,1),(2,2,2),(3,2,3),(4,3,1),(5,3,2),(6,3,3),(7,4,1),(8,4,2),(9,4,3),(10,5,1),(11,5,2),(12,5,3),(13,6,1),(14,6,2),(15,6,3),(16,8,1),(17,8,2),(18,8,3),(19,9,1),(20,9,2),(21,9,3),(22,10,1),(23,10,2),(24,10,3),(25,11,1),(26,11,2),(27,11,3),(28,12,1),(29,12,2),(30,12,3),(31,13,1),(32,13,2),(33,13,3),(34,14,1),(35,14,2),(36,14,3),(37,15,1),(38,15,2),(39,15,3),(40,16,1),(41,16,2),(42,16,3),(43,17,1),(44,17,2),(45,17,3);
/*!40000 ALTER TABLE `module_standard_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `idnotes` int(19) NOT NULL AUTO_INCREMENT,
  `notes` text,
  `sqcrm_record_id` int(19) DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `related_module_id` int(19) DEFAULT NULL,
  `starred` int(1) DEFAULT '0',
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`idnotes`),
  KEY `notes_idnotes_idx` (`idnotes`),
  KEY `notes_sqcrm_record_id_idx` (`sqcrm_record_id`),
  KEY `notes_related_module_id_idx` (`related_module_id`),
  KEY `notes_iduser_idx` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org_to_grp_rel`
--

DROP TABLE IF EXISTS `org_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `org_to_grp_rel` (
  `idorg_to_grp_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idorganization` int(19) NOT NULL,
  `idgroup` int(20) DEFAULT NULL,
  KEY `orgtogrprel_idorg_to_grp_rel_idx` (`idorg_to_grp_rel`),
  KEY `orgtogrprel_idleads_idx` (`idorganization`),
  KEY `orgtogrprel_idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_to_grp_rel`
--

LOCK TABLES `org_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `org_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization` (
  `idorganization` int(19) NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `member_of` int(19) DEFAULT NULL,
  `num_employes` varchar(20) DEFAULT NULL,
  `sis_code` varchar(100) DEFAULT NULL,
  `ticker_symbol` varchar(100) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `annual_revenue` float(10,3) DEFAULT NULL,
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `industry_type` varchar(200) DEFAULT NULL,
  `email_opt_out` int(3) NOT NULL DEFAULT '0',
  `description` text,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(19) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`idorganization`),
  KEY `org_idorganization_idx` (`idorganization`),
  KEY `org_iduser_idx` (`iduser`),
  KEY `org_organization_name_idx` (`organization_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization`
--

LOCK TABLES `organization` WRITE;
/*!40000 ALTER TABLE `organization` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_address`
--

DROP TABLE IF EXISTS `organization_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization_address` (
  `idorganization_address` int(19) NOT NULL AUTO_INCREMENT,
  `idorganization` int(19) NOT NULL,
  `org_bill_address` text,
  `org_ship_address` text,
  `org_bill_pobox` varchar(50) DEFAULT NULL,
  `org_ship_pobox` varchar(50) DEFAULT NULL,
  `org_bill_postalcode` varchar(50) DEFAULT NULL,
  `org_ship_postalcode` varchar(50) DEFAULT NULL,
  `org_bill_city` varchar(100) DEFAULT NULL,
  `org_ship_city` varchar(100) DEFAULT NULL,
  `org_bill_state` varchar(100) DEFAULT NULL,
  `org_ship_state` varchar(100) DEFAULT NULL,
  `org_bill_country` varchar(50) DEFAULT NULL,
  `org_ship_country` varchar(50) DEFAULT NULL,
  KEY `orgaddr_idorganization_address_idx` (`idorganization_address`),
  KEY `orgaddr_idorganization_idx` (`idorganization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_address`
--

LOCK TABLES `organization_address` WRITE;
/*!40000 ALTER TABLE `organization_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_custom_fld`
--

DROP TABLE IF EXISTS `organization_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization_custom_fld` (
  `idorganization_custom_fld` int(19) NOT NULL AUTO_INCREMENT,
  `idorganization` int(19) NOT NULL,
  KEY `orgcf_idorganization_custom_fld_idx` (`idorganization_custom_fld`),
  KEY `orgcf_idorganization_idx` (`idorganization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_custom_fld`
--

LOCK TABLES `organization_custom_fld` WRITE;
/*!40000 ALTER TABLE `organization_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugins` (
  `idplugins` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `action_priority` int(11) DEFAULT NULL,
  `display_priority` int(11) DEFAULT NULL,
  PRIMARY KEY (`idplugins`),
  KEY `action_priority_idx` (`action_priority`),
  KEY `display_priority_idx` (`display_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plugins`
--

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pot_to_grp_rel`
--

DROP TABLE IF EXISTS `pot_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pot_to_grp_rel` (
  `idpot_to_grp_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idpotentials` int(19) NOT NULL,
  `idgroup` int(20) DEFAULT NULL,
  KEY `pottogrprel_idpot_to_grp_rel_idx` (`idpot_to_grp_rel`),
  KEY `pottogrprel_idpotentials_idx` (`idpotentials`),
  KEY `pottogrprel_idgroup_idx` (`idgroup`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pot_to_grp_rel`
--

LOCK TABLES `pot_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `pot_to_grp_rel` DISABLE KEYS */;
INSERT INTO `pot_to_grp_rel` VALUES (1,1,1);
/*!40000 ALTER TABLE `pot_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `potentials`
--

DROP TABLE IF EXISTS `potentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `potentials` (
  `idpotentials` int(19) NOT NULL AUTO_INCREMENT,
  `potential_name` varchar(200) NOT NULL,
  `potential_type` varchar(200) NOT NULL,
  `expected_closing_date` date DEFAULT NULL,
  `leadsource` varchar(100) DEFAULT NULL,
  `sales_stage` varchar(50) DEFAULT NULL,
  `probability` varchar(190) DEFAULT NULL,
  `description` text,
  `iduser` int(19) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `amount` float(10,3) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(19) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  PRIMARY KEY (`idpotentials`),
  KEY `pot_idpotentials_idx` (`idpotentials`),
  KEY `pot_iduser_idx` (`iduser`),
  KEY `pot_potential_name_idx` (`potential_name`),
  KEY `pot_sales_stage_idx` (`sales_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `potentials`
--

LOCK TABLES `potentials` WRITE;
/*!40000 ALTER TABLE `potentials` DISABLE KEYS */;
/*!40000 ALTER TABLE `potentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `potentials_custom_fld`
--

DROP TABLE IF EXISTS `potentials_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `potentials_custom_fld` (
  `idpotentials_custom_fld` int(19) NOT NULL AUTO_INCREMENT,
  `idpotentials` int(19) NOT NULL,
  KEY `potcf_idpotentials_custom_fld_idx` (`idpotentials_custom_fld`),
  KEY `potcf_idpotentials_idx` (`idpotentials`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `potentials_custom_fld`
--

LOCK TABLES `potentials_custom_fld` WRITE;
/*!40000 ALTER TABLE `potentials_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `potentials_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `potentials_related_to`
--

DROP TABLE IF EXISTS `potentials_related_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `potentials_related_to` (
  `idpotentials_related_to` int(19) NOT NULL AUTO_INCREMENT,
  `idpotentials` int(19) NOT NULL,
  `related_to` int(19) NOT NULL,
  `idmodule` int(19) NOT NULL,
  PRIMARY KEY (`idpotentials_related_to`),
  KEY `potrelto_idpotentials_related_to_idx` (`idpotentials_related_to`),
  KEY `potrelto_idpotentials_idx` (`idpotentials`),
  KEY `potrelto_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `potentials_related_to`
--

LOCK TABLES `potentials_related_to` WRITE;
/*!40000 ALTER TABLE `potentials_related_to` DISABLE KEYS */;
/*!40000 ALTER TABLE `potentials_related_to` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_service_tax`
--

DROP TABLE IF EXISTS `product_service_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_service_tax` (
  `idproduct_service_tax` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(200) DEFAULT NULL,
  `tax_value` float(7,3) DEFAULT NULL,
  PRIMARY KEY (`idproduct_service_tax`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_service_tax`
--

LOCK TABLES `product_service_tax` WRITE;
/*!40000 ALTER TABLE `product_service_tax` DISABLE KEYS */;
INSERT INTO `product_service_tax` VALUES (1,'VAT',4.500),(2,'Sales',10.000),(3,'Service',12.500);
/*!40000 ALTER TABLE `product_service_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `idproducts` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(200) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `product_category` varchar(200) DEFAULT NULL,
  `manufacturer` varchar(200) DEFAULT NULL,
  `idvendor` int(11) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `description` text,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idproducts`),
  KEY `product_name_idx` (`product_name`),
  KEY `idvendor_idx` (`idvendor`),
  KEY `iduser_idx` (`iduser`),
  KEY `deleted_idx` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_custom_fld`
--

DROP TABLE IF EXISTS `products_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_custom_fld` (
  `idproducts_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idproducts` int(11) NOT NULL,
  PRIMARY KEY (`idproducts_custom_fld`),
  KEY `idproducts_idx` (`idproducts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_custom_fld`
--

LOCK TABLES `products_custom_fld` WRITE;
/*!40000 ALTER TABLE `products_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_pricing`
--

DROP TABLE IF EXISTS `products_pricing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_pricing` (
  `idproducts_pricing` int(11) NOT NULL AUTO_INCREMENT,
  `idproducts` int(11) DEFAULT NULL,
  `product_price` float(10,3) DEFAULT NULL,
  `commission_rate` float(10,3) DEFAULT NULL,
  PRIMARY KEY (`idproducts_pricing`),
  KEY `idproducts_idx` (`idproducts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_pricing`
--

LOCK TABLES `products_pricing` WRITE;
/*!40000 ALTER TABLE `products_pricing` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_pricing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_stock`
--

DROP TABLE IF EXISTS `products_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_stock` (
  `idproducts_stock` int(11) NOT NULL AUTO_INCREMENT,
  `idproducts` int(11) DEFAULT NULL,
  `unit_quantity` float(5,2) DEFAULT NULL,
  `quantity_in_stock` float(10,3) DEFAULT NULL,
  `quantity_in_demand` float(10,3) DEFAULT NULL,
  PRIMARY KEY (`idproducts_stock`),
  KEY `idproducts_idx` (`idproducts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_stock`
--

LOCK TABLES `products_stock` WRITE;
/*!40000 ALTER TABLE `products_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_tax`
--

DROP TABLE IF EXISTS `products_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_tax` (
  `idproducts` int(11) DEFAULT NULL,
  `tax_name` varchar(100) DEFAULT NULL,
  `tax_value` float(7,3) DEFAULT NULL,
  KEY `idproducts_idx` (`idproducts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_tax`
--

LOCK TABLES `products_tax` WRITE;
/*!40000 ALTER TABLE `products_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_to_grp_rel`
--

DROP TABLE IF EXISTS `products_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_to_grp_rel` (
  `idproducts_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idproducts` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idproducts_to_grp_rel`),
  KEY `idproducts_idx` (`idproducts`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_to_grp_rel`
--

LOCK TABLES `products_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `products_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `idprofile` int(19) NOT NULL AUTO_INCREMENT,
  `profilename` varchar(50) NOT NULL,
  `description` text,
  `editable` int(1) DEFAULT NULL,
  PRIMARY KEY (`idprofile`),
  KEY `profile_idprofile_idx` (`idprofile`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES (1,'Administration','Profile having all the privileges in the CRM',0),(2,'CEO','Profile related to CEO and having all the permission of the CRM',1),(3,'Sales','Sales profile for the crm.',1);
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_fields_rel`
--

DROP TABLE IF EXISTS `profile_fields_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_fields_rel` (
  `idprofile_fields_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idprofile` int(19) NOT NULL,
  `idmodule` int(10) NOT NULL,
  `idfields` int(19) NOT NULL,
  `visible` int(19) DEFAULT '1',
  PRIMARY KEY (`idprofile_fields_rel`),
  KEY `profile_fields_rel_idprofile_fields_rel_idx` (`idprofile_fields_rel`),
  KEY `profile_fields_rel_idprofile_idx` (`idprofile`),
  KEY `profile_fields_rel_idmodule_idx` (`idmodule`),
  KEY `profile_fields_rel_idfields_idx` (`idfields`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_fields_rel`
--

LOCK TABLES `profile_fields_rel` WRITE;
/*!40000 ALTER TABLE `profile_fields_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_fields_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_global_permission_rel`
--

DROP TABLE IF EXISTS `profile_global_permission_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_global_permission_rel` (
  `idprofile_global_permission_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idprofile` int(19) NOT NULL,
  `idglobal_permission` int(19) NOT NULL,
  `permission_flag` int(1) DEFAULT '0',
  PRIMARY KEY (`idprofile_global_permission_rel`),
  KEY `progperrel_idprofile_global_permission_rel_idx` (`idprofile_global_permission_rel`),
  KEY `progperrel_idprofile_idx` (`idprofile`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_global_permission_rel`
--

LOCK TABLES `profile_global_permission_rel` WRITE;
/*!40000 ALTER TABLE `profile_global_permission_rel` DISABLE KEYS */;
INSERT INTO `profile_global_permission_rel` VALUES (1,1,1,1),(2,1,2,1),(3,2,1,0),(4,2,2,0),(5,3,1,0),(6,3,2,0);
/*!40000 ALTER TABLE `profile_global_permission_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_module_rel`
--

DROP TABLE IF EXISTS `profile_module_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_module_rel` (
  `idprofile_module_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idprofile` int(19) NOT NULL,
  `idmodule` int(19) NOT NULL,
  `permission_flag` int(1) NOT NULL,
  PRIMARY KEY (`idprofile_module_rel`),
  KEY `profile_module_rel_idprofile_module_rel_idx` (`idprofile_module_rel`),
  KEY `profile_module_rel_idprofile_idx` (`idprofile`),
  KEY `profile_module_rel_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_module_rel`
--

LOCK TABLES `profile_module_rel` WRITE;
/*!40000 ALTER TABLE `profile_module_rel` DISABLE KEYS */;
INSERT INTO `profile_module_rel` VALUES (1,1,1,1),(2,1,2,1),(3,1,3,1),(4,1,4,1),(5,1,5,1),(6,1,6,1),(7,2,1,1),(8,2,2,1),(9,2,3,1),(10,2,4,1),(11,2,5,1),(12,2,6,1),(13,1,8,1),(14,2,8,1),(15,1,9,1),(16,2,9,1),(17,1,10,1),(18,2,10,1),(19,1,11,1),(20,2,11,1),(21,3,11,1),(22,1,12,1),(23,2,12,1),(24,3,12,1),(25,3,1,1),(26,3,2,1),(27,3,3,1),(28,3,4,1),(29,3,5,1),(30,3,6,1),(31,3,8,1),(32,3,9,1),(33,3,10,1),(34,3,11,1),(35,3,12,1),(36,1,13,1),(37,2,13,1),(38,3,13,1),(39,1,14,1),(40,2,14,1),(41,3,14,1),(42,1,15,1),(43,2,15,1),(44,3,15,1),(45,1,16,1),(46,2,16,1),(47,3,16,1),(48,1,17,1),(49,2,17,1),(50,3,17,1);
/*!40000 ALTER TABLE `profile_module_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_standard_permission_rel`
--

DROP TABLE IF EXISTS `profile_standard_permission_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_standard_permission_rel` (
  `idprofile_standard_permission_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idprofile` int(19) NOT NULL,
  `idmodule` int(19) NOT NULL,
  `idstandard_permission` int(19) NOT NULL,
  `permission_flag` int(1) DEFAULT '0',
  PRIMARY KEY (`idprofile_standard_permission_rel`),
  KEY `prostdperrel_idprofile_standard_permission_rel_idx` (`idprofile_standard_permission_rel`),
  KEY `prostdperrel_idprofile_idx` (`idprofile`),
  KEY `prostdperrel_idmodule_idx` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_standard_permission_rel`
--

LOCK TABLES `profile_standard_permission_rel` WRITE;
/*!40000 ALTER TABLE `profile_standard_permission_rel` DISABLE KEYS */;
INSERT INTO `profile_standard_permission_rel` VALUES (1,1,1,1,0),(2,1,1,2,0),(3,1,1,3,0),(4,1,2,1,1),(5,1,2,2,1),(6,1,2,3,1),(7,1,3,1,1),(8,1,3,2,1),(9,1,3,3,1),(10,1,4,1,1),(11,1,4,2,1),(12,1,4,3,1),(13,1,5,1,1),(14,1,5,2,1),(15,1,5,3,1),(16,1,6,1,1),(17,1,6,2,1),(18,1,6,3,1),(19,2,1,1,0),(20,2,1,2,0),(21,2,1,3,0),(22,2,2,1,1),(23,2,2,2,1),(24,2,2,3,1),(25,2,3,1,1),(26,2,3,2,1),(27,2,3,3,1),(28,2,4,1,1),(29,2,4,2,1),(30,2,4,3,1),(31,2,5,1,1),(32,2,5,2,1),(33,2,5,3,1),(34,2,6,1,1),(35,2,6,2,1),(36,2,6,3,1),(37,1,8,1,1),(38,1,8,2,1),(39,1,8,3,1),(40,2,8,1,1),(41,2,8,2,1),(42,2,8,3,1),(43,1,9,1,1),(44,1,9,2,1),(45,1,9,3,1),(46,2,9,1,1),(47,2,9,2,1),(48,2,9,3,1),(49,1,10,1,1),(50,1,10,2,1),(51,1,10,3,1),(52,2,10,1,1),(53,2,10,2,1),(54,2,10,3,1),(55,3,10,1,1),(56,3,10,2,1),(57,3,10,3,1),(58,1,11,1,1),(59,1,11,2,1),(60,1,11,3,1),(61,2,11,1,1),(62,2,11,2,1),(63,2,11,3,1),(64,3,11,1,1),(65,3,11,2,1),(66,3,11,3,1),(67,1,12,1,1),(68,1,12,2,1),(69,1,12,3,1),(70,2,12,1,1),(71,2,12,2,1),(72,2,12,3,1),(73,3,12,1,1),(74,3,12,2,1),(75,3,12,3,1),(76,3,1,1,0),(77,3,1,2,0),(78,3,1,3,0),(79,3,2,1,1),(80,3,2,2,1),(81,3,2,3,0),(82,3,3,1,1),(83,3,3,2,1),(84,3,3,3,0),(85,3,4,1,1),(86,3,4,2,1),(87,3,4,3,0),(88,3,5,1,1),(89,3,5,2,1),(90,3,5,3,0),(91,3,6,1,1),(92,3,6,2,1),(93,3,6,3,0),(94,3,8,1,1),(95,3,8,2,1),(96,3,8,3,0),(97,3,9,1,1),(98,3,9,2,1),(99,3,9,3,0),(100,3,10,1,1),(101,3,10,2,1),(102,3,10,3,0),(103,3,11,1,1),(104,3,11,2,1),(105,3,11,3,0),(106,3,12,1,1),(107,3,12,2,1),(108,3,12,3,0),(109,1,13,1,1),(110,1,13,2,1),(111,1,13,3,1),(112,2,13,1,1),(113,2,13,2,1),(114,2,13,3,1),(115,3,13,1,1),(116,3,13,2,1),(117,3,13,3,1),(118,1,14,1,1),(119,1,14,2,1),(120,1,14,3,1),(121,2,14,1,1),(122,2,14,2,1),(123,2,14,3,1),(124,3,14,1,1),(125,3,14,2,1),(126,3,14,3,1),(127,1,15,1,1),(128,1,15,2,1),(129,1,15,3,1),(130,2,15,1,1),(131,2,15,2,1),(132,2,15,3,1),(133,3,15,1,1),(134,3,15,2,1),(135,3,15,3,1),(136,1,16,1,1),(137,1,16,2,1),(138,1,16,3,1),(139,2,16,1,1),(140,2,16,2,1),(141,2,16,3,1),(142,3,16,1,1),(143,3,16,2,1),(144,3,16,3,1),(145,1,17,1,1),(146,1,17,2,1),(147,1,17,3,1),(148,2,17,1,1),(149,2,17,2,1),(150,2,17,3,1),(151,3,17,1,1),(152,3,17,2,1),(153,3,17,3,1);
/*!40000 ALTER TABLE `profile_standard_permission_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order` (
  `idpurchase_order` int(11) NOT NULL AUTO_INCREMENT,
  `po_subject` varchar(200) DEFAULT NULL,
  `idvendor` int(11) DEFAULT NULL,
  `idcontacts` int(11) DEFAULT NULL,
  `description` text,
  `terms_condition` text,
  `po_status` varchar(200) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `po_number` int(11) DEFAULT NULL,
  `po_key` varchar(40) DEFAULT NULL,
  `net_total` float(10,3) DEFAULT NULL,
  `discount_type` varchar(100) DEFAULT NULL,
  `discount_value` float(10,3) DEFAULT NULL,
  `discounted_amount` float(10,3) DEFAULT NULL,
  `tax_values` varchar(200) DEFAULT NULL,
  `taxed_amount` float(10,3) DEFAULT NULL,
  `shipping_handling_charge` float(10,3) DEFAULT NULL,
  `shipping_handling_tax_values` varchar(200) DEFAULT NULL,
  `shipping_handling_taxed_amount` float(10,3) DEFAULT NULL,
  `final_adjustment_type` varchar(100) DEFAULT NULL,
  `final_adjustment_amount` float(10,3) DEFAULT NULL,
  `grand_total` float(10,3) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpurchase_order`),
  KEY `deleted_idx` (`deleted`),
  KEY `po_key_idx` (`po_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order`
--

LOCK TABLES `purchase_order` WRITE;
/*!40000 ALTER TABLE `purchase_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_address`
--

DROP TABLE IF EXISTS `purchase_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_address` (
  `idpurchase_order_address` int(11) NOT NULL AUTO_INCREMENT,
  `idpurchase_order` int(11) DEFAULT NULL,
  `po_billing_address` text,
  `po_billing_po_box` varchar(200) DEFAULT NULL,
  `po_billing_po_code` varchar(200) DEFAULT NULL,
  `po_billing_city` varchar(200) DEFAULT NULL,
  `po_billing_state` varchar(200) DEFAULT NULL,
  `po_billing_country` varchar(200) DEFAULT NULL,
  `po_shipping_address` text,
  `po_shipping_po_box` varchar(200) DEFAULT NULL,
  `po_shipping_po_code` varchar(200) DEFAULT NULL,
  `po_shipping_city` varchar(200) DEFAULT NULL,
  `po_shipping_state` varchar(200) DEFAULT NULL,
  `po_shipping_country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idpurchase_order_address`),
  KEY `idpurchase_order_idx` (`idpurchase_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_address`
--

LOCK TABLES `purchase_order_address` WRITE;
/*!40000 ALTER TABLE `purchase_order_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_custom_fld`
--

DROP TABLE IF EXISTS `purchase_order_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_custom_fld` (
  `idpurchase_order_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idpurchase_order` int(11) NOT NULL,
  PRIMARY KEY (`idpurchase_order_custom_fld`),
  KEY `idpurchase_order_idx` (`idpurchase_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_custom_fld`
--

LOCK TABLES `purchase_order_custom_fld` WRITE;
/*!40000 ALTER TABLE `purchase_order_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_to_grp_rel`
--

DROP TABLE IF EXISTS `purchase_order_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_to_grp_rel` (
  `idpurchase_order_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idpurchase_order` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpurchase_order_to_grp_rel`),
  KEY `idpurchase_order_idx` (`idpurchase_order`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_to_grp_rel`
--

LOCK TABLES `purchase_order_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `purchase_order_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes` (
  `idquotes` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) DEFAULT NULL,
  `idorganization` int(11) DEFAULT NULL,
  `idpotentials` int(11) DEFAULT NULL,
  `description` text,
  `terms_condition` text,
  `quote_stage` varchar(200) DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `quote_number` int(11) DEFAULT NULL,
  `quote_key` varchar(40) DEFAULT NULL,
  `net_total` float(10,3) DEFAULT NULL,
  `discount_type` varchar(100) DEFAULT NULL,
  `discount_value` float(10,3) DEFAULT NULL,
  `discounted_amount` float(10,3) DEFAULT NULL,
  `tax_values` varchar(200) DEFAULT NULL,
  `taxed_amount` float(10,3) DEFAULT NULL,
  `shipping_handling_charge` float(10,3) DEFAULT NULL,
  `shipping_handling_tax_values` varchar(200) DEFAULT NULL,
  `shipping_handling_taxed_amount` float(10,3) DEFAULT NULL,
  `final_adjustment_type` varchar(100) DEFAULT NULL,
  `final_adjustment_amount` float(10,3) DEFAULT NULL,
  `grand_total` float(10,3) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idquotes`),
  KEY `deleted_idx` (`deleted`),
  KEY `quote_key_idx` (`quote_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes`
--

LOCK TABLES `quotes` WRITE;
/*!40000 ALTER TABLE `quotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes_address`
--

DROP TABLE IF EXISTS `quotes_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes_address` (
  `idquotes_address` int(11) NOT NULL AUTO_INCREMENT,
  `idquotes` int(11) DEFAULT NULL,
  `q_billing_address` text,
  `q_billing_po_box` varchar(200) DEFAULT NULL,
  `q_billing_po_code` varchar(200) DEFAULT NULL,
  `q_billing_city` varchar(200) DEFAULT NULL,
  `q_billing_state` varchar(200) DEFAULT NULL,
  `q_billing_country` varchar(200) DEFAULT NULL,
  `q_shipping_address` text,
  `q_shipping_po_box` varchar(200) DEFAULT NULL,
  `q_shipping_po_code` varchar(200) DEFAULT NULL,
  `q_shipping_city` varchar(200) DEFAULT NULL,
  `q_shipping_state` varchar(200) DEFAULT NULL,
  `q_shipping_country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idquotes_address`),
  KEY `idquotes_idx` (`idquotes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes_address`
--

LOCK TABLES `quotes_address` WRITE;
/*!40000 ALTER TABLE `quotes_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotes_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes_custom_fld`
--

DROP TABLE IF EXISTS `quotes_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes_custom_fld` (
  `idquotes_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idquotes` int(11) NOT NULL,
  PRIMARY KEY (`idquotes_custom_fld`),
  KEY `idquotes_idx` (`idquotes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes_custom_fld`
--

LOCK TABLES `quotes_custom_fld` WRITE;
/*!40000 ALTER TABLE `quotes_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotes_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotes_to_grp_rel`
--

DROP TABLE IF EXISTS `quotes_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes_to_grp_rel` (
  `idquotes_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idquotes` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idquotes_to_grp_rel`),
  KEY `idquotes_idx` (`idquotes`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotes_to_grp_rel`
--

LOCK TABLES `quotes_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `quotes_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotes_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurrent_events`
--

DROP TABLE IF EXISTS `recurrent_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recurrent_events` (
  `idrecurrent_events` int(19) NOT NULL AUTO_INCREMENT,
  `idevents` int(19) DEFAULT NULL,
  `recurrent_pattern` text,
  KEY `rec_event_idrecurrent_events_idx` (`idrecurrent_events`),
  KEY `rec_event_idevents_idx` (`idevents`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurrent_events`
--

LOCK TABLES `recurrent_events` WRITE;
/*!40000 ALTER TABLE `recurrent_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `recurrent_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `related_information`
--

DROP TABLE IF EXISTS `related_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `related_information` (
  `idrelated_information` int(19) NOT NULL AUTO_INCREMENT,
  `idmodule` int(19) DEFAULT NULL,
  `related_module` varchar(200) DEFAULT NULL,
  `method_name` varchar(200) DEFAULT NULL,
  `heading` varchar(200) DEFAULT NULL,
  `sequence` int(5) DEFAULT NULL,
  PRIMARY KEY (`idrelated_information`),
  KEY `relinfo_idrelated_information` (`idrelated_information`),
  KEY `relinfo_idmodule` (`idmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `related_information`
--

LOCK TABLES `related_information` WRITE;
/*!40000 ALTER TABLE `related_information` DISABLE KEYS */;
INSERT INTO `related_information` VALUES (1,5,'Contacts','get_related_contacts','Contacts',1),(2,5,'Organization','get_related_organization','Organizations',2),(3,5,'Calendar','get_related_events','Events',3),(4,3,'Calendar','get_related_events','Events',1),(5,4,'Calendar','get_related_events','Events',1),(6,4,'Potentials','get_potentials','Prospects',1),(7,6,'Contacts','get_contacts','Contacts',1),(8,6,'Potentials','get_potentials','Prospects',2),(9,6,'Calendar','get_related_events','Events',3),(10,6,'Quotes','get_related_quotes','Quotes',4),(11,6,'SalesOrder','get_related_sales_order','Sales Order',5),(12,6,'Invoice','get_related_invoice','Invoice',6),(13,4,'SalesOrder','get_related_sales_order','Sales Order',3),(14,4,'Invoice','get_related_invoice','Invoice',4),(15,4,'PurchaseOrder','get_related_purchase_order','Purchase Order',5),(16,11,'PurchaseOrder','get_related_purchase_order','Purchase Order',1),(17,12,'PurchaseOrder','get_related_purchase_order','Purchase Order',1),(18,12,'Quotes','get_related_quotes','Quotes',2),(19,12,'SalesOrder','get_related_sales_order','Sales Order',3),(20,12,'Invoice','get_related_invoice','Invoice',4);
/*!40000 ALTER TABLE `related_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report` (
  `idreport` int(11) NOT NULL AUTO_INCREMENT,
  `idreport_folder` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `iduser` int(11) DEFAULT NULL,
  `report_type` int(11) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`idreport`),
  KEY `iduser_idx` (`iduser`),
  KEY `idreport_folder_idx` (`idreport_folder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report`
--

LOCK TABLES `report` WRITE;
/*!40000 ALTER TABLE `report` DISABLE KEYS */;
/*!40000 ALTER TABLE `report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_date_filter`
--

DROP TABLE IF EXISTS `report_date_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_date_filter` (
  `idreport_date_filter` int(11) NOT NULL AUTO_INCREMENT,
  `idreport` int(11) DEFAULT NULL,
  `idfield` int(11) DEFAULT NULL,
  `filter_type` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`idreport_date_filter`),
  KEY `idreport_idx` (`idreport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_date_filter`
--

LOCK TABLES `report_date_filter` WRITE;
/*!40000 ALTER TABLE `report_date_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_date_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_fields`
--

DROP TABLE IF EXISTS `report_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_fields` (
  `idreport_fields` int(11) NOT NULL AUTO_INCREMENT,
  `idreport` int(11) DEFAULT NULL,
  `report_fields` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idreport_fields`),
  KEY `idreport_idx` (`idreport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_fields`
--

LOCK TABLES `report_fields` WRITE;
/*!40000 ALTER TABLE `report_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_filter`
--

DROP TABLE IF EXISTS `report_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_filter` (
  `idreport_filter` int(11) NOT NULL AUTO_INCREMENT,
  `idreport` int(11) DEFAULT NULL,
  `filter_type` int(11) DEFAULT NULL,
  `filter_field` int(11) DEFAULT NULL,
  `filter_value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idreport_filter`),
  KEY `idreport_idx` (`idreport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_filter`
--

LOCK TABLES `report_filter` WRITE;
/*!40000 ALTER TABLE `report_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_folder`
--

DROP TABLE IF EXISTS `report_folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_folder` (
  `idreport_folder` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `iduser` int(11) DEFAULT '0',
  PRIMARY KEY (`idreport_folder`),
  KEY `iduser_idx` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_folder`
--

LOCK TABLES `report_folder` WRITE;
/*!40000 ALTER TABLE `report_folder` DISABLE KEYS */;
INSERT INTO `report_folder` VALUES (1,'Calendar Activity','Reports related to the calendar activity',0),(2,'Leads','Reports related to the leads',0),(3,'Contacts','Reports related to the contacts',0),(4,'Potentials','Reports related to the potentials',0),(5,'Organization','Reports related to the organization',0),(6,'Vendor','Reports related to vendors',0),(7,'Products','Reports related to products',0),(8,'Quotes','Reports related to quotes',0),(9,'Sales Order','Reports related to sales order',0),(10,'Invoice','Reports related to invoice',0),(11,'Purchase Order','Reports related to purchase order',0);
/*!40000 ALTER TABLE `report_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_module_rel`
--

DROP TABLE IF EXISTS `report_module_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_module_rel` (
  `idreport_module_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idreport` int(11) DEFAULT NULL,
  `primary_module` int(11) DEFAULT NULL,
  `secondary_module` int(11) DEFAULT '0',
  PRIMARY KEY (`idreport_module_rel`),
  KEY `primary_module_idx` (`primary_module`),
  KEY `secondary_module_idx` (`secondary_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_module_rel`
--

LOCK TABLES `report_module_rel` WRITE;
/*!40000 ALTER TABLE `report_module_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_module_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_sorting`
--

DROP TABLE IF EXISTS `report_sorting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_sorting` (
  `idreport_sorting` int(11) NOT NULL AUTO_INCREMENT,
  `idreport` int(11) DEFAULT NULL,
  `sort_field` int(11) DEFAULT NULL,
  `sort_type` varchar(10) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`idreport_sorting`),
  KEY `idreport_idx` (`idreport`),
  KEY `sort_field_idx` (`sort_field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_sorting`
--

LOCK TABLES `report_sorting` WRITE;
/*!40000 ALTER TABLE `report_sorting` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_sorting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `idrole` varchar(255) NOT NULL,
  `rolename` varchar(200) DEFAULT NULL,
  `parentrole` varchar(255) DEFAULT NULL,
  `depth` int(19) DEFAULT NULL,
  `editable` int(1) DEFAULT NULL,
  PRIMARY KEY (`idrole`),
  KEY `role_idrole_idx` (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES ('N1','Organization','N1',0,0),('N2','CEO','N1::N2',1,1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_profile_rel`
--

DROP TABLE IF EXISTS `role_profile_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_profile_rel` (
  `idrole_profile_rel` int(19) NOT NULL AUTO_INCREMENT,
  `idrole` varchar(255) NOT NULL,
  `idprofile` int(19) NOT NULL,
  PRIMARY KEY (`idrole_profile_rel`),
  KEY `role_profile_rel_idrole_profile_rel_idx` (`idrole_profile_rel`),
  KEY `role_profile_rel_idrole_idx` (`idrole`),
  KEY `role_profile_rel_idprofile_idx` (`idprofile`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_profile_rel`
--

LOCK TABLES `role_profile_rel` WRITE;
/*!40000 ALTER TABLE `role_profile_rel` DISABLE KEYS */;
INSERT INTO `role_profile_rel` VALUES (1,'N1',1),(2,'N2',2);
/*!40000 ALTER TABLE `role_profile_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order`
--

DROP TABLE IF EXISTS `sales_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_order` (
  `idsales_order` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) DEFAULT NULL,
  `idorganization` int(11) DEFAULT NULL,
  `idcontacts` int(11) DEFAULT NULL,
  `idpotentials` int(11) DEFAULT NULL,
  `idquotes` int(11) DEFAULT NULL,
  `description` text,
  `terms_condition` text,
  `sales_order_status` varchar(200) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `sales_order_number` int(11) DEFAULT NULL,
  `sales_order_key` varchar(40) DEFAULT NULL,
  `net_total` float(10,3) DEFAULT NULL,
  `discount_type` varchar(100) DEFAULT NULL,
  `discount_value` float(10,3) DEFAULT NULL,
  `discounted_amount` float(10,3) DEFAULT NULL,
  `tax_values` varchar(200) DEFAULT NULL,
  `taxed_amount` float(10,3) DEFAULT NULL,
  `shipping_handling_charge` float(10,3) DEFAULT NULL,
  `shipping_handling_tax_values` varchar(200) DEFAULT NULL,
  `shipping_handling_taxed_amount` float(10,3) DEFAULT NULL,
  `final_adjustment_type` varchar(100) DEFAULT NULL,
  `final_adjustment_amount` float(10,3) DEFAULT NULL,
  `grand_total` float(10,3) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idsales_order`),
  KEY `deleted_idx` (`deleted`),
  KEY `sales_order_key_idx` (`sales_order_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order`
--

LOCK TABLES `sales_order` WRITE;
/*!40000 ALTER TABLE `sales_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order_address`
--

DROP TABLE IF EXISTS `sales_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_order_address` (
  `idsales_order_address` int(11) NOT NULL AUTO_INCREMENT,
  `idsales_order` int(11) DEFAULT NULL,
  `so_billing_address` text,
  `so_billing_po_box` varchar(200) DEFAULT NULL,
  `so_billing_po_code` varchar(200) DEFAULT NULL,
  `so_billing_city` varchar(200) DEFAULT NULL,
  `so_billing_state` varchar(200) DEFAULT NULL,
  `so_billing_country` varchar(200) DEFAULT NULL,
  `so_shipping_address` text,
  `so_shipping_po_box` varchar(200) DEFAULT NULL,
  `so_shipping_po_code` varchar(200) DEFAULT NULL,
  `so_shipping_city` varchar(200) DEFAULT NULL,
  `so_shipping_state` varchar(200) DEFAULT NULL,
  `so_shipping_country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idsales_order_address`),
  KEY `idsales_order_idx` (`idsales_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order_address`
--

LOCK TABLES `sales_order_address` WRITE;
/*!40000 ALTER TABLE `sales_order_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order_custom_fld`
--

DROP TABLE IF EXISTS `sales_order_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_order_custom_fld` (
  `idsales_order_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idsales_order` int(11) NOT NULL,
  PRIMARY KEY (`idsales_order_custom_fld`),
  KEY `idsales_order_idx` (`idsales_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order_custom_fld`
--

LOCK TABLES `sales_order_custom_fld` WRITE;
/*!40000 ALTER TABLE `sales_order_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_order_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order_to_grp_rel`
--

DROP TABLE IF EXISTS `sales_order_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_order_to_grp_rel` (
  `idsales_order_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idsales_order` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idsales_order_to_grp_rel`),
  KEY `idsales_order_idx` (`idsales_order`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order_to_grp_rel`
--

LOCK TABLES `sales_order_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `sales_order_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_order_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_handling_tax`
--

DROP TABLE IF EXISTS `shipping_handling_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_handling_tax` (
  `idshipping_handling_tax` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(200) DEFAULT NULL,
  `tax_value` float(7,3) DEFAULT NULL,
  PRIMARY KEY (`idshipping_handling_tax`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_handling_tax`
--

LOCK TABLES `shipping_handling_tax` WRITE;
/*!40000 ALTER TABLE `shipping_handling_tax` DISABLE KEYS */;
INSERT INTO `shipping_handling_tax` VALUES (1,'VAT',4.500),(2,'Sales',10.000),(3,'Service',12.500);
/*!40000 ALTER TABLE `shipping_handling_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `standard_permission`
--

DROP TABLE IF EXISTS `standard_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `standard_permission` (
  `idstandard_permission` int(19) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(100) NOT NULL,
  PRIMARY KEY (`idstandard_permission`),
  KEY `standard_permission_idstandard_permission_idx` (`idstandard_permission`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `standard_permission`
--

LOCK TABLES `standard_permission` WRITE;
/*!40000 ALTER TABLE `standard_permission` DISABLE KEYS */;
INSERT INTO `standard_permission` VALUES (1,'Add/Edit'),(2,'View'),(3,'Delete');
/*!40000 ALTER TABLE `standard_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_related_to`
--

DROP TABLE IF EXISTS `test_related_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_related_to` (
  `idtest_related_to` int(19) NOT NULL AUTO_INCREMENT,
  `idtest` int(19) NOT NULL,
  `related_to` int(19) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idtest_related_to`),
  KEY `test_idtest_related_to_idx` (`idtest_related_to`),
  KEY `test_idtest_idx` (`idtest`),
  KEY `test_related_to_idx` (`related_to`),
  KEY `test_module_idx` (`module`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_related_to`
--

LOCK TABLES `test_related_to` WRITE;
/*!40000 ALTER TABLE `test_related_to` DISABLE KEYS */;
INSERT INTO `test_related_to` VALUES (1,3501,1,'organization'),(2,2501,2,'organization'),(3,1,1,'contacts');
/*!40000 ALTER TABLE `test_related_to` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `iduser` int(19) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_admin` int(3) NOT NULL DEFAULT '0',
  `email` varchar(200) NOT NULL,
  `firstname` varchar(200) DEFAULT NULL,
  `lastname` varchar(200) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `department` varchar(200) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `office_phone` varchar(50) DEFAULT NULL,
  `other_email` varchar(200) DEFAULT NULL,
  `mobile_num` varchar(50) DEFAULT NULL,
  `signature` text,
  `note` text,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `po` varchar(100) DEFAULT NULL,
  `user_avatar` varchar(200) DEFAULT NULL,
  `user_timezone` varchar(200) DEFAULT NULL,
  `reports_to` int(19) DEFAULT NULL,
  `idrole` varchar(100) NOT NULL,
  `date_view` varchar(25) NOT NULL,
  `deleted` int(1) DEFAULT '0',
  `is_active` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  KEY `user_iduser_idx` (`iduser`),
  KEY `user_idrole_idx` (`idrole`),
  KEY `user_reports_to_idx` (`reports_to`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','ad5bc89a3ee1d800edb1ff443043c5d5',1,'abhik@sqcrm.com','Abhik','Chakraborty','','','','','','','','','','','','','','','Asia/Kolkata',0,'N1','mm-dd-yyyy',0,'Yes'),(2,'ceouser','ad5bc89a3ee1d800edb1ff443043c5d5',0,'abhik@sqcrm.com','CEO','CEO','Senior Marketing VP','Sales and Marketing','','','','','','','','','','','','','Pacific/Midway',1,'N2','mm-dd-yyyy',0,'Yes');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_homepage_component`
--

DROP TABLE IF EXISTS `user_homepage_component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_homepage_component` (
  `iduser_homepage_component` int(19) NOT NULL AUTO_INCREMENT,
  `iduser` int(19) DEFAULT NULL,
  `idhomepage_component` int(19) DEFAULT NULL,
  PRIMARY KEY (`iduser_homepage_component`),
  KEY `hmpage_user_component_iduser_homepage_component_idx` (`iduser_homepage_component`),
  KEY `hmpage_user_component_iduser_idx` (`iduser`),
  KEY `hmpage_user_component_idhomepage_component_idx` (`idhomepage_component`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_homepage_component`
--

LOCK TABLES `user_homepage_component` WRITE;
/*!40000 ALTER TABLE `user_homepage_component` DISABLE KEYS */;
INSERT INTO `user_homepage_component` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,2,1),(7,2,2),(8,2,3),(9,2,4),(10,2,5);
/*!40000 ALTER TABLE `user_homepage_component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `idvendor` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `description` text,
  `iduser` int(11) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  `added_on` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvendor`),
  KEY `iduser_idx` (`iduser`),
  KEY `deleted_idx` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_address`
--

DROP TABLE IF EXISTS `vendor_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_address` (
  `idvendor_address` int(11) NOT NULL AUTO_INCREMENT,
  `idvendor` int(11) DEFAULT NULL,
  `vendor_street` text,
  `vendor_city` varchar(200) DEFAULT NULL,
  `vendor_postal_code` varchar(200) DEFAULT NULL,
  `vendor_po_box` varchar(200) DEFAULT NULL,
  `vendor_state` varchar(200) DEFAULT NULL,
  `vendor_country` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idvendor_address`),
  KEY `idvendor_idx` (`idvendor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_address`
--

LOCK TABLES `vendor_address` WRITE;
/*!40000 ALTER TABLE `vendor_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_custom_fld`
--

DROP TABLE IF EXISTS `vendor_custom_fld`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_custom_fld` (
  `idvendor_custom_fld` int(11) NOT NULL AUTO_INCREMENT,
  `idvendor` int(11) NOT NULL,
  PRIMARY KEY (`idvendor_custom_fld`),
  KEY `idvendor_idx` (`idvendor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_custom_fld`
--

LOCK TABLES `vendor_custom_fld` WRITE;
/*!40000 ALTER TABLE `vendor_custom_fld` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_custom_fld` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_to_grp_rel`
--

DROP TABLE IF EXISTS `vendor_to_grp_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor_to_grp_rel` (
  `idvendor_to_grp_rel` int(11) NOT NULL AUTO_INCREMENT,
  `idvendor` int(11) NOT NULL,
  `idgroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`idvendor_to_grp_rel`),
  KEY `idvendor_idx` (`idvendor`),
  KEY `idgroup_idx` (`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_to_grp_rel`
--

LOCK TABLES `vendor_to_grp_rel` WRITE;
/*!40000 ALTER TABLE `vendor_to_grp_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_to_grp_rel` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-20 16:54:37
