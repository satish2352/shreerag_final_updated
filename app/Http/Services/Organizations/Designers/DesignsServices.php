<?php
namespace App\Http\Services\Organizations\Designers;
use App\Http\Repository\Organizations\Designers\DesignsRepository;
use Carbon\Carbon;
use App\Models\ {
    DesignModel
    };

use Config;
    class DesignsServices
    {
        protected $repo;
        public function __construct(){
        $this->repo = new DesignsRepository();
    }

    
    public function getAllNewRequirement(){
        try {
            $data_output = $this->repo->getAllNewRequirement();
      
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAll(){
        try {
            $data_output = $this->repo->getAll();
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
   
   
    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function updateAll($request){
    //     try {
           
    //         $last_id = $this->repo->updateAll($request);
    //     //   dd($last_id);
    //     //   die();
    //         $path = Config::get('FileConstant.DESIGNS_ADD');
    //         $designImageName = $last_id['designImageName'];
    //         $bomImageName = $last_id['bomImageName'];
    //         uploadImage($request, 'design_image', $path, $designImageName);
    //         uploadImage($request, 'bom_image', $path, $bomImageName);

    //         if ($last_id) {
    //             return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => ' Data get Not Added.'];
    //         } 
    //     } catch (Exception $e) {
    //         // If an exception occurs, return error response with the error message
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }
    // }
    public function updateAll($request){
        try {
            $last_id = $this->repo->updateAll($request);
        //  dd($last_id);
        //  die();
            $path = Config::get('FileConstant.DESIGNS_ADD');
    
            // Upload images
            foreach ($last_id['designImageNames'] as $index => $designImageName) {
                uploadImage($request, "addmore.{$index}.design_image", $path, $designImageName);
            }
    
            foreach ($last_id['bomImageNames'] as $index => $bomImageName) {
                uploadImage($request, "addmore.{$index}.bom_image", $path, $bomImageName);
            }
    // dd($last_id);
    // die();
            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
            }
        } catch (Exception $e) {
            // If an exception occurs, return error response with the error message
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
    
    
//     public function updateAll($request)
// {
//     try {
//         $businessDetails = $request->input('addmore');
//         // dd($businessDetails);
//         // die();
//         foreach ($businessDetails as $index => $detail) {
//             $dataOutput = DesignModel::where('id', $detail['edit_id'])->first();
//             if (!$dataOutput) {
//                 continue; // Skip if the record is not found
//             }

//             if ($request->hasFile("addmore.{$index}.design_image")) {
//                 $designImageName = $dataOutput->id . '_' . rand(100000, 999999) . '_design.' . $request->file("addmore.{$index}.design_image")->getClientOriginalExtension();
//                 $request->file("addmore.{$index}.design_image")->move(public_path('uploads/designs'), $designImageName);
//                 $dataOutput->design_image = $designImageName;
//             }

//             if ($request->hasFile("addmore.{$index}.bom_image")) {
//                 $bomImageName = $dataOutput->id . '_' . rand(100000, 999999) . '_bom.' . $request->file("addmore.{$index}.bom_image")->getClientOriginalExtension();
//                 $request->file("addmore.{$index}.bom_image")->move(public_path('uploads/boms'), $bomImageName);
//                 $dataOutput->bom_image = $bomImageName;
//             }

//             $dataOutput->save();
//         }

//         return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
//     } catch (Exception $e) {
//         return ['status' => 'error', 'msg' => $e->getMessage()];
//     }
// }


    public function updateReUploadDesign($request){
        try {
           
            $last_id = $this->repo->updateReUploadDesign($request);
            $path = Config::get('FileConstant.DESIGNS_ADD');
            $designImageName = $last_id['designImageName'];
            $bomImageName = $last_id['bomImageName'];
            uploadImage($request, 'design_image', $path, $designImageName);
            uploadImage($request, 'bom_image', $path, $bomImageName);

            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
            } 
        } catch (Exception $e) {
            // If an exception occurs, return error response with the error message
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}