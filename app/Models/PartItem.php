<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartItem extends Model
{
    use HasFactory;
    protected $table = 'tbl_part_item';
    protected $primaryKey = 'id';
}
