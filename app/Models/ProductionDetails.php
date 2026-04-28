<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetails extends Model
{
    use HasFactory;

    protected $table = 'production_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'business_details_id',
        'part_item_id',
        'quantity',
        'unit',
        'basic_rate',
        'items_used_total_amount',
        'material_send_production',
        'quantity_minus_status',
        'business_id',
        'design_id',
        'production_id',
        'is_deleted',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_details_id');
    }

    public function partItemRelation()
    {
        return $this->belongsTo(\App\Models\PartItem::class, 'part_item_id');
    }

    public function unitMasterRelation()
    {
        return $this->belongsTo(\App\Models\UnitMaster::class, 'unit', 'id');
    }
}
