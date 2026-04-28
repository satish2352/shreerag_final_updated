<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomMaterialItem extends Model
{
    use HasFactory;

    protected $table = 'bom_material_items';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessDetails()
    {
        return $this->belongsTo(BusinessDetails::class, 'business_details_id');
    }

    public function design()
    {
        return $this->belongsTo(DesignModel::class, 'design_id');
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
