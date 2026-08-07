-- =====================================================================
--  RUN THIS ON SERVER  —  T-2026-060 Stock Daily Report fix
-- =====================================================================
--
--  Plain SQL only. No information_schema, no PREPARE/EXECUTE, no
--  variables — your host's phpMyAdmin rejects those (#1109).
--
--  RUN PART A ONLY.  Parts B and C are optional extras.
--
--  HOW TO RUN
--      phpMyAdmin -> click your live database in the left panel
--      -> "SQL" tab -> paste PART A -> Go
-- =====================================================================


-- #####################################################################
-- ##  PART A — THE ACTUAL FIX.  This is the part that matters.       ##
-- ##  Copy from here down to the end of PART A and run it.           ##
-- #####################################################################

-- A1. Seed one reconciliation entry per out-of-sync part item.
--     Computes the gap live, so it is correct whenever you run it.
--     Parts already reconciled are skipped -> safe to run twice.
--     NEVER changes tbl_item_stock. Your stock quantities are untouched.

INSERT INTO tbl_item_stock_history
    (part_item_id, movement_type, quantity, quantity_delta, balance_after,
     remark, is_active, is_deleted, created_at, updated_at)
SELECT
    x.part_id,
    'opening_reconciliation',
    ROUND(x.actual - x.ledger, 3),
    ROUND(x.actual - x.ledger, 3),
    ROUND(x.actual, 3),
    'Stock Adjustment (opening reconciliation) - historical manual stock movements that predate the fix could not be reconstructed. Seeded by RUN_THIS_ON_SERVER.sql.',
    1, 0,
    DATE_SUB(x.earliest, INTERVAL 1 SECOND),
    NOW()
FROM (
    SELECT
        p.id       AS part_id,
        s.quantity AS actual,
        COALESCE(p.opening_stock,0) + COALESCE(grn.v,0) - COALESCE(prod.v,0)
      - COALESCE(del.v,0) - COALESCE(ret.v,0) + COALESCE(man.v,0) AS ledger,
        CASE WHEN LEAST(COALESCE(p.created_at,'2099-12-31 00:00:00'),
                        COALESCE(grn.mn,'2099-12-31 00:00:00'),
                        COALESCE(prod.mn,'2099-12-31 00:00:00'),
                        COALESCE(del.mn,'2099-12-31 00:00:00'),
                        COALESCE(ret.mn,'2099-12-31 00:00:00'),
                        COALESCE(man.mn,'2099-12-31 00:00:00')) = '2099-12-31 00:00:00'
             THEN NOW()
             ELSE LEAST(COALESCE(p.created_at,'2099-12-31 00:00:00'),
                        COALESCE(grn.mn,'2099-12-31 00:00:00'),
                        COALESCE(prod.mn,'2099-12-31 00:00:00'),
                        COALESCE(del.mn,'2099-12-31 00:00:00'),
                        COALESCE(ret.mn,'2099-12-31 00:00:00'),
                        COALESCE(man.mn,'2099-12-31 00:00:00'))
        END AS earliest
    FROM tbl_part_item p
    JOIN tbl_item_stock s ON s.part_item_id = p.id AND s.quantity IS NOT NULL
    LEFT JOIN (SELECT g.part_no_id pid, SUM(IFNULL(g.accepted_quantity,0)) v,
                      MIN(COALESCE(gt.grn_date,g.updated_at)) mn
                 FROM tbl_grn_po_quantity_tracking g JOIN grn_tbl gt ON gt.id=g.grn_id
                WHERE g.is_deleted=0 AND g.is_active=1 GROUP BY g.part_no_id) grn ON grn.pid=p.id
    LEFT JOIN (SELECT part_item_id pid, SUM(quantity) v,
                      MIN(COALESCE(issued_at,updated_at)) mn
                 FROM production_details
                WHERE is_deleted=0 AND is_active=1 AND quantity_minus_status='done'
                  AND material_send_production=1 GROUP BY part_item_id) prod ON prod.pid=p.id
    LEFT JOIN (SELECT d.part_item_id pid, SUM(d.quantity) v,
                      MIN(COALESCE(dc.dc_date,d.created_at)) mn
                 FROM tbl_delivery_chalan_item_details d
                 LEFT JOIN tbl_delivery_chalan dc ON dc.id=d.delivery_chalan_id
                WHERE d.is_deleted=0 AND d.is_active=1 GROUP BY d.part_item_id) del ON del.pid=p.id
    LEFT JOIN (SELECT r.part_item_id pid, SUM(r.quantity) v,
                      MIN(COALESCE(rc.dc_date,r.created_at)) mn
                 FROM tbl_returnable_chalan_item_details r
                 LEFT JOIN tbl_returnable_chalan rc ON rc.id=r.returnable_chalan_id
                WHERE r.is_deleted=0 AND r.is_active=1 GROUP BY r.part_item_id) ret ON ret.pid=p.id
    LEFT JOIN (SELECT part_item_id pid, SUM(quantity_delta) v,
                      MIN(CASE WHEN movement_type IN ('manual_addition','manual_adjustment_set')
                               THEN created_at END) mn
                 FROM tbl_item_stock_history
                WHERE is_active=1 AND is_deleted=0
                  AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
                  AND quantity_delta IS NOT NULL GROUP BY part_item_id) man ON man.pid=p.id
    WHERE p.is_active=1 AND p.is_deleted=0
      AND NOT EXISTS (SELECT 1 FROM (
              SELECT part_item_id FROM tbl_item_stock_history
               WHERE movement_type='opening_reconciliation' AND is_deleted=0
          ) already WHERE already.part_item_id = p.id)
) x
WHERE ABS(x.actual - x.ledger) >= 0.001;


