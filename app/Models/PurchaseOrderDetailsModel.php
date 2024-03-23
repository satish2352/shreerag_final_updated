<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailsModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_details';
    protected $primaryKey = 'id';
    protected $fillable = ['part_no', 'description', 'due_date', 'hsn_no', 'quantity', 'rate', 'amount'];

    public function design()
    {
        return $this->belongsTo(DesignModel::class, 'purchase_id');
    }

}
