<?php

namespace App\Http\Repository\Organizations\Dispatch;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Logistics,
    CustomerProductQuantityTracking
};

class AllListRepository
{
    public function getAllReceivedFromFianance()
    {
        try {

            $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_RECEIVED_FROM_FINANCE_ACCORDING_TO_LOGISTICS')];
            $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.RECEIVED_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT')];

            $data_output = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
                $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
                })
                ->leftJoin('business_application_processes as bap1', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.business_application_processes_id', '=', 'bap1.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('tbl_transport_name', function ($join) {
                    $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
                })
                ->leftJoin('tbl_vehicle_type', function ($join) {
                    $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
                })
                ->leftJoin('production', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.production_id', '=', 'production.id');
                })
                ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)

                // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                // ->distinct('businesses_details.id')
                ->select(
                    'tbl_customer_product_quantity_tracking.id',
                    'tbl_customer_product_quantity_tracking.business_details_id',
                    'businesses.title',
                    'businesses.project_name',
                    'businesses.created_at',
                    'businesses.customer_po_number',
                    'businesses_details.product_name',
                    'businesses.title',
                    'businesses_details.quantity',
                    'businesses.remarks',
                    'businesses.is_active',
                    'tbl_customer_product_quantity_tracking.completed_quantity',
                    // DB::raw('(businesses_details.quantity - tbl_customer_product_quantity_tracking.completed_quantity) AS remaining_quantity'),
                    DB::raw('(SELECT SUM(t2.completed_quantity)
    FROM tbl_customer_product_quantity_tracking AS t2
    WHERE t2.business_details_id = businesses_details.id
      AND t2.id <= tbl_customer_product_quantity_tracking.id
   ) AS cumulative_completed_quantity'),
                    DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
    FROM tbl_customer_product_quantity_tracking AS t2
    WHERE t2.business_details_id = businesses_details.id
      AND t2.id <= tbl_customer_product_quantity_tracking.id
   )) AS remaining_quantity'),
                    // DB::raw('production.updated_at AS updated_at'),
                    'production.business_id',
                    'production.id as productionId',
                    'bap1.store_material_sent_date',
                    'tbl_customer_product_quantity_tracking.updated_at',
                    'tbl_transport_name.name as transport_name',
                    'tbl_vehicle_type.name as vehicle_name',
                    'tbl_logistics.truck_no',
                    'tbl_logistics.from_place',
                    'tbl_logistics.to_place',

                )
                ->orderBy('tbl_logistics.updated_at', 'desc')
                ->get();


            return $data_output;
        } catch (\Exception $e) {

            return $e;
        }
    }
    public function getAllDispatch()
    {
        try {

            // ✅ Config values
            $array_to_be_quantity_tracking = [
                config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')
            ];

            $search = trim(request('search'));
            $perPage = Config::get('AllFileValidation.PAGINATION');

            // ✅ MAIN DATA QUERY
            $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_logistics.business_id', '=', 'businesses.id');
                })
                ->leftJoin('business_application_processes as bap1', function ($join) {
                    $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('tbl_transport_name', function ($join) {
                    $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
                })
                ->leftJoin('tbl_vehicle_type', function ($join) {
                    $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
                })

                // ✅ Dispatch (latest per logistics)
                ->join(DB::raw('
                (SELECT id, logistics_id, outdoor_no, gate_entry, remark, updated_at 
                 FROM tbl_dispatch 
                 WHERE id IN (
                     SELECT MIN(id) FROM tbl_dispatch GROUP BY logistics_id
                 )
                ) as tbl_dispatch
            '), function ($join) {
                    $join->on('tbl_logistics.id', '=', 'tbl_dispatch.logistics_id');
                })

                // ✅ Filters
                ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
                ->where('businesses.is_active', 1)
                ->where('businesses.is_deleted', 0)

                // ✅ Search
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
                            ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%");
                    });
                })

                // ✅ SELECT (IMPORTANT)
                ->select(
                    'tbl_customer_product_quantity_tracking.id',
                    'tbl_logistics.business_details_id', // ✅ REQUIRED
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'tbl_logistics.truck_no',
                    'tbl_dispatch.outdoor_no',
                    'tbl_dispatch.gate_entry',
                    'tbl_dispatch.updated_at',
                    'tbl_logistics.from_place',
                    'tbl_logistics.to_place',
                    'tbl_dispatch.remark',
                    'tbl_dispatch.updated_at',
                    'tbl_customer_product_quantity_tracking.completed_quantity',
                    DB::raw('(SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id
                            AND t2.id <= tbl_customer_product_quantity_tracking.id
                         ) AS cumulative_completed_quantity'),
                    DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
                          FROM tbl_customer_product_quantity_tracking AS t2
                          WHERE t2.business_details_id = businesses_details.id
                            AND t2.id <= tbl_customer_product_quantity_tracking.id
                         )) AS remaining_quantity'),
                )

                ->orderBy('tbl_dispatch.updated_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();


            // =====================================================
            // ✅ STEP 2: CHECK FULL COMPLETION & UPDATE STATUS
            // =====================================================

            $businessDetailsIds = $data_output->pluck('business_details_id')->filter()->unique();

            if ($businessDetailsIds->isNotEmpty()) {

                // ✅ Get only FULLY completed records
                $completedIds = DB::table('tbl_customer_product_quantity_tracking as t')
                    ->join('businesses_details as bd', 'bd.id', '=', 't.business_details_id')
                    ->whereIn('t.business_details_id', $businessDetailsIds)
                    ->select(
                        't.business_details_id',
                        DB::raw('SUM(t.completed_quantity) as total_completed'),
                        'bd.quantity as total_required'
                    )
                    ->groupBy('t.business_details_id', 'bd.quantity')
                    ->havingRaw('SUM(t.completed_quantity) = bd.quantity') // ✅ MAIN LOGIC
                    ->pluck('business_details_id');

                // ✅ Update ONLY completed ones
                if ($completedIds->isNotEmpty()) {
                    DB::table('business_application_processes')
                        ->whereIn('business_details_id', $completedIds)
                        ->where(function ($q) {
                            $q->whereNull('dispatch_status_id')
                                ->orWhere('dispatch_status_id', '!=', 1154);
                        })
                        ->update([
                            'dispatch_status_id' => 1154,
                            'updated_at' => now()
                        ]);
                }
            }

            return $data_output;
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // public function getAllDispatch()
    // {
    //     try {

    //         $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
    //         $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];

    //         $array_to_be_check_new = ['0'];
    //         $array_to_be_check_new = [NULL];
    //         $search = trim(request('search'));
    //         $perPage = Config::get('AllFileValidation.PAGINATION');

    //         $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
    //             $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
    //         })
    //             ->leftJoin('businesses', function ($join) {
    //                 $join->on('tbl_logistics.business_id', '=', 'businesses.id');
    //             })
    //             ->leftJoin('business_application_processes as bap1', function ($join) {
    //                 $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
    //             })
    //             ->leftJoin('businesses_details', function ($join) {
    //                 $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->leftJoin('tbl_transport_name', function ($join) {
    //                 $join->on('tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id');
    //             })
    //             ->leftJoin('tbl_vehicle_type', function ($join) {
    //                 $join->on('tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id');
    //             })
    //             ->join(DB::raw('(SELECT id, logistics_id, outdoor_no, gate_entry, remark, updated_at FROM tbl_dispatch WHERE id IN (SELECT MIN(id) FROM tbl_dispatch GROUP BY logistics_id)) as tbl_dispatch'), function ($join) {
    //                 $join->on('tbl_logistics.id', '=', 'tbl_dispatch.logistics_id');
    //             })
    //             ->whereIn('tbl_customer_product_quantity_tracking.quantity_tracking_status', $array_to_be_quantity_tracking)
    //             // ->whereIn('bap1.dispatch_status_id',$array_to_be_check)
    //             ->where('businesses.is_active', true)
    //             ->where('businesses.is_deleted', 0)
    //             ->when($search, function ($query) use ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('businesses.project_name', 'LIKE', "%{$search}%")
    //                         ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
    //                         ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%");
    //                 });
    //             })
    //             ->select(
    //                 'tbl_customer_product_quantity_tracking.id',
    //                 'businesses.project_name',
    //                 'businesses.created_at',
    //                 'businesses.customer_po_number',
    //                 'businesses.title',
    //                 'businesses_details.product_name',
    //                 'businesses_details.description',
    //                 'businesses_details.quantity',
    //                 'tbl_logistics.truck_no',
    //                 'tbl_dispatch.outdoor_no',
    //                 'tbl_dispatch.gate_entry',
    //                 'tbl_dispatch.remark',
    //                 'tbl_dispatch.updated_at',
    //                 'tbl_logistics.from_place',
    //                 'tbl_logistics.to_place',
    //                 'tbl_customer_product_quantity_tracking.completed_quantity',
    //                 DB::raw('(SELECT SUM(t2.completed_quantity)
    //       FROM tbl_customer_product_quantity_tracking AS t2
    //       WHERE t2.business_details_id = businesses_details.id
    //         AND t2.id <= tbl_customer_product_quantity_tracking.id
    //      ) AS cumulative_completed_quantity'),
    //                 DB::raw('(businesses_details.quantity - (SELECT SUM(t2.completed_quantity)
    //       FROM tbl_customer_product_quantity_tracking AS t2
    //       WHERE t2.business_details_id = businesses_details.id
    //         AND t2.id <= tbl_customer_product_quantity_tracking.id
    //      )) AS remaining_quantity'),


    //             )
    //             ->orderBy('tbl_dispatch.updated_at', 'desc')
    //             ->paginate($perPage)
    //             ->withQueryString();


    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    public function getAllDispatchClosedProduct()
    {
        try {
            $array_to_be_check = [config('constants.DISPATCH_DEPARTMENT.LIST_DISPATCH_COMPLETED_FROM_DISPATCH_DEPARTMENT')];
            $array_to_be_quantity_tracking = [config('constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT')];
            $search = trim(request('search'));
            $perPage = Config::get('AllFileValidation.PAGINATION');
            $data_output = Logistics::leftJoin('tbl_customer_product_quantity_tracking as tcqt1', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tcqt1.id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_logistics.business_id', '=', 'businesses.id');
                })
                ->leftJoin('business_application_processes as bap1', function ($join) {
                    $join->on('tbl_logistics.business_application_processes_id', '=', 'bap1.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
                })
                ->join(DB::raw('(SELECT id, logistics_id, business_details_id, updated_at FROM tbl_dispatch WHERE id IN (SELECT MIN(id) FROM tbl_dispatch GROUP BY logistics_id)) as tbl_dispatch'), function ($join) {
                    $join->on('tbl_logistics.id', '=', 'tbl_dispatch.logistics_id');
                })
                ->leftJoin('estimation', function ($join) {
                    $join->on('tbl_logistics.business_details_id', '=', 'estimation.business_details_id');
                })
                ->leftJoin(
                    DB::raw('(SELECT business_details_id, SUM(items_used_total_amount) as total_items_used_amount 
                     FROM production_details 
                     GROUP BY business_details_id) as pd'),
                    'tbl_dispatch.business_details_id',
                    '=',
                    'pd.business_details_id'
                )

                ->whereIn('tcqt1.quantity_tracking_status', $array_to_be_quantity_tracking)
                // ->whereIn('bap1.dispatch_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%")
                            ->orWhere('businesses_details.product_name', 'LIKE', "%{$search}%");
                    });
                })
                ->select(
                    'businesses_details.id as business_details_id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.created_at',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses_details.quantity',
                    DB::raw('MAX(estimation.total_estimation_amount) as total_estimation_amount'),
                    DB::raw('SUM(tcqt1.completed_quantity) as total_completed_quantity'),
                    DB::raw('COALESCE(MAX(pd.total_items_used_amount), 0) as total_items_used_amount'),
                    DB::raw('MAX(tbl_dispatch.updated_at) as last_updated_at')
                )


                ->groupBy(
                    'businesses_details.id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.created_at',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses_details.quantity',
                )

                ->havingRaw('SUM(tcqt1.completed_quantity) = businesses_details.quantity')
                ->orderBy('last_updated_at', 'desc') // Use the alias instead of tbl_dispatch.last_updated_at
                ->paginate($perPage)
                ->withQueryString();

            /**
             * ✅ Update dispatch_status_id = 1154
             * Only once per business_application_process
             */
            // $bapIds = $data_output->pluck('business_details_id')->unique();


            // if ($bapIds->isNotEmpty()) {
            //     DB::table('business_application_processes')
            //         ->whereIn('id', $bapIds)
            //         ->update([
            //             'dispatch_status_id' => 1154,
            //             'updated_at' => now()
            //         ]);
            // }
            $data_output->getCollection()->transform(function ($data) {
                $data->last_updated_at = $data->last_updated_at ? Carbon::parse($data->last_updated_at) : null;
                return $data;
            });
            // $data_output = $data_output->map(function ($data) {
            //     $data->last_updated_at = Carbon::parse($data->last_updated_at);
            //     return $data;
            // });

            DB::commit();


            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
