INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'delivery_boy', '1', current_timestamp(), current_timestamp(), NULL);

-- permissions 
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'view_all_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'add_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'delivery_boy_payment_histories', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'delivery_boy_cancel_request_list', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'delivery_boy_configuration', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'delivery_boy_collection_histories', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'edit_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'ban_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'collect_from_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'pay_to_delivery_boy', 'delivery_boy', 'web', '2023-06-22 05:02:50', '2023-06-22 05:02:50');

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_collection` double(25,2) NOT NULL DEFAULT 0.00,
  `total_earning` double(25,2) NOT NULL DEFAULT 0.00,
  `monthly_salary` double(25,2) DEFAULT NULL,
  `order_commission` double(25,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- delivery histories table 


CREATE TABLE `delivery_histories` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_status` varchar(255) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `earning` double(25,2) NOT NULL DEFAULT 0.00,
  `collection` double(25,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `delivery_histories`
--
ALTER TABLE `delivery_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Table structure for table `delivery_boy_payments`
--

CREATE TABLE `delivery_boy_payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment` double(25,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery_boy_payments`
--
ALTER TABLE `delivery_boy_payments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivery_boy_payments`
--
ALTER TABLE `delivery_boy_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `delivery_boy_collections`
--

CREATE TABLE `delivery_boy_collections` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `collection_amount` double(25,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery_boy_collections`
--
ALTER TABLE `delivery_boy_collections`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivery_boy_collections`
--
ALTER TABLE `delivery_boy_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users` CHANGE `user_type` `user_type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'customer'; 
ALTER TABLE `orders` ADD `assign_delivery_boy` INT(11) NULL AFTER `combined_order_id`;
ALTER TABLE `orders` ADD `delivery_history_date` TIMESTAMP NULL AFTER `tracking_url`;
ALTER TABLE `orders` ADD `cancel_request` TINYINT NOT NULL DEFAULT '0' AFTER `tracking_url`;
ALTER TABLE `orders` ADD `cancel_request_at` DATETIME NULL AFTER `cancel_request`;


INSERT INTO `settings` (`id`, `type`, `value`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, 'support_chat', '1', current_timestamp(), current_timestamp(), NULL);

UPDATE `settings` SET `value` = '3.0' WHERE `type` = 'current_version';

COMMIT;

