<?php
namespace App\Http\Repository\Organizations\Inventory;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
PartItem,
UnitMaster,
HSNMaster,
GroupMaster,
ItemStock,
ItemStockHistory

};
use Config;

class InventoryRepository  {


    public function getAll(){
        try {
          $data_output = ItemStock::leftJoin('tbl_part_item', function ($join) {
            $join->on('tbl_item_stock.part_item_id', '=', 'tbl_part_item.id');
        })
        ->leftJoin('tbl_unit', function ($join) {
            $join->on('tbl_part_item.unit_id', '=', 'tbl_unit.id');
        })
        ->leftJoin('tbl_hsn', function ($join) {
            $join->on('tbl_part_item.hsn_id', '=', 'tbl_hsn.id');
        })
        ->leftJoin('tbl_group_master', function ($join) {
            $join->on('tbl_part_item.group_type_id', '=', 'tbl_group_master.id');
        })
        
        ->select(
            'tbl_item_stock.id',
            'tbl_part_item.id',
            'tbl_part_item.part_number',
            'tbl_part_item.basic_rate',
            'tbl_part_item.opening_stock',
            'tbl_part_item.description',
            'tbl_part_item.extra_description',
            'tbl_part_item.unit_id',
            'tbl_item_stock.quantity',
            'tbl_unit.name',
            'tbl_part_item.hsn_id',
            'tbl_hsn.name as hsn_name',
            'tbl_part_item.group_type_id',
            'tbl_group_master.name as group_name',
        )
        // ->whereNotNull('tbl_item_stock.quantity')
          ->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function addAll($request)
    // {   
    //     try {
    //         // Step 1: Check if the ItemStock record with the given part_item_id already exists
    //         $dataOutput = ItemStock::where('part_item_id', $request->part_item_id)->first();
    
    //         if ($dataOutput) {
    //             // Step 2: If the record exists, update the quantity
    //             // $dataOutput->quantity = $request->quantity;
    //             $dataOutput->quantity += $request->quantity;
    //         } else {
    //             // Step 3: If the record does not exist, create a new one
    //             $dataOutput = new ItemStock();
    //             $dataOutput->part_item_id = $request->part_item_id;
    //             $dataOutput->quantity = $request->quantity;
    //         }
    
    //         // Save the record (either updated or new)
    //         $dataOutput->save();
    
    //         // Step 4: Insert a new record into ItemStockHistory for tracking changes
    //         $itemStockHistory = new ItemStockHistory();
    //         $itemStockHistory->part_item_id = $dataOutput->id;  // Use the ID of the saved record
    //         $itemStockHistory->quantity = $request->quantity;
    //         $itemStockHistory->save();
    
    //         return [
    //             'status' => 'success'
    //         ];
    
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e->getMessage(),
    //             'status' => 'error'
    //         ];
    //     }
    // }
    public function addAll($request)
{
    try {
        // Step 1: Fetch the existing PartItem to get the previous opening_stock
        $partItem = PartItem::find($request->part_item_id);

        if (!$partItem) {
            throw new \Exception('Part item not found.');
        }

        // Step 2: Check if the ItemStock record with the given part_item_id already exists
        $dataOutput = ItemStock::where('part_item_id', $request->part_item_id)->first();

        if ($dataOutput) {
            // Step 3: If the record exists, check if quantity is null
            if ($dataOutput->quantity === null) {
                // If quantity is null, add opening_stock + new quantity
                $dataOutput->quantity = $partItem->opening_stock + $request->quantity;
            } else {
                // Otherwise, just add the new quantity
                $dataOutput->quantity += $request->quantity;
            }
        } else {
            // Step 4: If the record does not exist, create a new one
            $dataOutput = new ItemStock();
            $dataOutput->part_item_id = $request->part_item_id;
            $dataOutput->quantity = $partItem->opening_stock + $request->quantity;
        }

        // Save the record (either updated or new)
        $dataOutput->save();

        // Step 5: Insert a new record into ItemStockHistory for tracking changes
        $itemStockHistory = new ItemStockHistory();
        $itemStockHistory->part_item_id = $dataOutput->id;  // Use the ID of the saved record
        $itemStockHistory->quantity = $request->quantity;
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
            $dataOutputByid = ItemStock::leftJoin('tbl_part_item', function ($join) {
                $join->on('tbl_item_stock.part_item_id', '=', 'tbl_part_item.id');
            })
            ->select(
                'tbl_item_stock.id',
                'tbl_part_item.opening_stock',
                'tbl_item_stock.part_item_id',
                'tbl_part_item.description',
                'tbl_item_stock.quantity',                
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
            $return_data = array();
    
            // Fetch the existing PartItem to get the previous opening_stock
            $partItem = PartItem::find($request->id);
    
            if (!$partItem) {
                throw new \Exception('Part item not found.');
            }
    
            // Check if the ItemStock record with the given part_item_id already exists
            $itemStock = ItemStock::where('id', $request->id)->first();
    
            // if ($itemStock) {
            //     // If the record exists, update the quantity
            //     if ($itemStock->quantity === null) {
            //         // If quantity is null, initialize it with the opening_stock plus new quantity
            //         $itemStock->quantity = $partItem->opening_stock + $request->quantity;
            //     } else {
            //         // Otherwise, add the new quantity to the existing quantity
            //         $itemStock->quantity += $request->quantity;
            //     }
            // } else {
            //     // If the record does not exist, create a new one
            //     $itemStock = new ItemStock();
            //     $itemStock->part_item_id = $request->part_item_id;
            //     $itemStock->quantity = $partItem->opening_stock + $request->quantity;
            // }
    
            // Save the ItemStock record (either updated or new)
            $itemStock->quantity = $request->quantity;
            $itemStock->save();
    
            // Insert a new record into ItemStockHistory for tracking changes
            $itemStockHistory = new ItemStockHistory();
            $itemStockHistory->part_item_id = $itemStock->part_item_id;  // Use the ID of the updated or created ItemStock record
            $itemStockHistory->quantity = $request->quantity;
            $itemStockHistory->save();
    
            $return_data['data'] = $itemStock;
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