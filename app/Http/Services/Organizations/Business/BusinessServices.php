<?php

namespace App\Http\Services\Organizations\Business;

use App\Http\Repository\Organizations\Business\BusinessRepository;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Models\{
    Business
};

class BusinessServices
{
    protected $repo;

    protected $service;

    public function __construct()
    {
        $this->repo = new BusinessRepository();
    }

    public function getAll()
    {
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function addAll($request)
    {
        try {
            $result = $this->repo->addAll($request);
            $path = Config::get('FileConstant.BUSINESS_PDF_ADD');
            if ($request->hasFile('business_pdf')) {
                if ($result['business_pdf']) {
                    if (file_exists(Config::get('DocumentConstant.BUSINESS_PDF_DELETE') . $result['business_pdf'])) {
                        removeImage(Config::get('DocumentConstant.BUSINESS_PDF_DELETE') . $result['business_pdf']);
                    }
                }
                $englishImageName = $result['last_insert_id'] . '_' . rand(100000, 999999) . '.' . $request->business_pdf->extension();
                uploadImage($request, 'business_pdf', $path, $englishImageName);
                $slide_data = Business::find($result['last_insert_id']);
                $slide_data->business_pdf = $englishImageName;
                $slide_data->save();
            }

            if ($result['status'] === 'success') {
                return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getById($id)
    {
        try {

            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateAll($request)
    {
        try {

            $return_data = $this->repo->updateAll($request);

            // Upload path (SAME path for upload + delete)
            $path = Config::get('FileConstant.BUSINESS_PDF_ADD');

            if ($request->hasFile('business_pdf')) {

                /* ===============================
               DELETE OLD PDF
            =============================== */
                if (!empty($return_data['business_pdf'])) {

                    $oldFilePath = $path . $return_data['business_pdf'];

                    if (file_exists_view($oldFilePath)) {
                        removeImage($oldFilePath);
                    }
                }

                /* ===============================
               NEW FILE NAME
            =============================== */
                $englishImageName =
                    $return_data['last_insert_id']
                    . '_' . time()
                    . '.'
                    . $request->file('business_pdf')->extension();

                /* ===============================
               UPLOAD NEW PDF
            =============================== */
                uploadImage(
                    $request,
                    'business_pdf',
                    $path,
                    $englishImageName
                );

                /* ===============================
               UPDATE DATABASE
            =============================== */
                $business = Business::find($return_data['last_insert_id']);
                $business->business_pdf = $englishImageName;
                $business->save();
            }

            /* ===============================
           RETURN RESPONSE
        =============================== */
            if ($return_data['status'] === 'success') {

                return [
                    'status' => 'success',
                    'msg' => $return_data['msg'],
                    'last_insert_id' => $return_data['last_insert_id'] ?? null,
                    'total_amount' => $return_data['total_amount'] ?? 0,
                ];
            }

            return [
                'status' => 'error',
                'msg' => $return_data['msg'],
                'error' => $return_data['error'] ?? null,
            ];
        } catch (\Exception $e) {

            return [
                'status' => 'error',
                'msg' => 'Something went wrong in service.',
                'error' => $e->getMessage()
            ];
        }
    }
    // public function updateAll($request)
    // {
    //     try {
    //         $return_data = $this->repo->updateAll($request);
    //         $path = Config::get('FileConstant.BUSINESS_PDF_ADD');

    //         if ($request->hasFile('business_pdf')) {

    //             // 🔥 DELETE OLD PDF
    //             if (!empty($return_data['business_pdf'])) {

    //                 if (file_exists_view(
    //                     Config::get('DocumentConstant.BUSINESS_PDF_DELETE')
    //                         . $return_data['business_pdf']
    //                 )) {

    //                     removeImage(
    //                         Config::get('DocumentConstant.BUSINESS_PDF_DELETE')
    //                             . $return_data['business_pdf']
    //                     );
    //                 }
    //             }

    //             // 🔥 NEW FILE NAME
    //             $englishImageName =
    //                 $return_data['last_insert_id'] . '_' . time() . '.pdf';

    //             // 🔥 UPLOAD NEW FILE
    //             uploadImage($request, 'business_pdf', $path, $englishImageName);

    //             // 🔥 UPDATE DB
    //             Business::where('id', $return_data['last_insert_id'])
    //                 ->update([
    //                     'business_pdf' => $englishImageName
    //                 ]);
    //         }

    //         if ($return_data['status'] === 'success') {
    //             return [
    //                 'status' => 'success',
    //                 'msg' => $return_data['msg'],
    //                 'last_insert_id' => $return_data['last_insert_id'] ?? null,
    //                 'total_amount' => $return_data['total_amount'] ?? 0,
    //             ];
    //         } else {
    //             return [
    //                 'status' => 'error',
    //                 'msg' => $return_data['msg'],
    //                 'error' => $return_data['error'] ?? null,
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'msg' => 'Something went wrong in the service.',
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }
    public function deleteByIdAddmore($id)
    {
        try {
            $deleted = $this->repo->deleteByIdAddmore($id);

            if ($deleted) {
                return [
                    'status' => 'success',
                    'msg' => 'Product row deleted successfully.'
                ];
            }

            return [
                'status' => 'error',
                'msg' => "Record not found or already deleted (ID = $id)"
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg'    => $e->getMessage()
            ];
        }
    }




    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function acceptEstimationBOM($id)
    {
        try {
            $acceptPurchaseOrder = $this->repo->acceptEstimationBOM($id);

            return $acceptPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function addRejectedEstimationBOM($request)
    {
        try {
            $rejectedPurchaseOrder = $this->repo->addRejectedEstimationBOM($request);

            return $rejectedPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function acceptPurchaseOrder($id, $business_id)
    {
        try {
            $acceptPurchaseOrder = $this->repo->acceptPurchaseOrder($id, $business_id);

            return $acceptPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function rejectedPurchaseOrder($id, $business_id)
    {
        try {
            $rejectedPurchaseOrder = $this->repo->rejectedPurchaseOrder($id, $business_id);
            return $rejectedPurchaseOrder;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptPurchaseOrderPaymentRelease($id, $business_id)
    {
        try {
            $acceptPurchaseOrderPaymentRelease = $this->repo->acceptPurchaseOrderPaymentRelease($id, $business_id);
            return $acceptPurchaseOrderPaymentRelease;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}
