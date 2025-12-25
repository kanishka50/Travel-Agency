CREATE DATABASE  IF NOT EXISTS `tourism_platform` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tourism_platform`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: tourism_platform
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','content_moderator','finance_admin','complaint_manager','registration_manager') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_user_id_unique` (`user_id`),
  KEY `admins_role_index` (`role`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,1,'Super Administrator','super_admin','+94771234567','2025-10-12 08:29:13','2025-10-12 08:29:13');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bids` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tourist_request_id` bigint unsigned NOT NULL,
  `guide_id` bigint unsigned NOT NULL,
  `bid_number` int NOT NULL,
  `proposal_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_by_day_plan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `price_breakdown` text COLLATE utf8mb4_unicode_ci,
  `destinations_covered` json NOT NULL,
  `accommodation_details` text COLLATE utf8mb4_unicode_ci,
  `transport_details` text COLLATE utf8mb4_unicode_ci,
  `included_services` text COLLATE utf8mb4_unicode_ci,
  `excluded_services` text COLLATE utf8mb4_unicode_ci,
  `estimated_days` int DEFAULT NULL,
  `status` enum('pending','accepted','rejected','withdrawn') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bids_tourist_request_id_guide_id_bid_number_unique` (`tourist_request_id`,`guide_id`,`bid_number`),
  KEY `bids_tourist_request_id_index` (`tourist_request_id`),
  KEY `bids_guide_id_index` (`guide_id`),
  KEY `bids_status_index` (`status`),
  CONSTRAINT `bids_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bids_tourist_request_id_foreign` FOREIGN KEY (`tourist_request_id`) REFERENCES `tourist_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bids`
--

