<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimationRevisionForProd extends Model
{
    use HasFactory;
    protected $table = 'estimation_revision_for_prod';
    protected $primaryKey = 'id';
    public function designRevisionForProd()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}



