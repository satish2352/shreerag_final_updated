<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gatepass extends Model
{
    use HasFactory;
    protected $table = 'gatepass';
    protected $primaryKey = 'id';
    protected $fillable = [
        'business_details_id', 
        'business_id', 
        'is_active', 
        'is_deleted'
    ];
}