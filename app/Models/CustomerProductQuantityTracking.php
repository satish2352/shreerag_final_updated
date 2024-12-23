<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProductQuantityTracking extends Model
{
    use HasFactory;
    protected $table = 'tbl_customer_product_quantity_tracking';
    protected $primaryKey = 'id';

    public function customerProductQuantityTracking()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
