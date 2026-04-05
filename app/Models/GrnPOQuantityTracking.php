<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnPOQuantityTracking extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'tbl_grn_po_quantity_tracking';
    protected $primaryKey = 'id';
}
