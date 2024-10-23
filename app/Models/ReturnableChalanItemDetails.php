<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnableChalanItemDetails  extends Model
{
    use HasFactory;
    protected $table = 'tbl_returnable_chalan_item_details';
    protected $primaryKey = 'id';
}
