-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: inventory_sample
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.13-MariaDB

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
-- Table structure for table `customer`
--
USE inventory_sample;

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id_customer` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `diskon` float(15,2) DEFAULT NULL,
  `tipe_diskon` enum('persen','fix') DEFAULT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Ahmad Ridwan','853','Jalan',10.00,'persen','61b6cbf3d9658.JPG'),(2,'Joko',NULL,NULL,5000.00,'fix',NULL),(3,'Sardi','888888','Jalan',NULL,NULL,NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item` (
  `id_item` int(10) NOT NULL AUTO_INCREMENT,
  `nama_item` varchar(100) NOT NULL,
  `unit` enum('kg','pcs') NOT NULL,
  `stok` int(10) NOT NULL,
  `harga_satuan` float(15,2) NOT NULL,
  `barang` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (2,'Kopi Gula Aren','pcs',10,8000.00,NULL),(3,'Indomie','pcs',94,3000.00,'61b6dbc1d6a86.jpg'),(5,'Susu','pcs',100,10000.00,'61b6de16999d4.jpg');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `kode_transaksi` varchar(10) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_customer` int(10) NOT NULL,
  `total_bayar` float(15,2) NOT NULL,
  `total_diskon` float(15,2) NOT NULL,
  PRIMARY KEY (`kode_transaksi`),
  KEY `FK_sales_customer` (`id_customer`),
  CONSTRAINT `FK_sales_customer` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES ('250100515','2021-12-13 04:05:55',3,11000.00,0.00),('3021944460','2021-12-13 03:20:09',2,6000.00,5000.00),('5771330507','2021-12-13 04:30:45',3,51000.00,0.00),('750475381','2021-12-13 03:14:58',1,13300.00,5.00);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_detail`
--

DROP TABLE IF EXISTS `sales_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_detail` (
  `id_detail` int(10) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(10) NOT NULL,
  `id_item` int(10) NOT NULL,
  `qty` int(5) NOT NULL DEFAULT 1,
  `total_harga` float(10,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `kode_transaksi_id_item` (`kode_transaksi`,`id_item`),
  KEY `FK_sales_detail_item` (`id_item`),
  CONSTRAINT `FK_sales_detail_item` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sales_detail_sales` FOREIGN KEY (`kode_transaksi`) REFERENCES `sales` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_detail`
--

LOCK TABLES `sales_detail` WRITE;
/*!40000 ALTER TABLE `sales_detail` DISABLE KEYS */;
INSERT INTO `sales_detail` VALUES (12,'750475381',2,1,8000.00),(13,'750475381',3,2,6000.00),(14,'3021944460',2,1,8000.00),(15,'3021944460',3,1,3000.00),(16,'250100515',2,1,8000.00),(17,'250100515',3,1,3000.00),(18,'5771330507',2,6,48000.00),(19,'5771330507',3,1,3000.00);
/*!40000 ALTER TABLE `sales_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `sales_detail_before_insert` BEFORE INSERT ON `sales_detail` FOR EACH ROW BEGIN

SET @stok = (SELECT stok FROM item WHERE id_item = NEW.id_item);

SET @sisa = @stok - NEW.qty;

IF @sisa < 0 THEN

	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Warning: stok limit'; 

END IF;

UPDATE item SET stok = @sisa WHERE id_item = NEW.id_item;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `sales_detail_before_update` BEFORE UPDATE ON `sales_detail` FOR EACH ROW BEGIN

IF OLD.id_item = NEW.id_item THEN 

	SET @stok = (SELECT stok FROM item WHERE id_item = OLD.id_item);

	SET @sisa = (@stok + OLD.qty) - NEW.qty;

	IF @sisa < 0 THEN

		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Warning: stok limit'; 

	END IF;

	UPDATE item SET stok = @sisa WHERE id_item = OLD.id_item;

ELSE

	SET @stok_lama = (SELECT stok FROM item WHERE id_item = OLD.id_item);

	SET @sisa_lama = (@stok_lama + OLD.qty);

	UPDATE item SET stok = @sisa_lama WHERE id_item = OLD.id_item;

	SET @stok_baru = (SELECT stok FROM item WHERE id_item = NEW.id_item);

	SET @sisa_baru = @stok_baru - NEW.qty;

	IF @sisa_baru < 0 THEN

		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Warning: stok limit'; 

	END IF;

	UPDATE item SET stok = @sisa_baru WHERE id_item = NEW.id_item;

END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `sales_detail_after_delete` AFTER DELETE ON `sales_detail` FOR EACH ROW BEGIN

SET @stok = (SELECT stok FROM item WHERE id_item = OLD.id_item);

SET @sisa = @stok + OLD.qty;

UPDATE item SET stok = @sisa WHERE id_item = OLD.id_item;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'inventory_sample'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-13 12:48:25
