
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'affiliate_configuration', 'affiliate', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'show_affiliate_users', 'affiliate', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'show_referral_users', 'affiliate', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'affiliate_withdraw_request', 'affiliate', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'affiliate_log', 'affiliate', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');

INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'club_point_configuration', 'club_point', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');
INSERT INTO `permissions` (`id`, `name`, `parent`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL, 'show_user_points', 'club_point', 'web', '2022-06-22 05:02:50', '2022-06-22 05:02:50');


UPDATE `settings` SET `value` = '2.9' WHERE `type` = 'current_version';

COMMIT;
