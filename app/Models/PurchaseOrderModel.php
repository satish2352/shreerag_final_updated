<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';

    public function purchaseOrderModel()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
