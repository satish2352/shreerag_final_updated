<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductServices extends Model
{
    use HasFactory;
    protected $table = 'product_services';
    protected $primaryKey = 'id';
    protected $fillable = ['title','image'];
}
