<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    use HasFactory;
    protected $table = 'tbl_logistics';
    protected $primaryKey = 'id';
    // In App\Models\Logistics.php
protected $fillable = [
    'business_details_id', 
    'business_id', 
    'business_application_processes_id', 
    'quantity_tracking_id', 
    'is_approve', 
    'is_active', 
    'is_deleted'
];

}
