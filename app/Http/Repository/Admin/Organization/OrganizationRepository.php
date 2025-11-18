<?php

namespace App\Http\Repository\Admin\Organization;

use App\Models\{
    OrganizationModel
};
use Illuminate\Support\Facades\Config;

class organizationRepository
{

    public function getAll()
    {
        try {
            $data_output = OrganizationModel::orderBy('updated_at', 'desc')->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request)
    {
        try {

            $dataOutput = new OrganizationModel();
            $dataOutput->company_name = $request->company_name;
            $dataOutput->email = $request->email;
            $dataOutput->gst_no = $request->gst_no;
            $dataOutput->cin_number = $request->cin_number;
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->address = $request->address;
            $dataOutput->role_id = config('constants.ROLE_ID.HIGHER_AUTHORITY');
            $dataOutput->image = 'null';
            $dataOutput->founding_date = $request->founding_date;
            $dataOutput->employee_count = $request->employee_count;
            // $dataOutput->instagram_link = $request->instagram_link;
            // $dataOutput->facebook_link = $request->facebook_link;
            // $dataOutput->twitter_link = $request->twitter_link;
            $dataOutput->website = $request->website_link;
            if ($request->has('instagram_link')) {
                $dataOutput->instagram_link = $request->instagram_link;
            }
            if ($request->has('facebook_link')) {
                $dataOutput->facebook_link = $request->facebook_link;
            }
            if ($request->has('twitter_link')) {
                $dataOutput->twitter_link = $request->twitter_link;
            }
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();

            $finalOutput = OrganizationModel::find($last_insert_id);
            $finalOutput->image = $imageName;
            $finalOutput->save();

            return [
                'ImageName' => $imageName,
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById($id)
    {
        try {
            $dataOutputByid = OrganizationModel::find($id);
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
            $return_data = array();

            $dataOutput = OrganizationModel::find($request->id);
            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
            $previousEnglishImage = $dataOutput->image;
            $dataOutput->company_name = $request->company_name;
            $dataOutput->email = $request->email;
            $dataOutput->gst_no = $request->gst_no;
            $dataOutput->cin_number = $request->cin_number;
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->address = $request->address;
            // $foundingDate = Carbon::createFromFormat( 'd/m/Y', $request->input( 'founding_date' ) )->format( 'Y-m-d' );
            $dataOutput->founding_date = $request->founding_date;
            $dataOutput->employee_count = $request->employee_count;
            $dataOutput->website = $request->website_link;
            if ($request->has('instagram_link')) {
                $dataOutput->instagram_link = $request->instagram_link;
            }
            if ($request->has('facebook_link')) {
                $dataOutput->facebook_link = $request->facebook_link;
            }
            if ($request->has('twitter_link')) {
                $dataOutput->twitter_link = $request->twitter_link;
            }

            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['image'] = $previousEnglishImage;

            return  $return_data;
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
            $deleteDataById = OrganizationModel::find($id);

            if ($deleteDataById) {
                if (file_exists_view(Config::get('DocumentConstant.ADDITIONAL_SOLUTIONS_DELETE') . $deleteDataById->image)) {
                    removeImage(Config::get('DocumentConstant.ADDITIONAL_SOLUTIONS_DELETE') . $deleteDataById->image);
                }
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
