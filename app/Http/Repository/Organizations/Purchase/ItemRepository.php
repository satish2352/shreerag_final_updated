<?php
namespace App\Http\Repository\Organizations\Purchase;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
PartItem,
UnitMaster,
HSNMaster,
GroupMaster,
RackMaster,
ItemStock,
ItemStockHistory,
};
use Config;

class ItemRepository  {


    public function getAll(){
        try {
          $data_output = PartItem::leftJoin('tbl_unit', function ($join) {
            $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
        })
        ->leftJoin('tbl_hsn', function ($join) {
            $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
        })
        ->leftJoin('tbl_group_master', function ($join) {
            $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
        })
        ->leftJoin('tbl_rack_master', function ($join) {
            $join->on('tbl_part_item.rack_id', '=', 'tbl_rack_master.id');
        })
        ->select(
            'tbl_part_item.id',
            'tbl_part_item.part_number',
            'tbl_part_item.basic_rate',
            'tbl_part_item.opening_stock',
            'tbl_part_item.description',
            'tbl_part_item.extra_description',
            'tbl_part_item.unit_id',
            'tbl_unit.name',
            'tbl_part_item.hsn_id',
            'tbl_hsn.name as hsn_name',
            'tbl_part_item.group_type_id',
            'tbl_group_master.name as group_name',
            'tbl_rack_master.name as rack_name'
        )->orderBy('tbl_part_item.updated_at', 'desc')
          ->get();
  
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   
        try {
            // Create a new PartItem record
            $dataOutput = new PartItem();
            $dataOutput->part_number = $request->part_number;
            $dataOutput->description = $request->description;
            $dataOutput->unit_id = $request->unit_id;
            $dataOutput->hsn_id = $request->hsn_id;
            $dataOutput->group_type_id = $request->group_type_id;
            $dataOutput->basic_rate = $request->basic_rate;
            $dataOutput->opening_stock = $request->opening_stock;
            if ($request->has('rack_id')) {
                $dataOutput->rack_id = $request->rack_id;
            }
            if ($request->has('extra_description')) {
                $dataOutput->extra_description = $request->extra_description;
            }
           
    
            $dataOutput->save();
    
            // Retrieve the last inserted ID
            $last_insert_id = $dataOutput->id;
   
            // Insert new record into ItemStock
            $itemStock = new ItemStock();
            $itemStock->part_item_id = $last_insert_id;
            $itemStock->quantity = $dataOutput->opening_stock;
            $itemStock->save();
            // Insert new record into ItemStockHistory
            $itemStockHistory = new ItemStockHistory();
            $itemStockHistory->part_item_id = $last_insert_id;
            $itemStockHistory->quantity = $dataOutput->opening_stock;
            $itemStockHistory->save();
         
            return [
                'status' => 'success'
            ];
    
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    

    public function getById($id){
    try {
            // $dataOutputByid = PartItem::find($id);
            $dataOutputByid = PartItem::leftJoin('tbl_unit', function ($join) {
                $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
            })
            ->leftJoin('tbl_hsn', function ($join) {
                $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
            })
            ->leftJoin('tbl_group_master', function ($join) {
                $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
            })
            ->leftJoin('tbl_rack_master', function ($join) {
                $join->on('tbl_part_item.rack_id', '=', 'tbl_rack_master.id');
            })
            ->select(
                'tbl_part_item.id',
                'tbl_part_item.part_number',
                'tbl_part_item.basic_rate',
                'tbl_part_item.opening_stock',
                'tbl_part_item.description',
                'tbl_part_item.extra_description',
                'tbl_part_item.unit_id',
                'tbl_unit.id',
                'tbl_unit.name',
                'tbl_part_item.hsn_id',
                'tbl_hsn.id',
                'tbl_hsn.name as hsn_name',
                'tbl_part_item.group_type_id',
                'tbl_group_master.id',
                'tbl_group_master.name as group_name',
                'tbl_part_item.rack_id',
                 'tbl_rack_master.name as rack_name'
            )
            ->where('tbl_part_item.id', $id)
            ->first();
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

            // dd($request);
            // die();
            $return_data = array();
            $dataOutput = PartItem::find($request->id);
    
            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
    
            $dataOutput->part_number = $request->part_number;
            $dataOutput->description = $request->description;
            $dataOutput->unit_id = $request->unit_id;
            $dataOutput->hsn_id = $request->hsn_id;
            $dataOutput->group_type_id = $request->group_type_id;
            // $dataOutput->rack_id = $request->rack_id;
            $dataOutput->basic_rate = $request->basic_rate;
            if ($request->has('opening_stock')) {
                $dataOutput->opening_stock = $request->opening_stock;
            }
            // $dataOutput->opening_stock = $request->opening_stock;
            if ($request->has('rack_id')) {
                $dataOutput->rack_id = $request->rack_id;
            }
            if ($request->has('extra_description')) {
                $dataOutput->extra_description = $request->extra_description;
            }
    
            $dataOutput->save();
    
            // Retrieve the last inserted ID
            $last_insert_id = $dataOutput->id;
    
            // Check if ItemStock record exists
            $itemStock = ItemStock::where('part_item_id', $last_insert_id)->first();
                // Update existing record with the new quantity
                $itemStock->quantity = $dataOutput->opening_stock; // Set new quantity
                $itemStock->save();
                // Check if ItemStockHistory record exists
                $itemStockHistory = ItemStockHistory::where('part_item_id', $last_insert_id)->first();
                // Update existing history record with the new quantity
                $itemStockHistory->quantity = $dataOutput->opening_stock; // Set new quantity
                $itemStockHistory->save();
            
              
    
            $return_data['data'] = $dataOutput;
            $return_data['status'] = 'success';
    
            return $return_data;
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    


    public function deleteById($id){
            try {
                $deleteDataById = PartItem::find($id);
                $deleteDataById->delete();
                return $deleteDataById;
            
            } catch (\Exception $e) {
                return $e;
            }    }

}