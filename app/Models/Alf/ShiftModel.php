<?php

namespace App\Models\Alf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_shift_master';
    protected $primaryKey = 'id';
}
