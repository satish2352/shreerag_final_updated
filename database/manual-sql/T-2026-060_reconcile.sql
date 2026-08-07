-- =====================================================================
-- T-2026-060 — STOCK RECONCILIATION (pure SQL, no terminal needed)
-- =====================================================================
--
-- SQL equivalent of:  php artisan stock:reconcile-opening-balance
--
-- Seeds ONE "Stock Adjustment (opening reconciliation)" row per part item
-- whose computed ledger total does not match tbl_item_stock.quantity, so the
-- Stock Daily Report reconciles with the Inventory Material List.
--
-- It computes the gap LIVE at the moment you run it, so it cannot go stale.
--
-- HOW TO RUN
--   phpMyAdmin -> select the live database -> "SQL" tab.
--   STEP 1 (preview) and STEP 2 (apply) are separate — run STEP 1 first,
--   read the output, then run STEP 2 only if it looks right.
--
-- SAFE TO RE-RUN
--   Any part that already has an active 'opening_reconciliation' row is
--   skipped, so running STEP 2 twice never double-posts.
--
-- IT NEVER TOUCHES tbl_item_stock — your live stock quantities are the
-- source of truth and are left exactly as they are. This only adds
-- explanatory ledger rows so the report can see the history it lost.
--
-- Mirrors the artisan command exactly:
--   ledger = COALESCE(opening_stock,0)
--          + GRN accepted (is_deleted=0, is_active=1)
--          - production issues (is_deleted=0, is_active=1,
--                               quantity_minus_status='done',
--                               material_send_production=1)
--          - delivery challan issues (is_deleted=0, is_active=1)
--          - returnable challan issues (is_deleted=0, is_active=1)
--          + manual history deltas (movement_type IN
--              ('manual_addition','manual_adjustment_set',
--               'opening_reconciliation') AND quantity_delta IS NOT NULL)
--   delta  = tbl_item_stock.quantity - ledger      (skipped if < 0.001)
-- =====================================================================


-- =====================================================================
-- STEP 1 — PREVIEW.  Read-only. Shows exactly what STEP 2 would insert.
-- =====================================================================

SELECT
    x.part_id                        AS `Part ID`,
    x.description                    AS `Description`,
    ROUND(x.ledger, 3)               AS `Computed Balance`,
    ROUND(x.actual, 3)               AS `Actual Qty (tbl_item_stock)`,
    ROUND(x.actual - x.ledger, 3)    AS `Delta to post`
FROM (
    SELECT
        p.id          AS part_id,
        p.description AS description,
        s.quantity    AS actual,
        COALESCE(p.opening_stock, 0)
          + COALESCE(grn.v, 0)
          - COALESCE(prod.v, 0)
          - COALESCE(del.v, 0)
          - COALESCE(ret.v, 0)
          + COALESCE(man.v, 0)       AS ledger
    FROM tbl_part_item p
    JOIN tbl_item_stock s
      ON s.part_item_id = p.id AND s.quantity IS NOT NULL
    LEFT JOIN (
        SELECT g.part_no_id AS pid, SUM(IFNULL(g.accepted_quantity,0)) AS v
          FROM tbl_grn_po_quantity_tracking g
          JOIN grn_tbl gt ON gt.id = g.grn_id
         WHERE g.is_deleted = 0 AND g.is_active = 1
         GROUP BY g.part_no_id
    ) grn  ON grn.pid  = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid, SUM(quantity) AS v
          FROM production_details
         WHERE is_deleted = 0 AND is_active = 1
           AND quantity_minus_status = 'done' AND material_send_production = 1
         GROUP BY part_item_id
    ) prod ON prod.pid = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid, SUM(quantity) AS v
          FROM tbl_delivery_chalan_item_details
         WHERE is_deleted = 0 AND is_active = 1
         GROUP BY part_item_id
    ) del  ON del.pid  = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid, SUM(quantity) AS v
          FROM tbl_returnable_chalan_item_details
         WHERE is_deleted = 0 AND is_active = 1
         GROUP BY part_item_id
    ) ret  ON ret.pid  = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid, SUM(quantity_delta) AS v
          FROM tbl_item_stock_history
         WHERE is_active = 1 AND is_deleted = 0
           AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
           AND quantity_delta IS NOT NULL
         GROUP BY part_item_id
    ) man  ON man.pid  = p.id
    WHERE p.is_active = 1 AND p.is_deleted = 0
      AND NOT EXISTS (
            SELECT 1 FROM (
                SELECT part_item_id FROM tbl_item_stock_history
                 WHERE movement_type = 'opening_reconciliation' AND is_deleted = 0
            ) already WHERE already.part_item_id = p.id
      )
) x
WHERE ABS(x.actual - x.ledger) >= 0.001
ORDER BY x.part_id;


-- =====================================================================
-- STEP 2 — APPLY.  Run only after reviewing STEP 1.
-- =====================================================================

INSERT INTO tbl_item_stock_history
    (part_item_id, movement_type, quantity, quantity_delta, balance_after,
     remark, is_active, is_deleted, created_at, updated_at)
