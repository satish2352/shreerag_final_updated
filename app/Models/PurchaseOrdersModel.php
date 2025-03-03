<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrdersModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_name','phone_number','tax','client_address',
        'email','gst_number','payment_terms',
        'invoice_date','items','note','total','status'
    ];

    public function PurchaseOrdersModel()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetailsModel::class, 'purchase_id', 'id');
    }
}