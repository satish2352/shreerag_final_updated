<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDetails extends Model
{
    use HasFactory;
    protected $table = 'businesses_details';
    protected $primaryKey = 'id';
    protected $fillable = ['product_name', 'description', 'quantity', 'rate', 'total_amount'];
    
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

}
