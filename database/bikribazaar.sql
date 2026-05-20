-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: bikribazaar
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,1,2,'2026-05-12 05:12:23'),(7,5,2,'2026-05-16 15:18:19'),(8,4,12,'2026-05-17 14:56:03');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `is_seen` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,12,5,4,'Hello',1,'2026-05-16 15:17:45'),(2,12,5,4,'Hello',1,'2026-05-16 15:36:34'),(3,12,4,5,'Yes ?',1,'2026-05-16 15:52:22'),(4,12,4,5,'ok',1,'2026-05-17 14:56:30'),(5,12,4,5,'Check message',1,'2026-05-17 15:02:03'),(6,12,4,5,'check',1,'2026-05-17 16:30:00'),(7,12,5,4,'Hello jaspreet',1,'2026-05-17 16:31:28'),(8,12,4,5,'Hey !',1,'2026-05-17 16:31:35'),(9,12,5,4,'I would love to buy your product',1,'2026-05-17 16:31:51'),(10,12,4,5,'Can we discuss the price?',1,'2026-05-17 16:37:28'),(11,12,5,4,'Yes sure',1,'2026-05-17 16:42:15'),(12,12,5,4,'Hello ?',1,'2026-05-18 13:22:21'),(13,12,4,5,'Yes ma\'am?',1,'2026-05-19 16:06:39'),(14,12,5,4,'Can we close the deal?',1,'2026-05-19 16:06:50'),(15,12,4,5,'ok sure',1,'2026-05-19 16:42:55');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'1778519721_Screenshot 2026-04-10 163542.png','2026-05-19 19:14:04'),(2,2,'1778575231_Screenshot 2026-04-23 192935.png','2026-05-19 19:14:04'),(3,12,'1778944246_Screenshot 2026-04-02 124048.png','2026-05-19 19:14:04'),(4,13,'1779223524_6a0ccbe4d8023_Screenshot 2026-05-20 021421.png','2026-05-19 20:45:24'),(5,13,'1779223524_6a0ccbe4db0cb_Screenshot 2026-05-20 021424.png','2026-05-19 20:45:24'),(6,13,'1779223524_6a0ccbe4ddb54_Screenshot 2026-05-20 021428.png','2026-05-19 20:45:24');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `condition` enum('new','used','refurbished') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'used',
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','sold') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'dsgds','dsGsd',15000.00,'gdsgds','used','Mobiles','active','2026-05-11 11:45:21'),(2,1,'LAPTOP','NEW LAPTOP ONLY ONE MONTH USED',20000.00,'JHARKHAND','used','Electronics','active','2026-05-12 03:10:31'),(12,4,'Test try 2','Hoping it works',100.00,'Jamshedpur','new','Mobiles','sold','2026-05-16 15:10:46'),(13,4,'Laptop','Multiple uploads check',75000.00,'Jamshedpur','new','Electronics','active','2026-05-19 20:45:24');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Harsh Pathak','pathakharsh584@gmail.com','$2y$10$eu890BC/KyEbszqtgihBT.96osa/mCkkYq0p8bgHSD88MfLtiW/be',NULL,NULL,'2026-05-10 02:30:42',NULL,NULL),(2,'Harsh Pathak','pathakneetu584@gmail.com','$2y$10$uHIntx3lfjhTmLPiRcJHGOUaPQJPT/cQ9XKiLsQ7U75A7JhakUaB.','09229214413','JAMSHEDPUR','2026-05-11 10:59:37',NULL,NULL),(3,'Harsh Pathak','pathakneet584@gmail.com','$2y$10$AHKYYL2PUicyE4fqLwFK9.ryF9cMHGns0H4s/5olA0qEEvBDBxCQO','09229214413','JAMSHEDPUR','2026-05-12 10:20:30',NULL,NULL),(4,'Jaspreet','jaspreetsk.2020@gmail.com','$2y$10$Jrz6/i5Qsv6ixG40YVNyeeAtNubFrknbho9yxEz199aOCM256iXZS','9234241658','JAMSHEDPUR','2026-05-12 12:28:06',NULL,NULL),(5,'Satinder','satindergurprit321@gmail.com','$2y$10$lB/3xeow5Z545bDSacJBQONMv.N5yJ7qO7GEZ423tr93f42xaA6Xu','9234241658','Jamshedpur','2026-05-16 15:16:56',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-20 10:07:38
