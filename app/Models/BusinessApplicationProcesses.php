<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessApplicationProcesses extends Model
{
    use HasFactory;
    protected $table = 'business_application_processes';
    protected $primaryKey = 'id';

  public function businessApplicationProcesses()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