SELECT
    x.part_id,
    'opening_reconciliation',
    ROUND(x.actual - x.ledger, 3),
    ROUND(x.actual - x.ledger, 3),
    ROUND(x.actual, 3),
    CONCAT(
        'Stock Adjustment (opening reconciliation) - computed ledger total ',
        FORMAT(x.ledger, 3), ' did not match tbl_item_stock.quantity ',
        FORMAT(x.actual, 3), ' as of ', NOW(),
        '. Unrecoverable historical manual stock movements (e.g. pre-fix ',
        '"Add Stock" / "Edit Stock" entries) are assumed to account for the ',
        'difference. Seeded by T-2026-060_reconcile.sql.'
    ),
    1,
    0,
    DATE_SUB(x.earliest, INTERVAL 1 SECOND),
    NOW()
FROM (
    SELECT
        p.id          AS part_id,
        s.quantity    AS actual,
        COALESCE(p.opening_stock, 0)
          + COALESCE(grn.v, 0)
          - COALESCE(prod.v, 0)
          - COALESCE(del.v, 0)
          - COALESCE(ret.v, 0)
          + COALESCE(man.v, 0)       AS ledger,
        -- Earliest date anywhere in this part's own ledger, so the new row
        -- sorts as the first real transaction (mirrors the command).
        CASE WHEN LEAST(
                    COALESCE(p.created_at, '2099-12-31 00:00:00'),
                    COALESCE(grn.mn,       '2099-12-31 00:00:00'),
                    COALESCE(prod.mn,      '2099-12-31 00:00:00'),
                    COALESCE(del.mn,       '2099-12-31 00:00:00'),
                    COALESCE(ret.mn,       '2099-12-31 00:00:00'),
                    COALESCE(man.mn,       '2099-12-31 00:00:00')
                  ) = '2099-12-31 00:00:00'
             THEN NOW()
             ELSE LEAST(
                    COALESCE(p.created_at, '2099-12-31 00:00:00'),
                    COALESCE(grn.mn,       '2099-12-31 00:00:00'),
                    COALESCE(prod.mn,      '2099-12-31 00:00:00'),
                    COALESCE(del.mn,       '2099-12-31 00:00:00'),
                    COALESCE(ret.mn,       '2099-12-31 00:00:00'),
                    COALESCE(man.mn,       '2099-12-31 00:00:00')
                  )
        END AS earliest
    FROM tbl_part_item p
    JOIN tbl_item_stock s
      ON s.part_item_id = p.id AND s.quantity IS NOT NULL
    LEFT JOIN (
        SELECT g.part_no_id AS pid,
               SUM(IFNULL(g.accepted_quantity,0)) AS v,
               MIN(COALESCE(gt.grn_date, g.updated_at)) AS mn
          FROM tbl_grn_po_quantity_tracking g
          JOIN grn_tbl gt ON gt.id = g.grn_id
         WHERE g.is_deleted = 0 AND g.is_active = 1
         GROUP BY g.part_no_id
    ) grn  ON grn.pid  = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid, SUM(quantity) AS v,
               MIN(COALESCE(issued_at, updated_at)) AS mn
          FROM production_details
         WHERE is_deleted = 0 AND is_active = 1
           AND quantity_minus_status = 'done' AND material_send_production = 1
         GROUP BY part_item_id
    ) prod ON prod.pid = p.id
    LEFT JOIN (
        SELECT d.part_item_id AS pid, SUM(d.quantity) AS v,
               MIN(COALESCE(dc.dc_date, d.created_at)) AS mn
          FROM tbl_delivery_chalan_item_details d
          LEFT JOIN tbl_delivery_chalan dc ON dc.id = d.delivery_chalan_id
         WHERE d.is_deleted = 0 AND d.is_active = 1
         GROUP BY d.part_item_id
    ) del  ON del.pid  = p.id
    LEFT JOIN (
        SELECT r.part_item_id AS pid, SUM(r.quantity) AS v,
               MIN(COALESCE(rc.dc_date, r.created_at)) AS mn
          FROM tbl_returnable_chalan_item_details r
          LEFT JOIN tbl_returnable_chalan rc ON rc.id = r.returnable_chalan_id
         WHERE r.is_deleted = 0 AND r.is_active = 1
         GROUP BY r.part_item_id
    ) ret  ON ret.pid  = p.id
    LEFT JOIN (
        SELECT part_item_id AS pid,
               SUM(quantity_delta) AS v,
               MIN(CASE WHEN movement_type IN ('manual_addition','manual_adjustment_set')
                        THEN created_at END) AS mn
          FROM tbl_item_stock_history
         WHERE is_active = 1 AND is_deleted = 0
           AND movement_type IN ('manual_addition','manual_adjustment_set','opening_reconciliation')
           AND quantity_delta IS NOT NULL
         GROUP BY part_item_id
    ) man  ON man.pid  = p.id
    WHERE p.is_active = 1 AND p.is_deleted = 0
      AND NOT EXISTS (
            SELECT 1 FROM (
                SELECT part_item_id FROM tbl_item_stock_history
                 WHERE movement_type = 'opening_reconciliation' AND is_deleted = 0
            ) already WHERE already.part_item_id = p.id
      )
) x
WHERE ABS(x.actual - x.ledger) >= 0.001;


-- =====================================================================
-- STEP 3 — VERIFY.  Re-running STEP 1 now must return ZERO rows.
--          This also reports how many adjustments exist in total.
-- =====================================================================

SELECT COUNT(*)                       AS `Reconciliation rows now present`,
       ROUND(SUM(quantity_delta), 3)  AS `Total quantity adjusted`
  FROM tbl_item_stock_history
 WHERE movement_type = 'opening_reconciliation' AND is_deleted = 0;
