CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

ALTER TABLE `reviews` ADD `image` INT NULL AFTER `comment`;

UPDATE `settings` SET `value` = '[\"Home\",\"All Brands\",\"All Blogs\",\"Offers\"] ', `deleted_at` = NULL WHERE `settings`.`type` = 'header_menu_labels';

UPDATE `settings` SET `value` = '[\"\\/\",\"\\/all-brands\",\"\\/all-blogs\",\"\\/all-offers\"] ', `deleted_at` = NULL WHERE `settings`.`type` = 'header_menu_links';

UPDATE `settings` SET `value` = '2.8' WHERE `type` = 'current_version';

COMMIT;
