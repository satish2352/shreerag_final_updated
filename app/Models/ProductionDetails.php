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
        'material_send_production',
        'business_id',
        'design_id',
        'production_id',
    ];

    // Corrected the relationship method
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_details_id'); 
    }
}
