<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GRNModel extends Model
{
    use HasFactory;
    protected $table = 'grn_tbl';
    protected $primaryKey = 'id';
}
