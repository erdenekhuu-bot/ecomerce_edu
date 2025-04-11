ALTER TABLE `orders` ADD `type_of_delivery` VARCHAR(50) NULL AFTER `delivery_type`;
ALTER TABLE `orders` ADD `pickup_point_id` INT NULL AFTER `type_of_delivery`;

ALTER TABLE `products` ADD `for_pickup` BOOLEAN NOT NULL DEFAULT FALSE AFTER `has_warranty`;


--
-- Table structure for table `pickup_points`
--

CREATE TABLE `pickup_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `location` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pickup_points`
--
ALTER TABLE `pickup_points`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pickup_points`
--
ALTER TABLE `pickup_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


-- pickup policy page

INSERT INTO `pages` (`id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'pickup_policy', 'Pickup Policy', 'pickup-policy', 'LOCAL PICKUP GUIDE & POLICY\r\nLocal Pickup policies\r\n\r\nWhen placing your order in our online shop, you can select Warehouse Pickup if you would like to pick up your order in person free of charge.\r\n\r\nYour order will be ready for pickup within 3 hours during our pickup hours of 10am to 4pm, Monday to Friday. So fast! At the moment, we cannot accommodate pick up outside of the stated hours. \r\n\r\nPlease wait for the notification telling you your order is ready before coming to the warehouse! Our little team will happily prepare your order in just a few hours and will let you know as soon as you can pick it up.\r\n\r\nOnce you get your confirmation, your order will be waiting for you in a paper shopping bag with your name and order number on it, at the door of our warehouse.\r\n\r\nCan not make it at the chosen time? Email us\r\n\r\nOrders will be available for you to pick up for 14 days. We will send you a reminder email during this time, we know you are busy! After 14 days, we will cancel your order and refund your payment, minus a 10% restocking and cancelation fee to cover our prep time and payment processing fees.\r\n\r\nPlease note that pickup for orders placed online is at our warehouse not a shop. Our warehouse sadly no longer has the extra space or staff necessary for a showroom, and so you will not be able to browse, smell things, see products or shop in person at this location. Please visit one of our lovely local retailers if you want to shop and browse in person before purchasing!\r\n\r\nOur warehouse team cannot accept payments and cannot prepare orders for you on the spot.', 'pickup-policy', NULL, NULL, NULL, '2020-11-04 11:14:41', '2024-05-19 09:52:52', NULL);


UPDATE `settings` SET `value` = '3.3' WHERE `type` = 'current_version';
COMMIT;