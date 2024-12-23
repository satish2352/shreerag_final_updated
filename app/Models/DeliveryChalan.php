<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChalan extends Model
{
    use HasFactory;
    protected $table = 'tbl_delivery_chalan';
    protected $primaryKey = 'id';

    public function deliveryChalan()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
