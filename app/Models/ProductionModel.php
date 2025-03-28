<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionModel extends Model
{
    use HasFactory;
    protected $table = 'production';
    protected $primaryKey = 'id';
    protected $fillable = ['title','description', 'image'];

    public function productionModel()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    
}
