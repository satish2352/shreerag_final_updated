<?php

namespace App\Http\Repository\Organizations\Designers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\{
    DesignModel,
    BusinessApplicationProcesses,
    DesignRevisionForProd,
    AdminView,
    BusinessDetails,
    NotificationStatus,
    EstimationModel,
};

class DesignsRepository
{

    public function getAllNewRequirement()
    { //checked
        try {
            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
            $search = trim(request('search'));
            $perPage = Config::get('AllFileValidation.PAGINATION');
            $data_output = DesignModel::leftJoin('businesses', function ($join) {
                $join->on('designs.business_id', '=', 'businesses.id');
            })
                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('designs.business_id', '=', 'business_application_processes.business_id');
                })
                ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->distinct('businesses.id')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.grand_total_amount', 'LIKE', "%{$search}%");
                    });
                })
                ->select(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.created_at',
                    'businesses.remarks',
                    'businesses.grand_total_amount',
                    'businesses.is_active',
                    'designs.business_id',
                    'businesses.updated_at'
                )
                ->orderBy('businesses.updated_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllNewRequirementBusinessWise($id)
    { //checked
        try {
            $decoded_business_id = base64_decode($id);
            $dataOutputNew = DesignModel::where('business_id', $decoded_business_id)->get();
            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
            $data_output = DesignModel::leftJoin('businesses', function ($join) {
                $join->on('designs.business_id', '=', 'businesses.id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('designs.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('designs.business_details_id', '=', 'business_application_processes.business_details_id');
                })
                ->where('business_application_processes.production_status_id', 0)
                ->where('business_application_processes.production_id', 0)
                ->where('designs.business_id', $decoded_business_id)
                ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
                ->groupBy(
                    'businesses_details.product_name',
                    'designs.business_id',
                    'designs.business_details_id',
                    'businesses_details.description',
                    'businesses_details.quantity',
                    'businesses_details.total_amount',
                    'businesses_details.business_id',
                    'business_application_processes.production_id',
                    'business_application_processes.production_status_id',
                    'designs.updated_at'
                )
                ->select(
                    'businesses_details.business_id',
                    'designs.business_id',
                    'designs.business_details_id',
                    'businesses_details.business_id',
                    'businesses_details.product_name',
                    'businesses_details.description',
                    'businesses_details.quantity',
                    'businesses_details.total_amount',
                    'business_application_processes.production_id',
                    'business_application_processes.production_status_id',
                    'designs.updated_at'
                )->orderBy('designs.updated_at', 'desc')
                ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id)
    {
        try {
            $dataOutputByid = DesignModel::find($id);

            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request)
    {  //checked
        try {
            $return_data = array();
            $edit_id = $request->business_id;

            $dataOutputNew = DesignModel::where('business_details_id', $edit_id)->first();
            if (!$dataOutputNew) {
                return [
                    'msg' => 'Design not found.',
                    'status' => 'error',
                ];
            }
            $businessDetails = BusinessDetails::where('id', $dataOutputNew->business_details_id)->first();
            if (!$businessDetails) {
                return [
                    'msg' => 'Business details not found.',
                    'status' => 'error',
                ];
            }

            $productName = $businessDetails->product_name;
            $designImageName = $dataOutputNew->design_image;
            $bomImageName = $dataOutputNew->bom_image;

            if ($request->hasFile('design_image')) {
                $formattedProductName = preg_replace('/_+/', '_', $productName);
                $designImageName = $dataOutputNew->id . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->file('design_image')->getClientOriginalExtension();
                $dataOutputNew->design_image = $designImageName;
            }

            if ($request->hasFile('bom_image')) {
                $formattedProductName = preg_replace('/_+/', '_', $productName);
                $bomImageName = $dataOutputNew->id . '_' . $formattedProductName . '_' . rand(100000, 999999) . '.' . $request->file('bom_image')->getClientOriginalExtension();
                $dataOutputNew->bom_image = $bomImageName;
            }

            $dataOutputNew->save();

            $estimation_data = EstimationModel::firstOrNew(['design_id' => $dataOutputNew->id]);

            $estimation_data->business_id = $dataOutputNew->business_id;
            $estimation_data->business_details_id = $dataOutputNew->business_details_id;
            $estimation_data->design_id = $dataOutputNew->id;
            $estimation_data->save();

            // Store design and production IDs
            $designIds[] = $dataOutputNew->id;
            $estimationIds[] = $estimation_data->id;

            $designRevisionForProdIDInsert = new DesignRevisionForProd();
            $designRevisionForProdIDInsert->business_id = $dataOutputNew->business_id;
            $designRevisionForProdIDInsert->business_details_id = $dataOutputNew->business_details_id;
            $designRevisionForProdIDInsert->design_id = $dataOutputNew->id;
            $designRevisionForProdIDInsert->estimation_id = $estimation_data->id;
            $designRevisionForProdIDInsert->reject_reason_prod = '';
            $designRevisionForProdIDInsert->remark_by_design = '';
            $designRevisionForProdIDInsert->design_image = $designImageName ?? null;
            $designRevisionForProdIDInsert->bom_image = $bomImageName ?? null;
            $designRevisionForProdIDInsert->save();

            // Update BusinessApplicationProcesses if record exists
            $business_applications = BusinessApplicationProcesses::where('design_id', $dataOutputNew->id)->get();

            foreach ($business_applications as $business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                $business_application->design_id = $designIds[0] ?? null; // Use first element if available
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_ESTIMATION_DEPT_FIRST_TIME');
                $business_application->design_send_to_estimation = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_ESTIMATION_DEPT_FIRST_TIME');
                $business_application->estimation_id = $estimation_data->id ?? null; // Use first element if available
                $business_application->off_canvas_status = 12;
                $business_application->save();
            }
            $update_data_admin['off_canvas_status'] = 12;
            $update_data_business['off_canvas_status'] = 12;
            $update_data_admin['is_view'] = '0';
            AdminView::where('business_details_id', $dataOutputNew->business_details_id)
                ->update($update_data_admin);
            NotificationStatus::where('business_details_id', $dataOutputNew->business_details_id)
                ->update($update_data_business);

            $last_insert_id = $dataOutputNew->id;
            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['design_image'] = $designImageName;
            $return_data['bom_image'] = $bomImageName;
            $return_data['product_name'] = $productName;
            return $return_data;
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update design.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    public function getUploadedDesignSendEstimation()
    { //checked
        try {
            $array_to_be_check = [
                config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_ESTIMATION_DEPT_FIRST_TIME')
            ];
            $search = trim(request('search'));
            $perPage = Config::get('AllFileValidation.PAGINATION');
            $data_output = EstimationModel::leftJoin('businesses', function ($join) {
                $join->on('estimation.business_id', '=', 'businesses.id');
            })
                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('estimation.business_id', '=', 'business_application_processes.business_id');
                })
                ->leftJoin(DB::raw('(SELECT * FROM designs d1 WHERE d1.id IN (SELECT MAX(id) FROM designs GROUP BY business_id)) as designs'), function ($join) {
                    $join->on('estimation.business_details_id', '=', 'designs.business_id');
                })
                ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('businesses.project_name', 'LIKE', "%{$search}%")
                            ->orWhere('businesses.customer_po_number', 'LIKE', "%{$search}%");
                    });
                })
                ->select(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.remarks',
                    'businesses.is_active',
                    'businesses.created_at',
                    DB::raw('MAX(estimation.updated_at) as updated_at'),
                    'estimation.business_id',
                    'designs.id as design_id',
                    'designs.design_image',
                    'designs.bom_image',
                    'designs.business_id as design_business_id'
                )
                ->groupBy(
                    'businesses.id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.remarks',
                    'businesses.is_active',
                    'businesses.created_at',
                    'estimation.business_id',
                    'designs.id',
                    'designs.design_image',
                    'designs.bom_image',
                    'designs.business_id'
                )
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            return $data_output;
        } catch (\Exception $e) {
            Log::error('Error in getAll(): ' . $e->getMessage());
            return collect(); // return an empty collection to avoid type errors
        }
    }
    public function updateReUploadDesign($request)
    { //checked
        try {
            $return_data = array();
            $edit_id = $request->design_revision_for_prod_id;
            $dataOutputNew = DesignRevisionForProd::where('id', $edit_id)->first();
            if (!$dataOutputNew) {
                return [
                    'msg' => 'Design not found.',
                    'status' => 'error',
                ];
            }
            $businessDetails = BusinessDetails::where('id', $dataOutputNew->business_details_id)->first();
            if (!$businessDetails) {
                return [
                    'msg' => 'Business details not found.',
                    'status' => 'error',
                ];
            }
            $productName = $businessDetails->product_name;
            $formattedProductName = preg_replace('/_+/', '_', $productName);
            $designRevisionForProd = DesignRevisionForProd::where('id', $request->design_revision_for_prod_id)->orderBy('id', 'desc')->first();
            if ($designRevisionForProd) {

                $designRevisionForProd->remark_by_design = $request->remark_by_design;

                $designImageName = $designRevisionForProd->id . '_' . $formattedProductName . '_' . rand(100000, 999999) . '_re_design.' . $request->design_image->getClientOriginalExtension();
                $designRevisionForProd->design_image = $designImageName;

                // T-2026-003: BOM Excel upload is optional — only process if a file was uploaded.
                $bomImageName = $designRevisionForProd->bom_image; // retain existing value by default
                if ($request->hasFile('bom_image')) {
                    $bomImageName = $designRevisionForProd->id . '_' . $formattedProductName . '_' . rand(100000, 999999) . '_re_bom.' . $request->bom_image->getClientOriginalExtension();
                    $designRevisionForProd->bom_image = $bomImageName;
                }

                $designRevisionForProd->save();
            }

            // T-2026-006: After production rejection, re-route revised design BACK TO ESTIMATION
            // (not directly to production as it was previously with 1116/off_canvas=14).
            // Estimation re-reviews → sends to owner for approval → owner accepts → estimation sends to production.
            // New status: 11131 (DESIGN_REVISED_SENT_TO_ESTIMATION), off_canvas=12 (same as first-time send to estimation).
            $business_application = BusinessApplicationProcesses::where('business_details_id', $designRevisionForProd->business_details_id)->first();

            if ($business_application) {
                DB::transaction(function () use ($business_application, $designRevisionForProd) {
                    // Route back to estimation with new traceability status 11131
                    $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_REVISED_SENT_TO_ESTIMATION');
                    $business_application->design_send_to_estimation = config('constants.DESIGN_DEPARTMENT.DESIGN_REVISED_SENT_TO_ESTIMATION');
                    // Clear any prior production_status_id so production no longer sees this item
                    $business_application->production_status_id = 0;
                    // Reset owner BOM fields so estimation's "New Design List" query (whereNull bom_estimation_send_to_owner)
                    // can pick up this revised design correctly.
                    $business_application->bom_estimation_send_to_owner = null;
                    $business_application->owner_bom_accepted = null;
                    $business_application->owner_bom_rejected = null;
                    $business_application->estimation_send_to_production = null;
                    // Use a dedicated off_canvas_status (36) for revised/corrected designs
                    // so the Estimation bell shows the correct "Corrected Design Received"
                    // notification (status 12 stays reserved for first-time new designs).
                    $business_application->off_canvas_status = 36;
                    $business_application->save();

                    AdminView::where('business_details_id', $business_application->business_details_id)
                        ->update([
                            'off_canvas_status' => 36,
                            'is_view'           => '0',
                        ]);
                    NotificationStatus::where('business_details_id', $business_application->business_details_id)
                        ->update([
                            'off_canvas_status' => 36,
                            'estimation_view'   => '0', // reset so bell icon shows notification to estimation dept
                        ]);
                });
            }

            $return_data['designImageName'] = $designImageName;
            $return_data['bomImageName'] = $bomImageName ?? null; // T-2026-003: may be null if no file uploaded
            $return_data['last_insert_id'] = $designRevisionForProd->business_id;

            return $return_data;
        } catch (\Exception $e) {

            return [
                'msg' => 'Failed to update Report Incident Crowdsourcing.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }
}
