<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\ItemRepository;
use App\Models\ {
    PartItem
    };
use Config;
class ItemServices
{
	protected $repo;
    public function __construct(){
        $this->repo = new ItemRepository();
    }
    public function getAll(){
        try {
            $data_output = $this->repo->getAll();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function addAll($request){
    //   try {
    //       $last_id = $this->repo->addAll($request);
   
    //       if ($last_id) {
    //           return ['status' => 'success', 'msg' => 'Data Added Successfully.'];
    //       } else {
    //           return ['status' => 'error', 'msg' => ' Data Not Added.'];
    //       }  
    //   } catch (Exception $e) {
    //       return ['status' => 'error', 'msg' => $e->getMessage()];
    //   }      
    // }

     public function addAll($request){
        try {
            $last_id = $this->repo->addAll($request);
            $path = Config::get('DocumentConstant.PART_ITEM_ADD');
            $ImageName = $last_id['ImageName'];
            uploadImage($request, 'image', $path, $ImageName);
           
            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Data Added Successfully'];
            } else {
                return ['status' => 'error', 'msg' => ' Data get Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateAll($request)
{
    try {
        // Call repository to update the main part item data
        $return_data = $this->repo->updateAll($request);     

        // If repository update fails
        if ($return_data['status'] !== 'success') {
            return [
                'status' => 'error',
                'msg' => $return_data['msg'] ?? 'Failed to update data.'
            ];
        }

        // Image upload path (from config)
        $path = Config::get('DocumentConstant.PART_ITEM_ADD');

        // Check if new image is uploaded
        if ($request->hasFile('image')) {

            // Delete old image if exists
            if (!empty($return_data['image'])) {
                $oldImagePath = Config::get('DocumentConstant.PART_ITEM_DELETE') . $return_data['image'];

                if (file_exists_view($oldImagePath)) {
                    removeImage($oldImagePath);
                }
            }

            // Generate new unique image name
            $newImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->file('image')->extension();

            // Upload new image to configured path
            uploadImage($request, 'image', $path, $newImageName);

            // Update the image field in the database (Products table)
            $product = PartItem::find($return_data['last_insert_id']);
            if ($product) {
                $product->image = $newImageName;
                $product->save();
            }
        }

        // Return success
        return [
            'status' => 'success',
            'msg' => 'Data Updated Successfully.'
        ];
    } catch (\Exception $e) {
        // Catch and return exception error
        return [
            'status' => 'error',
            'msg' => $e->getMessage()
        ];
    }
}

    // public function updateAll($request){
    //     try {
    //         $return_data = $this->repo->updateAll($request);     
    //           $path = Config::get('DocumentConstant.PART_ITEM_ADD');
    //         if ($request->hasFile('image')) {
    //             if ($return_data['image']) {
    //                 if (file_exists_view(Config::get('DocumentConstant.PART_ITEM_DELETE') . $return_data['image'])) {
    //                     removeImage(Config::get('DocumentConstant.PART_ITEM_DELETE') . $return_data['image']);
    //                 }

    //             }
    //             if ($request->hasFile('image')) {
    //                 $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->file('image')->extension();
    //               } else {
    //                 // Handle the case where 'image' key is not present in the request.
    //                 // For example, you might want to skip the file handling or return an error message.
    //             }                
    //             // $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_image.' . $request->image->extension();
    //             uploadImage($request, 'image', $path, $englishImageName);
    //             $aboutus_data = Products::find($return_data['last_insert_id']);
    //             $aboutus_data->image = $englishImageName;
    //             $aboutus_data->save();
              
    //         }   
    //         if ($return_data) {
    //             return ['status' => 'success', 'msg' => 'Data Updated Successfully.'];
    //         } else {
    //             return ['status' => 'error', 'msg' => 'Data  Not Updated.'];
    //         }  
    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //     }      
    // }
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
}