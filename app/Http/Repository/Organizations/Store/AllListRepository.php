<?php

namespace App\Http\Repository\Organizations\Store;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\{
    BusinessApplicationProcesses,
    PurchaseOrdersModel,
    GRNModel
};

class AllListRepository
{
    public function getAllListDesignRecievedForMaterial()
    {
        try {
            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
            $array_to_be_check_store = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
            $array_to_be_check_store_after_quality = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
            $array_to_be_check_production = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
            $search = request()->search;
            $latestProduction = DB::table('production')->select('business_id', DB::raw('MAX(created_at) as created_at'))->groupBy('business_id');
            $perPage = Config::get('AllFileValidation.PAGINATION');

            $data_output = BusinessApplicationProcesses::leftJoinSub($latestProduction, 'production', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'production.business_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where(function ($query) use (
                    $array_to_be_check,
                    $array_to_be_check_store,
                    $array_to_be_check_store_after_quality,
                    $array_to_be_check_production
                ) {
                    $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
                        ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
                        ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
                        ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
                })
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)

                // ✅ SEARCH FILTER
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%");
                    });
                })

                ->select(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.grand_total_amount',
                    'businesses.customer_po_number',
                    'businesses.remarks',
                    'production.created_at'
                )
                ->groupBy(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.grand_total_amount',
                    'businesses.customer_po_number',
                    'businesses.remarks',
                    'production.created_at'
                )
                ->orderByRaw('MAX(business_application_processes.updated_at) DESC')
                ->paginate($perPage)
                ->withQueryString();
            // $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
            //     $join->on('business_application_processes.business_id', '=', 'production.business_id');
            // })
            //     ->leftJoin('businesses', function ($join) {
            //         $join->on('business_application_processes.business_id', '=', 'businesses.id');
            //     })
            //     ->where(function ($query) use (
            //         $array_to_be_check,
            //         $array_to_be_check_store,
            //         $array_to_be_check_store_after_quality,
            //         $array_to_be_check_production
            //     ) {
            //         $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
            //             ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
            //             ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
            //             ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
            //     })
            //     ->where('businesses.is_active', true)
            //     ->where('businesses.is_deleted', 0)
            //     ->when($search, function ($query) use ($search) {
            //         $query->where(function ($q) use ($search) {
            //             $q->where('businesses.project_name', 'LIKE', "%{$search}%")
            //                 ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
            //                 ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%")
            //                 ->orWhere('production.business_id', 'LIKE', "%{$search}%");
            //         });
            //     })

            //     ->select(
            //         'businesses.id',
            //         'businesses.project_name',
            //         'businesses.grand_total_amount',
            //         'businesses.customer_po_number',
            //         'businesses.remarks',
            //         'production.business_id',
            //         'production.updated_at',
            //         'production.created_at'
            //     )
            //     ->orderBy('business_application_processes.updated_at', 'desc')
            //     ->paginate($perPage)
            //     ->withQueryString();
            // //     ->unique('id')   //  Ensure only one row per business
            // //     ->values();      // Reset array keys

            return $data_output;


            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function getAllListDesignRecievedForMaterial()
    // {
    //     try {
    //         $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
    //         $array_to_be_check_store = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
    //         $array_to_be_check_store_after_quality = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
    //         $array_to_be_check_production = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];
    //         $search = request()->search;
    //         $latestProduction = DB::table('production')->select('business_id', DB::raw('MAX(created_at) as created_at'))->groupBy('business_id');
    //         $perPage = Config::get('AllFileValidation.PAGINATION');

    //         $data_output = BusinessApplicationProcesses::leftJoinSub($latestProduction, 'production', function ($join) {
    //             $join->on('business_application_processes.business_id', '=', 'production.business_id');
    //         })
    //             ->leftJoin('businesses', function ($join) {
    //                 $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //             })
    //             ->where(function ($query) use (
    //                 $array_to_be_check,
    //                 $array_to_be_check_store,
    //                 $array_to_be_check_store_after_quality,
    //                 $array_to_be_check_production
    //             ) {
    //                 $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
    //                     ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
    //                     ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
    //                     ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
    //             })
    //             ->where('businesses.is_active', true)
    //             ->where('businesses.is_deleted', 0)

    //             // ✅ SEARCH FILTER
    //             ->when($search, function ($query) use ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('businesses.project_name', 'LIKE', "%{$search}%")
    //                         ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
    //                         ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%");
    //                 });
    //             })

    //             ->select(
    //                 'businesses.id',
    //                 'businesses.project_name',
    //                 'businesses.grand_total_amount',
    //                 'businesses.customer_po_number',
    //                 'businesses.remarks',
    //                 'production.created_at'
    //             )
    //             ->groupBy(
    //                 'businesses.id',
    //                 'businesses.project_name',
    //                 'businesses.grand_total_amount',
    //                 'businesses.customer_po_number',
    //                 'businesses.remarks',
    //                 'production.created_at'
    //             )
    //             ->orderByRaw('MAX(business_application_processes.updated_at) DESC')
    //             ->paginate($perPage)
    //             ->withQueryString();
    //         // $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
    //         //     $join->on('business_application_processes.business_id', '=', 'production.business_id');
    //         // })
    //         //     ->leftJoin('businesses', function ($join) {
    //         //         $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //         //     })
    //         //     ->where(function ($query) use (
    //         //         $array_to_be_check,
    //         //         $array_to_be_check_store,
    //         //         $array_to_be_check_store_after_quality,
    //         //         $array_to_be_check_production
    //         //     ) {
    //         //         $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
    //         //             ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
    //         //             ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
    //         //             ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
    //         //     })
    //         //     ->where('businesses.is_active', true)
    //         //     ->where('businesses.is_deleted', 0)
    //         //     ->when($search, function ($query) use ($search) {
    //         //         $query->where(function ($q) use ($search) {
    //         //             $q->where('businesses.project_name', 'LIKE', "%{$search}%")
    //         //                 ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
    //         //                 ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%")
    //         //                 ->orWhere('production.business_id', 'LIKE', "%{$search}%");
    //         //         });
    //         //     })

    //         //     ->select(
    //         //         'businesses.id',
    //         //         'businesses.project_name',
    //         //         'businesses.grand_total_amount',
    //         //         'businesses.customer_po_number',
    //         //         'businesses.remarks',
    //         //         'production.business_id',
    //         //         'production.updated_at',
    //         //         'production.created_at'
    //         //     )
    //         //     ->orderBy('business_application_processes.updated_at', 'desc')
    //         //     ->paginate($perPage)
    //         //     ->withQueryString();
    //         // //     ->unique('id')   //  Ensure only one row per business
    //         // //     ->values();      // Reset array keys

    //         return $data_output;


    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    public function getAllListDesignRecievedForMaterialBusinessWise($business_id)
    {
        try {
            $decoded_business_id = base64_decode($business_id);
            $array_to_be_check = [config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION')];
            $array_to_be_check_store = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];
            $array_to_be_check_store_after_quality = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
            $array_to_be_check_production = [config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN')];

            // $product_dispatch = [config('constants.DISPATCH_DEPARTMENT.DISPATCH_DEPARTMENT_MARKED_DISPATCH_COMPLETED')];


            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('production.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('designs', function ($join) {
                    $join->on('production.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('production_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('estimation', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
                })
                ->where('businesses_details.business_id', $decoded_business_id)
                ->where('businesses_details.is_deleted', 0)
                ->distinct('businesses.id')
                ->where('production.is_approved_production', 1)
                // ->where('business_application_processes.dispatch_status_id', $product_dispatch)

                ->where(function ($query) use ($array_to_be_check, $array_to_be_check_store, $array_to_be_check_store_after_quality, $array_to_be_check_production) {
                    $query->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store)
                        ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check)
                        ->orWhereIn('business_application_processes.store_status_id', $array_to_be_check_store_after_quality)
                        ->orWhereIn('business_application_processes.production_status_id', $array_to_be_check_production);
                })
                ->select(
                    'business_application_processes.id',
                    'businesses.id as business_id',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.id as business_details_id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses.remarks',
                    'estimation.total_estimation_amount',
                    DB::raw('MAX(designs.id) as design_id'),
                    DB::raw('MAX(design_revision_for_prod.reject_reason_prod) as reject_reason_prod'),
                    DB::raw('MAX(designs.bom_image) as bom_image'),
                    DB::raw('MAX(designs.design_image) as design_image'),
                    DB::raw('MAX(design_revision_for_prod.bom_image) as re_bom_image'),
                    DB::raw('MAX(design_revision_for_prod.design_image) as re_design_image'),
                    DB::raw('MAX(design_revision_for_prod.remark_by_design) as remark_by_design'),
                    DB::raw('MAX(design_revision_for_prod.remark_by_estimation) as remark_by_estimation'),
                    'production.updated_at',
                    'business_application_processes.dispatch_status_id'
                )
                ->groupBy(
                    'business_application_processes.id',
                    'businesses.id',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses.remarks',
                    'production.updated_at',
                    'business_application_processes.dispatch_status_id',
                    'estimation.total_estimation_amount',
                )
                ->orderBy('production.updated_at', 'desc')
                ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentToProduction()
    {
        try {

            $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION')];

            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('production.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })

                ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'businesses.id',
                    'businesses.title',
                    'businesses.customer_po_number',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'design_revision_for_prod.reject_reason_prod',
                    'design_revision_for_prod.id as design_revision_for_prod_id',
                    'designs.bom_image',
                    'designs.design_image'

                )
                ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    /**
     * T-2026-059 (Defect 2iii + "ADDED SCOPE" header/body misattribution fix).
     *
     * Previous implementation joined businesses_details THROUGH `production`
     * (production.business_details_id = businesses_details.id) rather than directly
     * off business_application_processes.business_details_id, so any project with no
     * `production` row yielded product_name=NULL/description=NULL. Combined with a
     * groupBy(['businesses_details.product_name', 'businesses_details.description'])
     * that was NOT scoped to business_details_id/business_id/BAP, ALL such NULL/NULL
     * projects collapsed into one row — and more generally ANY two projects that
     * legitimately share the same (product_name, description) pair ALSO collapsed,
     * with MAX(requisition.id) picking one arbitrary requisition and
     * SUM(businesses_details.quantity) summing across unrelated projects. Because the
     * BOM Requisition modal's HEADER (product/customer name) came from this grouped
     * row while its BODY came from requisition_items keyed by that same arbitrary
     * MAX(requisition.id), two different projects' name and item list could be
     * wholesale mismatched (not just "some items missing" — a genuine cross-project
     * misattribution).
     *
     * Fix:
     *   1. Join businesses_details directly off business_application_processes.business_details_id.
     *   2. businesses.is_active / businesses.is_deleted moved into the JOIN's ON clause
     *      (not a top-level ->where(...)), so the LEFT JOIN genuinely stays a LEFT JOIN
     *      and never silently degrades into an INNER JOIN.
     *   3. designs is also joined with is_active/is_deleted scoped into its ON clause —
     *      defensive: multiple historical design revision rows can exist per
     *      business_details_id (see DesignModel usage elsewhere in StoreController),
     *      and an unscoped join here would fan out duplicate rows per project once the
     *      collapsing groupBy is removed.
     *   4. `production` is no longer joined at all — every column this query actually
     *      selects comes from businesses / businesses_details / requisition, and
     *      `production` was ONLY ever used as a (buggy) stepping stone to reach
     *      businesses_details; keeping it would reintroduce a fan-out risk (multiple
     *      production rows can exist per business_details_id) for zero benefit.
     *   5. groupBy is now business_application_processes.id — the true per-project row
     *      identity. Two projects can NEVER collapse into one row again, regardless of
     *      shared product_name/description, because grouping is on the BAP row itself,
     *      not on any descriptive text. Every selected column is wrapped in MAX() purely
     *      to satisfy ONLY_FULL_GROUP_BY — since the group is exactly one BAP row's own
     *      joined data, MAX() here can never blend two different projects' values.
     *   6. Multiple-requisitions-per-project note (Defect 2iii item 5): this codebase's
     *      write path (StoreController::storeShortageRequisition — `Requisition::where
     *      ('business_details_id', ...)->first()` before ever creating a new one) never
     *      creates more than one requisition per business_details_id, so
     *      MAX(requisition.id) is at most a defensive no-op here, not a collapsing
     *      mechanism — this query already surfaces "one row per requisition" in the only
     *      configuration this codebase's write path can actually produce. If a future
     *      change ever allowed >1 requisition per project, this query would need a
     *      genuine per-requisition fan-out (dropping MAX(requisition.id) in favour of a
     *      real join-and-multiply) to keep exposing every one of them.
     *   7. Pagination is preserved (->paginate($perPage) still runs against the
     *      per-project-row grouped query, not an unbounded full scan).
     *
     * Header/body consistency (bug-class fix, not just data fix): because this method's
     * output row now IS the single source of truth for BOTH the requisition_id used to
     * load items AND the product/customer name/quantity/description shown alongside it,
     * list-material-sent-to-purchase.blade.php's modal title and body — and its Print/CSV
     * handlers, which read straight out of the rendered modal DOM — can structurally never
     * diverge again; there is no remaining code path that derives the title from an
     * aggregated/grouped row while the body comes from something else.
     */
    public function getAllListMaterialSentToPurchase()
    {
        try {

            $perPage = Config::get('AllFileValidation.PAGINATION');
            $search = request()->search;

            $array_to_be_check = [
                config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')
            ];

            $query = BusinessApplicationProcesses::leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id')
                        ->where('designs.is_active', 1)
                        ->where('designs.is_deleted', 0);
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id')
                        ->where('businesses.is_active', true)
                        ->where('businesses.is_deleted', 0);
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('requisition', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
                })
                ->whereIn('business_application_processes.store_status_id', $array_to_be_check);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('businesses.project_name', 'like', "%$search%")
                        ->orWhere('businesses.customer_po_number', 'like', "%$search%")
                        ->orWhere('businesses_details.product_name', 'like', "%$search%");
                });
            }

            $data_output = $query
                ->groupBy(['business_application_processes.id'])
                ->selectRaw("
                business_application_processes.id as bap_id,
                MAX(business_application_processes.business_details_id) as business_details_id,
                MAX(businesses.id) as business_id,
                MAX(businesses.customer_po_number) as customer_po_number,
                MAX(businesses.project_name) as customer_project_name,
                MAX(businesses_details.product_name) as product_name,
                MAX(businesses.title) as title,
                MAX(businesses_details.description) as description,
                MAX(businesses_details.quantity) as quantity,
                MAX(businesses.remarks) as remarks,
                MAX(requisition.id) as requistition_id,
                MAX(requisition.bom_file) as bom_file,
                MAX(businesses.updated_at) as updated_at,
                MAX(businesses.created_at) as created_at
            ")
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function getAllListMaterialSentToPurchase()
    // {
    //     try {
    //         $perPage = Config::get('AllFileValidation.PAGINATION');
    //         $array_to_be_check = [config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE')];
    //         $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
    //             $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //         })
    //             ->leftJoin('designs', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
    //             })
    //             ->leftJoin('businesses', function ($join) {
    //                 $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //             })
    //             ->leftJoin('design_revision_for_prod', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
    //             })
    //             ->leftJoin('businesses_details', function ($join) {
    //                 $join->on('production.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->leftJoin('requisition', function ($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'requisition.business_details_id');
    //             })
    //             ->whereIn('business_application_processes.store_status_id', $array_to_be_check)
    //             ->where('businesses.is_active', true)
    //             ->where('businesses.is_deleted', 0)
    //             ->groupBy([
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //             ])
    //             ->selectRaw("
    //                 MAX(businesses.id) as business_id,
    //                 MAX(businesses.customer_po_number) as customer_po_number,
    //                 MAX(businesses.project_name) as customer_project_name,
    //                 businesses_details.product_name,
    //                 MAX(businesses.title) as title,
    //                 businesses_details.description,
    //                 SUM(businesses_details.quantity) as quantity,
    //                 MAX(businesses.remarks) as remarks,
    //                 MAX(production.business_id) as production_business_id,
    //                 MAX(production.id) as productionId,
    //                 MAX(design_revision_for_prod.reject_reason_prod) as reject_reason_prod,
    //                 MAX(design_revision_for_prod.id) as design_revision_for_prod_id,
    //                 MAX(designs.bom_image) as bom_image,
    //                 MAX(designs.design_image) as design_image,
    //                 MAX(requisition.bom_file) as bom_file,
    //                 MAX(businesses.updated_at) as updated_at,
    //                 MAX(businesses.created_at) as created_at
    //             ")
    //             ->orderBy('updated_at', 'desc')
    //             ->paginate($perPage);
    //         // ->get();

    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    public function getAllListMaterialReceivedFromQuality()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'businesses_details.id',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'business_application_processes.store_receipt_no',
                    'businesses.updated_at'
                )
                ->distinct()
                ->orderBy('businesses.updated_at', 'desc')
                ->get();

            if ($data_output->isNotEmpty()) {
                return $data_output;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPurchaseOrderBusinessWise()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $perPage = Config::get('AllFileValidation.PAGINATION');
            $search = request()->search;
            $data_output = GRNModel::leftJoin('purchase_orders', function ($join) {
                $join->on('grn_tbl.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
            })
                ->leftJoin('vendors', function ($join) {
                    $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('purchase_orders.business_details_id', '=', 'business_application_processes.business_details_id');
                })
                ->leftJoin('production_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                // ->where('businesses_details.id', $id)
                ->where('businesses_details.is_deleted', 0)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('vendors.vendor_name', 'LIKE', "%{$search}%")
                            ->orWhere('grn_tbl.grn_no_generate', 'LIKE', "%{$search}%")
                            ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%")
                            ->orWhere('purchase_orders.purchase_orders_id', 'LIKE', "%{$search}%");
                    });
                })
                ->select(

                    'grn_tbl.id',
                    'grn_tbl.grn_date',
                    'grn_tbl.grn_no_generate',
                    'grn_tbl.bill_no',
                    'business_application_processes.business_details_id',
                    'purchase_orders.purchase_orders_id',
                    'vendors.vendor_name',
                    'vendors.gst_no',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    DB::raw('MAX(production_details.material_send_production) as material_send_production'),
                    'purchase_orders.is_active'

                )
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->groupBy(
                    'grn_tbl.id',
                    'grn_tbl.grn_date',
                    'grn_tbl.grn_no_generate',
                    'grn_tbl.bill_no',
                    'business_application_processes.business_details_id',
                    'purchase_orders.purchase_orders_id',
                    'vendors.vendor_name',
                    'vendors.gst_no',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'purchase_orders.is_active'

                )
                ->orderBy('grn_tbl.id', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            return $data_output;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTracking()
    {
        try {
            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $search = request()->search;
            $perPage = Config::get('AllFileValidation.PAGINATION');
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%");
                    });
                })
                ->select(
                    'businesses_details.id',
                    'businesses.project_name',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'business_application_processes.store_receipt_no',
                    'businesses.updated_at'
                )
                // ->distinct()
                ->groupBy(
                    'businesses_details.id',
                    'businesses.project_name',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id',
                    'business_application_processes.store_receipt_no',
                    'businesses.updated_at'
                )
                ->orderBy('businesses.updated_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            return $data_output;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise()
    // {
    //     try {
    //         $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
    //         $search = request()->search;
    //         $perPage = Config::get('AllFileValidation.PAGINATION');
    //         $data_output = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
    //             ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
    //             ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
    //             ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
    //             ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
    //             ->select(
    //                 'purchase_orders.business_details_id',
    //                 'purchase_orders.purchase_orders_id',
    //                 'tbl_grn_po_quantity_tracking.grn_id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 'grn_tbl.grn_no_generate',
    //                 'vendors.vendor_name',
    //                 'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id' // GRN ID from tracking table
    //             )
    //             ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
    //             // ->where('businesses_details.id', $id)
    //             ->where('businesses_details.is_deleted', 0)
    //                if ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('businesses.project_name', 'like', "%$search%")
    //                     ->orWhere('businesses.customer_po_number', 'like', "%$search%")
    //                     ->orWhere('businesses_details.product_name', 'like', "%$search%");
    //             });
    //         }
    //             ->groupBy(
    //                 'purchase_orders.purchase_orders_id',
    //                 'tbl_grn_po_quantity_tracking.grn_id',
    //                 'purchase_orders.business_details_id',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 'vendors.vendor_name',
    //                 'grn_tbl.grn_no_generate',
    //             )
    //             ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
    //            ->paginate($perPage)
    //             ->withQueryString();
    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
    public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise($id)
    {
        try {

            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            $search = request()->search;
            $perPage = Config::get('AllFileValidation.PAGINATION');

            $data_output = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
                ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
                ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
                ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                ->where('businesses_details.id', $id)
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                ->where('businesses_details.is_deleted', 0)

                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses_details.product_name', 'like', "%{$search}%")
                            ->orWhere('vendors.vendor_name', 'like', "%{$search}%")
                            ->orWhere('purchase_orders.purchase_orders_id', 'like', "%{$search}%")
                            ->orWhere('grn_tbl.grn_no_generate', 'like', "%{$search}%");
                    });
                })

                ->select(
                    'purchase_orders.business_details_id',
                    'purchase_orders.purchase_orders_id',
                    'tbl_grn_po_quantity_tracking.grn_id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'grn_tbl.grn_no_generate',
                    'vendors.vendor_name',
                    'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
                )

                ->groupBy(
                    'purchase_orders.purchase_orders_id',
                    'tbl_grn_po_quantity_tracking.grn_id',
                    'purchase_orders.business_details_id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'vendors.vendor_name',
                    'grn_tbl.grn_no_generate'
                )

                ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            return $data_output;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
    public function getAllInprocessProductProduction()
    {
        try {
            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->where('production.store_status_quantity_tracking', 'incomplete-store')
                ->where('businesses_details.is_active', true)
                ->where('businesses_details.is_deleted', 0)
                ->groupBy(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses_details.is_active',
                    'production.business_details_id',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'businesses_details.is_active',
                    'production.business_details_id',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date',
                    DB::raw('MAX(production.updated_at) as updated_at')
                )
                ->orderBy('updated_at', 'desc')
                ->get();

            return $data_output;
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
}
