<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryChalanItemDetails extends Model
{
    use HasFactory;
    protected $table = 'tbl_delivery_chalan_item_details';
    protected $primaryKey = 'id';
}
