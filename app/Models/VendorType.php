<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
    use HasFactory;
    protected $table = 'tbl_vendor_type';
    protected $primaryKey = 'id';
}
