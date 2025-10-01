/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `method_statements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `method_statements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permit_to_work_id` bigint(20) unsigned NOT NULL,
  `responsible_person` varchar(255) NOT NULL,
  `method_statement_date` date NOT NULL,
  `permit_receiver` varchar(255) NOT NULL,
  `method_statement_author` varchar(255) DEFAULT NULL,
  `document_guidance` text DEFAULT NULL,
  `hazard_identification` text DEFAULT NULL,
  `risk_evaluation` text DEFAULT NULL,
  `risk_document_different` tinyint(1) NOT NULL DEFAULT 0,
  `persons_responsibility` text DEFAULT NULL,
  `work_sequence_1` text DEFAULT NULL,
  `work_sequence_2` text DEFAULT NULL,
  `work_sequence_3` text DEFAULT NULL,
  `work_sequence_4` text DEFAULT NULL,
  `work_sequence_5` text DEFAULT NULL,
  `work_sequence_6` text DEFAULT NULL,
  `tools_access_method` text DEFAULT NULL,
  `safety_equipment_ppe` text DEFAULT NULL,
  `training_competence` text DEFAULT NULL,
  `route_identification` text DEFAULT NULL,
  `off_job_equipment_storage` text DEFAULT NULL,
  `work_sequence_order` text DEFAULT NULL,
  `equipment_inspection` text DEFAULT NULL,
  `temporary_platform` text DEFAULT NULL,
  `weather_influence` text DEFAULT NULL,
  `cleanliness_housekeeping` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `method_statements_permit_to_work_id_foreign` (`permit_to_work_id`),
  CONSTRAINT `method_statements_permit_to_work_id_foreign` FOREIGN KEY (`permit_to_work_id`) REFERENCES `permit_to_works` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permit_to_works`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permit_to_works` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permit_number` varchar(255) NOT NULL,
  `work_title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `work_description` text DEFAULT NULL,
  `work_location` varchar(255) NOT NULL,
  `equipment_tools` text NOT NULL,
  `department` varchar(255) NOT NULL,
  `responsible_person` varchar(255) NOT NULL,
  `building` varchar(255) DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `tip` varchar(255) DEFAULT NULL,
  `work_at_heights` tinyint(1) NOT NULL DEFAULT 0,
  `hot_work` tinyint(1) NOT NULL DEFAULT 0,
  `loto_isolation` tinyint(1) NOT NULL DEFAULT 0,
  `line_breaking` tinyint(1) NOT NULL DEFAULT 0,
  `excavation` tinyint(1) NOT NULL DEFAULT 0,
  `confined_spaces` tinyint(1) NOT NULL DEFAULT 0,
  `explosive_atmosphere` tinyint(1) NOT NULL DEFAULT 0,
  `form_y_n` varchar(255) DEFAULT NULL,
  `form_detail` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','pending_approval','approved','in_progress','completed','cancelled') NOT NULL DEFAULT 'draft',
  `permit_issuer_id` bigint(20) unsigned NOT NULL,
  `authorizer_id` bigint(20) unsigned DEFAULT NULL,
  `receiver_id` bigint(20) unsigned DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `authorized_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permit_to_works_permit_number_unique` (`permit_number`),
  KEY `permit_to_works_permit_issuer_id_foreign` (`permit_issuer_id`),
  KEY `permit_to_works_authorizer_id_foreign` (`authorizer_id`),
  KEY `permit_to_works_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `permit_to_works_authorizer_id_foreign` FOREIGN KEY (`authorizer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `permit_to_works_permit_issuer_id_foreign` FOREIGN KEY (`permit_issuer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `permit_to_works_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `risk_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risk_assessments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permit_to_work_id` bigint(20) unsigned NOT NULL,
  `hazard_activity` text NOT NULL,
  `risk_level` enum('high','medium','low') NOT NULL,
  `control_measures` text DEFAULT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `receiver_id` bigint(20) unsigned NOT NULL,
  `author_date` date NOT NULL,
  `receiver_date` date NOT NULL,
  `detailed_control_measures` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `risk_assessments_permit_to_work_id_foreign` (`permit_to_work_id`),
  KEY `risk_assessments_author_id_foreign` (`author_id`),
  KEY `risk_assessments_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `risk_assessments_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  CONSTRAINT `risk_assessments_permit_to_work_id_foreign` FOREIGN KEY (`permit_to_work_id`) REFERENCES `permit_to_works` (`id`) ON DELETE CASCADE,
  CONSTRAINT `risk_assessments_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('administrator','contractor','bekaert') NOT NULL DEFAULT 'contractor',
  `phone` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_08_29_070830_create_permit_to_works_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_08_29_070836_create_method_statements_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_08_29_070844_create_risk_assessments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_08_29_070855_add_role_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_08_29_073050_fix_time_columns_in_permit_to_works_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_08_29_085938_add_department_and_supervisor_email_to_permit_to_works_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_08_29_092324_drop_emergency_contact_date_from_permit_to_works_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_08_29_092721_drop_unused_fields_from_permit_to_works_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_09_02_015849_drop_time_fields_from_permit_to_works_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_09_02_020359_add_missing_fields_to_permit_to_works_table',5);
