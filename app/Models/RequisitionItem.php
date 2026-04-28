<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    use HasFactory;

    protected $table = 'requisition_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'requisition_id',
        'business_details_id',
        'part_item_id',
        'product_description',
        'required_quantity',
        'available_quantity',
        'shortage_quantity',
        'unit_id',
        'rate',
        'is_active',
        'is_deleted',
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function partItem()
    {
        return $this->belongsTo(PartItem::class, 'part_item_id');
    }

    public function unitMaster()
    {
        return $this->belongsTo(UnitMaster::class, 'unit_id');
    }
}
