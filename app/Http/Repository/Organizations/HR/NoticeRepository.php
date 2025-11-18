<?php

namespace App\Http\Repository\Organizations\HR;

use Illuminate\Support\Facades\Session;
use App\Models\{
    Notice
};


class NoticeRepository
{

    public function getAll()
    {
        try {
            // $data_output = Notice::orderBy( 'updated_at', 'desc' )->get();
            $data_output = Notice::join('tbl_departments', 'tbl_departments.id', '=', 'tbl_notice.department_id')
                ->select(
                    'tbl_notice.id',
                    'tbl_notice.department_id',
                    'tbl_notice.title',
                    'tbl_notice.description',
                    'tbl_notice.image',
                    'tbl_departments.department_name',
                    'tbl_notice.is_active',
                )
                ->where('tbl_departments.is_deleted', 0)
                ->orderBy('tbl_notice.id', 'desc')
                ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function departmentWiseNotice()
    {
        try {
            $roleId = Session::get('role_id');
            $data_output = Notice::join('tbl_departments', 'tbl_departments.id', '=', 'tbl_notice.department_id')
                ->select(
                    'tbl_notice.id',
                    'tbl_notice.department_id',
                    'tbl_notice.title',
                    'tbl_notice.description',
                    'tbl_notice.image',
                    'tbl_departments.department_name',
                    'tbl_notice.is_active'
                )
                ->where('tbl_departments.id', $roleId)
                // ->where( 'tbl_departments.id' ) // Filter by the provided ID
                ->where('tbl_departments.is_deleted', 0)
                ->orderBy('tbl_notice.id', 'desc')
                ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function addAll( $request ) {
    //     try {
    //         $data = array();
    //         $dataOutput = new Notice();

    //         $dataOutput->department_id = $request[ 'department_id' ];
    //         $dataOutput->title = $request[ 'title' ];
    //         $dataOutput->description = $request[ 'description' ];
    //         $dataOutput->save();

    //         $last_insert_id = $dataOutput->id;

    //         $ImageName = $last_insert_id .'_' . rand( 100000, 999999 ) . '_pdf.' . $request->image->extension();

    //         $finalOutput = Notice::find( $last_insert_id );
    //         // Assuming $request directly contains the ID
    //         $finalOutput->image = $ImageName;
    //         // Save the image filename to the database
    //         $finalOutput->save();

    //         $data[ 'ImageName' ] = $ImageName;
    //         return $data;

    //     } catch ( \Exception $e ) {
    //         return [
    //             'msg' => $e,
    //             'status' => 'error'
    //         ];
    //     }
    // }
    // public function addAll($request, $department_id) {
    //     try {
    //         $data = ['ImageName' => null];
    //         $dataOutput = new Notice();

    //         $dataOutput->department_id = $department_id;  // Use department from loop
    //         $dataOutput->title = $request['title'];
    //         $dataOutput->description = $request['description'];
    //         $dataOutput->save();

    //         $last_insert_id = $dataOutput->id;

    //         $ImageName = $last_insert_id . '_' . rand(100000, 999999) . '_pdf.' . $request->image->extension();

    //         $finalOutput = Notice::find($last_insert_id);
    //         $finalOutput->image = $ImageName;
    //         $finalOutput->save();

    //         $data['ImageName'] = $ImageName;
    //         return $data;

    //     } catch (\Exception $e) {
    //         return ['msg' => $e->getMessage(), 'status' => 'error', 'ImageName' => null];
    //     }
    // }

    public function addAll($request, $department_id, $ImageName = null)
    {
        try {
            $dataOutput = new Notice();
            $dataOutput->department_id = $department_id;
            $dataOutput->title = $request['title'];
            $dataOutput->description = $request['description'];
            $dataOutput->image = $ImageName;  // Reuse the same file
            $dataOutput->save();

            return $dataOutput->id;
        } catch (\Exception $e) {
            return ['msg' => $e->getMessage(), 'status' => 'error'];
        }
    }


    public function getById($id)
    {
        try {
            $data_output = Notice::join('tbl_departments', 'tbl_departments.id', '=', 'tbl_notice.department_id')
                ->select(
                    'tbl_notice.id',
                    'tbl_notice.department_id',
                    'tbl_notice.title',
                    'tbl_notice.description',
                    'tbl_notice.image',
                    'tbl_departments.department_name',
                    'tbl_notice.is_active'
                )
                ->where('tbl_notice.id', $id)
                ->where('tbl_departments.is_deleted', 0)
                ->orderBy('tbl_notice.id', 'desc')
                ->first();

            if ($data_output) {
                return $data_output;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to get data by ID.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function updateAll($request)
    {
        try {
            $return_data = array();
            $dataOutput = Notice::find($request->id);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
            // Store the previous image names
            $previousEnglishImage = $dataOutput->image;

            // Update the fields from the request
            $dataOutput->title = $request['title'];
            $dataOutput->description = $request['description'];
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

    public function updateOne($request)
    {
        try {
            $updateOutput = Notice::find($request);
            // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the model
            if ($updateOutput) {
                $is_active = $updateOutput->is_active === '1' ? '0' : '1';
                $updateOutput->is_active = $is_active;
                $updateOutput->save();

                return [
                    'msg' => 'Data Updated Successfully.',
                    'status' => 'success'
                ];
            }
            return [
                'msg' => 'Data not Found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id)
    {
        try {
            $deleteDataById = Notice::find($id);

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
