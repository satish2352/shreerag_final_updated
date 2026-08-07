-- =====================================================================
-- T-2026-060 — SCHEMA ALIGNMENT (follow-up)
-- =====================================================================
--
-- The new columns were added to the live database by hand rather than by
-- T-2026-060_deploy.sql, so three things differ from what the migration
-- defines. This script closes those gaps.
--
--   1. All FOUR indexes are missing.  <-- the one that actually matters
--      The Stock Daily Report filters/sorts on exactly these columns.
--      Without them every report load does full table scans.
--
--   2. `movement_type` is `varchar(50) NULL DEFAULT NULL`
--      but should be `NOT NULL DEFAULT 'legacy_unspecified'`.
--
--   3. `movement_type` / `quantity_delta` / `balance_after` have no column
--      comments (documentation only — no behavioural effect).
--
-- HOW TO RUN
--   phpMyAdmin -> select the live database -> "SQL" tab -> paste -> Go.
--
-- SAFE
--   * Checks information_schema before every change, so re-running is a
--     no-op. Safe to run even if part of it was already applied.
--   * Both tables are small (tbl_item_stock_history 239 rows,
--     production_details 173), so every ALTER is effectively instant.
--   * Step 2 rewrites existing NULL `movement_type` values to
--     'legacy_unspecified'. This does NOT change what the report shows:
--     the report's manual-movement leg only includes
--       movement_type IN ('manual_addition','manual_adjustment_set',
--                         'opening_reconciliation')
--       AND quantity_delta IS NOT NULL
--     'legacy_unspecified' is excluded by that filter exactly as NULL was.
--     It only makes the legacy rows explicitly labelled instead of blank.
--
-- ORDER
--   Run this BEFORE or AFTER /stock-reconcile — either is fine. Rows written
--   by the reconciliation already carry movement_type='opening_reconciliation'
--   and are untouched by step 2.
--
-- Works on MySQL 5.7 / 8.x and MariaDB 10.x.
-- =====================================================================

SET @dbname = DATABASE();


-- ---------------------------------------------------------------------
-- 1. Missing indexes  (the performance-relevant part)
-- ---------------------------------------------------------------------

SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND INDEX_NAME   = 'idx_item_stock_history_part_created') > 0,
    'SELECT ''SKIP: idx_item_stock_history_part_created already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_part_created` (`part_item_id`, `created_at`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND INDEX_NAME   = 'idx_item_stock_history_movement_type') > 0,
    'SELECT ''SKIP: idx_item_stock_history_movement_type already exists'' AS result',
    'ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_movement_type` (`movement_type`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'production_details'
        AND INDEX_NAME   = 'idx_production_details_issued_at') > 0,
    'SELECT ''SKIP: idx_production_details_issued_at already exists'' AS result',
    'ALTER TABLE `production_details` ADD INDEX `idx_production_details_issued_at` (`issued_at`)'
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

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
-- 2. Label the legacy rows, then make movement_type NOT NULL
--    (backfill MUST run first, or the MODIFY fails on the NULL rows)
-- ---------------------------------------------------------------------

UPDATE `tbl_item_stock_history`
   SET `movement_type` = 'legacy_unspecified'
 WHERE `movement_type` IS NULL
    OR `movement_type` = '';

SET @s = (SELECT IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'movement_type'
        AND IS_NULLABLE  = 'NO') > 0,
    'SELECT ''SKIP: movement_type is already NOT NULL'' AS result',
    'ALTER TABLE `tbl_item_stock_history` MODIFY `movement_type` VARCHAR(50) NOT NULL DEFAULT ''legacy_unspecified'' COMMENT ''manual_addition|manual_adjustment_set|item_creation_opening_stock|opening_reconciliation|legacy_unspecified'''
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ---------------------------------------------------------------------
-- 3. Column comments (documentation only)
-- ---------------------------------------------------------------------

SET @s = (SELECT IF(
    (SELECT COLUMN_COMMENT FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'quantity_delta') <> '',
    'SELECT ''SKIP: quantity_delta comment already set'' AS result',
    'ALTER TABLE `tbl_item_stock_history` MODIFY `quantity_delta` DECIMAL(15,3) NULL COMMENT ''Signed delta applied to tbl_item_stock.quantity by this event. NULL for legacy rows.'''
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = (SELECT IF(
    (SELECT COLUMN_COMMENT FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = @dbname
        AND TABLE_NAME   = 'tbl_item_stock_history'
        AND COLUMN_NAME  = 'balance_after') <> '',
    'SELECT ''SKIP: balance_after comment already set'' AS result',
    'ALTER TABLE `tbl_item_stock_history` MODIFY `balance_after` DECIMAL(15,3) NULL COMMENT ''tbl_item_stock.quantity immediately after this event. NULL for legacy rows.'''
));
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ---------------------------------------------------------------------
-- 4. VERIFY — every row below must show OK
-- ---------------------------------------------------------------------

SELECT 'idx_item_stock_history_part_created'    AS object, IF(COUNT(DISTINCT INDEX_NAME) > 0,'OK','MISSING') AS status
  FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'idx_item_stock_history_part_created'
UNION ALL
SELECT 'idx_item_stock_history_movement_type',  IF(COUNT(DISTINCT INDEX_NAME) > 0,'OK','MISSING')
  FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'idx_item_stock_history_movement_type'
UNION ALL
SELECT 'idx_production_details_issued_at',      IF(COUNT(DISTINCT INDEX_NAME) > 0,'OK','MISSING')
  FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'idx_production_details_issued_at'
UNION ALL
SELECT 'idx_production_details_issue_predicate',IF(COUNT(DISTINCT INDEX_NAME) > 0,'OK','MISSING')
  FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = DATABASE() AND INDEX_NAME = 'idx_production_details_issue_predicate'
UNION ALL
SELECT 'movement_type NOT NULL',                IF(COUNT(*) > 0,'OK','STILL NULLABLE')
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_item_stock_history'
   AND COLUMN_NAME = 'movement_type' AND IS_NULLABLE = 'NO'
UNION ALL
SELECT 'no NULL/blank movement_type left',      IF(COUNT(*) = 0,'OK',CONCAT(COUNT(*),' REMAIN'))
  FROM `tbl_item_stock_history`
 WHERE `movement_type` IS NULL OR `movement_type` = '';
