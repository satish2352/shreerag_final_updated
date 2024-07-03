<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorDesk extends Model
{
    use HasFactory;
    protected $table = 'tbl_directors';
    protected $primaryKey = 'id';
    protected $fillable = ['product_id','image', 'title'];

}
