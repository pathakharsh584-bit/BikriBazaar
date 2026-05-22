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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (14,16,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779456193/olx_replica/products/jc67xnmzvns1dp49c2aa.png','2026-05-22 13:23:14'),(15,16,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779456197/olx_replica/products/g3w0x3hbdsvunk59fmdu.png','2026-05-22 13:23:18'),(16,16,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779456200/olx_replica/products/p1ft3snkaup17wlrck0m.png','2026-05-22 13:23:21'),(17,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457937/olx_replica/products/uurmpjo0cg7kj9vliosl.png','2026-05-22 13:52:18'),(18,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457941/olx_replica/products/ugvmen0wuqpewtlvey3o.png','2026-05-22 13:52:23'),(19,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457946/olx_replica/products/nwebhfcxjnfu6wpsh96t.png','2026-05-22 13:52:26');
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
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `condition` enum('new','used','refurbished') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'used',
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','sold') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (16,4,'Car','- 26350 kms driven, just 5 years old premium compact SUV, 2 Airbags, 5 star NCAP safety rating, all services done from tata workshop only\r\n- Automatic ORVM, Rain sensing wipers, cruise control, height adjustable steering and driver seat\r\n- Second top model: petrol variant\r\n- All accessories installed from dealership\r\n- Valid PUC certificate, Insurance\r\n- Recently replaced battery\r\n- Big Sunroof, automatic climate control AC\r\n- No insurance claims, no prior accidents\r\n- First owner profession: doctor\r\n- Price negotiable for genuine buyers',600000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Cars','active','2026-05-22 13:23:10'),(17,4,'Tata Nexon','First Owner, INSURANCE VALID TILL DEC 2026,\r\nFREE DUST COVER\r\nFREE WHELL LOCK',400000.00,'Main Road, Opposite P&M Hi-Tech Mall','Jamshedpur','used','Cars','active','2026-05-22 13:52:11');
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
  `about` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '',
  `profile_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Harsh Pathak','pathakharsh584@gmail.com','$2y$10$eu890BC/KyEbszqtgihBT.96osa/mCkkYq0p8bgHSD88MfLtiW/be',NULL,NULL,'2026-05-10 02:30:42',NULL,NULL,'',NULL),(2,'Harsh Pathak','pathakneetu584@gmail.com','$2y$10$uHIntx3lfjhTmLPiRcJHGOUaPQJPT/cQ9XKiLsQ7U75A7JhakUaB.','09229214413','JAMSHEDPUR','2026-05-11 10:59:37',NULL,NULL,'',NULL),(3,'Harsh Pathak','pathakneet584@gmail.com','$2y$10$AHKYYL2PUicyE4fqLwFK9.ryF9cMHGns0H4s/5olA0qEEvBDBxCQO','09229214413','JAMSHEDPUR','2026-05-12 10:20:30',NULL,NULL,'',NULL),(4,'Jaspreet','jaspreetsk.2020@gmail.com','$2y$10$LRbIq9PAd7eNLYQsZUvlE.JAiKWJcPfwB7lAEP7PrgKWm/JeOtE8i','9234241658','JAMSHEDPUR','2026-05-12 12:28:06',NULL,NULL,'',NULL),(5,'Satinder','satindergurprit321@gmail.com','$2y$10$lB/3xeow5Z545bDSacJBQONMv.N5yJ7qO7GEZ423tr93f42xaA6Xu','9234241658','Jamshedpur','2026-05-16 15:16:56',NULL,NULL,'',NULL);
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

-- Dump completed on 2026-05-22 19:43:08
