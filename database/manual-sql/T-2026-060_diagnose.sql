-- =====================================================================
-- T-2026-060 — DIAGNOSTIC  (READ ONLY — changes nothing)
-- =====================================================================
-- Paste into phpMyAdmin with the LIVE database selected.
-- Every statement is a SELECT. It writes nothing, locks nothing.
-- Send me the output and I can tell you exactly what state the server is in.
-- =====================================================================

-- 1. Which database am I actually looking at?
SELECT DATABASE() AS `Connected database`, VERSION() AS `Server version`;

-- 2. Do the new columns exist?  (expect 5 rows)
SELECT TABLE_NAME AS `Table`, COLUMN_NAME AS `Column`,
       COLUMN_TYPE AS `Type`, IS_NULLABLE AS `Nullable`,
       IFNULL(COLUMN_DEFAULT,'NULL') AS `Default`
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE()
   AND COLUMN_NAME IN ('movement_type','quantity_delta','balance_after','remark','issued_at')
 ORDER BY TABLE_NAME, COLUMN_NAME;

-- 3. Do the indexes exist?  (expect 4 rows)
SELECT DISTINCT TABLE_NAME AS `Table`, INDEX_NAME AS `Index`
  FROM information_schema.STATISTICS
 WHERE TABLE_SCHEMA = DATABASE()
   AND INDEX_NAME IN ('idx_item_stock_history_part_created',
                      'idx_item_stock_history_movement_type',
                      'idx_production_details_issued_at',
                      'idx_production_details_issue_predicate');

-- 4. What is actually in the history table, by type?
SELECT IFNULL(movement_type,'(NULL)') AS `movement_type`,
       COUNT(*)                       AS `rows`,
       SUM(quantity_delta IS NULL)    AS `rows with NULL quantity_delta`
  FROM tbl_item_stock_history
 WHERE is_deleted = 0
 GROUP BY movement_type
 ORDER BY `rows` DESC;

-- 5. Has the reconciliation been applied?  (0 = not run yet)
SELECT COUNT(*)                      AS `opening_reconciliation rows`,
       ROUND(SUM(quantity_delta),3)  AS `total adjusted`
  FROM tbl_item_stock_history
 WHERE movement_type = 'opening_reconciliation' AND is_deleted = 0;

-- 6. The CO2 welding wire part, end to end
SELECT p.id                                   AS `Part ID`,
       p.description                          AS `Description`,
       IFNULL(p.opening_stock,0)              AS `Opening stock`,
       s.quantity                             AS `Inventory screen qty`,
       (SELECT IFNULL(SUM(quantity),0) FROM production_details
         WHERE part_item_id = p.id AND is_deleted = 0 AND is_active = 1
           AND quantity_minus_status = 'done' AND material_send_production = 1)
                                              AS `Issued to production`,
       (SELECT IFNULL(SUM(quantity_delta),0) FROM tbl_item_stock_history
         WHERE part_item_id = p.id AND is_deleted = 0 AND is_active = 1
           AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
           AND quantity_delta IS NOT NULL)
                                              AS `Manual movements counted`
  FROM tbl_part_item p
  LEFT JOIN tbl_item_stock s ON s.part_item_id = p.id
 WHERE p.description LIKE '%CO2 WELDING WIRE LAYER%0.80%'
   AND p.description NOT LIKE '%SS%';

-- 7. Every history row for that part
SELECT h.id, h.part_item_id,
       IFNULL(h.movement_type,'(NULL)') AS movement_type,
       h.quantity,
       IFNULL(h.quantity_delta,'(NULL)') AS quantity_delta,
       IFNULL(h.balance_after,'(NULL)')  AS balance_after,
       h.is_active, h.is_deleted, h.created_at
  FROM tbl_item_stock_history h
  JOIN tbl_part_item p ON p.id = h.part_item_id
 WHERE p.description LIKE '%CO2 WELDING WIRE LAYER%0.80%'
   AND p.description NOT LIKE '%SS%'
 ORDER BY h.id;

-- 8. How many parts are still out of sync overall?
SELECT COUNT(*)                                                    AS `Parts checked`,
       SUM(CASE WHEN ABS(actual-ledger) <  0.001 THEN 1 ELSE 0 END) AS `In sync`,
       SUM(CASE WHEN ABS(actual-ledger) >= 0.001 THEN 1 ELSE 0 END) AS `Still out of sync`,
       SUM(CASE WHEN ledger < 0 THEN 1 ELSE 0 END)                  AS `Negative balance`
  FROM (
    SELECT p.id, s.quantity AS actual,
           COALESCE(p.opening_stock,0) + COALESCE(grn.v,0) - COALESCE(prod.v,0)
         - COALESCE(del.v,0) - COALESCE(ret.v,0) + COALESCE(man.v,0) AS ledger
      FROM tbl_part_item p
      JOIN tbl_item_stock s ON s.part_item_id = p.id AND s.quantity IS NOT NULL
      LEFT JOIN (SELECT g.part_no_id pid, SUM(IFNULL(g.accepted_quantity,0)) v
                   FROM tbl_grn_po_quantity_tracking g JOIN grn_tbl gt ON gt.id = g.grn_id
                  WHERE g.is_deleted = 0 AND g.is_active = 1 GROUP BY g.part_no_id) grn ON grn.pid = p.id
      LEFT JOIN (SELECT part_item_id pid, SUM(quantity) v FROM production_details
                  WHERE is_deleted = 0 AND is_active = 1 AND quantity_minus_status = 'done'
                    AND material_send_production = 1 GROUP BY part_item_id) prod ON prod.pid = p.id
      LEFT JOIN (SELECT part_item_id pid, SUM(quantity) v FROM tbl_delivery_chalan_item_details
                  WHERE is_deleted = 0 AND is_active = 1 GROUP BY part_item_id) del ON del.pid = p.id
      LEFT JOIN (SELECT part_item_id pid, SUM(quantity) v FROM tbl_returnable_chalan_item_details
                  WHERE is_deleted = 0 AND is_active = 1 GROUP BY part_item_id) ret ON ret.pid = p.id
      LEFT JOIN (SELECT part_item_id pid, SUM(quantity_delta) v FROM tbl_item_stock_history
                  WHERE is_active = 1 AND is_deleted = 0
                    AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
                    AND quantity_delta IS NOT NULL GROUP BY part_item_id) man ON man.pid = p.id
     WHERE p.is_active = 1 AND p.is_deleted = 0
  ) z;
