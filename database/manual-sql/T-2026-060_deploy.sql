-- =====================================================================
-- T-2026-060 — Stock Daily Report ledger reconciliation
-- MANUAL SCHEMA DEPLOYMENT (no terminal / no `php artisan migrate`)
-- =====================================================================
--
-- Equivalent of these two migrations:
--   database/migrations/2026_08_06_100000_add_movement_tracking_columns_to_tbl_item_stock_history_table.php
--   database/migrations/2026_08_06_100100_add_issued_at_to_production_details_table.php
--
-- HOW TO RUN
--   phpMyAdmin -> select the application database -> "SQL" tab
--   -> paste this whole file -> Go.
--
-- RUN THIS **BEFORE** UPLOADING THE NEW PHP FILES.
--   The updated InventoryRepository / ItemRepository / StoreRepository write to
--   the new columns. If the PHP goes live first, "Add Stock", "Edit Stock",
--   creating a part item, and issuing material to production will all throw
--   "Unknown column" errors until this script has run.
--
-- SAFE TO RE-RUN
--   Every statement checks information_schema first, so running this twice
--   does nothing the second time. Purely additive: no column is dropped,
--   renamed or retyped, and no existing row is modified.
--
-- Works on MySQL 5.7 / 8.x and MariaDB 10.x.
-- =====================================================================

SET @dbname = DATABASE();


-- ---------------------------------------------------------------------
-- 1. tbl_item_stock_history — movement tracking columns
-- ---------------------------------------------------------------------

-- 1a. movement_type
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'movement_type') > 0,
    'SELECT ''SKIP: tbl_item_stock_history.movement_type already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD `movement_type` VARCHAR(50) NOT NULL DEFAULT ''legacy_unspecified'' COMMENT ''manual_addition|manual_adjustment_set|item_creation_opening_stock|opening_reconciliation|legacy_unspecified'' AFTER `part_item_id`'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1b. quantity_delta
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'quantity_delta') > 0,
    'SELECT ''SKIP: tbl_item_stock_history.quantity_delta already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD `quantity_delta` DECIMAL(15,3) NULL COMMENT ''Signed delta applied to tbl_item_stock.quantity by this event. NULL for legacy rows.'' AFTER `quantity`'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1c. balance_after
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'balance_after') > 0,
    'SELECT ''SKIP: tbl_item_stock_history.balance_after already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD `balance_after` DECIMAL(15,3) NULL COMMENT ''tbl_item_stock.quantity immediately after this event. NULL for legacy rows.'' AFTER `quantity_delta`'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1d. remark
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'remark') > 0,
    'SELECT ''SKIP: tbl_item_stock_history.remark already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD `remark` VARCHAR(500) NULL AFTER `balance_after`'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1e. index (part_item_id, created_at)
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND INDEX_NAME   = 'idx_item_stock_history_part_created') > 0,
    'SELECT ''SKIP: idx_item_stock_history_part_created already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_part_created` (`part_item_id`, `created_at`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 1f. index (movement_type)
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND INDEX_NAME   = 'idx_item_stock_history_movement_type') > 0,
    'SELECT ''SKIP: idx_item_stock_history_movement_type already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_movement_type` (`movement_type`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ---------------------------------------------------------------------
-- 2. production_details — issued_at
-- ---------------------------------------------------------------------

-- 2a. issued_at
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'production_details'
        AND COLUMN_NAME  = 'issued_at') > 0,
    'SELECT ''SKIP: production_details.issued_at already exists'' AS result',
    'ALTER TABLE `production_details` ADD `issued_at` TIMESTAMP NULL AFTER `quantity_minus_status`'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2b. index (issued_at)
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'production_details'
        AND INDEX_NAME   = 'idx_production_details_issued_at') > 0,
    'SELECT ''SKIP: idx_production_details_issued_at already exists'' AS result',
    'ALTER TABLE `production_details` ADD INDEX `idx_production_details_issued_at` (`issued_at`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2c. index (quantity_minus_status, material_send_production)
SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'production_details'
        AND INDEX_NAME   = 'idx_production_details_issue_predicate') > 0,
    'SELECT ''SKIP: idx_production_details_issue_predicate already exists'' AS result',
    'ALTER TABLE `production_details` ADD INDEX `idx_production_details_issue_predicate` (`quantity_minus_status`, `material_send_production`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ---------------------------------------------------------------------
-- 3. Record both migrations as applied
--    So that if `php artisan migrate` is ever run later (e.g. on a copy of
--    this DB, or if terminal access appears), it does not try to re-add
--    columns that already exist and fail.
-- ---------------------------------------------------------------------

SET @batch = (SELECT IFNULL(MAX(batch), 0) + 1 FROM (SELECT batch FROM `migrations`) AS b);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_06_100000_add_movement_tracking_columns_to_tbl_item_stock_history_table', @batch
WHERE NOT EXISTS (
    SELECT 1 FROM (SELECT `migration` FROM `migrations`) AS m
    WHERE m.`migration` = '2026_08_06_100000_add_movement_tracking_columns_to_tbl_item_stock_history_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_06_100100_add_issued_at_to_production_details_table', @batch
WHERE NOT EXISTS (
    SELECT 1 FROM (SELECT `migration` FROM `migrations`) AS m
    WHERE m.`migration` = '2026_08_06_100100_add_issued_at_to_production_details_table'
);


-- ---------------------------------------------------------------------
-- 4. VERIFY — all five rows below must show status = OK
-- ---------------------------------------------------------------------

SELECT 'tbl_item_stock_history.movement_type'  AS object,
       IF(COUNT(*) > 0, 'OK', 'MISSING')       AS status
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_item_stock_history' AND COLUMN_NAME = 'movement_type'
UNION ALL
SELECT 'tbl_item_stock_history.quantity_delta',
       IF(COUNT(*) > 0, 'OK', 'MISSING')
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_item_stock_history' AND COLUMN_NAME = 'quantity_delta'
UNION ALL
SELECT 'tbl_item_stock_history.balance_after',
       IF(COUNT(*) > 0, 'OK', 'MISSING')
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_item_stock_history' AND COLUMN_NAME = 'balance_after'
UNION ALL
SELECT 'tbl_item_stock_history.remark',
       IF(COUNT(*) > 0, 'OK', 'MISSING')
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_item_stock_history' AND COLUMN_NAME = 'remark'
UNION ALL
SELECT 'production_details.issued_at',
       IF(COUNT(*) > 0, 'OK', 'MISSING')
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'production_details' AND COLUMN_NAME = 'issued_at';


-- =====================================================================
-- NEXT STEP (after uploading the new PHP files)
--
-- The one-time stock reconciliation still has to be run. It is NOT plain
-- SQL — it recomputes each part's full 6-leg ledger in PHP. Log in as
-- Owner and open:
--
--   https://www.shreeragengineering.com/stock-reconcile              <- preview only, writes nothing
--   https://www.shreeragengineering.com/stock-reconcile?apply=1      <- actually writes the adjustments
--
-- Always open the preview first and check the reported figures.
-- =====================================================================
