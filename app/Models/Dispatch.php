<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;
    protected $table = 'tbl_dispatch';
    protected $primaryKey = 'id';
    // In App\Models\Dispatch.php
protected $fillable = [
    'business_details_id', 
    'business_id', 
    'logistics_id', 
    'business_application_processes_id', 
    'quantity_tracking_id',
    'is_approve', 
    'is_active', 
    'is_deleted'
];

}