LOCK TABLES `bids` WRITE;
/*!40000 ALTER TABLE `bids` DISABLE KEYS */;
INSERT INTO `bids` VALUES (1,2,1,1,'because i am the batman,because i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman','because i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman\r\n\r\nbecause i am the batmanbecause i am the batmanbecause i am the batman\r\n\r\nbecause i am the batmanbecause i am the batman\r\n\r\nbecause i am the batmanbecause i am the batman\r\n\r\n\r\nbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman',1000.00,'because i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman\r\n\r\n\r\nbecause i am the batmanbecause i am the batmanbecause i am the batman\r\nbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman','[\"because i am the batmanbecause i am the batman\", \"because i am the batman\", \"because i am the batmanbecause i am the batman\"]','because i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman','because i am the batmanbecause i am the batman','because i am the batmanbecause i am the batman','because i am the batmanbecause i am the batmanbecause i am the batmanbecause i am the batman',7,'withdrawn',NULL,'2025-11-13 12:23:45','2025-11-13 12:24:07','2025-11-13 12:23:45','2025-11-13 12:24:07'),(2,2,1,2,'i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,','i am superman,i am superman,i am superman,i am superman,i am superman,\r\ni am superman,i am superman,\r\ni am superman,i am superman,i am superman,i am superman,i am superman,i am superman,\r\n\r\ni am superman,i am superman,i am superman,\r\ni am superman,i am superman,i am superman,i am superman,\r\ni am superman,i am superman,i am superman,\r\ni am superman,i am superman,i am superman,i am superman,',750.00,'i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,i am superman,','[\"i am superman,i am superman,\", \"i am superman,\"]','i am superman,i am superman,','i am superman,i am superman,','i am superman,i am superman,','i am superman,i am superman,',7,'accepted',NULL,'2025-11-13 12:36:38','2025-11-13 12:37:49','2025-11-13 12:36:38','2025-11-13 12:37:49'),(3,3,1,1,'this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overview','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my g\r\n\r\nthis is my guide bidding testing overviewuide bidding testing overviewthis is my guide bidding testing overview\r\n\r\nthis is my guide bidding testing overviewthis is my guide bidding testing overview',1800.00,'this is my guide bidding testing overview\r\nthis is my guide bidding testing overviewthis is my guide bidding testing overview\r\nthis is my guide bidding testing overview\r\nthis is my guide bidding testing overview\r\nthis is my guide bidding testing overview\r\nthis is my guide bidding testing overviewthis is my guide bidding testing overview','[\"ella\", \"badualla\", \"haputhale\"]','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overview','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overview','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overview','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overview',5,'accepted',NULL,'2025-11-30 09:24:15','2025-11-30 09:27:33','2025-11-30 09:24:15','2025-11-30 09:27:33'),(4,4,1,1,'Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)','Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)',3000.00,'Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)','[\"kandy\", \"colombo\", \"anuradapura\"]','Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)','Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)','Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)','Introduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)\r\nIntroduce yourself and explain why you\'re the best guide for this tour (100-2000 characters)',4,'pending',NULL,'2025-12-02 12:22:06',NULL,'2025-12-02 12:22:06','2025-12-02 12:22:06'),(5,5,1,1,'this is my best proposal,this is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal','this is my best proposalthis is my best proposal\r\nthis is my best proposal\r\nthis is my best proposalthis is my best proposal\r\nthis is my best proposalthis is my best proposalthis is my best proposal\r\nthis is my best proposal\r\nthis is my best proposal\r\nthis is my best proposalthis is my best proposal',1500.00,'this is my best proposalthis is my best proposalthis is my best proposal\r\nthis is my best proposal\r\nthis is my best proposalthis is my best proposal\r\nthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal\r\nthis is my best proposalthis is my best proposal','[\"kandy\", \"colombo\", \"galle\", \"ella\"]','this is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal','this is my best proposalthis is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal','this is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal','this is my best proposalthis is my best proposalthis is my best proposalthis is my best proposal',7,'pending',NULL,'2025-12-19 18:09:34',NULL,'2025-12-19 18:09:34','2025-12-19 18:09:34');
/*!40000 ALTER TABLE `bids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_addons`
--

DROP TABLE IF EXISTS `booking_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `guide_plan_addon_id` bigint unsigned DEFAULT NULL,
  `addon_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addon_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_number` int NOT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `num_participants` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_addons_guide_plan_addon_id_foreign` (`guide_plan_addon_id`),
  KEY `booking_addons_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_addons_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_addons_guide_plan_addon_id_foreign` FOREIGN KEY (`guide_plan_addon_id`) REFERENCES `guide_plan_addons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_addons`
--

LOCK TABLES `booking_addons` WRITE;
/*!40000 ALTER TABLE `booking_addons` DISABLE KEYS */;
INSERT INTO `booking_addons` VALUES (1,19,1,'Add on one','we offer beach party',1,10.00,1,10.00,'2025-12-19 23:20:29');
/*!40000 ALTER TABLE `booking_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_payments`
--

DROP TABLE IF EXISTS `booking_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL,
  `guide_payout` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount_remaining` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_payments_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_payments`
--

LOCK TABLES `booking_payments` WRITE;
/*!40000 ALTER TABLE `booking_payments` DISABLE KEYS */;
INSERT INTO `booking_payments` VALUES (1,8,55.00,5.00,45.00,0.00,45.00,'2025-11-13 22:59:57'),(2,9,55.00,5.00,45.00,0.00,45.00,'2025-11-13 22:59:57'),(3,10,55.00,5.00,45.00,35.00,10.00,'2025-11-30 14:20:02'),(4,12,1980.00,180.00,1620.00,1620.00,0.00,'2025-11-30 15:29:46'),(5,13,49.50,4.50,45.00,20.00,25.00,'2025-11-30 18:44:58'),(6,14,66.00,6.00,60.00,0.00,0.00,'2025-12-02 18:23:58'),(7,16,55.00,5.00,45.00,0.00,0.00,'2025-12-19 22:50:42'),(8,19,66.00,6.00,54.00,0.00,0.00,'2025-12-19 23:31:34');
/*!40000 ALTER TABLE `booking_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_status_history`
--

DROP TABLE IF EXISTS `booking_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_status_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `old_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `changed_by` bigint unsigned DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_status_history_changed_by_foreign` (`changed_by`),
  KEY `booking_status_history_booking_id_index` (`booking_id`),
  CONSTRAINT `booking_status_history_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_status_history_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_status_history`
--

LOCK TABLES `booking_status_history` WRITE;
/*!40000 ALTER TABLE `booking_status_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_vehicle_assignments`
--

DROP TABLE IF EXISTS `booking_vehicle_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_vehicle_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `is_temporary` tinyint(1) NOT NULL DEFAULT '0',
  `temporary_vehicle_data` json DEFAULT NULL,
  `assigned_at` timestamp NOT NULL,
  `assigned_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_vehicle_assignments_booking_id_unique` (`booking_id`),
  KEY `booking_vehicle_assignments_vehicle_id_foreign` (`vehicle_id`),
  KEY `booking_vehicle_assignments_assigned_by_foreign` (`assigned_by`),
  KEY `booking_vehicle_assignments_is_temporary_index` (`is_temporary`),
  KEY `booking_vehicle_assignments_assigned_at_index` (`assigned_at`),
  CONSTRAINT `booking_vehicle_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_vehicle_assignments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_vehicle_assignments_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_vehicle_assignments`
--

LOCK TABLES `booking_vehicle_assignments` WRITE;
/*!40000 ALTER TABLE `booking_vehicle_assignments` DISABLE KEYS */;
INSERT INTO `booking_vehicle_assignments` VALUES (1,14,1,0,NULL,'2025-12-18 19:29:00',3,'2025-12-18 19:29:00','2025-12-18 19:29:00');
/*!40000 ALTER TABLE `booking_vehicle_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_type` enum('guide_plan','custom_request','plan_proposal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tourist_id` bigint unsigned NOT NULL,
  `guide_id` bigint unsigned NOT NULL,
  `guide_plan_id` bigint unsigned DEFAULT NULL,
  `tourist_request_id` bigint unsigned DEFAULT NULL,
  `accepted_bid_id` bigint unsigned DEFAULT NULL,
  `accepted_proposal_id` bigint unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `num_adults` int NOT NULL,
  `num_children` int NOT NULL DEFAULT '0',
  `children_ages` json DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `addons_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `guide_payout` decimal(10,2) NOT NULL,
  `status` enum('pending_payment','payment_failed','confirmed','upcoming','ongoing','completed','cancelled_by_tourist','cancelled_by_guide','cancelled_by_admin') COLLATE utf8mb4_unicode_ci DEFAULT 'pending_payment',
  `payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_completed_at` timestamp NULL DEFAULT NULL,
  `tourist_notes` text COLLATE utf8mb4_unicode_ci,
  `guide_notes` text COLLATE utf8mb4_unicode_ci,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `agreement_pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_guide_plan_id_foreign` (`guide_plan_id`),
  KEY `bookings_tourist_request_id_foreign` (`tourist_request_id`),
  KEY `bookings_accepted_bid_id_foreign` (`accepted_bid_id`),
  KEY `bookings_booking_number_index` (`booking_number`),
  KEY `bookings_tourist_id_index` (`tourist_id`),
  KEY `bookings_guide_id_index` (`guide_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_start_date_index` (`start_date`),
  KEY `bookings_accepted_proposal_id_foreign` (`accepted_proposal_id`),
  CONSTRAINT `bookings_accepted_bid_id_foreign` FOREIGN KEY (`accepted_bid_id`) REFERENCES `bids` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_accepted_proposal_id_foreign` FOREIGN KEY (`accepted_proposal_id`) REFERENCES `plan_proposals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_tourist_request_id_foreign` FOREIGN KEY (`tourist_request_id`) REFERENCES `tourist_requests` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (4,'BK-20251110-437998','guide_plan',3,1,1,NULL,NULL,NULL,'2025-12-03','2025-12-05',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-10 15:23:56','2025-11-10 15:23:56'),(8,'BK-20251110-E2901C','guide_plan',3,1,1,NULL,NULL,NULL,'2025-11-13','2025-11-15',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'ongoing',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251110-E2901C.pdf','cs_test_a1d3G6R1OmkPnYvQXIMscLB6FyEXZKbte2EGoNzDyCHkRUCebqDprTXnUj','pi_3ST3jE3WHl0IQbsv0Ak0FE9i','2025-11-13 11:20:38','2025-11-10 15:47:50','2025-11-13 16:03:00'),(9,'BK-20251113-43D861','guide_plan',3,1,1,NULL,NULL,NULL,'2025-11-20','2025-11-22',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'upcoming',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251113-43D861.pdf','cs_test_a1uk41Yvotn96Sl2DUXFru0nr1MvDgV2COapPc4gEy7XdkYxcn8dqKcZjR','pi_3ST3Zj3WHl0IQbsv0A0yzVkf','2025-11-13 11:10:50','2025-11-13 09:39:24','2025-11-13 16:03:00'),(10,'BK-20251130-77E06A','guide_plan',3,1,1,NULL,NULL,NULL,'2025-12-03','2025-12-05',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'confirmed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251130-77E06A.pdf','cs_test_a12Ox3qDVprsIsM5yrxAFr7PthGeMOj5q0zaLV4iq1DAfflht6wYVt1LtQ','pi_3SZBTn3WHl0IQbsv0l3oAVAr','2025-11-30 08:50:02','2025-11-30 08:47:11','2025-11-30 08:50:02'),(12,'BK-20251130-D477A2','custom_request',3,1,NULL,3,3,NULL,'2025-12-02','2025-12-07',2,1,NULL,1800.00,0.00,1800.00,180.00,1980.00,1620.00,'confirmed',NULL,NULL,NULL,'tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1','this is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my guide bidding testing overviewthis is my g\r\n\r\nthis is my guide bidding testing overviewuide bidding testing overviewthis is my guide bidding testing overview\r\n\r\nthis is my guide bidding testing overviewthis is my guide bidding testing overview',NULL,NULL,'agreements/booking-BK-20251130-D477A2.pdf','cs_test_a1oI3BlkkrwT6HfDSqcsVlUEBatWu48vnuS4QG7yrLKVkXd3ReTj1j9GJl','pi_3SZCZG3WHl0IQbsv1aJSTnce','2025-11-30 09:59:46','2025-11-30 09:27:33','2025-11-30 09:59:46'),(13,'BKHMMDC8RR','plan_proposal',3,1,1,NULL,NULL,1,'2025-12-09','2025-12-11',1,0,NULL,45.00,0.00,45.00,4.50,49.50,45.00,'confirmed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BKHMMDC8RR.pdf','cs_test_a1j6NCIRjTxDhzfDJkOLW1FGF7SAvJ4iXUlWSl18Rhzl9aMLqzhWkyGkhU','pi_3SZFcC3WHl0IQbsv1GqCmnL1','2025-11-30 13:14:58','2025-11-30 13:04:16','2025-11-30 13:14:58'),(14,'BK3HVDA2SW','plan_proposal',3,1,1,NULL,NULL,2,'2026-01-01','2026-01-03',1,0,NULL,60.00,0.00,60.00,6.00,66.00,60.00,'confirmed',NULL,NULL,NULL,'Modifications requested: ,jvhcgxfggfyuiuo\'ij;k,bvmbnvfhdturi',NULL,NULL,NULL,'agreements/booking-BK3HVDA2SW.pdf','cs_test_a11H7xksB4TUBa9ZxVlnUyLDaHLlumtnc7mvYNNkVeGXGLqfKtaW3cTD9r','pi_3SZyEt3WHl0IQbsv118Ny7UZ','2025-12-02 12:53:58','2025-12-02 12:48:21','2025-12-02 12:53:58'),(15,'BK-20251219-AA0C7A','guide_plan',3,1,1,NULL,NULL,NULL,'2025-12-24','2025-12-26',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'pending_payment',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251219-AA0C7A.pdf','cs_test_a1wLiUNOoHxihev3xeZRzuvNqFbqDlmumuFg8xvRVIjbnHhfGW4QxmW50R',NULL,NULL,'2025-12-19 10:30:42','2025-12-19 10:31:33'),(16,'BK-20251219-CEF0A5','guide_plan',3,1,1,NULL,NULL,NULL,'2026-01-06','2026-01-08',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'confirmed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251219-CEF0A5.pdf','cs_test_a1oBxej9HKMBnrHbrJ0zRFFfVSgRTZh8bONWhiu88es7KIbbbiyc8wqtOv','pi_3SgCVN3WHl0IQbsv1n67c6YF','2025-12-19 17:20:42','2025-12-19 10:39:00','2025-12-19 17:20:42'),(17,'BK-20251219-8DADA7','guide_plan',3,1,1,NULL,NULL,NULL,'2025-12-26','2025-12-28',1,0,NULL,50.00,0.00,50.00,5.00,55.00,45.00,'confirmed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251219-8DADA7.pdf','cs_test_a12fER70o0yZTxgn7JYjSm1ciCHu2sQvpicjEoetw0rccaCv1h2ywevYDp','pi_3SZyEt3DHl0IQbsv118Ny7UZ','2025-12-19 17:02:40','2025-12-19 17:02:40','2025-12-19 17:04:15'),(19,'BK-20251219-D2DA22','guide_plan',3,1,1,NULL,NULL,NULL,'2025-12-29','2025-12-31',1,0,NULL,50.00,10.00,60.00,6.00,66.00,54.00,'confirmed',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'agreements/booking-BK-20251219-D2DA22.pdf','cs_test_a1a0m7jcdB3Zq6hZ85KT4ralwKweEa1UxFO6xadxuMFQAZNC0NpEfSgbBG','pi_3SgD8v3WHl0IQbsv0YL1XCim','2025-12-19 18:01:34','2025-12-19 17:50:29','2025-12-19 18:01:34');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('travelagency-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1766187518),('travelagency-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1766187518;',1766187518),('travelagency-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3','i:1;',1766183748),('travelagency-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer','i:1766183748;',1766183748);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaint_responses`
--

DROP TABLE IF EXISTS `complaint_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaint_responses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned DEFAULT NULL,
  `responder_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responder_id` bigint unsigned DEFAULT NULL,
  `response_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `response_type` enum('email','internal_note','status_update','public_note','request_info','evidence_submission','defendant_response','complainant_response') COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_to_complainant` tinyint(1) NOT NULL DEFAULT '1',
  `visible_to_defendant` tinyint(1) NOT NULL DEFAULT '1',
  `internal_only` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `complaint_responses_admin_id_foreign` (`admin_id`),
  KEY `complaint_responses_complaint_id_index` (`complaint_id`),
  KEY `complaint_responses_responder_type_responder_id_index` (`responder_type`,`responder_id`),
  KEY `complaint_responses_internal_only_index` (`internal_only`),
  CONSTRAINT `complaint_responses_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaint_responses_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaint_responses`
--

LOCK TABLES `complaint_responses` WRITE;
/*!40000 ALTER TABLE `complaint_responses` DISABLE KEYS */;
INSERT INTO `complaint_responses` VALUES (1,2,NULL,'tourist',3,'what is the statue could you inform me','[]','complainant_response',1,1,0,'2025-12-02 11:56:08'),(2,2,NULL,'tourist',3,'hi i am the bat man,','[]','complainant_response',1,1,0,'2025-12-02 12:03:03'),(3,2,1,'admin',1,' i have assiged admin 1 for this and its under review','[]','public_note',1,1,0,'2025-12-02 14:06:10'),(4,2,NULL,'tourist',3,'cool cool very well wee done','[]','complainant_response',1,1,0,'2025-12-02 14:08:26'),(5,2,1,'admin',1,'hai hai hai ahia aadlfkajlsfj alffeqwef','[\"complaint-attachments/01KBFSDWF9FB8Z7SW59GCSB1X8.jpg\"]','public_note',1,0,0,'2025-12-02 15:03:22'),(6,3,NULL,'guide',1,'ghghkjhkyutyrstedfxcvhbjli','[]','complainant_response',1,1,0,'2025-12-02 18:03:09'),(7,3,NULL,'guide',1,'Complaint withdrawn by complainant.',NULL,'status_update',1,1,0,'2025-12-02 18:03:51'),(8,1,1,'admin',1,' ,nbvgcfxgdtuytiuyiuo;jlknmbn bvbx','[\"complaint-attachments/01KBG5NCHJFB2Y9XPRX3R7ZHNB.jpg\"]','status_update',1,1,0,'2025-12-02 18:37:11');
/*!40000 ALTER TABLE `complaint_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `complaint_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` bigint unsigned DEFAULT NULL,
  `filed_by` bigint unsigned NOT NULL,
  `filed_by_type` enum('tourist','guide') COLLATE utf8mb4_unicode_ci NOT NULL,
  `complainant_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complainant_id` bigint unsigned DEFAULT NULL,
  `against_user_id` bigint unsigned NOT NULL,
  `against_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `against_id` bigint unsigned DEFAULT NULL,
  `complaint_type` enum('service_quality','safety_concern','unprofessional_behavior','payment_issue','cancellation_dispute','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence_files` json DEFAULT NULL,
  `status` enum('open','under_review','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `resolution_summary` text COLLATE utf8mb4_unicode_ci,
  `visible_to_defendant` tinyint(1) NOT NULL DEFAULT '1',
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_complaint_number_unique` (`complaint_number`),
  KEY `complaints_filed_by_foreign` (`filed_by`),
  KEY `complaints_against_user_id_foreign` (`against_user_id`),
  KEY `complaints_complaint_number_index` (`complaint_number`),
  KEY `complaints_booking_id_index` (`booking_id`),
  KEY `complaints_status_index` (`status`),
  KEY `complaints_assigned_to_index` (`assigned_to`),
  KEY `complaints_against_type_against_id_index` (`against_type`,`against_id`),
  KEY `complaints_complainant_type_complainant_id_index` (`complainant_type`,`complainant_id`),
  CONSTRAINT `complaints_against_user_id_foreign` FOREIGN KEY (`against_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `complaints_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `complaints_filed_by_foreign` FOREIGN KEY (`filed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
/*!40000 ALTER TABLE `complaints` DISABLE KEYS */;
INSERT INTO `complaints` VALUES (1,'CMP-20251202-0001',10,5,'tourist','tourist',3,3,'guide',1,'service_quality','bad service','bad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad servicebad service','[\"complaint-evidence/c4WgTumzkRGWKuoYtJleJLf17eUuXqWdOnVNLpk2.jpg\"]','under_review','medium',1,NULL,NULL,1,NULL,'2025-12-02 05:55:11','2025-12-02 09:00:24'),(2,'CMP-20251202-0002',12,5,'tourist','tourist',3,3,'guide',1,'cancellation_dispute','this guide has been canceled the tour even i have paid??','this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??this guide has been canceled the tour even i have paid??','[\"complaint-evidence/vyL8phwHaYsJZWaCtXRchDkmM5R1RP48G6IudwdM.jpg\"]','under_review','medium',1,'i have assigned this for admin 1',NULL,0,NULL,'2025-12-02 06:12:31','2025-12-02 09:37:33'),(3,'CMP-20251202-0003',13,3,'guide','guide',1,5,'tourist',3,'payment_issue','gfdzfdtfj','ajadi alkdsjfi ajadi alkdsjfi ajadi alkdsjfi ajadi alkdsjfi','[\"complaint-evidence/WB23ZdZWfaaCxX8NkFUUTCJJekv928xeAcO9uK0J.jpg\"]','closed','medium',NULL,NULL,'Withdrawn by complainant',1,NULL,'2025-12-02 12:32:19','2025-12-02 12:33:51');
/*!40000 ALTER TABLE `complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tourist_id` bigint unsigned NOT NULL,
  `guide_plan_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_tourist_id_guide_plan_id_unique` (`tourist_id`,`guide_plan_id`),
  KEY `favorites_guide_plan_id_foreign` (`guide_plan_id`),
  KEY `favorites_tourist_id_index` (`tourist_id`),
  CONSTRAINT `favorites_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_payment_transactions`
--

DROP TABLE IF EXISTS `guide_payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_payment_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `paid_by_admin` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guide_payment_transactions_booking_payment_id_index` (`booking_payment_id`),
  KEY `guide_payment_transactions_payment_date_index` (`payment_date`),
  KEY `guide_payment_transactions_paid_by_admin_index` (`paid_by_admin`),
  CONSTRAINT `guide_payment_transactions_booking_payment_id_foreign` FOREIGN KEY (`booking_payment_id`) REFERENCES `booking_payments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `guide_payment_transactions_paid_by_admin_foreign` FOREIGN KEY (`paid_by_admin`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_payment_transactions`
--

LOCK TABLES `guide_payment_transactions` WRITE;
/*!40000 ALTER TABLE `guide_payment_transactions` DISABLE KEYS */;
INSERT INTO `guide_payment_transactions` VALUES (1,3,5.00,'2025-12-01 16:36:00','cash',NULL,NULL,1,'2025-12-01 16:36:36','2025-12-01 16:36:36'),(2,3,10.00,'2025-12-01 16:37:00','bank_transfer',NULL,NULL,1,'2025-12-01 16:37:46','2025-12-01 16:37:46'),(3,5,20.00,'2025-12-02 13:00:00','bank_transfer',NULL,NULL,1,'2025-12-02 13:00:18','2025-12-02 13:00:18');
/*!40000 ALTER TABLE `guide_payment_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_plan_addons`
--

DROP TABLE IF EXISTS `guide_plan_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_plan_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_plan_id` bigint unsigned NOT NULL,
  `day_number` int NOT NULL,
  `addon_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addon_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `require_all_participants` tinyint(1) NOT NULL DEFAULT '0',
  `max_participants` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_plan_addons_guide_plan_id_index` (`guide_plan_id`),
  CONSTRAINT `guide_plan_addons_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_plan_addons`
--

LOCK TABLES `guide_plan_addons` WRITE;
/*!40000 ALTER TABLE `guide_plan_addons` DISABLE KEYS */;
INSERT INTO `guide_plan_addons` VALUES (1,1,1,'Add on one','we offer beach party',10.00,0,5,'2025-12-19 23:05:28');
/*!40000 ALTER TABLE `guide_plan_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_plan_itineraries`
--

DROP TABLE IF EXISTS `guide_plan_itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_plan_itineraries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_plan_id` bigint unsigned NOT NULL,
  `day_number` int NOT NULL,
  `day_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `accommodation_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accommodation_type` enum('hotel','guesthouse','resort','homestay','camping','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accommodation_tier` enum('budget','midrange','luxury') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breakfast_included` tinyint(1) NOT NULL DEFAULT '0',
  `lunch_included` tinyint(1) NOT NULL DEFAULT '0',
  `dinner_included` tinyint(1) NOT NULL DEFAULT '0',
  `meal_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_plan_itineraries_guide_plan_id_index` (`guide_plan_id`),
  KEY `guide_plan_itineraries_guide_plan_id_day_number_index` (`guide_plan_id`,`day_number`),
  CONSTRAINT `guide_plan_itineraries_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_plan_itineraries`
--

LOCK TABLES `guide_plan_itineraries` WRITE;
/*!40000 ALTER TABLE `guide_plan_itineraries` DISABLE KEYS */;
INSERT INTO `guide_plan_itineraries` VALUES (1,1,1,'day first','this s the day first','hotel one','hotel','budget',1,1,1,'proper meals','2025-12-19 22:30:14');
/*!40000 ALTER TABLE `guide_plan_itineraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_plan_photos`
--

DROP TABLE IF EXISTS `guide_plan_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_plan_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_plan_id` bigint unsigned NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guide_plan_photos_guide_plan_id_index` (`guide_plan_id`),
  CONSTRAINT `guide_plan_photos_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_plan_photos`
--

LOCK TABLES `guide_plan_photos` WRITE;
/*!40000 ALTER TABLE `guide_plan_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_plan_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_plans`
--

DROP TABLE IF EXISTS `guide_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_days` int NOT NULL,
  `num_nights` int NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dropoff_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destinations` json NOT NULL,
  `trip_focus_tags` json NOT NULL,
  `price_per_adult` decimal(10,2) NOT NULL,
  `price_per_child` decimal(10,2) NOT NULL,
  `max_group_size` int NOT NULL,
  `min_group_size` int NOT NULL DEFAULT '1',
  `availability_type` enum('date_range','always_available') COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_start_date` date DEFAULT NULL,
  `available_end_date` date DEFAULT NULL,
  `vehicle_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_capacity` int DEFAULT NULL,
  `vehicle_ac` tinyint(1) NOT NULL DEFAULT '1',
  `vehicle_description` text COLLATE utf8mb4_unicode_ci,
  `dietary_options` json DEFAULT NULL,
  `accessibility_info` text COLLATE utf8mb4_unicode_ci,
  `inclusions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exclusions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `allow_proposals` tinyint(1) NOT NULL DEFAULT '1',
  `min_proposal_price` decimal(10,2) DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `booking_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guide_plans_guide_id_index` (`guide_id`),
  KEY `guide_plans_status_index` (`status`),
  KEY `guide_plans_availability_type_index` (`availability_type`),
  KEY `guide_plans_price_per_adult_index` (`price_per_adult`),
  KEY `guide_plans_num_days_index` (`num_days`),
  KEY `guide_plans_available_start_date_available_end_date_index` (`available_start_date`,`available_end_date`),
  CONSTRAINT `guide_plans_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_plans`
--

LOCK TABLES `guide_plans` WRITE;
/*!40000 ALTER TABLE `guide_plans` DISABLE KEYS */;
INSERT INTO `guide_plans` VALUES (1,1,'Kelani Tour','Campus ek wate tour eka...',3,2,'Colombo International Airport','Colombo International Airport','[\"Thalweta\", \"Saibe\", \"Ground\", \"Walekade\", \"Bording\"]','[\"Adventure\"]',50.00,30.00,2,1,'always_available',NULL,NULL,'Car',2,1,'Parana minor car..','[\"vegetarian\", \"vegan\", \"halal\"]','No accessibility support.','Bonna Denawa','Kanna denna','guide-plans/covers/dMrGHaCzmhfYQsy5LIE9yfinUUi4AO1NOoriA2l8.jpg','active',1,NULL,38,0,'2025-11-05 12:04:40','2025-12-18 11:55:20');
/*!40000 ALTER TABLE `guide_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guide_registration_requests`
--

DROP TABLE IF EXISTS `guide_registration_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guide_registration_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guide_type` enum('chauffeur_guide','national_guide','area_guide','site_guide','tourist_driver','wildlife_tracker','trekking_guide','not_specified') COLLATE utf8mb4_unicode_ci DEFAULT 'not_specified',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `years_experience` int NOT NULL,
  `languages` json NOT NULL,
  `expertise_areas` json NOT NULL,
  `regions_can_guide` json NOT NULL,
  `experience_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id_document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driving_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guide_certificate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_certificates` json DEFAULT NULL,
  `status` enum('documents_pending','documents_verified','interview_scheduled','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'documents_pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `interview_date` date DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guide_registration_requests_email_unique` (`email`),
  KEY `guide_registration_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `guide_registration_requests_email_index` (`email`),
  KEY `guide_registration_requests_status_index` (`status`),
  CONSTRAINT `guide_registration_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guide_registration_requests`
--

LOCK TABLES `guide_registration_requests` WRITE;
/*!40000 ALTER TABLE `guide_registration_requests` DISABLE KEYS */;
INSERT INTO `guide_registration_requests` VALUES (1,'yehan','not_specified','kdilhan50@gmail.com','0775685925','200100031171',1,'[\"English\"]','[\"Cultural Sites\", \"Wildlife\", \"Adventure\", \"Beaches\"]','[\"Kandy\", \"Ella\", \"Sigiriya\", \"Galle\"]','mam thamai batman..','guide-documents/profile-photos/9yajZxvID4qf73fU7SG54ZDyeZC9nEDp5x2ufoT5.png','',NULL,NULL,NULL,'rejected','nic is existing',NULL,1,'2025-11-05 09:13:32','2025-10-12 15:06:49','2025-11-05 09:13:32'),(2,'kaushalya','not_specified','kanishkatest41@gmail.com','0778888889','200100031171',2,'[\"English\", \"Sinhala\"]','[\"Cultural Sites\", \"Hill Country\", \"Tea Plantations\"]','[\"Ella\", \"Galle\", \"Yala\"]','mma thamai super man...','guide-documents/profile-photos/8pvtyWZu7IyXohCQXs7cEqHLWQgS054kU3TvfjVG.jpg','',NULL,NULL,NULL,'approved',NULL,NULL,1,'2025-11-05 08:20:11','2025-10-12 15:21:34','2025-11-05 08:20:11'),(3,'lakshitha','chauffeur_guide','lakshitha@gmail.com','0775685921','200147854789',2,'[\"English\", \"Sinhala\"]','[\"Cultural Sites\", \"Wildlife\", \"Adventure\"]','[\"Kandy\", \"Nuwara Eliya\", \"Ella\"]','','guide-documents/profile-photos/4PlsyehnQteyUUUucYKa0qNiHxsVKthziGZcoaBr.jpg','guide-documents/national-id/4tiapzX4J6jeiG9ZSu0byxo1sJckiApxTswLlWfn.jpg','guide-documents/driving-license/l39yi9cev1SSbm3rSQg6ya6fRtgI7sA2kKii128e.jpg','guide-documents/certificates/34SuqYCMF46w9xhAbGkiGwqb4vdM5h7tdC4roCKD.jpg',NULL,'approved',NULL,NULL,1,'2025-12-18 20:09:55','2025-12-18 11:52:30','2025-12-18 20:09:55');
/*!40000 ALTER TABLE `guide_registration_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guides`
--

DROP TABLE IF EXISTS `guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `guide_id_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guide_type` enum('chauffeur_guide','national_guide','area_guide','site_guide','tourist_driver','wildlife_tracker','trekking_guide','not_specified') COLLATE utf8mb4_unicode_ci DEFAULT 'not_specified',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `languages` json DEFAULT NULL,
  `expertise_areas` json DEFAULT NULL,
  `regions_can_guide` json DEFAULT NULL,
  `years_experience` int NOT NULL DEFAULT '0',
  `average_rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_reviews` int NOT NULL DEFAULT '0',
  `total_bookings` int NOT NULL DEFAULT '0',
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `insurance_policy_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_expiry` date DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '90.00',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guides_user_id_unique` (`user_id`),
  UNIQUE KEY `guides_guide_id_number_unique` (`guide_id_number`),
  KEY `guides_guide_id_number_index` (`guide_id_number`),
  KEY `guides_average_rating_index` (`average_rating`),
  CONSTRAINT `guides_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guides`
--

LOCK TABLES `guides` WRITE;
/*!40000 ALTER TABLE `guides` DISABLE KEYS */;
INSERT INTO `guides` VALUES (1,3,'GD-2025-0001','kaushalya','national_guide','0778888889','200100031171','mma thamai super man...','guide-documents/profile-photos/8pvtyWZu7IyXohCQXs7cEqHLWQgS054kU3TvfjVG.jpg','[\"English\", \"Sinhala\"]','[\"Cultural Sites\", \"Hill Country\", \"Tea Plantations\"]','[\"Ella\", \"Galle\", \"Yala\"]',2,0.00,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,90.00,NULL,'2025-11-05 08:20:11','2025-12-18 11:50:27'),(2,6,'GD-2025-0002','lakshitha','chauffeur_guide','0775685921','200147854789','','guide-documents/profile-photos/4PlsyehnQteyUUUucYKa0qNiHxsVKthziGZcoaBr.jpg','[\"English\", \"Sinhala\"]','[\"Cultural Sites\", \"Wildlife\", \"Adventure\"]','[\"Kandy\", \"Nuwara Eliya\", \"Ella\"]',2,0.00,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,90.00,NULL,'2025-12-18 20:09:55','2025-12-18 20:09:55');
/*!40000 ALTER TABLE `guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_10_11_204102_create_tourists_table',1),(5,'2025_10_11_204539_create_guides_table',1),(6,'2025_10_11_204608_create_admins_table',1),(7,'2025_10_11_204649_create_guide_registration_requests_table',1),(8,'2025_10_11_204714_create_system_settings_table',1),(9,'2025_10_11_204737_create_notifications_table',1),(10,'2025_10_11_204804_create_guide_plans_table',1),(11,'2025_10_11_204842_create_guide_plan_photos_table',1),(12,'2025_10_11_204920_create_guide_plan_vehicle_photos_table',1),(13,'2025_10_11_205018_create_guide_plan_itineraries_table',1),(14,'2025_10_11_205045_create_guide_plan_addons_table',1),(15,'2025_10_11_205110_create_tourist_requests_table',1),(16,'2025_10_11_205137_create_bids_table',1),(17,'2025_10_11_205202_create_bookings_table',1),(18,'2025_10_11_205232_create_booking_addons_table',1),(19,'2025_10_11_205313_create_booking_status_history_table',1),(20,'2025_10_11_205347_create_booking_payments_table',1),(21,'2025_10_11_205612_create_reviews_table',1),(22,'2025_10_11_205637_create_review_photos_table',1),(23,'2025_10_11_205704_create_favorites_table',1),(24,'2025_10_11_205828_create_recently_viewed_table',1),(25,'2025_10_11_205855_create_complaints_table',1),(26,'2025_10_11_205924_create_complaint_responses_table',1),(27,'2025_11_10_211640_add_agreement_pdf_path_to_bookings_table',2),(28,'2025_11_10_214116_create_customer_columns',3),(29,'2025_11_10_214117_create_subscriptions_table',3),(30,'2025_11_10_214118_create_subscription_items_table',3),(31,'2025_11_10_214119_add_meter_id_to_subscription_items_table',3),(32,'2025_11_10_214120_add_meter_event_name_to_subscription_items_table',3),(33,'2025_11_10_214550_add_stripe_fields_to_bookings_table',4),(35,'2025_11_13_213226_add_upcoming_status_to_bookings_table',5),(36,'2025_11_30_180547_create_plan_proposals_table',6),(37,'2025_11_30_180634_add_proposal_settings_to_guide_plans_table',7),(38,'2025_11_30_180703_add_accepted_proposal_id_to_bookings_table',7),(39,'2025_12_01_000001_add_plan_proposal_to_booking_type_enum',8),(40,'2025_12_01_213319_add_payment_tracking_to_booking_payments_table',9),(42,'2025_12_01_213427_create_guide_payment_transactions_table',10),(43,'2025_12_01_220122_remove_redundant_columns_from_booking_payments_table',10),(44,'2025_12_02_101203_optimize_complaints_system',11),(45,'2025_12_02_154537_drop_visible_to_public_column_from_complaints_table',12),(46,'2025_12_18_170548_add_guide_type_to_guides_and_registration_requests',13),(47,'2025_12_18_175849_create_regions_table',13),(48,'2025_12_19_000001_vehicle_management_system',14),(49,'2025_12_19_000002_remove_vehicle_fields_from_registration_requests',15),(50,'2025_12_19_000003_add_year_and_photo_to_vehicles',16),(51,'2025_12_19_003636_remove_vehicle_category_from_guide_plans',17),(52,'2025_12_19_014637_add_admin_notes_to_guides_table',18),(53,'2025_12_19_201358_remove_cancellation_policy_from_guide_plans_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `notification_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_index` (`user_id`),
  KEY `notifications_status_index` (`status`),
  KEY `notifications_notification_type_index` (`notification_type`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_proposals`
--

DROP TABLE IF EXISTS `plan_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_plan_id` bigint unsigned NOT NULL,
  `tourist_id` bigint unsigned NOT NULL,
  `proposed_price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `num_adults` int unsigned NOT NULL DEFAULT '1',
  `num_children` int unsigned NOT NULL DEFAULT '0',
  `children_ages` json DEFAULT NULL,
  `modifications` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','accepted','rejected','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `booking_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_proposals_booking_id_foreign` (`booking_id`),
  KEY `plan_proposals_guide_plan_id_status_index` (`guide_plan_id`,`status`),
  KEY `plan_proposals_tourist_id_status_index` (`tourist_id`,`status`),
  KEY `plan_proposals_start_date_index` (`start_date`),
  CONSTRAINT `plan_proposals_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `plan_proposals_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plan_proposals_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_proposals`
--

LOCK TABLES `plan_proposals` WRITE;
/*!40000 ALTER TABLE `plan_proposals` DISABLE KEYS */;
INSERT INTO `plan_proposals` VALUES (1,1,3,45.00,'2025-12-09','2025-12-11',1,0,NULL,NULL,NULL,'accepted',NULL,13,'2025-11-30 12:58:05','2025-11-30 13:04:16'),(2,1,3,60.00,'2026-01-01','2026-01-03',1,0,NULL,',jvhcgxfggfyuiuo\'ij;k,bvmbnvfhdturi',NULL,'accepted',NULL,14,'2025-12-02 12:45:21','2025-12-02 12:48:21'),(3,1,3,60.00,'2025-12-22','2025-12-24',1,0,NULL,NULL,NULL,'pending',NULL,NULL,'2025-12-19 18:03:49','2025-12-19 18:03:49');
/*!40000 ALTER TABLE `plan_proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recently_viewed`
--

DROP TABLE IF EXISTS `recently_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recently_viewed` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tourist_id` bigint unsigned NOT NULL,
  `guide_plan_id` bigint unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `recently_viewed_guide_plan_id_foreign` (`guide_plan_id`),
  KEY `recently_viewed_tourist_id_viewed_at_index` (`tourist_id`,`viewed_at`),
  CONSTRAINT `recently_viewed_guide_plan_id_foreign` FOREIGN KEY (`guide_plan_id`) REFERENCES `guide_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recently_viewed_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_viewed`
--

LOCK TABLES `recently_viewed` WRITE;
/*!40000 ALTER TABLE `recently_viewed` DISABLE KEYS */;
/*!40000 ALTER TABLE `recently_viewed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regions_name_unique` (`name`),
  KEY `regions_is_active_sort_order_index` (`is_active`,`sort_order`),
  KEY `regions_province_index` (`province`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Colombo','Western Province',1,1,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(2,'Negombo','Western Province',1,2,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(3,'Mount Lavinia','Western Province',1,3,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(4,'Kalutara','Western Province',1,4,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(5,'Kandy','Central Province',1,5,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(6,'Nuwara Eliya','Central Province',1,6,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(7,'Sigiriya','Central Province',1,7,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(8,'Dambulla','Central Province',1,8,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(9,'Matale','Central Province',1,9,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(10,'Knuckles Range','Central Province',1,10,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(11,'Galle','Southern Province',1,11,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(12,'Unawatuna','Southern Province',1,12,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(13,'Mirissa','Southern Province',1,13,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(14,'Weligama','Southern Province',1,14,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(15,'Tangalle','Southern Province',1,15,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(16,'Hikkaduwa','Southern Province',1,16,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(17,'Bentota','Southern Province',1,17,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(18,'Yala National Park','Southern Province',1,18,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(19,'Udawalawe National Park','Southern Province',1,19,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(20,'Bundala National Park','Southern Province',1,20,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(21,'Tissamaharama','Southern Province',1,21,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(22,'Hambantota','Southern Province',1,22,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(23,'Ella','Uva Province',1,23,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(24,'Bandarawela','Uva Province',1,24,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(25,'Haputale','Uva Province',1,25,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(26,'Badulla','Uva Province',1,26,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(27,'Horton Plains','Uva Province',1,27,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(28,'Anuradhapura','North Central Province',1,28,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(29,'Polonnaruwa','North Central Province',1,29,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(30,'Mihintale','North Central Province',1,30,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(31,'Minneriya National Park','North Central Province',1,31,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(32,'Kaudulla National Park','North Central Province',1,32,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(33,'Habarana','North Central Province',1,33,'2025-12-18 12:34:59','2025-12-18 12:34:59'),(34,'Ritigala','North Central Province',1,34,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(35,'Trincomalee','Eastern Province',1,35,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(36,'Arugam Bay','Eastern Province',1,36,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(37,'Batticaloa','Eastern Province',1,37,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(38,'Passikudah','Eastern Province',1,38,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(39,'Nilaveli','Eastern Province',1,39,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(40,'Pigeon Island','Eastern Province',1,40,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(41,'Jaffna','Northern Province',1,41,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(42,'Point Pedro','Northern Province',1,42,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(43,'Nallur','Northern Province',1,43,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(44,'Delft Island','Northern Province',1,44,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(45,'Mannar','Northern Province',1,45,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(46,'Chilaw','North Western Province',1,46,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(47,'Kurunegala','North Western Province',1,47,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(48,'Wilpattu National Park','North Western Province',1,48,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(49,'Kalpitiya','North Western Province',1,49,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(50,'Puttalam','North Western Province',1,50,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(51,'Ratnapura','Sabaragamuwa Province',1,51,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(52,'Sinharaja Forest Reserve','Sabaragamuwa Province',1,52,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(53,'Adams Peak (Sri Pada)','Sabaragamuwa Province',1,53,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(54,'Kitulgala','Sabaragamuwa Province',1,54,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(55,'Pinnawala Elephant Orphanage','Sabaragamuwa Province',1,55,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(56,'Gal Oya National Park','Eastern Province',1,56,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(57,'Kumana National Park','Eastern Province',1,57,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(58,'Wasgamuwa National Park','Central Province',1,58,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(59,'Lahugala National Park','Eastern Province',1,59,'2025-12-18 12:35:00','2025-12-18 12:35:00'),(60,'Lunugamvehera National Park','Southern Province',1,60,'2025-12-18 12:35:00','2025-12-18 12:35:00');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_photos`
--

DROP TABLE IF EXISTS `review_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint unsigned NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `review_photos_review_id_index` (`review_id`),
  CONSTRAINT `review_photos_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_photos`
--

LOCK TABLES `review_photos` WRITE;
/*!40000 ALTER TABLE `review_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `tourist_id` bigint unsigned NOT NULL,
  `guide_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  `review_text` text COLLATE utf8mb4_unicode_ci,
  `professionalism_rating` int DEFAULT NULL,
  `communication_rating` int DEFAULT NULL,
  `value_for_money_rating` int DEFAULT NULL,
  `itinerary_quality_rating` int DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `admin_hidden_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_booking_id_unique` (`booking_id`),
  KEY `reviews_tourist_id_foreign` (`tourist_id`),
  KEY `reviews_guide_id_index` (`guide_id`),
  KEY `reviews_rating_index` (`rating`),
  KEY `reviews_is_visible_index` (`is_visible`),
  CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('hlOXNuDvS3jRs0Ml5SDGBvZmWmhdC9LwsM8JtMNe',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYzNKM2dzbTd5NFNaZkN6MG1SaEJ1dU51aGU3TjJqd3RDSlpYamNROCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1766188990),('wrkz2XIf8ugwQLzFNLeTuDuUVSCi0efzxOT3XtZx',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZ2dVeEhINDlDdmJ6RjBoQVlsbGJndkNmRFdJenRRY3p0T3RZNXV0USI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZ3VpZGUtcGxhbnMvMS9lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJHF2OGR5VmtlL1lDRXNGSzV5SHZXYU9lVTdteUgzSE4uZFpZdm5DUVo1aWdDZXZ3ejlMcWdxIjt9',1766185388);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_items`
--

DROP TABLE IF EXISTS `subscription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint unsigned NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meter_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `meter_event_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`),
  KEY `subscription_items_subscription_id_stripe_price_index` (`subscription_id`,`stripe_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_items`
--

LOCK TABLES `subscription_items` WRITE;
/*!40000 ALTER TABLE `subscription_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_setting_key_unique` (`setting_key`),
  KEY `system_settings_setting_key_index` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tourist_requests`
--

DROP TABLE IF EXISTS `tourist_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tourist_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tourist_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` int NOT NULL,
  `preferred_destinations` json NOT NULL,
  `must_visit_places` text COLLATE utf8mb4_unicode_ci,
  `num_adults` int NOT NULL,
  `num_children` int NOT NULL DEFAULT '0',
  `children_ages` json DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `dates_flexible` tinyint(1) NOT NULL DEFAULT '0',
  `flexibility_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_min` decimal(10,2) NOT NULL,
  `budget_max` decimal(10,2) NOT NULL,
  `trip_focus` json NOT NULL,
  `transport_preference` enum('public_transport','private_vehicle','luxury_vehicle','no_preference') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accommodation_preference` enum('budget','midrange','luxury','mixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dietary_requirements` json DEFAULT NULL,
  `accessibility_needs` text COLLATE utf8mb4_unicode_ci,
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `status` enum('open','bids_received','bid_accepted','closed','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `bid_count` int NOT NULL DEFAULT '0',
  `expires_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tourist_requests_tourist_id_index` (`tourist_id`),
  KEY `tourist_requests_status_index` (`status`),
  KEY `tourist_requests_start_date_index` (`start_date`),
  KEY `tourist_requests_expires_at_index` (`expires_at`),
  CONSTRAINT `tourist_requests_tourist_id_foreign` FOREIGN KEY (`tourist_id`) REFERENCES `tourists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tourist_requests`
--

LOCK TABLES `tourist_requests` WRITE;
/*!40000 ALTER TABLE `tourist_requests` DISABLE KEYS */;
INSERT INTO `tourist_requests` VALUES (2,3,'tourist one','this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .this is the reques one for testing .',7,'[\"kandy, colombo\"]','kandy',2,0,NULL,'2025-11-20','2025-11-25',1,'i can travel anytime in november',500.00,2000.00,'[\"Cultural\", \"Adventure\"]','luxury_vehicle','luxury','[\"Vegan\"]','no need any accessibility','this is optional. just only for testing','bid_accepted',1,'2025-11-27','2025-11-13 12:08:54','2025-11-13 12:37:49'),(3,3,'tour request testing 1','this is the tour request testing 1.tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1',5,'[\"Ella\"]','Ella',2,1,NULL,'2025-12-02','2025-12-07',1,NULL,500.00,2000.00,'[\"Adventure\", \"Photography\"]','private_vehicle','budget','[\"Vegetarian\"]',NULL,'tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1tour request testing 1','bid_accepted',1,'2025-12-14','2025-11-30 09:19:30','2025-11-30 09:27:33'),(4,3,'tour with wedda','this is testing tour that i need to go. this time is flexible and also could be adjust payments..',4,'[\"in Sinharaje\"]','sinharajaya',1,0,NULL,'2025-12-10','2025-12-13',1,'yes this dates are flexible to adjust ..',1500.00,5000.00,'[\"Adventure\", \"Wildlife\", \"Photography\", \"Nightlife\"]','private_vehicle','midrange','[\"Gluten-Free\", \"Dairy-Free\", \"No Beef\"]',NULL,'i need a adventures jurney..','open',1,'2025-12-15','2025-12-01 17:11:39','2025-12-02 12:22:06'),(5,3,'My first tour','this is my first tour and i need to travel srilanka, and also need to go to kandy',7,'[\"kandy\", \"ella\", \"gallle\"]','kandy',2,0,NULL,'2025-12-20','2025-12-31',1,NULL,500.00,2000.00,'[\"Cultural\", \"Beach & Relaxation\"]','luxury_vehicle','midrange','[\"Vegan\"]',NULL,NULL,'open',1,'2026-01-02','2025-12-19 18:07:05','2025-12-19 18:09:34');
/*!40000 ALTER TABLE `tourist_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tourists`
--

DROP TABLE IF EXISTS `tourists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tourists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tourists_user_id_unique` (`user_id`),
  CONSTRAINT `tourists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tourists`
--

LOCK TABLES `tourists` WRITE;
/*!40000 ALTER TABLE `tourists` DISABLE KEYS */;
INSERT INTO `tourists` VALUES (2,4,'yehan weerapana','+94722676000','Other',NULL,NULL,'2025-11-05 09:40:45','2025-11-05 09:40:45'),(3,5,'kanishka','0775685921','India',NULL,NULL,'2025-11-10 13:47:32','2025-11-10 13:47:32');
/*!40000 ALTER TABLE `tourists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('tourist','guide','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_email_index` (`email`),
  KEY `users_user_type_index` (`user_type`),
  KEY `users_status_index` (`status`),
  KEY `users_stripe_id_index` (`stripe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@tourismplatform.com','$2y$12$qv8dyVke/YCEsFK5yHvWaOeU7myH3HN.dZYvnCQZ5igCevwz9Lqgq','admin','active','2025-10-12 08:29:13',NULL,'2025-10-12 08:29:13','2025-10-12 08:29:13',NULL,NULL,NULL,NULL),(3,'kanishkatest41@gmail.com','$2y$12$j5j1dAzBUkEOQ0DsBvn3l.x9Ye6rTTgEQ39dIZPu/4PgwuMHMLVhm','guide','active','2025-11-05 08:20:11','U8YA51APa5SAvafmT1sz07waQfUmBWjIwxV3GUifNzlj0KFB9WZ5QQwh3xYN','2025-11-05 08:20:11','2025-11-05 11:45:03',NULL,NULL,NULL,NULL),(4,'binathweerapana@gmail.com','$2y$12$9ttRC8y6uM7Tfmp9djH2j.0JE6yiI2qgCFNQz1FAMURImZZg0067W','tourist','active','2025-11-05 09:40:45',NULL,'2025-11-05 09:40:45','2025-11-05 11:42:38',NULL,NULL,NULL,NULL),(5,'kdilhan50@gmail.com','$2y$12$.fZPRgU77QiPSo03pYhOLufzKhCBejdnKlzaMKBkSMcQkmB9UoTMC','tourist','active','2025-11-10 13:48:27',NULL,'2025-11-10 13:47:32','2025-11-10 13:48:27',NULL,NULL,NULL,NULL),(6,'lakshitha@gmail.com','$2y$12$fEUpk925q2EoHJSPmh3CZO/xaxt2cn/eg.XqKb2XoJPh0wUgUs6k6','guide','active','2025-12-18 20:09:55',NULL,'2025-12-18 20:09:55','2025-12-18 20:38:17',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_documents`
--

DROP TABLE IF EXISTS `vehicle_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `document_type` enum('registration','insurance') COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicle_documents_vehicle_id_document_type_index` (`vehicle_id`,`document_type`),
  CONSTRAINT `vehicle_documents_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_documents`
--

LOCK TABLES `vehicle_documents` WRITE;
/*!40000 ALTER TABLE `vehicle_documents` DISABLE KEYS */;
INSERT INTO `vehicle_documents` VALUES (1,1,'registration','vehicles/1/documents/xv9I8xtXuPAT8OlIV23WFqTT5urn2AOlBIkZnBvG.pdf','2025-12-19 00:19:48');
/*!40000 ALTER TABLE `vehicle_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_photos`
--

DROP TABLE IF EXISTS `vehicle_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicle_photos_vehicle_id_sort_order_index` (`vehicle_id`,`sort_order`),
  CONSTRAINT `vehicle_photos_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_photos`
--

LOCK TABLES `vehicle_photos` WRITE;
/*!40000 ALTER TABLE `vehicle_photos` DISABLE KEYS */;
INSERT INTO `vehicle_photos` VALUES (1,1,'vehicles/1/photos/fPgJn3zr0XfWFpOh5AswIzdjWduUOcP0SG6Pr2L3.jpg',0,0,'2025-12-19 00:17:05'),(2,1,'vehicles/1/photos/QivWYWjMakD1JK6jQbCG0EddY1AAKo7hAHZpk3lh.jpg',0,1,'2025-12-19 00:17:42');
/*!40000 ALTER TABLE `vehicle_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guide_id` bigint unsigned NOT NULL,
  `vehicle_type` enum('car','van','suv','minibus','bus','tuk_tuk','motorcycle') COLLATE utf8mb4_unicode_ci NOT NULL,
  `make` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` smallint unsigned DEFAULT NULL,
  `license_plate` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seating_capacity` tinyint unsigned NOT NULL,
  `has_ac` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_plate_per_guide` (`guide_id`,`license_plate`),
  KEY `vehicles_is_active_index` (`is_active`),
  CONSTRAINT `vehicles_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,1,'van','Toyota','caravan',NULL,'ABC-1234',12,0,NULL,NULL,1,'2025-12-18 18:10:28','2025-12-18 18:10:28');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-25 20:22:57
