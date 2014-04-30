-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: orcamentos
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

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
-- Table structure for table `Client`
--

DROP TABLE IF EXISTS `Client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logotype` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `responsable` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C0E80163C8C6906B` (`cnpj`),
  KEY `IDX_C0E80163979B1AD6` (`company_id`),
  CONSTRAINT `FK_C0E80163979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `Company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Client`
--

LOCK TABLES `Client` WRITE;
/*!40000 ALTER TABLE `Client` DISABLE KEYS */;
INSERT INTO `Client` VALUES (1,1,'2014-04-01 12:21:19',NULL,'Mateus','7779998881','mateus@coderockr.com','mateus_logo.jpg','(49) 9978-2269','asdasdsdsasdasdadsadsaaaa'),(3,1,'2014-04-10 14:24:49',NULL,'Mateus','885522','nene@empresa.com','a8902045818f2506ea91ec42bc49321a.jpg','(49) 9978-2269','Vacilao'),(10,1,'2014-04-29 15:35:17',NULL,'Cliente2','','email@empresa.com','0df59e39755c02bb377f132751379e1d.jpg','','naotem');
/*!40000 ALTER TABLE `Client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClientNote`
--

DROP TABLE IF EXISTS `ClientNote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ClientNote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `note` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_4A1DDCA419EB6921` (`client_id`),
  KEY `IDX_4A1DDCA4DB805178` (`quote_id`),
  CONSTRAINT `FK_4A1DDCA419EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  CONSTRAINT `FK_4A1DDCA4DB805178` FOREIGN KEY (`quote_id`) REFERENCES `Quote` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ClientNote`
--

LOCK TABLES `ClientNote` WRITE;
/*!40000 ALTER TABLE `ClientNote` DISABLE KEYS */;
/*!40000 ALTER TABLE `ClientNote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Company`
--

DROP TABLE IF EXISTS `Company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `site` varchar(255) DEFAULT NULL,
  `logotype` varchar(255) NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Company`
--

LOCK TABLES `Company` WRITE;
/*!40000 ALTER TABLE `Company` DISABLE KEYS */;
INSERT INTO `Company` VALUES (1,'2014-04-01 12:20:01',NULL,'Coderockr','www.corderock.com','fe1cd280e01130b85c000d3724b43f7b.jpg','Xornas','(49) 9978-2269');
/*!40000 ALTER TABLE `Company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PrivateNote`
--

DROP TABLE IF EXISTS `PrivateNote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PrivateNote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `note` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_D911EC08166D1F9C` (`project_id`),
  KEY `IDX_D911EC08A76ED395` (`user_id`),
  CONSTRAINT `FK_D911EC08166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `Project` (`id`),
  CONSTRAINT `FK_D911EC08A76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PrivateNote`
--

LOCK TABLES `PrivateNote` WRITE;
/*!40000 ALTER TABLE `PrivateNote` DISABLE KEYS */;
INSERT INTO `PrivateNote` VALUES (4,4,1,'2014-04-17 09:47:50',NULL,'aaaaa');
/*!40000 ALTER TABLE `PrivateNote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E00EE97219EB6921` (`client_id`),
  KEY `IDX_E00EE972979B1AD6` (`company_id`),
  CONSTRAINT `FK_E00EE97219EB6921` FOREIGN KEY (`client_id`) REFERENCES `Client` (`id`),
  CONSTRAINT `FK_E00EE972979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `Company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Project`
--

LOCK TABLES `Project` WRITE;
/*!40000 ALTER TABLE `Project` DISABLE KEYS */;
INSERT INTO `Project` VALUES (1,1,1,'2014-04-01 12:22:18',NULL,'Projeto novo','Descricao','app,novo,teste'),(3,1,1,'2014-04-10 14:30:20',NULL,'Movelsul 2014','sadasds','2,nova'),(4,1,1,'2014-04-15 08:45:09',NULL,'Projeto do mateus','hehehe','novo, 2'),(5,3,1,'2014-04-23 17:44:06',NULL,'Yeah','2222','222dd');
/*!40000 ALTER TABLE `Project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Quote`
--

DROP TABLE IF EXISTS `Quote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Quote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `version` varchar(150) NOT NULL,
  `status` int(11) NOT NULL,
  `privateNotes` longtext,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AAB0E4F0166D1F9C` (`project_id`),
  CONSTRAINT `FK_AAB0E4F0166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `Project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Quote`
--

LOCK TABLES `Quote` WRITE;
/*!40000 ALTER TABLE `Quote` DISABLE KEYS */;
INSERT INTO `Quote` VALUES (1,'2014-04-03 18:42:30',NULL,'versao 1',0,'',NULL),(2,'2014-04-10 17:49:34',NULL,'v2',1,'hehe',3),(15,'2014-04-15 11:10:44',NULL,'1',1,'dasdsaadsdsa',4);
/*!40000 ALTER TABLE `Quote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Resource`
--

DROP TABLE IF EXISTS `Resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `cost` double NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `equipmentLife` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_45E79640979B1AD6` (`company_id`),
  KEY `IDX_45E79640C54C8C93` (`type_id`),
  CONSTRAINT `FK_45E79640979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `Company` (`id`),
  CONSTRAINT `FK_45E79640C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `Type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Resource`
--

LOCK TABLES `Resource` WRITE;
/*!40000 ALTER TABLE `Resource` DISABLE KEYS */;
INSERT INTO `Resource` VALUES (78,1,'2014-04-15 11:02:54',NULL,'pc',22,2,13),(79,1,'2014-04-15 11:05:51',NULL,'das',21,1,NULL),(80,1,'2014-04-15 11:09:57',NULL,'dd',21,3,NULL),(81,1,'2014-04-15 11:10:23',NULL,'ca',14,3,NULL),(82,1,'2014-04-15 11:11:25',NULL,'xx',41,3,NULL),(107,1,'2014-04-24 16:40:58',NULL,'Teste',31,2,23),(110,1,'2014-04-28 15:27:02',NULL,'Algo',15.6,2,0);
/*!40000 ALTER TABLE `Resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ResourceQuote`
--

DROP TABLE IF EXISTS `ResourceQuote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResourceQuote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `amount` double NOT NULL,
  `value` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4BCB7FA489329D25` (`resource_id`),
  KEY `IDX_4BCB7FA4DB805178` (`quote_id`),
  CONSTRAINT `FK_4BCB7FA489329D25` FOREIGN KEY (`resource_id`) REFERENCES `Resource` (`id`),
  CONSTRAINT `FK_4BCB7FA4DB805178` FOREIGN KEY (`quote_id`) REFERENCES `Quote` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ResourceQuote`
--

LOCK TABLES `ResourceQuote` WRITE;
/*!40000 ALTER TABLE `ResourceQuote` DISABLE KEYS */;
INSERT INTO `ResourceQuote` VALUES (59,78,15,'2014-04-23 15:35:39',NULL,3111,21),(60,79,15,'2014-04-23 15:35:39',NULL,1,21),(61,80,15,'2014-04-23 15:35:39',NULL,213232,21),(62,82,15,'2014-04-23 15:35:39',NULL,111111,41),(63,78,2,'2014-04-29 15:20:22',NULL,21,22);
/*!40000 ALTER TABLE `ResourceQuote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Share`
--

DROP TABLE IF EXISTS `Share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2EC7B25EDB805178` (`quote_id`),
  CONSTRAINT `FK_2EC7B25EDB805178` FOREIGN KEY (`quote_id`) REFERENCES `Quote` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Share`
--

LOCK TABLES `Share` WRITE;
/*!40000 ALTER TABLE `Share` DISABLE KEYS */;
INSERT INTO `Share` VALUES (37,2,'2014-04-29 13:48:20',NULL,'jao@coderockr.com',0),(38,2,'2014-04-29 13:49:08',NULL,'thiago@coderockr.com',0);
/*!40000 ALTER TABLE `Share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ShareNote`
--

DROP TABLE IF EXISTS `ShareNote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ShareNote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `note` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_DAEA4B832AE63FDB` (`share_id`),
  CONSTRAINT `FK_DAEA4B832AE63FDB` FOREIGN KEY (`share_id`) REFERENCES `Share` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ShareNote`
--

LOCK TABLES `ShareNote` WRITE;
/*!40000 ALTER TABLE `ShareNote` DISABLE KEYS */;
/*!40000 ALTER TABLE `ShareNote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Type`
--

DROP TABLE IF EXISTS `Type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contractType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Type`
--

LOCK TABLES `Type` WRITE;
/*!40000 ALTER TABLE `Type` DISABLE KEYS */;
INSERT INTO `Type` VALUES (1,'2014-04-08 18:38:15',NULL,'Conta','service',NULL),(2,'2014-04-08 18:43:57',NULL,'Computador','equipment',NULL),(3,'2014-04-08 18:45:25',NULL,'Funcionario','human','CLT');
/*!40000 ALTER TABLE `Type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977E7927C74` (`email`),
  KEY `IDX_2DA17977979B1AD6` (`company_id`),
  CONSTRAINT `FK_2DA17977979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `Company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,1,'2014-04-03 18:31:49',NULL,'User 10','mateus@coderockr.com','$2y$10$b24QlFVVEAxE2Xp/q6d07ud0kS9xEgyHQNB9BpryHpjyz4XpOCOvi',1);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `View`
--

DROP TABLE IF EXISTS `View`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `View` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5ECF04B02AE63FDB` (`share_id`),
  CONSTRAINT `FK_5ECF04B02AE63FDB` FOREIGN KEY (`share_id`) REFERENCES `Share` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `View`
--

LOCK TABLES `View` WRITE;
/*!40000 ALTER TABLE `View` DISABLE KEYS */;
INSERT INTO `View` VALUES (1,NULL,'2014-04-30 14:49:07',NULL);
/*!40000 ALTER TABLE `View` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-30 19:38:38
