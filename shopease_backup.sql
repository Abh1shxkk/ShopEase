-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: shopease
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abandoned_cart_reminders`
--

DROP TABLE IF EXISTS `abandoned_cart_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abandoned_cart_reminders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `abandoned_cart_id` bigint(20) unsigned NOT NULL,
  `reminder_number` int(11) NOT NULL,
  `channel` varchar(255) NOT NULL DEFAULT 'email',
  `status` enum('sent','opened','clicked','failed') NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `opened_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `abandoned_cart_reminders_abandoned_cart_id_foreign` (`abandoned_cart_id`),
  CONSTRAINT `abandoned_cart_reminders_abandoned_cart_id_foreign` FOREIGN KEY (`abandoned_cart_id`) REFERENCES `abandoned_carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abandoned_cart_reminders`
--

LOCK TABLES `abandoned_cart_reminders` WRITE;
/*!40000 ALTER TABLE `abandoned_cart_reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `abandoned_cart_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `abandoned_carts`
--

DROP TABLE IF EXISTS `abandoned_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abandoned_carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `cart_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `items_count` int(11) NOT NULL DEFAULT 0,
  `cart_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cart_snapshot`)),
  `status` enum('pending','reminded','recovered','expired') NOT NULL DEFAULT 'pending',
  `reminder_count` int(11) NOT NULL DEFAULT 0,
  `last_reminder_at` timestamp NULL DEFAULT NULL,
  `recovered_at` timestamp NULL DEFAULT NULL,
  `recovered_order_id` bigint(20) unsigned DEFAULT NULL,
  `recovery_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abandoned_carts_recovery_token_unique` (`recovery_token`),
  KEY `abandoned_carts_recovered_order_id_foreign` (`recovered_order_id`),
  KEY `abandoned_carts_status_created_at_index` (`status`,`created_at`),
  KEY `abandoned_carts_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `abandoned_carts_recovered_order_id_foreign` FOREIGN KEY (`recovered_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `abandoned_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abandoned_carts`
--

LOCK TABLES `abandoned_carts` WRITE;
/*!40000 ALTER TABLE `abandoned_carts` DISABLE KEYS */;
INSERT INTO `abandoned_carts` VALUES (1,8,382.20,1,'[{\"product_id\":29,\"product_name\":\"Cashmere V-Neck Sweater\",\"product_image\":\"https:\\/\\/images.unsplash.com\\/photo-1434389677669-e08b4cac3105?w=600\",\"price\":\"195.00\",\"quantity\":2,\"subtotal\":382.2}]','recovered',0,NULL,'2026-01-11 12:42:53',6,'o2CO6o6VIdod6pcU1bl310zpWkmvryJ7fsI938x631yYe5b8eUiIg4UE4jxPsI2G','2026-01-11 12:40:35','2026-01-11 12:42:53'),(2,8,185.25,1,'[{\"product_id\":29,\"product_name\":\"Cashmere V-Neck Sweater\",\"product_image\":\"https:\\/\\/images.unsplash.com\\/photo-1434389677669-e08b4cac3105?w=600\",\"price\":\"195.00\",\"quantity\":1,\"subtotal\":185.25}]','pending',0,NULL,NULL,NULL,'szRP1O8wKnGwANhbQJksPaffQUWEUQ7pJqyQRPQhNEQDSLLQlx9BOKAO3THZ4B5d','2026-01-11 12:44:58','2026-01-11 12:44:58'),(3,4,105.00,1,'[{\"product_id\":86,\"product_name\":\"Plant Pot Set\",\"product_image\":\"https:\\/\\/images.unsplash.com\\/photo-1485955900006-10f4d324d411?w=600\",\"price\":\"35.00\",\"quantity\":3,\"subtotal\":105}]','pending',0,NULL,NULL,NULL,'PfLI73VxpSw7ruOSrq4D4QxmClZflA0YasrmHziO0nRMFCLYVfjDIH3Dh7kgLqPD','2026-01-28 14:01:53','2026-01-28 14:01:53');
/*!40000 ALTER TABLE `abandoned_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) NOT NULL DEFAULT 'Home',
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address_line_1` text NOT NULL,
  `address_line_2` text DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'India',
  `landmark` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `type` enum('shipping','billing','both') NOT NULL DEFAULT 'both',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,5,'Home','gfhgff','04656546465','vvhgvhgv',NULL,'bnbbn','nbnb','7678','India',NULL,1,'both','2026-01-05 13:39:57','2026-01-05 13:39:57');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'Lifestyle','lifestyle','Stories about living well',NULL,1,0,'2026-01-10 12:26:10','2026-01-10 12:26:10'),(2,'Travel','travel','Adventures and destinations',NULL,1,0,'2026-01-10 12:26:10','2026-01-10 12:26:10'),(3,'Culture','culture','Art, heritage and traditions',NULL,1,0,'2026-01-10 12:26:10','2026-01-10 12:26:10');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_post_id_foreign` (`post_id`),
  KEY `blog_comments_user_id_foreign` (`user_id`),
  KEY `blog_comments_parent_id_foreign` (`parent_id`),
  CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `author_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `status` enum('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `reading_time` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_category_id_foreign` (`category_id`),
  KEY `blog_posts_author_id_foreign` (`author_id`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blog_posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (1,1,NULL,'What the Places We Call Home Have Taught Us','what-the-places-we-call-home-have-taught-us','A reflection on the spaces that shape our lives and the memories they hold.','Home is more than just a place. It is where our stories begin, where we learn to love, and where we find comfort in the familiar. Through the years, the places we call home teach us about resilience, about change, and about the beauty of belonging.\n\nEvery corner holds a memory, every wall has witnessed our growth. From childhood bedrooms to first apartments, each space has contributed to who we are today.\n\nThe kitchen where we learned to cook our grandmother\'s recipes. The backyard where we played until the streetlights came on. The living room where we gathered for celebrations and quiet evenings alike.\n\nThese spaces shape us in ways we often don\'t realize until we\'ve moved on. They teach us about comfort, about creating sanctuary, about the importance of having a place to return to.','https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800',NULL,'[\"home\",\"lifestyle\",\"reflection\"]','published','2026-01-05 12:26:10',1,1,2,NULL,NULL,1,'2026-01-10 12:26:10','2026-01-16 10:22:45'),(2,3,NULL,'Still Naughty. Still Saucy. Still Yummy.','still-naughty-still-saucy-still-yummy','Exploring the bold flavors and vibrant culture of street food around the world.','There is something magical about street food. The sizzle of a hot pan, the aroma of spices wafting through the air, the joy of discovering a hidden gem tucked away in a narrow alley.\n\nFrom the bustling markets of Bangkok to the food trucks of Los Angeles, street food tells the story of a place and its people. It is authentic, unpretentious, and absolutely delicious.\n\nStreet food vendors are the unsung heroes of culinary culture. They preserve recipes passed down through generations, adapting them to modern tastes while keeping the soul intact.\n\nWhether it\'s a steaming bowl of pho on a Hanoi sidewalk or tacos from a Mexico City cart, these humble dishes connect us to the heart of a culture in ways that fine dining never could.','https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800',NULL,'[\"food\",\"culture\",\"travel\"]','published','2025-12-31 12:26:10',1,1,2,NULL,NULL,1,'2026-01-10 12:26:10','2026-01-16 23:17:10'),(3,2,NULL,'The Art of Wandering Without a Plan','the-art-of-wandering-without-a-plan','Sometimes the best adventures come from getting lost.','In a world of GPS and detailed itineraries, there is something liberating about simply wandering. No destination in mind, no schedule to keep, just the open road and endless possibilities.\n\nWandering teaches us to be present, to notice the small details we usually rush past. It reminds us that the journey itself can be the destination.\n\nThe best discoveries often happen when we least expect them. A hidden cafe down a side street. A breathtaking view around an unexpected corner. A conversation with a stranger that changes our perspective.\n\nSo next time you travel, leave some room for spontaneity. Put away the map, follow your curiosity, and see where the day takes you. You might just find exactly what you didn\'t know you were looking for.','https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',NULL,'[\"travel\",\"adventure\",\"wanderlust\"]','published','2025-12-26 12:26:10',1,1,2,NULL,NULL,1,'2026-01-10 12:26:10','2026-01-16 12:17:04');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand_story_sections`
--

DROP TABLE IF EXISTS `brand_story_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_story_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_position` varchar(255) NOT NULL DEFAULT 'right',
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand_story_sections`
--

LOCK TABLES `brand_story_sections` WRITE;
/*!40000 ALTER TABLE `brand_story_sections` DISABLE KEYS */;
INSERT INTO `brand_story_sections` VALUES (1,'Our Beginning','A passion for quality','Every great journey begins with a single step. Ours started with a simple belief: that everyone deserves access to beautifully crafted, high-quality products that stand the test of time.\n\nFounded with a vision to bridge the gap between exceptional craftsmanship and everyday accessibility, we set out to create something special.','https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000','right',NULL,NULL,1,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(2,'Our Mission','Quality meets accessibility','We believe that exceptional quality shouldn\'t come with an exceptional price tag. Our mission is to bring you carefully curated products that combine timeless design with superior craftsmanship.\n\nEvery item in our collection is selected with care, ensuring it meets our high standards for quality, durability, and style.','https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=1000','left',NULL,NULL,2,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(3,'Our Promise','Committed to excellence','When you shop with us, you\'re not just buying a product – you\'re investing in quality that lasts. We stand behind everything we sell with our satisfaction guarantee.\n\nOur dedicated team is here to ensure your experience exceeds expectations, from browsing to delivery and beyond.','https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000','background','Shop Now','/shop',3,1,'2026-01-10 04:18:58','2026-01-10 04:18:58');
/*!40000 ALTER TABLE `brand_story_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bundle_items`
--

DROP TABLE IF EXISTS `bundle_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bundle_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bundle_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bundle_items_bundle_id_product_id_unique` (`bundle_id`,`product_id`),
  KEY `bundle_items_product_id_foreign` (`product_id`),
  CONSTRAINT `bundle_items_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `product_bundles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bundle_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bundle_items`
--

LOCK TABLES `bundle_items` WRITE;
/*!40000 ALTER TABLE `bundle_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `bundle_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

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

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('shopease-cache-setting_crisp_website_id','s:0:\"\";',1769632976),('shopease-cache-setting_facebook_url','s:29:\"https://facebook.com/shopease\";',1769632976),('shopease-cache-setting_footer_address','s:21:\"Baral Partapur Meerut\";',1769632976),('shopease-cache-setting_footer_copyright','s:38:\"© 2026 ShopEase. All rights reserved.\";',1769632976),('shopease-cache-setting_footer_email','s:27:\"abhichauhan200504@gmail.com\";',1769632976),('shopease-cache-setting_footer_phone','s:15:\"+91 82794 22813\";',1769632976),('shopease-cache-setting_instagram_url','s:30:\"https://instagram.com/shopease\";',1769632976),('shopease-cache-setting_live_chat_enabled','s:1:\"0\";',1769632976),('shopease-cache-setting_live_chat_provider','s:4:\"tawk\";',1769632976),('shopease-cache-setting_tawk_property_id','s:24:\"695de517aad9c019814f9a7e\";',1769632976),('shopease-cache-setting_tawk_widget_id','s:9:\"1jebcdpq6\";',1769632976),('shopease-cache-setting_twitter_url','s:28:\"https://twitter.com/shopease\";',1769632976),('shopease-cache-setting_youtube_url','s:28:\"https://youtube.com/shopease\";',1769632976);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

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

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_variant_id_foreign` (`variant_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (4,3,87,NULL,1,'2026-01-03 13:29:03','2026-01-03 13:29:03'),(9,4,86,NULL,1,'2026-01-04 14:17:14','2026-01-28 14:02:08'),(12,7,38,NULL,1,'2026-01-04 23:24:53','2026-01-04 23:24:53'),(15,8,29,11,1,'2026-01-11 12:44:54','2026-01-11 12:44:54');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (16,'Men\'s Clothing','mens-clothing','Premium menswear collection',NULL,1,1,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(17,'Women\'s Clothing','womens-clothing','Elegant women\'s fashion',NULL,1,2,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(18,'Footwear','footwear','Shoes, sneakers & boots',NULL,1,3,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(19,'Accessories','accessories','Bags, watches & jewelry',NULL,1,4,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(20,'Electronics','electronics','Gadgets & tech accessories',NULL,1,5,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(21,'Home & Living','home-living','Home decor & essentials',NULL,1,6,'2026-01-03 13:20:34','2026-01-03 13:20:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_conversations`
--

DROP TABLE IF EXISTS `chat_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_conversations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_conversations_user_id_session_id_index` (`user_id`,`session_id`),
  CONSTRAINT `chat_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_conversations`
--

LOCK TABLES `chat_conversations` WRITE;
/*!40000 ALTER TABLE `chat_conversations` DISABLE KEYS */;
INSERT INTO `chat_conversations` VALUES (1,5,'BM4pJwvl5yYBYTNfh4hsqkGtq9weBbcuYmw3kUHh','closed','2026-01-10 02:53:36','2026-01-10 02:53:25','2026-01-10 04:50:15'),(2,NULL,'kPUmuVXnvZU4lWQ9mg6JT23iKCF9hVPTXrLi9eiK','active','2026-01-10 02:55:36','2026-01-10 02:55:36','2026-01-10 02:55:36'),(3,5,'E3TtvmC7NAzFoBQ79Yu0EkNeA7VdZiDkHpog0ekI','active','2026-01-10 04:50:25','2026-01-10 04:50:17','2026-01-10 04:50:25'),(4,8,'NBT3qYQGB8BWWQR8mwH5dHISj3a65yov83M32hoJ','active','2026-01-11 12:34:26','2026-01-11 12:34:26','2026-01-11 12:34:26'),(5,NULL,'3fxi2j6qGVd9Gx011zNJFmz7usrImPRrzzvgf5eT','active','2026-01-11 12:50:07','2026-01-11 12:50:07','2026-01-11 12:50:07'),(6,NULL,'9qaBuLW63Qe59ZGH0sWkKu6rO8oTAUtrRUGY1yMb','active','2026-01-14 00:50:56','2026-01-14 00:50:56','2026-01-14 00:50:56'),(7,1,'58a43JkzxY8TepPGwDHcAPjWSbNEPpnbg1C0twKl','active','2026-01-16 07:47:25','2026-01-16 07:47:25','2026-01-16 07:47:25'),(8,NULL,'KIA0HEu7TgediVbEwwzklOMwBNOtVSLcxLxFgzrc','active','2026-01-28 13:55:09','2026-01-28 13:55:09','2026-01-28 13:55:09');
/*!40000 ALTER TABLE `chat_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint(20) unsigned NOT NULL,
  `role` enum('user','assistant','system') NOT NULL,
  `content` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  CONSTRAINT `chat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (5,3,'user','Track my order','[]','2026-01-10 04:50:17','2026-01-10 04:50:17'),(6,3,'assistant','Your most recent order #ORD-695C0C9C7CE12 is currently \'processing\'. You can track all your orders from your account dashboard or use our order tracking page.','[]','2026-01-10 04:50:18','2026-01-10 04:50:18'),(7,3,'user','What is your return policy?','[]','2026-01-10 04:50:20','2026-01-10 04:50:20'),(8,3,'assistant','We have a 30-day return policy. Items must be unused with original tags. To initiate a return, please go to your order details and click \'Request Return\', or create a support ticket for assistance.','[]','2026-01-10 04:50:21','2026-01-10 04:50:21'),(9,3,'user','Shipping information','[]','2026-01-10 04:50:24','2026-01-10 04:50:24'),(10,3,'assistant','Hi Abhishek Chauhan! Welcome to ShopEase. How can I help you today? 😊','[]','2026-01-10 04:50:25','2026-01-10 04:50:25');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupon_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  KEY `coupon_usages_user_id_foreign` (`user_id`),
  KEY `coupon_usages_order_id_foreign` (`order_id`),
  CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_usages`
--

LOCK TABLES `coupon_usages` WRITE;
/*!40000 ALTER TABLE `coupon_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_limit_per_user` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `starts_at` date DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','Welcome Discount','Get 10% off on your first order','percentage',10.00,500.00,200.00,NULL,1,0,NULL,'2026-07-04',1,'2026-01-04 14:15:59','2026-01-04 14:36:18'),(2,'FLAT100','Flat ₹100 Off','Get flat ₹100 off on orders above ₹999','fixed',100.00,999.00,NULL,500,2,0,NULL,'2026-04-04',1,'2026-01-04 14:15:59','2026-01-04 14:15:59'),(3,'SAVE20','20% Off Sale','Save 20% on all products','percentage',20.00,1500.00,500.00,100,1,0,NULL,'2026-02-04',1,'2026-01-04 14:15:59','2026-01-04 14:15:59'),(4,'FREESHIP','Free Shipping','Get ₹49 off (free shipping)','fixed',49.00,299.00,NULL,NULL,5,0,NULL,NULL,1,'2026-01-04 14:15:59','2026-01-04 14:15:59'),(5,'MEGA50','Mega Sale 50% Off','Massive 50% discount','percentage',50.00,3000.00,1000.00,50,1,0,'2026-01-11','2026-01-18',1,'2026-01-04 14:15:59','2026-01-04 14:15:59');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `early_access_sales`
--

DROP TABLE IF EXISTS `early_access_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `early_access_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `member_access_at` datetime NOT NULL,
  `public_access_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `member_discount` decimal(5,2) NOT NULL DEFAULT 0.00,
  `applicable_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_categories`)),
  `applicable_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_products`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `early_access_sales`
--

LOCK TABLES `early_access_sales` WRITE;
/*!40000 ALTER TABLE `early_access_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `early_access_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ethos_sections`
--

DROP TABLE IF EXISTS `ethos_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ethos_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_position` varchar(255) NOT NULL DEFAULT 'right',
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ethos_sections`
--

LOCK TABLES `ethos_sections` WRITE;
/*!40000 ALTER TABLE `ethos_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `ethos_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ethos_values`
--

DROP TABLE IF EXISTS `ethos_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ethos_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ethos_values`
--

LOCK TABLES `ethos_values` WRITE;
/*!40000 ALTER TABLE `ethos_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `ethos_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_categories`
--

DROP TABLE IF EXISTS `faq_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `faq_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_categories`
--

LOCK TABLES `faq_categories` WRITE;
/*!40000 ALTER TABLE `faq_categories` DISABLE KEYS */;
INSERT INTO `faq_categories` VALUES (1,'Orders & Shipping','orders-shipping','truck','Questions about placing orders and delivery',1,1,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(2,'Returns & Refunds','returns-refunds','refresh','Information about our return and refund policies',2,1,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(3,'Payment','payment','credit-card','Payment methods and billing questions',3,1,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(4,'Account & Profile','account-profile','user','Managing your account and preferences',4,1,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(5,'Products','products','shopping-bag','Questions about our products',5,1,'2026-01-06 10:52:31','2026-01-06 10:52:31');
/*!40000 ALTER TABLE `faq_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `not_helpful_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_category_id_foreign` (`category_id`),
  CONSTRAINT `faqs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,1,'How can I track my order?','Once your order is shipped, you will receive an email with tracking information. You can also track your order by logging into your account and visiting the \"My Orders\" section.',1,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(2,1,'What are the shipping charges?','We offer free shipping on orders above ₹999. For orders below this amount, a flat shipping fee of ₹99 is applicable. Express delivery options are available at additional cost.',2,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(3,1,'How long does delivery take?','Standard delivery takes 5-7 business days. Express delivery (where available) takes 2-3 business days. Delivery times may vary based on your location and product availability.',3,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(4,1,'Can I change my delivery address after placing an order?','You can change your delivery address within 24 hours of placing the order by contacting our support team. Once the order is shipped, address changes are not possible.',4,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(5,2,'What is your return policy?','We offer a 30-day return policy for most items. Products must be unused, in original packaging, and with all tags attached. Some items like innerwear and personalized products are not eligible for return.',1,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(6,2,'How do I initiate a return?','To initiate a return, go to \"My Orders\" in your account, select the order, and click \"Return Item\". Follow the instructions to schedule a pickup or drop-off at a nearby location.',2,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(7,2,'When will I receive my refund?','Refunds are processed within 5-7 business days after we receive and inspect the returned item. The amount will be credited to your original payment method.',3,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(8,2,'Can I exchange an item instead of returning it?','Yes, you can exchange items for a different size or color (subject to availability). Select the \"Exchange\" option when initiating your return request.',4,1,1,0,'2026-01-06 10:52:31','2026-01-07 04:24:35'),(9,3,'What payment methods do you accept?','We accept all major credit/debit cards (Visa, Mastercard, Rupay), UPI, Net Banking, and popular wallets like Paytm and PhonePe. Cash on Delivery is available for select locations.',1,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(10,3,'Is it safe to use my credit card on your website?','Yes, absolutely. We use industry-standard SSL encryption and partner with Razorpay for secure payment processing. Your card details are never stored on our servers.',2,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(11,3,'Why was my payment declined?','Payments can be declined due to insufficient funds, incorrect card details, or bank security measures. Please verify your details and try again, or contact your bank for assistance.',3,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(12,3,'Can I use multiple payment methods for one order?','Currently, we support single payment method per order. However, you can combine store credit or gift cards with other payment methods.',4,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(13,4,'How do I create an account?','Click on \"Sign Up\" at the top of the page and enter your email address and create a password. You can also sign up using your Google or Facebook account for faster registration.',1,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(14,4,'I forgot my password. How can I reset it?','Click on \"Forgot Password\" on the login page, enter your registered email address, and we\'ll send you an OTP to reset your password.',2,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(15,4,'How do I update my profile information?','Log into your account and go to \"My Profile\". Here you can update your personal information, addresses, and communication preferences.',3,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(16,4,'How do I delete my account?','You can delete your account from the \"My Profile\" section. Please note that this action is irreversible and all your data including order history will be permanently deleted.',4,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(17,5,'How do I find my size?','Each product page has a size guide with detailed measurements. We recommend measuring yourself and comparing with our size chart for the best fit.',1,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(18,5,'Are the product colors accurate?','We strive to display colors as accurately as possible. However, actual colors may vary slightly due to monitor settings and lighting conditions in photography.',2,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(19,5,'How do I know if an item is in stock?','Stock availability is shown on each product page. If an item is out of stock, you can click \"Notify Me\" to receive an email when it\'s back in stock.',3,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31'),(20,5,'Do you offer gift wrapping?','Yes, we offer premium gift wrapping for an additional ₹99. You can select this option during checkout and add a personalized message.',4,1,0,0,'2026-01-06 10:52:31','2026-01-06 10:52:31');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_sections`
--

DROP TABLE IF EXISTS `featured_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featured_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `link_text` varchar(255) DEFAULT NULL,
  `section_type` varchar(255) NOT NULL,
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_data`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_sections`
--

LOCK TABLES `featured_sections` WRITE;
/*!40000 ALTER TABLE `featured_sections` DISABLE KEYS */;
INSERT INTO `featured_sections` VALUES (1,'Women Category','Women',NULL,'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=800','/shop?gender=women',NULL,'category_showcase',NULL,1,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(2,'Men Category','Men',NULL,'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?auto=format&fit=crop&q=80&w=800','/shop?gender=men',NULL,'category_showcase',NULL,1,1,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(3,'Accessories Category','Accessories',NULL,'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800','/shop?category=Accessories',NULL,'category_showcase',NULL,1,2,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(4,'New Arrivals','New Arrivals',NULL,'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=800','/shop?sort=newest',NULL,'category_showcase',NULL,1,3,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(5,'Heritage Main','Sustainability Through Rediscovery.','We don\'t just make products; we preserve cultures. By sourcing kilims that are up to 100 years old, we reduce waste while honoring the geometric languages of nomadic tribes.','https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=1000','/our-ethos','Read Our Ethos','heritage',NULL,1,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(6,'Journal 1','What the Places We Call Home Have Taught Us',NULL,'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&q=80&w=800',NULL,NULL,'journal',NULL,1,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(7,'Journal 2','Still Naughty. Still Saucy. Still Yummy.',NULL,'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800',NULL,NULL,'journal',NULL,1,1,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(8,'Journal 3','The Art of Wandering Without a Plan',NULL,'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&q=80&w=800',NULL,NULL,'journal',NULL,1,2,'2026-01-04 12:29:43','2026-01-04 12:29:43');
/*!40000 ALTER TABLE `featured_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flash_sale_products`
--

DROP TABLE IF EXISTS `flash_sale_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flash_sale_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flash_sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `quantity_limit` int(11) DEFAULT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT 0,
  `per_user_limit` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flash_sale_products_flash_sale_id_product_id_unique` (`flash_sale_id`,`product_id`),
  KEY `flash_sale_products_product_id_foreign` (`product_id`),
  CONSTRAINT `flash_sale_products_flash_sale_id_foreign` FOREIGN KEY (`flash_sale_id`) REFERENCES `flash_sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flash_sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flash_sale_products`
--

LOCK TABLES `flash_sale_products` WRITE;
/*!40000 ALTER TABLE `flash_sale_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `flash_sale_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flash_sales`
--

DROP TABLE IF EXISTS `flash_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flash_sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flash_sales_slug_unique` (`slug`),
  KEY `flash_sales_is_active_starts_at_ends_at_index` (`is_active`,`starts_at`,`ends_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flash_sales`
--

LOCK TABLES `flash_sales` WRITE;
/*!40000 ALTER TABLE `flash_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `flash_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `footer_links`
--

DROP TABLE IF EXISTS `footer_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `footer_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `footer_links`
--

LOCK TABLES `footer_links` WRITE;
/*!40000 ALTER TABLE `footer_links` DISABLE KEYS */;
INSERT INTO `footer_links` VALUES (1,'Women','/shop?gender=women','shop',1,1,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(2,'Men','/shop?gender=men','shop',1,2,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(3,'All Products','/shop','shop',1,3,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(4,'My Profile','/profile','account',1,1,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(5,'My Orders','/orders','account',1,2,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(6,'Wishlist','/wishlist','account',1,3,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(7,'Shopping Cart','/cart','account',1,4,'2026-01-04 12:50:55','2026-01-04 12:50:55');
/*!40000 ALTER TABLE `footer_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frequently_bought_together`
--

DROP TABLE IF EXISTS `frequently_bought_together`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frequently_bought_together` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `related_product_id` bigint(20) unsigned NOT NULL,
  `purchase_count` int(11) NOT NULL DEFAULT 0,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 5.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frequently_bought_together_product_id_related_product_id_unique` (`product_id`,`related_product_id`),
  KEY `frequently_bought_together_related_product_id_foreign` (`related_product_id`),
  CONSTRAINT `frequently_bought_together_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `frequently_bought_together_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frequently_bought_together`
--

LOCK TABLES `frequently_bought_together` WRITE;
/*!40000 ALTER TABLE `frequently_bought_together` DISABLE KEYS */;
/*!40000 ALTER TABLE `frequently_bought_together` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_banners`
--

DROP TABLE IF EXISTS `hero_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hero_banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `button_text_2` varchar(255) DEFAULT NULL,
  `button_link_2` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_banners`
--

LOCK TABLES `hero_banners` WRITE;
/*!40000 ALTER TABLE `hero_banners` DISABLE KEYS */;
INSERT INTO `hero_banners` VALUES (1,'SHOP KILIM CLOGS','Hand-crafted comfort using vintage textiles.','https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&q=80&w=1600','SHOP HIM','/shop?gender=men','SHOP HER','/shop?gender=women',1,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(2,'THE ART OF WANDERING','Premium weekender bags for your next journey.','https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&q=80&w=1600','SHOP HIM','/shop?gender=men','SHOP HER','/shop?gender=women',1,1,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(3,'VINTAGE TEXTILE SOULS','Each piece tells a story of heritage and craft.','https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&q=80&w=1600','SHOP HIM','/shop?gender=men','SHOP HER','/shop?gender=women',1,2,'2026-01-04 12:29:43','2026-01-04 12:29:43');
/*!40000 ALTER TABLE `hero_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_alerts`
--

DROP TABLE IF EXISTS `inventory_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_alerts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `type` enum('low_stock','out_of_stock','restocked') NOT NULL,
  `stock_level` int(11) NOT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_alerts_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_alerts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_alerts`
--

LOCK TABLES `inventory_alerts` WRITE;
/*!40000 ALTER TABLE `inventory_alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"28f7eeae-7b40-4ff9-9c3b-1c645cf6ed2c\",\"displayName\":\"App\\\\Mail\\\\LoginNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\LoginNotification\\\":7:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:9:\\\"loginTime\\\";s:27:\\\"January 28, 2026 at 7:31 PM\\\";s:9:\\\"ipAddress\\\";s:9:\\\"127.0.0.1\\\";s:9:\\\"userAgent\\\";s:111:\\\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\\\";s:8:\\\"location\\\";s:7:\\\"Unknown\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"abhichauhan200504@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1769628705,\"delay\":null}',0,NULL,1769628705,1769628705);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membership_plans`
--

DROP TABLE IF EXISTS `membership_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','quarterly','yearly') NOT NULL DEFAULT 'monthly',
  `duration_days` int(11) NOT NULL,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `early_access_days` int(11) NOT NULL DEFAULT 0,
  `priority_support` tinyint(1) NOT NULL DEFAULT 0,
  `exclusive_products` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_plans_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membership_plans`
--

LOCK TABLES `membership_plans` WRITE;
/*!40000 ALTER TABLE `membership_plans` DISABLE KEYS */;
INSERT INTO `membership_plans` VALUES (1,'Basic','basic','Perfect for occasional shoppers who want to save on shipping.',99.00,'monthly',30,1,0.00,0,0,0,'[\"Free shipping on all orders\",\"Member-only newsletter\"]',1,0,1,'2026-01-06 11:52:25','2026-01-06 11:52:25'),(2,'Premium','premium','Our most popular plan with great savings and early access.',199.00,'monthly',30,1,10.00,2,1,0,'[\"Free shipping on all orders\",\"10% off all purchases\",\"2-day early access to sales\",\"Priority customer support\"]',2,1,1,'2026-01-06 11:52:25','2026-01-06 11:52:25'),(3,'Elite','elite','The ultimate shopping experience with maximum benefits.',499.00,'monthly',30,1,15.00,5,1,1,'[\"Free shipping on all orders\",\"15% off all purchases\",\"5-day early access to sales\",\"Priority customer support\",\"Access to exclusive products\",\"Birthday bonus rewards\"]',3,0,1,'2026-01-06 11:52:25','2026-01-06 11:52:25'),(4,'Premium Annual','premium-annual','Save 20% with annual billing. Best value for regular shoppers.',1899.00,'yearly',365,1,10.00,2,1,0,'[\"Free shipping on all orders\",\"10% off all purchases\",\"2-day early access to sales\",\"Priority customer support\",\"Save 20% vs monthly\"]',4,0,1,'2026-01-06 11:52:25','2026-01-06 11:52:25'),(5,'Elite Annual','elite-annual','Maximum savings with all elite benefits for a full year.',4799.00,'yearly',365,1,15.00,5,1,1,'[\"Free shipping on all orders\",\"15% off all purchases\",\"5-day early access to sales\",\"Priority customer support\",\"Access to exclusive products\",\"Birthday bonus rewards\",\"Save 20% vs monthly\"]',5,0,1,'2026-01-06 11:52:25','2026-01-06 11:52:25');
/*!40000 ALTER TABLE `membership_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_02_000000_add_role_to_users_table',1),(5,'2025_01_02_100000_create_products_table',1),(6,'2025_01_02_200000_create_carts_table',1),(7,'2025_01_02_200001_create_orders_table',1),(8,'2025_01_02_200002_create_wishlists_table',1),(9,'2025_01_03_000001_add_profile_fields_to_users_table',2),(10,'2025_01_03_000002_create_addresses_table',2),(11,'2025_01_03_000003_create_payment_methods_table',2),(12,'2025_01_03_100000_create_categories_table',3),(13,'2025_01_03_100001_update_products_table_for_categories',3),(14,'2025_01_03_100002_migrate_products_to_categories',4),(15,'2025_01_03_200000_add_gender_to_products_table',5),(16,'2025_01_04_000001_create_reviews_table',6),(17,'2025_01_04_000002_add_social_login_fields_to_users_table',7),(18,'2025_01_04_100000_add_payment_fields_to_orders_table',8),(19,'2025_01_04_200000_create_site_settings_table',9),(20,'2025_01_04_210000_create_shop_banners_table',10),(21,'2025_01_05_100000_add_dark_mode_to_users_table',11),(22,'2025_01_05_100000_create_coupons_table',12),(23,'2025_01_05_110000_create_stock_notifications_table',13),(24,'2025_01_05_120000_create_product_variants_table',14),(25,'2025_01_05_120001_add_variant_id_to_carts_table',14),(26,'2025_01_05_130000_create_search_histories_table',15),(27,'2025_01_06_100000_create_support_tickets_table',16),(28,'2025_01_06_100001_create_faqs_table',16),(29,'2025_01_06_200000_create_memberships_table',17),(30,'2025_01_06_300000_add_login_otp_fields_to_users_table',18),(31,'2025_01_06_400000_add_discount_fields_to_orders_table',19),(32,'2025_01_07_100000_create_newsletter_subscribers_table',20),(33,'2025_01_07_110000_create_newsletter_campaigns_table',21),(34,'2025_01_07_200000_create_referral_system_tables',22),(35,'2025_01_07_210000_add_points_fields_to_orders_table',23),(36,'2025_01_08_100000_create_product_bundles_table',24),(37,'2025_01_09_100000_create_multi_vendor_tables',25),(38,'2025_01_09_100000_create_recently_viewed_table',25),(39,'2025_01_09_100001_create_flash_sales_table',25),(40,'2025_01_09_100002_create_abandoned_carts_table',25),(41,'2025_01_10_100000_create_customer_experience_tables',26),(42,'2025_01_10_120000_create_chat_tables',27),(43,'2025_01_10_130000_create_brand_pages_tables',28),(44,'2025_01_10_140000_create_ethos_tables',29),(45,'2025_01_10_150000_create_blog_tables',30);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_campaigns`
--

DROP TABLE IF EXISTS `newsletter_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `type` enum('promotional','new_arrivals','sale','announcement','custom') NOT NULL DEFAULT 'custom',
  `status` enum('draft','scheduled','sending','sent','failed') NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `total_recipients` int(11) NOT NULL DEFAULT 0,
  `sent_count` int(11) NOT NULL DEFAULT 0,
  `failed_count` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsletter_campaigns_created_by_foreign` (`created_by`),
  KEY `newsletter_campaigns_status_index` (`status`),
  KEY `newsletter_campaigns_type_index` (`type`),
  CONSTRAINT `newsletter_campaigns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_campaigns`
--

LOCK TABLES `newsletter_campaigns` WRITE;
/*!40000 ALTER TABLE `newsletter_campaigns` DISABLE KEYS */;
INSERT INTO `newsletter_campaigns` VALUES (1,'ok','<p>Hi there! 👋</p>\r\n\r\n<p>Fresh styles have just landed! Check out our <strong>NEW ARRIVALS</strong> collection.</p>\r\n\r\n<p>We\'ve curated the latest trends just for you. From elegant dresses to casual wear, there\'s something for everyone.</p>\r\n\r\n<p>✨ <strong>What\'s New:</strong></p>\r\n<ul>\r\n    <li>Spring/Summer Collection 2026</li>\r\n    <li>Exclusive Designer Pieces</li>\r\n    <li>Limited Edition Items</li>\r\n</ul>\r\n\r\n<p>Be the first to shop these new styles before they sell out!</p>\r\n\r\n<p>With love,<br>Team ShopEase</p>','new_arrivals','sent',NULL,'2026-01-07 04:59:55',1,1,0,1,'2026-01-07 04:59:03','2026-01-07 04:59:55');
/*!40000 ALTER TABLE `newsletter_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_logs`
--

DROP TABLE IF EXISTS `newsletter_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint(20) unsigned NOT NULL,
  `subscriber_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('sent','failed','pending') NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsletter_logs_subscriber_id_foreign` (`subscriber_id`),
  KEY `newsletter_logs_campaign_id_status_index` (`campaign_id`,`status`),
  CONSTRAINT `newsletter_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `newsletter_campaigns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `newsletter_logs_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `newsletter_subscribers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_logs`
--

LOCK TABLES `newsletter_logs` WRITE;
/*!40000 ALTER TABLE `newsletter_logs` DISABLE KEYS */;
INSERT INTO `newsletter_logs` VALUES (1,1,1,'abhichauhan200504@gmail.com','sent',NULL,'2026-01-07 04:59:55','2026-01-07 04:59:55','2026-01-07 04:59:55');
/*!40000 ALTER TABLE `newsletter_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` enum('active','unsubscribed') NOT NULL DEFAULT 'active',
  `unsubscribe_token` varchar(255) DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  UNIQUE KEY `newsletter_subscribers_unsubscribe_token_unique` (`unsubscribe_token`),
  KEY `newsletter_subscribers_status_index` (`status`),
  KEY `newsletter_subscribers_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (1,'abhichauhan200504@gmail.com',NULL,'active','Kv3J00Id05RyGZe4STSKjbFSMeR73FhZXHqkjj7b8aoBk9TOp8j1TucYcw7HNIn6','2026-01-07 10:08:53',NULL,'2026-01-07 04:38:53','2026-01-07 04:38:53');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (2,2,26,'Classic White Oxford Shirt',89.00,1,89.00,'2026-01-03 15:28:15','2026-01-03 15:28:15'),(3,2,27,'Navy Blue Blazer',245.00,1,245.00,'2026-01-03 15:28:15','2026-01-03 15:28:15'),(4,2,28,'Slim Fit Chinos',75.00,1,75.00,'2026-01-03 15:28:15','2026-01-03 15:28:15'),(5,3,26,'Classic White Oxford Shirt',89.00,3,267.00,'2026-01-04 11:42:46','2026-01-04 11:42:46'),(6,4,34,'Wool Overcoat',395.00,1,395.00,'2026-01-04 23:02:42','2026-01-04 23:02:42'),(7,5,28,'Slim Fit Chinos',75.00,1,75.00,'2026-01-05 13:40:20','2026-01-05 13:40:20'),(8,6,29,'Cashmere V-Neck Sweater',195.00,2,390.00,'2026-01-11 12:42:01','2026-01-11 12:42:01');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_tracking_events`
--

DROP TABLE IF EXISTS `order_tracking_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_tracking_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `event_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_tracking_events_order_id_event_time_index` (`order_id`,`event_time`),
  CONSTRAINT `order_tracking_events_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_tracking_events`
--

LOCK TABLES `order_tracking_events` WRITE;
/*!40000 ALTER TABLE `order_tracking_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_tracking_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `coupon_id` bigint(20) unsigned DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `shipping_name` varchar(255) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_phone` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_zip` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `estimated_delivery` timestamp NULL DEFAULT NULL,
  `points_redeemed` decimal(10,2) NOT NULL DEFAULT 0.00,
  `points_earned` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-69593E1B82A7C',3,299.99,0.00,24.00,323.99,'pending','pending','cod',NULL,NULL,0.00,NULL,NULL,NULL,NULL,'abhi','abhi@abhi.com','657657654','jhgfhgchgv','mnhbjhgb','mnhvb','mnb',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,'2026-01-03 10:34:43','2026-01-03 10:34:43'),(2,'ORD-695982E7A522F',5,409.00,0.00,32.72,441.72,'delivered','paid','card',NULL,NULL,0.00,NULL,NULL,NULL,NULL,'Abhishek Chauhan','abhishekchauhan.gms@gmail.com','4656546465','vvhgvhgv','bnbbn','nbnb','7678',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,'2026-01-03 15:28:15','2026-01-04 12:25:14'),(3,'ORD-695A9F8EAB1C3',4,267.00,49.00,48.06,364.06,'cancelled','paid','upi',NULL,NULL,0.00,'order_RzsbJgNwZCdnWM','pay_RzscWWYXXSEUeX','b310b1aa903091241a23bf1046ea796ed9e4b005b156a3152286eac908f8e00b','2026-01-04 13:07:22','Abhishek Chauhan','abhichauhan200504@gmail.com','8279422813','295/1 baral partapur meerut','meerut','uttar pradesh','250103',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,'2026-01-04 11:42:46','2026-01-04 13:07:29'),(4,'ORD-695B3EEAB312B',7,395.00,49.00,71.10,515.10,'processing','paid','upi',NULL,NULL,0.00,'order_S04BXJMuZJs5C5','pay_S04CCQykNHjLUj','2916f0dea293f27c5ac606a8acd2946991a413b86a8b00435b59cfebb0bc4770','2026-01-04 23:03:34','Leslie Bennett','babitachauhan161@gmail.com','34234234','234234','234234234','234234234','234234',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,'2026-01-04 23:02:42','2026-01-04 23:03:34'),(5,'ORD-695C0C9C7CE12',5,69.00,49.00,12.42,130.42,'processing','paid','upi',NULL,NULL,0.00,'order_S0J8ayQyOFmYbZ','pay_S0J9Mxj22RjHvl','495ba3a997fe0e75788de737761a139b6dc0a4e5b8b183de8805f60989feb6f6','2026-01-05 13:41:21','Abhishek Chauhan','abhishekchauhan.gms@gmail.com','+914656546465','vvhgvhgv','bnbbn','nbnb','7678',NULL,NULL,NULL,NULL,NULL,NULL,0.00,0.00,'2026-01-05 13:40:20','2026-01-05 13:41:21'),(6,'ORD-6963E7F123346',8,382.20,49.00,68.80,500.00,'processing','paid','upi',NULL,NULL,0.00,'order_S2fLmhPo1yic8m','pay_S2fMONrqXPGVms','41807afa2258f9c6d7c7b38e16c9373118f0ef5fc00fde5e123ce606b8c2def1','2026-01-11 12:42:53','Abhishek Chauhan','abhishek.codes2004@gmail.com','873678346','sfcfdskjdfhekrf','fvrfvrfvfdvfv','fvrfv','2334',NULL,NULL,NULL,NULL,NULL,NULL,0.00,500.00,'2026-01-11 12:42:01','2026-01-11 12:42:53');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('card','upi','netbanking','wallet') NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `last_four` varchar(255) DEFAULT NULL,
  `card_brand` varchar(255) DEFAULT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_methods_user_id_foreign` (`user_id`),
  CONSTRAINT `payment_methods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `popular_searches`
--

DROP TABLE IF EXISTS `popular_searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `popular_searches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `search_count` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `popular_searches_query_unique` (`query`),
  KEY `popular_searches_search_count_index` (`search_count`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `popular_searches`
--

LOCK TABLES `popular_searches` WRITE;
/*!40000 ALTER TABLE `popular_searches` DISABLE KEYS */;
INSERT INTO `popular_searches` VALUES (1,'dress',150,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(2,'shirt',120,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(3,'jeans',100,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(4,'jacket',95,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(5,'shoes',90,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(6,'bag',85,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(7,'watch',80,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(8,'sunglasses',75,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(9,'sneakers',70,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(10,'blazer',65,'2026-01-05 13:52:25','2026-01-05 13:52:25'),(11,'hjjh',7,'2026-01-05 13:53:34','2026-01-05 13:53:46');
/*!40000 ALTER TABLE `popular_searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_steps`
--

DROP TABLE IF EXISTS `process_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_steps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `step_number` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_steps`
--

LOCK TABLES `process_steps` WRITE;
/*!40000 ALTER TABLE `process_steps` DISABLE KEYS */;
INSERT INTO `process_steps` VALUES (1,'Sourcing','We carefully select materials from trusted suppliers around the world. Each material is chosen for its quality, sustainability, and ability to create products that last.\n\nOur sourcing team travels extensively to find the finest raw materials, building relationships with artisans and suppliers who share our commitment to excellence.',NULL,'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=1000',1,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(2,'Design','Our design team combines timeless aesthetics with modern functionality. Every piece is thoughtfully designed to be both beautiful and practical.\n\nWe believe great design should enhance your life, not complicate it. That\'s why we focus on clean lines, quality materials, and attention to detail.',NULL,'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?auto=format&fit=crop&q=80&w=1000',2,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(3,'Crafting','Skilled artisans bring our designs to life using time-honored techniques combined with modern precision. Each piece is crafted with care and attention to detail.\n\nWe work with craftspeople who take pride in their work, ensuring every stitch, seam, and finish meets our exacting standards.',NULL,'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&q=80&w=1000',3,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(4,'Quality Check','Before any product reaches you, it undergoes rigorous quality inspection. We check every detail to ensure it meets our high standards.\n\nOur quality control process includes multiple checkpoints, from material inspection to final product review, ensuring consistency and excellence.',NULL,'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=1000',4,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(5,'Delivery','We carefully package each order to ensure it arrives in perfect condition. Our shipping partners are chosen for their reliability and care.\n\nFrom our hands to yours, we ensure your purchase is protected and delivered with the same attention to detail we put into creating it.',NULL,'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&q=80&w=1000',5,1,'2026-01-10 04:18:58','2026-01-10 04:18:58');
/*!40000 ALTER TABLE `process_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_answers`
--

DROP TABLE IF EXISTS `product_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `answer` text NOT NULL,
  `is_seller_answer` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_answers_question_id_foreign` (`question_id`),
  KEY `product_answers_user_id_foreign` (`user_id`),
  CONSTRAINT `product_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `product_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_answers`
--

LOCK TABLES `product_answers` WRITE;
/*!40000 ALTER TABLE `product_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_bundles`
--

DROP TABLE IF EXISTS `product_bundles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_bundles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bundle_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_bundles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_bundles`
--

LOCK TABLES `product_bundles` WRITE;
/*!40000 ALTER TABLE `product_bundles` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_bundles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_comparisons`
--

DROP TABLE IF EXISTS `product_comparisons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_comparisons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`product_ids`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_comparisons_user_id_session_id_index` (`user_id`,`session_id`),
  CONSTRAINT `product_comparisons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_comparisons`
--

LOCK TABLES `product_comparisons` WRITE;
/*!40000 ALTER TABLE `product_comparisons` DISABLE KEYS */;
INSERT INTO `product_comparisons` VALUES (1,1,'2mhrkleimDikGguH7AZrx8z5BqUrwfJgQbJR3pa5','[28]','2026-01-10 02:33:04','2026-01-10 02:33:04');
/*!40000 ALTER TABLE `product_comparisons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_questions`
--

DROP TABLE IF EXISTS `product_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `question` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_questions_product_id_foreign` (`product_id`),
  KEY `product_questions_user_id_foreign` (`user_id`),
  CONSTRAINT `product_questions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_questions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_questions`
--

LOCK TABLES `product_questions` WRITE;
/*!40000 ALTER TABLE `product_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_is_active_index` (`product_id`,`is_active`),
  KEY `product_variants_size_color_material_index` (`size`,`color`,`material`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,28,'SLIM-28-XS','XS',NULL,NULL,NULL,73.50,NULL,17,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(2,28,'SLIM-28-S','S',NULL,NULL,NULL,78.00,NULL,39,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(3,28,'SLIM-28-M','M',NULL,NULL,NULL,88.50,75.23,23,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(4,28,'SLIM-28-L','L',NULL,NULL,NULL,69.00,NULL,33,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(5,28,'SLIM-28-XL','XL',NULL,NULL,NULL,81.00,NULL,23,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(6,29,'CASH-29-XS-BLA-COT','XS','Black','#000000','Cotton',191.10,NULL,17,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(7,29,'CASH-29-XS-BLA-SIL','XS','Black','#000000','Silk',210.60,NULL,15,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(8,29,'CASH-29-XS-WHI-COT','XS','White','#FFFFFF','Cotton',210.60,179.01,35,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(9,29,'CASH-29-XS-WHI-SIL','XS','White','#FFFFFF','Silk',196.95,NULL,48,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(10,29,'CASH-29-S-BLA-COT','S','Black','#000000','Cotton',230.10,195.59,38,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(11,29,'CASH-29-S-BLA-SIL','S','Black','#000000','Silk',185.25,NULL,33,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(12,29,'CASH-29-S-WHI-COT','S','White','#FFFFFF','Cotton',226.20,NULL,10,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(13,29,'CASH-29-S-WHI-SIL','S','White','#FFFFFF','Silk',189.15,NULL,42,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(14,29,'CASH-29-M-BLA-COT','M','Black','#000000','Cotton',191.10,NULL,31,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(15,29,'CASH-29-M-BLA-SIL','M','Black','#000000','Silk',202.80,NULL,45,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(16,29,'CASH-29-M-WHI-COT','M','White','#FFFFFF','Cotton',226.20,NULL,5,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(17,29,'CASH-29-M-WHI-SIL','M','White','#FFFFFF','Silk',191.10,NULL,35,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(18,30,'DENI-30-XS-BLA-COT','XS','Black','#000000','Cotton',150.00,NULL,11,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(19,30,'DENI-30-XS-BLA-SIL','XS','Black','#000000','Silk',136.25,NULL,37,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(20,30,'DENI-30-XS-BLA-LIN','XS','Black','#000000','Linen',128.75,NULL,28,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(21,30,'DENI-30-XS-WHI-COT','XS','White','#FFFFFF','Cotton',140.00,119.00,41,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(22,30,'DENI-30-XS-WHI-SIL','XS','White','#FFFFFF','Silk',147.50,125.38,48,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(23,30,'DENI-30-XS-WHI-LIN','XS','White','#FFFFFF','Linen',133.75,113.69,5,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(24,30,'DENI-30-S-BLA-COT','S','Black','#000000','Cotton',131.25,NULL,5,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(25,30,'DENI-30-S-BLA-SIL','S','Black','#000000','Silk',117.50,99.88,26,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(26,30,'DENI-30-S-BLA-LIN','S','Black','#000000','Linen',143.75,NULL,31,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(27,30,'DENI-30-S-WHI-COT','S','White','#FFFFFF','Cotton',148.75,NULL,18,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(28,30,'DENI-30-S-WHI-SIL','S','White','#FFFFFF','Silk',132.50,NULL,24,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(29,30,'DENI-30-S-WHI-LIN','S','White','#FFFFFF','Linen',122.50,NULL,44,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(30,30,'DENI-30-M-BLA-COT','M','Black','#000000','Cotton',126.25,NULL,17,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(31,30,'DENI-30-M-BLA-SIL','M','Black','#000000','Silk',140.00,NULL,6,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(32,30,'DENI-30-M-BLA-LIN','M','Black','#000000','Linen',126.25,107.31,23,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(33,30,'DENI-30-M-WHI-COT','M','White','#FFFFFF','Cotton',138.75,NULL,34,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(34,30,'DENI-30-M-WHI-SIL','M','White','#FFFFFF','Silk',116.25,NULL,33,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(35,30,'DENI-30-M-WHI-LIN','M','White','#FFFFFF','Linen',122.50,104.13,41,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(36,30,'DENI-30-L-BLA-COT','L','Black','#000000','Cotton',123.75,NULL,39,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(37,30,'DENI-30-L-BLA-SIL','L','Black','#000000','Silk',140.00,NULL,36,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(38,30,'DENI-30-L-BLA-LIN','L','Black','#000000','Linen',146.25,NULL,37,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(39,30,'DENI-30-L-WHI-COT','L','White','#FFFFFF','Cotton',131.25,111.56,29,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(40,30,'DENI-30-L-WHI-SIL','L','White','#FFFFFF','Silk',142.50,NULL,37,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(41,30,'DENI-30-L-WHI-LIN','L','White','#FFFFFF','Linen',122.50,NULL,49,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(42,30,'DENI-30-XL-BLA-COT','XL','Black','#000000','Cotton',146.25,NULL,25,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(43,30,'DENI-30-XL-BLA-SIL','XL','Black','#000000','Silk',140.00,NULL,23,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(44,30,'DENI-30-XL-BLA-LIN','XL','Black','#000000','Linen',146.25,NULL,42,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(45,30,'DENI-30-XL-WHI-COT','XL','White','#FFFFFF','Cotton',146.25,NULL,42,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(46,30,'DENI-30-XL-WHI-SIL','XL','White','#FFFFFF','Silk',136.25,NULL,6,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(47,30,'DENI-30-XL-WHI-LIN','XL','White','#FFFFFF','Linen',138.75,NULL,2,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(48,30,'DENI-30-XX-BLA-COT','XXL','Black','#000000','Cotton',145.00,123.25,15,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(49,30,'DENI-30-XX-BLA-SIL','XXL','Black','#000000','Silk',137.50,116.88,15,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(50,30,'DENI-30-XX-BLA-LIN','XXL','Black','#000000','Linen',148.75,126.44,21,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(51,30,'DENI-30-XX-WHI-COT','XXL','White','#FFFFFF','Cotton',130.00,NULL,23,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(52,30,'DENI-30-XX-WHI-SIL','XXL','White','#FFFFFF','Silk',137.50,116.88,44,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(53,30,'DENI-30-XX-WHI-LIN','XXL','White','#FFFFFF','Linen',147.50,NULL,30,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(54,31,'PREM-31-BLA',NULL,'Black','#000000',NULL,73.45,NULL,2,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(55,31,'PREM-31-WHI',NULL,'White','#FFFFFF',NULL,73.45,NULL,29,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(56,31,'PREM-31-NAV',NULL,'Navy','#1e3a5f',NULL,75.40,NULL,32,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(57,32,'LEAT-32-XS-COT','XS',NULL,NULL,'Cotton',423.00,359.55,47,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(58,32,'LEAT-32-XS-SIL','XS',NULL,NULL,'Silk',499.50,NULL,6,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(59,32,'LEAT-32-S-COT','S',NULL,NULL,'Cotton',486.00,NULL,3,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(60,32,'LEAT-32-S-SIL','S',NULL,NULL,'Silk',468.00,NULL,14,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(61,32,'LEAT-32-M-COT','M',NULL,NULL,'Cotton',409.50,NULL,24,NULL,0,'2026-01-05 13:18:31','2026-01-05 13:18:31'),(62,32,'LEAT-32-M-SIL','M',NULL,NULL,'Silk',414.00,351.90,25,NULL,1,'2026-01-05 13:18:31','2026-01-05 13:18:31');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `gender` enum('men','women','unisex') NOT NULL DEFAULT 'unisex',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `category_old` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `hide_when_out_of_stock` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `has_variants` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_seller_id_foreign` (`seller_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (26,NULL,16,'men','Classic White Oxford Shirt','Crisp cotton oxford shirt with button-down collar.',89.00,NULL,'Fashion',49,5,1,'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600','active','approved',0,'2026-01-04 14:53:43','2026-01-03 13:20:34','2026-01-04 14:53:43'),(27,NULL,16,'men','Navy Blue Blazer','Tailored wool-blend blazer with notch lapels.',295.00,245.00,'Fashion',29,5,1,'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=600','active','approved',0,'2026-01-04 14:53:49','2026-01-03 13:20:34','2026-01-04 14:53:49'),(28,NULL,16,'men','Slim Fit Chinos','Comfortable stretch cotton chinos in khaki.',75.00,NULL,'Fashion',78,5,1,'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=600','active','approved',1,NULL,'2026-01-03 13:20:34','2026-01-05 13:41:21'),(29,NULL,16,'men','Cashmere V-Neck Sweater','Luxuriously soft pure cashmere sweater.',195.00,NULL,'Fashion',23,5,1,'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600','active','approved',1,NULL,'2026-01-03 13:20:34','2026-01-11 12:42:53'),(30,NULL,16,'men','Denim Jacket','Classic indigo denim jacket with vintage wash.',125.00,NULL,'Fashion',45,5,1,'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600','active','approved',1,NULL,'2026-01-03 13:20:34','2026-01-05 13:18:31'),(31,NULL,16,'men','Premium Polo Shirt','Pique cotton polo with embroidered logo.',65.00,NULL,'Fashion',100,5,1,'https://images.unsplash.com/photo-1625910513413-5fc45e80b5b5?w=600','active','approved',1,NULL,'2026-01-03 13:20:34','2026-01-05 13:18:31'),(32,NULL,16,'men','Leather Bomber Jacket','Genuine leather bomber with ribbed cuffs.',450.00,375.00,'Fashion',15,5,1,'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600','active','approved',1,NULL,'2026-01-03 13:20:34','2026-01-05 13:18:31'),(33,NULL,16,'men','Linen Summer Shirt','Breathable pure linen shirt for warm weather.',85.00,NULL,'Fashion',60,5,1,'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(34,NULL,16,'men','Wool Overcoat','Double-breasted wool overcoat in camel.',395.00,NULL,'Fashion',19,5,1,'https://images.unsplash.com/photo-1544923246-77307dd628b8?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-04 23:03:34'),(35,NULL,16,'men','Graphic Print T-Shirt','Soft cotton tee with artistic print.',45.00,NULL,'Fashion',120,5,1,'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(36,NULL,16,'men','Tailored Dress Pants','Wool-blend dress pants with pressed crease.',145.00,NULL,'Fashion',40,5,1,'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(37,NULL,16,'men','Hooded Sweatshirt','Premium fleece hoodie with kangaroo pocket.',95.00,NULL,'Fashion',75,5,1,'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(38,NULL,17,'women','Silk Blouse','Elegant silk blouse with bow tie neck.',145.00,NULL,'Fashion',40,5,1,'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(39,NULL,17,'women','Floral Midi Dress','Romantic floral print dress with flowing silhouette.',125.00,99.00,'Fashion',55,5,1,'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(40,NULL,17,'women','High-Waist Trousers','Tailored wide-leg trousers in classic black.',110.00,NULL,'Fashion',65,5,1,'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(41,NULL,17,'women','Cashmere Cardigan','Soft cashmere cardigan with pearl buttons.',225.00,NULL,'Fashion',30,5,1,'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(42,NULL,17,'women','Little Black Dress','Timeless LBD with elegant neckline.',175.00,NULL,'Fashion',35,5,1,'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(43,NULL,17,'women','Denim Skinny Jeans','High-rise skinny jeans with stretch comfort.',89.00,NULL,'Fashion',90,5,1,'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(44,NULL,17,'women','Wool Blend Coat','Elegant belted coat in camel.',295.00,249.00,'Fashion',25,5,1,'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(45,NULL,17,'women','Striped Breton Top','Classic French-style striped top.',55.00,NULL,'Fashion',80,5,1,'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(46,NULL,17,'women','Pleated Maxi Skirt','Flowing pleated skirt in dusty rose.',95.00,NULL,'Fashion',45,5,1,'https://images.unsplash.com/photo-1583496661160-fb5886a0uj9a?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(47,NULL,17,'women','Knit Turtleneck','Cozy ribbed turtleneck in cream.',85.00,NULL,'Fashion',70,5,1,'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(48,NULL,17,'women','Satin Slip Dress','Luxurious satin slip dress for evening.',165.00,NULL,'Fashion',30,5,1,'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(49,NULL,18,'unisex','White Leather Sneakers','Clean minimalist sneakers in premium leather.',145.00,NULL,'Footwear',100,5,1,'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(50,NULL,18,'men','Oxford Dress Shoes','Handcrafted leather oxfords.',275.00,NULL,'Footwear',35,5,1,'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(51,NULL,18,'unisex','Running Shoes Pro','High-performance running shoes.',165.00,139.00,'Footwear',80,5,1,'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(52,NULL,18,'men','Suede Chelsea Boots','Classic Chelsea boots in tan suede.',225.00,NULL,'Footwear',40,5,1,'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(53,NULL,18,'women','Stiletto Heels','Elegant pointed-toe heels in black patent.',195.00,NULL,'Footwear',45,5,1,'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(54,NULL,18,'unisex','Canvas Slip-Ons','Casual canvas slip-ons for summer.',55.00,NULL,'Footwear',120,5,1,'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(55,NULL,18,'women','Ankle Boots','Chic leather ankle boots with block heel.',185.00,NULL,'Footwear',50,5,1,'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(56,NULL,18,'men','Loafers Classic','Penny loafers in burgundy leather.',195.00,NULL,'Footwear',55,5,1,'https://images.unsplash.com/photo-1582897085656-c636d006a246?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(57,NULL,18,'women','Platform Sandals','Trendy platform sandals with ankle strap.',125.00,99.00,'Footwear',60,5,1,'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(58,NULL,18,'unisex','High-Top Sneakers','Retro-inspired high-tops in black and white.',115.00,NULL,'Footwear',75,5,1,'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(59,NULL,18,'unisex','Hiking Boots','Waterproof hiking boots with superior grip.',185.00,NULL,'Footwear',40,5,1,'https://images.unsplash.com/photo-1520219306100-ec4afeeefe58?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(60,NULL,18,'women','Ballet Flats','Comfortable leather ballet flats in nude.',95.00,NULL,'Footwear',65,5,1,'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(61,NULL,19,'women','Leather Tote Bag','Spacious leather tote with interior pockets.',245.00,NULL,'Accessories',35,5,1,'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(62,NULL,19,'unisex','Classic Aviator Sunglasses','Timeless aviator frames with polarized lenses.',165.00,NULL,'Accessories',80,5,1,'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(63,NULL,19,'men','Automatic Watch','Swiss automatic movement watch.',495.00,425.00,'Accessories',20,5,1,'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(64,NULL,19,'women','Silk Scarf','Hand-printed silk scarf with artistic pattern.',125.00,NULL,'Accessories',50,5,1,'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(65,NULL,19,'men','Leather Wallet','Bifold wallet in full-grain leather.',85.00,NULL,'Accessories',90,5,1,'https://images.unsplash.com/photo-1627123424574-724758594e93?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(66,NULL,19,'women','Pearl Drop Earrings','Freshwater pearl earrings with sterling silver.',145.00,NULL,'Accessories',40,5,1,'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(67,NULL,19,'unisex','Canvas Backpack','Durable canvas backpack with leather trim.',125.00,NULL,'Accessories',70,5,1,'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(68,NULL,19,'men','Leather Belt','Classic leather belt with brushed silver buckle.',75.00,NULL,'Accessories',100,5,1,'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(69,NULL,19,'women','Gold Chain Necklace','Delicate gold-plated chain necklace.',95.00,NULL,'Accessories',55,5,1,'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(70,NULL,19,'women','Crossbody Bag','Compact crossbody bag in quilted leather.',175.00,NULL,'Accessories',45,5,1,'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(71,NULL,19,'unisex','Wool Fedora Hat','Classic wool fedora with grosgrain ribbon.',85.00,NULL,'Accessories',35,5,1,'https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(72,NULL,20,'unisex','Wireless Headphones','Premium over-ear headphones with ANC.',299.00,249.00,'Electronics',60,5,1,'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(73,NULL,20,'unisex','Smart Watch','Feature-rich smartwatch with health tracking.',349.00,NULL,'Electronics',45,5,1,'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(74,NULL,20,'unisex','Wireless Earbuds','True wireless earbuds with premium sound.',179.00,NULL,'Electronics',80,5,1,'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(75,NULL,20,'unisex','Portable Speaker','Waterproof Bluetooth speaker with 360° sound.',129.00,NULL,'Electronics',70,5,1,'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(76,NULL,20,'unisex','Laptop Stand','Ergonomic aluminum laptop stand.',79.00,NULL,'Electronics',55,5,1,'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(77,NULL,20,'unisex','Wireless Charger','Fast wireless charging pad.',45.00,NULL,'Electronics',100,5,1,'https://images.unsplash.com/photo-1586816879360-004f5b0c51e5?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(78,NULL,20,'unisex','Mechanical Keyboard','RGB mechanical keyboard with tactile switches.',149.00,NULL,'Electronics',40,5,1,'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(79,NULL,20,'unisex','Webcam HD','1080p HD webcam with built-in microphone.',89.00,NULL,'Electronics',65,5,1,'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(80,NULL,20,'unisex','Power Bank','20000mAh portable charger with fast charging.',59.00,NULL,'Electronics',90,5,1,'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(81,NULL,20,'unisex','USB-C Hub','Multi-port USB-C hub with HDMI.',69.00,NULL,'Electronics',75,5,1,'https://images.unsplash.com/photo-1625723044792-44de16ccb4e9?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(82,NULL,21,'unisex','Ceramic Vase Set','Set of 3 minimalist ceramic vases.',85.00,NULL,'Home',50,5,1,'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(83,NULL,21,'unisex','Scented Candle Set','Luxury soy candles in lavender, vanilla.',55.00,NULL,'Home',80,5,1,'https://images.unsplash.com/photo-1602607753754-e8e0e5e5e5e5?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(84,NULL,21,'unisex','Throw Blanket','Cozy knit throw blanket in cream.',95.00,NULL,'Home',45,5,1,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(85,NULL,21,'unisex','Desk Organizer','Bamboo desk organizer with compartments.',45.00,NULL,'Home',70,5,1,'https://images.unsplash.com/photo-1544816155-12df9643f363?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(86,NULL,21,'unisex','Plant Pot Set','Set of 3 terracotta pots with saucers.',35.00,NULL,'Home',90,5,1,'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(87,NULL,21,'unisex','Table Lamp','Modern LED table lamp with adjustable brightness.',89.00,NULL,'Home',55,5,1,'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(88,NULL,21,'unisex','Wall Art Print','Abstract art print on premium paper.',65.00,NULL,'Home',40,5,1,'https://images.unsplash.com/photo-1513519245088-0e12902e35a6?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(89,NULL,21,'unisex','Coffee Table Book','Stunning photography book on architecture.',75.00,NULL,'Home',35,5,1,'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(90,NULL,21,'unisex','Cushion Cover Set','Set of 2 linen cushion covers in sage.',45.00,NULL,'Home',60,5,1,'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(91,NULL,21,'unisex','Storage Basket','Handwoven seagrass basket for storage.',55.00,NULL,'Home',65,5,1,'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=600','active','approved',0,NULL,'2026-01-03 13:20:34','2026-01-03 13:20:34'),(92,NULL,20,'unisex','Abhishek Chauhan','fvdgvdg',333.00,33.00,NULL,0,5,1,NULL,'active','approved',0,NULL,'2026-01-11 13:04:26','2026-01-11 13:04:26');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recently_viewed`
--

DROP TABLE IF EXISTS `recently_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recently_viewed` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recently_viewed_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `recently_viewed_product_id_foreign` (`product_id`),
  KEY `recently_viewed_user_id_viewed_at_index` (`user_id`,`viewed_at`),
  CONSTRAINT `recently_viewed_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recently_viewed_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recently_viewed`
--

LOCK TABLES `recently_viewed` WRITE;
/*!40000 ALTER TABLE `recently_viewed` DISABLE KEYS */;
INSERT INTO `recently_viewed` VALUES (1,1,28,'2026-01-10 02:32:52','2026-01-10 02:32:16','2026-01-10 02:32:52'),(2,5,28,'2026-01-10 02:40:34','2026-01-10 02:40:34','2026-01-10 02:40:34'),(3,5,29,'2026-01-10 02:41:02','2026-01-10 02:40:39','2026-01-10 02:41:02'),(4,8,28,'2026-01-11 12:38:44','2026-01-11 12:38:44','2026-01-11 12:38:44'),(5,8,29,'2026-01-11 12:44:54','2026-01-11 12:38:53','2026-01-11 12:44:54'),(6,1,77,'2026-01-16 10:46:02','2026-01-16 10:46:02','2026-01-16 10:46:02');
/*!40000 ALTER TABLE `recently_viewed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_settings`
--

DROP TABLE IF EXISTS `referral_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referral_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `referral_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_settings`
--

LOCK TABLES `referral_settings` WRITE;
/*!40000 ALTER TABLE `referral_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `referral_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `referrer_id` bigint(20) unsigned NOT NULL,
  `referred_id` bigint(20) unsigned NOT NULL,
  `status` enum('pending','completed','expired') NOT NULL DEFAULT 'pending',
  `referrer_reward` decimal(10,2) NOT NULL DEFAULT 0.00,
  `referred_reward` decimal(10,2) NOT NULL DEFAULT 0.00,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrals_referrer_id_foreign` (`referrer_id`),
  KEY `referrals_referred_id_foreign` (`referred_id`),
  CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referrals`
--

LOCK TABLES `referrals` WRITE;
/*!40000 ALTER TABLE `referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_photos`
--

DROP TABLE IF EXISTS `review_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `review_photos_review_id_foreign` (`review_id`),
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
-- Table structure for table `review_votes`
--

DROP TABLE IF EXISTS `review_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_votes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_helpful` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `review_votes_review_id_user_id_unique` (`review_id`,`user_id`),
  KEY `review_votes_user_id_foreign` (`user_id`),
  CONSTRAINT `review_votes_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  CONSTRAINT `review_votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_votes`
--

LOCK TABLES `review_votes` WRITE;
/*!40000 ALTER TABLE `review_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `rating` int(10) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `not_helpful_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
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
-- Table structure for table `reward_transactions`
--

DROP TABLE IF EXISTS `reward_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reward_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('earned','redeemed','expired','adjusted') NOT NULL,
  `points` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `sourceable_type` varchar(255) NOT NULL,
  `sourceable_id` bigint(20) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reward_transactions_user_id_foreign` (`user_id`),
  KEY `reward_transactions_sourceable_type_sourceable_id_index` (`sourceable_type`,`sourceable_id`),
  CONSTRAINT `reward_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reward_transactions`
--

LOCK TABLES `reward_transactions` WRITE;
/*!40000 ALTER TABLE `reward_transactions` DISABLE KEYS */;
INSERT INTO `reward_transactions` VALUES (1,8,'earned',500.00,500.00,'order','App\\Models\\Order',6,'Earned 500 points from order #ORD-6963E7F123346','2026-01-11 12:42:53','2026-01-11 12:42:53');
/*!40000 ALTER TABLE `reward_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_histories`
--

DROP TABLE IF EXISTS `search_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `query` varchar(255) NOT NULL,
  `results_count` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_histories_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `search_histories_query_index` (`query`),
  CONSTRAINT `search_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_histories`
--

LOCK TABLES `search_histories` WRITE;
/*!40000 ALTER TABLE `search_histories` DISABLE KEYS */;
INSERT INTO `search_histories` VALUES (1,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:34','2026-01-05 13:53:34'),(2,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:41','2026-01-05 13:53:41'),(3,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:43','2026-01-05 13:53:43'),(4,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:43','2026-01-05 13:53:43'),(5,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:45','2026-01-05 13:53:45'),(6,5,'hjjh',0,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','2026-01-05 13:53:46','2026-01-05 13:53:46');
/*!40000 ALTER TABLE `search_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller_earnings`
--

DROP TABLE IF EXISTS `seller_earnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller_earnings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `order_item_id` bigint(20) unsigned DEFAULT NULL,
  `order_amount` decimal(12,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(12,2) NOT NULL,
  `seller_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','processed','paid','cancelled') NOT NULL DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_earnings_seller_id_foreign` (`seller_id`),
  KEY `seller_earnings_order_id_foreign` (`order_id`),
  CONSTRAINT `seller_earnings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seller_earnings_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller_earnings`
--

LOCK TABLES `seller_earnings` WRITE;
/*!40000 ALTER TABLE `seller_earnings` DISABLE KEYS */;
/*!40000 ALTER TABLE `seller_earnings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller_payouts`
--

DROP TABLE IF EXISTS `seller_payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller_payouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) unsigned NOT NULL,
  `payout_id` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('bank_transfer','upi','razorpay') NOT NULL DEFAULT 'bank_transfer',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seller_payouts_payout_id_unique` (`payout_id`),
  KEY `seller_payouts_seller_id_foreign` (`seller_id`),
  CONSTRAINT `seller_payouts_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller_payouts`
--

LOCK TABLES `seller_payouts` WRITE;
/*!40000 ALTER TABLE `seller_payouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `seller_payouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller_reviews`
--

DROP TABLE IF EXISTS `seller_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `review` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seller_reviews_seller_id_foreign` (`seller_id`),
  KEY `seller_reviews_user_id_foreign` (`user_id`),
  KEY `seller_reviews_order_id_foreign` (`order_id`),
  CONSTRAINT `seller_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seller_reviews_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seller_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller_reviews`
--

LOCK TABLES `seller_reviews` WRITE;
/*!40000 ALTER TABLE `seller_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `seller_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seller_settings`
--

DROP TABLE IF EXISTS `seller_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seller_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `default_commission_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `minimum_payout_amount` decimal(10,2) NOT NULL DEFAULT 500.00,
  `payout_frequency_days` int(11) NOT NULL DEFAULT 7,
  `auto_approve_sellers` tinyint(1) NOT NULL DEFAULT 0,
  `auto_approve_products` tinyint(1) NOT NULL DEFAULT 0,
  `seller_terms` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller_settings`
--

LOCK TABLES `seller_settings` WRITE;
/*!40000 ALTER TABLE `seller_settings` DISABLE KEYS */;
INSERT INTO `seller_settings` VALUES (1,10.00,500.00,7,0,0,NULL,'2026-01-08 14:38:40','2026-01-08 14:38:40');
/*!40000 ALTER TABLE `seller_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sellers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_slug` varchar(255) NOT NULL,
  `store_description` text DEFAULT NULL,
  `store_logo` varchar(255) DEFAULT NULL,
  `store_banner` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `business_email` varchar(255) NOT NULL,
  `business_phone` varchar(255) NOT NULL,
  `business_address` text DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_ifsc_code` varchar(255) DEFAULT NULL,
  `bank_account_holder` varchar(255) DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_earnings` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_withdrawn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','suspended','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `total_products` int(11) NOT NULL DEFAULT 0,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sellers_store_slug_unique` (`store_slug`),
  KEY `sellers_user_id_foreign` (`user_id`),
  CONSTRAINT `sellers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellers`
--

LOCK TABLES `sellers` WRITE;
/*!40000 ALTER TABLE `sellers` DISABLE KEYS */;
INSERT INTO `sellers` VALUES (1,5,'abhi','abhi','sfddfsdf',NULL,NULL,'sdfsdfsdf','abhishekchauhan.gms@gmail.com','3453534545','vvhgvhgv','3242342342',NULL,'dfvdfvdf','3424234242','3dvsxvfsdf','sdfsfcsdf',10.00,0.00,0.00,0.00,'approved',NULL,'2026-01-09 00:08:36',0,0,0,0.00,'2026-01-09 00:07:17','2026-01-09 00:08:36'),(2,8,'anion','anion','xcxcvxcv',NULL,NULL,'sdcdscw342423423','abhishek.codes2004@gmail.com','+91873678346','sfcfdskjdfhekrf','23423423423',NULL,'3234234','32234234','2342342','xcv vdfc df',10.00,0.00,0.00,0.00,'approved',NULL,'2026-01-11 12:52:57',0,1,0,0.00,'2026-01-11 12:49:32','2026-01-11 13:04:26');
/*!40000 ALTER TABLE `sellers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('HCMxBRmpBhIFCZJNsRGqVy3N58R5Ia2RoRv5gE5v',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoicXBQdlhjbko0NWJWNGlMejVWQ1lsNHJvZnAzaU41eHpheTBnS2wzSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZWFyY2gvc3VnZ2VzdGlvbnM/cT0iO3M6NToicm91dGUiO3M6MTg6InNlYXJjaC5zdWdnZXN0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',1769628728),('OheXeRGURYj31kt2XGUtRBguqpmHq2rjuviP9ZBq',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXM3S1RkWFhJOEFZcUhJeDZkUlRnbnRqeVMzbVNGa2xMaFhsNURSUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3Qvc2hvcGVhc2Uvc2VhcmNoL3N1Z2dlc3Rpb25zP3E9IjtzOjU6InJvdXRlIjtzOjE4OiJzZWFyY2guc3VnZ2VzdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1769629546),('WjtjqoixIlZNqJ2MFpocKZHDmLOlUTOGjZWu9DI5',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS1Z1eUNXbE5SMmw4TWw2c3VqUEI2bEhYNjU0cFFva09UUklNS0JjdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Qvc2hvcGVhc2UvYXV0aC9nb29nbGUiO3M6NToicm91dGUiO3M6MTE6ImF1dGguZ29vZ2xlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1OiJzdGF0ZSI7czo0MDoidXBseUIyaHhoYXlCUGxqYlU1dm1ScmhTTHlmUXZ4bjhBOWJyZHVvYyI7fQ==',1769629499);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_banners`
--

DROP TABLE IF EXISTS `shop_banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_banners`
--

LOCK TABLES `shop_banners` WRITE;
/*!40000 ALTER TABLE `shop_banners` DISABLE KEYS */;
INSERT INTO `shop_banners` VALUES (1,'New Season Arrivals','Discover the latest trends','https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600',1,1,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(2,'Summer Collection','Light & breezy styles','https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600',1,2,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(3,'Premium Quality','Crafted with care','https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600',1,3,'2026-01-04 12:50:55','2026-01-04 12:50:55');
/*!40000 ALTER TABLE `shop_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','ShopEase','text','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(2,'site_tagline','Shop Smart, Live Better','text','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(3,'site_description','Discover quality products at unbeatable prices.','textarea','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(4,'contact_email','abhichauhan200504@gmail.com','text','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(5,'contact_phone','+91 8279422813','text','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(6,'contact_address','Baral, Partapur, Meerut, UP, India','textarea','general',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-04 12:29:43'),(7,'instagram_url','https://instagram.com/shopease','text','footer',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-10 12:48:21'),(8,'facebook_url','https://facebook.com/shopease','text','footer',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-10 12:48:21'),(9,'twitter_url','https://twitter.com/shopease','text','footer',NULL,NULL,0,'2026-01-04 12:29:43','2026-01-10 12:48:21'),(10,'footer_email','abhichauhan200504@gmail.com','text','footer',NULL,NULL,0,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(11,'footer_phone','+91 82794 22813','text','footer',NULL,NULL,0,'2026-01-04 12:50:55','2026-01-10 12:48:21'),(12,'footer_address','Baral Partapur Meerut','text','footer',NULL,NULL,0,'2026-01-04 12:50:55','2026-01-10 12:48:21'),(13,'footer_copyright','© 2026 ShopEase. All rights reserved.','text','footer',NULL,NULL,0,'2026-01-04 12:50:55','2026-01-04 12:50:55'),(14,'live_chat_enabled','0','text','general',NULL,NULL,0,'2026-01-06 23:25:19','2026-01-06 23:28:52'),(15,'live_chat_provider','tawk','text','general',NULL,NULL,0,'2026-01-06 23:25:19','2026-01-06 23:25:19'),(16,'tawk_property_id','695de517aad9c019814f9a7e','text','general',NULL,NULL,0,'2026-01-06 23:25:19','2026-01-06 23:25:19'),(17,'tawk_widget_id','1jebcdpq6','text','general',NULL,NULL,0,'2026-01-06 23:25:19','2026-01-06 23:25:19'),(18,'crisp_website_id','','text','general',NULL,NULL,0,'2026-01-06 23:27:16','2026-01-06 23:27:16'),(19,'linkedin_url','https://www.linkedin.com/in/abhishek-chauhan-880496394','text','general',NULL,NULL,0,'2026-01-10 12:43:30','2026-01-10 12:43:30'),(20,'youtube_url','https://youtube.com/shopease','text','footer',NULL,NULL,0,'2026-01-10 12:48:21','2026-01-10 12:48:21');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `size_guides`
--

DROP TABLE IF EXISTS `size_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `size_guides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'clothing',
  `measurements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`measurements`)),
  `fit_tips` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `size_guides_category_id_foreign` (`category_id`),
  CONSTRAINT `size_guides_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `size_guides`
--

LOCK TABLES `size_guides` WRITE;
/*!40000 ALTER TABLE `size_guides` DISABLE KEYS */;
/*!40000 ALTER TABLE `size_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_notifications`
--

DROP TABLE IF EXISTS `stock_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_notifications_product_id_email_unique` (`product_id`,`email`),
  KEY `stock_notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_notifications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_notifications`
--

LOCK TABLES `stock_notifications` WRITE;
/*!40000 ALTER TABLE `stock_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_payments`
--

DROP TABLE IF EXISTS `subscription_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_subscription_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'INR',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_payments_user_subscription_id_foreign` (`user_subscription_id`),
  KEY `subscription_payments_user_id_foreign` (`user_id`),
  CONSTRAINT `subscription_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_payments_user_subscription_id_foreign` FOREIGN KEY (`user_subscription_id`) REFERENCES `user_subscriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_payments`
--

LOCK TABLES `subscription_payments` WRITE;
/*!40000 ALTER TABLE `subscription_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `description` text NOT NULL,
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `support_tickets_ticket_number_unique` (`ticket_number`),
  KEY `support_tickets_user_id_foreign` (`user_id`),
  KEY `support_tickets_order_id_foreign` (`order_id`),
  KEY `support_tickets_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,'Sarah Johnson','Founder & CEO','With over 15 years in the fashion industry, Sarah founded ShopEase with a vision to make quality accessible to everyone.','https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400','https://linkedin.com','https://twitter.com',1,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(2,'Michael Chen','Creative Director','Michael brings a unique blend of traditional craftsmanship and modern design sensibility to every collection.','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=400','https://linkedin.com',NULL,2,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(3,'Emily Rodriguez','Head of Operations','Emily ensures that every order is processed with care and delivered on time, maintaining our high standards.','https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=400','https://linkedin.com',NULL,3,1,'2026-01-10 04:18:58','2026-01-10 04:18:58'),(4,'David Kim','Quality Assurance Lead','David\'s meticulous attention to detail ensures that every product meets our exacting quality standards.','https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=400',NULL,NULL,4,1,'2026-01-10 04:18:58','2026-01-10 04:18:58');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_attachments`
--

DROP TABLE IF EXISTS `ticket_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned DEFAULT NULL,
  `reply_id` bigint(20) unsigned DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_attachments_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_attachments_reply_id_foreign` (`reply_id`),
  CONSTRAINT `ticket_attachments_reply_id_foreign` FOREIGN KEY (`reply_id`) REFERENCES `ticket_replies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_attachments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_attachments`
--

LOCK TABLES `ticket_attachments` WRITE;
/*!40000 ALTER TABLE `ticket_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_replies`
--

DROP TABLE IF EXISTS `ticket_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `is_staff_reply` tinyint(1) NOT NULL DEFAULT 0,
  `is_internal_note` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_replies_ticket_id_foreign` (`ticket_id`),
  KEY `ticket_replies_user_id_foreign` (`user_id`),
  CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_replies`
--

LOCK TABLES `ticket_replies` WRITE;
/*!40000 ALTER TABLE `ticket_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `membership_plan_id` bigint(20) unsigned NOT NULL,
  `status` enum('active','cancelled','expired','pending') NOT NULL DEFAULT 'pending',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `payment_method` varchar(255) DEFAULT NULL,
  `razorpay_subscription_id` varchar(255) DEFAULT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_subscriptions_membership_plan_id_foreign` (`membership_plan_id`),
  KEY `user_subscriptions_user_id_status_index` (`user_id`,`status`),
  KEY `user_subscriptions_ends_at_index` (`ends_at`),
  CONSTRAINT `user_subscriptions_membership_plan_id_foreign` FOREIGN KEY (`membership_plan_id`) REFERENCES `membership_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `referral_code` varchar(10) DEFAULT NULL,
  `is_member` tinyint(1) NOT NULL DEFAULT 0,
  `membership_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `login_otp` varchar(6) DEFAULT NULL,
  `login_otp_expires_at` timestamp NULL DEFAULT NULL,
  `hide_membership_popup` tinyint(1) NOT NULL DEFAULT 0,
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `social_avatar` varchar(255) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `sms_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `marketing_emails` tinyint(1) NOT NULL DEFAULT 0,
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referred_by` bigint(20) unsigned DEFAULT NULL,
  `reward_points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_earned_points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_redeemed_points` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_referred_by_foreign` (`referred_by`),
  CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@shopease.com',NULL,'avatars/XhAKZf70ZcxEVuxm9t0Cs0ghVwbg1FcQDHOYR2S0.png','2004-05-20','male',NULL,'$2y$12$jjk3TB4lvxYcYz0iN3zAKOR6iktdfEhy8MlzRp8UzHb9ElRK3ci4.','admin',NULL,0,NULL,'oh2M4vqzE6gBnvdJICLUODIY3NfIWlR3uidrjudj0IjWAM7U9WN4sKRgGCzH',NULL,NULL,0,NULL,NULL,NULL,'2026-01-18 11:31:25',1,0,0,0,'2026-01-02 12:24:19','2026-01-18 11:31:25',NULL,0.00,0.00,0.00),(2,'John Doe','user@shopease.com',NULL,NULL,NULL,NULL,NULL,'$2y$12$1eJiDHJ4oCkFqTO6MzWwiuZOlHPFvGqOeQAP8A0FrfCnirwzVOzo.','user',NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,1,0,0,0,'2026-01-02 12:24:19','2026-01-02 12:24:19',NULL,0.00,0.00,0.00),(3,'abhi','abhi@abhi.com',NULL,'avatars/GyhbfzFdN0u6Ec7pwsWTJZToBPl83PELZzL2J3r9.png',NULL,NULL,NULL,'$2y$12$FvM7H0adz32hJ672N2dO3uL7dMIQ5dIMopl0WLDw85v44IhBgkMiC','user',NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,1,0,0,0,'2026-01-03 10:28:58','2026-01-03 12:25:52',NULL,0.00,0.00,0.00),(4,'Abhishek Chauhan','abhichauhan200504@gmail.com',NULL,NULL,NULL,NULL,'2026-01-03 14:19:38','$2y$12$YFlqX5ycrQgbJLQkf.suNem1ioLQ/zjEZrWrg08djruRFjhXTdb4u','user',NULL,0,NULL,'iHhLWsvzOk6tNvqUVdhpjaaAbyx4PRKm0THXATd2mZg6q3hsUqZp7GEi8djo',NULL,NULL,0,'108078090028086076182',NULL,'https://lh3.googleusercontent.com/a/ACg8ocKLAapFqLCgLUnYBV1o3v34UYmzWyFYCMnnnOrmjkQ9mQwLa3JqPQ=s96-c','2026-01-28 14:01:45',1,0,0,0,'2026-01-03 14:19:38','2026-01-28 14:01:45',NULL,0.00,0.00,0.00),(5,'Abhishek Chauhan','abhishekchauhan.gms@gmail.com',NULL,NULL,NULL,NULL,'2026-01-03 14:50:12','$2y$12$VX7u0WqYmnJhkHR7/sIpseeGmkgHfp69u02ADfVX6lxXrtnITwnKe','seller','VL6EF92W',0,NULL,'XzgP35dCFfWwA1khBlDeozX2b0VibL2CkPfZFCrbZPUDi3iipWjz9NCslbmQ',NULL,NULL,0,'105416986754366882808',NULL,'https://lh3.googleusercontent.com/a/ACg8ocITBGSh9z_4Dr3t2M6dyEIJzLlR66hrvaatWgsMUi7GpEXxsFI=s96-c','2026-01-28 10:49:30',1,0,0,0,'2026-01-03 14:50:12','2026-01-28 10:49:30',NULL,0.00,0.00,0.00),(6,'Abhi Chauhan','cashem1001@gmail.com',NULL,NULL,NULL,NULL,'2026-01-04 14:54:50','$2y$12$GCu7dtRv2FexRyJ4mal7v.VpEhxS9dszfoE36M6dqRXcne6SQKnQO','user',NULL,0,NULL,'z5JOJ53HX6jm3qBzTSUEQC4ErNe1rkBGJPbfceY7iXG9jA4iIvaXSm9BG4fI',NULL,NULL,0,'107407620380742441136',NULL,'https://lh3.googleusercontent.com/a/ACg8ocJjc4Ppd4Qc7MKG4yeLpoJ0uyKOC6mdiSt2e8vDFapVK-uzfA=s96-c','2026-01-04 14:54:55',1,0,0,0,'2026-01-04 14:54:50','2026-01-04 14:54:55',NULL,0.00,0.00,0.00),(7,'Leslie Bennett','babitachauhan161@gmail.com',NULL,NULL,NULL,NULL,'2026-01-04 23:00:10','$2y$12$Y03bqmbYXWn3xD.Sm1z4cuJLdG55eioUjpHxS0GaiclIieqMjL.za','user',NULL,0,NULL,'geSMIltMJjozghDXtMQoyNwauz9l4u52FouCArF3bR76sLvt13eEcdCa3jGp',NULL,NULL,0,'101604247327151637846',NULL,'https://lh3.googleusercontent.com/a/ACg8ocI-9CrCud9z9UNLClK4CWyTnXyxyd9Gp7wpXAxnU17oz4lAPDD3=s96-c','2026-01-04 23:10:15',1,0,0,0,'2026-01-04 23:00:10','2026-01-04 23:10:15',NULL,0.00,0.00,0.00),(8,'Abhishek Chauhan','abhishek.codes2004@gmail.com',NULL,NULL,NULL,NULL,'2026-01-07 04:40:24','$2y$12$vLJPB8ZrK0taPKEO4b4ST.JhTArzBPvcPAQLgpRstsiHy6Up1q.0K','seller',NULL,0,NULL,'hUCVmrMi22IbdgplDkvl65kjKseGoE40OiWkNZ4I0I2UZv0xcVz6nZ2Plovb',NULL,NULL,0,'118430394450708809369',NULL,'https://lh3.googleusercontent.com/a/ACg8ocI4BLDYkeVmN4GciJJ1P8zBn8KBk1_mvHgH0riojt_Pk-CghQ=s96-c','2026-01-07 04:40:31',1,0,0,0,'2026-01-07 04:40:24','2026-01-11 12:49:32',NULL,500.00,500.00,0.00),(9,'abhi','abhi@admin.com',NULL,NULL,NULL,NULL,NULL,'$2y$12$E33yu61DnlBprXYshXos7.fAOQCeggCZW/PtpPd21AQvti9sJ9jKS','user','KUPRV1BD',0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-01-08 14:39:46',1,0,0,0,'2026-01-08 14:28:57','2026-01-08 14:39:46',NULL,0.00,0.00,0.00);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_shares`
--

DROP TABLE IF EXISTS `wishlist_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist_shares` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `share_token` varchar(64) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_shares_share_token_unique` (`share_token`),
  KEY `wishlist_shares_user_id_foreign` (`user_id`),
  CONSTRAINT `wishlist_shares_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_shares`
--

LOCK TABLES `wishlist_shares` WRITE;
/*!40000 ALTER TABLE `wishlist_shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist_shares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
INSERT INTO `wishlists` VALUES (1,3,87,'2026-01-03 13:28:58','2026-01-03 13:28:58'),(2,7,52,'2026-01-04 23:24:28','2026-01-04 23:24:28'),(3,7,69,'2026-01-04 23:24:42','2026-01-04 23:24:42'),(4,5,29,'2026-01-10 02:41:02','2026-01-10 02:41:02');
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-29  1:23:54