-- A2. VERIFY — "Still out of sync" MUST be 0.

SELECT COUNT(*)                                                     AS `Parts checked`,
       SUM(CASE WHEN ABS(actual-ledger)<0.001 THEN 1 ELSE 0 END)    AS `In sync`,
       SUM(CASE WHEN ABS(actual-ledger)>=0.001 THEN 1 ELSE 0 END)   AS `Still out of sync`,
       SUM(CASE WHEN ledger<0 THEN 1 ELSE 0 END)                    AS `Negative balance`
  FROM (
    SELECT p.id, s.quantity AS actual,
           COALESCE(p.opening_stock,0)+COALESCE(grn.v,0)-COALESCE(prod.v,0)
         - COALESCE(del.v,0)-COALESCE(ret.v,0)+COALESCE(man.v,0) AS ledger
      FROM tbl_part_item p
      JOIN tbl_item_stock s ON s.part_item_id=p.id AND s.quantity IS NOT NULL
      LEFT JOIN (SELECT g.part_no_id pid,SUM(IFNULL(g.accepted_quantity,0)) v
                   FROM tbl_grn_po_quantity_tracking g JOIN grn_tbl gt ON gt.id=g.grn_id
                  WHERE g.is_deleted=0 AND g.is_active=1 GROUP BY g.part_no_id) grn ON grn.pid=p.id
      LEFT JOIN (SELECT part_item_id pid,SUM(quantity) v FROM production_details
                  WHERE is_deleted=0 AND is_active=1 AND quantity_minus_status='done'
                    AND material_send_production=1 GROUP BY part_item_id) prod ON prod.pid=p.id
      LEFT JOIN (SELECT part_item_id pid,SUM(quantity) v FROM tbl_delivery_chalan_item_details
                  WHERE is_deleted=0 AND is_active=1 GROUP BY part_item_id) del ON del.pid=p.id
      LEFT JOIN (SELECT part_item_id pid,SUM(quantity) v FROM tbl_returnable_chalan_item_details
                  WHERE is_deleted=0 AND is_active=1 GROUP BY part_item_id) ret ON ret.pid=p.id
      LEFT JOIN (SELECT part_item_id pid,SUM(quantity_delta) v FROM tbl_item_stock_history
                  WHERE is_active=1 AND is_deleted=0
                    AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
                    AND quantity_delta IS NOT NULL GROUP BY part_item_id) man ON man.pid=p.id
     WHERE p.is_active=1 AND p.is_deleted=0
  ) z;


-- A3. VERIFY the CO2 welding wire part specifically.
--     "Report balance" must equal "Inventory qty".

SELECT p.id AS `Part ID`, p.description AS `Description`,
       s.quantity AS `Inventory qty`,
       ROUND(COALESCE(p.opening_stock,0)
           + COALESCE((SELECT SUM(quantity_delta) FROM tbl_item_stock_history
                        WHERE part_item_id=p.id AND is_deleted=0 AND is_active=1
                          AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
                          AND quantity_delta IS NOT NULL),0)
           - COALESCE((SELECT SUM(quantity) FROM production_details
                        WHERE part_item_id=p.id AND is_deleted=0 AND is_active=1
                          AND quantity_minus_status='done' AND material_send_production=1),0),3)
           AS `Report balance`
  FROM tbl_part_item p
  LEFT JOIN tbl_item_stock s ON s.part_item_id=p.id
 WHERE p.description LIKE '%CO2 WELDING WIRE LAYER%0.80%'
   AND p.description NOT LIKE '%SS%';

-- ##  END OF PART A  —  the fix is complete at this point.            ##


-- #####################################################################
-- ##  PART B — OPTIONAL: indexes (makes the report faster).          ##
-- ##  Run these ONE AT A TIME. If one says "Duplicate key name",     ##
-- ##  that index already exists — ignore it and run the next.        ##
-- #####################################################################

-- ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_part_created` (`part_item_id`,`created_at`);
-- ALTER TABLE `tbl_item_stock_history` ADD INDEX `idx_item_stock_history_movement_type` (`movement_type`);
-- ALTER TABLE `production_details` ADD INDEX `idx_production_details_issued_at` (`issued_at`);
-- ALTER TABLE `production_details` ADD INDEX `idx_production_details_issue_predicate` (`quantity_minus_status`,`material_send_production`);


-- #####################################################################
-- ##  PART C — OPTIONAL: tidy up legacy rows.                        ##
-- ##  Cosmetic only. Does not change any report figure.              ##
-- #####################################################################

-- UPDATE `tbl_item_stock_history`
--    SET `movement_type` = 'legacy_unspecified'
--  WHERE `movement_type` IS NULL OR `movement_type` = '';
