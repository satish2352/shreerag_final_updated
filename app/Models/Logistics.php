<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    use HasFactory;
    protected $table = 'tbl_logistics';
    protected $primaryKey = 'id';
}
