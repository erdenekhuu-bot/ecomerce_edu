
ALTER TABLE `product_variations` ADD `current_stock` INT NULL AFTER `stock`;

UPDATE `settings` SET `value` = '3.6' WHERE `type` = 'current_version';
COMMIT;
