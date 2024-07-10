<?php
namespace App\Http\Services\Organizations\Business;

use App\Http\Repository\Organizations\Business\BusinessRepository;
use Carbon\Carbon;

// use App\Models\ {
//     DesignModel
//     }
;

use Config;

class BusinessServices
{
    protected $repo;

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
            if ($result['status'] === 'success') {
                return ['status' => 'success', 'msg' => 'This business send to Design Department Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Failed to Add Data.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    //     public function addAll( $request )
//  {
//         try {
//             $last_id = $this->repo->addAll( $request );
//             $path = Config::get( 'DocumentConstant.ORGANIZATION_ADD' );
//             $ImageName = $last_id[ 'ImageName' ];

    //             uploadImage( $request, 'image', $path, $ImageName );

    //             if ( $last_id ) {
//                 return [ 'status' => 'success', 'msg' => 'Data Added Successfully.' ];
//             } else {
//                 return [ 'status' => 'error', 'msg' => ' Data get Not Added.' ];
//             }

    //         } catch ( Exception $e ) {
//             return [ 'status' => 'error', 'msg' => $e->getMessage() ];
//         }

    //     }

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
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Data  Not Updated.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }

    // public function updateAll( $request ) {
    //     try {
    //         $return_data = $this->repo->updateAll( $request );

    //         $path = Config::get( 'DocumentConstant.ORGANIZATION_ADD' );
    //         if ( $request->hasFile( 'image' ) ) {
    //             if ( $return_data[ 'image' ] ) {
    //                 if ( file_exists_view( Config::get( 'DocumentConstant.ORGANIZATION_DELETE' ) . $return_data[ 'image' ] ) ) {
    //                     removeImage( Config::get( 'DocumentConstant.ORGANIZATION_DELETE' ) . $return_data[ 'image' ] );
    //                 }

    //             }
    //             if ( $request->hasFile( 'image' ) ) {
    //                 $englishImageName = $return_data[ 'last_insert_id' ] . '_' . rand( 100000, 999999 ) . '_video.' . $request->file( 'image' )->extension();

    //                 // Rest of your code...
    //             } else {
    //                 // Handle the case where 'image' key is not present in the request.
    //                 // For example, you might want to skip the file handling or return an error message.
    //             }

    //             // $englishImageName = $return_data[ 'last_insert_id' ] . '_' . rand( 100000, 999999 ) . '_image.' . $request->image->extension();
    //             uploadImage( $request, 'image', $path, $englishImageName );
    //             $slide_data = AboutUs::find( $return_data[ 'last_insert_id' ] );
    //             $slide_data->image = $englishImageName;
    //             $slide_data->save();
    //         }

    //         if ( $return_data ) {
    //             return [ 'status' => 'success', 'msg' => 'Data Updated Successfully.' ];
    //         } else {
    //             return [ 'status' => 'error', 'msg' => 'Data  Not Updated.' ];
    //         }

    //     } catch ( Exception $e ) {
    //         return [ 'status' => 'error', 'msg' => $e->getMessage() ];
    //     }

    // }

    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            // dd( $delete );
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }
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

    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }

}