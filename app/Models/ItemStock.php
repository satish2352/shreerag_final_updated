<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'tbl_item_stock';
    protected $primaryKey = 'id';
}
