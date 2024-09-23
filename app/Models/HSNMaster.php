<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HSNMaster extends Model
{
    use HasFactory;
    protected $table = 'tbl_hsn';
    protected $primaryKey = 'id';
}
