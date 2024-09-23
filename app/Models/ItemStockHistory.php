<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStockHistory extends Model
{
    use HasFactory;
    protected $table = 'tbl_item_stock_history';
    protected $primaryKey = 'id';
}
