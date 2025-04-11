INSERT INTO `settings` (`id`, `type`, `value`,  `created_at`, `updated_at`) VALUES (NULL, 'myfatoorah_sandbox', 1,  current_timestamp(), current_timestamp());


UPDATE `settings` SET `value` = '3.2' WHERE `type` = 'current_version';

COMMIT;
