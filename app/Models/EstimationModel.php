<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimationModel extends Model
{
    use HasFactory;
    protected $table = 'estimation';
    protected $primaryKey = 'id';
    protected $fillable = ['title','description', 'image'];

    public function productionModel()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    
}
