<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMaster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'tbl_unit';
    protected $primaryKey = 'id';
}
