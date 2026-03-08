<?php

namespace App\Http\Repository\Organizations\Purchase;

use App\Models\{
    Vendors,
};

class VendorRepository
{


    public function getAll($request)
    {
        try {

            $query = Vendors::query();

            // Search
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('vendor_name', 'like', '%' . $request->search . '%')
                        ->orWhere('vendor_company_name', 'like', '%' . $request->search . '%')
                        ->orWhere('vendor_email', 'like', '%' . $request->search . '%')
                        ->orWhere('contact_no', 'like', '%' . $request->search . '%')
                        ->orWhere('gst_no', 'like', '%' . $request->search . '%');
                });
            }

            return $query->paginate(10);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request)
    {
        try {
            $vendor_data = new Vendors();
            $vendor_data->vendor_name = $request->vendor_name;
            $vendor_data->vendor_company_name = $request->vendor_company_name;
            $vendor_data->vendor_email = $request->vendor_email;
            $vendor_data->contact_no = $request->contact_no;
            $vendor_data->gst_no = $request->gst_no;
            $vendor_data->quote_no = $request->quote_no;
            $vendor_data->payment_terms = $request->payment_terms;
            $vendor_data->vendor_address = $request->vendor_address;
            $vendor_data->save();
            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            // 
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById($id)
    {
        try {
            $dataOutputByid = Vendors::find($id);
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
    {
        try {
            $vendor_data = Vendors::find($request->id);

            if (!$vendor_data) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $vendor_data->vendor_name = $request->vendor_name;
            $vendor_data->vendor_company_name = $request->vendor_company_name;
            $vendor_data->vendor_email = $request->vendor_email;
            $vendor_data->contact_no = $request->contact_no;
            $vendor_data->gst_no = $request->gst_no;
            $vendor_data->quote_no = $request->quote_no;
            $vendor_data->payment_terms = $request->payment_terms;
            $vendor_data->vendor_address = $request->vendor_address;


            $vendor_data->save();

            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }


    public function deleteById($id)
    {
        try {
            $deleteDataById = Vendors::find($id);

            if ($deleteDataById) {
                $deleteDataById->delete();
                return $deleteDataById;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
