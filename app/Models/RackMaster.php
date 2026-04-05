<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackMaster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'tbl_rack_master';
    protected $primaryKey = 'id';
}
