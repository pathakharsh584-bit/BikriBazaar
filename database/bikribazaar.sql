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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `activity_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,'register','Jazz created a new account','2026-05-28 14:52:32'),(2,'register','Jazz created a new account','2026-05-28 14:55:48'),(3,'register','J created a new account','2026-05-28 15:08:21'),(4,'register','Jazz created a new account','2026-05-28 15:15:10'),(5,'post_product','Jazz posted a new ad: Admin Test','2026-05-28 15:19:54'),(6,'delete_product','Product deleted: Admin Test','2026-05-28 15:20:35'),(7,'post_product','Jazz posted a new ad: Anklet','2026-05-28 16:38:06'),(8,'post_product','Jazz posted a new ad: Car','2026-05-28 19:20:08'),(9,'delete_product','Product deleted: Car','2026-05-28 19:22:21');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Jaspreet','bikribazaar.project@gmail.com','123456_123456','2026-05-28 07:18:35');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (7,5,2,'2026-05-16 15:18:19'),(8,4,12,'2026-05-17 14:56:03'),(25,9,63,'2026-05-28 18:30:58');
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
  `deleted_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `plan_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `razorpay_order_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `razorpay_payment_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `razorpay_signature` text COLLATE utf8mb4_general_ci,
  `payment_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'success',
  `start_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `expiry_mail_sent` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,4,21,'Seller Subscription',999.00,'order_SsYBmnlRpjqBBf','pay_SsYET2aBgE37jw','9c3ac48cbc1921afdf1fc7a8977aed0069cfdacb9339ab2b5a5b757920470f05','paid',NULL,NULL,0,'2026-05-22 20:59:53'),(2,4,58,'Mega Boost',499.00,'order_Ssl0P3oE87tnrt',NULL,NULL,'pending',NULL,NULL,0,'2026-05-23 09:32:07'),(3,4,59,'Demo',1.00,'order_SttIsz4ObFYwjH','pay_SttJC6G0YSJZfX','66df5237dde7c6f7f51067f8aae4fad75e42b0a2f4c14c568440da36207742e8','success','2026-05-26 06:18:44','2026-05-27 06:18:44',0,'2026-05-26 06:18:44'),(4,4,60,'Basic',249.00,'order_SttSTqnucTjILD','pay_SttSaz7oEY2vz5','056e79dd37ddd492a90d7efd5b62dda1d8c8edda74eca625d8400f6b8d37dd87','success','2026-05-26 06:27:38','2026-06-25 06:27:38',0,'2026-05-26 06:27:38'),(5,4,61,'Basic',249.00,'order_Stzy52nV4yXFdT','pay_StzyOW9ixAJO1A','4fbccf3cf617f1a4b2d17afd4fb1ac7c7f3e672a4212e76782d580f98b365e6f','success','2026-05-26 18:19:54','2026-06-25 18:19:54',0,'2026-05-26 12:49:54');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (17,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457937/olx_replica/products/uurmpjo0cg7kj9vliosl.png','2026-05-22 13:52:18'),(18,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457941/olx_replica/products/ugvmen0wuqpewtlvey3o.png','2026-05-22 13:52:23'),(19,17,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779457946/olx_replica/products/nwebhfcxjnfu6wpsh96t.png','2026-05-22 13:52:26'),(20,18,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779478633/olx_replica/products/t2osuysi5j0cdl5n7z6f.png','2026-05-22 19:37:14'),(21,18,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779478638/olx_replica/products/mku5bdredomoinoiu86c.png','2026-05-22 19:37:18'),(22,19,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779480318/olx_replica/products/olqxk9an9gpjhqzcjhmt.png','2026-05-22 20:05:19'),(23,19,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779480325/olx_replica/products/smrpk5fdig9gxewqpsyl.png','2026-05-22 20:05:26'),(24,20,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779481560/olx_replica/products/yxxawboknwyxo7ozqmc7.png','2026-05-22 20:26:00'),(25,20,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779481563/olx_replica/products/hwm00ycjm7l18nq1eekj.png','2026-05-22 20:26:04'),(29,22,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779482048/olx_replica/products/fxgvmwreydao4im6aaru.png','2026-05-22 20:34:09'),(30,22,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779482052/olx_replica/products/oojmqb4ljtvhigwsgawu.png','2026-05-22 20:34:12'),(31,23,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779482184/olx_replica/products/yr00w18enmbx6wxk2g5t.png','2026-05-22 20:36:24'),(32,24,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779482365/olx_replica/products/hebu6t3dvz5spgl38xr5.png','2026-05-22 20:39:25'),(33,25,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779482917/olx_replica/products/zzjlwddvnwuuoxab026r.png','2026-05-22 20:48:37'),(34,26,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484342/olx_replica/products/lx7dw7qzxxdawtu9t8y0.png','2026-05-22 21:12:22'),(35,26,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484346/olx_replica/products/hnjcpdf8wv4io5eqhijx.png','2026-05-22 21:12:26'),(36,26,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484349/olx_replica/products/lrhfzxjrdonoxfb4xzng.png','2026-05-22 21:12:30'),(37,27,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484496/olx_replica/products/vgxwyxldy9zqrktfr2hl.png','2026-05-22 21:14:56'),(38,27,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484499/olx_replica/products/p9tfl3mvdqyzxp0ou1fj.png','2026-05-22 21:15:00'),(39,28,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484682/olx_replica/products/npy7z3zujexp4rhaypsu.png','2026-05-22 21:18:03'),(40,28,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484688/olx_replica/products/jlg19lv7ikeflsoqtuif.png','2026-05-22 21:18:08'),(41,28,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484693/olx_replica/products/jboyxpcxyxehu69dm2fj.png','2026-05-22 21:18:14'),(45,30,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484915/olx_replica/products/m9y8w2u4qn682qtc8gll.png','2026-05-22 21:21:56'),(46,30,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484919/olx_replica/products/pb8r0jkofki6g2ve6s5e.png','2026-05-22 21:22:00'),(47,30,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779484923/olx_replica/products/isr2zkcigigsec0t7ohj.png','2026-05-22 21:22:04'),(48,31,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485040/olx_replica/products/rrpgsyecnkfkzqjadcgw.png','2026-05-22 21:24:01'),(49,31,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485044/olx_replica/products/q6tinu10ihakmjw0ej1k.png','2026-05-22 21:24:05'),(50,32,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485126/olx_replica/products/ohk7ciw796kynpdtrble.png','2026-05-22 21:25:26'),(51,32,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485130/olx_replica/products/kjokheocd271f77zzjna.png','2026-05-22 21:25:31'),(52,33,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485231/olx_replica/products/hy15c7u5hz0hs07ca6h5.png','2026-05-22 21:27:12'),(53,33,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485235/olx_replica/products/hvwryacm28ry3siqjgls.png','2026-05-22 21:27:16'),(54,33,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485238/olx_replica/products/msxwiz54djjl2dauulvb.png','2026-05-22 21:27:19'),(55,34,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485316/olx_replica/products/nkararngx21wfcwzyjuq.png','2026-05-22 21:28:37'),(56,34,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485320/olx_replica/products/gna79pzijqrx2zojtk7o.png','2026-05-22 21:28:41'),(57,34,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485324/olx_replica/products/tn1vwvmr0ecssy0dig4f.png','2026-05-22 21:28:45'),(58,35,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485414/olx_replica/products/oiod245mahd4ewtgrogo.png','2026-05-22 21:30:14'),(59,35,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485419/olx_replica/products/l0k7kdqdzqdizacm08il.png','2026-05-22 21:30:20'),(60,35,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485423/olx_replica/products/exw3cbju4ccswzkvilmw.png','2026-05-22 21:30:24'),(61,36,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485537/olx_replica/products/z3phlxxad9qxhvu6bnib.png','2026-05-22 21:32:18'),(62,36,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485541/olx_replica/products/zatpqaambxexa2u9p0yt.png','2026-05-22 21:32:22'),(63,37,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485653/olx_replica/products/l0p0bhq0qsugbvelif65.png','2026-05-22 21:34:14'),(64,37,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485656/olx_replica/products/ok0teeyambuo21tqxsk3.png','2026-05-22 21:34:17'),(65,38,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485758/olx_replica/products/vzafpvv2sylgvxg6g6ub.png','2026-05-22 21:35:59'),(66,38,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485761/olx_replica/products/d2er2qsfpymr7bvr2vnn.png','2026-05-22 21:36:02'),(67,39,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485814/olx_replica/products/ajr5gnahntbavaanciam.png','2026-05-22 21:36:54'),(68,39,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485817/olx_replica/products/xqjgm59odayyvkbtw76w.png','2026-05-22 21:36:58'),(69,40,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485908/olx_replica/products/j5fpeeld461x8gesqmwx.png','2026-05-22 21:38:28'),(70,40,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779485912/olx_replica/products/y116go5solcwkzmj1ytr.png','2026-05-22 21:38:32'),(71,41,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486008/olx_replica/products/rofcpwtqith2ng5f3sw1.png','2026-05-22 21:40:08'),(72,41,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486012/olx_replica/products/w4cg3oef9bjd6izgomso.png','2026-05-22 21:40:13'),(73,41,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486015/olx_replica/products/req1bdyby71riofjzbar.png','2026-05-22 21:40:16'),(74,42,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486154/olx_replica/products/ij39srlray1ijbn65sxy.png','2026-05-22 21:42:35'),(75,43,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486229/olx_replica/products/hdvrpqjxgvlf9lpxzxdq.png','2026-05-22 21:43:50'),(76,44,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486333/olx_replica/products/vvcc9dhpetuoykyjzkec.png','2026-05-22 21:45:33'),(77,44,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486336/olx_replica/products/pdrp6lxqatm2s2tbizpt.png','2026-05-22 21:45:37'),(78,44,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486340/olx_replica/products/dcpmyipc9bg8qjxbc4fg.png','2026-05-22 21:45:40'),(79,45,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486452/olx_replica/products/wl5pyig12qbzelprdkpb.png','2026-05-22 21:47:32'),(80,45,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486455/olx_replica/products/hahh7tkj8ngffjjph7qr.png','2026-05-22 21:47:36'),(81,45,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486459/olx_replica/products/h0qqufqwqd2hfawtkg5f.png','2026-05-22 21:47:39'),(82,46,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486538/olx_replica/products/ea2rt2xfbix1tjpnhwfu.png','2026-05-22 21:48:58'),(83,46,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486541/olx_replica/products/dpmvta2wia6r5jh8s8ci.png','2026-05-22 21:49:01'),(84,47,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486598/olx_replica/products/iie7pys7qwxyf3lryiy6.png','2026-05-22 21:49:58'),(85,48,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486664/olx_replica/products/xivzdty4nd3wxcqw3prh.png','2026-05-22 21:51:04'),(86,49,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486710/olx_replica/products/ovqj5ttad3ovwsehsnw3.png','2026-05-22 21:51:50'),(87,49,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486713/olx_replica/products/oql1cgdxfofcl0dxa7bf.png','2026-05-22 21:51:53'),(88,50,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486798/olx_replica/products/ldizx6galx9rsc8wqiqg.png','2026-05-22 21:53:18'),(89,51,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486869/olx_replica/products/xk4dbw3pwaewrm49uf4j.png','2026-05-22 21:54:30'),(90,52,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486946/olx_replica/products/bleymm8lfcvivxegknea.png','2026-05-22 21:55:47'),(91,52,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779486950/olx_replica/products/yusxmkfxkl5qdoytu97k.png','2026-05-22 21:55:51'),(92,53,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487015/olx_replica/products/lwxjldg4okoklebbtb1t.png','2026-05-22 21:56:56'),(93,53,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487019/olx_replica/products/chq2itjre6hfylhddm83.png','2026-05-22 21:56:59'),(94,53,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487023/olx_replica/products/wjlmueagteymmuwrla85.png','2026-05-22 21:57:03'),(98,55,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487212/olx_replica/products/lov0ga13vimtbur5x483.png','2026-05-22 22:00:12'),(99,55,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487218/olx_replica/products/t1jb7hkk5gkfraaezzn2.png','2026-05-22 22:00:18'),(100,56,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487340/olx_replica/products/avihmjfodfg20f1etcqw.png','2026-05-22 22:02:20'),(101,56,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487344/olx_replica/products/rg1z6csireilieuwhpkm.png','2026-05-22 22:02:25'),(102,56,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487348/olx_replica/products/xx01xab8pexxstmkzkkf.png','2026-05-22 22:02:28'),(103,56,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487352/olx_replica/products/nd0budrtenkn7lrjyp8a.png','2026-05-22 22:02:33'),(104,57,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487416/olx_replica/products/gdz7z1fbxwswdtc23mpd.png','2026-05-22 22:03:36'),(105,57,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487421/olx_replica/products/pi1yoetkab8oknkawa8a.png','2026-05-22 22:03:41'),(106,57,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779487425/olx_replica/products/mswj0e6zs3pvggir0ues.png','2026-05-22 22:03:47'),(107,58,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779526867/olx_replica/products/x4ragznyhgdd62xt3560.png','2026-05-23 09:01:08'),(108,58,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779526871/olx_replica/products/ulqoosffnghqwslxv2xx.png','2026-05-23 09:01:12'),(109,58,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779526874/olx_replica/products/ijefrjjsaecksgazy4el.png','2026-05-23 09:01:15'),(116,61,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779799692/olx_replica/products/onaeaoll1iyrrckbeaam.png','2026-05-26 12:48:13'),(117,61,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779799696/olx_replica/products/ecx1z6b65fgayy4b1oxu.png','2026-05-26 12:48:17'),(118,61,'https://res.cloudinary.com/dagf2tcuh/image/upload/v1779799701/olx_replica/products/nm10e6ssrcabcuyemden.png','2026-05-26 12:48:21');
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
  `is_boosted` tinyint(1) DEFAULT '0',
  `boost_plan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `boost_expiry` datetime DEFAULT NULL,
  `boost_type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_urgent` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (17,4,'Tata Nexon','First Owner, INSURANCE VALID TILL DEC 2026,\r\nFREE DUST COVER\r\nFREE WHELL LOCK',400000.00,'Main Road, Opposite P&M Hi-Tech Mall','Jamshedpur','used','Cars','active','2026-05-22 13:52:11',0,NULL,NULL,NULL,0,0,0,NULL),(18,4,'Honda Activa 6g (2020)','One owner\r\n45 km milege\r\nOnly 15000 km used\r\nCompany fitted engine\r\nCompany condition vehicle\r\nNon accidental\r\nWarranty available',50000.00,'Kamani Centre, Bistupur','Jamshedpur','new','Others','active','2026-05-22 19:37:09',0,NULL,NULL,NULL,0,0,0,NULL),(19,4,'TEAKWOOD SOFA SET','A 3+1+1 teakwood sofa set having a centre table for sale.',15000.00,'Near Sonari Aerodrome, Sonari','Jamshedpur','used','Furniture','active','2026-05-22 20:05:15',0,NULL,NULL,NULL,0,0,0,NULL),(20,4,'Digital Clock','New unique style digital clock.',500.00,'Near Patna Museum, Frazer Road','Patna','new','Electronics','active','2026-05-22 20:25:56',0,NULL,NULL,NULL,0,0,0,NULL),(22,4,'Bajaj Pulsar 200 (2019)','Model: Bajaj Pulsar NS200\r\nKilometers: 14,500 km\r\nOwnership: First \r\nOwnerInsurance: Valid comprehensive insurance till November 2026\r\nMileage: 35-40 kmpl',80000.00,'Near Gandhi Maidan','Patna','new','Bikes','active','2026-05-22 20:34:05',0,NULL,NULL,NULL,0,0,0,NULL),(23,4,'Wooden Bed','Size: Double( king size)\r\nCondition: Very Good (No damage/no crack)\r\nMaterial: Solid wood\r\nStorage: Yes (4 boxes)\r\nColor: brown, black\r\nReason for Selling: extra furniture',17000.00,'Near Firayalal Chowk, Main Road','Ranchi','used','Furniture','active','2026-05-22 20:36:19',0,NULL,NULL,NULL,0,0,0,NULL),(24,4,'Showcase','Showcase in very good condition!\r\nHeight - 3 ft\r\nWidth - 1.5ft\r\nLength- 4 ft',6500.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 20:39:21',0,NULL,NULL,NULL,0,0,0,NULL),(25,4,'TV & Sound System','Good condition\r\nExcellent sound quality',20000.00,'Lalpur Chowk','Ranchi','used','Electronics','active','2026-05-22 20:48:33',0,NULL,NULL,NULL,0,0,0,NULL),(26,4,'Yamaha fz25 (2018)','Regularly serviced with a very smooth engine and no mechanical issues. Zero accident history with scratch-less body condition. All documents are clear and up to date, including Original RC, Insurance, and PUC.',75000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Bikes','active','2026-05-22 21:12:17',0,NULL,NULL,NULL,0,0,0,NULL),(27,4,'ipad 8th Generation','Ipad 8th generation with 32 GB\r\nExcellent Conditions\r\n10.02 inch - Display',22000.00,'Tube Colony, Baridih','Jamshedpur','used','Electronics','active','2026-05-22 21:14:53',0,NULL,NULL,NULL,0,0,0,NULL),(28,4,'Toyota Fortuner','Immaculate Condition, Certified Car, Non Accidental, Single Owner, Alloy Wheels, Finance Available, Rear view Camera, Service History Available, Roadside Assistance Available, Test Drive Available\r\nADDITIONAL VEHICLE INFORMATION:\r\nMake Month: 0\r\nColor: Silver\r\nInsurance Type: No Insurance\r\nRegistration Place: MH\r\nAir Conditioning: No\r\nPower Windows: Front & rear\r\nAdjustable External Mirror: Power\r\nLock System: Remote Controlled Central\r\nNumber of Airbags: 1 airbag\r\nBattery Condition: Old\r\nTyre Condition: Old\r\nService History: Available\r\nVehicle Certified: Yes\r\nAccidental: No',1100000.00,'Near Linking Road, Bandra West','Mumbai','used','Cars','active','2026-05-22 21:17:58',0,NULL,NULL,NULL,0,0,0,NULL),(30,4,'Hyundai i20','PETROL\r\nMODEL :2015\r\nSECOND OWNERSHIP\r\nKM: 77000\r\nSECOND\r\nCOMPANY SERVICE\r\nNO REPLACEMENT\r\nNEAT AND CLEAN\r\nADDITIONAL VEHICLE INFORMATION:\r\nABS: Yes\r\nAccidental: No\r\nAdjustable External Mirror: Power\r\nAdjustable Steering: Yes\r\nAir Conditioning: Automatic Climate Control\r\nNumber of Airbags: 2 airbags\r\nAlloy Wheels: Yes\r\nAux Compatibility: Yes\r\nBattery Condition: New\r\nBluetooth: Yes\r\nVehicle Certified: Yes\r\nColor: White\r\nInsurance Type: Comprehensive\r\nLock System: Remote Controlled Central\r\nMake Month: June\r\nParking Sensors: Yes\r\nPower steering: Yes\r\nPower Windows: Front & rear\r\nAM/FM Radio: Yes\r\nRear Parking Camera: Yes\r\nService History: Available\r\nTyre Condition: New\r\nUSB Compatibility: Yes',400000.00,'Plaza Market, Telco Colony','Jamshedpur','used','Cars','active','2026-05-22 21:21:52',0,NULL,NULL,NULL,0,0,0,NULL),(31,4,'Sofa Set','Damro Sofa. 5 seater.',30000.00,'Near Koramangala BDA Complex','Bengaluru','used','Furniture','active','2026-05-22 21:23:57',0,NULL,NULL,NULL,0,0,0,NULL),(32,4,'iPhone','Perfect working condition.\r\niPhone 15',30000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Electronics','active','2026-05-22 21:25:23',0,NULL,NULL,NULL,0,0,0,NULL),(33,4,'Hero Passion Pro (2015)','Ownership : Single\r\nInsurance availability: NO\r\nEMI option - NO\r\nEngine and Outlook - Good',30000.00,'Ashok Nagar','Ranchi','used','Bikes','active','2026-05-22 21:27:08',0,NULL,NULL,NULL,0,0,0,NULL),(34,4,'Tata Indigo ECS','ABS: Yes\r\nAdjustable External Mirror: Power\r\nAdjustable Steering: Yes\r\nAir Conditioning: With Heater\r\nAnti Theft Device: Yes\r\nAux Compatibility: Yes\r\nBluetooth: Yes\r\nColor: Bordeaux/Maroon\r\nCruise Control: Yes\r\nInsurance Type: No Insurance\r\nNavigation System: Yes\r\nParking Sensors: Yes\r\nPower steering: Yes\r\nPower Windows: Front & rear\r\nAM/FM Radio: Yes\r\nRear Parking Camera: Yes\r\nUSB Compatibility: Yes',200000.00,'Near Sonari Aerodrome, Sonari','Jamshedpur','used','Cars','active','2026-05-22 21:28:33',0,NULL,NULL,NULL,0,0,0,NULL),(35,4,'Hyundai Creta (2020)','#Certified Non-Accidental & Non - Flooded Cars.\r\n#6 months warranty on Engine & Gearbox #100 point PDI before delivery\r\n#15 day Own-Drive Guarantee #Genuine KM reading along with service records. Call us to know more.\r\nADDITIONAL VEHICLE INFORMATION:\r\nABS: Yes\r\nAccidental: No\r\nAdjustable Steering: Yes\r\nAir Conditioning: Automatic Climate Control\r\nNumber of Airbags: 2 airbags\r\nAlloy Wheels: Yes\r\nAnti Theft Device: Yes\r\nAux Compatibility: Yes\r\nBluetooth: Yes\r\nVehicle Certified: Yes\r\nColor: Blue\r\nInsurance Type: Comprehensive\r\nLock System: Remote Controlled Central\r\nMake Month: August\r\nNavigation System: Yes\r\nParking Sensors: Yes\r\nPower steering: Yes\r\nPower Windows: Front & rear\r\nAM/FM Radio: Yes\r\nRear Parking Camera: Yes',1200000.00,'Near Connaught Place, Inner Circle','New Delhi','used','Cars','active','2026-05-22 21:30:09',0,NULL,NULL,NULL,0,0,0,NULL),(36,4,'Yamaha yzf r15 v3 (2018)','Petrol\r\nDocuments clear\r\nTransfer compulsory\r\nLess driven\r\nShowroom condition',75000.00,'Near Karol Bagh Market','New Delhi','used','Bikes','active','2026-05-22 21:32:14',0,NULL,NULL,NULL,0,0,0,NULL),(37,4,'Ipad 11th generation','Ipad 11th generation, 128 GB, wi-fi only 2025, A16 chip, 11 inch.\r\nCondition as totally new only 6 months old. Rarely used.\r\nNo scratches.\r\nNo damages.\r\nWith back cover, original charger and bill box.',40000.00,'Near Charminar, Old City','Hyderabad','new','Electronics','active','2026-05-22 21:34:09',0,NULL,NULL,NULL,0,0,0,NULL),(38,4,'Sofa cum Bed','Stylish & comfortable sofa cum bed for Sale - like new!\r\nUpgrade your living space with this modern and multi-functional sofa cum bed. Perfect for small apartments or guest rooms, it easily converts from a comfortable sofa into a spacious bed within seconds.',42000.00,'Park Street Crossing','Kolkata','used','Furniture','active','2026-05-22 21:35:54',0,NULL,NULL,NULL,0,0,0,NULL),(39,4,'Storage Cupboard','Storage Cupboard\r\nWood cupboard',5000.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 21:36:50',0,NULL,NULL,NULL,0,0,0,NULL),(40,4,'Suzuki Hayabusa (2015)','OWNER:-1\r\nODO:-18,000km GENUINE\r\nDUAL ABS\r\nINSURANCE:-EXPIRED\r\n*ACCESSORIES INSTALLED\r\n*DOUBLE BUBBLE VISOR\r\n*MAGNET COVER GARD\r\n*TANK CAP CARBON FIBER DIP\r\n*CLUTCH AND BREAK HYDROLIC HAYABUSA COVERS\r\n*ENGINE CRASH GARD\r\n*LIVER GARD\r\n*SEAT COUL\r\n*AUSTIN RACING EXHAUST\r\n*BRAND NEW TYRES\r\n*LED HEADLIGHTS\r\nAND MANY MORE\r\nRECENTLY SERVICED DONE\r\nBIKE IS IN PRISTINE CONDITON NO CALIMS NO ACCIDENTE NO REPLACEMENTS BIKE IS VERY WELL MAINTAINED.',1000000.00,'Near Karol Bagh Market','New Delhi','used','Bikes','active','2026-05-22 21:38:25',0,NULL,NULL,NULL,0,0,0,NULL),(41,4,'Bajaj pulsar ns160 (2019)','Model: Bajaj Pulsar NS160 (Twin Disc variant)\r\nYear: 2019\r\nKilometers: 28,000 km\r\nOwnership: First Owner\r\nInsurance: Valid third-party insurance\r\nMileage: Easily delivers 40-45 kmpl in the city',50000.00,'Viman Nagar, Near Phoenix Mall','Pune','used','Bikes','active','2026-05-22 21:40:04',0,NULL,NULL,NULL,0,0,0,NULL),(42,4,'Sony BDV-E4100 5.1 Home Theatre','Sony BDV-E4100 5.1 Home Theatre 1000W Bluetooth NFC Mint Cond\r\nPhysical State: Excellent/Mint condition. No scratches, dents, or cosmetic damage.\r\nWorking Status: 100% functional. All speakers, the subwoofer, and the Blu-ray player work perfectly.\r\nCables: All original color-coded wires and cables are intact and included.\r\nRemote: Original remote included and working.\r\n\r\nKey Technical Highlights:\r\n1000W Total Output: Massive sound with two Tall-boy speakers, two satellite speakers, a center channel, and a dedicated subwoofer.\r\nSmart Connectivity: Built-in Wi-Fi, Bluetooth, and NFC for easy mobile streaming.\r\nFull HD 3D Playback: Crystal clear Blu-ray and DVD playback.\r\nHDMI ARC & Optical: Easily connects to any modern Smart TV with a single cable.\r\nUSB Playback: Play movies or music directly from a pen drive.',18000.00,'Besant Nagar, Near Elliot\'s Beach','Chennai','used','Electronics','active','2026-05-22 21:42:31',0,NULL,NULL,NULL,0,0,0,NULL),(43,4,'Home Theatre','Functional & excellect home theatre and two speaker',3500.00,'Vastrapur, Near Alpha One Mall','Ahmedabad','used','Electronics','active','2026-05-22 21:43:46',0,NULL,NULL,NULL,0,0,0,NULL),(44,4,'Ford Ecosport','ADDITIONAL VEHICLE INFORMATION:\r\nAdjustable Steering: Yes\r\nColor: White\r\nCruise Control: Yes\r\nEngine Capacity/Displacement (in Cc): 1400\r\nInsurance Type: Third Party\r\nMake Month: March\r\nNavigation System: Yes\r\nPower steering: Yes',400000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Cars','active','2026-05-22 21:45:30',0,NULL,NULL,NULL,0,0,0,NULL),(45,4,'Mahindra xuv700 (2022)','Petrol Automatic 7 seater 50525 km done immaculate condition single owner company service.\r\nADDITIONAL VEHICLE INFORMATION:\r\nABS: Yes\r\nAccidental: No\r\nAdjustable External Mirror: Power\r\nAdjustable Steering: Yes\r\nAir Conditioning: Automatic Climate Control\r\nNumber of Airbags: 6 airbags\r\nAlloy Wheels: Yes\r\nAnti Theft Device: Yes\r\nAux Compatibility: Yes\r\nBattery Condition: New\r\nBluetooth: Yes\r\nVehicle Certified: Yes\r\nColor: Blue\r\nCruise Control: Yes\r\nEngine Capacity/Displacement (in Cc): 2000\r\nInsurance Type: Comprehensive\r\nLock System: Remote Controlled Central\r\nMake Month: July\r\nNavigation System: Yes\r\nParking Sensors: Yes\r\nPower steering: Yes\r\nPower Windows: Front & rear\r\nAM/FM Radio: Yes\r\nRear Parking Camera: Yes',1000000.00,'Main Road, Opposite P&M Hi-Tech Mall','Jamshedpur','used','Cars','active','2026-05-22 21:47:26',0,NULL,NULL,NULL,0,0,0,NULL),(46,4,'Almirah','Good quality storage almirah',6100.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 21:48:56',0,NULL,NULL,NULL,0,0,0,NULL),(47,4,'Sofa Set','Excellent & well maintained comfortable',17000.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 21:49:54',0,NULL,NULL,NULL,0,0,0,NULL),(48,4,'Cane Chair & Table','Aesthetic Furniture',10000.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 21:50:53',0,NULL,NULL,NULL,0,0,0,NULL),(49,4,'Dining Table','Dining table with rotation wheel and 6 chair\r\nAesthetic Furniture',20000.00,'Ashok Nagar','Ranchi','used','Furniture','active','2026-05-22 21:51:47',0,NULL,NULL,NULL,0,0,0,NULL),(50,4,'Headphones','Cosmicbyte wired gaming headphones',500.00,'Rajendra Nagar Terminal Area','Patna','used','Electronics','active','2026-05-22 21:53:16',0,NULL,NULL,NULL,0,0,0,NULL),(51,4,'Headphone','JBL Bluetooth headphone 510BT',1400.00,'Rajendra Nagar Terminal Area','Patna','used','Electronics','active','2026-05-22 21:54:27',0,NULL,NULL,NULL,0,0,0,NULL),(52,4,'Suzuki access (2025)','FULL STANDARDISED FITTINGS',40000.00,'Rajendra Nagar Terminal Area','Patna','used','Others','active','2026-05-22 21:55:43',0,NULL,NULL,NULL,0,0,0,NULL),(53,4,'Yamaha fzs (2019)','Owner_1st\r\nKilometres running_18246\r\nColour_BMNM3\r\nMileage_55\r\nNo Scratch\r\nWell maintaine bike',63000.00,'Near Gandhi Maidan','Patna','used','Bikes','active','2026-05-22 21:56:49',0,NULL,NULL,NULL,0,0,0,NULL),(55,4,'Royal Enfield Meteor 350 (2023)','Showroom Condition, Meteor Supernova 350 Top Model , Rare Used',200000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Bikes','active','2026-05-22 22:00:08',0,NULL,NULL,NULL,0,0,0,NULL),(56,4,'Maruti Suzuki Grand Vitara (2022)','ADDITIONAL VEHICLE INFORMATION:\r\nABS: Yes\r\nAccidental: No\r\nAdjustable External Mirror: Power\r\nAdjustable Steering: Yes\r\nAir Conditioning: Automatic Climate Control\r\nAlloy Wheels: Yes\r\nAux Compatibility: Yes\r\nBluetooth: Yes\r\nVehicle Certified: Yes\r\nColor: Blue\r\nInsurance Type: Comprehensive\r\nLock System: Central\r\nMake Month: July\r\nParking Sensors: Yes\r\nPower steering: Yes\r\nPower Windows: Front & rear\r\nAM/FM Radio: Yes',1000000.00,'Near Sonari Aerodrome, Sonari','Jamshedpur','used','Cars','active','2026-05-22 22:02:17',0,NULL,NULL,NULL,0,0,0,NULL),(57,4,'Mahindra Scorpio-n (2024)','Single Owner\r\nPerfect Condition\r\nAutomatic Diesel - Top End Model',2000000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','new','Cars','active','2026-05-22 22:03:32',0,NULL,NULL,NULL,0,0,0,NULL),(58,4,'Headphone Set','Full functioning new headphones',17000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','used','Electronics','active','2026-05-23 09:01:05',0,NULL,NULL,NULL,0,0,0,NULL),(61,4,'Maruti Suzuki Brezza','Model & Variant: Maruti Suzuki Brezza ZXI\r\nYear of Manufacture: October 2022\r\nKilometers Driven: 28,400 km (Genuine, verifiable history)\r\nNo. of Owners: First (Single handedly driven)\r\nFuel Type: Petrol\r\nTransmission: Manual (5-Speed)\r\nRegistration: JH-05 (Jamshedpur)\r\nInsurance: Comprehensive insurance valid until September 2026',700000.00,'Near Akash Deep Plaza, Golmuri','Jamshedpur','new','Cars','active','2026-05-26 12:48:08',1,'Basic','2026-06-25 18:19:54','basic',0,0,0,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reported_ads`
--

DROP TABLE IF EXISTS `reported_ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reported_ads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reported_ads`
--

LOCK TABLES `reported_ads` WRITE;
/*!40000 ALTER TABLE `reported_ads` DISABLE KEYS */;
INSERT INTO `reported_ads` VALUES (2,63,4,'Fake Product','2026-05-28 16:46:18');
/*!40000 ALTER TABLE `reported_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `support_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `maintenance_mode` enum('on','off') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'off',
  `theme_mode` enum('light','dark') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'light',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'BikriBazaar','support@bikribazaar.com','off','light');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Harsh Pathak','pathakneet584@gmail.com','$2y$10$AHKYYL2PUicyE4fqLwFK9.ryF9cMHGns0H4s/5olA0qEEvBDBxCQO','09229214413','JAMSHEDPUR','2026-05-12 10:20:30',NULL,NULL,'',NULL),(4,'Jaspreet','jaspreetsk.2020@gmail.com','$2y$10$zwxxv81GlDosR0nOvVHkAO82DLLYFijC1ApbC2fteJHk4FAfLVMpS','9234241658','JAMSHEDPUR','2026-05-12 12:28:06',NULL,NULL,'',NULL),(5,'Satinder','satindergurprit321@gmail.com','$2y$10$lB/3xeow5Z545bDSacJBQONMv.N5yJ7qO7GEZ423tr93f42xaA6Xu','9234241658','Jamshedpur','2026-05-16 15:16:56',NULL,NULL,'',NULL);
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

-- Dump completed on 2026-05-29  1:34:25
