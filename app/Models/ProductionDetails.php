<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetails extends Model
{
    use HasFactory;
    protected $table = 'production_details';
    protected $primaryKey = 'id';
    protected $fillable = ['title','description', 'image'];
    
}
